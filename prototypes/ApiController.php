<?php

namespace app\prototypes;

use yii\rest\ActiveController;
use yii\web\Response;

class ApiController extends ActiveController
{

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
        $userIp = \Yii::$app->request->getUserIP();
        // в списке фронтендов поищим с этим ip
//        if (!isset(\Yii::$app->params['frontends'][$userIp])) {
//            throw new \Exception('Frontend server not found');
//        }
        // TODO authCode должен передаваться в зафишрованном виде, а тут расфировываться.
        // TODO возможно шифровать надо всю посылку
        // проверим authCode
        // TODO раскоментить потом
//        $authCode = \Yii::$app->request->get('authCode');
//        if ($authCode === null) {
//            throw new \Exception('authCode not found');
//        }
//
//        if (\Yii::$app->params['frontends']['authCode'] !== $authCode) {
//            throw new \Exception('Invalid authCode');
//        }
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