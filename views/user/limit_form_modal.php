<?php
use app\models\AccountsType;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\BigAccountsType;

$script = <<<JS
 
    
 
    //метод изменения выбора типа
    function onChangeLimit(e) {
        //return false;
    }


    
    function set_data_limit_modale() {
        set_limit = 1;
        $.ajax({
            url: this_host + "/user/set_limit_from_modal_ajax",
            type: 'POST',
            dataType: 'JSON',
            data: {
                params: $("#data_limit_form").serializeObject(),
            },
            cache: false,
            success: function(msg) {
                $("#limit_form").html(msg.msg);
                if (msg.error == 'no') {
                    $("#limit_form").html(msg.msg);
                    setTimeout(function(){
                        $("#AddLimitAccountModal").modal("hide");
                    },2000);
                    
                }
            },
            complete: function() {
                set_limit = 0;              
            }
        });
    }

JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>

<div class="row">
    <div class="col-lg-12">
        <div class="limit-form">
            <?php $form = ActiveForm::begin(['id'=>'data_limit_form']); ?>
            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($model, 'limit')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-lg-6">
                    <?= $form->field($model, 'type')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(BigAccountsType::find()->asArray()->all(), 'id', 'name'),
                        'options' => [
                            'placeholder' => 'Выберите тип...'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'dropdownParent' => '#AddLimitAccountModal',
                        ],
                        'pluginEvents' => [
                            'change' => 'function() { onChangeLimit(this); }'
                        ],
                    ]); ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
