<?php
$ar_fb_list = Yii::$app->user->identity->getAccess('ar_fb_list');
$ar_pr_list = Yii::$app->user->identity->getAccess('ar_pr_list');
$ar_st_list = Yii::$app->user->identity->getAccess('ar_st_list');


?>
<ul class="nav nav-tabs" style="margin-bottom: 10px;">

    <?php if ($ar_st_list) { ?>
    <li class="nav-item">
        <a class="nav-link <?= ($page == 'settings') ? 'active' : ''?>" href="/autoreg_settings/index">
            <span class="nav-link-text"><i class="fas fa-user-circle-o"></i> Settings</span>
        </a>
    </li>
    <?php } ?>

    <?php if ($ar_fb_list) { ?>
    <li class="nav-item">
        <a class="nav-link <?= ($page == 'facebook') ? 'active' : ''?>" href="/autoreg_facebook/index">
            <span class="nav-link-text"><i class="fas fa-bullhorn"></i> Facebook</span>
        </a>
    </li>
    <?php } ?>

    <?php if ($ar_pr_list) { ?>
    <li class="nav-item">
        <a class="nav-link <?= ($page == 'proxy') ? 'active' : ''?>" href="/autoreg_proxy/index">
            <span class="nav-link-text"><i class="fas fa-user"></i> Proxy</span>
        </a>
    </li>
    <?php } ?>
</ul>
