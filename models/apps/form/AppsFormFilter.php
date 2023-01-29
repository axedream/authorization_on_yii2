<?php
namespace app\models\apps\form;

use yii\base\Model;

/**
 * Class AppsFormFilter
 * @package app\models\apps\form
 * @property mixed $apps_status_from
 * @property string $apps_platform_form
 */
class AppsFormFilter extends Model
{
    public $apps_status_from;
    public $apps_platform_form;

    public function rules()
    {
        return [
            [['apps_status_from','apps_platform_form'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'apps_status_from' => 'Статус',
            'apps_platform_form' => 'Платформа'
        ];
    }

}