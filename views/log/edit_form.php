<?php

use app\models\AccountsSource;
use app\models\AccountsStatus;
use app\models\AccountsType;
use kartik\checkbox\CheckboxX;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

$data_before = ($model->before_data!='') ?  json_decode($model->before_data, true) : '';
$data_after  = ($model->after_data!= '') ?  json_decode($model->after_data,true) : '';

?>


<?php $form = ActiveForm::begin(['id'=>'data_edit_row']); ?>
<div class="row">

    <div class="col-lg-6">
        <?= (isset($model->user)) ? $form->field($model->user, 'login')->textInput(['maxlength' => true,'disabled' => true]) : '' ?>
    </div>


    <div class="col-lg-6">
        <?= (isset($model->log_action_name)) ? $form->field($model->log_action_name, 'name')->textInput(['maxlength' => true,'disabled' => true]) : '' ?>
    </div>

    <div class="col-lg-6">
        <?= $form->field($model, 'date_time_convert')->textInput(['maxlength' => true,'disabled' => true]) ?>
    </div>
    <div class="col-lg-12"></div>


    <div class="col-lg-12">
        <?php
        if ($model->post!='') {
            $data = json_decode($model->post,true);
        ?>
            <div class="card">
                <div class="card-header">
                    POST данные
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <td>Поля</td>
                                <td>Данные</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $k => $v) { ?>
                                <tr>
                                    <td><?= $k ?></td>
                                    <td><?= $v ?></td>
                                </tr>

                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php } ?>
    </div>



    <div class="col-lg-12">
        <?php
        if ($data_before!='') { ?>
            <div class="card">
                <div class="card-header">
                    Данные в таблице <span class="alert alert-primary" role="alert" style="padding: 3px !important; margin-left: 7px; margin-right: 7px;"><?= (isset($model->log_action_name)) ? $model->log_action_name->model : ''  ?></span> "До изменения"
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <td>Поля</td>
                            <td>Данные</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($data_before as $k => $v) { ?>
                            <tr>
                                <td><?= $k ?></td>
                                <td><?= $v ?></td>
                            </tr>

                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php }  ?>
    </div>


    <div class="col-lg-12">
        <?php
        if ($data_after!='') { ?>
            <div class="card">
                <div class="card-header">
                    Данные в таблице <span class="alert alert-primary" role="alert" style="padding: 3px !important; margin-left: 7px; margin-right: 7px;"><?= (isset($model->log_action_name)) ? $model->log_action_name->model : ''  ?></span> "После изменения"
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <td>Поля</td>
                            <td>Данные</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($data_after as $k => $v) { ?>
                            <tr>
                                <td><?= $k ?></td>
                                <td><?= $v ?></td>
                            </tr>

                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php }  ?>
    </div>

</div>
<?php ActiveForm::end(); ?>
