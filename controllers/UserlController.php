<?php

namespace app\controllers;

use yii\web\Controller;
use Yii;
use app\models\LoginForm;


/**
 * UserController implements the CRUD actions for User model.
 */
class UserlController extends Controller
{

    public function actionLogin()
    {
        Yii::$app->controller->layout = 'main-login';

        if (!Yii::$app->user->isGuest) {
            return  Yii::$app->response->redirect(['site/index'],302);
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return  Yii::$app->response->redirect(['site/index'],302);
        }

        $model->password = '';
        return $this->render('login', ['model' => $model]);
    }
}
