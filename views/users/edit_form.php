<?php

use app\models\Role;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;


if ($model->roles) {
    $model->roles_view = $model->roles_view_transform;
}


?>


<?php $form = ActiveForm::begin(['id'=>'data_edit_row']); ?>
<div class="row">

    <div class="col-lg-12"><?= $form->field($model, 'id')->label(FALSE)->hiddenInput(); ?></div>

    <div class="col-lg-6">
        <?= $form->field($model, 'login')->textInput(['maxlength' => true])->label('Login') ?>
    </div>

    <?php if ($model->isNewRecord) { ?>
        <div class="col-lg-6">
            <?= $form->field($model, 'password')->textInput(['maxlength' => true])->label('Пароль') ?>
        </div>
    <?php } else { ?>
        <div class="col-lg-6" style="padding-top: 32px; text-align: right;">
            <a href="#" class="btn btn-success" onclick="user_passwd_get_button('<?= $model->id ?>');">Установить пароль</a>
        </div>
    <?php } ?>

    <div class="col-lg-6">
        <?php if($model->archive==1) { ?>
        <div style="padding-top: 30px; text-align: right;">
            <a href="#" class="btn btn-success" onclick="user_recovery_button('<?= $model->id ?>')">Восстановить из архива</a>
        </div>
        <?php } ?>
    </div>

    <div class="col-lg-12">
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
        'pluginOptions' => [
            'allowClear' => true,
            'dropdownParent' => '#edit_form_modal',
        ],
    ]) :'';
    ?>
    </div>

    <?php if (!$model->isNewRecord) { ?>
        <div class="col-lg-12">
            <?= $form->field($model, 'auth_key')->textInput(['maxlength' => true,'disabled' => true])->label('Внешний ключ авторизации') ?>
        </div>
    <?php } ?>

</div>
<?php ActiveForm::end(); ?>
