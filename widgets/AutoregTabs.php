<?php

namespace app\widgets;

use Yii;

class AutoregTabs extends \yii\bootstrap4\Widget
{
    public $page = 'settings';

    public function run()
    {
        return $this->render('autoreg_tabs/index',['page'=>$this->page]);
    }
}
