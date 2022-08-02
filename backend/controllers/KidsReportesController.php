<?php

namespace backend\controllers;

use backend\models\helpers\Scripts;
use backend\models\kids\PdfLibreta;
use backend\models\KidsCalificaTarea;
use backend\models\KidsEscalaCalificacion;
use backend\models\KidsDestrezaTarea;
use backend\models\ViewKidsTareasSearch;
use backend\models\ScholarisQuimestre;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


class KidsReportesController extends Controller{
    
    
    public function actionIndex1(){

        $usuario = Yii::$app->user->identity->usuario;
        $peridoId = Yii::$app->user->identity->periodo_id;

        // toma los paralelos del docente logeado
        $script = new Scripts();
        $paralelos = $script->get_paralelo_x_periodo($peridoId, $usuario);

        

        return $this->render('index', [
            'paralelos' => $paralelos
            
        ]);
    } 

    public function actionListaEstudiantes(){
        $paraleloId = $_GET['paralelo_id'];
        $script = new Scripts();
        $alumnos = $script->get_alumnos_x_paralelo($paraleloId);        

        $quimestres = ScholarisQuimestre::find()->orderBy('orden')->all();
        
        return $this->renderPartial('_ajax-alumnos', [
            'alumnos' => $alumnos,
            'quimestres' => $quimestres,
            'paraleloId' => $paraleloId
        ]);
    }

    public function actionReportes(){
        $paraleloId = $_GET['paralelo_id'];
        $reporte = $_GET['reporte'];
        $quimestre = $_GET['quimestre_id'];

        if($reporte == 'libreta'){
            $reporte = new PdfLibreta($quimestre);
            $reporte->generate_pdf();
        }

    }
    
}