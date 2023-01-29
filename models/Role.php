<?php

namespace app\models;

use Yii;
use app\models\Action;

/**
 * This is the model class for table "role".
 *
 * @property int $id
 * @property string|null $name
 * @property int $archive
 * @property int $archive_s
 * @property mixed $access_elements
 * @property mixed $menu
 * @property mixed $menu_s
 * @property mixed $menu_view
 */
class Role extends Basic
{

    public $archive_s;
    public $menu_view;

    public function init()
    {
        //разрешение на чтение таблицы через API
        self::$get_model_api = TRUE;


        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'role';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
            [['archive','archive_s','access_elements','menu','menu_view'],'safe'],
        ];
    }

    public function beforeSave($insert)
    {
        //соединяем текущий массив в строку для хранения
        $this->menu= (is_array($this->menu_view)) ? implode(',',$this->menu_view) : '';
        if ($this->menu =='') $this->menu = 0;

        return parent::beforeSave($insert);
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Роль',
            'archive' => 'Удален',
            'access_elements' => 'Элементы доступа',
            'menu' => 'Элементы меню',
            'menu_s' => 'Элементы меню',
            'menu_view' => 'Элементы меню',
        ];
    }


    /**
     * Есть ли роль у данного пользователя
     *
     * @param $role
     * @return bool
     */
    public function actionIs_role($role)
    {
        if ($this->access_elements!='') {
            $elements_array = explode(',',$this->access_elements);
            if (in_array($role, $elements_array)) return true;
        }
        return false;
    }

    /**
     * Формируем предварительный массив для отображения (Меню)
     *
     * @return array
     */
    public function getMenu_view_transform()
    {
        $out = false;
        if (!$this->isNewRecord && $this->menu!='') {
            $out= explode(',',$this->menu);
        }
        return $out;
    }

    public function clear_role()
    {
        $this->access_elements = '';
        $this->save();
    }

    public function set_role($params=FALSE)
    {
        $out=[];
        if ($params) {
            foreach ($params as $p) {
                $p_out = explode('_',$p);
                $p_in = $p_out[1];

                if (Action::find()->where(['id'=>$p_in])->exists()) {
                    $model_action = Action::findOne($p_in);
                    $out[] = $model_action->name;
                }
            }
        }

        if (isset($out) && is_array($out)) {
            $this->access_elements = implode(',',$out);
            $this->save();
        }
        return false;
    }


}
