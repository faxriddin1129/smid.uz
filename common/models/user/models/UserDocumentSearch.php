<?php

namespace common\modules\user\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\user\models\UserDocument;

/**
 * UserDocumentSearch represents the model behind the search form of `common\modules\user\models\UserDocument`.
 */
class UserDocumentSearch extends UserDocument
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'file_id', 'user_id', 'type', 'sort'], 'integer'],
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
        $query = UserDocument::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!$this->load($params) && $params) {
            $this->setAttributes($params['filter']);
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'file_id' => $this->file_id,
            'user_id' => $this->user_id,
            'type' => $this->type,
            'sort' => $this->sort,
        ]);

        return $dataProvider;
    }
}
