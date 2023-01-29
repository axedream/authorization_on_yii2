<?php

    $bin_fa = Yii::$app->user->identity->getAccess('bin_fa_index');
    $bin_big = Yii::$app->user->identity->getAccess('bin_big_index');


?>
    <ul class="nav nav-tabs" style="margin-bottom: 10px;">
        <?php if ($bin_fa) { ?>
        <li class="nav-item">
            <a class="nav-link <?= ($page == 'bin_fa') ? 'active' : ''?>" href="/bin_fa/index">
                <span class="nav-link-text"><!--<i class="fas fa-user-circle-o"></i>--> First accounts</span>
            </a>
        </li>
        <?php } ?>

        <?php if ($bin_big) { ?>
        <li class="nav-item">
            <a class="nav-link <?= ($page == 'bin_big') ? 'active' : ''?>" href="/bin_big/index">
                <span class="nav-link-text"><!--<i class="fas fa-bullhorn"></i>--> Big accounts</span>
            </a>
        </li>
        <?php } ?>


        <?php if ($page == 'bin_big_name') { ?>
        <li class="nav-item">
            <div class="input-group">
                <a class="nav-link active" href="/bin_big/edit_row/<?= $id ?>">
                    <span class="nav-link-text"><!--<i class="fas fa-bullhorn"></i>--> Big accounts: <?= $name ?></span>
                </a>
                <!-- style="border-color: #0ba1fe !important; -->
                <div class="input-group-prepend btn_clouse" onclick="close_tab()">
                    <div class="input-group-text btn_clouse" style="position: relative; border: 3px solid #0ba1fe !important; border-top-right-radius: 15px; border-bottom-right-radius: 15px; left: -12px; top: 1px; ">X</div>
                </div>

            </div>
        </li>
        <?php } ?>


    </ul>

<style type="text/css">
    .btn_clouse {
        cursor: default;
    }
    .btn_clouse:hover {
        cursor: pointer;
    }
</style>