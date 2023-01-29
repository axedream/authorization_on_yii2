<?php

namespace app\widgets;

use Yii;

class Fb_sharingTabs extends \yii\bootstrap4\Widget
{
    public $page = 'android';

    public function run()
    {
        return $this->render('fb_sharing_tabs/index',['page'=>$this->page]);
    }
}
