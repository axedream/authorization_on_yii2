<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Menu".
 *
 * @property int $id
 * @property string|null $label
 * @property string|null $icon
 * @property string|null $url
 * @property int|null $parent_id
 */
class Menu extends Basic
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id'], 'integer'],
            [['label', 'icon', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => 'Label',
            'icon' => 'Icon',
            'url' => 'Url',
            'parent_id' => 'Parent ID',
        ];
    }
}
