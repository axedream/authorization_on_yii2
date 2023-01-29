<?php

namespace app\controllers;

use app\models\Action;
use app\models\ActionGroupe;
use app\models\Role;
use app\models\RoleSearch;
use Yii;

class RoleController extends BasicController
{

    public function actionIndex()
    {
        $searchModel = new RoleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new Role();

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return Yii::$app->response->redirect(['role/index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', ['model' => $model,]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {

            $model->save();
            $post = Yii::$app->request->post();

            //----------------- получаем экшн для ролей ---------------------//
            foreach (array_keys($post) as $p) {
                if (strpos($p, 'n_') !== false) {
                    $chekbox_array[] =$p;
                }
            }
            if (isset($chekbox_array) && is_array($chekbox_array) && count($chekbox_array))
            {
                $model->set_role($chekbox_array);
            } else {
                $model->clear_role();
            }

            //----------------- получаем экшн для ролей ---------------------//

            return Yii::$app->response->redirect(['role/index']);
        }

        $model_groupe = ActionGroupe::find()->where(['archive'=>0])->all();

        return $this->render('update', [
            'model' => $model,
            'model_groupe' => $model_groupe,
        ]);
    }

    public function actionDelete($id)
    {
        if (Role::find()->where(['id'=>$id])->exists()) {
            $model = Role::findOne($id);
            if ($model->id != 1) {
                $model->archive = 1;
                $model->save();
            }
        }


        return Yii::$app->response->redirect(['role/index']);
    }


    protected function findModel($id)
    {
        if (($model = Role::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
