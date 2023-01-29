<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Role;
/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
$id = $model->id;

if ($model->roles) {
    $model->roles_view = $model->roles_view_transform;
}

if (!$model->isNewRecord) {
    $id = $model->id;
}

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <?php $form = ActiveForm::begin(); ?>
            <?= (!$model->isNewRecord) ? $form->field($model, 'id')->label(false)->hiddenInput() : '' ?>
            <?= $form->field($model, 'login')->textInput(['maxlength' => true]) ?>
            <?= ($model->id != 1) ? $form->field($model, 'roles_view')->widget(Select2::classname(),[
                'data' => ArrayHelper::map(Role::find()->where(['archive'=>0])->andWhere(' id!=1 ')->asArray()->all(), 'id', 'name'),
                'theme' => Select2::THEME_BOOTSTRAP,
                'hideSearch' => false,
                'maintainOrder' => true,
                'options' => [
                    'placeholder' => 'Выберите роли для данного пользователя...',
                    'multiple' => true,
                    'autocomplete' => 'off'
                ],
            ]) :'';
            ?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
