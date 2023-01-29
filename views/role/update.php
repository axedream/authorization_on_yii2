<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Role */

$this->title = 'CRM: Редактировать роль: ' . $model->name;

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <p style="">
                <a id="button_task" class="btn btn-success" href="/role/index">К списку ролей</a>
            </p>
        </div>
        <div class="col-lg-12">
            <?= $this->render('_form', ['model' => $model,'model_groupe'=>$model_groupe]) ?>
        </div>
    </div>
</div>

