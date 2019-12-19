<?php
namespace Kdteam;
class ApiSms
{
    public function __construct()
    {
        $this->privateKey = 'b92986f663c0596737dfcb773f6eca17';
        $this->publicKey = '6bd53830b4d07adbb4f2e4df6bd67ccc';
        $this->formatResponse = 'json';
        $this->url = 'http://atompark.com/api/sms/';
        $this->version = '3.0';
        $this->testMode = false;
    }
    public function execCommad($action, $params)
    {
        $params['key'] = $this->publicKey;
        if ($this->testMode) {
            $params['test'] = true;
        }
        $params['version'] = $this->version;
        $params['action'] = $action;
        // Выбор маршрута для Украины
        $route ['sendTypeCountry']['211'] = "national_ua";
        // Преобразовываем параметры для России и Украины в формат json
        $params ['sendOption'] = json_encode($route);
        ksort($params);
        $sum = '';
        foreach ($params as $k => $v) {
            $sum .= $v;
        }
        $sum .= $this->privateKey;
        $controlSUM = md5($sum);
        $params['sum'] = $controlSUM;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_URL, $this->url . $this->version . '/' . $action);
        $result = curl_exec($ch);
        if (curl_errno($ch) > 0) {
            return array('success' => false, 'code' => curl_errno($ch), 'error' => curl_error($ch));
        } elseif ($this->formatResponse == 'json') {
            return $this->processResponseJSON($result, false);
        } else {
            return false;
        }
    }
    private function processResponseJSON($result, $simple)
    {
        if ($simple) {
            return json_decode($result, true);
        } elseif ($result) {
            $jsonObj = json_decode($result, true);
            if (null === $jsonObj) {
                return array('success' => false, 'result' => null);
            } elseif (!empty($jsonObj->error)) {
                return array('success' => false, 'error' => $jsonObj->error, 'code' => $jsonObj->code);
            } else {
                return $jsonObj;
            }
        } else {
            return array('success' => false, 'result' => null);
        }
    }
}