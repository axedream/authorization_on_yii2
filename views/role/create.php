<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Role */

$this->title = 'CRM: Создать роль';
?>
<div class="role-create">


    <div class="col-lg-12">
        <p style="">
            <a id="button_task" class="btn btn-success" href="/role/index">К списку ролей</a>
        </p>
    </div>
    <div class="col-lg-12">
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>

</div>
