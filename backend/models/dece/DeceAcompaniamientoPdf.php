<?php
namespace backend\models\dece;

use backend\models\DeceRegistroSeguimiento;
use backend\models\DeceSeguimientoAcuerdos;
use backend\models\DeceSeguimientoFirmas;
use backend\models\helpers\Scripts;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;
use datetime;
use backend\models\helpers\HelperGeneral;



class DeceAcompaniamientoPdf extends \yii\db\ActiveRecord
{
    private $dece_acompaniamiento;
    private $colorFondo ='#D5DBDB';
    
    public function __construct($id_acompaniamiento)
    {     
        $this->dece_acompaniamiento = DeceRegistroSeguimiento::findOne($id_acompaniamiento);                      
        $this->generate_pdf();
    }
    private function generate_pdf()
    {
        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 40,
            'margin_bottom' => 10,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);
        $cabecera = $this->cabecera();
        $mpdf->SetHtmlHeader($cabecera);
        $mpdf->showImageErrors = true;  
        
        $html = $this->cuerpo();               
        $html.= $this->firmas();            
        $mpdf->WriteHTML($html); 

        // $piePagina=$this->piePagina();
        // $mpdf->SetFooter($piePagina);      

        //$mpdf->Output('Planificacion-de-unidad' . "curso" . '.pdf', 'D');
        $mpdf->Output();
        exit;
    }
    /****CABCERA */
    private function cabecera(){
        $codigoISO = "ISMR20-08";
        $version ="6.0";
        $fecha=date('Y-m-d H:i:s'); 
        $fecha='24/10/2022';
        $html = <<<EOT
        <table border="1" width="100%" cellspacing="0" cellpadding="10"> 
            <tr> 
                <td class="border" align="center" width="20%">
                    <img src="imagenes/instituto/logo/logoISM1.png" width="80px">
                    <br>
                    Proceso Académico
                </td>
                <td class="border" align="center" width="" >
                        <font size="4">
                                <b>
                                <p>ISM</p>
                                <p>International Scholastic Model</p> 
                                </b>
                        </font>                       
                    </td>
                <td class="border" align="left" width="20%">
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
        EOT;  
        return $html;
    }
    private function piePagina(){
        
    }
    private function firmas()
    {

    }
    private function acuerdos()
    {
        $model = $this->dece_acompaniamiento;
        $modelSegFirmas = DeceSeguimientoAcuerdos::find()
        ->where(['id_reg_seguimiento'=>$model->id])
        ->orderBy(['secuencial'=>SORT_ASC])
        ->all();

        $html = '
        <br>
        <table border="1" width="100%" cellspacing="0" cellpadding="5">    
            <tr style="background-color:'.$this->colorFondo.'"> 
                <td colspan="4"  align="left" style="font-size:10">
                    <b>3.- DETALLE DE ACUERDOS</b>
                </td>
            </tr>
            <tr> 
                <td align="left" style="font-size:10"><b> Item </b></td>
                <td align="left" style="font-size:10"><b> Acuerdo y responsable </b></td>
                <td align="left" style="font-size:10"><b> Fecha Máximo Cumplimiento </b></td>
                <td align="left" style="font-size:10"><b> Cumplimiento </b></td>             
            </tr>';
            foreach($modelSegFirmas as $acuerdo)
            {
                $html.='<tr> 
                            <td align="left" style="font-size:10"> '.$acuerdo->secuencial.'.- </td>
                            <td align="left" style="font-size:10"> '.$acuerdo->responsable.' </td>
                            <td align="left" style="font-size:10"> '.substr($acuerdo->fecha_max_cumplimiento,0,10).' </td>';
                            if($acuerdo->cumplio)
                            {
                                $html.='<td align="left" style="font-size:10"> SI </td>';  
                                
                            }else
                            {
                                $html.='<td align="left" style="font-size:10"> </td>';  
                            }
                                       
                            $html.='</tr>';                
            }    
        
        $html.='</table>';

        return $html;
    }
   

    private function firmas_acuerdos()
    {
        $model = $this->dece_acompaniamiento;
        $modelSegFirmas = DeceSeguimientoFirmas::find()
        ->where(['id_reg_seguimiento'=>$model->id])
        ->all();

        $html = '
        <br>
        <table border="1" width="100%" cellspacing="0" cellpadding="5">    
            <tr style="background-color:'.$this->colorFondo.'"> 
                <td colspan="4"  align="left" style="font-size:10">
                    <b>4.- FIRMAS</b>
                </td>
            </tr>
            <tr> 
                <td align="left" style="font-size:10" width="20%"> <b>Nombre</b> </td>
                <td align="left" style="font-size:10" width="20%"> <b>Cédula</b> </td>
                <td align="left" style="font-size:10" width="20%"> <b>Parentesco / Cargo</b> </td>
                <td align="left" style="font-size:10" width="40%"> <b>Firma</b> </td>             
            </tr>';
            foreach($modelSegFirmas as $firma)
            {
                $html.='<tr> 
                            <td align="left" style="font-size:10"> '.$firma->nombre.' </td>
                            <td align="left" style="font-size:10"> '.$firma->cedula.' </td>
                            <td align="left" style="font-size:10"> '.$firma->parentesco.' '.$firma->cargo.' </td>
                            <td align="left" style="font-size:10"> </td>                
                        </tr>';                
            }    
        
        $html.='</table>';

        return $html;
    }
    private function cabecera_acompaniamiento()
    {
        $model = $this->dece_acompaniamiento;
        $estudiante = $model->estudiante->last_name.' '.$model->estudiante->middle_name.' '.$model->estudiante->first_name;
        $objScript = new Scripts();
        $arrayCurso = $objScript->mostrar_curso_estudiante($model->id_estudiante);

        $curso = '';
        if($arrayCurso[0]) {$curso = $arrayCurso[0]['curso'];}   

        $html ='';
        $html.='
        <table border="1" width="100%" cellspacing="0" cellpadding="5">  
                <tr style="background-color:'.$this->colorFondo.'"> 
                        <td colspan="5"  align="center" style="font-size:12">
                            <b>REGISTRO DE ACOMPAÑAMIENTO</b>
                        </td>
                </tr>
                <tr style="background-color:'.$this->colorFondo.'"> 
                        <td colspan="5"  align="left" style="font-size:10">
                            <b>1.- DATOS INFORMATIVOS</b>
                        </td>
                </tr>
                <tr style="background-color:'.$this->colorFondo.'"> 
                        <td colspan="2"  align="left" style="font-size:10">
                            <b>Nombre completo estudiante/staff:</b>
                        </td>
                        <td colspan="3"  align="left" style="font-size:10">
                            <b>Nombre de quien lidera:</b>
                        </td>
                </tr>
                <tr > 
                        <td colspan="2"  align="left" style="font-size:10">
                            '.$estudiante.'
                        </td>
                        <td colspan="3"  align="left" style="font-size:10">
                            '.$model->nombre_quien_lidera.'
                        </td>
                </tr>                
                <tr style="background-color:'.$this->colorFondo.'"> 
                        <td align="left" style="font-size:10">
                            <b>Departamento</b>
                        </td>
                        <td align="left" style="font-size:10">
                            <b>Fecha</b>
                        </td>
                        <td align="left" style="font-size:10">
                            <b>Grado/Curso</b>
                        </td>
                        <td align="left" style="font-size:10">
                            <b>Hora de Inicio</b>
                        </td>
                        <td align="left" style="font-size:10">
                            <b>Hora de Cierre</b>
                        </td>
                </tr>
                <tr > 
                        <td align="left" style="font-size:10">
                        '.$model->departamento.'
                        </td>
                        <td align="left" style="font-size:10">
                        '.$model->fecha_inicio.'
                        </td>
                        <td align="left" style="font-size:10">
                        '.$curso.'
                        </td>
                        <td align="left" style="font-size:10">
                        '.$model->hora_inicio.'
                        </td>
                        <td align="left" style="font-size:10">
                        '.$model->hora_cierre.'
                        </td>
                </tr>
        </table>        
        ';
        $html.='<br>';
        $html.='
        <table border="1" width="100%" cellspacing="0" cellpadding="5"> 
                <tr style="background-color:'.$this->colorFondo.'"> 
                        <td colspan="5"  align="left" style="font-size:10">
                            <b>2.- DETALLE DEL SEGUIMIENTO</b>
                        </td>
                </tr>
                <tr > 
                        <td colspan="5"  align="left" style="font-size:10">
                            '.$model->pronunciamiento.'
                        </td>
                </tr>
        </table>';
        return  $html;
    }
    private function mostrar_curso_estudiante($idEstudiante)
    {
        $usuarioLog = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;
        $con = Yii::$app->db;
        $query ="select  distinct c4.id,concat(c4.last_name, ' ',c4.first_name,' ',c4.middle_name) as student,
                concat( c8.name,' ', c7.name ) curso 
                from scholaris_clase c1 , scholaris_grupo_alumno_clase c2 ,
                    op_student c4 ,op_student_inscription c5, 
                    scholaris_op_period_periodo_scholaris c6,op_course_paralelo c7, op_course c8
                where  c1.id = c2.clase_id 
                        and c2.estudiante_id = c4.id 
                        and c2.estudiante_id ='$idEstudiante'
                        and c4.id = c5.student_id 
                        and c5.period_id  = c6.op_id 
                        and c6.scholaris_id = '$periodoId'
                        and c7.id = c1.paralelo_id 
                        and c8.id = c7.course_id 
                order by student;";

               
        $resp = $con->createCommand($query)->queryAll();
        echo '<pre>';
        print_r( $query);
        die();
        return $resp;
    }


    private function cuerpo()
    {
        $html = '';
        $html.= $this->cabecera_acompaniamiento();  
        $html.= $this->acuerdos();
        $html.= $this->firmas_acuerdos();
        return $html;
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