<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "action_groupe".
 *
 * @property int $id
 * @property string|null $name
 */
class ActionGroupe extends Basic
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'action_groupe';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }
}
