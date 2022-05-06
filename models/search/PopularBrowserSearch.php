<?php

namespace app\models\search;

use app\helpers\AppHelper;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Log;
use yii\db\Expression;

/**
 * PopularBrowserSearch represents the model behind the search form about `app\models\Log`.
 */
class PopularBrowserSearch extends Log
{
    public function attributes()
    {
        return array_merge(parent::attributes(), ['date_from', 'date_to', 'usageCount']);
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

    public function applyDateConditions($query){
        $query->andFilterWhere(['>=', 'day', $this->date_from])
            ->andFilterWhere(['<=', 'day', $this->date_to]);
        return $query;
    }

    public function applyOtherFilterConditions($query){
        $query->andFilterWhere(['os_id' => $this->os_id])
            ->andFilterWhere(['architecture_id' => $this->architecture_id]);
        return $query;
    }

    public function applyConditions($query)
    {
        $this->applyDateConditions($query);
        $this->applyOtherFilterConditions($query);
        return $query;
    }

    public function getMostPopularBrowsersIds()
    {
        $mostPopularBrowsersQuery = self::find()->select(['browser_id', 'COUNT(browser_id) as usageCount']);
        $this->applyConditions($mostPopularBrowsersQuery);
        return $mostPopularBrowsersQuery
            ->groupBy('browser_id')
            ->orderBy('usageCount DESC')
            ->limit(3)
            ->column();
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
        $query = self::find()
            ->alias('mainQuery');

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

        $query->andFilterWhere(['IN', 'browser_id', $this->getMostPopularBrowsersIds()]);
        $this->applyConditions($query);

        $subQuery = self::find()
            ->select('COUNT(id)')
            ->alias('totalCountQuery')
            ->andWhere(['totalCountQuery.day' => new Expression('mainQuery.day')]);

        $this->applyOtherFilterConditions($subQuery);

        $query->select(['day as date', 'browser_id', 'COUNT(browser_id)*100/(' . $subQuery->createCommand()->rawSql . ') as usageCount']);

        $query->groupBy('day, browser_id');
        $query->orderBy('day');

        return $dataProvider;
    }
}
