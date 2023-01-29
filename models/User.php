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
 * @property string $role
 * @property string $roles
 * @property int $archive
 * @property int $username
 * @property int $archive_s
 * @property mixed $roles_view
 * @property mixed $auth_key
 * @property mixed $token_telegram_connect
 * @property int $user_type_id
 * @property int $user_group_id
 * @property int $buyer_id
 * @property int $chat_id_telegram
 * @property int $user_group_farm_id
 * @property mixed $user_group_farms
 *
 */
class User extends Basic implements \yii\web\IdentityInterface
{

    public $roles_view;
    public $archive_s;
    public $role_models;

    public $user_group_farms;

    public static function tableName()
    {
        return 'crm.user';
    }

    public function rules()
    {
        return [
            [['id','user_type_id','user_group_id','buyer_id','user_group_farm_id','chat_id_telegram'], 'integer'],
            [['login','password','archive','archive_s','roles','roles_view','auth_key','token_telegram_connect','user_group_farms'], 'safe'],
            //валидация поля при условии
            [['user_group_id'],'required',
                'when' => function($model) {
                    return ($model->user_type_id ==3 && $model->user_group_id =='');
                },
                'whenClient' => 'function (attribute, value) {
                    if ($("#user-user_type_id").val() == 3 && $("#user-user_group_id").val() =="") {
                        alert("Ошибка сохранения! Поле [Группа пользователя] не заполнено!");
                        return true;
                    } 
                    return false; 
                }',
                'message' => "Группа пользователя должна быть обязательно выбрана!"
                ],
            [['username'], 'string', 'max' => 255],
            [['login'],'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Логин пользователя',
            'password' => 'Пароль',
            'username' => 'Имя',
            'roles_view' => 'Роли пользователя',
            'archive' => 'Удалена',
            'user_type_id' => 'Тип пользователя',
            'user_group_id' => 'Группа пользователя',
            'user_group_farm_id' => 'Группа Farm',
            'user_group_farms' => 'Группы Farm',
            'buyer_id' => 'ID Buyer',
            'token_telegram_connect' => 'Токен для авторизации бота',
            'chat_id_telegram' =>'Chat id telegram',
        ];
    }

    /**
     * Получаем Apps_id текущего пользователя
     *
     * @return array
     */
    public static function apps_id()
    {
        $apps_id = [];
        $msua = AppsAccessUser::find()->select('apps_id')->where(['user_id'=>Yii::$app->user->identity->id])->asArray()->all();
        if ($msua) foreach ($msua as $mua) {
            $apps_id[] = $mua['apps_id'];
        }
        $msga = AppsAccessGroup::find()->select('apps_id')->where(['group_id'=>Yii::$app->user->identity->user_group_id])->asArray()->all();
        if ($msga) foreach ($msga as $mga) {
            if (!in_array($mga['apps_id'],$apps_id)) {
                $apps_id[] = $mga['apps_id'];
            }

        }
        return $apps_id;
    }


    /**
     * @return string
     * @throws \Exception
     */
    public static function getNew_token_telegram() {
        return substr(md5(self::getNowDateTime().rand(1,100000)),0,15);
    }

    /**
     * Получаем Apps_id текущего выбранного пользователя
     *
     * @return array
     */
    public static function apps_user_id($user_id)
    {
        $apps_id = [];
        if ($user_id && User::find()->where(['id'=>$user_id])->exists()) {
            $model_user = User::findOne($user_id);

            $msua = AppsAccessUser::find()->select('apps_id')->where(['user_id'=>$user_id])->asArray()->all();
            if ($msua) foreach ($msua as $mua) {
                $apps_id[] = $mua['apps_id'];
            }

            $msga = AppsAccessGroup::find()->select('apps_id')->where(['group_id'=>$model_user->user_group_id])->asArray()->all();
            if ($msga) foreach ($msga as $mga) {
                if (!in_array($mga['apps_id'],$apps_id)) {
                    $apps_id[] = $mga['apps_id'];
                }

            }
            return $apps_id;
        }

        return  FALSE;
    }

    /**
     * Получаем лимиты по пользователям (в зависимости от типа
     *
     * @param bool $type
     * @return bool|int|null
     */
    public function get_limit_type($type=false)
    {
        if ($type && UserLimit::find()->where(['user_id'=>$this->id,'big_accounts_type_id'=>$type])->exists()) {
            $model_user_limit =  UserLimit::findOne(['user_id'=>$this->id,'big_accounts_type_id'=>$type]);
            return $model_user_limit->lim;
        }

        return false;
    }


