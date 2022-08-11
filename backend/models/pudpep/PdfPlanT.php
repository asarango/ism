<?php
namespace backend\models\pudpep;

use backend\models\PlanificacionBloquesUnidad;
use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;
use backend\models\PudPep;
use backend\models\ScholarisPeriodo;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;

class PdfPlanT extends \yii\db\ActiveRecord{

    private $planUnidadId;
    private $opCourseTemplateId;
    private $unidad;
    private $periodoId;

    public function __construct($planUnidadId){
        
        $this->periodoId = Yii::$app->user->identity->periodo_id;
        
        $this->planUnidadId = $planUnidadId;
        $this->unidad = \backend\models\PepPlanificacionXUnidad::findOne($planUnidadId);
        
        $this->opCourseTemplateId = $this->unidad->op_course_template_id;
        
        $this->generate_pdf();
    }

    private function generate_pdf(){
        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 2,
            'margin_bottom' => 0,
            'margin_header' => 2,
            'margin_footer' => 0,
        ]);

         //$cabecera = $this->cabecera();
        $pie = $this->pie('{PAGENO}');

//        $mpdf->SetHtmlHeader($cabecera);
        $mpdf->showImageErrors = true;

        
        $caratula = $this->caratula();
        $html = $this->cuerpo();
        
        $mpdf->WriteHTML($caratula);
        $mpdf->addPage();
        $mpdf->WriteHTML($html);
       
        //}
//        $mpdf->addPage();
        $mpdf->SetFooter($pie);

        //$mpdf->Output('Planificacion-de-unidad' . "curso" . '.pdf', 'D');
        $mpdf->Output();
        exit;
    }
            

    private function cabecera(){
        $html = ''; 
        $html .= '<table width="100%" cellspacing="0" cellpadding="8">'; 
        $html .= '<tr>'; 
        $html .= '<td class="border" align="center" width="20%"><img src="imagenes/instituto/logo/logo2.png" width="60px"></td>';
        $html .= '<td class="border" align="center" width=""></td>';
        $html .= '<td class="border" align="right" width="20%">Código: ISMR20-18</td>';
        $html .= '</tr>'; 
        $html .= '</table>'; 
        return $html;
    }
    
    private function pie($numeroPagina){
        
        $html = ''; 
        $html .= '<table width="100%" cellspacing="0" cellpadding="10">'; 
        $html .= '<tr>'; 
        $html .= '<td class="" align="center" width="20%">'
                . '<img src="imagenes/bi/ib_logo.jpg" width="150px" style="border: 1px solid #ccc"></td>';
        $html .= '<td class="" align="center">© Organización del Bachillerato Internacional , 2019 <br>'
                . 'International Baccalaureate®  Baccalauréat International® <br>'
                . 'Bachillerato Internacional®</td>';
        $html .= '<td class="" align="right" width="40%">PEP Planificador de unidades de indagación (educación primaria) <br> Página '.$numeroPagina.'</td>';
        $html .= '</tr>'; 
        $html .= '</table>'; 
        return $html;
//        
//  
    }
    
    private function caratula(){
        $html = '';
        $html.= $this->estilos();    
        $html.= '<img src="imagenes/bi/fondobipep.jpeg" width="1305px" height="600px" style="margin:0 -30 -40">';
        
        return $html;
    }

    private function cuerpo(){
        $html = '';
        $html .= $this->informacion(); 
//        $html .= $this->dos(); 
//        $html .= $this->dos_detalle(); 

        return $html;
    }
    
    private function informacion(){
        $hoy = date("Y-m-d");
        $html = '';
        $html .= '<table width="100%" cellspacing="2" cellpadding="4" style="background-color: #eee; border: solid 1px #ccc">'; 
        $html .= '<tr>'; 
        $html .= '<td class="" align="" width="10%"><b>Curso / grado escolar:</b></td>';
        $html .= '<td class="" align="" width="20%">'.$this->unidad->opCourseTemplate->name.'</td>';
        $html .= '<td class="" align="" width="20%"><b>Equipo docente colaborativo:</b></td>';
        $html .= '<td class="" align="" width="50%"></td>';
        $html .= '</tr>'; 
        $html .= '<tr>'; 
        $html .= '<td class="" align="" width="20%"><b>Fecha:</b></td>';
        $html .= '<td class="" align="" width="20%">'.$hoy.'</td>';
        $html .= '<td class="" align="" width="20%"><b>Cronograma:</b></td>';
        $html .= '<td class="" align="" width="20%"></td>';
        $html .= '</tr>'; 
        $html .= '</table>';
        return $html;
    }
    
    private function get_docentes(){
        $con = Yii::$app->db;
        $query = "select 	concat(fa.x_first_name, ' ', fa.last_name) as docente 
                    from 	scholaris_clase cl
                                    inner join op_course_paralelo pa on pa.id = cl.paralelo_id 
                                    inner join op_course cu on cu.id = pa.course_id
                                    inner join ism_area_materia am on am.id = cl.ism_area_materia_id 
                                    inner join ism_malla_area ma on ma.id = am.malla_area_id 
                                    inner join ism_periodo_malla pm on pm.id = ma.periodo_malla_id
                                    inner join op_faculty fa on fa.id = cl.idprofesor 
                    where 	cu.x_template_id = $this->opCourseTemplateId
                                    and pm.scholaris_periodo_id = $this->periodoId
                    group by fa.x_first_name, fa.last_name 
                    order by fa.x_first_name, fa.last_name ;";
        $res = $con->createCommand()->queryAll();
        return $res;
    }
    
    private function estilos(){
        $html = '';
        $html .= '<style>';
        $html .= '.border {
                    border: 0.1px solid black;
                  }
                  
                  .centrarTexto {
                    text-align: center;
                  }
                  .derechaTexto {
                    text-align: right;
                  }
                  
                  .tamano6{
                    font-size: 6px;
                  }
                  
                  .tamano8{
                    font-size: 9px;
                  }
                  
                .tamano10{
                    font-size: 10px;
                 }
                 
                 .paddingTd{
                    padding: 2px;
                }
                
                .colorPlomo{
                    background-color:#c9cfcb;
                }
                
                .colorFinal{
                    background-color:#8ccaa0;
                }

                    ';
        $html .= '</style>';
        return $html;
    }
}


?>