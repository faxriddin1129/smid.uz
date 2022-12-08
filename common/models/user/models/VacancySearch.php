<?php

namespace common\modules\user\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class VacancySearch extends Vacancy
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'safe'],
            [['type', 'place_count', 'position_id', 'percent', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['salary'], 'string'],
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
        $query = Vacancy::find();

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
            'type' => $this->type,
            'place_count' => $this->place_count,
            'position_id' => $this->position_id,
            'percent' => $this->percent,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        if ($this->title) {
            $this->title = strtolower($this->title);
            $query->andWhere("(LOWER(title::text) ILIKE '%$this->title%')");
        }

        $query->andWhere(['<>', 'status', self::STATUS_DELETED]);


        return $dataProvider;
    }

}