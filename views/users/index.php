<?php

use hail812\adminlte\widgets\Alert;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use yii\helpers\Html;

$this->title = 'Users: Управление пользователями';

$script = <<<JS

function clouse_load() {
    $("#model_preloading").modal("hide");
    event_get_data();
}

function get_data_send() {
    //let status_proxy; if ($("[name='status_proxy']").val() == '0') { status_proxy = 'nole'; } else { status_proxy = $("[name='status_proxy']").val(); }
    return {
        page_limit : $("[name='page_limit']").val(),
        page_post  : $("#page_post").val(),
        sort_post : $("#sort_post").val(),   
        login: $("[name='login']").val(), 
        id: $("[name='id']").val(), 
        archive: $("[name='archive']").val(), 
        roles: $("[name='roles[]']").val(), 
    }
}

function get_list() {
    $("#model_preloading").modal("show");
    $.ajax({
        url: this_host + "/users/get_list",
        type: 'POST',
        data: get_data_send(),        
        dataType: 'JSON',
        cache: false,
        success: function(msg) {
            $("#result_list").html(msg.data);
        },
        complete: function(msg) {
            setTimeout(clouse_load, 1000);
        },
    });
}

function get_filter() {
    $.ajax({
        url: this_host + "/users/get_filter",
        type: 'POST',
        dataType: 'JSON',
        data: get_data_send(),      
        cache: false,
        success: function(msg) {
            $("#block_filter_content").html(msg.data);
            if (!event_filter) {
                $('#block_filter').on('click',function() {
                    $('#block_filter_content').slideToggle(300);
                    $('#block_footer_content').slideToggle(300);
                });
                //скрываем фильтр
                $('#block_filter_content').slideToggle(0);
                $('#block_footer_content').slideToggle(0);
                event_filter = 1;
            }        
        },        
    });
}

function get_edit_row(id) {
    $("#edit_form_modal").modal("show");
    $("#data_edit_form_modal").html($("#preloading").html());
    $.ajax({
        url: this_host + "/users/get_edit",
        data: {
            id: id
        },
        type: 'POST',
        dataType: 'JSON',
        cache: false,
        success: function(msg) {
            if (msg.error == 'no') {
                $("#data_edit_form_modal").html(msg.data);
            }
        },
    });
}

function save_row() {
    $("#edit_form_modal").modal("show");
    $.ajax({
        url: this_host + "/users/save_form",
        data: {
            params: $("#data_edit_row").serializeObject(), 
        },
        type: 'POST',
        dataType: 'JSON',
        cache: false,
        success: function(msg) {
            if (msg.error == 'no') {
                $("#edit_form_modal").modal("hide");
                get_filter();
                get_list();                
            } else {
                $("#data_edit_form_modal").html(msg.msg);
            }
        },
    });    
}
function user_recovery(id) {
    if (id) {
        $.ajax({
            url: this_host + "/users/recovery",
            data: {
                id: id
            },
            type: 'POST',
            dataType: 'JSON',
            cache: false,
            success: function(msg) {
                if (msg.error == 'no') {
                    $("#edit_form_modal").modal("hide");
                    get_filter();
                    get_list();
                }
            },
        });
    }
}

function delete_row(id=0) {
    if (id) {
        $.ajax({
            url: this_host + "/users/del",
            data: {
                id: id
            },
            type: 'POST',
            dataType: 'JSON',
            cache: false,
            success: function(msg) {
                if (msg.error == 'no') {
                    get_filter();
                    get_list();
                }
            },
        });
    } else {
        $.ajax({
            url: this_host + "/users/del",
            data: {
                checkbox: get_array_checkbox()
            },
            type: 'POST',
            dataType: 'JSON',
            cache: false,
            success: function(msg) {
                if (msg.error == 'no') {
                    get_list();
                }
            },
        });
    }
}


function event_get_data() {
    //запросить данные по аккаунту и показать модальное окно, в него вставить отображение формы
    $('.data_').on('click',function(e) {
        var id = 0;
        e.preventDefault();
        let pre_id = $(this).data("id");
        if (pre_id) {
            id = explode('_',pre_id)[1];    
        }
        get_edit_row(id);
        return false;
    });
}

$(function() {
    
    save_form = function() { save_row(); };
    del_row = function(id) { if (confirm("Вы действительно желаете удалять данную строку?")) { delete_row(id)}  };
    del_rows = function () { if (confirm('Вы действительно хотите удалить данный(е) строки(ы)?')) { delete_row(); } };
    set_filter = function() { get_list(); };
    checkbox_set_all = function() { $(".s_check").trigger('click'); };
    user_add_button = function() { get_edit_row(0); };
    user_recovery_button = function(id) { user_recovery(id); };
    
    
    get_filter();
    get_list();
})
JS;

$this->registerJs($script, yii\web\View::POS_READY);

?>
    <script type="text/javascript">

        //экшн установки фильтра
        var set_filter;

        //ключ сворачивания фильтра
        var event_filter = 0;

        //сохранить, удалить, редактировать
        var save_form,del_row,del_rows,edit_row;

        //чекбокс
        var checkbox_set_all;

        //добавить пользователя
        var user_add_button;

        //восстановить пользователя из архива
        var user_recovery_button;

    </script>

<?= $this->render('modal_form'); ?>

    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-12">
                <p>
                    <a href="#" class="btn btn-success" onclick="user_add_button()">Добавить пользователя</a>
                </p>
            </div>

                <?php if ($message) { ?>
                <div class="col-lg-6">
                    <?= Alert::widget(['type' => 'success','body' => '<h3>'.$message.'</h3>']) ?>
                </div>
                <div class="col-lg-6"></div>
            <?php } ?>


            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header" id="block_filter">
                        Фильтры
                    </div>
                    <div class="card-body" id="block_filter_content">
                    </div>

                    <div class="card-footer" id="block_footer_content">
                        <a class="btn btn-success" onclick="set_filter()" id="submitFilter">Применить фильтр</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header" >
                        <div class="row">
                            <div class="col-lg-12">Пользователи</div>
                        </div>
                    </div>
                    <div class="card-body" id="result_list">
                    </div>

                </div>
            </div>

        </div>
    </div>

<?= Html::cssFile( "@web/css/views/users.css", ["type" => "text/css"]); ?>
