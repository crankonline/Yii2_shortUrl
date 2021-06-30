<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "shorturl".
 *
 * @property int $id
 * @property string $url
 * @property string $shorturl
 * @property string $creating_date_time
 * @property int $counter
 */
class Shorturl extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shorturl';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url', 'shorturl', 'creating_date_time', 'counter'], 'required'],
            [['url', 'shorturl'], 'string'],
            [['creating_date_time'], 'safe'],
            [['counter'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'shorturl' => 'Shorturl',
            'creating_date_time' => 'Creating Date Time',
            'counter' => 'Counter',
        ];
    }
}
