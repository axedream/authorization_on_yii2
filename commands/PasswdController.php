<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
namespace app\commands;

use app\models\User;
use yii\console\Controller;


/**
 * Class Create_fbController
 * @package app\commands
 * @property mixed $id
 * @property mixed $passwd
 *
 * php yii passwd/run id=12 passwd=eee
 */
class PasswdController extends Controller
{

    public function actionRun($id,$paaswd)
    {
        if (!$id  || !$paaswd) {
            echo "You must enter ID and PASSWD from USERS\n";
            return FALSE;
        }
        echo "ID: ".$id. " PASSWD: ".$paaswd;
        if (User::find()->where(['id'=>$id])->exists()) {
            $model = User::findOne($id);
            $model->password = $paaswd;
            $model->update();
            echo "Passwd successfule change\n";
        }
        return TRUE;
    }
}
