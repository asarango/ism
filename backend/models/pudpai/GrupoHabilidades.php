<?php
namespace backend\models\pudpai;

use Yii;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use backend\models\PlanificacionBloquesUnidad;
use backend\models\PlanificacionVerticalPaiDescriptores;
use DateTime;

class GrupoHabilidades extends ActiveRecord{

    private $planUnidadId;
    private $planUnidad;
    private $habilidades;
    private $scholarisPeriodoId;
    private $institutoId;
    public $html;


    public function __construct($planUnidadId){
        $this->planUnidadId = $planUnidadId;
        $this->planUnidad   = PlanificacionBloquesUnidad::findOne($planUnidadId);

        $this->habilidades = $this->get_hablidades();

        $this->scholarisPeriodoId = Yii::$app->user->identity->periodo_id;
        $this->institutoId = Yii::$app->user->identity->instituto_defecto;

        $this->html = '';

        $this->get_grupo();
    }

    private function get_hablidades(){
      $con = Yii::$app->db;
      $query = "select 	h.es_titulo2  as contenido
                    ,h.es_titulo1,h.es_exploracion
                from 	planificacion_vertical_pai_opciones op 
                    inner join contenido_pai_habilidades h on h.es_exploracion = op.contenido 
                where 	op.plan_unidad_id = $this->planUnidadId
                group  by h.es_titulo2,h.es_titulo1,h.es_exploracion 
                order by h.es_titulo2;";

      $res = $con->createCommand($query)->queryAll();
      return $res;
    }


    private function get_grupo(){        

      $this->html .= '<h5 class=""><b>3.- ENFOQUES DE APRENDIZAJE / HABILIDADES </b></h5>';                

      $this->html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">';
            $this->html .= '<div class="card" style="width: 95%; margin-top:20px">';

            $this->html .= '<div class="card-header">';
                $this->html .= '<h5 class=""><b>3.1.- GRUPO DE HABILIDADES </b></h5>';                
            $this->html .= '</div>';
                
            $this->html .= '<div class="card-body">';

            $this->html .= '<div class="table table-responsive">';
            $this->html .= '<table class="table table-hover table-condensed table-striped table-bordered">';
            $this->html .= '<thead>';
            $this->html .= '<tr style="background-color: #ab0a3d; color: #eee">';
            $this->html .= '<th class="text-center">COMUNICACIÓN</th>';
            $this->html .= '<th class="text-center">SOCIALES</th>';
            $this->html .= '<th class="text-center">AUTOGESTIÓN</th>';
            $this->html .= '<th class="text-center">INVESTIGACIÓN</th>';
            $this->html .= '<th class="text-center">PENSAMIENTO</th>';
            $this->html .= '</tr>';
            $this->html .= '</thead>';

            $this->html .= '<tbody>';
            $this->html .= '<tr>';

            $this->html .= '<td>';
              $this->html .= $this->estructuraContenido('HABILIDADES DE COMUNICACIÓN');
            $this->html .= '</td>';

            $this->html .= '<td>';
              $this->html .= $this->estructuraContenido('HABILIDADES DE SOCIALES');
            $this->html .= '</td>';

            $this->html .= '<td>';
              $this->html .= $this->estructuraContenido('HABILIDADES DE AUTOGESTIÓN');
            $this->html .= '</td>';

            $this->html .= '<td>';
              $this->html .= $this->estructuraContenido('HABILIDADES DE INVESTIGACIÓN');
            $this->html .= '</td>';

            $this->html .= '<td>';
              $this->html .= $this->estructuraContenido('HABILIDADES DE PENSAMIENTO');
            $this->html .= '</td>';

            $this->html .= '</tr>';
            $this->html .= '</tbody>';
            
            $this->html .= '</table>';
            $this->html .= '</div>';

          $this->html .= '</div>';
        $this->html .= '</div>';
      $this->html .= '</div>';


    }
    //devuelve la estructura de arbol de las habilidades
    private function estructuraContenido($a_buscar)
    {      
        $arrayHabilidades = array();
        $html = '';
        foreach($this->habilidades as $habilidad)
        {
           if($habilidad['es_titulo1']==$a_buscar && !in_array($habilidad['contenido'],$arrayHabilidades,true))
           {
              $arrayHabilidades[]=$habilidad['contenido'];
           }
        }       
        
        foreach($arrayHabilidades as $contenido)
        {
            $html .= '<b>'.$contenido.'</b>';
              foreach($this->habilidades as $habilidad)
              {
                if($habilidad['contenido']==$contenido)
                {
                  $html .='<li>'.$habilidad['es_exploracion'].'</li>';              
                }
              }
        }
        return $html;
    }

   
    //devuelve la estructura de arbol de las habilidades
    private function estructuraContenidoNuevo($a_buscar)
    {      
        $arrayHabilidades = array();
        $html = '';
        foreach($this->habilidades as $habilidad)
        {
           if($habilidad['es_titulo1']==$a_buscar && !in_array($habilidad['contenido'],$arrayHabilidades,true))
           {
              $arrayHabilidades[]=$habilidad['contenido'];
           }
        }       
        
        foreach($arrayHabilidades as $contenido)
        {
            $html .= '<b>'.$contenido.'</b>';
              foreach($this->habilidades as $habilidad)
              {
                if($habilidad['contenido']==$contenido)
                {
                  $html .='<li>'.$habilidad['es_exploracion'].'</li>';              
                }
              }
        }
        return $html;
    }
    
   
}