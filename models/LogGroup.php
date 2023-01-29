<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log_group".
 *
 * @property int $id
 * @property string|null $name
 */
class LogGroup extends Basic
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Группа',
        ];
    }
}
