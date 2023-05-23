<?php
namespace backend\models\calificaciones;

use backend\models\helpers\CalendarioSemanal;
use backend\models\ScholarisBloqueSemanas;
use Yii;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

class PorClase extends ActiveRecord{
    
    private $grupo;       
    
    public function __construct($grupoId, $calificacion) {
        $this->periodId = Yii::$app->user->identity->periodo_id;
        $this->weekId = $weekId;
        $this->userTeacher = $userTeacher;
        $this->week = ScholarisBloqueSemanas::findOne($weekId);

        $this->dates = $this->get_dates($this->week->fecha_inicio, 
                        $this->week->fecha_finaliza, 
                        $userTeacher, $this->periodId);

        $this->hours = $this->get_hours($userTeacher, $this->periodId);
    }

    
       
}