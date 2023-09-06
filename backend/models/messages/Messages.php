<?php

namespace backend\models\messages;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\mail\MessageInterface;

class Messages extends \yii\db\ActiveRecord {
    
    public function send_email($arrayTo, $from, $subject, $textBody, $htmlBody){
        Yii::$app->mailer->compose()
        ->setTo($arrayTo)
        ->setFrom(['info@ism.edu.ec' => 'Información EDUX'])
        ->setSubject($subject)
        ->setTextBody($textBody)
        ->setHtmlBody($htmlBody)
        ->send();
    }

}
