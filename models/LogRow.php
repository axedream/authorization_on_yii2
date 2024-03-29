<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log_row".
 *
 * @property int $id
 * @property int|null $log_action_name_uid
 * @property int|null $user_id
 * @property string|null $action_date
 * @property string|null $post
 * @property string|null $before_data
 * @property string|null $after_data
 */
class LogRow extends Basic
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log_row';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['log_action_name_uid', 'user_id'], 'integer'],
            [['action_date'], 'safe'],
            [['post', 'before_data', 'after_data'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'log_action_name_uid' => 'Log action Name UID',
            'user_id' => 'User ID',
            'action_date' => 'Action Date',
            'post' => 'Post',
            'before_data' => 'Before Data',
            'after_data' => 'After Data',
            'date_time_convert' => 'Дата и время действия'
        ];
    }

    public function getDate_time_convert()
    {
        if ($this->action_date) {
            $_date_src = new \DateTime($this->action_date);
            return $_date_src->format('d-m-Y H:i:s');
        }
        return false;
    }

    public function getLog_action_name()
    {
        return $this->hasOne(LogActionName::className(), ['uid' => 'log_action_name_uid']);
    }

    public function getLog_group()
    {
        if (isset($this->log_action_name)) {
            return $this->log_action_name->log_group;
        }
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function load_post($model,$post)
    {
        if ($model) {

            $action_date = ($post['action_date']) ? $post['action_date'] : 0;
            if ($action_date) {
                $_d = explode('|', $action_date);
                $action_date_start = (isset($_d[0])) ? trim($_d[0]) : 0;
                $action_date_end = (isset($_d[1])) ? trim($_d[1]) : 0;
                if ($action_date_start) $model->andWhere('DATE(action_date)>= STR_TO_DATE("' . $action_date_start . '","%d-%m-%Y")');
                if ($action_date_end) $model->andWhere('DATE(action_date)<= STR_TO_DATE("' . $action_date_end . '","%d-%m-%Y")');
            }

            $action_name = ($post['action_name']) ? $post['action_name'] : 0;
            if ($action_name) {
                $model->andWhere(['log_action_name_uid'=>$action_name]);
            }

            $login = ($post['login']) ? $post['login'] : 0;
            if ($login) {
                $model->andWhere(['user_id'=>$login]);
            }


            $action_group = ($post['action_group']) ? $post['action_group'] : 0;
            if ($action_group) {
                $model_log_action_names = LogActionName::find()->select(['uid'])->where(['log_group_id'=>$action_group])->asArray()->all();
                foreach ($model_log_action_names as $mlan) {
                    $ln[] = $mlan['uid'];
                }
                if (count($ln)) {
                    $model->andWhere(['IN','log_action_name_uid',$ln]);
                }
            }


        }


        return $model;
    }

    public function beforeSave($insert)
    {


        if (mb_strlen($this->post,'UTF-8')>32000) { $this->post = mb_substr($this->post,0,32000); }
        if (mb_strlen($this->before_data,'UTF-8')>32000) { $this->before_data = mb_substr($this->before_data,0,32000); }
        if (mb_strlen($this->after_data,'UTF-8')>32000) { $this->after_data = mb_substr($this->after_data,0,32000); }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}
