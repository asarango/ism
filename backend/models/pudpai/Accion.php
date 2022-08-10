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
use backend\models\PlanificacionBloquesUnidadSubtitulo;
use DateTime;

class Accion extends ActiveRecord{

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
        $this->actualizaCampoUltimaSeccion('5.1.-',$planUnidadId);

        $this->get_accion();
    }

    private function actualizaCampoUltimaSeccion($ultima_seccion,$idPlanBloqUni)
    {
        $con=Yii::$app->db;        
        $query = "update pud_pai set ultima_seccion ='$ultima_seccion' where planificacion_bloque_unidad_id = $idPlanBloqUni ; ";
        
        $con->createCommand($query )->queryOne();
    }
    private function get_accion(){       
        
            $temas = PlanificacionBloquesUnidadSubtitulo::find()->where([
                'plan_unidad_id' => $this->planUnidadId
            ])
            ->orderBy('orden')
            ->all();

            $this->html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">';
                $this->html .= '<div class="card" style="width: 90%; margin-top:20px">';

                    $this->html .= '<div class="card-header">';
                        $this->html .= '<h5 class=""><b>5.- ACCIÓN: ENSEÑANZA Y APRENDIZAJE A TRAVÈS DE LA INDAGACIÒN</b></h5>';                        
                    $this->html .= '</div>';
                        
                    $this->html .= '<div class="card-body">';
                        $this->html .= '<div class="table table-responsive">';
                        $this->html .= '<table class="table table-hover table-condensed table-bordered">';
                        $this->html .= '<tr>'; 
                            $this->html .= '<td align="center" rowspan = "2"><b>CONTENIDOS: </b> </td>';
                            $this->html .= '<td align="center" rowspan = "2"><b>VERFICACIÓN: (SI/NO/REPLANIFICADO) </b> </td>';
                            $this->html .= '<td align="center" colspan = "2"><b>PROCESO DE APRENDIZAJE: </b> </td>';
                        $this->html .= '</tr>'; 

                        $this->html .= '<tr>
                                <td><b>EXPERIENCIAS DE APRENDIZAJE Y ESTRATEGIAS DE ENSEÑANZA: </b>
                                <small><i>
                                    variedad que abarque el espectro de preferencias de los alumnos. 
                                    Basadas en los conocimientos previos y en la indagación. (Todas las actividades a realizar en clase o para la casa)
                                </i></small>
                                </td>';

                        // $this->html .= '<th><b>EVALUACIÓN FORMATIVAS: </b>
                        // <small style="color: #65b2e8">
                        //     genera evidencia de avance y ofrece oportunidades variadas de practicar, de hacer comentarios detallados y adaptar la enseñanza planificada. 
                        //     Incluye autoevaluación y coevaluación. Se deben ofrecer comentarios sobre el avance en el desarrollo de habilidades.
                        // </small></th>';

                        $this->html .= '<td><b>DIFERENCIACIÓN: </b>
                            <small ><i>
                                de contenido, de proceso (cómo se enseñará y se aprenderá) y de producto (lo que se evaluará). Definir las actividades 
                                correspondientes a los 3 diferentes estilos de aprendizaje más reconocidos: VISUAL, KINESTÉSICO, AUDITIVO.
                            </i></small>
                            </td>
                        </tr>';                        

                        $this->html .= '</tr>';            
                        $this->html .= '</thead>';       

                        $this->html .= '<tbody>';
                        foreach($temas as $tema){
                            $this->html .= '<tr>';
                                $this->html .= '<td align = "center">'.$tema->subtitulo.'</td>';
                                $this->html .= '<td align = "center">'.$tema->verificacion.'</td>';
                                $this->html .= '<td>'.$tema->experiencias.'</td>';
                                //$this->html .= '<td>'.$tema->evaluacion_formativa.'</td>';
                                $this->html .= '<td>'.$tema->diferenciacion.'</td>';
                            $this->html .= '</tr>';
                        }            
                        $this->html .= '</tbody>';      

                        $this->html .= '</table>';            
                        $this->html .= '</div>';

                    $this->html .= '</div>';
                $this->html .= '</div>';
            $this->html .= '</div>';                        

    }

       
   
}