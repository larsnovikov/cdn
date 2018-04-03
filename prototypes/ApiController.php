<?php

namespace app\prototypes;

use yii\base\Controller;

class ApiController extends Controller
{
    const STATUS_OK = 'ok';
    const STATUS_FAIL = 'fail';

    /**
     * @param \yii\base\Action $action
     * @return bool
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
