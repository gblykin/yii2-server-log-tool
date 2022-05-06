<?php

namespace app\models\search;

use app\helpers\AppHelper;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Log;

/**
 * RequestsCountSearch represents the model behind the search form about `app\models\Log`.
 */
class RequestsCountSearch extends Log
{
    public function attributes()
    {
        return array_merge(parent::attributes(), ['date_from', 'date_to', 'requestsCount']);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['architecture_id', 'os_id'], 'integer'],
            [['date_from', 'date_to'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = self::find()->select(['day as date', 'COUNT(id) as requestsCount']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (empty($this->date_from)){
            $this->date_from = Date('Y-m-d', AppHelper::startOfMonth());
        }

        if (empty($this->date_to)){
            $this->date_to = Date('Y-m-d', AppHelper::finishOfDay(time()));
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['os_id' => $this->os_id])
            ->andFilterWhere(['architecture_id' => $this->architecture_id]);

        $query->andFilterWhere(['>=', 'day', $this->date_from])
            ->andFilterWhere(['<=', 'day', $this->date_to]);


        $query->groupBy('day');
        return $dataProvider;
    }
}
