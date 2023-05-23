<?php
namespace app\controllers;

use app\models\AccountsCookie;
use app\models\FirstAccounts;
use app\models\LogActionName;
use app\models\LogRow;
use app\models\User;
use Yii;
use yii\data\Pagination;


class LogController extends BasicController
{
    public function actionIndex()
    {
        return $this->render('index.php');
    }

    public function actionGet_list()
    {
        $this->init_ajax();
        $this->error = 'no';
        $this->msg = 'Данные успешно загружены';

        $post = Yii::$app->request->post();

        $model = LogRow::find();

        $model = LogRow::load_post($model,$post);
        //пагинация

        $page_limit = (isset($post['page_limit']) ? $post['page_limit'] : 100);
        $page_post =  (isset($post['page_post']) ? $post['page_post'] : 0);
        $sort_post =  (isset($post['sort_post']) ? $post['sort_post'] : 'id');

        $pages = FALSE;
        if ($model) {
            $models_pages = clone $model;
        }

        $page_size = (trim($page_limit)) ? $page_limit : 100;
        if ($page_size) {
            //выбранная текущая страница минус один
            $page = is_integer($page_post) ? $page_post : 0;
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

        $page_limit = (isset($post['page_limit']) ? $post['page_limit'] : 100);
        $page_post = (isset($post['page_post']) ? $post['page_post'] : 0);

        $models = LogActionName::find()->all();

        $data_select = [];
        if ($models) foreach ($models as $m){
            /*
            $data_select['reg_device'][$m->reg_device] = $m->reg_device;
            $data_select['type_reg'][$m->type_reg] = $m->type_reg;
            $data_select['reg_geo'][$m->reg_geo] = $m->reg_geo;
            $data_select['stage'][$m->stage] = $m->stage;
            $data_select['stage_id'][$m->stage_id] = $m->stage_id;
            $data_select['profile_ua'][$m->profile_ua] = $m->profile_ua;
            $data_select['last_used'][$m->last_used] = $m->last_used;
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

    /**
     * Загрузка модели для редактирования
     */
    public function actionGet_edit()
    {
        $this->init_ajax();
        $this->error = "yes";

        $id = (Yii::$app->request->post('id') ? Yii::$app->request->post('id') : FALSE );
        if ($id && LogRow::find()->where(['id'=>$id])->exists()) {
            $model = LogRow::findOne($id);
            $this->data = $this->renderAjax('edit_form',['model'=>$model]);
            $this->error = "no";
            $this->msg = 'Модель '.$id.' успешно загружена!';
        }

        return $this->out();
    }
}