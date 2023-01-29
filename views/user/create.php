<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'CRM: Создать пользователя';
?>
<div class="container-fluid">
    <div class="row">

        <div class="col-lg-12">
            <p style="">
                <a id="button_task" class="btn btn-success" href="/user/index">К списку пользователей</a>
            </p>
        </div>

        <div class="col-lg-12">
            <?= $this->render('_form', ['model' => $model]) ?>
        </div>

    </div>
</div>
