<?php

use yii\bootstrap4\LinkPager;


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
    <?php if (count($models)) { ?>
    <div class="col-lg-2">
        <a class="btn btn-success" onclick="del_rows()">Удалить выбранное</a><br>
    </div>
    <?php } ?>

    <div class="col-lg-10">
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
                    <th>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" onchange="checkbox_set_all()" id="s_all" name="s_all" class="custom-control-input">
                            <label class="custom-control-label" for="s_all"></label>
                        </div>
                    </th>

                    <th><a href="#" class="page-sort" data-page="id">ID</a></th>
                    <th><a href="#" class="page-sort" data-page="login">Логин</a></th>
                    <th><a href="#" class="page-sort" data-page="roles">Роли</a></th>
                    <th><a href="#" class="page-sort" data-page="archive">Удаленные</a></th>

                    <th width="100px;">DEL</th>
                </tr>
                </thead>
                <tbody>
                <?php $i=0; ?>
                <?php foreach ($models as $model) { ?>
                    <?php $i++; ?>
                    <tr>
                        <td>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input s_check" id="s_<?= $model->id ?>" name="s_<?= $model->id ?>">
                                <label class="custom-control-label" for="s_<?= $model->id ?>"></label>
                            </div>
                        </td>

                        <td class="data_" data-id="n_<?= $model->id ?>"><?= $model->id ?></td>
                        <td class="data_" data-id="n_<?= $model->id ?>"><?= $model->login ?></td>
                        <td class="data_" data-id="n_<?= $model->id ?>"><?= $model->roles_list_string ?></td>
                        <td class="data_" data-id="n_<?= $model->id ?>"><?= ($model->archive==0) ? 'В работе' : 'Удален' ?></td>

                        <!--  DEL -->
                        <td><?php if ($model->id!= 1 && $model->archive==0) { ?><a class="btn btn-success sender" data-id="<?= $model->id ?>" onclick="del_row('<?= $model->id ?>')"><i class="nav-icon fas fa-trash"></i></a><?php } ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            Данных не найдено
        <?php } ?>
    </div>
</div>