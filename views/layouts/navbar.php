<?php

use yii\helpers\Html;
use app\models\ViewPost;

?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <?php if (in_array(Yii::$app->controller->id,array_keys(ViewPost::$cn))) { ?>
        <!-- Navbar Search -->
        <li class="nav-item">
            <?= $this->render('/view_post/index.php',['cn'=>Yii::$app->controller->id]) ?>
        </li>
        <?php } ?>

        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

        <li class="nav-item">
            <?= Html::a('<i class="fas fa-sign-out-alt"></i>', ['/user/logout'], ['data-method' => 'post', 'class' => 'nav-link']) ?>
        </li>
    </ul>
</nav>
<!-- /.navbar -->