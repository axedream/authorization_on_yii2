<?php

use app\models\Menu;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Role */
/* @var $form yii\widgets\ActiveForm */

if ($model->menu) {
    $model->menu_view = $model->menu_view_transform;
}

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?=  $form->field($model, 'menu_view')->widget(Select2::classname(),[
                'data' => ArrayHelper::map(Menu::find()->asArray()->all(), 'id', 'label'),
                'theme' => Select2::THEME_BOOTSTRAP,
                'hideSearch' => false,
                'maintainOrder' => true,
                'options' => [
                    'placeholder' => 'Выберите элементы меню для данной роли...',
                    'multiple' => true,
                    'autocomplete' => 'off'
                ],
            ]);
            ?>
            <?php if (isset($model_groupe) && $model_groupe) { ?>
                <div class="row">
                    <?php foreach ($model_groupe as $mg) { ?>
                        <?= $this->render('action', ['model' => $model,'model_groupe'=>$mg]) ?>
                    <?php } ?>
                </div>
            <?php } ?>



            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
