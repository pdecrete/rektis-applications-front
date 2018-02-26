<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Choice;

/**
 * ChoiceSearch represents the model behind the search form about `app\models\Choice`.
 */
class ChoiceSearch extends Choice
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['count', 'school_type', 'prefecture_id'], 'integer'],
            [['specialty'], 'string', 'max' => 8],
            [['position'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
        $query = Choice::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'count' => $this->count,
            'school_type' => $this->school_type,
            'prefecture_id' => $this->prefecture_id,
        ]);

        $query->andFilterWhere(['like', 'specialty', $this->specialty])
            ->andFilterWhere(['like', 'position', $this->position]);

        return $dataProvider;
    }
}
