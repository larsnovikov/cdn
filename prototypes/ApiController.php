<?php

namespace app\prototypes;

use yii\rest\ActiveController;
use yii\web\Response;

/**
 * Class ApiController
 * @package app\prototypes
 */
class ApiController extends ActiveController
{

    /**
     * @var string
     */
    public $modelClass = '';

    /**
     * Статус выполнения команды
     *
     * @var string
     */
    private $status = 'success';
    /**
     * Проверяет, может ли пользователь обратиться к серверу
     *
     * @throws \Exception
     */
    private function canRequest()
    {
        if (\Yii::$app->request->method !== 'POST') {
            throw new \Exception('Invalid request method');
        }

        $userIp = \Yii::$app->request->getUserIP();

        // в списке фронтендов поищим с этим ip
        if (!array_key_exists($userIp, \Yii::$app->params['cdn']['frontends'])) {
            throw new \Exception('Frontend server not found');
        }
    }
    /**
     * Установка статуса
     *
     * @param $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
    /**
     * Получение статуса
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
    /**
     * @return array
     */
    public function behaviors()
    {
        $urlData = parse_url(\Yii::$app->request->getUrl());
        if (isset($urlData['query'])) {
            parse_str($urlData['query'], $queryParams);
            \Yii::$app->request->bodyParams = $queryParams;
        }
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
        return $behaviors;
    }
    /**
     * @param string $id
     * @param array $params
     * @return mixed
     */
    public function runAction($id, $params = [])
    {
        try {
            $this->canRequest();
            $out['data'] = parent::runAction($id, $params);
        } catch (\Exception $e) {
            $this->setStatus('error');
            $out['data']['message'] = $e->getMessage();
        }
        $out['status'] = $this->status;
        return $out;
    }
}
