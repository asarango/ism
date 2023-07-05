<?php
namespace backend\models\toc;

use backend\models\ScholarisClase;
use backend\models\ScholarisPeriodo;
use backend\models\TocPlanUnidad;
use backend\models\TocPlanVertical;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;
use datetime;

class PdfTocAnual extends \yii\db\ActiveRecord
{
    PRIVATE $claseId;
    private $modelClase;
    private $periodo;
    private $plan;
    private $unidades;

    public function __construct($claseId)
    {     
        $this->claseId = $claseId;
        $this->modelClase = ScholarisClase::findOne($claseId);
        $this->periodo = ScholarisPeriodo::findOne(Yii::$app->user->identity->periodo_id);        
        $this->plan = TocPlanVertical::find()->where([
            'clase_id' => $claseId
        ])->all();

        $this->unidades = $this->get_unidades();


        $this->generate_pdf();   
    }

    private function get_unidades(){        
        $con = Yii::$app->db;
        $query = "select 	uni.id, uni.bloque_id, uni.clase_id, uni.titulo, uni.objetivos, uni.conceptos_clave, uni.contenido, uni.evaluacion_pd
                    from 	toc_plan_unidad uni
                            inner join scholaris_bloque_actividad blo on blo.id = uni.bloque_id
                    where 	uni.clase_id = $this->claseId
                    order by blo.orden;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
        
    private function generate_pdf()
    {
        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-P',
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
        $html .= '<td align="center"><b>PLANIFICACIÓN VERTICAL - PD<br>CLASE '.$this->periodo->nombre.'</b></td>';
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
        $html .= '<td class="centrarTexto border" colspan="3">'.
               $this->modelClase->profesor->last_name.
               ' '.
               $this->modelClase->profesor->x_first_name.
               '</td>';

        $html .= '<th class="fondoTh border" colspan="2">GRUPO DE ASIGNATURAS, CURSO Y NIVEL</th>';
        $html .= '<td class="border" colspan="2">'.
                    $this->recorre_data('GRUPO_ASIGNATURA');
                '</td>';

        $html .= '<th class="fondoTh border">AÑO DEL DIPLOMA</th>';
        $html .= '<td class="border">'.
                    $this->recorre_data('ANIO');
                '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<th class="fondoTh border">CARGA HORARIA SEMANAL:</th>';
        $html .= '<td class="border">'.
                    $this->recorre_data('CARGA_HORARIA');
                '</td>';

        $html .= '<th class="fondoTh border">NRO. SEMANAS DE TRABAJO:</th>';
        $html .= '<td class="border">'.
                    $this->recorre_data('NUM_SEMANAS');
                '</td>';

        $html .= '<th class="fondoTh border">TOTAL DE SEMANAS DE CLASE:</th>';
        $html .= '<td class="border">'.
                    $this->recorre_data('TOTAL_SEMANAS');
                '</td>';

        $html .= '<th class="fondoTh border">EVALUACIÓN DEL APRENDIZAJE E IMPREVISTOS:</th>';
        $html .= '<td class="border">'.
                    $this->recorre_data('IMPREVISTOS');
                '</td>';

        $html .= '<th class="fondoTh border">CANTIDAD DE UNIDADES:</th>';
        $html .= '<td class="border">'.
                    $this->recorre_data('TOTAL_SEMANAS');
                '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }

    private function segunda_parte(){
        $html = '<table width="100%" cellpadding="2" cellspacing="0" class="marginTop10 tamano10">';
        $html .= '<tr>';
        $html .= '<th class="centrarTexto border fondoTh">NRO.</th>';
        $html .= '<th class="centrarTexto border fondoTh">TÍTULO DE LA UNIDAD</th>';
        $html .= '<th class="centrarTexto border fondoTh">OBJETIVOS DE LA UNIDAD</th>';
        $html .= '<th class="centrarTexto border fondoTh">CONCEPTOS CLAVE</th>';
        $html .= '<th class="centrarTexto border fondoTh">CONTENIDO</th>';
        $html .= '<th class="centrarTexto border fondoTh">HABILIDADES IB</th>';
        $html .= '<th class="centrarTexto border fondoTh">EVALUACIÓN PD</th>';
        $html .= '</tr>';

        $i=0;
        foreach($this->unidades as $unidad){
            $i++;
            $html .= '<tr>';
            $html .= '<td class="border">'.$i.'</td>';
            $html .= '<td class="border">'.$unidad['titulo'].'</td>';
            $html .= '<td class="border">'.$unidad['objetivos'].'</td>';
            $html .= '<td class="border">'.$unidad['conceptos_clave'].'</td>';
            $html .= '<td class="border">'.$unidad['contenido'].'</td>';
            $html .= '<td class="border">';
            
            $habilidades = $this->get_habilidades($unidad['id']);
            $html .= '<ul>';
            foreach($habilidades as $hab){
                $html .= '<li>'.$hab['descripcion'].'</li>';
            }
            $html .= '</ul>';
            $html .= '</td>';
            $html .= '<td class="border">'.$unidad['evaluacion_pd'].'</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';
        return $html;
    }


    private function get_habilidades($unidadId){
        $con = Yii::$app->db;
        $query = "select 	op.descripcion 
                from 	toc_plan_unidad_habilidad pha
                        inner join toc_opciones op on op.id = pha.toc_opciones_id 
                where 	pha.toc_plan_unidad_id = $unidadId
                order by op.opcion;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
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

    private function get_habilidades_grupo($habilidad){
        $con = Yii::$app->db;
        $query = "select 	orden_titulo2 
                            ,es_titulo2 
                    from 	contenido_pai_habilidades
                    where 	es_titulo1 = '$habilidad'
                    group by orden_titulo2 
                            ,es_titulo2;";
        $habilidad = $con->createCommand($query)->queryAll();
               
        $html = '';
        foreach($habilidad as $hab){
            $html.= '<table width="100%" cellpadding="1" cellspacing="0" style="font-size: 10px">';
            $html.= "<tr>";
            $html.= '<td class="border centrarTexto" colspan="6"><b>'.$hab['orden_titulo2'].' HABILIDADES DE '.$hab['es_titulo2'].'</b></td>';
            $html.= "</tr>";
            $html.= "<tr>";
            $html.= '<td class="border centrarTexto" width="65%"><b>EXPLORACIÓN</b></td>';
             foreach($this->courses as $course){
                 $html.= '<td class="centrarTexto border"><b>'.$course['course'].'</b></td>';
             }
            $html.= "</tr>";
            
            /* para tomar los exploradores */
            $exploradores = ContenidoPaiHabilidades::find()->where([
                'es_titulo2' => $hab['es_titulo2']
            ])
            ->orderBy('es_exploracion')

            ->all();

              foreach($exploradores as $exp){
                  $html .= '<tr>';
                  $html .= '<td class="border">'.$exp->es_exploracion.'</td>';

                  $html.= $this->get_explorador($exp->id);

                  $html .= '</tr>';
              }

            /* Fin de exploradores */


            $html.= "</table>";
        }

        return $html;

    }


    private function get_explorador($exploradorId){
        $html = '';
        foreach($this->courses as $course){ 
            $valores= MapaEnfoquesPai::find()->where([
                'periodo_id'=>$this->period->id,
                'course_template_id'=>$course['id'],
                'pai_habilidad_id'=>$exploradorId,
                'materia_id'=>$this->materia->id
            ])->one();
            if($valores->estado==1){
                $opcion="X";
                $color="green";
                }
                else{
                    $opcion="-";
                    $color="red";
                }
            $html .= '<td class="border centrarTexto" style="color:'.$color.'">'.$opcion.'</td>';

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


    private function recorre_data($campo){
        foreach($this->plan as $plan){
            if($plan->opcion_descripcion == $campo){
                return $plan->contenido;
            }
        }
    }
    
}


?>