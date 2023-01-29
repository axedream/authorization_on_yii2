<?php
use yii\bootstrap4\Modal;

Modal::begin([
    'id'=>'modal_view_post',
    'size'=>Modal::SIZE_LARGE,
    'title' => '<h5 class="modal-title">Выбрать отображаемые поля</h5>',
    'footer'=>'<a class="btn btn-primary" onclick="save_modal_view_post()">Сохранить</a>'
]); ?>
    <div id="data_view_post">

    </div>
<?php Modal::end(); ?>