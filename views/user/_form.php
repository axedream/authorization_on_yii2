<?php

use yii\bootstrap4\Modal;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Role;
use app\models\UserType;
use app\models\UserGroup;
/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
$id = $model->id;

if ($model->roles) {
    $model->roles_view = $model->roles_view_transform;
}
if ($model->isNewRecord) {
    $model->user_type_id = ($model->user_type_id) ? $model->user_type_id : 1;
}

$key_limit = 'no';
if (!$model->isNewRecord) {
    $key_limit = 'yes';
    $id = $model->id;
}

$model->user_group_farms = $model->groupe_farms;


$script = <<<JS
    //запрос для получения формы лимита
    function get_form_limit(){
        $.ajax({
            url: this_host + "/user/get_limit_form_ajax",
            type: 'POST',
            dataType: 'JSON',
            cache: false,
            success: function(msg) {
                $("#limit_form").html(msg.data);
            },
        });
    }
    
    function get_data_limit(){
        $.ajax({
            url: this_host + "/user/get_limit_data_ajax",
            data: {
                id: "$id" ,
            },
            type: 'POST',
            dataType: 'JSON',
            cache: false,
            success: function(msg) {
                $("#limit_data").html(msg.data);
            },
        });
    }
    
    $(function() {
        if ("$key_limit"=='yes') {
            get_form_limit();
            get_data_limit();
        }
    })
JS;


$this->registerJs($script, yii\web\View::POS_READY);


?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <?php $form = ActiveForm::begin(); ?>
            <?= (!$model->isNewRecord) ? $form->field($model, 'id')->label(false)->hiddenInput() : '' ?>
            <?= $form->field($model, 'login')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'buyer_id')->textInput(['maxlength' => true]) ?>
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
            <?=  ($model->id != 1) ? $form->field($model, 'user_type_id')->widget(Select2::classname(),[
                'data' => ArrayHelper::map(UserType::find()->asArray()->all(), 'id', 'name'),
                'theme' => Select2::THEME_BOOTSTRAP,
                'hideSearch' => false,
                'maintainOrder' => true,
                'options' => [
                    'placeholder' => 'Выберите тип пользователя...',
                    'multiple' => false,
                    'autocomplete' => 'off'
                ],
            ]) : '';
            ?>

            <?=  $form->field($model, 'user_group_id')->widget(Select2::classname(),[
                'data' => ArrayHelper::map(UserGroup::find()->asArray()->all(), 'id', 'name'),
                'theme' => Select2::THEME_BOOTSTRAP,
                'hideSearch' => false,
                'maintainOrder' => true,
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'options' => [
                    'placeholder' => 'Выберите группу...',
                    'multiple' => false,
                    'autocomplete' => 'off'
                ],
            ]);
            ?>

            <?=  $form->field($model, 'user_group_farms')->widget(Select2::classname(),[
                'data' => ArrayHelper::map(\app\models\UserGroupFarm::find()->asArray()->all(), 'id', 'name'),
                'theme' => Select2::THEME_BOOTSTRAP,
                'hideSearch' => false,
                'maintainOrder' => true,
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'options' => [
                    'placeholder' => 'Выберите группы Farm...',
                    'multiple' => true,
                    'autocomplete' => 'off'
                ],
            ]);
            ?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>

        <?php if ($key_limit == 'yes' && $model->user_type_id == 3) { ?>
            <div class="col-lg-6" >
                <div class="card">
                    <div class="card-header" >
                        <div class="row">
                            <div class="col-lg-12" >Добавление/редактирование лимитов</div>
                        </div>
                    </div>
                    <div class="card-body" id="limit_form"></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header" >
                        <div class="row">
                            <div class="col-lg-12" >Установленные лимиты</div>
                        </div>
                    </div>
                    <div class="card-body" id="limit_data"></div>
                </div>

            </div>
        <?php } ?>
    </div>
</div>
