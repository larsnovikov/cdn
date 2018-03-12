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
     * @return void
     */
    private function canRequest(): void
    {
        if (\Yii::$app->request->method !== 'POST') {
            throw new \Exception('Invalid request method');
        }

        $userIp = \Yii::$app->request->getUserIP();

        if (!array_key_exists('frontends',  \Yii::$app->params['cdn'])) {
            throw new \Exception('Frontend servers not set');
        }

        // в списке фронтендов поищим с этим ip
        if (!array_key_exists($userIp, \Yii::$app->params['cdn']['frontends'])) {
            throw new \Exception('Frontend server not found');
        }
    }
    
    /**
     * Установка статуса
     *
     * @param $status
     * @return void
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
    
    /**
     * Получение статуса
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
    
    /**
     * @return array
     */
    public function behaviors(): array
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
