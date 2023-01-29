<?php

namespace app\controllers;
use app\models\GetAccountForm;
use app\models\LimitForm;
use app\models\BigAccountsType;
use app\models\User;
use app\models\UserGroupFarm;
use app\models\UserLimit;
use app\models\UserSearch;
use yii\web\NotFoundHttpException;
use Yii;


/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends BasicController
{
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionOut_in_arhive($id=false)
    {
        if ($id && User::find()->where(['id'=>$id])->exists()) {
            $model_user = User::findOne($id);
            $model_user->archive = 0;
            $model_user->save();
        }
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionUgf_index()
    {
        return $this->render('ugf_index.php');
    }

    public function actionUgf_list()
    {
        $this->init_ajax();
        $this->error = 'no';
        $this->msg = 'Данные успешно загружены';

        $models = UserGroupFarm::find()->all();

        $this->data = $this->renderAjax('ugf_list_rows',['models'=>$models]);

        return $this->out();

    }

    /**
     * Загрузка модели для редактирования
     */
    public function actionUgf_get_edit()
    {
        $this->init_ajax();
        $this->error = "yes";

        $id = (Yii::$app->request->post('id') ? Yii::$app->request->post('id') : FALSE );
        if ($id && UserGroupFarm::find()->where(['id'=>$id])->exists()) {
            $model = UserGroupFarm::findOne($id);
            $this->data = $this->renderAjax('ugf_edit_form',['model'=>$model]);
            $this->error = "no";
            $this->msg = 'Модель '.$id.' успешно загружена!';
        } else {
            $model = new UserGroupFarm();
            $this->data = $this->renderAjax('ugf_edit_form',['model'=>$model]);
            $this->error = "no";
            $this->msg = 'Форма под новую модель успешно загружена!';
        }

        return $this->out();
    }

    /**
     * @return array
     * @throws \yii\db\StaleObjectException
     */
    public function actionUgf_save_form()
    {
        $this->init_ajax();
        $this->error = "yes";

        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            $id = ($post && isset($post['params']['UserGroupFarm']['id'])) ? $post['params']['UserGroupFarm']['id'] : FALSE;
            $model = ($id && UserGroupFarm::find()->where(['id'=>$id])->exists()) ? UserGroupFarm::findOne($id) : new UserGroupFarm();

            //отдельно выделим пользователей которые нужно сохранить если они удалены
            if ($model->team_users_id!='') {
                foreach ($model->team_users_id as $_u) {
                    if (User::find()->where(['id'=>$_u,'archive'=>1])->exists()) {
                        $safe_users[] = $_u;
                    }
                }
                unset($_u);
            }

            $model->load_ajax($post['params']);


            //сохраняем пользователей которые были удалены archive = 1;
            $mu = ($model->team_users_id) ? $model->team_users_id : [];
            if ($safe_users && is_array($safe_users)) foreach ($safe_users as $u) {
                $mu[] = $u;
            }
            $model->team_users_id = $mu;


                if ($model->validate()) {
                    $model->save();
                    if (isset($post['params']['UserGroupFarm']['team_users'])) {
                        $model->team_users_id = $post['params']['UserGroupFarm']['team_users_id'];
                    }

                    $this->error = "no";
                    $this->msg = "";
                    $this->data = "Запись успешно сохранена";
                } else {
                    $this->error = "yes";
                    $this->msg = $this->renderAjax('ugf_edit_form',$model);
                }

        }

        return $this->out();
    }

    /**
     * @return array
     * @throws \yii\db\StaleObjectException
     */
    public function  actionUgf_del()
    {
        $this->init_ajax();
        $this->error = 'no';
        $this->data = 'Ошибка загрузки данных!';
        $out_id = [];
        $post = $post = Yii::$app->request->post();

        if (Yii::$app->request->isAjax) {
            if (isset($post['checkbox']) && is_array($post['checkbox']) && count($post['checkbox'])>=1){
                foreach ($post['checkbox'] as $ch) {
                    if (isset($ch) && UserGroupFarm::find()->where(['id'=>$ch])->exists()) {
                        $model_fa = UserGroupFarm::findOne($ch);
                        $out_id []= $model_fa->id;
                        $model_fa->delete();
                    }
                }
            }
            if (isset($post['id']) && UserGroupFarm::find()->where(['id'=>$post['id']])->exists()) {
                $model_fa = UserGroupFarm::findOne($post['id']);
                $model_fa->delete();
            }
        }
        $this->data = $out_id;
        return $this->out();
    }

    public function actionGet_limit_from_modal_ajax()
    {
        $this->init_ajax();
        $model = new LimitForm();
        $this->error = 'no';
        $this->data = $this->renderAjax('limit_form_modal',['model'=>$model]);
        return $this->out();
    }

    public function actionGet_limit_form_ajax()
    {
        $this->init_ajax();
        $model = new LimitForm();
        $this->error = 'no';
        $this->data = $this->renderAjax('limit_form',['model'=>$model]);
        return $this->out();
    }

    public function actionGet_limit_data_ajax()
    {
        $this->init_ajax();
        $post = Yii::$app->request->post();
        $id = $post['id'];
        $models = UserLimit::find()->where(['user_id'=>$id])->all();
        $this->error = 'no';
        $this->data = $this->renderAjax('limit_list',['models'=>$models]);
        return $this->out();
    }

    /**
     * Удалить лимит по контретному пользователю-типу
     *
     * @return array
     * @throws \yii\db\StaleObjectException
     */
    public function actionDel_limit_data_ajax()
    {
        $this->init_ajax();
        $this->error = 'yes';
        $this->msg = 'Неопределенная ошибка!';
        $post = Yii::$app->request->post();
        if (UserLimit::find()->where(['id'=>$post['limit_id'],'user_id'=> $post['user_id']])->exists()) {
            $model = UserLimit::findOne($post['limit_id']);
            $model->delete();
            $this->msg = 'Запись успешно удалена';
            $this->error = 'no';
            $this->data = $this->renderAjax('limit_list',['models'=>UserLimit::find()->where(['user_id'=>$post['user_id']])->all()]);
        }
        return $this->out();
    }

    /**
     * Добавить/изменить лимит по контретному пользователю-типу
     *
     * @return array
     */
    public function actionSet_limit_data_ajax()
    {
        $this->init_ajax();
        $this->error = 'yes';
        $this->msg = 'Неопределенная ошибка!';
        $post = Yii::$app->request->post();
        $model = new LimitForm();
        $model->load_ajax($post['params']);
        if ($model->validate()) {
            $model->limit_one_save($post['user_id']);
            $this->error = 'no';
            $this->msg = 'Запись успешно произведена!';
            $this->data['list'] = $this->renderAjax('limit_list',['models'=>UserLimit::find()->where(['user_id'=>$post['user_id']])->all()]);
            $this->data['form'] = $this->renderAjax('limit_form',['model'=>new LimitForm()]);;
            return $this->out();
        }
        $this->error = 'yes';
        $this->msg = $this->renderAjax('limit_form',['model'=>$model]);
        return $this->out();
    }

    /**
     * Сохранение лимитов через модальное окно
     *
     * @return array
     */
    public function actionSet_limit_from_modal_ajax()
    {
        $this->init_ajax();
        $this->error = "yes";
        $this->msg = "Неопределенная ошибка!";
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            $model = new LimitForm();
            $model->load_ajax($post['params']);
            if ($model->validate()) {
                $model->limit_save();
                $this->error = 'no';
                $this->msg = 'Лимиты успешно установлены!';
                return $this->out();
            }
        }
        $this->error = 'yes';
        $this->msg = $this->renderAjax('limit_form_modal',['model'=>$model]);
        return $this->out();

    }

    public function actionCreate()
    {
        $model = new User();

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return Yii::$app->response->redirect(['user/update/'.$model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {

            if ($model->save()) {
                return Yii::$app->response->redirect(['user/update/'.$model->id]);
            }

        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        if (User::find()->where(['id'=>$id])->exists()) {
            $model = User::findOne($id);

            if ($model->login != 'admin') {
                $model->archive = 1;
                Yii::$app->db->createCommand()->delete(Yii::$app->session->sessionTable, ['user_id' => $id])->execute();
                $model->save();
            }
        }


        return Yii::$app->response->redirect(['user/index']);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionChange_pass_ajax()
    {
        $this->init_ajax();
        $this->error = 'yes';
        $post = Yii::$app->request->post();
        if (trim($post['passwd1']) == trim($post['passwd2']) && trim($post['passwd1'])!='' && trim($post['id'])!='' && is_numeric(trim($post['id'])) && User::find()->where(['id'=>$post['id']])->exists()) {
            $model = User::findOne($post['id']);
            $model->setPassword($post['passwd1']);
            $model->save();
            $this->error = 'no';
            $this->msg = '<div class="alert alert-success" role="alert">Пароль успешно изменен!</div>';
        } else {
            $this->msg = '<div class="alert alert-danger" role="alert">Ошибка! Повторите ввод!</div>';
        }

        return $this->out();
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return  Yii::$app->response->redirect(['user/login'],302);
    }


    public function actionSet_limits_king()
    {
        $this->init_ajax();
        $this->error = 'no';
        $id = Yii::$app->request->post('id');
        $limit = Yii::$app->request->post('limit');
        if ($limit && $id && User::find()->where(['id'=>$id])->exists()) {
            $model =(UserLimit::find()->where(['user_id'=>$id,'big_accounts_type_id'=>1])->exists()) ? UserLimit::findOne(['user_id'=>$id,'big_accounts_type_id'=>1]) : new UserLimit();
            $model->lim = (int)$limit;
            if ($model->isNewRecord) {
                $model->user_id = $id;
                $model->big_accounts_type_id = 1;
                $model->not_use = 0;
            }
            $model->save();
        }
        return $this->out();
    }

    /*
    public function actionSet_limits_sk20()
    {
        $this->init_ajax();
        $this->error = 'no';
        $id = Yii::$app->request->post('id');
        $limit = Yii::$app->request->post('limit');
        if ($limit && $id && User::find()->where(['id'=>$id])->exists()) {
            $model =(UserLimit::find()->where(['user_id'=>$id,'big_accounts_type_id'=>8])->exists()) ? UserLimit::findOne(['user_id'=>$id,'big_accounts_type_id'=>8]) : new UserLimit();
            $model->lim = $limit;
            $model->big_accounts_type_id = 8;
            $model->save();
        }
        return $this->out();
    }
    */
    public function actionSet_limits_sk()
    {
        $this->init_ajax();
        $this->error = 'no';
        $id = Yii::$app->request->post('id');
        $limit = Yii::$app->request->post('limit');
        if ($limit && $id && User::find()->where(['id'=>$id])->exists()) {
            $model =(UserLimit::find()->where(['user_id'=>$id,'big_accounts_type_id'=>6])->exists()) ? UserLimit::findOne(['user_id'=>$id,'big_accounts_type_id'=>6]) : new UserLimit();
            $model->user_id = $id;
            $model->lim = $limit;
            $model->big_accounts_type_id = 6;
            $model->save();
        }
        return $this->out();
    }

    public function actionSet_limits_pzrd()
    {
        $this->init_ajax();
        $this->error = 'no';
        $id = Yii::$app->request->post('id');
        $limit = Yii::$app->request->post('limit');
        if ($limit && $id && User::find()->where(['id'=>$id])->exists()) {
            $model =(UserLimit::find()->where(['user_id'=>$id,'big_accounts_type_id'=>7])->exists()) ? UserLimit::findOne(['user_id'=>$id,'big_accounts_type_id'=>7]) : new UserLimit();
            $model->user_id = $id;
            $model->lim = $limit;
            $model->big_accounts_type_id = 7;
            $model->save();
        }
        return $this->out();
    }
    public function actionSet_limits_fp()
    {
        $this->init_ajax();
        $this->error = 'no';
        $id = Yii::$app->request->post('id');
        $limit = Yii::$app->request->post('limit');
        if ($limit && $id && User::find()->where(['id'=>$id])->exists()) {
            $model =(UserLimit::find()->where(['user_id'=>$id,'big_accounts_type_id'=>100])->exists()) ? UserLimit::findOne(['user_id'=>$id,'big_accounts_type_id'=>100]) : new UserLimit();
            $model->user_id = $id;
            $model->lim = $limit;
            $model->big_accounts_type_id = 100;
            $model->save();
        }
        return $this->out();
    }



    public function actionGet_form_limits_king()
    {
        $this->init_ajax();
        $this->error = 'no';
        $models = User::find()->where(['user_type_id'=>3,'archive'=>0])->all();
        $this->data = $this->renderAjax('form_limits_king',['models'=>$models]);
        return $this->out();
    }

}
