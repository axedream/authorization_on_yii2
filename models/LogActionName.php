<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log_action_name".
 *
 * @property int $id
 * @property int $uid
 * @property int $log_group_id
 * @property string|null $name
 * @property string|null $controller
 * @property string|null $action
 * @property string|null $model
 */
class LogActionName extends Basic
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log_action_name';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid','log_group_id'],'integer'],
            [['name', 'controller', 'action', 'model'], 'string', 'max' => 255],
        ];
    }

    public function getLog_group()
    {
        return $this->hasOne(LogGroup::className(), ['id' => 'log_group_id']);
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'UID',
            'log_group_id' => 'Goup',
            'name' => 'Action',
            'controller' => 'Controller',
            'action' => 'Action',
            'model' => 'Model',
        ];
    }
}
