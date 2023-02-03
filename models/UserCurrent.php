<?php

namespace app\models;
use Yii;

/**
 * Class UserCurrent
 * @package app\models
 * @property int $id
 * @property mixed $login
 * @property mixed $password
 * @property string $roles
 * @property int $archive
 * @property mixed $auth_key
 */
class UserCurrent extends User
{

    public static function load_post($model,$post)
    {

        if ($model) {
            $model->where(['!=','id',1]);

            $id = ($post['id']) ? $post['id'] : '';
            if ($id!='') {
                $ids = explode(',', $post['id']);
                $model->andWhere(['IN','id', $ids]);
            }

            $login = ($post['login']) ? $post['login'] : '';
            if ($login!='') {
                $model->andWhere('login LIKE "%'.$login.'%"');
            }

            $roles = ($post['roles']) ? $post['roles'] : '';

            if ($roles!='') {
                if (is_array($roles)) foreach ($roles as $role) {
                    $model->andWhere("roles = '".$role."' OR roles LIKE '".$role.",%' OR roles LIKE '%,".$role.",%' OR roles LIKE '%,".$role."'");
                }

            }

            $archive = ($post['archive']) ? $post['archive'] : '';
            switch ($archive) {
                //в работе
                case 2:
                    $model->andWhere(['archive'=>0]);
                    break;
                //удален
                case 1:
                    $model->andWhere(['archive'=>1]);
                    break;
                //остальные варианты
                default:
                    break;
            }




            /*
            $model_sqery = clone  $model;
            $sql = $model_sqery->createCommand()->getRawSql();
            file_put_contents("c:\\OSPanel\\domains\\zm\\my.txt","\nВыводимые данные:\n\n".print_r($sql,TRUE), FILE_APPEND | LOCK_EX );
            */

            return $model;
        }
    }

}
