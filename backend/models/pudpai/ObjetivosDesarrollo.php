<?php
namespace backend\models\pudpai;

use backend\models\IsmContenidoPlanInterdiciplinar;
use Yii;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use backend\models\ContenidoPaiOpciones;
use backend\models\IsmContenidoPaiPlanificacion;
use backend\models\PlanificacionBloquesUnidad;
use backend\models\PlanificacionOpciones;
use backend\models\PlanificacionVerticalPaiDescriptores;
use DateTime;

class ObjetivosDesarrollo extends ActiveRecord
{

    private $planUnidadId;
    private $planUnidad;
    private $habilidades;
    private $scholarisPeriodoId;
    private $institutoId;
    public $html;
    private $seccion_numero;


    public function __construct($planUnidadId)
    {
        $this->planUnidadId = $planUnidadId;
        $this->planUnidad   = PlanificacionBloquesUnidad::findOne($planUnidadId);        

        $this->scholarisPeriodoId = Yii::$app->user->identity->periodo_id;
        $this->institutoId = Yii::$app->user->identity->instituto_defecto;      
           
        $this->seccion_numero = 4;      
        $this->actualizaCampoUltimaSeccion('4.1.-',$planUnidadId);     

        $this->html = $this->get_formato_html();        
    }
    private function actualizaCampoUltimaSeccion($ultima_seccion,$idPlanBloqUni)
    {
        $con=Yii::$app->db;        
        $query = "update pud_pai set ultima_seccion ='$ultima_seccion' where planificacion_bloque_unidad_id = $idPlanBloqUni ; ";      
        
        $con->createCommand($query )->queryOne();
    } 
    
