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



class Pdf extends \yii\db\ActiveRecord{

    private $planCabecera;
    
    public function __construct($cabeceraId)
    {     
        $this->planCabecera = PlanificacionDesagregacionCabecera::findOne($cabeceraId);                      
        $this->generate_pdf();
    }
    /*****  METODOS DE CONSULTA A LA BASE ****/
       
   
    private function select_plan_des_cab(){
        //extraer los cursos del PLAN DES CAB , enviando el id de la materia       
        $modelPdc = PlanificacionDesagregacionCabecera::find()->where([
            'ism_area_materia_id'=>$this->planCabecera->ism_area_materia_id
            ])->all();
               
        return $modelPdc;
    }
    private function select_op_course(){        
        $arrayIdCourse = $this->select_plan_des_cab(); 
        //arreglo para capturar los registro de  op_course_template_id  
        $arryIds=array();
        foreach($arrayIdCourse as $planDC)
        {            
            //array_push($arryIds,$planDC['op_course_template_id']);
            array_push($arryIds,$planDC->ismAreaMateria->mallaArea->periodoMalla->malla->op_course_template_id);
        }          
        $modelOpCourse = OpCourseTemplate::find()->where(['in','id',$arryIds])->all(); 
        return $modelOpCourse;
    } 
    /*****FIN  METODOS DE CONSULTA A LA BASE ****/

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

    /***BLOQUE-MATERIA TITULOS */
    private function bloque_materia_iteracion()
    {
        $modelOpCourse = $this->select_op_course();       
         
        foreach($modelOpCourse as $model)
        {
            $html='';
            $html .= '</pr>'; 
            $html .= '<table width="100%" cellspacing="0" cellpadding="3">'; 
            $html .= '<tr>'; 
            $html .= '<td class="border" align=""><font size="3"><b>DIPLOMA UNO: </b>'.$model->name.'</font></td>';
            $html .= '</tr>';
            $html .= '<tr>'; 
            $html .= '<td class="border" align=""><font size="3"><b>Asignatura: </b>'.$this->planCabecera->ismAreaMateria->materia->nombre.'</font></td>';
            $html .= '</tr>';
            $html .= '</table>'; 
        }
        return $html;
    }
    /*** FIN BLOQUE-MATERIA ITERACION  */

