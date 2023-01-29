<?php

use yii\helpers\Html;

$this->title = 'Log - логирование всех разделов';


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
        action_date : $("[name='action_date']").val(),
        action_name : $("[name='action_name']").val(),
        action_group : $("[name='action_group']").val(),
        login : $("[name='login']").val(),
        /*      
        login_facebook : $("[name='login_facebook']").val(),      
        archive : $("[name='archive']").val(),      
         */
        sort_post : $("#sort_post").val(),            
    }
}

function get_list() {
    
    $.ajax({
        url: this_host + "/log/get_list",
        type: 'POST',
        data: get_data_send(),        
        dataType: 'JSON',
        cache: false,
        success: function(msg) {
            $("#result_list").html(msg.data);
        },
    });
}

function get_filter() {
    $("#model_preloading").modal("show");
    $.ajax({
        url: this_host + "/log/get_filter",
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
        complete: function() {
            get_list();
            setTimeout(clouse_load, 1000);
        }
    });
}

function get_edit_row(id) {
    $("#edit_form_modal").modal("show");
    $("#data_edit_form_modal").html($("#preloading").html());
    $.ajax({
        url: this_host + "/log/get_edit",
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
    

function delete_row(id=0) {
    if (id) {
        $.ajax({
            url: this_host + "/log/del",
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
            url: this_host + "/log/del",
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
    
    del_row = function(id) { if (confirm("Вы действительно желаете удалять данную строку?")) { delete_row(id)}  };
    del_rows = function () { if (confirm('Вы действительно хотите удалить данный(е) аккаунт(ы)?')) { delete_row(); } };
        
    
    //получаем фильтр
    set_filter = function() { get_filter(); };
    
    //устанавливаем чекбокс везеде
    checkbox_set_all = function() { $(".s_check").trigger('click'); };
    get_filter();
})
JS;

$this->registerJs($script, yii\web\View::POS_READY);

?>
<script type="text/javascript">

    //акшн установки фильтра
    var set_filter;

    //ключ сворачивания фильтра
    var event_filter = 0;

    //удалить
    var del_row,del_rows;

    //чекбокс
    var checkbox_set_all;

</script>

<?= $this->render('modal_form'); ?>

    <div class="container-fluid">
        <div class="row">

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
                            <div class="col-lg-12">Logs</div>
                        </div>
                    </div>
                    <div class="card-body" id="result_list">
                    </div>

                </div>
            </div>

        </div>
    </div>

<?= Html::cssFile( "@web/css/views/log.css", ["type" => "text/css"]); ?>