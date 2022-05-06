<?php

namespace app\models\search;

use app\helpers\AppHelper;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Log;
use yii\db\Expression;

/**
 * LogSearch represents the model behind the search form about `app\models\Log`.
 */
class LogSearch extends Log
{
    public function attributes()
    {
        return array_merge(parent::attributes(), ['date_from', 'date_to', 'requestsCount', 'urlUrl', 'browserTitle']);
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

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'urlUrl' => Yii::t('app', 'Most popular Url'),
            'browserTitle' => Yii::t('app', 'Most popular Browser'),
        ]);

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

        $this->applyConditions($query);

        $subQueryPopularUrl = self::find()
            ->select('url.url as urlUrl')
            ->alias('popularUrlQuery')
            ->joinWith(['url'])
            ->andWhere(['popularUrlQuery.day' => new Expression('mainQuery.day')])
            ->groupBy('url_id')
            ->orderBy('COUNT(url_id) DESC')
            ->limit(1);

        $this->applyOtherFilterConditions($subQueryPopularUrl);

        $subQueryPopularBrowser = self::find()
            ->select('browser.title as browserTitle')
            ->alias('popularBrowserQuery')
            ->joinWith(['browser'])
            ->andWhere(['popularBrowserQuery.day' => new Expression('mainQuery.day')])
            ->groupBy('browser_id')
            ->orderBy('COUNT(browser_id) DESC')
            ->limit(1);

        $this->applyOtherFilterConditions($subQueryPopularBrowser);


        $query->select([
            'day as date',
            'COUNT(mainQuery.id) as requestsCount',
            '(' . $subQueryPopularUrl->createCommand()->rawSql . ') as urlUrl',
            '(' . $subQueryPopularBrowser->createCommand()->rawSql . ') as browserTitle',
        ]);

        $query->groupBy('day');

        $dataProvider->sort->defaultOrder = ['day' => SORT_ASC];

        $dataProvider->sort->attributes['browserTitle'] = [
            'asc' => ['browserTitle' => SORT_ASC],
            'desc' => ['browserTitle' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['urlUrl'] = [
            'asc' => ['urlUrl' => SORT_ASC],
            'desc' => ['urlUrl' => SORT_DESC],
        ];

        return $dataProvider;
    }
}
