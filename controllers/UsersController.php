<?php

namespace app\controllers;
use app\models\User;
use yii\data\Pagination;
use Yii;
use app\models\UserCurrent;

class UsersController extends BasicController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionGet_list()
    {
        $this->init_ajax();
        $this->error = 'no';
        $this->msg = 'Данные успешно загружены';

        $post = Yii::$app->request->post();

        $model = UserCurrent::find();

        $model = UserCurrent::load_post($model,$post);

        //пагинация

        $page_limit = ($post['page_limit'] ? $post['page_limit'] : 25);
        $page_post =  ($post['page_post'] ? $post['page_post'] : 0);
        $sort_post =  ($post['sort_post'] ? $post['sort_post'] : 0);

        $pages = FALSE;
        if ($model) {
            $models_pages = clone $model;
        }

        $page_size = (trim($page_limit)) ? $page_limit : FALSE;
        if ($page_size) {
            //выбранная текущая страница минус один
            $page = $page_post;
            $countQuery  = clone $models_pages;
            //общее колличество записей (попавших в выборку)
            $cnt = $countQuery->count();

            $pages = new Pagination(['totalCount' => $cnt,'pageSize'=>$page_size]);
            $pages->pageSizeParam  = false;
            $pages->forcePageParam = false;

            $page = ($cnt < $page_size*$page) ? 0 : $page;
            $pages->setPage($page);
        }

        //лимиты
        if ($model && $page_size && $pages) {
            $models = $model->offset($pages->offset)->limit($pages->limit);
        } else {
            $models = $model;
        }


        //сортировка
        if (isset($sort_post) && $sort_post!='') {
            $models = $models->orderBy($sort_post)->all();
        } else {
            $models = $models->orderBy(' id DESC ')->all();
        }

        $this->data = $this->renderAjax('list_rows',[
            'models'=>$models,
            'pages' => $pages,
        ]);

        return $this->out();
    }

    /**
     * Формирование фильтра
     *
     * @return array
     */
    public function actionGet_filter()
    {
        $this->init_ajax();
        $this->error = 'no';
        $this->msg = 'Данные успешно загружены';

        $post = Yii::$app->request->post();

        $page_limit = ($post['page_limit'] ? $post['page_limit'] : 25);
        $page_post = ($post['page_post'] ? $post['page_post'] : 0);

        $models = UserCurrent::find()->all();

        $data_select = [];

        if ($models) foreach ($models as $m){
            /*
            $data_select['status'][$m->status] = $m->status;
            */
        }

        $this->data = $this->renderAjax('filter',[
            'data_select'=>$data_select,
            'page_limit'=>$page_limit,
            'page_post' =>$page_post,
            'post' => $post,
        ]);
        return $this->out();
    }

    public function  actionDel()
    {
        $this->init_ajax();
        $this->error = 'no';
        $this->data = 'Ошибка загрузки данных!';
        $out_id = [];
        $post = $post = Yii::$app->request->post();

        if (Yii::$app->request->isAjax) {

            if (isset($post['checkbox']) && is_array($post['checkbox']) && count($post['checkbox'])>=1){
                foreach ($post['checkbox'] as $ch) {

                    if (isset($ch) && UserCurrent::find()->where(['id'=>$ch])->exists()) {
                        $model_del = UserCurrent::findOne($ch);
                        $model_del->archive = 1;
                        $model_del->save();
                    }

                }
            }

            if (isset($post['id']) && UserCurrent::find()->where(['id'=>$post['id']])->exists()) {
                $model_del = UserCurrent::findOne($post['id']);
                $model_del->archive = 1;
                $model_del->save();
            }

        }
        $this->data = $out_id;
        return $this->out();
    }

    /**
     * Восстановление пользователя
     *
     * @return array
     */
    public function  actionRecovery()
    {
        $this->init_ajax();
        $this->error = 'no';
        $this->data = 'Ошибка загрузки данных!';

        $post = $post = Yii::$app->request->post();

        if (Yii::$app->request->isAjax) {
            if (isset($post['id']) && UserCurrent::find()->where(['id'=>$post['id']])->exists()) {
                $model_del = UserCurrent::findOne($post['id']);
                $model_del->archive = 0;
                $model_del->save();
            }
        }
        return $this->out();
    }

    /**
     * Загрузка модели для редактирования
     */
    public function actionGet_edit()
    {
        $this->init_ajax();
        $this->error = "yes";

        $id = (Yii::$app->request->post('id') ? Yii::$app->request->post('id') : FALSE );
        $model = ($id && UserCurrent::find()->where(['id'=>$id])->exists()) ? UserCurrent::findOne($id) : new UserCurrent();
        $this->data = $this->renderAjax('edit_form',['model'=>$model,'disable_all'=>FALSE]);
        $this->error = "no";
        $this->msg = 'Модель '.$id.' успешно загружена!';


        return $this->out();
    }

    public function actionSave_form()
    {
        $this->init_ajax();
        $this->error = "yes";

        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            $id = ($post && isset($post['params']['UserCurrent']['id'])) ? $post['params']['UserCurrent']['id'] : FALSE;
            $model = ($id && UserCurrent::find()->where(['id'=>$id])->exists()) ? UserCurrent::findOne($id) : new UserCurrent();
            $model->load_ajax($post['params']);

            if (isset($post['params']['UserCurrent']['roles_view'])) {
                $model->roles_view = $post['params']['UserCurrent']['roles_view'];
            }

            if ($model->validate()) {
                if ($model->id != 1) {
                    if ($model->isNewRecord) {
                        $model->archive = 0;
                        $model->setAuthKey();
                    }
                    $model->save();
                }
                $this->error = "no";
                $this->msg = "";
                $this->data = "Запись успешно сохранена";
            } else {
                $this->error = "yes";
                $this->msg = $this->renderAjax('edit_form',['model'=>$model]);
            }

        }

        return $this->out();
    }

    /*
     * Получаем Login рользователя из формы изменения пароля
     */
    public function actionPasswd_get()
    {
        $this->init_ajax();
        $this->error = "yes";
        $this->msg = "недостаточно прав пользователя!";

        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            $id = ($post && $post['id']) ? $post['id'] : FALSE;
            if ($id && User::find()->where(['id'=>$id])->exists()) {
                $model_user = User::findOne($id);
                $this->error = "no";
                $this->msg = "Логин успешно найден";
                $this->data['name'] = $model_user->login;
                $this->data['content'] = $this->renderAjax('change_form');
            }
        }
        return $this->out();
    }

    /**
     * Устанавливаем пароль пользователю
     *
     * @return array
     */
    public function actionPasswd_set()
    {
        $this->init_ajax();
        $this->error = "yes";
        $this->msg = "недостаточно прав пользователя!";

        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            $id = ($post && $post['id']) ? $post['id'] : FALSE;
            if ($id && User::find()->where(['id'=>$id])->exists()) {
                $model_user = User::findOne($id);
                if (trim($post['passwd_change_1'])!='' && trim($post['passwd_change_2'])!='' && $post['passwd_change_1'] == $post['passwd_change_2']) {
                    $model_user->setPassword($post['passwd_change_1']);
                    $model_user->save();
                    $this->error = "no";
                    $this->msg = "Пароль успешно изменен";
                } else {
                    $this->error = "no";
                    $this->msg = "Ошибка при задании пароля! Выберите пользователя повторно и попробуйте еще раз!";
                }

            }
        }
        return $this->out();
    }


    public function actionLogout()
    {
        Yii::$app->user->logout();
        return  Yii::$app->response->redirect(['user/login'],302);
    }

}
