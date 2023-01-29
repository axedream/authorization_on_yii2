<?php
namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Class Basic
 * @package app\models
 */
class BasicForm extends Model
{

    /**
     * Функция загразуки с ajax форма
     *
     * @param mixed $_data
     * @return bool
     */
    public function load_ajax($_data=FALSE) {
        if ($_data) {
            $modelName = \yii\helpers\StringHelper::basename(get_class($this));
            if (isset($_data[$modelName])) {
                $data = $_data[$modelName];
                $array_atr = $this->attributes();
                foreach ($this->attributes() as $atr) {
                    if (isset($data[$atr]) && $data[$atr]!='id') {
                        $this->$atr = $data[$atr];
                    }
                }
                return true;
            }
        }
        return false;
    }
}