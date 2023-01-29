<?php

use yii\bootstrap4\LinkPager;
use yii\helpers\Html;

//$fa_del = Yii::$app->user->identity->getAccess('fa_del');
$fa_del = 1;

$script = <<<JS
$(function() {
  
});


JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>

<div class="row">
    <div class="col-lg-12">
        <?php if($fa_del && $models) { ?>
            <a class="btn btn-success" onclick="del_rows()">Удалить выбранное</a><br>
        <?php } ?>
    </div>
</div>

<div class="row">

    <div class="col-lg-12">
    <?php if ($models) { ?>
        <table class="table">
            <thead>
                <tr>
                    <?php if($fa_del) { ?>
                    <th width="50px;">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" onchange="checkbox_set_all()" id="s_all" name="s_all" class="custom-control-input">
                            <label class="custom-control-label" for="s_all"></label>
                        </div>
                    </th>
                    <?php } ?>
                    <th width="100px;">ID</th>
                    <th>Имя</th>
                    <th>Lead Farm</th>
                    <th>Users Farm</th>
                    <th>Team_Buyer</th>
                    <?php if($fa_del) { ?><th width="100px;">DEL</th><?php } ?>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($models as $model) { ?>
                <tr>
                    <?php if($fa_del) { ?>
                    <td>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input s_check" id="s_<?= $model->id ?>" name="s_<?= $model->id ?>">
                            <label class="custom-control-label" for="s_<?= $model->id ?>"></label>
                        </div>
                    </td>
                    <?php } ?>
                    <td class="data_" data-id="n_<?= $model->id ?>"><?= $model->id ?></td>
                    <td class="data_" data-id="n_<?= $model->id ?>"><?= $model->name ?></td>
                    <td class="data_" data-id="n_<?= $model->id ?>"><?= ($model->lead) ? $model->lead->login : '' ?></td>
                    <td class="data_" data-id="n_<?= $model->id ?>"><?= $model->team_login ?></td>
                    <td class="data_" data-id="n_<?= $model->id ?>"><?= $model->team_buyer_name ?></td>

                    <!--  DEL -->
                    <?php if($fa_del) { ?><td><a class="btn btn-success sender" data-id="<?= $model->id ?>" onclick="del_row('<?= $model->id ?>')"><i class="nav-icon fas fa-trash"></i></a></td> <?php } ?>

                </tr>
            <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        Данных не найдено
    <?php } ?>
    </div>
</div>