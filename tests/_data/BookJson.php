<?php

namespace data;

use voskobovich\linker\LinkerBehavior;
use yii\helpers\Json;

/**
 * Class BookJson
 * @package data
 */
class BookJson extends Book
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'linkerBehavior' => [
                'class' => LinkerBehavior::className(),
                'relations' => [
                    'author_list' => [
                        'authors',
                        'get' => function ($value) {
                            return Json::encode($value);
                        },
                        'set' => function ($value) {
                            return Json::decode($value);
                        },
                    ],
                    'review_list' => [
                        'reviews',
                        'get' => function ($value) {
                            return Json::encode($value);
                        },
                        'set' => function ($value) {
                            return Json::decode($value);
                        },
                    ]
                ]
            ]
        ];
    }
}
