<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use app\models\Shorturl;

class UrlController extends \yii\web\Controller
{
    private function errorResponse($message) {
        Yii::$app->response->statusCode = 400;
        return $this->asJson(['error' => $message]);
    }

    private function validateUrlFormat($url): mixed
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }

    private function shuffle(): string
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle($permitted_chars), 0, 6);
    }

    private function create_short_url($url, $shorturl): string
    {
        $parse = parse_url($url);
        return "${parse['scheme']}://${parse['host']}/${shorturl}";
    }

    protected function check_url_exists($url): bool
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return (!empty($response) && $response != 404);
    }

    public function actionIndex()
    {
        $hash = Yii::$app->request->get('hash');
        $record = Shorturl::find()
            ->where(['shorturl' => $hash])
            ->one();
        if (!is_null($record)) {
            $record->counter += 1;
            $record->save();
            $return = [
                'url' => $record->url,
                'count' => $record->counter
            ];
            return $this->asJson($return);
        } else {
            return $this->errorResponse("Указанный hash не найден.");
        }
    }

    public function actionCreate()
    {
        $model = new Shorturl();

        $request = json_decode(Yii::$app->request->getRawBody());
        $url = $request->url;
        if ($this->validateUrlFormat($url) == false) {
            return $this->errorResponse("URL имеет неправильный формат.");
        }

        $record = Shorturl::find()
            ->where(['url' => $url])
            ->one();
        if (!is_null($record)) {
            //$record = Shorturl::findOne($count_records['id']);
            $record->counter += 1;
            $record->save();
            return $this->create_short_url($url, $record->shorturl);
        }

        if (!$this->check_url_exists($url)) {
            return $this->errorResponse("URL не существует.");
        }

        $model->url = $url;
        $model->shorturl = $this->shuffle();
        $model->creating_date_time = date('Y-m-d H:i:s');
        $model->counter = 1;
        $model->save();

        return $this->create_short_url($url, $model->shorturl);

    }


}
