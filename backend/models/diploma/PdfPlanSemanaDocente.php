<?php
namespace backend\models\diploma;

use backend\models\PlanificacionSemanal;
use backend\models\PlanificacionSemanalRecursos;
use backend\models\ResUsers;
use backend\models\ScholarisActividad;
use backend\models\ScholarisClase;
use backend\models\ScholarisPeriodo;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;
use datetime;

class PdfPlanSemanaDocente extends \yii\db\ActiveRecord
{
    private $periodo;
    private $plan;
    private $semanaId;
    private $usuario;
    private $user;

    public function __construct($semanaId, $usuario, $periodId)
    {     
        $this->semanaId = $semanaId;
        $this->periodo  = ScholarisPeriodo::findOne($periodId);     
        $this->usuario  = $usuario;
        $this->user     = ResUsers::find()->where(['login' => $usuario])->one();

        $this->generate_pdf();   
    }

        
    private function generate_pdf()
    {
        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 25,
            'margin_bottom' => 4,
            'margin_header' => 2,
            'margin_footer' => 0,
        ]);
        $cabecera = $this->cabecera();
        
        $mpdf->SetHtmlHeader($cabecera);
        $mpdf->showImageErrors = true;  
        
        $html = $this->cuerpo();         
        // $html.= $this->firmas();      
        
       
        $mpdf->WriteHTML($html); 

        // $piePagina=$this->piePagina();
        // $mpdf->SetFooter($piePagina);      

        //$mpdf->Output('Planificacion-de-unidad' . "curso" . '.pdf', 'D');
        $mpdf->Output();
        exit;
    }

    /****CABCERA */
    private function cabecera()
    {
        
        $html = '<table width="100%" cellspacing="0" cellpadding="10" style="font-size: 10px; font-family: Gill Sans">';
        $html .= '<tr>';
        $html .= '<td align="center">';
        $html .= '<img = src="imagenes/instituto/logo/logoISM.JPG" width="40px"><img src="imagenes/instituto/logo/diploma.png" width="60px" align = "right">';
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td align="center"><b>PLANIFICACIÓN SEMANAL - DIP<br>AÑO LECTIVO '.$this->periodo->nombre.'</b></td>';
        $html .= '</tr>';

        $html .= '</table>';
        return $html;
    }

    private function piePagina()
    {
        $html =<<<EOP
        <table  width="100%">
            <tr>
                <td >Información obtenida de la guía de los Principios a la Práctica del 2014 del IB, Págs. 108 - 114</td>
                <td ><img src="imagenes/instituto/logo/logoISO.png" width="40px" align = "right"></td>
            </tr>
        </table>

        EOP;

        return   $html;     
    }
    private function firmas()
    {
        $html = $this->estilos();
        $html .= <<<EOD
        <br>
        <br>
        <table width="100%" cellspacing="0" cellpadding="5">         
            <tr> 
                <td  align="center" style="font-size:10" class="border">
                    FIRMA DE APROBACIÓN DE COORDINACIÓN
                </td>               
            </tr> 
            <tr> 
                <td align="left" style="font-size:10" class="border"><br><br><br><br><br><br></td>
            </tr> 
        </table> 
        EOD;      
        return $html;
    }  
    private function cuerpo()
    {       
        $html = $this->estilos();
        $html.= $this->primera_parte();
        $html.= $this->segunda_parte();
        // $html.= $this->tercera_parte();
        
        return $html;
    }

    private function primera_parte(){
        $html = '<table class="tamano10" width="100%" cellpadding="1" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<th class="fondoTh border">PROFESOR(ES)</th>';
        $html .= '<td class="centrarTexto centrarTexto border" colspan="3">'.
               $this->user->partner->name.
               '</td>';
        
        $html .= '<th class="fondoTh border">SEMANA</th>';
        $html .= '<td class="centrarTexto border">'.
                    $this->semanaId;
                '</td>';
        $html .= '</tr>';

        $html .= '</table>';

        return $html;
    }

    private function segunda_parte(){
        $html = '<table width="100%" cellpadding="2" cellspacing="0" class="marginTop10 tamano10">';
        $html .= '<tr>';
        $html .= '<th class="centrarTexto border fondoTh">FECHA</th>';
        $html .= '<th class="centrarTexto border fondoTh">DÍA</th>';        
        $html .= '<th class="centrarTexto border fondoTh">HORA</th>';
        $html .= '<th class="centrarTexto border fondoTh">CURSO</th>';
        $html .= '<th class="centrarTexto border fondoTh">PARALELO</th>';
        $html .= '<th class="centrarTexto border fondoTh">TEMA</th>';
        $html .= '<th class="centrarTexto border fondoTh">ACTIVIDADES</th>';
        $html .= '<th class="centrarTexto border fondoTh">DIF. NEE</th>';
        $html .= '<th class="centrarTexto border fondoTh">INSUMOS</th>';
        $html .= '<th class="centrarTexto border fondoTh">RECURSOS</th>';
        $html .= '</tr>';

        $planSemanal = $this->get_planificiacion();
        
        foreach($planSemanal as $plan){

            $insumos = ScholarisActividad::find()->where([
                'plan_semanal_id'  => $plan['id']
            ])->all();

            $lms = PlanificacionSemanalRecursos::find()->where([
                'plan_semanal_id'  => $plan['id']
            ])->all();

            $html .= '<tr>';
            $html .= '<td class="centrarTexto border">'.$plan['fecha'].'</td>';
            setlocale(LC_TIME, 'es_ES.UTF-8');
            $diaTimeStamp = strtotime($plan['fecha']);
            $dia = strftime("%A", $diaTimeStamp);
            $html .= '<td class="centrarTexto border">'.$dia.'</td>';
            $html .= '<td class="centrarTexto border">'.$plan['sigla'].'</td>';
            $html .= '<td class="centrarTexto border">'.$plan['curso'].'</td>';
            $html .= '<td class="centrarTexto border">'.$plan['paralelo'].'</td>';
            $html .= '<td class="centrarTexto border">'.$plan['tema'].'</td>';
            $html .= '<td class="centrarTexto border">'.$plan['actividades'].'</td>';
            $html .= '<td class="centrarTexto border">-</td>';
            $html .= '<td class="centrarTexto border">';

                foreach($insumos as $insumo){
                    $html .= '<ul>';
                    $html .= '<li>'.$insumo->title.'</li>';
                    $html .= '</ul>';
                }

            $html .= '</td>';

            $html .= '<td class="centrarTexto border">';
                foreach($lms as $l){
                    $html .= '<ul>';
                    $html .= '<li>'.$l->tema.'</li>';
                    $html .= '</ul>';
                }
            $html .= '</td>';

            $html .= '</tr>';
        }

        $html .= '</table>';
        return $html;
    }


    private function get_planificiacion(){
        $con = Yii::$app->db;
        $query = "select ps.id ,ps.fecha 
		,hor.sigla 
		,cur.name as curso
		,par.name as paralelo
		,ps.tema ,ps.actividades 
		,ps.diferenciacion_nee
from 	planificacion_semanal ps 
		inner join scholaris_horariov2_hora hor on hor.id = ps.hora_id 
		inner join scholaris_clase cla on cla.id = ps.clase_id 
		inner join op_course_paralelo par on par.id = cla.paralelo_id 
		inner join op_course cur on cur.id = par.course_id 
where 	ps.semana_id = $this->semanaId 
		and ps.created = '$this->usuario' 
order by ps.fecha, hor.sigla;";

        // echo $query;
        // die();

        $habilidades = $con->createCommand($query)->queryAll();
        return $habilidades;
    }


    private function tercera_parte(){
        $con = Yii::$app->db;
        $query = "select es_titulo1 from contenido_pai_habilidades group by es_titulo1;";
        $habilidades = $con->createCommand($query)->queryAll();
       
        $html = '';
        foreach($habilidades as $hab){
            $html .= '<br><table width="100%">';
            $html .= '<tr>';
            $html .= '<td class="" style="border: solid 1px #ab0a3d"><b>'.$hab['es_titulo1'].'</b></td>';
            $html .= '</tr>';
            $html .= '</table>';

            $html .= $this->get_habilidades_grupo($hab['es_titulo1']);

        }

        return $html;

    }

    


    private function estilos(){
        $html = '';
        $html .= '<style>';
        $html .= '.border {
                    border: 0.1px solid #000;
                  }

                  .marginTop10{
                    margin-top: 10px;
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

                .fondoTh{
                    background-color:#80c9fc;
                }

                    ';
        $html .= '</style>';
        return $html;
    }
    
}


?>