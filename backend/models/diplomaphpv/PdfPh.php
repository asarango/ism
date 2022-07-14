<?php
namespace backend\models\diplomaphpv;

use backend\controllers\PlanificacionVerticalDiplomaController;
use backend\models\CurriculoMecBloque;
use backend\models\OpCourseTemplate;
use backend\models\PlanificacionBloquesUnidad;
use backend\models\PlanificacionDesagregacionCabecera;
use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;
use backend\models\PlanificacionVerticalDiploma;
use backend\models\PudPep;
use backend\models\ScholarisMateria;
use backend\models\ScholarisPeriodo;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;
use datetime;
use backend\models\helpers\HelperGeneral;



class PdfPh extends \yii\db\ActiveRecord
{

    private $planCabecera;
    private $cursos;
    
    public function __construct($cabeceraId)
    {             
        $user = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;
        $objHelper = new HelperGeneral();       
        $this->cursos = $objHelper->get_cursos_docente($user,$periodoId);    
        $this->planCabecera = PlanificacionDesagregacionCabecera::findOne($cabeceraId);                  
        $this->generate_pdf();
    }
    

    private function generate_pdf(){
        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 25,
            'margin_bottom' => 0,
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
        $codigoISO = "";
        $version ="";
        $fecha=date('Y-m-d H:i:s'); 
        $fecha=date('Y-m-d'); 
        $html = <<<EOT
        <table width="100%" cellspacing="0" cellpadding="10"> 
            <tr> 
                <td class="border" align="center" width="10%">
                    <img src="imagenes/instituto/logo/logoISM1.png" width="80px">
                </td>
                <td class="border" align="center" width="" >
                        <font size="4">
                                <b>
                                <p>ISM</p>
                                <p>International Scholastic Model</p> 
                                </b>
                        </font>                       
                    </td>
                <td class="border" align="left" width="10%">
                    <table style="font-size:8;">
                        <tr>
                            <td>Código:</td>
                            <td>'.$codigoISO.'</td> 
                        </tr>
                        <tr>
                            <td>Versión:</td>                            
                            <td>'.$version.'</td>
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
        $html = <<<EOD
        <br>
        <br>
        <table width="100%" cellspacing="0" cellpadding="5">         
            <tr> 
                <td colspan="2"  align="left" style="font-size:10">
                    Elaborado Por:_________________________________________________________________________________________
                </td>
                <td colspan="2"  align="left" style="font-size:10">
                    Aprobado Por:__________________________________________________________________________________________
                </td>                
            </tr> 
            <tr> 
                <td align="left" style="font-size:10">Fecha:_________________________________________________________________</td>
                <td align="left" style="font-size:10">Firma:_________________________________________________________________</td>
                <td align="left" style="font-size:10">Fecha:_________________________________________________________________</td>
                <td align="left" style="font-size:10">Firma:_________________________________________________________________</td>
            </tr> 
        </table> 
        EOD;      
        return $html;
    }


