<?php

namespace backend\models\kids;

use backend\models\CurCurriculoKidsCriterioEvaluacion;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;

use backend\models\CurriculoMecNiveles;
use backend\models\helpers\HelperGeneral;
use backend\models\KidsMicroDestreza;
use backend\models\KidsMicroObjetivos;
use backend\models\PcaDetalle;
use backend\models\PlanificacionBloquesUnidadSubtitulo;
use backend\models\PlanificacionBloquesUnidadSubtitulo2;
use backend\models\PlanificacionDesagregacionCabecera;

use backend\models\pca\Pca;

class MicroPdf extends \yii\db\ActiveRecord{

    private $experienciaId;
    private $experiencia;
    private $opCourseTemplateId;
    public  $html;

    public function __construct($experienciaId){
        $this->experienciaId = $experienciaId;
        $this->html = '';       
        $this->experiencia = \backend\models\KidsUnidadMicro::findOne($experienciaId);
        $this->opCourseTemplateId = $this->experiencia->pca->opCourse->x_template_id;
        $this->genera_pfd();       
    }

    private function genera_pfd(){

        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 30,
            'margin_bottom' => 0,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);


        $cabecera = $this->cabecera();
        $pie = '<h4>Genera Pie</h4>';

        $mpdf->SetHtmlHeader($cabecera);
        $mpdf->showImageErrors = true;

        $html = $this->cuerpo();
        $mpdf->WriteHTML($html);
        $mpdf->SetFooter($pie);