    /*** UNIDADES ITERACION  */
    private function unidades_iteracion()
    {
        $objPlanVerticalDiploma = new PlanificacionVerticalDiploma();
        
        $planUnidadBloque = PlanificacionBloquesUnidad::find()
          ->innerJoin('curriculo_mec_bloque c', 'c.id = planificacion_bloques_unidad.curriculo_bloque_id')
                ->where([
                         'plan_cabecera_id'=>$this->planCabecera->id,
                         'c.is_active' => true
        ])
        ->orderBy(['curriculo_bloque_id'=> SORT_ASC])
        ->all();          
       
        $colorCabeceraFondo = "#BEDBEC";
        //cabecera tabla
        $html = <<<EOK
            <table width="100%" cellspacing="0" cellpadding="10">
            <tr> 
                <td class="border" style="background-color:$colorCabeceraFondo;font-size:9"><b>NRO</b></td>            
                <td class="border" style="background-color:$colorCabeceraFondo;font-size:9"><b>TITULO DE LA UNIDAD</b></td>
                <td class="border" style="background-color:$colorCabeceraFondo;font-size:9"><b>OBJETIVOS DE LA ASIGNATURA</b></td>           
                <td class="border" style="background-color:$colorCabeceraFondo;font-size:9"><b>CONCEPTO CLAVE</b></td>           
                <td class="border" style="background-color:$colorCabeceraFondo;font-size:9"><b>CONTENIDOS</b></td>           
                <td class="border" style="background-color:$colorCabeceraFondo;font-size:9"><b>HABILIDADES IB</b></td>           
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
              
                /***BUSQUEDA ITEM: RELACION TDC */          
                $arrayConsultaTdc=$objPlanVerticalDiploma->consultar_tdc_ckeck_reporte($planVerticalDipl[0]['id']);              
                 // recorremos un bucle para capturar los item re relacion tdc
                $relacion_tdc ='';
                if (count($arrayConsultaTdc)>0) 
                {
                    for ($i = 0; $i < count($arrayConsultaTdc); $i++) 
                    {
                        $relacion_tdc  = $relacion_tdc.'* '.$arrayConsultaTdc[$i]['opcion'].'<br><br>';
                    }
                }
                /***FIN BUSQUEDA ITEM: RELACION TDC */  
                
                /***BUSQUEDA ITEM: HABILIDADES  */          
                $arrayConsultaHab=$objPlanVerticalDiploma->consultar_habilidad_check_reporte($planVerticalDipl[0]['id']);              
                // recorremos un bucle para capturar los item re relacion tdc                
                $habilidades ='';
                if (count($arrayConsultaHab)>0) 
                {
                    for ($j = 0; $j < count($arrayConsultaHab); $j++) 
                    {
                        $habilidades  = $habilidades.'* '.$arrayConsultaHab[$j]['es_exploracion'].'<br><br>';                        
                    }
                }                
                /***FIN BUSQUEDA ITEM: HABILIDADES*/ 
                //echo '<pre>';
                //print_r($habilidades);                
                $html .= '<td class="border" style="font-size:9">'.$planVerticalDipl[0]['objetivo_asignatura'].'</td>';   //OBJ UNIDAD
                $html .= '<td class="border" style="font-size:9">'.$planVerticalDipl[0]['concepto_clave'].'</td>';        //CONCEP. CLAVE 
                $html .= '<td class="border" style="font-size:9">'.$planVerticalDipl[0]['contenido'].'</td>';   //CONTENIDO 
                //$html .= '<td class="border" align=""><font size="3">'.$relacion_tdc.'</font></td>';           //RELACION CON TDC
                $html .= '<td class="border" style="font-size:9">'.$this->consultaHabilidadesBI($planVerticalDipl[0]['id']).'</td>';  //HABILIDADES DE ENFOQUE DEL APRENDIZAJE 
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
                $html .= '<td class="border" align=""></td>';   //OBJ EVALUACION        
            }            
            $html .= '</tr>'; 
        } 
        $html .= '</table>'; 
        return $html;       
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
                    <td align="center">PLANIFICACIÓN VERTICAL - PD</td>               
                </tr>
            </table>
        EOK;       

        $texto = "La finalidad de la planificación vertical es establecer una secuencia en el aprendizaje 
                que garantice la continuidad y la progresión a lo largo de cada año del programa, e incluso 
                para los futuros estudios de los alumnos. Exploran las conexiones y relaciones entre las 
                asignaturas entre las asignaturas y refuerzan los conocimientos, la comprensión y las 
                habilidades comunes a las distintas disciplinas. (Bachillerato Internacional, 2015, pág. 62)";

        $html .= $titulo;
        $html .= $this->generaDatosCabeceras();      
             
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
    //genera los datos de profesor y materia
    private function generaDatosCabeceras()
    {
        $planBloqueUnidad   = PlanificacionBloquesUnidad::find()
        ->where(['plan_cabecera_id'=>$this->planCabecera->id])
        ->one();
        $scholarisPeriodoId = Yii::$app->user->identity->periodo_id;
        $institutoId = Yii::$app->user->identity->instituto_defecto;
        $docentes = $this->get_docentes($planBloqueUnidad,$scholarisPeriodoId);
        // **** datos para extraer numero de horas semana, y numero de semanas *********
        $objHelper = new HelperGeneral();
        $arrayhorasSemana =    $objHelper->getCargaHorariaSemanal($this->planCabecera->id);
        $horasSemana = $arrayhorasSemana[0]['count'];
        $arraySemanas = $objHelper->getCargaSemanasTrabajo($this->planCabecera->id);

        $tablaSemana = '<table>';
        $tablaSemana .= '<tr><td style="font-size:8">B1 - </td><td style="font-size:8">'.$arraySemanas[0].'</td></tr>';
        $tablaSemana .= '<tr><td style="font-size:8">B2 - </td><td style="font-size:8">'.$arraySemanas[1].'</td></tr>';
        $tablaSemana .= '<tr><td style="font-size:8">B3 - </td><td style="font-size:8">'.$arraySemanas[2].'</td></tr>';
        $tablaSemana .= '<tr><td style="font-size:8">B4 - </td><td style="font-size:8">'.$arraySemanas[3].'</td></tr>';
        $tablaSemana .= '</table>';

               

        $tiempo = $this->calcula_horas(
            $this->planCabecera->ism_area_materia_id,
            $this->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->op_course_template_id,
            $scholarisPeriodoId,
            $planBloqueUnidad
        );       
       
        $colorFondo = "#BEDBEC";
        $materia = $this->planCabecera->ismAreaMateria->materia->nombre;
        $curso = $this->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->name;
        $profesores ="";
        //$horasSemana = $tiempo['horas'];
        foreach($docentes as $docente){
            $profesores .= '* '.$docente['docente'].' <br> ';
        } 

        $html = <<<EOT
            <table width="100%" cellspacing="0" cellpadding="5">
            <tr >
                <td class="border" style="background-color:$colorFondo; font-size:10">Profesor(es):</td>
                <td colspan="3" class="border" style="font-size:10" >$profesores</td>
                <td colspan="2" style="background-color:$colorFondo;font-size:10" class="border">Grupo de Asignaturas, curso y nivel:</td>
                <td colspan="2" class="border" style="font-size:10">$materia</td>
                <td class="border" style="background-color:$colorFondo;font-size:10">Año del PD</td>
                <td class="border" style="font-size:10"> $curso</td>                 
            </tr>
            <tr>
                <td class="border" style="background-color:$colorFondo;font-size:10">Carga Horario Semanal:</td>
                <td class="border" style="font-size:10">$horasSemana</td>
                <td class="border" style="background-color:$colorFondo;font-size:10">Nro. Semanas de Trabajo:</td>
                <td class="border" style="font-size:10">  $tablaSemana </td>
                <td class="border" style="background-color:$colorFondo;font-size:10">Total de Semanas de Clases:</td>
                <td class="border" style="font-size:10">40 </td>
                <td class="border" style="background-color:$colorFondo;font-size:10">Evaluación del Aprendizaje e Imprevistos</td>                
                <td class="border" style="font-size:10"></td>
                <td class="border" style="background-color:$colorFondo;font-size:10">Cantidad de Unidades</td>
                <td class="border" style="font-size:10"> 4 </td>
            </tr>
            </table>
            EOT;   
        return $html;
        

    }
    private function calcula_horas($materiaId, $courseTemplateId,$scholarisPeriodoId,$planBloqueUnidad)
    {
        $con = Yii::$app->db;
         
        $query = "select count(h.detalle_id) as hora_semanal ,h.clase_id ,cla.tipo_usu_bloque 
                    from scholaris_horariov2_horario h inner join scholaris_clase cla on cla.id = h.clase_id 
                    where h.clase_id = (select max(clase.id) from op_course_template t 
                                                                    inner join op_course c on c.x_template_id = t.id inner join op_course_paralelo p on p.course_id = c.id 
                                                                    inner join op_section s on s.id = c.section inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = s.period_id 
                                                                    inner join scholaris_clase clase on clase.paralelo_id = p.id 
                                                            where t.id = $courseTemplateId and sop.scholaris_id = $scholarisPeriodoId 
                                                                and clase.ism_area_materia_id = $materiaId
                                                                            and clase.id = cla.id) 
                    group by h.clase_id, cla.tipo_usu_bloque;";     
                                                     
        $resH = $con->createCommand($query)->queryOne();
       
        $horasSemana = $resH['hora_semanal'];
        $uso = $resH['tipo_usu_bloque'];
        $orden = $planBloqueUnidad->curriculoBloque->code;
        
        $queryFechas = "select 	b.bloque_inicia 
                                ,b.bloque_finaliza 
                        from 	scholaris_bloque_actividad b
                                inner join scholaris_periodo p on p.codigo = b.scholaris_periodo_codigo 
                        where 	b.tipo_uso = '$uso'
                                and p.id = $scholarisPeriodoId
                                and b.orden = $orden;";
        $resF = $con->createCommand($queryFechas)->queryOne();        
        
        $fechaInicia = new DateTime($resF['bloque_inicia']);
        $fechaFinal = new DateTime($resF['bloque_finaliza']);

        $diff = $fechaInicia->diff($fechaFinal);

        return array(
            'horas' => ($diff->days) * $horasSemana,
            'fecha_inicio' => $resF['bloque_inicia'],
            'fecha_final' => $resF['bloque_finaliza']
        );

    }
    private function get_docentes($planBloqueUnidad,$scholarisPeriodoId){
        $materiaId = $planBloqueUnidad->planCabecera->ism_area_materia_id;       
        $templateId = $planBloqueUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->op_course_template_id;

        $con = Yii::$app->db;
        
        $query = "select 	concat(f.x_first_name,' ', f.last_name) as docente 
                    from 	scholaris_clase c 
                                    inner join ism_area_materia am on am.id = c.ism_area_materia_id 
                                    inner join ism_malla_area ma on ma.id = am.malla_area_id 
                                    inner join ism_periodo_malla pm on pm.id = ma.periodo_malla_id 
                                    inner join op_course_paralelo par on par.id = c.paralelo_id  
                                    inner join op_course oc on oc.id = par.course_id  
                                    inner join op_faculty f on f.id = c.idprofesor 
                    where 	c.ism_area_materia_id = $materiaId
                                    and pm.scholaris_periodo_id  = $scholarisPeriodoId 
                                    and oc.x_template_id = $templateId
                    group by f.x_first_name, f.last_name;";
        
        $res = $con->createCommand($query)->queryAll();
        
        return $res;
    }

}


?>