<?php
use yii\bootstrap4\Modal;

?>
<!-- ----------------------------------------ОКНО РЕДАКТИРОВАНИЯ---------------------------------------- -->
<?php Modal::begin([
    'id'=>'edit_form_modal',
    'size'=>Modal::SIZE_LARGE,
    'title' => '<h5 class="modal-title">Редактирование</h5>',
    'footer'=> '<a class="btn btn-success" onclick="save_form();">Сохранить</a>'
]); ?>
<div id="data_edit_form_modal"></div>
<?php Modal::end(); ?>
<!-- ----------------------------------------ОКНО РЕДАКТИРОВАНИЯ---------------------------------------- -->

<!-- ----------------------------------------ОКНО ПРЕЛОАДИНГА---------------------------------------- -->
<?php Modal::begin([
    'id'=>'model_preloading',
    'title' => '<h5 class="modal-title">Идет загрузка данных...</h5>',
    'size'=>Modal::SIZE_LARGE,

]); ?>
<div id="preloading">
    <div class="row">
        <div style="width: 100%; text-align: center"><img src="/img/fidget_spinner.gif" class="brand-image align-items-center" style="width: 200px;height: 200px;"></div>
        <div style="width: 100%; text-align: center"><h3>Статус: загрузка данных...</h3></div>
    </div>
</div>
<?php Modal::end(); ?>
<!-- ----------------------------------------ОКНО ПРЕЛОАДИНГА---------------------------------------- -->

<!-- ----------------------------------------ОКНО СМЕНЫ/УСТАНОВКИ ПАРОЛЯ---------------------------------------- -->
<?php Modal::begin(['id'=>'ChangePasswdModal', 'title' => '<h5 class="modal-title">Изменить/задать пароль: <b><div id="ChangePasswdModalName"></div></b></h5>','footer'=>'<a class="btn btn-primary" id="SavePasswdButton" onclick="user_passwd_set_button()" >Сохранить</a>' ]); ?>
    <div id="ChangePasswdModalContent">
    </div>
<?php Modal::end(); ?>
<!-- ----------------------------------------ОКНО СМЕНЫ/УСТАНОВКИ ПАРОЛЯ---------------------------------------- -->