        $mpdf->Output('Plan-Micro' . "curso" . '.pdf', 'D');
        exit;
    }
    
    private function estilos() {
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

                .colorAyudas{
                    color: #65b2e8;
                }

                    ';
        $html .= '</style>';
        return $html;
    }


    private function cabecera() {
        $html = '';
        $html .= '<table width="100%" cellspacing="0" cellpadding="10">';
        $html .= '<tr>';
        $html .= '<td class="border" align="center" width="20%"><img src="imagenes/instituto/logo/logo2.png" width="60px"></td>';
        $html .= '<td class="border" align="center" width=""></td>';
        $html .= '<td class="border" align="right" width="20%">
                    Código: ISMR20-22 <br>
                    Versión: 5.0<br>
                    Fecha: 28/09/021<br>
                    Página: {PAGENO} / {nb}<br>
                  </td>';
        $html .= '</tr>';
        $html .= '</table>';
        return $html;
    }
    
    
    private function cuerpo() {
        $periodoId = Yii::$app->user->identity->periodo_id;
        $periodo = \backend\models\ScholarisPeriodo::findOne($periodoId);

        $html = $this->estilos();

        $html .= '<table width="100%" cellspacing="0" cellpadding="10">';
        $html .= '<tr>';
        $html .= '<td class="border" align="center"><b>ISM</b> <br> International Scholastic Model</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="border" align="center"><b>PLANIFICACIÓN MICROCURRÍCULAR DE EDUCACIÒN INICIAL</b> <br> AÑO ESCOLAR ' . $periodo->codigo . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="border" style="background-color: #eee"><b>1.- DATOS INFORMATIVOS</b></td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= $this->uno();
        $html .= $this->dos();
        $html .= $this->tres();
//        $html .= $this->cuatro();
       $html .= $this->cinco();
       $html .= $this->firmas();

        return $html;
    }
    
    private function uno(){
        
        $helper = new HelperGeneral();
        $docentes = $helper->query_docentes_x_curso($this->experiencia->pca->op_course_id);

        $objetivosIntegradores = KidsMicroObjetivos::find()->where(['micro_id' => $this->experiencia->id])->all();

        $html = '';
        $html .= '<table width="100%" cellspacing="0" cellpadding="10">';
        $html .= '<tr>';
        $html .= '<td width="60%" class="border"><b>DOCENTES:</b> ';
        foreach( $docentes as $docente ){
            $html .= ' | '.$docente['docente'];
        }
        $html .= '</td>';

        $html .= '<td width="40%" class="border" colspan="2"><b>GRADO:</b> ';
        // $html .= $this->experiencia->pca->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->name;
        $html .= $this->experiencia->pca->opCourse->name;
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '<tr>';    
        $html .= '<td width="60%" class="border"><b>EXPERIENCIA DE APRENDIZAJE: </b>'.$this->experiencia->orden.'.-'.$this->experiencia->experiencia.'</td>';
        $html .= '<td width="20%" class="border"><b>FECHA DE COMIENZO DE LA EXPERIENCIA:</b> ';
        $html .= $this->experiencia->fecha_inicia;
        $html .= '</td>';
        
        $html .= '<td width="20%" class="border"><b>FECHA QUE TERMINA LA EXPERIENCIA:</b> ';
        $html .= $this->experiencia->fecha_termina;
        $html .= '</td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td width="" colspan="3" class="border"><b>OBJETIVOS INTEGRADORES:</b> ';
        foreach($objetivosIntegradores as $objetivo){
            $html.= '<b>* '.$objetivo->objetivo->codigo.'</b> '.$objetivo->objetivo->detalle.'<br>';
        }
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        return $html;
    }
    
    private function dos(){

        $criteriosEvaluacion = KidsMicroDestreza::find()->where(['micro_id' => $this->experiencia->id])->all();
        
        $html = '';
        $html .= '<table width="100%" cellspacing="0" cellpadding="10">';
        $html .= '<tr>';
        $html .= '<td class="border" colspan="6" style="background-color: #eee"><b>2.- PLAN DE EXPERIENCIA</b></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border" colspan="6"><b>CRITERIOS DE EVALUACIÓN: </b>';
        foreach($criteriosEvaluacion as $criterio){
            $html. '<b>'.$criterio->id.'</b>';

            if(isset($criterio->criterio_evaluacion_id)){
                $criterioEva = CurCurriculoKidsCriterioEvaluacion::findOne($criterio->criterio_evaluacion_id);
                $criterioDetalle = '<b>'.$criterioEva->codigo.'</b>'.$criterioEva->nombre;
            }else{
                $criterioDetalle = ' ';
            }

            $html .= $criterioDetalle;

            
        }
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="border centrarTexto" width="16.6"><b>EJES DE DESARROLLO Y APRENDIZAJE</b></td>';
        $html .= '<td class="border centrarTexto" width="16.6"><b>ÁMBITO DE DESARROLLO Y APRENDIZAJE</b></td>';
        $html .= '<td class="border centrarTexto" width="16.6"><b>DESTREZAS CON CRITERIO DE DESEMPEÑO</b></td>';
        $html .= '<td class="border centrarTexto" width="16.6"><b>ACTIVIDADES DE APRENDIZAJE</b></td>';
        $html .= '<td class="border centrarTexto" width="16.6"><b>RECURSOS</b></td>';
        $html .= '<td class="border centrarTexto" width="16.6"><b>INDICADORES PARA EVALUAR</b></td>';
        $html .= '</tr>';

        $detalleDestrezas = $this->get_detalle_destrezas();

        foreach($detalleDestrezas as $det){
            $html .= '<tr>';
            $html .= '<td class="border">'.$det['eje'].'</td>';
            $html .= '<td class="border">'.$det['ambito'].'</td>';
            $html .= '<td class="border">'.$det['destreza'].'</td>';
            $html .= '<td class="border">'.$det['actividades_aprendizaje'].'</td>';
            $html .= '<td class="border">'.$det['recursos'].'</td>';
            $html .= '<td class="border">'.$det['indicadores_evaluacion'].'</td>';
            $html .= '</tr>';
        }
                
        
        $html .= '</table>';
        
        return $html;
    }

    private function get_detalle_destrezas(){
        $con = Yii::$app->db;
        $query = "select 	eje.nombre	as eje
                        ,amb.nombre as ambito
                        ,des.nombre as destreza
                        ,kmd.actividades_aprendizaje 
                        ,kmd.recursos 
                        ,kmd.indicadores_evaluacion 
                from 	kids_micro_destreza kmd
                        inner join cur_curriculo_destreza des on des.id = kmd.destreza_id 
                        inner join cur_curriculo_ambito amb on amb.id = des.ambito_id
                        inner join cur_curriculo_eje eje on eje.id = amb.eje_id 
                where 	kmd.micro_id = $this->experienciaId;";

        $res = $con->createCommand($query)->queryAll();
        return $res;
    } 
    
    public function tres(){
        $html = '';
        $html .= '<table width="100%" cellspacing="0" cellpadding="10">';
        $html .= '<tr>';
        $html .= '<td class="border" colspan="1" style="background-color: #eee"><b>3.- ADAPTACIONES CURRICULARES</b></td>';
        $html .= '</tr>';
        
        $html .= '<tr>';  
        $html .= '<td class="border">';  
        $html .= 'VIENE DESDE LA RETROALIMENTACION DE CADA DOCENTE';
        $html .= '</td>';
        $html .= '</tr>';
                    
        $html .= '</table>';
        
        return $html;
    }

    public function cuatro(){
        $html = '';
        $html .= '<table width="100%" cellspacing="0" cellpadding="10">';
        $html .= '<tr>';
        $html .= '<td class="border" colspan="1" style="background-color: #eee"><b>4.- EJES TRANSVERSALES</b></td>';
        $html .= '</tr>';
        
        $html .= '<tr>';  
        $html .= '<td class="border">';  
        $html .= '<ul>';
        $html .= '<li>Justicia</li>';
        $html .= '<li>Solidaridad</li>';
        $html .= '<li>Innovador</li>';
        $html .= '</ul>';
        $html .= '</td>';
        $html .= '</tr>';
                    
        $html .= '</table>';
        
        return $html;
    }
    
    public function cinco(){
        
        $html = '';
        $html .= '<table width="100%" cellspacing="0" cellpadding="10">';
        $html .= '<tr>';
        $html .= '<td class="border" style="background-color: #eee"><b>5.- OBSERVACIONES</b></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="border">'.$this->experiencia->observaciones.'</td>';
        $html .= '</tr>';
                  
        $html .= '</table>';
        
        return $html;
    }
    
    
    public function firmas(){
        
        $html = '';
        $html .= '<br>';
        $html .= '<table width="100%" cellspacing="0" cellpadding="10">';
        
        $html .= '<tr>';
        $html .= '<td align="center" class="border" colspan="1" style="background-color: #eee;" width="50%"><b>ELABORADO POR</b></td>';
        $html .= '<td align="center" class="border" colspan="1" style="background-color: #eee" width="50%"><b>REVISADO Y APROBADO POR COORDINACIÓN</b></td>';
        $html .= '</tr>';
        
        $html .= '<tr>';  
        $html .= '<td class="border">DOCENTES</td>';
        $html .= '<td class="border">NOMBRE</td>';
        $html .= '</tr>';
        
        $html .= '<tr>';  
        $html .= '<td class="border">FIRMA</td>';
        $html .= '<td class="border">FIRMA</td>';
        $html .= '</tr>';
        
        $html .= '<tr>';  
        $html .= '<td class="border">FECHA</td>';
        $html .= '<td class="border">FECHA</td>';
        $html .= '</tr>';
                  
        $html .= '</table>';
        
        return $html;
    }
    
}

?>