<?php

use kartik\daterange\DateRangePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\LogActionName;
use app\models\LogGroup;
use app\models\User;


$action_name = (isset($post['action_name'])) ? $post['action_name'] : '';
$action_group = (isset($post['action_group'])) ? $post['action_group'] : '';
$login = (isset($post['login'])) ? $post['login'] : '';

$data_action_name = ($action_group!='') ? ArrayHelper::map(LogActionName::find()->where(['log_group_id'=>$action_group])->all(),'uid','name') : ArrayHelper::map(LogActionName::find()->all(),'uid','name');

$data_action_group = ArrayHelper::map(LogGroup::find()->all(),'id','name');
$data_login = ArrayHelper::map(User::find()->all(),'id','login');

?>
<div class="row">

    <div class="col-lg-12">
        <form id="row_fiters">
    </div>

    <!-- ------------------------ Дата --------------------------- -->
    <div class="col-lg-3">
        <label class="control-label" style="color: #dc3545">Дата</label>
        <?= DateRangePicker::widget([
            'name'=>'action_date',
            'convertFormat'=>true,
            'value' => (isset($post['action_date']) ? $post['action_date'] : ''),
            //'useWithAddon'=>true,
            'pluginOptions'=>[
                'locale'=>[
                    'format'=>'d-m-Y',
                    'separator'=>' | ',
                ],
                'opens'=>'left',
                'allowClear' => true,
            ],
        ]); ?>
    </div>
    <!-- ------------------------ Дата --------------------------- -->

    <!-- ------------------------ LIMIT -------------------------- -->
    <div class="col-lg-2">
        <label class="control-label" data-from="limit_page">На странице</label>
        <select class="form-control" name="page_limit">
            <option value="100" <?= ($page_limit=='100' ? 'selected' : '') ?>>100</option>
            <option value="200" <?= ($page_limit=='200' ? 'selected' : '') ?>>200</option>
            <option value="500" <?= ($page_limit=='500' ? 'selected' : '') ?>>500</option>
        </select>
    </div>
    <!-- ------------------------ LIMIT -------------------------- -->

    <!-- ------------------------ action_name -------------------------- -->
    <div class="col-lg-4">
        <label class="control-label" data-from="source">Action</label>
        <?= Select2::widget([
            'name' => 'action_name',
            'data' => $data_action_name,
            'value' => $action_name,
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => ['placeholder' => 'Выберите action_name...','multiple' => false],
            'pluginOptions' => [ 'allowClear' => true],
        ]);
        ?>
    </div>
    <!-- ------------------------ action_name -------------------------- -->

    <!-- ------------------------ action_group -------------------------- -->
    <div class="col-lg-3">
        <label class="control-label" data-from="source">Группа</label>
        <?= Select2::widget([
            'name' => 'action_group',
            'data' => $data_action_group,
            'value' => $action_group,
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => ['placeholder' => 'Выберите группу...','multiple' => false],
            'pluginOptions' => [ 'allowClear' => true],
            'pluginEvents' => [
                'change' => 'function() { set_filter(); }'
            ],

        ]);
        ?>
    </div>
    <!-- ------------------------ action_group -------------------------- -->

    <!-- ------------------------ login -------------------------- -->
    <div class="col-lg-3">
        <label class="control-label" data-from="source">Login</label>
        <?= Select2::widget([
            'name' => 'login',
            'data' => $data_login,
            'value' => $login,
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => ['placeholder' => 'Выберите пользователя...','multiple' => false],
            'pluginOptions' => [ 'allowClear' => true],
        ]);
        ?>
    </div>
    <!-- ------------------------ login -------------------------- -->


    <div class="col-lg-12">
        <?= Html::input('hidden','page_post',($page_post ? $page_post : ''),['id'=>'page_post'])?>
        <?= Html::input('hidden','sort_post',(isset($post['sort_post']) ? $post['sort_post'] : ''),['id'=>'sort_post'])?>
        </form>
    </div>

</div>
