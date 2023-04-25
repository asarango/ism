<?php
namespace backend\models\dece;

use backend\models\DeceDeteccion;
use backend\models\DeceSeguimientoAcuerdos;
use backend\models\DeceSeguimientoFirmas;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;
use datetime;
use backend\models\helpers\HelperGeneral;



class DeceDeteccionPdf extends \yii\db\ActiveRecord
{
    private $dece_deteccion;
    private $colorFondo ='#D5DBDB';
    
    public function __construct($id_deteccion)
    {     
        $this->dece_deteccion = DeceDeteccion::findOne($id_deteccion);                      
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

        $piePagina=$this->piePagina();
        $mpdf->SetFooter($piePagina);      

        //$mpdf->Output('Planificacion-de-unidad' . "curso" . '.pdf', 'D');
        $mpdf->Output();
        exit;
    }
    /****CABCERA */
    private function cabecera(){
        $codigoISO = "ISMR21-11";
        $version ="11.0";
        $fecha=date('Y-m-d H:i:s'); 
        $fecha='10/11/2021';
        $html = <<<EOT
        <table border="1" width="100%" cellspacing="0" cellpadding="10"> 
            <tr> 
                <td class="border" align="center" width="20%">
                    <img src="imagenes/instituto/logo/logoISM1.png" width="80px">
                    <br>
                    Proceso DECE
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
        $html = '
        <table border="0" width="100%" cellspacing="0" cellpadding="5">    
            <tr> 
                <td class="border" align="right" width="20%">
                    <img src="imagenes/iso/iso.png" width="80px">
                </td>
            </tr>
        </table>';
        
        return $html;
        
    }
    private function firmas()
    {   
        $html=''; 
        $html.='
        <table border="1" width="100%" cellspacing="0" cellpadding="5"> 
                <tr style="background-color:'.$this->colorFondo.'"> 
                        <td colspan="4"  align="left" style="font-size:10">
                            <b>5.- FIRMAS DE RESPONSABILIDAD</b>
                        </td>
                </tr>
                <tr > 
                        <td colspan="2"  align="left" style="font-size:10">
                            <br><br><br><br><br>
                        </td>
                        <td colspan="2"  align="left" style="font-size:10">
                            <br><br><br><br><br>
                        </td>
                </tr>
                <tr > 
                        <td colspan="2"  align="center" style="font-size:10">
                                APELLIDOS Y NOMBRE
                                <br>
                                FIRMA DE QUIEN REPORTA
                        </td>
                        <td colspan="2"  align="center" style="font-size:10">
                                APELLIDOS Y NOMBRE
                                <br>
                                FIRMA PROFESIONAL DECE
                        </td>
                </tr>               
        </table>';
        return  $html;   

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
    private function cabecera_deteccion()
    {
        $model = $this->dece_deteccion;
        // echo '<pre>';
        // print_r($model);
        // die();
        //$estudiante = $model->estudiante->last_name.' '.$model->estudiante->middle_name.' '.$model->estudiante->first_name;
        $html ='';
        $html.='
        <table border="1" width="100%" cellspacing="0" cellpadding="5">  
                <tr style="background-color:'.$this->colorFondo.'"> 
                        <td colspan="4"  align="center" style="font-size:12">
                            <b>FICHA DE DETECCIÓN Y REPORTE</b>
                        </td>
                </tr>
                <tr style="background-color:'.$this->colorFondo.'"> 
                        <td colspan="4"  align="left" style="font-size:10">
                            <b>1.- DATOS INFORMATIVOS GENERALES</b>
                        </td>
                </tr>
                <tr style="background-color:'.$this->colorFondo.'"> 
                        <td colspan="2"  align="left" style="font-size:10">
                            <b>NOMBRE DEL ESTUDIANTE:</b>
                        </td>
                        <td colspan="2"  align="center" style="font-size:10">
                            <b>AÑO Y PARALELO:</b>
                        </td>
                </tr>
                <tr > 
                        <td colspan="2"  align="left" style="font-size:10">
                            '.$model->nombre_estudiante.'
                        </td>
                        <td align="left" style="font-size:10">
                            '.$model->anio.'
                        </td>
                        <td align="left" style="font-size:10">
                            '.$model->paralelo.'
                        </td>
                </tr>   
                <tr style="background-color:'.$this->colorFondo.'"> 
                        <td colspan="4"  align="left" style="font-size:10">
                            <b>2.- PERSONA QUE REPORTA</b>
                        </td>
                </tr>             
                <tr style="background-color:'.$this->colorFondo.'"> 
                        <td align="left" style="font-size:10">
                            <b>NOMBRE</b>
                        </td>
                        <td align="left" style="font-size:10">
                            <b>CARGO</b>
                        </td>
                        <td align="left" style="font-size:10">
                            <b>CÉDULA</b>
                        </td>
                        <td align="left" style="font-size:10">
                            <b>FECHA DE REPORTE</b>
                        </td>
                </tr>
                <tr > 
                        <td align="left" style="font-size:10">
                        '.$model->nombre_quien_reporta.'
                        </td>
                        <td align="left" style="font-size:10">
                        '.$model->cargo.'
                        </td>
                        <td align="left" style="font-size:10">
                        '.$model->cedula.'
                        </td>
                        <td align="left" style="font-size:10">
                        '.substr($model->fecha_reporte,0,10).'
                        </td>
                </tr>
        </table>        
        ';       
        $html.='
        <table border="1" width="100%" cellspacing="0" cellpadding="5"> 
                <tr style="background-color:'.$this->colorFondo.'"> 
                        <td colspan="4"  align="left" style="font-size:10">
                            <b>3.- DESCRIPCIÓN DEL HECHO: (qué paso, quiénes se involucran, dónde, cuándo)</b>
                        </td>
                </tr>
                <tr style="background-color:'.$this->colorFondo.'"> 
                        <td colspan="4"  align="left" style="font-size:10">
                            <b>HORA APROXIMADA: </b>'.$model->hora_aproximada.'
                        </td>
                </tr>               
                <tr > 
                        <td colspan="4"  align="left" style="font-size:10">
                            '.$model->descripcion_del_hecho.'
                        </td>
                </tr>
                <tr style="background-color:'.$this->colorFondo.'"> 
                        <td colspan="4"  align="left" style="font-size:10">
                            <b>ACCIONES REALIZADAS POR LA PERSONA QUE REPORTA</b>
                        </td>
                </tr>
                <tr > 
                        <td colspan="4"  align="left" style="font-size:10">
                            '.$model->acciones_realizadas.'
                        </td>
                </tr>
        </table>';
        $html.='
        <table border="1" width="100%" cellspacing="0" cellpadding="5"> 
                <tr style="background-color:'.$this->colorFondo.'"> 
                        <td colspan="4"  align="left" style="font-size:10">
                            <b>4.- ENLISTE LAS EVIDENCIAS</b>
                        </td>
                </tr>
                <tr > 
                        <td colspan="4"  align="left" style="font-size:10">
                            '.$model->lista_evidencias.'
                        </td>
                </tr>                
        </table>';
      
        return  $html;
    }


    private function cuerpo()
    {
        $html = '';
        $html.= $this->cabecera_deteccion();        
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