    private function get_formato_html()
    {
        /*
            Creado Por: Santiago / Fecha Creacion: 2023-03-08 
            Modificado Por: 	/ Fecha Modificación:
            Detalle: Genera en pantalla, la interaccion son los datos para el ods de la planificacion normal pai
        */
        $planUnidad = $this->planUnidad;
        $html = "";
        $html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">';
        $html .= '<div class="card" style="width: 90%; margin-top:20px">';

        $html .= '<div class="card-header" style="background-color:#800834;">';
            $html .= '<h5 class="" style="color: #ffffff;"><b>4.1.- OBJETIVOS DE DESARROLLO SOSTENIBLE </b></h5>';
        $html .= '</div>';

        $html .= '<div class="card-body">';
        $html .= '<div class="ocultar">';
        $html .= $this->modal_competencias($planUnidad);
        $html .= '</div>';
        $html .= '<div class="table table-responsive">';

        $html .= '<table class="table table-hover table-condensed table-bordered">';
        $html .= '<div class="table table-responsive">';
        $html .= $this->objetivos_desarrollo_sostenible_competencia2($planUnidad->id);     
        $html .= '</div>';

        $html .= '</table>';
        $html .= '</div>';

        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
    
    private function modal_competencias($planUnidad)
    {
        $pestana = '4.1.-';
        $html = '<a href="#"  data-bs-toggle="modal" data-bs-target="#reflexionModal" onclick="show_reflexion_disponibles()"> 
                              <span class="badge rounded-pill" 
                              style="background-color: #ab0a3d; font-size:13px;"><i class="fa fa-briefcase" aria-hidden="true"></i> 
                              Seleccionar Competencia
                              </span>';
        $html .= '</a>';

        $html .= '<div class="modal fade" id="reflexionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-x">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">SELECCIONAR COMPETENCIAS</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="carrar_competencia(\'' . $pestana . '\')"></button>                      
                    </div>'; //FIN DE MODAL -HEADER

        $html .= '<div class="modal-body">'; //Inicio de modal-body

        $html .= '<div class="table table-responsive">';
        $html .= '<table class="table table-condensed table-bordered">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th class="text-center" style="background-color: #0a1f8f; color: white">COMPETENCIA</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody id="table-competencia-disponibless">';
        $html .= '<tr>';
        $html .= '<td>' . $this->mostrar_competencias_disponibles($planUnidad) . '</td>';
        $html .= '</tr>';
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';


        $html .= '</div>'; // fin de modal-body

        $html .= '<div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="carrar_competencia(\'' . $pestana . '\');">Cerrar</button>                      
                      
                    </div>
                  </div>
                </div>
              </div>';
        return $html;
    }
    private function mostrar_competencias($planUnidad)
    {
        $html = "";
        $html .= '<tr>';
        $html .= '<td>' . $this->mostrar_competencias_disponibles($planUnidad) . '</td>';
        $html .= '</tr>';
        return $html;
    }
    //
    private function mostrar_competencias_disponibles($planUnidad)
    {    
        /*
        Creado Por: Santiago / Fecha Creacion: 2023-03-17 
        Modificado Por: 	/ Fecha Modificación:
        Detalle: Devuelve una tabla con las competencias disponibles para el PUD normal
        */     
        $con = Yii::$app->db;     
        $query = "select id,tipo,contenido_es,contenido_en,contenido_fr,estado 
                  from contenido_pai_opciones c
                    where id not in (
                        select id_contenido_pai from ism_contenido_pai_planificacion i
                        where planificacion_bloque_unidad_id =$planUnidad->id and tipo ='competencia_pai_inter'
                    ) and tipo = 'competencia_pai_inter' ;";  

        // echo $query;
        // die();
        
        $arraylPlanOpciones = $con->createCommand($query)->queryAll();              
        

        $html = "";
        $html .= '<table>';
        foreach ($arraylPlanOpciones as $array) {
            $html .= '<tr>
                <td style="font-size:15px"><a href="#" onclick="guardar_competencias(' . $array['id'] . ',\'' . strtoupper('') . '\');">' . $array['contenido_es'] . '</a>
                </td>
            </tr>';

        }
        $html .= '</table>';
        return $html;
    }
    
    private function objetivos_desarrollo_sostenible_competencia2($idPlanBloqUni)
    {       
        $html ="";    

        $modelCompetenciasSelect = IsmContenidoPaiPlanificacion::find()
            ->where(['planificacion_bloque_unidad_id'=>$idPlanBloqUni])
            ->andWhere(['tipo'=>'competencia_pai_inter'])
            ->all();

        $html .='<table class="table table-condensed table-bordered">
                     <tr>';
                     $html .= '<th class="text-center" style="background-color: #0a1f8f; color: white">COMPETENCIA</th>';
                     $html .= '<th class="text-center" style="background-color: #9e28b5; color: white">ACTIVIDAD</th>';
                     $html .= '<th class="text-center" style="background-color: #ab0a3d; color: white">OBJETIVO</th>';
                     $html .= '<th class="text-center" style="background-color: #ab0a3d; color: white">RELACION ODS-IB</th>';
                     $html .= '</tr>';
                    foreach( $modelCompetenciasSelect as $model)
                    {
                        $html .= '<tr><td style="font-size:14px"><a href="#" onclick="eliminar_competencias(' . $model->id . ');"><i style="color:red;" class="fas fa-trash"></i></a>
                        <span style="color:blue;">' . $model->contenido . '</span></td>';                        
                   
                        $html .= '<td>
                                <textarea id="competencia_actividad_' . $model->id. '" class="form-control" style="max-width: 100%;" 
                                onchange="actualizar_competencia(' . $model->id . ')">' . $model->actividad. '</textarea>
                                </td>';
                        $html .= '<td>
                                <textarea id="competencia_objetivo_' . $model->id. '" class="form-control" style="max-width: 100%;" 
                                onchange="actualizar_competencia(' . $model->id . ')">' . $model->objetivo. '</textarea>
                                </td>';
                        $html .= '<td>
                                <textarea id="competencia_relacion_ods_' . $model->id. '" class="form-control" style="max-width: 100%;" 
                                onchange="actualizar_competencia(' . $model->id . ')">' . $model->relacion_ods. '</textarea>
                                </td>';
                        $html .='</tr>';   
                    }
        $html .= '</table>';
        return $html;
    }   

}