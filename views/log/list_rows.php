<?php

use yii\bootstrap4\LinkPager;
use yii\helpers\Html;

$script = <<<JS
// -------------------------------------------- ПАГИНАЦИЯ ------------------------------------------//
$(".page-link").on('click',function(e) {
    e.preventDefault();
    let page_id = $(this).attr('data-page');
        $("#page_post").val(page_id);
        $('#submitFilter').trigger('click');
        return false;

    });
    
// -------------------------------------------- ПАГИНАЦИЯ ------------------------------------------//

// -------------------------------------------- СОРТИРОВКА В ТАБЛИЦЕ ------------------------------------------//
$(".page-sort").on('click',function(e) {
    e.preventDefault();
    let page_sort = $(this).attr('data-page');
    let now_sort = $("#sort_post").val();
    
    if (now_sort=='') now_sort = 'id DESC';
    let out = now_sort.split(' ');
    let sort_type = '';
    
    if (out[0] == page_sort) {
        if (out[1] == 'ASC') { sort_type = 'DESC'; } else { sort_type = 'ASC' } 
    } else {
        sort_type = 'DESC';
    }
    $("#sort_post").val(page_sort + ' ' + sort_type);
    $('#submitFilter').trigger('click');
    return false;
});
// -------------------------------------------- СОРТИРОВКА В ТАБЛИЦЕ ------------------------------------------//


$(function() {
  
});


JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>

<div class="row">
    <div class="col-lg-12">
        <?php if($bin_fa_del) { ?>
            <a class="btn btn-success" onclick="del_rows()">Удалить выбранное</a><br>
        <?php } ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-6"></div>
    <div class="col-lg-6">
        <div style="text-align: right">
            <?= ($pages ? 'Всего найдено записей: '.$pages->totalCount : '') ?>
            <?= ((!$pages && count($models)) ? 'Всего найдено записей: '.count($models) : '') ?>
        </div>
    </div>

    <div class="col-lg-12"><?= ($pages) ? LinkPager::widget(['pagination' => $pages,]) : '' ?></div>
    <div class="col-lg-12">
    <?php if ($models) { ?>
        <table class="table">
            <thead>
                <tr>
                    <th width="60px"><a href="#" class="page-sort" data-page="id">ID</a></th>
                    <th width="200px"><a href="#" class="page-sort" data-page="action_date">Дата</a></th>
                    <th width="300px">Action</th>
                    <th>Группа</th>
                    <th width="200px"><a href="#" class="page-sort" data-page="user_id">Пользователь</a></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($models as $model) { ?>
                <tr>
                    <td class="data_" data-id="n_<?= $model->id ?>"><?= $model->id ?></td>
                    <td class="data_" data-id="n_<?= $model->id ?>"><?= $model->date_time_convert ?></td>
                    <td class="data_" data-id="n_<?= $model->id ?>"><?= (isset($model->log_action_name)) ? $model->log_action_name->name : '' ?></td>
                    <td class="data_" data-id="n_<?= $model->id ?>"><?= (isset($model->log_group)) ? $model->log_group->name : '' ?></td>
                    <td class="data_" data-id="n_<?= $model->user_id ?>"><?= (isset($model->user)) ? $model->user->login : '' ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        Данных не найдено
    <?php } ?>
    </div>
</div>