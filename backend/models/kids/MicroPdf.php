<?php

namespace backend\models\kids;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;

use backend\models\CurriculoMecNiveles;
use backend\models\PcaDetalle;
use backend\models\PlanificacionBloquesUnidadSubtitulo;
use backend\models\PlanificacionBloquesUnidadSubtitulo2;
use backend\models\PlanificacionDesagregacionCabecera;

use backend\models\pca\Pca;

class MicroPdf extends \yii\db\ActiveRecord{

    private $experienciaId;
    private $experiencia;
    public  $html;

    public function __construct($experienciaId){
        $this->experienciaId = $experienciaId;
        $this->html = '';       
        $this->experiencia = \backend\models\KidsUnidadMicro::findOne($experienciaId);
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
                    C??digo: ISMR20-22 <br>
                    Versi??n: 5.0<br>
                    Fecha: 28/09/021<br>
                    P??gina: {PAGENO} / {nb}<br>
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
        $html .= '<td class="border" align="center"><b>PLANIFICACI??N MICROCURR??CULAR DE EDUCACI??N PRESCOLAR</b> <br> A??O ESCOLAR ' . $periodo->codigo . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="border" style="background-color: #eee"><b>1.- DATOS INFORMATIVOS</b></td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= $this->uno();
//        $html .= $this->dos();
//        $html .= $this->tres();
//        $html .= $this->cuatro();
//        $html .= $this->cinco();
//        $html .= $this->seis();
//        $html .= $this->siete();
//        $html .= $this->firmas();

        return $html;
    }
    
    private function uno(){
        
        $html = '';
        $html .= '<table width="100%" cellspacing="0" cellpadding="10">';
        $html .= '<tr>';
        $html .= '<td width="" class="border"><b>DOCENTES:</b> ';
        $html .= '</td>';
            
        $html .= '<td width="" class="border"><b>FECHA DE COMIENZO DE LA EXPERIENCIA:</b> ';
        $html .= $this->experiencia->fecha_inicia;
        $html .= '</td>';
        
        $html .= '<td width="" class="border"><b>FECHA QUE TERMINA LA EXPERIENCIA:</b> ';
        $html .= $this->experiencia->fecha_termina;
        $html .= '</td>';
        
        $html .= '<td width="" class="border"><b>GRADO:</b> ';
        $html .= $this->experiencia->pca->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->name;
        $html .= '</td>';
        
        $html .= '</tr>';
                
        $html .= '<tr>';
        $html .= '<td width="50%" class="border"><b>GRADO / CURSO:</b> ';
        $html .= $this->modelPca->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->name;
        $html .= '</td>';        
        
        $helpers = new \backend\models\helpers\HelperGeneral();
        $paralelos = $helpers->get_paralelos_por_template_id($this->opCourseTemplateId);
        $html .= '<td width="50%" class="border"><b>NIVELES:</b> ';
        foreach ($paralelos as $p){
            $html .= ' | '.$p['name'];
        }
        $html .= '</td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        
        return $html;
    }
    
    private function dos(){
        
        $html = '';
        $html .= '<table width="100%" cellspacing="0" cellpadding="10">';
        $html .= '<tr>';
        $html .= '<td class="border" colspan="6" style="background-color: #eee"><b>2.- TIEMPO</b></td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td width="" class="border"><b>CARGA HORARIA SEMANAL:</b> ';
        $html .= $this->modelPca->carga_horaria_semanal;
        $html .= '</td>';
            
        $html .= '<td width="" class="border"><b>N?? SEMANAS TRABAJO:</b> ';
        $html .= $this->modelPca->numero_semanas_trabajo;
        $html .= '</td>';
        
        $html .= '<td width="" class="border"><b>EVALUACI??N DEL APRENDIZAJE E IMPREVISTOS:</b> ';
        $html .= $this->modelPca->imprevistos;
        $html .= '</td>';
                
        $html .= '<td width="" class="border"><b>TOTAL DE SEMANAS CLASES:</b> ';
        $html .= $this->modelPca->numero_semanas_trabajo - $this->modelPca->imprevistos;
        $html .= '</td>';
        
        $html .= '<td width="" class="border"><b>TOTAL DE PERIODOS:</b> ';
        $html .= ($this->modelPca->numero_semanas_trabajo - $this->modelPca->imprevistos)*$this->modelPca->carga_horaria_semanal;
        $html .= '</td>';
        
        $html .= '<td width="" class="border"><b>N?? UNIDADES MICROCURRICULARES: </b> ';
        $html .= 4;
        $html .= '</td>';
        
        $html .= '</tr>';
                
        
        $html .= '</table>';
        
        return $html;
    }
    
    public function tres(){
        $html = '';
        $html .= '<table width="100%" cellspacing="0" cellpadding="10">';
        $html .= '<tr>';
        $html .= '<td class="border" colspan="1" style="background-color: #eee"><b>3.- OBJETIVOS</b></td>';
        $html .= '</tr>';
        
        $html .= '<tr>';  
        $html .= '<td class="border">';  
        $html .= $this->modelPca->objetivos;
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
        
        $model = \backend\models\KidsUnidadMicro::find()->where([
            'pca_id' => $this->pcaId
        ])
        ->orderBy('orden')
        ->all();
        
        $html = '';
        $html .= '<table width="100%" cellspacing="0" cellpadding="10">';
        $html .= '<tr>';
        $html .= '<td class="border" colspan="2" style="background-color: #eee"><b>5.- UNIDADES MICROCURRICULARES</b></td>';
        $html .= '</tr>';
        
        $i = 0;
        foreach ($model as $m){
            $i++;
            $html .= '<tr>';            
            $html .= '<td class="border" width="5%">'.$i.'</td>';            
            $html .= '<td class="border">'.$m->experiencia.'</td>';            
            $html .= '</tr>';
        }
                  
        $html .= '</table>';
        
        return $html;
    }
    
    public function seis(){
        
        $html = '';
        $html .= '<table width="100%" cellspacing="0" cellpadding="10">';
        $html .= '<tr>';
        $html .= '<td class="border" colspan="1" style="background-color: #eee"><b>6.- OBSERVACIONES</b></td>';
        $html .= '</tr>';
        
         $html .= '<tr>';  
        $html .= '<td class="border">';  
        $html .= $this->modelPca->observaciones;
        $html .= '</td>';
        $html .= '</tr>';
                  
        $html .= '</table>';
        
        return $html;
    }
    
    public function siete(){
        
        $html = '';
        $html .= '<table width="100%" cellspacing="0" cellpadding="10">';
        $html .= '<tr>';
        $html .= '<td class="border" colspan="1" style="background-color: #eee"><b>7.- BIBLIOGRAF??A / WEBGRAF??A: (UTILIZAR NORMAS APA VI EDICI??N)</b></td>';
        $html .= '</tr>';
        
         $html .= '<tr>';  
        $html .= '<td class="border">';  
        $html .= $this->modelPca->bibliografia;
        $html .= '</td>';
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
        $html .= '<td align="center" class="border" colspan="1" style="background-color: #eee" width="50%"><b>REVISADO Y APROBADO POR COORDINACI??N</b></td>';
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