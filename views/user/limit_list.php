<?php

$script = <<<JS

    //запрос на сохранение лимита
    function del_data_ajax(id) {
        $.ajax({
            url: this_host + "/user/del_limit_data_ajax",
            type: 'POST',
            dataType: 'JSON',
            data: {
                user_id: $("#user-id").val(),
                limit_id: id,
            },
            cache: false,
            success: function(msg) {
                if (msg.error == 'no') {
                    $("#limit_data").html(msg.data);    
                }
            },
        });
    }


    //удалить лимит    
    $(".DelLimit").on('click',function(e) {
        let id = $(this).data()['id'];
        e.preventDefault();
        del_data_ajax(id);
        return false;
    });

    

JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>
<div class="row">
    <div class="col-lg-12">
        <?php if ($models) { ?>
            <table class="table">
                <thead>
                <tr>
                    <th>№</th>
                    <th>Лимит</th>
                    <th>Тип</th>
                    <th>Удалить</th>
                </tr>
                </thead>
                <tbody>
                    <?php $i=1; foreach ($models as $model) { ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= ($model->lim ) ? $model->lim : 'Нет лимита'?></td>
                            <td><?= $model->big_accounts_type->name ?></td>
                            <td> <button type="button" class="btn btn-default px-3 DelLimit" data-id="<?= $model->id ?>"><i class="nav-icon fas fa-trash"></i></button></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            Данных не найдено
        <?php } ?>
    </div>
</div>
