<?php

use app\models\AccountsSource;
use app\models\UserGroup;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\User;
?>


<?php $form = ActiveForm::begin(['id'=>'data_edit_row']); ?>
<div class="row">
    <div class="col-lg-12"><?= $form->field($model, 'id')->label(FALSE)->hiddenInput(); ?></div>

    <div class="col-lg-6">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-lg-6">
        <?= $form->field($model, 'lead_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(User::find()->where(['archive'=>0])->asArray()->all(), 'id', 'login'),
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => [
                'placeholder' => 'Выберите Lead Farm...'
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'dropdownParent' => '#edit_form_modal',
            ],
        ]); ?>
    </div>

    <div class="col-lg-12">
        <?= $form->field($model, 'team_users_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(User::find()->where(['archive'=>0])->andWhere(['IN','user_type_id',[2,4]])->asArray()->all(), 'id', 'login'),
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => [
                'placeholder' => 'Выберите пользователей данной команды'
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'dropdownParent' => '#edit_form_modal',
            ],
            'options' => [
                'multiple' => true,
            ],

        ]); ?>
    </div>

    <div class="col-lg-12">
        <?= $form->field($model, 'team_buyer')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(UserGroup::find()->all(),'id','name'),
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => [
                'placeholder' => 'Выберите команды buyers'
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'dropdownParent' => '#edit_form_modal',
            ],
            'options' => [
                'multiple' => true,
            ],

        ]); ?>
    </div>


    </div>

</div>
<?php ActiveForm::end(); ?>
<style type="text/css">
    .ch_l {
        position: relative;
        top: 32px;
        left: 23px;
    }
    .ch_te {
        position: relative;
        top: -14px;
        left: 5px;
    }
</style>