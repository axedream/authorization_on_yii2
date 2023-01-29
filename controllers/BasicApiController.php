<?php
namespace app\controllers;

use app\models\Api_logger;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\User;

/**
 * Class BasicController
 * @package app\controllers
 *
 * @property string $request_ip
 * @property array $data_post
 * @property array $data
 */
class BasicApiController extends Controller
{

    /**
     * IP запроса
     *
     * @var string
     */
    public $request_ip;

    /**
     * Данные из POST
     *
     * @var mixed
     */
    public $data_post;

    /**
     * Объект пользователя если он найден
     *
     * @var object
     */
    public $oUser;

    /**
     * Массив групп доступа [1,2...]
     *
     * @var array
     */
    public $aGroups;

    /**
     * Переменная серверов доступа (берется из параметров)
     *
     * @var
     */
    public $server;

    /**
     * Сообщение об ошибке
     *
     * @var
     */
    public $msg;

    /**
     * Наличие ошибки
     *
     * @var
     */
    public $error;

    /**
     * Цифровой тип ошибки
     *
     * @var int
     */
    public $error_type = 0;

    /**
     * Данные к выдаче
     *
     * @var
     */
    public $data;

    /**
     * Наличие авторизации TRUE, нет авторизации FALSE
     *
     * @var bool
     */
    public $access=FALSE;


    public function init_ajax()
    {
        $this->error = 'yes';
        $this->msg = Yii::$app->params['messages']['user']['error']['params'];
        Yii::$app->response->format = Response::FORMAT_JSON;
    }

    /**
     * Стандартная выдача сообщений
     *
     * @return array
     * @throws \Exception
     */
    public function out()
    {
        $model = new Api_logger();
        $model->date_add  = User::getNowDateTime();
        $model->ip = $this->request_ip;
        $model->in_data = print_r($this->data_post,TRUE);
        $model->out_data= print_r($this->data,TRUE);
        $model->save();
        
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: authorization');
        header('Access-Control-Allow-Credentials: true');
        return ['error'=>$this->error, 'error_type'=>(!empty($this->error_type)) ? $this->error_type : '','msg'=>$this->msg, 'data'=> ($this->error=='no') ? $this->data : '' ];
    }

    /**
     * Базовая инициализация
     */
    public function init(){
        Yii::$app->user->logout();
        $this->error = 'yes';
        $this->msg = Yii::$app->params['messages']['user']['error']['params'];
        $this->enableCsrfValidation = false;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $this->request_ip = Yii::$app->request->userIP;

        //проверка доступа для метода POST
        if (Yii::$app->request->isPost) {
            if (Yii::$app->request->post('auth_key')) {
                $_auth_key = Yii::$app->request->post('auth_key');
                $this->test_access($_auth_key);
            } else {
                $this->error = 'yes';
                $this->msg = Yii::$app->params['messages']['user']['error']['access'];
            }
        }

        if ($this->access) {
            $this->indentyUser();
        }

    }

    public function indentyUser()
    {
        if ($this->oUser && User::find()->where(['id'=>$this->oUser->id])->exists()) {
            $identity = User::findOne($this->oUser->id);
            Yii::$app->user->login($identity,3600*24*30*10);
        }
    }


    /**
     * Проверка доступа
     *
     * @param bool $_auth_key
     * @param bool $_ip
     */
    public function test_access ($_auth_key=FALSE)
    {
        if ($_auth_key) {
            //проверяем ключ и дату ключа
            $id = User::getApiAuth($_auth_key);
            if ($id) {
                $this->oUser = User::findOne($id);
                $this->aGroups = 10;
                $this->error="no";
                $this->msg = $this->msg = Yii::$app->params['messages']['user']['success']['login'];
                $this->access = TRUE;
            } else { $this->msg = Yii::$app->params['messages']['user']['error']['auth_key']; }
        } else { $this->msg = Yii::$app->params['messages']['user']['error']['access']; }
    }

    /**
     * Минимальная группа доступа что бы пользоваться API
     *
     * @param $input_group
     * @return bool
     */
    public function accessGroupe($input_group=FALSE)
    {
        return TRUE;
    }

}