    /**
     * Все лимиты по текущему пользователю
     *
     * @return array
     */
    public static function get_all_limit()
    {
        $limit = [];

        //получаем все типы аккаунтов
        $model_type_accounts = BigAccountsType::find()->all();

        if ($model_type_accounts) foreach ($model_type_accounts as $at) {

            $model_req_form = new ReqForm();
            $model_req_form->req_type = $at->id;
            $model_req_form->get_req_use_limit();

            if ($model_req_form->req_type == 8 || $model_req_form->req_type == 9) {
                //$out = $model_req_form;
                //file_put_contents("c:\\OpenServer\\domains\\zm\\my.txt","\nВыводимые данные:\n\n".print_r($out,TRUE), FILE_APPEND | LOCK_EX );
            }



            if ($model_req_form->req_limit) {

                if (in_array($model_req_form->req_type,ReqForm::exclude()) || in_array($model_req_form->req_type,ReqForm::exclude2())) {

                    if (in_array($model_req_form->req_type,ReqForm::exclude())) {
                        //ключ проверки входждения в результат лимитов - только уникальные ключи
                        $key = TRUE;
                        foreach ($limit as $l) {
                            if (in_array($l['id'],ReqForm::exclude())) {
                                //если найдено хоть одно вхождение -> нельзя добавлять повторы
                                $key = FALSE;
                            }
                        }
                        //если ключ истина, то еще нет не одного вхождения -> добавляем
                        if ($key) {
                            $limit[] = [
                                'id' => $at->id,
                                'name' => ReqForm::limit_name(1),
                                'limit' => $model_req_form->req_limit,
                                'use' => $model_req_form->req_use_limit,
                                'rest' => $model_req_form->req_rest
                            ];
                        }

                    }
                    if (in_array($model_req_form->req_type,ReqForm::exclude2())) {

                        //ключ проверки входждения в результат лимитов - только уникальные ключи
                        $key = TRUE;
                        foreach ($limit as $l) {
                            if (in_array($l['id'], ReqForm::exclude2())) {
                                //если найдено хоть одно вхождение -> нельзя добавлять повторы
                                $key = FALSE;
                            }
                        }
                        //если ключ истина, то еще нет не одного вхождения -> добавляем
                        if ($key) {
                            $limit[] = [
                                'id' => $at->id,
                                'name' => ReqForm::limit_name(2),
                                'limit' => $model_req_form->req_limit,
                                'use' => $model_req_form->req_use_limit,
                                'rest' => $model_req_form->req_rest
                            ];

                        }
                    }
                } else {

                    //в случае если нет исключений
                    $limit[] = [
                        'id' => $at->id,
                        'name' => $at->name,
                        'limit' => $model_req_form->req_limit,
                        'use' => $model_req_form->req_use_limit,
                        'rest' => $model_req_form->req_rest
                    ];
                }
            }
        }
        return $limit;
    }


    /**
     * Получаем лимит пользователя в зависимости от типа
     *
     * @param bool $type
     * @return bool|int|null
     */
    public static function user_limit($type=false)
    {
        if (!Yii::$app->user->isGuest) {
            $model = User::findOne(Yii::$app->user->identity->id);
            if ($model && $type && UserLimit::find()->where(['user_id'=>$model->id,'big_accounts_type_id'=>$type])->exists()) {
                $model_user_limit =  UserLimit::findOne(['user_id'=>$model->id,'big_accounts_type_id'=>$type]);
                return $model_user_limit->lim;
            }
        }
        return false;
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
                    'icon' => $mm->icon,
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

    public function getGroupe_farms()
    {
        $uga = [];
        $mugfs = UserGroupFarm::find()->asArray()->all();

        if ($this->id) {
            foreach ($mugfs as $mugf) {
                $users_id = explode(',',$mugf['team_users_id']);

                if ($users_id && is_array($users_id) && in_array($this->id,$users_id) ) {
                    $uga[] = $mugf['id']    ;
                }
            }
        }
        return $uga;
    }

    public function save_group_farms()
    {
        $mugfs = UserGroupFarm::find()->all();
        foreach ($mugfs as $mgf) {
            //сначало везде удаляем
            $mgf->team_users_id = implode(',',array_diff($mgf->team_users_id,[$this->id]));
            $mgf->update();

            //если не пусто - нужно добавить
            if ($this->user_group_farms != '') {
                if(in_array($mgf->id,$this->user_group_farms)) {
                    if ($mgf->team_users_id=='') {
                        $mgf->team_users_id = $this->id;
                    } else {
                        $mgf->team_users_id = $mgf->team_users_id.','.$this->id;
                    }
                    $mgf->update();
                }
            }
        }
    }

    public function beforeSave($insert)
    {
        //$this->save_group_farms();

        $last = false;
        $_atr_old = $this->oldAttributes;   $atr_old = $_atr_old['archive'];
        $_atr_new = $this->attributes;      $atr_new = $_atr_new['archive'];

        //если отправили в архив
        if ($atr_old == 0 && $atr_new == 1) {
            $this->token_telegram_connect = '';
            $this->chat_id_telegram  = 0;
        }

        //если восстановили из архива
        if ($atr_old == 1 && $atr_new == 0) {
            $this->token_telegram_connect = self::getNew_token_telegram();
        }

        //если новая запись
        if ($this->isNewRecord) {
            $this->token_telegram_connect = self::getNew_token_telegram();
        }

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

    public function getUser_type()
    {
        return $this->hasOne(UserType::className(), ['id' => 'user_type_id']);
    }

    public function getUser_group()
    {
        return $this->hasOne(UserGroup::className(), ['id' => 'user_group_id']);
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
