<?php

namespace app\prototypes;

use yii\base\Controller;

/**
 * Class ApiController
 * @package app\prototypes
 */
class ApiController extends Controller
{

    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function beforeAction($action): bool
    {
        $urlData = parse_url(\Yii::$app->request->getUrl());
        if (isset($urlData['query'])) {
            parse_str($urlData['query'], $queryParams);
            \Yii::$app->request->bodyParams = $queryParams;
        }
        return parent::beforeAction($action);
    }
}
