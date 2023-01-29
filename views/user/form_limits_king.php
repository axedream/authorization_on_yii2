<?php
use yii\helpers\Html;
?>
<?php if ($models) { ?>

    <table class="table table-hover">
        <thead>
        <tr>
            <th >Login</th>
            <th >Buyer id</th>
            <th >Limit King</th>
            <th >Limit SK</th>
            <th >Limit ПЗРД</th>
            <th >Limit FP</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($models as $model) { ?>
            <tr id="<?= $model->id?>">

                <!-- Login -->
                <td>
                    <?= $model->login ?>
                </td>

                <!-- buyer_id -->
                <td>
                    <?= $model->buyer_id ?>
                </td>

                <!-- Limit -->
                <td>
                    <?= Html::input('text', 'limit_'.$model->id, $model->get_limit_type(1), ['class' => 'form-control','onchange'=>'change_limits_king("'.$model->id.'")']) ?>
                </td>
                <td>
                    <?= Html::input('text', 'limit_sk_'.$model->id, $model->get_limit_type(6), ['class' => 'form-control','onchange'=>'change_limits_sk("'.$model->id.'")']) ?>
                </td>
                <td>
                    <?= Html::input('text', 'limit_pzrd_'.$model->id, $model->get_limit_type(7), ['class' => 'form-control','onchange'=>'change_limits_pzrd("'.$model->id.'")']) ?>
                </td>
                <td>
                    <?= Html::input('text', 'limit_fp_'.$model->id, $model->get_limit_type(100), ['class' => 'form-control','onchange'=>'change_limits_fp("'.$model->id.'")']) ?>
                </td>

            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } else { ?>
    Данных не найдено!
<?php } ?>
