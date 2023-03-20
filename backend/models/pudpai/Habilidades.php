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
use backend\models\PlanificacionOpciones;
use backend\models\PlanificacionVerticalPaiDescriptores;
use DateTime;

class Habilidades extends ActiveRecord
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

        $this->html = '';       
        $this->seccion_numero = 3;
      
        $this->actualizaCampoUltimaSeccion('3.1.-',$planUnidadId);
        $this->get_nuevo_formato();
    }
    private function actualizaCampoUltimaSeccion($ultima_seccion,$idPlanBloqUni)
    {
        $con=Yii::$app->db;        
        $query = "update pud_pai set ultima_seccion ='$ultima_seccion' where planificacion_bloque_unidad_id = $idPlanBloqUni ; ";      
        // print_r($query);
        // die();
        $con->createCommand($query )->queryOne();
    }
    //extrae la habilidades seleccionadas en el plan vertical
    private function get_habilidades()
    {
      $con = Yii::$app->db;

      $query = "select 	h.es_titulo2  as contenido
                ,h.es_titulo1,h.es_exploracion,op.actividad,
                (select categoria  from planificacion_opciones po where id = op.id_pudpai_perfil)
                ,op.id_relacion,op.id
                from 	planificacion_vertical_pai_opciones op 
                inner join contenido_pai_habilidades h on h.es_exploracion = op.contenido 
                where 	op.plan_unidad_id = $this->planUnidadId
                group  by h.es_titulo2,h.es_titulo1,h.es_exploracion,op.actividad,op.id_pudpai_perfil,op.id_relacion ,op.id 
                order by h.es_titulo1;";

      $res = $con->createCommand($query)->queryAll();
      return $res;
    }

    private function get_nuevo_formato()
    {  
        $this->html .= '<h5 class=""><b>3.- ENFOQUES DE APRENDIZAJE / HABILIDADES </b></h5>';                

        $this->html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">';
              $this->html .= '<div class="card" style="width: 95%; margin-top:20px">';
  
                    $this->html .= '<div class="card-header">';
                        $this->html .= '';                
                    $this->html .= '</div>';
                        
                    $this->html .= '<div class="card-body">';
                            $this->html .= '<div class="table table-responsive">';
                                    $this->html .= '<table class="table table-hover table-condensed table-striped table-bordered">';
                                        $this->html .= '<thead>';
                                            $this->html .= '<tr style="background-color: #ab0a3d; color: #eee">';
                                                $this->html .= '<th class="text-center">HABILIDADES</th>';
                                                $this->html .= '<th class="text-center">EXPLORACIÓN</th>';
                                                $this->html .= '<th class="text-center">ACTIVIDAD</th>';
                                                $this->html .= '<th class="text-center">ATRIBUTOS DEL PERFIL</th>';
                                            $this->html .= '</tr>';
                                        $this->html .= '</thead>';
                        
                                        $this->html .= '<tbody>';
                                            $this->html .= '<tr>';
                                                $this->html .= $this->tipo_habilidades();
                                            $this->html .= '</tr>';
                                        $this->html .= '</tbody>';
                                    
                                    $this->html .= '</table>';
                            $this->html .= '</div>';
                    $this->html .= '</div>';

              $this->html .= '</div>';
        $this->html .= '</div>';

    }  
    private function tipo_habilidades()
    {
        $habilidades = $this->get_habilidades();
        $html = '';
        
        foreach($habilidades as $hab)
        {
            $html .= '<tr>';
                $html .='<td>'; 
                    $html .=$hab['es_titulo1']; 
                $html .='</td>';

                $html .='<td>'; 
                    $html .='* '.$hab['es_exploracion']; 
                $html .='</td>';

                $html .='<td>'; 
                    $html .=$this->modalActividad($hab['actividad'],$hab['id_relacion'],$hab['id']);
                    $html .='<br>';
                    $html .=$hab['actividad'];
                $html .='</td>';

                $html .='<td>'; 
                    $html .=$this->modalPerfiles($hab['id']);
                    $html .='<br>';
                    $html .=$hab['categoria']; 
                $html .='</td>';
            $html .= '<tr>';
        }          
       
        return $html;
    }  
    private function modalActividad($actividad,$id_relacion,$id)
    {
        $html = '<a href="#"  data-bs-toggle="modal" data-bs-target="#facticasModal'.$id.'"> 
                              <span class="badge rounded-pill" 
                                style="background-color: #ab0a3d"><i class="fa fa-briefcase" aria-hidden="true"></i> 
                                Editar
                              </span>';
                $html .= '</a></br>';
      
                $html.= '<div class="modal fade" id="facticasModal'.$id.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                  <div class="modal-content">
                    <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Actividad: </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>                      
                    </div>
                    <div class="modal-body">';//inicio modal-body

                            $html .= '<div class="form-group">'; // inicia actividad                               
                                $html .= '<input name="id_relacion" id="id_relacion'.$id.'" type="hidden" value="'.$id_relacion.'">';   
                                $html .= '<input name="id_pudpai" id="id_pudpai'.$id.'" type="hidden" value="'.$id.'">';    
                                $html .= '<textarea name="txt_actividad" id="editor-actividad'.$id.'">';                               
                                    $html .= $actividad;                               
                                $html .= '</textarea>
                                <script>
                                    CKEDITOR.replace("editor-actividad'.$id.'", {
                                        customConfig: "/ckeditor_settings/config.js"
                                    })
                                </script>';
                            $html .= '</div><hr>'; //fin de actividad

                    $html .= '</div>';//fin modal-body
                    $html .= '<div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal" onclick="update_habilidades_nuevo_formato('.$id.');">Actualizar</button>                      
                              </div>
                  </div>
                </div>
              </div>';
        return $html;
    }
    private function modalPerfiles($id)
    {
        $perfiles_disponibles = $this->muestra_perfiles_disponibles($id);

        $html = '<a href="#"  data-bs-toggle="modal" data-bs-target="#PerfilBiModal'.$id.'" onclick="show_perfiles_disponibles()"> 
                              <span class="ocultar badge rounded-pill " 
                                    style="background-color: #ab0a3d"><i class="fa fa-briefcase" aria-hidden="true"></i> 
                                    Editar
                              </span>';
                $html .= '</a><br>';
      
                $html.= '<div class="modal fade" id="PerfilBiModal'.$id.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                  <div class="modal-content">
                    <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">SELECCIONAR PERFILES</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>                      
                    </div>'; //FIN DE MODAL -HEADER

                    $html .= '<div class="modal-body">'; //Inicio de modal-body                
                        $html .= '<div class="table table-responsive">';
                            $html .= '<table class="table table-condensed table-bordered">';
                                $html .= '<thead>';
                                    $html .= '<tr>';
                                         $html .= '<th class="text-center" style="background-color: #FF9e18; color: white">PERFILES</th>';
                                    $html .= '</tr>';
                                $html .= '</thead>';
                                $html .= '<tbody >';
                                            foreach($perfiles_disponibles as $perfil)
                                                {
                                                    $html .= '<tr><td>';
                                                        $html .= '<a href="#" onclick="guardar_perfil('.$id.','.$perfil['id'].')">'.$perfil['categoria'].'</a>';
                                                    $html .= '</td></tr>';
                                                }
                                $html .='</tbody>';
                            $html .= '</table>';
                        $html .= '</div>';                      
                    $html .= '</div>';// fin de modal-body
                    $html .= '<div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="recargar_pagina()">Cerrar</button>                      
                      
                              </div>
                  </div>
                </div>
              </div>';
        return $html;
    }
    
    private function muestra_perfiles_disponibles($idPlanUnidad)
    {
        $con = yii::$app->db;   
        $query = "select id,tipo,categoria,opcion,estado from planificacion_opciones po 
                where po.tipo = 'PERFIL' and 
                po.id not in 
                (
                select COALESCE(id_pudpai_perfil,0)  from planificacion_vertical_pai_opciones pp where id = $idPlanUnidad
                );";
        
        $respuesta = $con->createCommand($query)->queryAll();       

        return $respuesta;
    }
    
   
}