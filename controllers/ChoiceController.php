<?php
namespace app\controllers;

use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Choice;

class ChoiceController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'select-choices' => ['POST']
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['select-choices'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Search term is provided via post. Also via post, the current selections are provided.
     */
    public function actionSelectChoices($prefecture = null, $specialty = null, $school_type = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $term = \Yii::$app->request->post('term', null);
        $selections = \Yii::$app->request->post('Application', []);
        $selected_ids = array_map(function ($selection) {
            if (array_key_exists('choice_id', $selection)) {
                return intval($selection['choice_id']);
            }
        }, $selections);
        $selected_ids = array_filter($selected_ids);

        $out = [
            'results' => [
                ['id' => '', 'text' => '' . $term]
            ]
        ];

        $choices = Choice::getChoices(
            $prefecture,
            $specialty,
            $school_type,
            $term,
            $selected_ids
        );
        $out['results'] = array_map(function ($choice) {
            return ['id' => $choice->id, 'text' => $choice->position];
        }, $choices);


        // $choices_query = Choice::getChoices(
        //     $prefecture,
        //     $specialty,
        //     $school_type,
        //     $term,
        //     $selected_ids,
        //     true // get query object
        // );
        // $choices_query = $choices_query->select(['id', 'position AS text']);
        // $command = $choices_query->createCommand();
        // $data = $command->queryAll();
        // $out['results'] = array_values($data);

        return $out;
    }
}
