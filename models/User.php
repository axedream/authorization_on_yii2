<?php

namespace app\models;
use yii\web\IdentityInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service_locator;
use Yii;

/**
 * Class User
 * @package app\models
 * @property int $id
 * @property mixed $login
 * @property mixed $password
 * @property string $roles
 * @property int $archive
 * @property mixed $auth_key
 */
class User extends Basic implements \yii\web\IdentityInterface
{

    public $roles_view;
    public $archive_s;
    public $role_models;

    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['login','password','archive','archive_s','roles','roles_view','auth_key'], 'safe'],
            [['login'],'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Логин пользователя',
            'password' => 'Пароль',
            'roles_view' => 'Роли пользователя',
            'archive' => 'Удалена',
        ];
    }


    /**
     * Получаем массив меню текущего пользователя
     *
     * @return array
     */
    public function getMenu()
    {
        //минимальный раздел меню
        $item_menu = ['1'];

        //получение меню исходя из ролей пользователя
        $id = ($this->id) ? $this->id : \Yii::$app->user->identity->id;
        if ($id && self::find()->where(['id'=>$id])->exists()) {
            $model = self::findOne($id);
            if ($model->roles_view_transform) foreach ($model->roles_view_transform as $m) {
                if (Role::find()->where(['id'=>$m])->exists()) {
                    $model->role_models[] = Role::findOne($m);
                }
            }
            foreach ($model->role_models as $role_model) {
                if ($role_model->menu!='') {
                    $menu = explode(',', $role_model->menu);
                    foreach ($menu as $mw) {
                        if (!in_array($mw,$item_menu)) {
                            $item_menu[] = $mw;
                        }
                    }
                }
            }
        }

        //генерация меню из базы данных
        if (Yii::$app->user->identity->id == 1) {
            $item_menu = Menu::find()->all();
        }

        foreach ($item_menu as $item) {
            if (Menu::find()->where(['id'=>$item])->exists()) {
                $mm = Menu::findOne($item);
                $out[] = [
                    'label' => $mm->label,
                    //'icon' => $mm->icon,
                    'url' => $mm->url
                ];
            }
        }



        return $out;
    }

    /**
     * Получаем массив меню текущего пользователя
     *
     * @return array
     */
    public function getAccess($test_access_element)
    {
        $item_access_elements = ['main_index'];
        $id = ($this->id) ? $this->id : \Yii::$app->user->identity->id;
        if ($id && self::find()->where(['id'=>$id])->exists()) {
            $model = self::findOne($id);
            if ($model->roles_view_transform) foreach ($model->roles_view_transform as $m) {
                if (Role::find()->where(['id'=>$m])->exists()) {
                    $model->role_models[] = Role::findOne($m);
                }
            }
            foreach ($model->role_models as $role_model) {
                if ($role_model->menu!='') {
                    $menu = explode(',', $role_model->access_elements);
                    //добавляем элемент в меню если такого еще нет
                    foreach ($menu as $mw) {
                        if (!in_array($mw,$item_access_elements)) {
                            $item_access_elements[] = $mw;
                        }
                    }
                }
            }
        }
        return (in_array($test_access_element,$item_access_elements) || in_array('all',$item_access_elements) ) ? TRUE : FALSE;
    }

    /**
     * Формируем предварительный массив для отображения (Ролей)
     *
     * @return array
     */
    public function getRoles_view_transform()
    {
        $out = false;
        if (!$this->isNewRecord && $this->roles!='') {
            $out= explode(',',$this->roles);
        }
        return $out;
    }

    /**
     * Формируем для отображения в общем листигни списки (Роли)
     *
     * @return string
     */
    public function getRoles_list_string()
    {
        $out = '';

        if ($this->roles!='') {
            $ids = explode(',',$this->roles);

            foreach ($ids as $id) {
                if(Role::find()->where(['id'=>$id])->exists()) {
                    $model_role = Role::findOne(['id'=>$id]);
                    $out .= ($out=='' ? '' : ','."<br>" ).'<span class="label label-info">'.$model_role->name.'</span>';
                }
            }
            if ($out!='') $out ='<div style="font-size: 16px; font-weight: normal !important;">'.$out.'</div>';

        }
        return $out;
    }

    public function beforeSave($insert)
    {
        $last = false;
        $_atr_old = $this->oldAttributes;   $atr_old = $_atr_old['archive'];
        $_atr_new = $this->attributes;      $atr_new = $_atr_new['archive'];

        //соединяем текущий массив в строку для хранения
        if (isset($this->roles_view)) {
            $this->roles= (is_array($this->roles_view)) ? implode(',',$this->roles_view) : '';
        }

        return parent::beforeSave($insert);
    }


    public static function findIdentityByAccessToken($token, $type = null) { }

    public static function findByLogin($login)
    {
        if (self::find()->where(['login'=>$login,'archive'=>0])->exists()) {
            return self::findOne(['login'=>$login]);
        }
        return false;
    }

    public static function findIdentity($id)
    {
        if (self::find()->where(['id'=>$id])->exists()) {
            return self::findOne($id);
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function validatePassword($password)
    {
        return $this->password == md5($password);
    }

    public function  setPassword($password)
    {
        return $this->password = md5($password);
    }

    public function getAuthKey() { }

    public function setAuthKey() {
        $this->auth_key = md5(self::getNowDateTime());
    }

    public function validateAuthKey($authKey) { }

    /**
     * Получаем актуальную дату время
     *
     * @return string
     * @throws \Exception
     */
    public static function getNowDateTime()
    {
        $dateFile = new \DateTime();
        return $dateFile->format('Y-m-d H:i:s');
    }

    public static function getNowDate()
    {
        $dateFile = new \DateTime();
        return $dateFile->format('Y-m-d');
    }

    /**
     * Возвращает ID пользователя по его ключу
     *
     * @param bool $key
     * @return bool|int
     */
    public static function getApiAuth($key=FALSE)
    {
        if ($key && !empty($key) && User::find()->where(['auth_key'=>$key])->exists()) {
            $model = User::findOne(['auth_key'=>$key]);
            return $model->id;
        }
        return FALSE;
    }

}
