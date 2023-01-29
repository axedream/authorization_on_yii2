<?php

namespace app\widgets;

use Yii;

class BinTabs extends \yii\bootstrap4\Widget
{
    public $page = '';
    public $name = '';
    public $id = 0;

    public function run()
    {
        return $this->render('bin_tabs/index',[
            'page'=>$this->page,
            'name'=>$this->name,
            'id' => $this->id,
        ]);
    }
}