    /*** UNIDADES ITERACION  */
    private function unidades_iteracion()
    {
        //$objPlanVerticalDiploma = new PlanificacionVerticalDiploma();
        $objHelper = new HelperGeneral();
        $cursoId = $this->cursos;
        $arrayAsignaturas = $objHelper->query_asignaturas_x_nivel($cursoId[0]['id']); //toma las asignaturas
      
        $html = '' ;     
        
        foreach($arrayAsignaturas as $asignatura)
        {        
            $colorCabeceraFondo = "#BEDBEC";
            $materia = $asignatura['name'];
            $idCabecera = $asignatura['id']; //id de desagregacion cabecera
            $curso = $this->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->name;
            $planUnidadBloque = PlanificacionBloquesUnidad::find()
            ->innerJoin('curriculo_mec_bloque c', 'c.id = planificacion_bloques_unidad.curriculo_bloque_id')
            ->where([
                            'plan_cabecera_id'=>$idCabecera,
                            'c.is_active' => true
            ])
            ->orderBy(['curriculo_bloque_id'=> SORT_ASC])
            ->all();
           
            //cabecera tabla
            $html .= <<<EOK
                <br>
                <table width="100%" cellspacing="0" cellpadding="5">
                <tr >                   
                    <td class="border" style="background-color:$colorCabeceraFondo;font-size:10" >Grupo de Asignaturas, curso y nivel:</td>
                    <td class="border" style="font-size:10">$materia</td>
                    <td class="border" style="background-color:$colorCabeceraFondo;font-size:10">Año del PD</td>
                    <td class="border" style="font-size:10"> $curso</td>                 
                </tr>        
                </table>
                <br>
                <table width="100%" cellspacing="0" cellpadding="10">
                <tr> 
                    <td class="border" style="background-color:$colorCabeceraFondo;font-size:9"><b>NRO</b></td>            
                    <td class="border" style="background-color:$colorCabeceraFondo;font-size:9"><b>TITULO DE LA UNIDAD</b></td>
                    <td class="border" style="background-color:$colorCabeceraFondo;font-size:9"><b>OBJETIVOS DE LA ASIGNATURA</b></td>           
                    <td class="border" style="background-color:$colorCabeceraFondo;font-size:9"><b>CONCEPTO CLAVE</b></td>           
                    <td class="border" style="background-color:$colorCabeceraFondo;font-size:9"><b>CONTENIDOS</b></td>           
                    <td class="border" style="background-color:$colorCabeceraFondo;font-size:9"><b>HABILIDADES IB</b></td>   
                    <td class="border" style="background-color:$colorCabeceraFondo;font-size:9"><b>CONEXIÓN CON TDC</b></td>           
                    <td class="border" style="background-color:$colorCabeceraFondo;font-size:9"><b>EVALUACIÓN PD</b></td>          
                    
                </tr>
            EOK;  

            foreach($planUnidadBloque as $plaUni)
            {                
                $planVerticalDipl = PlanificacionVerticalDiploma::find()->where([
                    'planificacion_bloque_unidad_id'=>$plaUni->id
                ])->asArray()->all(); 

                $bloques = CurriculoMecBloque::findOne($plaUni->curriculo_bloque_id);
                
                
                $html .= '<tr>';  
                $html .= '<td class="border" style="font-size:9">'.$bloques->last_name.'</td>';//NRO
                $html .= '<td class="border" style="font-size:9">'.$plaUni->unit_title.'</td>';//TITULO DE LA UNIDAD
            
                if (count($planVerticalDipl)>0) 
                {                   
                    $html .= '<td class="border" style="font-size:9">'.$planVerticalDipl[0]['objetivo_asignatura'].'</td>';   //OBJ UNIDAD
                    $html .= '<td class="border" style="font-size:9">'.$planVerticalDipl[0]['concepto_clave'].'</td>';        //CONCEP. CLAVE 
                    $html .= '<td class="border" style="font-size:9">'.$planVerticalDipl[0]['contenido'].'</td>';   //CONTENIDO 
                    //$html .= '<td class="border" align=""><font size="3">'.$relacion_tdc.'</font></td>';           //RELACION CON TDC
                    $html .= '<td class="border" style="font-size:9">'.$this->consultaHabilidadesBI($planVerticalDipl[0]['id']).'</td>';  //HABILIDADES DE ENFOQUE DEL APRENDIZAJE 
                    $html .= '<td class="border" style="font-size:9">'.$this->consultaConexionTDC($planVerticalDipl[0]['id']).'</td>';  //CONEXION CON TDC
                    $html .= '<td class="border" style="font-size:9">'.$planVerticalDipl[0]['objetivo_evaluacion'].'</td>';   //OBJ EVALUACION 
                    //$html .= '<td class="border" align=""><font size="3">'.$planVerticalDipl[0]['intrumentos'].'</font></td>';  //INSTRUMENTO EVALUACION 
                } 
                else
                {
                    //INGRESA AQUI CUANDO NO TIENE DATOS A MOSTRAR 
                    $html .= '<td class="border" align=""></td>';   //OBJ UNIDAD        
                    $html .= '<td class="border" align=""></td>';   //CONCEP. CLAVE   
                    $html .= '<td class="border" align=""></td>';   //CONTENIDO      
                    $html .= '<td class="border" align=""></td>';   //HABILIDADES DE ENFOQUE DEL APRENDIZAJE          
                    $html .= '<td class="border" align=""></td>';   //relacion tdc
                    $html .= '<td class="border" align=""></td>';   //OBJ EVALUACION        
                }            
                $html .= '</tr>'; 
            } //fin for 
            $html .= '</table>'; 
        }// fin for asignaturas
        return $html;       
    }
    private function consultaConexionTDC($id_planBloqueUnidad)
    {
        $con = Yii::$app->db; 
        $query = "
        --select p.id,p.es_activo ,p.contenido,d.id,d.es_de_lectura,d.tipo_area ,d.opcion,d.es_activo 
            select p.id,
            case
                when d.tipo_area ='Áreas de conocimiento' then 2 
                when d.tipo_area ='Conceptos' then 5 
                when d.tipo_area ='Conocimiento y actor del conocimiento' then 1 
                when d.tipo_area ='Marcos de conocimiento' then 4
                when d.tipo_area ='Preguntas de conocimiento' then 3
            end as orden,
            d.tipo_area,p.contenido, d.opcion,d.es_de_lectura
            from planificacion_conexion_tdc p
            inner join dip_conexiones_tdc_opciones d on d.id = p.opcion_tdc_id  
            where p.plan_vertical_id = $id_planBloqueUnidad 
            and p.es_activo = true and d.es_activo = true
            order by orden,d.tipo_area;
        ";

       $conexionTDC = $con->createCommand($query)->queryAll();
     

        //extraccion de fila uno
        $arrayFilas = array();
        foreach($conexionTDC as $habi)
        {
                    if(count($arrayFilas)>0)
                    {
                        if(!in_array($habi['tipo_area'],$arrayFilas))
                        {
                            $arrayFilas[]=$habi['tipo_area'];
                        }          
                    }else{
                        $arrayFilas[]=$habi['tipo_area'];
                    }                   
        } 
       
       
       $tablaConexion ='<table border="0" cellspacing="0" cellpadding="2">';
       foreach($arrayFilas as $fila)
       {
            $tablaConexion .='<tr>
                                <td style="font-size:9;"><b>'.strtoupper($fila).'</b></td>
                                <td style="font-size:9"></td>
                            </tr>'; 
            foreach($conexionTDC as $conTdc)
            {
                if($fila==$conTdc['tipo_area'])
                {                       
                    if($conTdc['es_de_lectura'])
                    {
                        $tablaConexion .= '<tr>  
                            <td style="font-size:9"></td>
                            <td style="font-size:9;border-top: thin solid;">'.$conTdc['contenido'].'</td>
                            </tr> '; 
                        
                    }else{
                        $tablaConexion .= '<tr>   
                            <td style="font-size:9"></td>
                            <td style="font-size:9;border-top: thin solid;">'.$conTdc['opcion'].'</td>
                        </tr> '; 
                    }                          
                } 
                        
            }
       }
       
       $tablaConexion .="</table>";       

       return $tablaConexion;
    }

