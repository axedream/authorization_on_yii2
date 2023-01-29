<?php
$this->title = 'CRM: Главная страница';
?>

<div class="container-fluid">
    <div class="row">

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header" >
                    <div class="row">
                        <div class="col-lg-12">Главная страница</div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-10">
                            <h5>Добро пожаловать в CRM. <br>Начните работу в системе с выбора необходимого раздела в левой части меню!</h5>
                        </div>
                        <div class="col-lg-2">
                            <?php // if (Yii::$app->user->identity->user_type_id == '3' && Yii::$app->user->identity->token_telegram_connect !='') { ?>
                            <a class="btn btn-success" href="https://t.me/zm_team_tools_bot?start=<?= Yii::$app->user->identity->token_telegram_connect ?>">ZM Tools Bot</a>
                            <?php //} ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <?php if (Yii::$app->user->identity->getAccess('v_big_cost')) { ?>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">Accs Cost</div>
                <div class="card-body">
                    <?= $this->renderAjax('/big_cost/index.php') ?>
                </div>
            </div>
        </div>
        <?php } ?>

    </div>
</div>