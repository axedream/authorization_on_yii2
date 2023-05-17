<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "anh_domoos.api_logger".
 *
 * @property int $id
 * @property string $date_add
 * @property string $ip
 * @property string $in_data
 * @property string $out_data
 */
class Api_logger extends Basic
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'api_logger';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_add'], 'safe'],
            [['in_data', 'out_data'], 'string'],
            [['ip'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date_add' => 'Date Add',
            'ip' => 'Ip',
            'in_data' => 'In Data',
            'out_data' => 'Out Data',
        ];
    }
}
