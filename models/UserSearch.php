<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form of `app\models\User`.
 */
class UserSearch extends User
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id','archive','user_type_id','user_group_id','buyer_id'], 'integer'],
            [['login', 'password', 'username', 'roles','archive_s'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        if ($this->buyer_id) {
            $query->andWhere(['buyer_id'=>$this->buyer_id]);
        }

        $query->andFilterWhere(['user_group_id'=>$this->user_group_id]);

        $query->andFilterWhere(['like', 'login', $this->login])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'roles', $this->roles])
            ->andFilterWhere(['user_type_id'=>$this->user_type_id]);
        if (in_array($this->archive_s,[1,2,3])) {
            if ($this->archive_s == 1) {
                $query->andWhere(['archive' => 1]);
            }

            if ($this->archive_s == 2) {
                $query->andWhere(['archive'=>0]);
            }

        } else {
            $query->andWhere(['archive'=>0]);
        }



        return $dataProvider;
    }
}