    private function consultaHabilidadesBI($id_planBloqueUnidad)
    {
        $con = Yii::$app->db;    

         $query = "
            select e.nombre as uno,eh.nombre as dos,eo.nombre as tres  
            from enfoques_diploma_habilidad e 
            inner join enfoques_diploma_sub_habilidad eh on eh.habilidad_id  = e.id 
            inner join enfoques_diploma_sb_opcion eo on eo.sub_habilidad_id = eh.id
            inner join planificacion_vertical_diploma_habilidad ph on ph.opcion_habilidad_id = eo.id
            inner join planificacion_vertical_diploma pd on pd.id = ph.plan_vertical_id 
            where pd.id  = $id_planBloqueUnidad
            order by e.nombre;
            ";

        $habilidades = $con->createCommand($query)->queryAll();        

        //extraccion de fila uno
        $arrayFilas = array();
        foreach($habilidades as $habi)
        {
                    if(count($arrayFilas)>0)
                    {
                        if(!in_array($habi['uno'],$arrayFilas))
                        {
                            $arrayFilas[]=$habi['uno'];
                        }          
                    }else{
                        $arrayFilas[]=$habi['uno'];
                    }                   
        } 
        
        //tabla de habilidades
        $tablaHbailidades = '
                <table border="0" cellspacing="0" cellpadding="2">                    
                    <tbody >
                ';       
                foreach($arrayFilas as $fila)
                {    
                    $tablaHbailidades .='<tr>
                                <td style="font-size:9;"><b>'.$fila.'</b></td>
                                <td style="font-size:9"></td>
                                <td style="font-size:9"></td>
                            </tr>';           
                    foreach($habilidades as $habi2)
                    {                        
                        if($fila==$habi2['uno'])
                        {
                            $tablaHbailidades .='<tr >
                            <td style="font-size:9;"></td>
                            <td style="font-size:9;border-top: thin solid;">'.$habi2['dos'].'</td>
                            <td style="font-size:9;border-top: thin solid;">'.$habi2['tres'].'</td>
                            </tr>';                            
                        }                      
                    }       
                    //$tablaHbailidades .= '</td>';                   
                }
                            
        $tablaHbailidades .="</tbody>
                </table> ";
               
        
        return $tablaHbailidades; 
    }
    
    /***FIN UNIDADES ITERACION TITULOS */

    private function cuerpo(){
        $periodoId = Yii::$app->user->identity->periodo_id;
        $periodo = ScholarisPeriodo::findOne($periodoId);
       
        $cursos = \backend\models\OpCourseTemplate::find()->all();        

        $html = $this->estilos();
        $titulo = <<<EOK
            <table width="100%" cellspacing="0" cellpadding="5">
                <tr >
                    <td align="center">PLANIFICACIÓN HORIZONTAL - PD</td>               
                </tr>
            </table>
        EOK;       

        $texto = "La finalidad de la planificación vertical es establecer una secuencia en el aprendizaje 
                que garantice la continuidad y la progresión a lo largo de cada año del programa, e incluso 
                para los futuros estudios de los alumnos. Exploran las conexiones y relaciones entre las 
                asignaturas entre las asignaturas y refuerzan los conocimientos, la comprensión y las 
                habilidades comunes a las distintas disciplinas. (Bachillerato Internacional, 2015, pág. 62)";

        $html .= $titulo; 
             
        //$html .= $this->bloque_materia_iteracion();        
        $html .= $this->unidades_iteracion(); 
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


?>