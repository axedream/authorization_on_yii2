<?php

use app\models\Role;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\User;

$post['archive'] = (!isset($post['archive'])) ? 3 : $post['archive'];
?>
<div class="row">

    <div class="col-lg-12">
        <form id="row_fiters">
    </div>
    <!-- ------------------------ ID -------------------------- -->
    <div class="col-lg-2">
        <label class="control-label" data-from="id">ID (можно через запятую)</label>
        <input type="text" class="form-control" name="id" value="<?= isset($post['id']) ? $post['id'] : ''; ?>">
    </div>
    <!-- ------------------------ ID -------------------------- -->

    <!-- ------------------------ Login -------------------------- -->
    <div class="col-lg-4">
        <label class="control-label" data-from="login">Login</label>
        <input type="text" class="form-control" name="login" value="<?= isset($post['login']) ? $post['login'] : ''; ?>">
    </div>
    <!-- ------------------------ Login -------------------------- -->

    <!-- ------------------------ Archive -------------------------- -->
    <div class="col-lg-2">
        <label class="control-label" data-from="archive">Удален</label>
        <select class="form-control" name="archive">
            <option value="2" <?= ( $post['archive']=='2' ? 'selected' : '') ?>>В работе</option>
            <option value="1" <?= ($post['archive']=='1' ? 'selected' : '') ?>>Удален</option>
            <option value="3" <?= ($post['archive']=='3' ? 'selected' : '') ?>>Все</option>
        </select>
    </div>
    <!-- ------------------------ Archive -------------------------- -->


    <!-- ------------------------ LIMIT -------------------------- -->
    <div class="col-lg-2">
        <label class="control-label" data-from="limit_page">На странице</label>
        <select class="form-control" name="page_limit">
            <option value="25" <?= ($page_limit=='25' ? 'selected' : '') ?>>25</option>
            <option value="50" <?= ($page_limit=='50' ? 'selected' : '') ?>>50</option>
            <option value="100" <?= ($page_limit=='100' ? 'selected' : '') ?>>100</option>
        </select>
    </div>
    <!-- ------------------------ LIMIT -------------------------- -->

    <!-- ------------------------ Roles -------------------------- -->
    <div class="col-lg-6">
        <label class="control-label" data-from="roles">Роли</label>
        <?= Select2::widget([
            'name' => 'roles',
            'data' => ArrayHelper::map(Role::find()->where(['archive'=>0])->andWhere(' id!=1 ')->asArray()->all(), 'id', 'name'),
            'value' => isset($post['roles']) ? $post['roles'] : '',
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => [
                'placeholder' => 'Выберите роли',
                'multiple' => true,
                'autocomplete' => 'off'
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ]);
        ?>
    </div>
    <!-- ------------------------ Roles -------------------------- -->



    <div class="col-lg-12">
        <?= Html::input('hidden','page_post',($page_post ? $page_post : ''),['id'=>'page_post'])?>
        <?= Html::input('hidden','sort_post',(isset($post['sort_post']) ? $post['sort_post'] : ''),['id'=>'sort_post'])?>
        </form>
    </div>

</div>
