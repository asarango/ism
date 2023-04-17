<?php
namespace backend\models\dece;

use backend\models\DeceDerivacion;
use backend\models\DeceDerivacionInstitucionExterna;
use backend\models\DeceDeteccion;
use backend\models\DeceInstitucionExterna;
use backend\models\DeceRegistroSeguimiento;
use backend\models\DeceSeguimientoAcuerdos;
use backend\models\DeceSeguimientoFirmas;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;
use datetime;
use backend\models\helpers\HelperGeneral;



class DeceDerivacionPdf extends \yii\db\ActiveRecord
{
    private $dece_derivacion;
    private $colorFondo ='#D5DBDB';
    
    public function __construct($id_derivacion)
    {     
        $this->dece_derivacion = DeceDerivacion::findOne($id_derivacion);                      
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
        $codigoISO = "ISMR21-06";
        $version ="4.0";
        $fecha=date('Y-m-d H:i:s'); 
        $fecha='08/03/2022';
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
                                <p>PROCESO DECE</p>
                                <p>INFORME DE DERIVACIÓN</p> 
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
    private function firmas()
    {   
        $html=''; 
        $html.='
        <table border="1" width="100%" cellspacing="0" cellpadding="5"> 
                <tr style="background-color:'.$this->colorFondo.'"> 
                        <td colspan="2"  align="left" style="font-size:10">
                            <b>PSICÓLOGO RESPONSABLE</b>
                        </td>
                        <td colspan="2"  align="left" style="font-size:10">
                            <b>DATOS DE REPRESENTANTE</b>
                        </td>
                </tr>
                <tr > 
                        <td colspan="2"  align="left" style="font-size:10">
                            <br>
                            NOMBRE:________________________________________
                            <br>
                            CI:________________________________________________
                        </td>
                        <td colspan="2"  align="left" style="font-size:10">
                            <br>
                            NOMBRE:________________________________________
                            <br>
                            CI:________________________________________________
                        </td>
                </tr>              
        </table>';
        return  $html;   

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
    
    private function cabecera_derivacion()
    {
        $model = $this->dece_derivacion;
        // echo '<pre>';
        // print_r($model);
        // die();
        $nombre_institucion = $model->estudiante->xInstitute->name.' ('.$model->estudiante->xInstitute->location.')';
        $direccion_institucion = $model->estudiante->xInstitute->direccion;
        $telf_institucion = $model->estudiante->xInstitute->telefono;
        $html ='';
        $html.='
        <table border="1" width="100%" cellspacing="0" cellpadding="5">  
                <tr style="background-color:'.$this->colorFondo.'"> 
                        <td colspan="4"  align="center" style="font-size:12">
                            <b>ISM</b>
                            <br>
                            <b>International Scholastic Model</b>
                        </td>
                </tr>
                <tr > 
                        ';
                        if($model->tipo_derivacion=='Interna')
                        {
                            $html.='<td colspan="2"  align="left" style="font-size:10">
                                    <b>Interna:</b> <span style="font-size:15px; color:red;">X</span>
                                    </td>
                                    <td colspan="2"  align="left" style="font-size:10">
                                        <b>Externa:</b>
                                    </td>';

                        }else
                        {
                            $html.='<td colspan="2"  align="left" style="font-size:10">
                                    <b>Interna:</b>
                                    </td>
                                    <td colspan="2"  align="left" style="font-size:10">
                                        <b>Externa:</b> <span style="font-size:15px; color:red;">X</span>
                                    </td>';
                        }
                $html.='      
                </tr>
                <tr style="background-color:'.$this->colorFondo.'"> 
                        <td colspan="4"  align="left" style="font-size:10">
                            <b>Datos Institucionales:</b>
                        </td>
                </tr>
                <tr > 
                        <td colspan="1"  align="left" style="font-size:10">
                            <b>Nombre de la institución educativa:</b>
                        </td>
                        <td colspan="3"  align="left" style="font-size:10">
                        '.$nombre_institucion.'
                        </td>
                </tr>   
                <tr > 
                        <td colspan="1"  align="left" style="font-size:10">
                            <b>Dirección de la institución: </b>
                        </td>
                        <td colspan="1"  align="left" style="font-size:10">
                            '.$direccion_institucion.'
                        </td>
                        <td colspan="1"  align="left" style="font-size:10">
                            <b>Número de teléfono de la Institución: </b>
                        </td>
                        <td colspan="1"  align="left" style="font-size:10">
                           '.$telf_institucion.'
                        </td>
                </tr>  
                <tr > 
                        <td colspan="1"  align="left" style="font-size:10">
                            <b>Datos personales de quien deriva: </b>
                        </td>
                        <td colspan="3"  align="left" style="font-size:10">
                           
                        </td>
                </tr>
                <tr > 
                        <td colspan="1"  align="left" style="font-size:10">
                            <b>Nombre de la persona que deriva:  </b>
                        </td>
                        <td colspan="3"  align="left" style="font-size:10">
                            '.$model->nombre_quien_deriva.'
                        </td>
                </tr>
                <tr > 
                        <td colspan="4"  align="left" style="font-size:10">
                            <b>Fecha de derivación: </b>'.substr($model->fecha_derivacion,0,10).'
                        </td>
                </tr>                
        </table>        
        ';
      
        return  $html;
    }
    private function institucion_externa()
    {
        $model = $this->dece_derivacion;
        

        //institucion externa derivacion
        $arrayInstExterna = DeceInstitucionExterna::find()->asArray()->all();

        $numDivisionesIntExterna = count($arrayInstExterna)/4;
        $numDivisionesIntExterna = intval($numDivisionesIntExterna)+1;      

        $html='';
        $html.='
            <table border="1" width="100%" cellspacing="0" cellpadding="5">
                        <tr style="background-color:'.$this->colorFondo.'"> 
                                <td colspan="6"  align="left" style="font-size:10">
                                    <b>INSTITUCIÓN EXTERNA</b>
                                </td>
                        </tr>';                           
                           $arrayDividido = array_chunk($arrayInstExterna, $numDivisionesIntExterna); 
                                foreach($arrayDividido as $array)
                                {
                                    // echo '<pre>';
                                    // print_r($array);
                                    // die();
                                    $html.='<tr >';
                                        foreach($array as $inst)
                                        {     
                                            $modelDerInsExterna = DeceDerivacionInstitucionExterna::find()
                                            ->where(['id_dece_derivacion'=>$model->id])
                                            ->andWhere(['id_dece_institucion_externa'=>$inst['id']])
                                            ->all();

                                            
                                                /*// $html.='<td>                                        
                                                //     <label style='font-size:15px;' for="<?=$inst['id']?>"> <?=$inst['nombre']?></label><br>
                                                //     <input style='align-items:center;font-size:18px;' type="checkbox" id ="<?=$inst['id']?>" name="<?=$inst['code']?>" value="<?=$inst['code']?>" checked="true">
                                                // </td>';  */ 
                                                $html.='<td style="font-size:10px;">
                                                    '. $inst['nombre'];                                               
                                                 
                                                    if($modelDerInsExterna)
                                                    {
                                                        $html.='<td>(X)</td>';
                                                    }
                                                    else
                                                    {
                                                        $html.='<td></td>';
                                                    }
                                                $html.='</td>';                                     
                                            
                                        
                                        }//fin foreach 2                                        
                                    $html.='</tr>';                                
                                }//fin foreach 1  
                    $html.='</table>';   
                    $html.='<table  border="1" width="100%" cellspacing="0" cellpadding="5">
                                <tr > 
                                    <td colspan="4"  align="left" style="font-size:10">
                                        <b>Indique Cual: </b> '.$model->otra_institucion_externa.'
                                    </td>
                                 </tr>
                            </table>';     
        return $html;

    }
    private function datos_personales_del_derivado()
    {
        $model = $this->dece_derivacion;
        $estudiante = $model->estudiante->last_name.' '.$model->estudiante->middle_name.' '.$model->estudiante->first_name;
        $html='';
        $html.='
        <table border="1" width="100%" cellspacing="0" cellpadding="5">
                <tr style="background-color:'.$this->colorFondo.'"> 
                    <td colspan="4"  align="center" style="font-size:10">
                        <b>DATOS PERSONALES DEL DERIVADO</b>
                    </td>
                </tr>
                <tr > 
                    <td colspan="1"  align="left" style="font-size:10">
                        <b>Apellidos y nombres completos:</b>
                    </td>
                    <td colspan="3"  align="left" style="font-size:10">
                        '.$estudiante.'
                    </td>
                </tr>
                <tr > 
                    <td colspan="1"  align="left" style="font-size:10">
                        <b>Fecha de Nacimiento:</b>
                    </td>
                    <td colspan="1"  align="left" style="font-size:10">
                        *****************
                    </td>
                    <td colspan="1"  align="left" style="font-size:10">
                        <b>Edad:</b>
                    </td>
                    <td colspan="1"  align="left" style="font-size:10">
                        *****************
                    </td>
                </tr>
                <tr > 
                    <td colspan="1"  align="left" style="font-size:10">
                        <b>Año que cursa:</b>
                    </td>
                    <td colspan="1"  align="left" style="font-size:10">
                        *****************
                    </td>
                    <td colspan="1"  align="left" style="font-size:10">
                        <b>Sexo:</b>
                    </td>
                    <td colspan="1"  align="left" style="font-size:10">
                        *****************
                    </td>
                </tr>
                <tr > 
                    <td colspan="1"  align="left" style="font-size:10">
                        <b>Dirección domiciliaria:</b>
                    </td>
                    <td colspan="1"  align="left" style="font-size:10">
                        *****************
                    </td>
                    <td colspan="1"  align="left" style="font-size:10">
                        <b>Número telefónico:</b>
                    </td>
                    <td colspan="1"  align="left" style="font-size:10">
                        *****************
                    </td>
                </tr>
                <tr > 
                    <td colspan="1"  align="left" style="font-size:10">
                        <b>Nombre del padre:</b>
                    </td>
                    <td colspan="1"  align="left" style="font-size:10">
                        *****************
                    </td>
                    <td colspan="1"  align="left" style="font-size:10">
                        <b>Nombre de la madre:</b>
                    </td>
                    <td colspan="1"  align="left" style="font-size:10">
                        *****************
                    </td>
                </tr>
        </table>';

        return $html;

    }


    private function cuerpo()
    {
        $html = '';
        $html.= $this->cabecera_derivacion(); 
        $html.= $this->institucion_externa();     
        $html.= $this->datos_personales_del_derivado();          
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