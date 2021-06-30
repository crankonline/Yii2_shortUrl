<?php

namespace app\controllers;

use phpDocumentor\Reflection\DocBlock\Tags\PropertyWrite;
use Yii;
use yii\rest\ActiveController;
use app\models\Shorturl;

class UrlController extends ActiveController
{
    public $modelClass = 'app\models\Shorturl';

    private function validateUrlFormat($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }

    private function shuffle(){
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle($permitted_chars), 0, 6);
    }

    private function create_short_url($url, $shorturl){
        $parse = parse_url($url);
        return "${parse['scheme']}://${parse['host']}/${shorturl}";
    }

    protected function check_url_exists($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch,  CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return (!empty($response) && $response != 404);
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        return $actions;
    }

    public function actionView()
    {
        return "Kartina repina";
    }

    public function actionCreate()
    {
        $model = new Shorturl();
        $url = Yii::$app->request->post('url'); //проверка на нул

        if ($this->validateUrlFormat($url) == false) {
            throw new \yii\web\BadRequestHttpException("URL имеет неправильный формат.");
        }

        $count_records = Shorturl::find()
            ->where(['url'=>$url])
            ->one();
        if(!is_null( $count_records)){
            return $this->create_short_url($url,$count_records['shorturl']);
        }

        if(!$this->check_url_exists($url)){
            throw new \yii\web\BadRequestHttpException("URL не существует.");
        }

        $model->url = $url;
        $model->shorturl = $this->shuffle();
        $model->creating_date_time = date('Y-m-d H:i:s');
        $model->counter=1;
        $model->save();

        return $this->create_short_url($url,$model->shorturl);

    }


}
