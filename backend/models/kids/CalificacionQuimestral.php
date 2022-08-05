<?php
namespace backend\models\kids;

use Yii;
use backend\models\KidsCalificacionesQuimestre;
use backend\models\KidsEscalaCalificacion;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use DateTime;

class CalificacionQuimestral extends ActiveRecord{

    private $grupoId;
    private $destrezaId;
    private $quimestreId;
    private $escalaActualId;
    private $nuevaEscalaId;
    private $codigoNuevoEscala;

    public function __construct($grupoId, $destrezaId, $quimestreId, $nuevaEscalaId)
    {
        $this->grupoId = $grupoId;
        $this->destrezaId = $destrezaId;
        $this->quimestreId = $quimestreId;
        $this->nuevaEscalaId = $nuevaEscalaId;

        $escalaNueva = KidsEscalaCalificacion::findOne($nuevaEscalaId);
        $this->codigoNuevoEscala = $escalaNueva->escala;
        
        $existe = KidsCalificacionesQuimestre::find()->where([
            'quimestre_id' => $this->quimestreId,
            'grupo_id' => $this->grupoId,
            'destreza_id' => $this->destrezaId
        ])->one();
        
        
        
        
        if(trim($existe->escala->escala) != 'A' ){
            echo $existe->escala->escala;
            $this->consulta_existencia_calificacion($existe);
        }
       

        
    }


    private function consulta_existencia_calificacion($existe){


        if($existe){
                 $existe->escala_id = $this->nuevaEscalaId;
                 $existe->save(false);
             
        }else{
            
            $model = new KidsCalificacionesQuimestre();
            $model->quimestre_id = $this->quimestreId;
            $model->grupo_id = $this->grupoId;
            $model->escala_id = $this->nuevaEscalaId;
            $model->destreza_id = $this->destrezaId;
            $model->save(false);
        }
    }

}