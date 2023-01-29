<?php

$this->title = 'Teams Farm';

$script = <<<JS

function clouse_load() {
    $("#model_preloading").modal("hide");
    event_get_data();
}

function get_list() {
    $("#model_preloading").modal("show");
    $.ajax({
        url: this_host + "/user/ugf_list",
        type: 'POST',        
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

function get_edit_row(id) {
    $("#edit_form_modal").modal("show");
    $("#data_edit_form_modal").html($("#preloading").html());
    $.ajax({
        url: this_host + "/user/ugf_get_edit",
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
        url: this_host + "/user/ugf_save_form",
        data: {
            params: $("#data_edit_row").serializeObject(), 
        },
        type: 'POST',
        dataType: 'JSON',
        cache: false,
        success: function(msg) {
            if (msg.error == 'no') {
                $("#edit_form_modal").modal("hide");
                //get_filter();
                get_list();                
            } else {
                $("#data_edit_form_modal").html(msg.msg);
            }
        },
    });    
}

function delete_row(id=0) {
    if (id) {
        $.ajax({
            url: this_host + "/user/ugf_del",
            data: {
                id: id
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
    } else {
        $.ajax({
            url: this_host + "/user/ugf_del",
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
    del_rows = function () { if (confirm('Вы действительно хотите удалить данный(е) аккаунт(ы)?')) { delete_row(); } };
    add_row = function() { get_edit_row(0); };    
    
    //получаем фильтр
    set_filter = function() { get_list(); };
    
    //устанавливаем чекбокс везеде
    checkbox_set_all = function() { $(".s_check").trigger('click'); };
    get_list();
})
JS;

$this->registerJs($script, yii\web\View::POS_READY);

?>
    <script type="text/javascript">

        //сохранить, удалить
        var save_form,del_row,del_rows,add_row;

        //чекбокс
        var checkbox_set_all;

    </script>

<?= $this->render('ugf_modal_form'); ?>
    <div class="col-lg-12" style="margin-bottom: 20px;">
            <a class="btn btn-success" href="index">Вернуться в раздел "Пользователи"</a>
            <a class="btn btn-success" onclick="add_row()">Добавить Team</a>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header" >
                        <div class="row">
                            <div class="col-lg-12">Teams Farm</div>
                        </div>
                    </div>
                    <div class="card-body" id="result_list">
                    </div>
                </div>
            </div>
        </div>
    </div>
