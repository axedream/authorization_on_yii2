<?php use yii\bootstrap4\Modal;

Modal::begin([
        'id'=>'DataModal',
        'size'=>Modal::SIZE_LARGE,
        'title' => '<h5 class="modal-title">Изменить данные</h5>',
        'footer'=>'<a class="btn btn-danger" id="DelData">Удалить</a><a class="btn btn-primary" id="SaveData">Сохранить</a>'
    ]); ?>
    <form id="DataContentModalFormTag">
        <div id="DataContentModal">
        </div>
    </form>
<?php Modal::end(); ?>