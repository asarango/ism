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
use backend\models\IsmCriterio;
use backend\models\IsmCriterioLiteral;
use backend\models\IsmLiteralDescriptores;
use backend\models\PlanificacionVerticalPaiDescriptores;
use DateTime;

class Evaluacion extends ActiveRecord{

    private $planUnidadId;
    private $planUnidad;
    private $scholarisPeriodoId;
    private $institutoId;
    public $html;


    public function __construct($planUnidadId){
        $this->planUnidadId = $planUnidadId;
        $this->planUnidad   = PlanificacionBloquesUnidad::findOne($planUnidadId);
        $this->scholarisPeriodoId = Yii::$app->user->identity->periodo_id;
        $this->institutoId = Yii::$app->user->identity->instituto_defecto;

        $this->html = '';
        $this->actualizaCampoUltimaSeccion('3.1.-',$planUnidadId);

        $this->get_preguntas();
    }
    private function actualizaCampoUltimaSeccion($ultima_seccion,$idPlanBloqUni)
    {
        $con=Yii::$app->db;        
        $query = "update pud_pai set ultima_seccion ='$ultima_seccion' where planificacion_bloque_unidad_id = $idPlanBloqUni ; ";
        
        $con->createCommand($query )->queryOne();
    }
    


    private function get_preguntas()
    {        
        $objContenidos = $this->consultar_objetivos_especificos($this->planUnidadId); 

            $this->html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">';
            $this->html .= '<div class="card" style="width: 90%; margin-top:20px">';

            $this->html .= '<div class="card-header">';
                $this->html .= '<h5 class=""><b>4.- EVALUACIÓN</b></h5>';                
            $this->html .= '</div>';
                
            $this->html .= '<div class="card-body">';
                // inicia row 
                  $this->html .= '<div class="row">';
                  $this->html .= '<div class="col-lg-4 col-md-4 text-center border"><b>OBJETIVOS ESPECÍFICOS Y ASPECTOS:</b><br>
                                      <small style="color: #65b2e8">(Copiar la redacción tal y como aparece en la guía de la asignatura, 
                                      para cada año del PAI)</small>
                                  </div>';
                  $this->html .= '<div class="col-lg-4 col-md-4 text-center border"><b>EVALUACIÓN</b><br>
                                </div>';
                  $this->html .= '<div class="col-lg-4 col-md-4 text-center border"><b>RELACION ENTRE LAS TAREAS DE EVALUACIÓN <br> 
                                    SUMATIVAS Y EL ENUNCIADO DE LA INDAGACIÓN:</b><br>
                                        <small style="color: #65b2e8">Relación entre las tareas de evaluación formativa y sumativa con base en el enunciado de la indagación.
                                        Resumen de las tareas de evaluación formativa y sumativa con base en los criterios de evaluación correspondientes.
                                        </small>
                                </div>'; 
                  $this->html .= '</div>';
                //******finaliza row 

                 //******inicia row 
                $this->html .= '<div class="row">';
                      $this->html .= '<div class="col-lg-4 col-md-4 text-left border">';
                              $this->html .=  $objContenidos;
                      $this->html .= '</div>';
                      $this->html .='<div class="col-lg-4 col-md-4 border">';
                               $this->html .='<div class="row">';
                                      $this->html .= '<div class="col-lg-12 col-md-12 text-left "><br><b>EVALUACIÓN FORMATIVA:</b>
                                          <small style="color: #65b2e8"> Genera evidencia de avance y ofrece oportunidades variadas de practicar, 
                                          de hacer comentarios detallados y adaptar la enseñanza planificada. Incluye autoevaluación y coevaluación. 
                                          Se deben ofrecer comentarios sobre el avance en el desarrollo de habilidades..</small>';
                                          $this->html .= '<hr><div  id="div-evaluacion-formativa"></div><hr>';                                         
                                      $this->html .= '</div>';
                                  $this->html .='</dv>';//CIERRA ROW
                                  $this->html .='<div class="row">';
                                      $this->html .= '<div class="col-lg-12 col-md-12 text-left "><br><b>EVALUACIÓN SUMATIVA:</b>
                                            <small style="color: #65b2e8"> (se explica claramente qué harán los alumnos para demostrar lo que saben, 
                                            lo que comprenden y lo que  pueden hacer; permite demostrar comprensión de los conceptos, la relación conceptual 
                                            y el contexto que se describen en el enunciado de la indagación; permite demostrar objetivos y aspectos escogidos 
                                            para  la  unidad;  utiliza  términos  de  instrucción  correctos para  ese  año  del  PAI,  permite  a  los  alumnos  
                                            demostrar  los  descriptores  de  todos  los  niveles  de  logro;  es estimulante pero accesible; permite a los alumnos 
                                            comunicar lo que saben, lo que comprenden y lo que pueden hacer de maneras múltiples y abiertas; permite aplicar lo que 
                                            han aprendido a una variedad de situaciones auténticas o situaciones que simulan el mundo real).</small>';
                                      $this->html .= '<hr><div  id="div-evaluacion-sumativa"></div>';
                                      $this->html .= '</div>';
                                $this->html .='</div>';//CIERRA ROW                              
                      $this->html .='</div>';//cierre columna  
               $this->html .= '</div>';//cierre row
               $this->html .='<div class="col-lg-4 col-md-4 border id="div-evaluacion-sumativa2">';    
                      $this->html .='<div id="div-evaluacion-relacion"></div>';
               $this->html .='</div>';//cierre columna
            $this->html .= '</div>';//fin de card-body
            $this->html .= '</div>';
        $this->html .= '</div>';

    }
     //objetivos especificos y aspectos del pai
     public function consultar_objetivos_especificos($planUnidadBloq)
     {
         $arrayCriterios = array();
         $con = yii::$app->db;       
         $criterios = ''; 
 
         $query = "select * from ism_criterio_descriptor_area icda 
         where id in (select descriptor_id from planificacion_vertical_pai_descriptores pvpd 
         where plan_unidad_id =$planUnidadBloq order by descriptor_id )
         order by id_criterio ;";
 
         $respuesta = $con->createCommand($query)->queryAll();
         foreach($respuesta as $resp)
         {
             if(in_array($resp['id_literal_criterio'],$arrayCriterios,true))
             {
                 //NO SE PRODUCE NADA;
             }else{
                 $arrayCriterios[]=$resp['id_literal_criterio'];
             }
         }
         
         foreach ($arrayCriterios as $criterio)
         {
             //consulta descriptor y literal descriptor
             $literalCriterio = IsmCriterioLiteral::findOne($criterio);            
             $criterios .= '<b>'.$literalCriterio->criterio->nombre.' - '. $literalCriterio->nombre_espanol.'</b>
             <br><br>'; 
             foreach($respuesta as $resp2)            {
                 if($criterio==$resp2['id_literal_criterio'])
                 {   
                     $descriptor = IsmLiteralDescriptores::findOne($resp2['id_literal_descriptor']);
                     $criterios .=  $descriptor->descripcion ;  
                     $criterios .= '<br><br>';                                      
                 }
             }            
         }         
         $criterios .= '</p>'; 
         return  $criterios;
     }


    private function modal_criterio($criterioId, $criterio)
    {
                $html = '<a href="#"  data-bs-toggle="modal" data-bs-target="#modal'.$criterio.'"> 
                              <span class="badge rounded-pill" 
                              style="background-color: #ab0a3d">
                              '.$criterio.'
                              </span>';
                $html.= '</a>';
      
                $html.= '<div class="modal fade" id="modal'.$criterio.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">CRITERIO '.$criterio.'</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>                                
                              </div>
                            <div class="modal-body">                    
                            <hr>';

                    $html .= '<input type="text" name="facticas" class="form-control" id="input-titulo" 
                                placeholder="Ingrese el tema">'; 
                                
                    $html .= '<textarea name="revision_coordinacion_observaciones" id="editor">
                                <?= $cabecera->revision_coordinacion_observaciones ?>
                              </textarea>
                              <script>
                                  CKEDITOR.replace("editor", {
                                      customConfig: "/ckeditor_settings/config.js"
                                  })
                              </script>';
                      
                    $html .= '</div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                      
                    </div>
                  </div>
                </div>
              </div>';
        return $html;
    }

    
   
}