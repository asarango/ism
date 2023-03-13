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

class Necesidades extends ActiveRecord
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
           
        $this->seccion_numero = 8;      
        $this->actualizaCampoUltimaSeccion('8.1.-',$planUnidadId);     

        $this->get_formato_html();        
    }
    private function actualizaCampoUltimaSeccion($ultima_seccion,$idPlanBloqUni)
    {
        $con=Yii::$app->db;        
        $query = "update pud_pai set ultima_seccion ='$ultima_seccion' where planificacion_bloque_unidad_id = $idPlanBloqUni ; ";      
        
        $con->createCommand($query )->queryOne();
    } 
    private function buscar_nee_x_materia()
    {
       
        $idCurso = $this->planUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->id ;      
        $idMateria= $this->planUnidad->planCabecera->ismAreaMateria->materia->id ;
        $idPeriodo=  Yii::$app->user->identity->periodo_id; 
        $con = Yii::$app->db;

        $query = "select 	s.id 
                            ,concat(s.first_name, ' ', s.middle_name, ' ', s.last_name) as student
                            ,nxc.grado_nee 
                            ,nxc.diagnostico_inicia 
                            ,nxc.diagnostico_finaliza 
                            ,nxc.recomendacion_clase 
                    from 	scholaris_clase cl
                            inner join op_course_paralelo pa on pa.id = cl.paralelo_id 
                            inner join op_course cu on cu.id = pa.course_id
                            inner join ism_area_materia am on am.id = cl.ism_area_materia_id 
                            inner join ism_malla_area ma on ma.id = am.malla_area_id 
                            inner join ism_periodo_malla pm on pm.id = ma.periodo_malla_id
                            inner join nee_x_clase nxc ON nxc.clase_id = cl.id 
                            inner join nee nee on nee.id = nxc.nee_id 
                            inner join op_student s on s.id = nee.student_id 
                    where 	cu.x_template_id = $idCurso
                            and pm.scholaris_periodo_id = $idPeriodo
                            and am.materia_id = $idMateria;";                           
        
        $resp = $con->createCommand($query)->queryAll();     
        return $resp;
    }
    private function getIniciales($nombre)
    {
        $name = '';
        $explode = explode(' ',$nombre);
        foreach($explode as $x){
            $name .=  $x[0];
        }
        
        return $name;    
    }
    private function devulve_lista_estudiante($arregloEstudiantes,$grado)
    {
        $html ='<ul>';
        foreach($arregloEstudiantes as $array)
        {
            if($array['grado_nee']==$grado)
            {
                $iniciales = $this->getIniciales($array['student']);                
                $html .='<li>'; 
                    $html .='<b>'.$iniciales.':</b>'.' <b>Diagnóstico:</b> '.$array['diagnostico_inicia'].'- <b>Recomendación:</b> '.$array['recomendacion_clase']; 
                $html .='<li>'; 
            }            
        }
        $html .='</ul>';
        return $html;
    }
    
    private function get_formato_html()
    {  
        $estudiantesNee = $this->buscar_nee_x_materia();
       

        $this->html .= '<h5 class=""><b>8.- ATENCIÓN A LAS NECESIDADES EDUCATIVAS ESPECIALES </b></h5>';  
        $this->html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">';
              $this->html .= '<div class="card" style="width: 95%; margin-top:20px">';
  
                    $this->html .= '<div class="card-header">';
                        $this->html .= '';                
                    $this->html .= '</div>';
                        
                    $this->html .= '<div class="card-body">';
                            $this->html .= '<div class="table table-responsive">';
                                    $this->html .= '<table class="table table-hover table-condensed table-striped table-bordered">';
                                        $this->html .= '<thead>';
                                            $this->html .= '<tr>';
                                                $this->html .= '<td style="background-color: #ab0a3d; color: #eee" class="text-center">GRADO 1</td>';
                                                $this->html .= '<td>'.$this->devulve_lista_estudiante($estudiantesNee,1).'</td>';
                                            $this->html .= '</tr>';
                                            $this->html .= '<tr>';
                                                $this->html .= '<td style="background-color: #ab0a3d; color: #eee" class="text-center">GRADO 2</td>';
                                                $this->html .= '<td>'.$this->devulve_lista_estudiante($estudiantesNee,2).'</td>';
                                            $this->html .= '</tr>';
                                            $this->html .= '<tr>';
                                                $this->html .= '<td style="background-color: #ab0a3d; color: #eee" class="text-center">GRADO 3</td>';
                                                $this->html .= '<td>'.$this->devulve_lista_estudiante($estudiantesNee,3).'</td>';
                                            $this->html .= '</tr>';
                                        $this->html .= '</thead>';
                        
                                        $this->html .= '<tbody>';
                                            $this->html .= '<tr>';
                                              
                                            $this->html .= '</tr>';
                                        $this->html .= '</tbody>';
                                    
                                    $this->html .= '</table>';
                            $this->html .= '</div>';
                    $this->html .= '</div>';

              $this->html .= '</div>';
        $this->html .= '</div>';
       
    }
    
   
}