<?php
namespace backend\models\pai;

use backend\models\ContenidoPaiHabilidades;
use backend\models\ContenidoPaiOpciones;
use backend\models\IsmMateria;
use backend\models\MapaEnfoquesPai;
use backend\models\ScholarisPeriodo;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;
use datetime;

class PdfMapaEnfoques extends \yii\db\ActiveRecord
{

    //atributos
    private $materia;
    private $period;
    private $courses;
    
    public function __construct($materiaId)
    {     
        $this->period = ScholarisPeriodo::findOne(Yii::$app->user->identity->periodo_id);
        $this->materia = IsmMateria::findOne($materiaId);                
        $this->courses = $this->get_courses();
        $this->generate_pdf();   
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
        $html.= $this->firmas();      
        
       
        $mpdf->WriteHTML($html); 

        $piePagina=$this->piePagina();
        $mpdf->SetFooter($piePagina);      

        //$mpdf->Output('Planificacion-de-unidad' . "curso" . '.pdf', 'D');
        $mpdf->Output();
        exit;
    }


    private function get_courses(){
        $periodId = $this->period->id;
        $con = Yii::$app->db;
        $query = "select 	tem.id 
                            ,tem.name
                            ,case 
                                when tem.name = 'SEPTIMO' then 'PAI 1'
                                when tem.name = 'OCTAVO' then 'PAI 2'
                                when tem.name = 'NOVENO' then 'PAI 3'
                                when tem.name = 'DECIMO' then 'PAI 4'
                                when tem.name = 'BACH1' then 'PAI 5'
                            end as course		
                    from 	op_course cur
                            inner join op_section sec on sec.id = cur.section
                            inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = sec.period_id 
                            inner join op_course_template tem on tem.id = cur.x_template_id
                    where 	sop.scholaris_id = $periodId
                            and sec.code = 'PAI'
                    order by tem.next_course_id desc;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }



    /****CABCERA */
    private function cabecera()
    {
        $codigoISO = 'ISOM20-21';
        $version ="4.0";
        $fecha=date('Y-m-d H:i:s'); 
        $fecha=date('Y-m-d'); 
        $fecha ='23/10/22';
        $html = <<<EOT
        <table width="100%" cellspacing="0" cellpadding="10"> 
            <tr> 
                <td class="" align="center" width="20%" style="border: solid 1px #000">
                    <img src="imagenes/instituto/logo/logoISM1.png" width="40px"><br><font size = "1">Proceso Académico</font>
                </td>
                <td class="border" align="center" width="60%" style="border: solid 1px #000">
                                             
                </td>
                <td class="border" align="left" width="20%" style="border: solid 1px #000">
                    <table style="font-size:8;">
                        <tr>
                            <td>Código:</td>
                            <td>$codigoISO</td> 
                        </tr>
                        <tr>
                            <td>Versión:</td>                            
                            <td>$version</td>
                        </tr> 
                        <tr>
                            <td>Fecha:</td>
                            <td>$fecha</td>
                        </tr> 
                        <tr>
                            <td>Pág: </td>
                            <td>{PAGENO}/{nbpg}</td>
                        </tr> 
                    </table>
                </td>
            </tr> 
        </table> 
        <br>  
             
        EOT;  
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
        $html.= $this->tercera_parte();
        
        return $html;
    }

    private function primera_parte(){
        $html = '<table width="100%" cellpadding="0" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto border"><h3><b>ISM</b><br>International Scholastic Model</h3></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="centrarTexto border"><b>MAPA DE ENFOQUES DEL APRENDIZAJE</b></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="centrarTexto border"><b>AÑO LECTIVO: '.$this->period->nombre.'</b></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border"><b>GRUPO DE ASIGNATURA: </b>'.$this->materia->nombre.'</td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td class="border">';
        $html .= 'Las habilidades de enfoques del aprendizaje están interconectadas y se desarrollan por medio de la indagación para explorar contenidos significativos; es también una herramienta para alcanzar un aprendizaje más sólido y autorregulado a lo largo de los cinco años PAI y en los ocho grupos de asignaturas. 

        ¨Los profesores deben proporcionar regularmente a los alumnos comentarios específicos sobre el desarrollo de dichas habilidades a través de actividades de aprendizaje y evaluaciones formativas. ¨ (De los principios a la práctica, 2014)
        '; 
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '</table>';

        return $html;
    }

    private function segunda_parte(){
        $html = '<table width="100%" cellpadding="0" cellspacing="0" class="marginTop10">';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto border">';
        $html .= '<br><img src="imagenes/bi/mapa_enfoquespng.png"><br>&nbsp;';
        $html.= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        return $html;
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

                    ';
        $html .= '</style>';
        return $html;
    }
    
}


?>