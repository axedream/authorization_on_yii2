<?php
use app\models\AccountsType;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\BigAccountsType;

$script = <<<JS

    function onChangeLimit() {
    
    }
    
    //запрос на сохранение лимита
    function set_data_ajax() {
        $.ajax({
            url: this_host + "/user/set_limit_data_ajax",
            type: 'POST',
            dataType: 'JSON',
            data: {
                user_id: $("#user-id").val(),
                params: $("#data_limit_form").serializeObject(),
            },
            cache: false,
            success: function(msg) {
                $("#limit_form").html(msg.data.form);
                $("#limit_data").html(msg.data.list);
            },
        });
    }

    $("#save_limit").on('click',function(e) {
        e.preventDefault();
        set_data_ajax();
        return false;
    });

    

JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>

<div class="row">
    <div class="col-lg-12">
        <div class="limit-form">
            <?php $form = ActiveForm::begin(['id'=>'data_limit_form']); ?>
            <div class="row">
                <div class="col-lg-6">
                    <?= $form->field($model, 'limit')->label(false)->textInput(['maxlength' => true,'placeholder'=>'Введите лимит [0...1000]']) ?>
                </div>

                <div class="col-lg-6">
                    <?= $form->field($model, 'type')->label(false)->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(BigAccountsType::find()->asArray()->all(), 'id', 'name'),
                        'options' => [
                            'placeholder' => 'Выберите тип...',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                        'pluginEvents' => [
                            'change' => 'function() { onChangeLimit(this); }'
                        ],
                    ]); ?>
                </div>
                <div class="col-lg-12">
                    <button class="btn btn-success" id="save_limit">Установить лимит</button>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
