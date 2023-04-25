<?php
namespace backend\models\dece;

use backend\models\CurriculoMecBloque;
use backend\models\DeceDeteccion;
use backend\models\DeceIntervencion;
use backend\models\DeceAreasIntervenir;
use backend\models\DeceIntervencionAreaCompromiso;
use backend\models\DeceIntervencionCompromiso;
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



class DeceIntervencionPdf extends \yii\db\ActiveRecord
{
    private $dece_intervencion;
    private $colorFondo ='#D5DBDB';
    
    public function __construct($id_intervencion)
    {     
        $this->dece_intervencion = DeceIntervencion::findOne($id_intervencion);                      
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
        //$html.= $this->firmas();            
        $mpdf->WriteHTML($html); 

        $piePagina=$this->piePagina();
        $mpdf->SetFooter($piePagina);      

        //$mpdf->Output('Planificacion-de-unidad' . "curso" . '.pdf', 'D');
        $mpdf->Output();
        exit;
    }
    /****CABCERA */
    private function cabecera()
    {
        $codigoISO = "ISMR21-07";
        $version ="4.0";
        $fecha=date('Y-m-d H:i:s'); 
        $fecha='09/05/2022';
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
                                <p>PROCESO DE INTERVENCION</p> 
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
        $revision_compromisos =$this->bloque_1_revision_est().'<br>'.$this->bloque_1_revision_repre().'<br>'.$this->bloque_1_revision_docente().'<br>'.$this->bloque_1_revision_dece();
        $html=''; 
        $html.='
        <table border="0" width="100%" cellspacing="0" cellpadding="5"> 
                <tr> 
                        <td colspan="4"  align="center" style="font-size:10">
                            <span style="align:center">
                            <br> <br> <br> <br>
                            ___________________________________
                            <br>
                            FIRMA DEL REPRESENTANTE
                            </span>
                        </td>
                </tr>                             
        </table>        
        <table border="1" width="100%" cellspacing="0" cellpadding="5"> 
                <tr > 
                        <td colspan="4"  align="left" style="font-size:10">
                            <b>Revisión cumplimiento:</b>
                            '.$revision_compromisos.'
                        </td>
                </tr>                             
        </table>';
        return  $html;   
    }    
    private function cabecera_intervencion()
    {
        $model = $this->dece_intervencion;
        
        $estudiante = $model->estudiante->last_name.' '.$model->estudiante->middle_name.' '.$model->estudiante->first_name;
        $objScript = new Scripts();
        $arrayCurso = $objScript->mostrar_curso_estudiante($model->id_estudiante);
        $curso = '';
        if($arrayCurso[0]) {$curso = $arrayCurso[0]['curso'];}    

        $html ='';
        $html.='
        <table border="1" width="100%" cellspacing="0" cellpadding="5">  
                <tr style="background-color:'.$this->colorFondo.'"> 
                        <td colspan="6"  align="center" style="font-size:12">
                            <b>ISM
                            <br>
                            International Scholastic Model
                            </b>
                        </td>
                </tr>
                <tr > 
                        <td colspan="2"  align="left" style="font-size:10">
                            <b>1.	Apellidos y nombres completos del estudiante: </b>
                        </td>
                        <td colspan="4"  align="left" style="font-size:10">
                            '.$estudiante.'
                        </td>
                </tr>
                <tr > 
                        <td colspan="2"  align="left" style="font-size:10">
                            <b>Curso y paralelo:</b>
                        </td>
                        <td colspan="4"  align="left" style="font-size:10">
                            '.$curso.'
                        </td>
                </tr>
                <tr > 
                        <td colspan="2"  align="left" style="font-size:10">
                            <b>Fecha de inicio de intervención:</b>
                        </td>
                        <td colspan="4"  align="left" style="font-size:10">
                            '.substr($model->fecha_intervencion,0,10).'
                        </td>
                </tr>
                <tr > 
                        <td colspan="2"  align="left" style="font-size:10">
                            <b>2.	Razón:</b>
                        </td>
                        <td colspan="4"  align="left" style="font-size:10">
                            '.$model->razon.'
                        </td>
                </tr> 
        </table>        
        ';      
        return  $html;
    }
    private function area_a_intervenir()
    {
        $model = $this->dece_intervencion;
        //institucion externa derivacion
        $arrayAreaIntervenir = DeceAreasIntervenir::find()->asArray()->all();
        $arrayAreaIntervenirUpdate = $this->buscaAreaIntervenir($model->id);
        $numDivisionesAreaIntervenir = count($arrayAreaIntervenir) / 3;
        $numDivisionesAreaIntervenir = intval($numDivisionesAreaIntervenir) + 1;
        $arrayDividido = array_chunk($arrayAreaIntervenirUpdate, $numDivisionesAreaIntervenir);
        
        $html='';
        $html.='<br>
                <table border="1" width="100%" cellspacing="0" cellpadding="5">  
                    <tr> 
                            <td colspan="6"  align="left" style="font-size:12">
                                3.	ÁREAS A INTERVENIR
                            </td>
                    </tr>
                </table>                   
                    <table border="1" width="100%" cellspacing="0" cellpadding="5">  ';                                         
                           
                            foreach ($arrayDividido as $array) 
                            {
                                // echo '<pre>';
                                // print_r($arrayDividido);
                                // die();
                           
                                $html.='<tr>';                                   
                                    foreach ($array as $inst) 
                                    {                                       
                                        if ($inst['seleccionado'] == 'si') 
                                        {                                   
                                            $html.='<td>
                                                '.$inst['nombre'].'
                                            </td>';
                                            $html.='<td> (X) </td>';
                                       
                                        } else {
                                       
                                            $html.='<td>
                                                '.$inst['nombre'].'
                                            </td>';   
                                            $html.='<td> </td>';                               
                                        }
                                    } //fin foreach 2                                    
                                $html.='</tr>';                        
                            } //fin foreach 1 
                    $html.='</table>';
                    $html.='<table border="1" width="100%" cellspacing="0" cellpadding="5">  
                                <tr> 
                                        <td colspan="6"  align="left" style="font-size:12">
                                            <b>OTRA/ESPECIFIQUE: </b>'.$model->otra_area.'
                                        </td>
                                </tr>
                            </table>';
                    // echo $html;
                    // die();
                return $html;

    }
    private function lineamiento_y_accion()
    {
        $model = $this->dece_intervencion;
        
        $estudiante = $model->estudiante->last_name.' '.$model->estudiante->middle_name.' '.$model->estudiante->first_name;
        $html ='';
        $html.='<br>
                <table border="1" width="100%" cellspacing="0" cellpadding="5">  
                    <tr> 
                            <td colspan="6"  align="left" style="font-size:12">
                                <b>4.	LINEAMIENTOS DEL PROCESO DE INTERVENCIÓN</b>
                            </td>
                    </tr>
                    <tr>                    
                            <td colspan="6"  align="left" style="font-size:12">
                                    <b>4.1	OBJETIVO GENERAL:</b> 
                                    <br>                           
                                    '.$model->objetivo_general.' 
                            </td>
                    </tr>
                    <tr> 
                            <td colspan="6"  align="left" style="font-size:12">
                                <b>5.	ACCIONES / RESPONSABLES</b>
                                <br>
                            
                                    '.$model->acciones_responsables.' 
                            </td>
                    </tr>';
        $html.='</table>';
        return $html;
    }
    private function bloques()
    {
        $model = $this->dece_intervencion;
       
        $modelBloques = CurriculoMecBloque::find()
        ->where(['is_active'=>true])
        ->all();

       
        
        $html ='';
        //realizamos iteracion de los bloques que estan activos para el año escolar
        foreach($modelBloques as $bloque)
        {
            $fecha_max_cumplimiento = '';
            $modelIntervencion = DeceIntervencionCompromiso::find()
            ->where(['id_dece_intervencion'=>$model->id])
            ->andWhere(['bloque'=>$bloque->shot_name])
            ->orderBy(['id'=>SORT_ASC])
            ->one(); 

              if($modelIntervencion)
            {
                $fecha_max_cumplimiento = $modelIntervencion->fecha_max_cumplimiento;
            }
            //echo '<pre>';
            //print_r($modelIntervencion);
            //die();

        $html.='<br>
                <table border="" width="100%" cellspacing="0" cellpadding="5">  
                    <tr> 
                        <td  align="center" style="font-size:10">
                            <b>'.strtoupper($bloque->last_name).'</b>
                            <br>
                            <b>Compromisos de las partes involucradas</b>
                        </td>
                    </tr>
                </table>
                <table  width="100%" cellspacing="0" cellpadding="5">  
                    <tr> 
                            <td class="border" align="left" style="font-size:12" width="200px">
                                <b>ESTUDIANTE</b>
                            </td>
                            <td class="border" align="left" style="font-size:12" width="200px">
                                <b>REPRESENTANTE</b>
                            </td>
                            <td class="border" align="left" style="font-size:12" width="200px">
                                <b>DOCENTES</b>
                            </td>
                            <td class="border" align="left" style="font-size:12" width="200px">
                                <b>DECE</b>
                            </td>
                    </tr>';                
                    $html.='<tr> 
                                    <td class="border" align="left" style="font-size:12" width="200px">
                                        '.$this->bloque_1_compro_est($bloque->shot_name).'
                                    </td>
                                    <td class="border" align="left" style="font-size:12" width="200px">
                                        '.$this->bloque_1_compro_repre($bloque->shot_name).'
                                    </td>
                                    <td class="border" align="left" style="font-size:12" width="200px">
                                        '.$this->bloque_1_compro_docente($bloque->shot_name).'
                                    </td>
                                    <td class="border" align="left" style="font-size:12" width="200px">
                                        '.$this->bloque_1_compro_dece($bloque->shot_name).'
                                    </td>
                            </tr>';                            

        $html.='</table> ';
        $html.='<br>
                <table border="0"   width="100%" cellspacing="0" cellpadding="5">  
                    <tr >
                        <td align="center" style="font-size:12">
                            Fecha máxima de cumplimiento: '.substr($fecha_max_cumplimiento,0,10).'
                        </td>
                    </tr>                    
                </table>';
        $revision_compromisos =$this->bloque_1_revision_est($bloque->shot_name).'<br>'.
                $this->bloque_1_revision_repre($bloque->shot_name).'<br>'.$this->bloque_1_revision_docente($bloque->shot_name).
                '<br>'.$this->bloque_1_revision_dece($bloque->shot_name);
                
                $html.='
                <table border="0" width="100%" cellspacing="0" cellpadding="5"> 
                        <tr> 
                                <td colspan="4"  align="center" style="font-size:10">
                                    <span style="align:center">
                                    <br> <br> <br> <br>
                                    ___________________________________
                                    <br>
                                    FIRMA DEL REPRESENTANTE
                                    </span>
                                </td>
                        </tr>                             
                </table>        
                <table border="1" width="100%" cellspacing="0" cellpadding="5"> 
                        <tr > 
                                <td colspan="4"  align="left" style="font-size:10">
                                    <b>Revisión cumplimiento:</b>
                                    '.$revision_compromisos.'
                                </td>
                        </tr>                             
                </table>';
        }
        
        return $html;
    }
    //COMPROMISPOS    ************************************************************************************************
    private function bloque_1_compro_est($bloque)
    {
        $model = $this->dece_intervencion;       
        $modelIntervencion = DeceIntervencionCompromiso::find()
        ->where(['id_dece_intervencion'=>$model->id])
        ->andWhere(['bloque'=>$bloque])
        ->orderBy(['id'=>SORT_ASC])
        ->all();  

        $cont=1;
        $html ='';
        $html.='
                <table  width="100%" cellspacing="0" cellpadding="5">  ';
                foreach($modelIntervencion as $modelInter)
                {
                     if($modelInter->comp_estudiante)
                    {
                        $html.='<tr> 
                                    <td >
                                        '.$cont.'.-'.$modelInter->comp_estudiante.'
                                    </td>
                            </tr>';
                        $cont=$cont+1;
                       
                    }                    
                }
        $html.='</table> ';        
        return $html;
    }
    private function bloque_1_compro_repre($bloque)
    {
        $model = $this->dece_intervencion;       
        $modelIntervencion = DeceIntervencionCompromiso::find()        
        ->where(['id_dece_intervencion'=>$model->id])
        ->andWhere(['bloque'=>$bloque])
        ->orderBy(['id'=>SORT_ASC])
        ->all(); 

        $cont=1;
        $html ='';
        $html.='
                <table  width="100%" cellspacing="0" cellpadding="5"> ';
                    
                foreach($modelIntervencion as $modelInter)
                {
                    if($modelInter->comp_representante)
                    {
                        $html.='<tr> 
                                    <td align="left" style="font-size:12">
                                    '.$cont.'.-'.$modelInter->comp_representante.'
                                    </td>
                            </tr>';
                            $cont=$cont+1;
                    }
                    
                }
        $html.='</table> ';        
        return $html;
    }
    private function bloque_1_compro_docente($bloque)
    {
        $model = $this->dece_intervencion;       
        $modelIntervencion = DeceIntervencionCompromiso::find()
        ->where(['id_dece_intervencion'=>$model->id])
        ->andWhere(['bloque'=>$bloque])
        ->orderBy(['id'=>SORT_ASC])
        ->all();     

        $cont=1;
        $html ='';
        $html.='
                <table width="100%" cellspacing="0" cellpadding="5"> ';
                    
                foreach($modelIntervencion as $modelInter)
                {
                    if($modelInter->comp_docente)
                    {
                        $html.='<tr> 
                                    <td align="left" style="font-size:12">
                                    '.$cont.'.-'.$modelInter->comp_docente.'
                                    </td>
                            </tr>';
                            $cont=$cont+1;
                    }
                    
                }
        $html.='</table> ';        
        return $html;
    }
    private function bloque_1_compro_dece($bloque)
    {
        $model = $this->dece_intervencion;       
        $modelIntervencion = DeceIntervencionCompromiso::find()
        ->where(['id_dece_intervencion'=>$model->id])
        ->andWhere(['bloque'=>$bloque])
        ->orderBy(['id'=>SORT_ASC])
        ->all();   
        
        $cont=1;
        $html ='';
        $html.='
                <table  width="100%" cellspacing="0" cellpadding="5"> ';
                    
                foreach($modelIntervencion as $modelInter)
                {
                    if($modelInter->comp_dece)
                    {
                        $html.='<tr> 
                                    <td align="left" style="font-size:12">
                                    '.$cont.'.-'.$modelInter->comp_dece.'
                                    </td>
                            </tr>';
                            $cont=$cont+1;
                    }
                    
                }
        $html.='</table> ';        
        return $html;
    }
    //REVISIONES   ********************************************************************************************************************
    private function bloque_1_revision_est($bloque)
    {
        $model = $this->dece_intervencion;       
        $modelIntervencion = DeceIntervencionCompromiso::find()
        ->where(['id_dece_intervencion'=>$model->id])
        ->andWhere(['bloque'=>$bloque])
        ->orderBy(['id'=>SORT_ASC])
        ->all();   
        
        $cont=1;
        $html ='';
        $html.='
                <table border="0" width="100%" cellspacing="0" cellpadding="5" style="font-size:10"> 
                <tr><td><b>Estudiante</b></td></tr>';
                    
                foreach($modelIntervencion as $modelInter)
                {
                    if($modelInter->revision_compromiso)
                    {
                        $html.='<tr> 
                                    <td align="left" style="font-size:10">
                                    '.$cont.'.-'.$modelInter->revision_compromiso.'
                                    </td>
                            </tr>';
                            $cont=$cont+1;
                    }
                    
                }
        $html.='</table> ';        
        return $html;
    }
    private function bloque_1_revision_repre($bloque)
    {
        $model = $this->dece_intervencion;       
        $modelIntervencion = DeceIntervencionCompromiso::find()
        ->where(['id_dece_intervencion'=>$model->id])
        ->andWhere(['bloque'=>$bloque])
        ->orderBy(['id'=>SORT_ASC])
        ->all();   
        
        $cont=1;
        $html ='';
        $html.='
                <table border="0" width="100%" cellspacing="0" cellpadding="5" style="font-size:10"> 
                <tr><td><b>Representante</b></td></tr>';
                    
                foreach($modelIntervencion as $modelInter)
                {
                    if($modelInter->revision_comp_representante)
                    {
                        $html.='<tr> 
                                    <td align="left" style="font-size:10">
                                    '.$cont.'.-'.$modelInter->revision_comp_representante.'
                                    </td>
                            </tr>';
                            $cont=$cont+1;
                    }
                    
                }
        $html.='</table> ';        
        return $html;
    }
    private function bloque_1_revision_docente($bloque)
    {
        $model = $this->dece_intervencion;       
        $modelIntervencion = DeceIntervencionCompromiso::find()
        ->where(['id_dece_intervencion'=>$model->id])
        ->andWhere(['bloque'=>$bloque])
        ->orderBy(['id'=>SORT_ASC])
        ->all();   
        
        $cont=1;
        $html ='';
        $html.='
                <table border="0" width="100%" cellspacing="0" cellpadding="5" style="font-size:10"> 
                <tr><td><b>Docente</b></td></tr>';
                
                    
                foreach($modelIntervencion as $modelInter)
                {
                    if($modelInter->revision_comp_docente)
                    {
                        $html.='<tr> 
                                    <td align="left" style="font-size:10">
                                    '.$cont.'.-'.$modelInter->revision_comp_docente.'
                                    </td>
                            </tr>';
                            $cont=$cont+1;
                    }
                    
                }
        $html.='</table> ';        
        return $html;
    }
    private function bloque_1_revision_dece($bloque)
    {
        $model = $this->dece_intervencion;       
        $modelIntervencion = DeceIntervencionCompromiso::find()
        ->where(['id_dece_intervencion'=>$model->id])
        ->andWhere(['bloque'=>$bloque])
        ->orderBy(['id'=>SORT_ASC])
        ->all();   
        
        $cont=1;
        $html ='';
        $html.='
                <table border="0" width="100%" cellspacing="0" cellpadding="5" style="font-size:10"> 
                <tr><td><b>Dece</b></td></tr>';
                    
                foreach($modelIntervencion as $modelInter)
                {
                    if($modelInter->revision_comp_dece)
                    {
                        $html.='<tr> 
                                    <td align="left" style="font-size:10">
                                    '.$cont.'.-'.$modelInter->revision_comp_dece.'
                                    </td>
                            </tr>';
                            $cont=$cont+1;
                    }
                    
                }
        $html.='</table> ';        
        return $html;
    }


    private function cuerpo()
    {
        $html = '';
        $html.= $this->estilos();
        $html.= $this->cabecera_intervencion();   
        $html.= $this->area_a_intervenir();   
        $html.= $this->lineamiento_y_accion();  
        $html.= $this->bloques();
        return $html;
    }
    //busca la institucion externa por el ID, de la Area Intervencion
    public function buscaAreaIntervenir($idIntervencion)
    {
       $con =yii::$app->db;
        $query="select i.id,i.nombre,i.code,'si' as Seleccionado 
        from dece_intervencion d1 , dece_intervencion_area_compromiso  d2,
        dece_areas_intervenir  i
        where d1.id = d2.id_dece_intervencion  
        and d2.id_dece_areas_intervenir  = i.id 
        and d1.id = '$idIntervencion'
        union all
         select dd.id,dd.nombre,dd.code,'no' as Seleccionado
         from dece_areas_intervenir dd 
         where id not in
         ( select id_dece_areas_intervenir from dece_intervencion_area_compromiso dr 
         where id_dece_intervencion = '$idIntervencion') order by id;";

       $resp = $con->createCommand($query)->queryAll();
     
       return $resp;
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