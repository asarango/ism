<?php
namespace backend\models\services;

use Yii;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

class WebServicesUrls extends ActiveRecord{

    public $url;
    public $secretKey;

    public function __construct($service){

        if($service == 'odoo'){
            $this->revisaPing();
            //$this->url = 'http://192.168.20.25/web-service/public/index.php/api';
            //$this->url = 'http://181.188.210.115:11042/web-service/public/index.php/api';
           // $this->secretKey = 'esmiaplicacionmaestra';
        }elseif($service == 'academico'){
            $this->revisaPing();
            //$this->url = 'http://192.168.20.25/ws-academico/public/index.php/api';
            //$this->url = 'http://181.188.210.115:11042/ws-academico/public/index.php/api';
            //$this->secretKey = 'esmiaplicacionmaestra';
           
        }else{
            return array(
                'status' => 'false',
                'message' => 'No està proveido el servicio para '.$service
            );
        }

    }
    public function revisaPing()
    {
        if($this->ping("192.168.20.25"))
        {
            $this->url = 'http://192.168.20.25/web-service/public/index.php/api';               
            $this->secretKey = 'esmiaplicacionmaestra';
        }elseif($this->ping("181.188.210.115"))
        {
            $this->url = 'http://181.188.210.115:11042/web-service/public/index.php/api';
            $this->secretKey = 'esmiaplicacionmaestra';
        }
    }
    private function myOS(){
        if (strtoupper(substr(PHP_OS, 0, 3)) === (chr(87).chr(73).chr(78)))
            return true;

        return false;
    }

    private function ping($ip_addr){
        if ($this->myOS()){
            if (!exec("ping -n 1 -w 1 ".$ip_addr." 2>NUL > NUL && (echo 0) || (echo 1)"))
                return true;
        } else {
            if (!exec("ping -q -c1 ".$ip_addr." >/dev/null 2>&1 ; echo $?"))
                return true;
        }

        return false;
    }


    public function consumir_servicio($urlNew){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlNew);

        $headers = array();
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Auth: '.$this->secretKey;

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $res = curl_exec($ch);
        curl_close($ch);

        return $res;
    }

}
