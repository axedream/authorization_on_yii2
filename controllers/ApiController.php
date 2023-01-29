<?php
namespace app\controllers;

use Yii;
use yii\web\Response;


/**
 * Class ApiController
 * @package app\controllers
 * @property object $model
 * @property array $data_post
 * @property array only_action
 */
class ApiController extends BasicApiController
{

    /**
     * Модель с которой будет работать запрос
     *
     * @var object
     */
    public $model;

    /**
     * Action которые разрешено использовать в API
     *
     * @var array
     */
    public $only_action = ['get','index','set'];

    /**
     * Если нет подходящего Action по сути заменяем action
     *
     * @param string $id
     * @param array $params
     * @return mixed
     * @throws \yii\base\InvalidRouteException
     */
    public function runAction($id, $params = [])
    {
        if (!in_array($id,$this->only_action)) {
            $id = 'index';
        }

        
        $this->data_post = Yii::$app->request->post();
        return parent::runAction($id, $params);
    }

    public function actionIndex()
    {
        $this->enableCsrfValidation = false;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $this->error = 'yes';
        $this->error_type = 500;
        $this->msg = Yii::$app->params['messages']['user']['error']['params'];
        return $this->out();
    }

    /**
     * Запрос на получение данных
     *
     * @return array
     * @throws \Exception
     */
    public function actionGet(){
        $this->get_table();
        return $this->out();
    }

    /**
     * Запрос на создание данных
     *
     * @return array
     * @throws \Exception
     */
    public function actionSet(){
        $this->set_table();
        return $this->out();
    }

    /**
     * Попытка создать(передать) данные
     */
    public function set_table()
    {
        if ($this->access) {
            $modelName = '\\app\\models\\' . Yii::$app->request->post('table_name');
            if (@class_exists($modelName)) {
                $this->model = new $modelName();
                if($this->model) {
                    if ($this->set_options()) {
                        $this->data = $this->model->set_data_table();
                        $this->error=$this->model->error;
                        $this->error_type = $this->model->error_type;
                        $this->msg = $this->model->msg;
                    }
                } else {
                    $this->error='yes';
                    $this->error_type = 201;
                    $this->msg = Yii::$app->params['messages']['request']['error']['t_name_api_access'];
                }
            } else { $this->error='yes'; $this->error_type = 208; $this->msg = Yii::$app->params['messages']['request']['error']['t_name']; }
        } else { $this->error='yes'; $this->error_type = 90; $this->msg = Yii::$app->params['messages']['user']['error']['access']; }
    }


    /**
     * Получаем информацию по таблице
     *
     * @return mixed
     */
    public function  get_table()
    {
        if ($this->access) {
            $modelName = '\\app\\models\\'.Yii::$app->request->post('table_name');

            if(@class_exists($modelName)){
                if (@class_exists($modelName)) { $this->model = new $modelName(); }

                if ($this->model) {
                        $this->error='no';
                        $this->error_type = 100;
                        $this->set_filter();
                        $this->data = $this->model->get_data_table();
                } else {
                    $this->error='yes'; $this->error_type = 201; $this->msg = $this->msg = Yii::$app->params['messages']['request']['error']['t_name_api_access'];
                }
            } else {
                $this->error='yes'; $this->error_type = 202; $this->msg = Yii::$app->params['messages']['request']['error']['t_name'];
            }

        } else {
            $this->error='yes'; $this->error_type = 90; $this->msg = Yii::$app->params['messages']['user']['error']['access'];
        }
    }

    /**
     * Устанавливает и собирает опции для записи
     *
     * @return array
     */
    public function  set_options()
    {
        $this->model->attr = array_keys($this->model->attributes);
        foreach ($this->data_post as $key => $value) {
            if (trim($key) == 'options') {
                $pre_filter = json_decode($value,TRUE);
                if (json_last_error()=='') {
                    $this->model->filter = $pre_filter;
                    return TRUE;
                } else {
                    $this->error='yes'; $this->error_type = 302; $this->msg = Yii::$app->params['messages']['request']['error']['json'];
                    return FALSE;
                }
            }
        }
        return FALSE;
    }


    /**
     * Устанавливает и собирает фильтр
     *
     * @return array
     */
    public function  set_filter()
    {
        $this->model->attr = array_keys($this->model->attributes);

        foreach ($this->data_post as $key => $value) {
            if (trim($key) == 'filter') {
                $pre_filter = json_decode($value,TRUE);
                if (json_last_error()=='') {
                    $this->model->filter = $pre_filter;
                } else {
                    $this->error='yes'; $this->error_type = 302; $this->msg = Yii::$app->params['messages']['request']['error']['json'];
                }
            }
            if ($key == 'select') {
                $pre_select = json_decode($value,TRUE);
                if (json_last_error()=='') {
                    $this->model->select = $pre_select;
                } else {
                    $this->error='yes'; $this->error_type = 302; $this->msg = Yii::$app->params['messages']['request']['error']['json'];
                }
            }
            if ($key == 'limit') {
                if (is_numeric($value) && $value>0) {
                    $this->model->limit = $value;
                }
            }

            if ($key == 'offset') {
                if (is_numeric($value) && $value>0) {
                    $this->model->offset = $value;
                }
            }
            if ($key == 'sort') {
                if ($value && $value != '') {
                    $this->model->sort = $value;
                }
            }
            if ($key == 'debug') {
                if ($value = 1) {
                    $this->model->debug = TRUE;
                }
            }

            if (!in_array($key,['filter','select','limit','offset','sort','debug','auth_key','table_name'])) {
                array_push($this->model->filter,[
                    'key' => $key,
                    'value' => explode(',',$value),
                    'condition' => '='
                ]);
            }

        }
    }
}