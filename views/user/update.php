<?php

use yii\bootstrap4\Modal;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'CRM: Редактировать пользователя: ' . $model->login;
$id = ($model->id) ?  $model->id : 0;

$user_out_in_arhive = Yii::$app->user->identity->getAccess('user_out_in_arhive');

$is_arhive =  ($model->archive == 1) ? 1 : 0;

$script = <<< JS
    var this_host = window.location.protocol + "//" + window.location.hostname;
    $('#ChangePasswdButton').on('click',function(e) {
        e.preventDefault();
        $('#msg').html('');
        $('#ChangePasswdModal').modal('show');
        return false;
    });
    
    $("#isNotArchive").on('click',function(e) {
        e.preventDefault();
        if (confirm("Вы уверены что хотите восстановить данный аккаунт?")) {
            window.location = '/user/out_in_arhive/$id';
        }
    });
    
    $('#SavePasswdButton').on('click',function(e) {
        e.preventDefault();
        set_request_passwd();
        return false;
    });
    //---------------------------------------- AJAX -------------------------------------//    
    function set_request_passwd(){
        $.ajax({
            url: this_host + "/user/change_pass_ajax",
            type: 'POST',
            dataType: 'JSON',
            data: { 
                id : '$id',
                passwd1: $("#passwd1").val(),
                passwd2: $("#passwd2").val(),
                },
            cache: false,
            success: function(msg) {
                $('#msg').html(msg.msg);
                if (msg.error=="no") {
                    setTimeout(function(){
                        $('#ChangePasswdModal').modal('hide');
                    },1500);
                }
            },
        });
    }
//-------------------------------------END AJAX -------------------------------------//
JS;

$this->registerJs($script, yii\web\View::POS_READY);

?>

<?php Modal::begin(['id'=>'ChangePasswdModal', 'title' => '<h5 class="modal-title">Изменить/задать пароль</h5>','footer'=>'<a class="btn btn-primary" id="SavePasswdButton">Сохранить</a>' ]); ?>
    <form>
        <div class="mb-3">
            <label for="passwd1" class="col-form-label">Пароль</label>
            <input type="text" class="form-control" id="passwd1">
        </div>
        <div class="mb-3">
            <label for="passwd2" class="col-form-label">Повторить пароль</label>
            <input type="text" class="form-control" id="passwd2">
        </div>
        <div class="mb-3" id="msg">
        </div>
    </form>
<?php Modal::end(); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <p style="">
                <a id="button_task" class="btn btn-success" href="/user/index">К списку пользователей</a>
                <?php if (!$model->isNewRecord) { ?>
                    <a class="btn btn-primary" id="ChangePasswdButton">Изменить/Задать пароль</a>
                    <?php if ($is_arhive && $user_out_in_arhive) { ?><a class="btn btn-danger" id="isNotArchive">Восстановить из архива</a> <?php } ?>
                <?php } ?>

            </p>
        </div>
        <div class="col-lg-12">
            <?= $this->render('_form', ['model' => $model]) ?>
        </div>
    </div>
</div>
