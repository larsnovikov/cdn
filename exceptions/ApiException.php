<?php

namespace exceptions;

/**
 * Created by PhpStorm.
 * User: Lars
 * Date: 06.01.2018
 * Time: 14:08
 */
class ApiException extends \yii\base\ErrorHandler
{
    /**
     * @param \Error|\Exception $exception
     */
    protected function renderException($exception)
    {
        if (\Yii::$app->has('response')) {
            $response = \Yii::$app->getResponse();
        } else {
            $response = new \yii\base\Response();
        }
        $response->data = [
            'status' => 'error',
            'data' => [
                'message' => $exception->getMessage()
            ]
        ];
        $response->send();
    }
}
