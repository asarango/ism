<?php
namespace backend\models\puddip;
use Yii;
use backend\controllers\PlanificacionVerticalDiplomaController;
use backend\controllers\PudDipController;
use backend\models\CurriculoMecBloque;
use backend\models\OpCourseTemplate;
use backend\models\PudDipEvaluaciones;
use backend\models\PlanificacionBloquesUnidad;
use backend\models\PlanificacionDesagregacionCabecera;
use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;
use backend\models\PlanificacionVerticalDiploma;
use backend\models\PlanificacionBloquesUnidadSubtitulo2;
use backend\models\PlanificacionVerticalDiplomaRelacionTdc;
use backend\models\PlanificacionBloquesUnidadSubtitulo;
use backend\models\PudPep;
use backend\models\ScholarisMateria;
use backend\models\ScholarisPeriodo;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;
use DateTime;
use backend\models\helpers\HelperGeneral;
use backend\models\helpers\Scripts;

class Pdf extends \yii\db\ActiveRecord{

    private $planVertDipl;
    private $IdCabecera;

    public function __construct($idPlanUniBloque){        
        $this->planVertDipl = PlanificacionVerticalDiploma::find()->where([
            'planificacion_bloque_unidad_id' => $idPlanUniBloque
        ])->one(); 
        
        $this->IdCabecera = $this->planVertDipl->planificacionBloqueUnidad->plan_cabecera_id; 
        $this->generate_pdf();
    }
    /*****  METODOS DE CONSULTA A LA BASE ****************************************************************************************************/       
    private function select_scholaris_materia(){
        //extrae el id de la materia para el reporte  
       $modelScholaris = \backend\models\IsmMateria::findOne($this->planVertDipl->planificacionBloqueUnidad->planCabecera->ismAreaMateria->materia_id);        
       return $modelScholaris;       
    }
    private function select_plan_des_cab(){
        //extraer los cursos den PLAN DES CAB , enviando el id de la materia       
        $modelPdc = PlanificacionDesagregacionCabecera::find()->where([
            'scholaris_materia_id'=>$this->planCabecera->scholaris_materia_id
            ])->asArray()->all();
               
        return $modelPdc;
    }
    private function select_op_course(){        
        $arrayIdCourse = $this->select_plan_des_cab(); 
        //arreglo para capturar los registro de  op_course_template_id  
        $arryIds=array();
        foreach($arrayIdCourse as $planDC)
        {            
            array_push($arryIds,$planDC['op_course_template_id']);
        }          
        $modelOpCourse = OpCourseTemplate::find()->where(['in','id',$arryIds])->all(); 
        return $modelOpCourse;
    } 
    /*****FIN  METODOS DE CONSULTA A LA BASE ***************************************************************************************************/

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
        $cabecera = $this->versionIso();        
        $mpdf->SetHtmlHeader($cabecera);
        $mpdf->showImageErrors = true; 
        
        $html = $this->cuerpo();  
        $html .= $this->firmas();       
        $mpdf->WriteHTML($html); 
        
        $piePagina=$this->pie_pagina();
        $mpdf->SetFooter($piePagina);           

        //$mpdf->Output('Planificacion-de-unidad' . "curso" . '.pdf', 'D');
        $mpdf->Output();
        exit;
    }
    /****CABECERA */
    private function versionIso()
    {

        
        $codigoISO='ISOMR20-10';
        $version='2.0';
        $fecha=date('Y-m-d H:i:s'); 

        $htmlTabla = <<<EOF
            <b>ISM<br>
            INTERNATIONAL SCHOLASTIC MODEL
            <br>
            PLANIFICADOR DE UNIDADES PROGRAMA DEL DIPLOMA
        </b> 
        EOF;
        
        $html = ''; 
        $html .= '<table width="100%" cellspacing="0" cellpadding="10">'; 
        $html .= '<tr>'; 
        $html .= '<td class="border" align="center" width="20%"><img src="imagenes/instituto/logo/logoISM1.png" width="150px"></td>';
        $html .= '<td class="border" align="center" width="100%">'.$htmlTabla.'</td>';
        $html .= '<td class="border" align="left" width="20%">
                        <table style="font-size:12;">
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
                                <td>'.$fecha.'</td>
                            </tr> 
                            <tr>
                                <td>Pág: :</td>
                                <td>{PAGENO}/{nbpg}</td>
                            </tr> 
                        </table>
                  </td>';
        $html .= '</tr>'; 
        $html .= '</table>';         
        return $html;
    }

    //pie de pagina
    private function pie_pagina()
    {
        $userLog = Yii::$app->user->identity->usuario;
        $fechaHoy = date('Y-m-d H:i:s'); 
        $objPlanVertDip = $this->planVertDipl;
        
        $html = '';
        /*$html.='<table style="font-size:10" width="100%" cellspacing="0" cellpadding="5">';
                $html.='<tr>';
                    $html.='<td class="border" align="center"><b>ELABORADO POR:</b></td>';
                    $html.='<td class="border" align="center"><b>REVISADO POR:</b></td>';
                    $html.='<td class="border" align="center"><b>APROBADO POR:</b></td>';                    
                $html.='</tr>';
                $html.='<tr>';
                    $html.='<td class="border"><b>DOCENTE: </b>'.$userLog .'</td>';
                    $html.='<td class="border"><b>JEFE DE ÁREA:</b></td>';
                    $html.='<td class="border"><b>COORDINADOR: </b></td>';
                $html.='</tr>';
                $html.='<tr>';                    
                    $html.='<td class="border">FIRMA: </td>';
                    $html.='<td class="border">FIRMA: </td>';
                    $html.='<td class="border">FIRMA: </td>';
                $html.='</tr>';               
                $html.='<tr>';                    
                    $html.='<td class="border">Fecha: '.$fechaHoy.'</td>';
                    $html.='<td class="border"> </td>';
                    $html.='<td class="border">Fecha: </td>';
                $html.='</tr>';

        $html.='</table>';*/
        return $html;

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
    
    /***FIN UNIDADES ITERACION TITULOS */

    private function cuerpo(){        

        $html='';
        $html .= $this->estilos();
        $html .= $this->datos_materia_profesor();
        $html .= $this->descripcion_y_evaluacion_reporte();
        $html .= $this->indagacion_reporte();
        $html .= $this->contenido_habilidades_reporte();  
        $html .= $this->recursos_reporte();
        $html .= $this->reflexion_reporte();
        return $html;
    }    

    //REPORTE : datos materia profesor
    private function datos_materia_profesor()
    {
        $periodoId = Yii::$app->user->identity->periodo_id;  
        $colorCabeceraFondo = "#BEDBEC";  
        $curso = $this->planVertDipl->planificacionBloqueUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->name;  
        $bloque = $this->planVertDipl->planificacionBloqueUnidad->curriculoBloque->last_name;
      
        $periodo = ScholarisPeriodo::findOne($periodoId);
        $fechaHoy = date('Y-m-d H:i:s');
        $objHelper = new HelperGeneral();
                                                        //hay que enviar, el id de la cabecera desagregacion
        $horaSemana = $objHelper->getCargaHorariaSemanal($this->IdCabecera);
       
        $modelScholarisM = $this->select_scholaris_materia();      

        $cursos = \backend\models\OpCourseTemplate::find()->all(); 
        
        $planUnidadId = $this->planVertDipl->planificacion_bloque_unidad_id;        
        $planBloqueUnidad   = PlanificacionBloquesUnidad::findOne($planUnidadId); 

        $tiempo = $this->calcula_horas($planBloqueUnidad->planCabecera->ism_area_materia_id, 
                                      $planBloqueUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->op_course_template_id,$periodoId,$planBloqueUnidad); 

        $docentes = $this->get_docentes( $planBloqueUnidad);        
        

        $profesores ='';
        foreach($docentes as $docente){
            $profesores .= '»'.$docente['docente'].' <br> ';
        }        

        $html= "";
                $html.='<table style="font-size:10;" width="100%" cellspacing="0" cellpadding="3">';
                $html.='<tr>';
                    $html.='<td class="border" colspan="3" style="background-color:'.$colorCabeceraFondo.'"><b>PROFESOR(ES):</b></td>';
                    $html.='<td class="border" align="">'.$profesores.'</td>';
                    $html.='<td class="border" colspan="3" style="background-color:'.$colorCabeceraFondo.'"><b>GRUPO DE ASIGNATURA Y CURSO:</b></td>';
                    $html.='<td class="border" align="" >'.$modelScholarisM->nombre.'</td>';
                    $html.='<td class="border" style="background-color:'.$colorCabeceraFondo.'"><b>AÑO DEL PD</b></td>';
                    $html.='<td class="border" align="" >'.$curso.'</td>';
                $html.='</tr>';
                $html.='<tr>';
                    $html.='<td class="border" style="background-color:'.$colorCabeceraFondo.'"><b>NRO. UNIDAD:</b></td>';
                    $html.='<td class="border" align="">'.$bloque.'</td>';
                    $html.='<td class="border" style="background-color:'.$colorCabeceraFondo.'"><b>TÍTULO DE LA UNIDAD:</b></td>';
                    $html.='<td class="border" align="">'.$planBloqueUnidad->unit_title.'</td>';
                    $html.='<td class="border" style="background-color:'.$colorCabeceraFondo.'"><b>CANTIDAD DE SEMANAS:</b></td>';
                    $html.='<td class="border" align="">40</td>';
                    $html.='<td class="border" style="background-color:'.$colorCabeceraFondo.'"><b>FECHAS DE INICIO:</b></td>';
                    $html.='<td class="border" align="">'.$tiempo['fecha_inicio'].'</td>';
                    $html.='<td class="border" style="background-color:'.$colorCabeceraFondo.'"><b>FECHAS DE FIN:</b></td>';
                    $html.='<td class="border" align="">'.$tiempo['fecha_final'].'</td>';
                $html.='</tr>';           
            $html.='</table>';
            $html.='<br>';
            return $html;

    }
    private function get_docentes($planBloqueUnidad)
    {

        //$planUnidadId = $this->planVertDipl->planificacionBloqueUnidad->planCabecera->scholaris_materia_id;
        
        $scholarisPeriodoId = Yii::$app->user->identity->periodo_id;
        $institutoId = Yii::$app->user->identity->instituto_defecto;  
        
        //$tiempo = $this->calcula_horas($planBloqueUnidad->planCabecera->scholaris_materia_id, 
        //                              $planBloqueUnidad->planCabecera->op_course_template_id,$scholarisPeriodoId,$planBloqueUnidad);  

        $materiaId = $planBloqueUnidad->planCabecera->ism_area_materia_id;       
        $templateId = $planBloqueUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->op_course_template_id;

        $con = Yii::$app->db;
        $query = "select 	concat(f.x_first_name,' ', f.last_name) as docente
        from 	scholaris_clase c 
                 inner join scholaris_periodo p on p.codigo = c.periodo_scholaris 
                 inner join op_course oc on oc.id = c.idcurso 
                inner join op_faculty f on f.id = c.idprofesor 
         where 	c.idmateria  = $materiaId
                 and p.id = $scholarisPeriodoId
                 and oc.x_template_id = $templateId
         group by f.x_first_name, f.last_name;";

        $res = $con->createCommand($query)->queryAll();        
        return $res;
    }
    private function calcula_horas($materiaId, $courseTemplateId,$scholarisPeriodoId,$planBloqueUnidad){
        $con = Yii::$app->db;
         
        $query = "select 	count(h.detalle_id) as hora_semanal
                    ,h.clase_id 
                    ,cla.tipo_usu_bloque
        from	scholaris_horariov2_horario h		
                inner join scholaris_clase cla on cla.id = h.clase_id
        where 	h.clase_id = (select max(clase.id) from op_course_template t 
						inner join op_course c on c.x_template_id = t.id inner join op_course_paralelo p on p.course_id = c.id 
						inner join op_section s on s.id = c.section inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = s.period_id 
						inner join scholaris_clase clase on clase.paralelo_id = p.id 
					where t.id = $courseTemplateId and sop.scholaris_id = $scholarisPeriodoId and clase.ism_area_materia_id = $materiaId
							and clase.id = cla.id) group by h.clase_id, cla.tipo_usu_bloque";         
                                             
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
    //REPORTE 2.1.- y 3.1.-
    private function descripcion_y_evaluacion_reporte()
    {        
        $objPlanVertDip = $this->planVertDipl;
        $colorCabeceraFondo = "#BEDBEC";
        
        $html ='';
        $html.='<table style="font-size:10;" width="100%" cellspacing="0" cellpadding="5">';
                    $html.='<tr>';
                        $html.='<td class="border" style="background-color:'.$colorCabeceraFondo.';"><b>DESCRIPCION Y TEXTO DE LA UNIDAD</b></td>';
                        $html.='<td class="border" style="background-color:'.$colorCabeceraFondo.';"><b>EVALUACION DEL PD PARA LA UNIDAD</b></td>';
                    $html.='</tr>';
                   $html.='<tr>';
                        $html.='<td class="border" align="">';
                           $html.="$objPlanVertDip->descripcion_texto_unidad";
                        $html.='</td>';
                        $html.='<td class="border" align="">';
                        $html.="$objPlanVertDip->objetivo_asignatura";
                        $html.='</td>';
                    $html.='</tr>';  
            $html.='</table>';
        return $html;
    }
    //REPORTE 4.1.- Objetivos de Tranferencia
    private function indagacion_reporte()
    {
        $objPlanVertDip = $this->planVertDipl;
        $colorCabeceraFondo = "#BEDBEC";
                    
        //bucle para capturar el contenido, habilidares
        $arrayContenido=$this->select_contenidos($objPlanVertDip->planificacion_bloque_unidad_id);
        $contenidoImp='';  
        if(count($arrayContenido)>0){
            foreach($arrayContenido as $arraySubContenido){                  
                $contenidoImp .= ''.$arraySubContenido['subtitulo'].'';   
                $contenidoImp .= '<ul>';                
                foreach($arraySubContenido['subtitulos'] as $contenido){
                    $contenidoImp .= '<li>♠ '.$contenido['contenido'].'</li>';                    
                }
                $contenidoImp .= '</ul>';
            }
        }

        
        $html ='<br>';
        $html.='<b>INDAGACION:</b>Establecimiento del propósito de la unidad';
        $html.='<table style="font-size:10;" width="100%" cellspacing="0" cellpadding="5">';
                    $html.='<tr>';
                        $html.='<td class="border" colspan = "3" style="background-color:'.$colorCabeceraFondo.';"><b>OBJETIVOS DE TRANSFERENCIA</b></td>';                       
                    $html.='</tr>';
                    $html.='<tr>';
                        $html.='<td class="border" colspan = "3">';
                           $html.="$objPlanVertDip->objetivo_asignatura";
                        $html.='</td>';
                    $html.='</tr>'; 
                    $html.='<tr>
                            <td class="border" colspan = "3" style="background-color:'.$colorCabeceraFondo.';"><b>CONOCIMIENTOS ESENCIALES:</b></td>
                            </tr>'; 
                    $html.='<tr>
                            <td class="border">CONTENIDO:<br>'.$contenidoImp.'</td>
                            <td class="border">HABILIDADES:<br>'.$objPlanVertDip->habilidades.'</td>
                            <td class="border">CONCEPTOS CLAVE:<br>'.$objPlanVertDip->concepto_clave.'</td>
                            </tr>'; 
        $html.='</table>';
        return $html;
    }

    //REPORTE 5.1.-Contenido Habilidades y conceptos: conocimientos esenciales
    //REPORTE 5.2.- Proceso de Aprendizaje
    private function contenido_habilidades_reporte()
    {
        $colorCabeceraFondo = "#BEDBEC";
        $objPlanVertDip = $this->planVertDipl;             
        //bucle para capturar el contenido, habilidares
        $arrayContenido=$this->select_contenidos($objPlanVertDip->planificacion_bloque_unidad_id);
        $contenidoImp='';  
        if(count($arrayContenido)>0){
            foreach($arrayContenido as $arraySubContenido){
                  
                $contenidoImp .= '<u><b>'.$arraySubContenido['subtitulo'].'</b></u>';   
                $contenidoImp .= '<ul>';                
                foreach($arraySubContenido['subtitulos'] as $contenido){
                    $contenidoImp .= '<li>♠ '.$contenido['contenido'].'</li>';                    
                }
                $contenidoImp .= '</ul>';
            }
        }
        $procesoAprendizaje = \backend\models\PudDipProcesoAprendizaje::find()
        ->where(['plan_unidad_id' => $objPlanVertDip->planificacion_bloque_unidad_id,'es_activo'=>true])
        ->all();
        
        $enfoqueEvaluacion = PudDipEvaluaciones::find()
        ->where(['plan_unidad_id'=>$objPlanVertDip->planificacion_bloque_unidad_id])
        ->one(); 

    //     echo '<pre>';
    //    print_r($enfoqueEvaluacion);
    //    die();


        $objScrip = new Scripts();                 
        $textProcesoApre = $objScrip->get_enfoques($objPlanVertDip->id);
        $textMetacognicion = $this->get_metacognicion($objPlanVertDip->planificacion_bloque_unidad_id);
        $textDiferenciacion = $this->get_diferenciacion($objPlanVertDip->planificacion_bloque_unidad_id);

        $estudianteNEE = $this->get_accion_nee($objPlanVertDip->planificacion_bloque_unidad_id);
        $estuSobresalientes = $this->get_accion_talentos($objPlanVertDip->planificacion_bloque_unidad_id);

     

        $proceso = '<ul>';
        foreach ($procesoAprendizaje as $proc) {
            $proceso .= '<li>'.$proc->opcion->opcion.'</li>';         
        }  
        $proceso .= '</ul>';

       
        $html ='<br>';
        $html.='<b>ACCION:</b> Enseñanza y aprendizaje a través de la investigación';
        $html.='<table style="font-size:10;" width="100%" cellspacing="0" cellpadding="5">';
                    $html.='<tr>                       
                                <td class="border" style="background-color:'.$colorCabeceraFondo.';"><b>PROCESO DE APRENDIZAJE: </b></td> 
                                <td class="border" style="background-color:'.$colorCabeceraFondo.';"><b>ENFOQUES DEL APRENDIZAJE: </b></td>
                                <td class="border" style="background-color:'.$colorCabeceraFondo.';"><b>EVALUACIÓN SUMATIVA: </b></td>                                                  
                            </tr>';
                   $html.='<tr> ';                      
                        $html.= '<td rowspan="3" class="border">'.$proceso.'</td>                        
                                 <td rowspan="3" class="border" >'. $textProcesoApre.'</td>
                                 <td class="border" >';
                                 if($enfoqueEvaluacion){$html.= ''.$enfoqueEvaluacion->sumativa;} 
                        $html.= '</td> ';                      
                    $html.='</tr>';            
                    $html.='<tr>                                
                                <td class="border" style="background-color:'.$colorCabeceraFondo.';"><b>EVALUACIÓN FORMATIVA: </b></td> 
                            </tr>'; 
                    $html.='<tr>
                                <td class="border" >';
                                    if($enfoqueEvaluacion){$html.= ''.$enfoqueEvaluacion->formativa;}
                    $html.='</td> 
                            </tr>';
                      
                    $html.='<tr>                       
                                <td class="border" style="background-color:'.$colorCabeceraFondo.';"><b>METACOGNICIÓN: </b></td> 
                                <td class="border" style="background-color:'.$colorCabeceraFondo.';"><b>DIFERECIACIÓN: </b></td>
                                <td class="border" style="background-color:'.$colorCabeceraFondo.';"><b>ESTUDIANTE CON NEE: </b></td>                                                  
                            </tr>';
                    $html.='<tr >                       
                                <td rowspan="3" class="border">'.$textMetacognicion.'</td>
                                <td rowspan="3" class="border" >'. $textDiferenciacion.'</td>
                                <td class="border">'.$estudianteNEE.'</td>                       
                            </tr>';                   
                    $html.='<tr>                                
                                <td class="border" style="background-color:'.$colorCabeceraFondo.';"><b>ESTUDIANTES CON TALENTOS SOBRESALIENTES: </b></td> 
                            </tr>'; 
                    $html.='<tr>
                                <td class="border" >'.$estuSobresalientes.'</td> 
                            </tr>'; 
                    //** fila de LENGUA Y APRENDISAJE */
                    $html.=$this->lenguaje_y_aprendizaje_reporte();
        $html.='</table>'; 
      
        
        return $html;
    }
    private function get_metacognicion($planBloqueUnidadId)
    {
        $pudDip = \backend\models\PudDip::find()->where([
            'planificacion_bloques_unidad_id' => $planBloqueUnidadId,
            'codigo' => 'METACOGNICION'
         ])->all();
        $campoTexto = '';
        $html = '';       
            $html.='<ul>';
            foreach ($pudDip as $pud) {
                           
                if($pud->campo_de == 'escrito')
                {
                    $campoTexto =$pud->opcion_texto;                   
                }elseif($pud->campo_de == 'seleccion' && $pud->opcion_boolean == true )
                {
                    $html .= '<li>'.$pud->opcion_texto.'</li>';                   
                }               
            }
            $html.='</ul>';       
        $html.='<p><b>Información Detallada: </b>'.$campoTexto.'</p>';

        return $html ;
    }
    private function get_diferenciacion($planBloqueUnidadId)
    {
        $pudDip = \backend\models\PudDip::find()->where([
            'planificacion_bloques_unidad_id' => $planBloqueUnidadId,
            'codigo' => 'APRENDIZAJE-DIFERENCIADO'
         ])->all();
        $campoTexto = '';
        $html = '';       
            $html.='<ul>';
            foreach ($pudDip as $pud) {
                           
                if($pud->campo_de == 'escrito')
                {
                    $campoTexto =$pud->opcion_texto;                   
                }elseif($pud->campo_de == 'seleccion' && $pud->opcion_boolean == true )
                {
                    $html .= '<li>'.$pud->opcion_texto.'</li>';                   
                }               
            }
            $html.='</ul>';       
        $html.='<p><b>Información Detallada: </b>'.$campoTexto.'</p>';

        return $html ;
    }
     //metodo usado para 5.1.-, llamada a contenidos REPORTE
     private function select_contenidos($planUnidadId)
     {        
         $arrayResp = array();
         $contenido = PlanificacionBloquesUnidadSubtitulo::find()->where([
             'plan_unidad_id'=>$planUnidadId
         ])->asArray()->all();       
 
         foreach ($contenido as $cont) {             
             $contenidosSubnivel = PlanificacionBloquesUnidadSubtitulo2::find()->where([
                 'subtitulo_id'=>$cont['id']
             ])->asArray()->all();
             $cont['subtitulos']=array();
 
             foreach ($contenidosSubnivel as $contSub) { 
                 array_push( $cont['subtitulos'],$contSub);                 
             }
             array_push($arrayResp,$cont);
         }             
         return  $arrayResp;
    }
    //5.3 enfoque del aprendizaje reporte
    private function enfoque_aprendizaje_reporte()
    {
       $objPlanVertDip = $this->planVertDipl;
       $titulo = $this->enfoque_aprendizaje_titulo("titulos");
       $detalle = $this->enfoque_aprendizaje_titulo("detalles");

        $text='<b>ENFOQUE DEL APRENDIZAJE (EDA): </b><br>';
        
        $html ='';
        $html.='<table style="font-size:10;" width="100%" cellspacing="0" cellpadding="5">';
                    $html.='<tr>';
                        $html.='<td class="border" align="">'.$text.'</td>';                       
                    $html.='</tr>';
                    $html.='<tr>';
                        $html.='<td class="border" align="">';
                           $html.=$titulo.$detalle;
                        $html.='</td>';
                    $html.='</tr>';  
            $html.='</table>';
        return $html;
    }
    
     /*** 5.3 METODO USADO EN Enfoque del aprendizaje*/
     private function enfoque_aprendizaje_titulo($tipo_consulta)
     {
        $objPlanVertDip = $this->planVertDipl; 
        $textItem='';
         //bucle para capturar las habilidades
         $arrayHabEnfoque = $this->select_habilidadesTDC($objPlanVertDip->id,$tipo_consulta);        
        if(count($arrayHabEnfoque)>0){
             foreach ($arrayHabEnfoque as $arraySubHab) 
             {
                 $textItem .= '<ul>';
                 foreach ($arraySubHab as $contenido) 
                 {
                     if ($tipo_consulta=="titulos")
                     {
                        $textItem .= '<li><font size="4"><b>' . $contenido . '</b></font></li>';
                     }
                     else
                     {
                        $textItem .= '<li>' . $contenido . '</li>';
                     }
                }                     
                 $textItem .= '</ul>';                               
             }
             $textItem .= '<br>';              
         } 
         return  $textItem;
    }
    // metodo usado para 5.3.- llamada a habilidades
     private function select_habilidadesTDC($planVerticalDiplId,$tipo_consulta)
     {
         //muestra todas las habilidades, segun el codigo del plan vertical diploma, que este asociada       
         $con = Yii::$app->db;
         switch($tipo_consulta){
             case 'titulos':
                  $query ="select distinct cph.es_titulo2 
                     from contenido_pai_habilidades cph , planificacion_vertical_diploma_habilidades pvdh 
                     where cph.id = pvdh .habilidad_id  
                     and pvdh.vertical_diploma_id = $planVerticalDiplId
                     order by cph.es_titulo2;"; 
             break;
             case 'detalles':
                 $query ="select  (cph.es_titulo2 || ': ' ||cph.es_exploracion) as dato  
                 from contenido_pai_habilidades cph , planificacion_vertical_diploma_habilidades pvdh 
                 where cph.id = pvdh .habilidad_id  
                 and pvdh.vertical_diploma_id = $planVerticalDiplId
                 order by cph.es_titulo2;"; 
 
             break;
         }
         $resultado = $con->createCommand($query)->queryAll();        
         return $resultado;
     }  
    //REPORTE 5.4 - lenguaje y aprendizaje
    private function lenguaje_y_aprendizaje_reporte()
    {
        $colorCabeceraFondo = "#BEDBEC";
        $objPlanVertDip = $this->planVertDipl;
        $text_lenguaje = '<b>LENGUAJE Y APRENDIZAJE</b>';
        $text_con_tdc = '<b>CONEXION CON TDC</b>';
        $text_con_cas = '<b>CONEXION CON CAS</b>';
       
        $detalle_len_y_aprend = $this->lenguaje_aprendizaje_item();
        $conexion_tdc = $this->conexion_tdc_reporte();
        $conexion_cas = $this->conexion_cas_reporte();

        $html ='';       
                    $html.='<tr>';
                        $html.='<td class="border" style="background-color:'.$colorCabeceraFondo.';">'.$text_lenguaje.'</td>'; 
                        $html.='<td class="border" style="background-color:'.$colorCabeceraFondo.';">'.$text_con_tdc.'</td>';   
                        $html.='<td class="border" style="background-color:'.$colorCabeceraFondo.';">'.$text_con_cas.'</td>';                       
                    $html.='</tr>';
                    $html.='<tr>';
                        $html.='<td class="border">'.$detalle_len_y_aprend.'                                                    
                                    <p><b>Información Detallada:</b></p>'.$objPlanVertDip->detalle_len_y_aprendizaje.'  
                               </td>';
                        $html.='<td class="border">'.$conexion_tdc.'                                                    
                                    <p><b>Información Detallada:</b></p>'.$objPlanVertDip->conexion_tdc.'  
                               </td>';
                        $html.='<td class="border" >'.$conexion_cas.'                                                    
                                    <p><b>Información Detallada:</b></p>'.$objPlanVertDip->detalle_cas.'  
                                </td>';       
                    $html.='</tr>';                   
          
        return $html;
    }
    /*** 5.4  Lenguaje y aprendizaje aux*/
    private function lenguaje_aprendizaje_item()
    {
        $objPlanVertDip = $this->planVertDipl;  
        $modelPlanifVertDiplTDC = $this->consultar_lenguaje_y_aprendizaje_ckeck($objPlanVertDip->id); 

        $itemConexionCas = '<ul>';      
        foreach($modelPlanifVertDiplTDC as $tdc)
        {            
            if($tdc['es_seleccionado'])
            {   
                $itemConexionCas.='<li>'.$tdc['opcion'].'</li>';                        
            }
        }  
        $itemConexionCas .= '</ul>';       
       return $itemConexionCas;
    }  
    //metodo usado para 5.4.- llamada a lenguaje y aprendizaje
    private function consultar_lenguaje_y_aprendizaje_ckeck($planVertDiplId) 
    {
        //consulta los los tdc que han sido marcados con check, mas los que aun no estan marcados
       $con = Yii::$app->db;
       $query = "select p.categoria ,p.opcion,pr.id as pvd_tdc_id ,p.id as tdc_id, 
                case 
                when p.id is not null then true else false
                end as es_seleccionado
                from planificacion_opciones p, planificacion_vertical_diploma_relacion_tdc pr,
                planificacion_vertical_diploma pvd 
                where p.tipo='LENGUAJE_Y_CONOCIMIENTO'  and pvd.id =$planVertDiplId 
                and pr.vertical_diploma_id = pvd.id   
                and pr.relacion_tdc_id  = p.id
                union all 
                select p.categoria ,p.opcion,0 as pvd_tdc_id,p.id tdc_id, 
                case 
                when null is not null then true else false
                end as es_seleccionado
                from planificacion_opciones p
                where p.tipo='LENGUAJE_Y_CONOCIMIENTO'
                and p.id not in 
                ( select relacion_tdc_id  from planificacion_vertical_diploma_relacion_tdc 
                where vertical_diploma_id = $planVertDiplId)
                order by opcion;
                ";
        $resultado = $con->createCommand($query)->queryAll();
        return $resultado;
    }
    //REPORTE: 5.5 CONEXION CON TDC
    /*** 5.5 Conexion con TDC */
    private function conexion_tdc_reporte()
    {
        $textoImp = '<ul>'; 
        $objPlanVertDip = $this->planVertDipl;
        $arrayConexionTDC = $this->select_relacionTDC($objPlanVertDip);
        foreach($arrayConexionTDC as $conexionTDC)
        {
            $textoImp .= '<li>'.$conexionTDC->relacionTdc->opcion.'</li>';
        }
        $textoImp.='</ul>';
        return  $textoImp;
    } 

    //metodo usado para 5.5.- llamada a relacion tdc
    private function select_relacionTDC($planVerticalDipl)
    {
        //muestra todas las relacion tdc, segun el codigo del plan vertical diploma, que este asociada
        $idPlanVertDipl = $planVerticalDipl->id;
        $planVertDip_Relacion = PlanificacionVerticalDiplomaRelacionTdc::find()->where([
            'vertical_diploma_id' => $idPlanVertDipl
        ])->all();         
        return $planVertDip_Relacion;
    }

     /**** REPORTE: 5.6 Conexion con CAS ***/
     private function conexion_cas_reporte()
     {
         $objPlanVertDip = $this->planVertDipl;        
         $modelPlanifVertDiplTDC = $this->consultar_conexion_cas_ckeck($objPlanVertDip->id); 
         $itemConexionCas = '';      
         foreach($modelPlanifVertDiplTDC as $tdc)
         {     
            $itemConexionCas .= '<ul>';         
             if($tdc['es_seleccionado'])
             {
                $itemConexionCas.='<li>'.$tdc['opcion'].'</li>';                                           
             }
         }
         $itemConexionCas.='</ul>';  
        return $itemConexionCas;
     }
      //metodo usado para 5.6.- llamada a Conexion CAS
    private function consultar_conexion_cas_ckeck($planVertDiplId) 
    {
        //consulta los los tdc que han sido marcados con check, mas los que aun no estan marcados
       $con = Yii::$app->db;
       $query = "select p.categoria ,p.opcion,pr.id as pvd_tdc_id ,p.id as tdc_id, 
                case 
                when p.id is not null then true else false
                end as es_seleccionado
                from planificacion_opciones p, planificacion_vertical_diploma_relacion_tdc pr,
                planificacion_vertical_diploma pvd 
                where p.tipo='CONEXION_CAS'  and pvd.id =$planVertDiplId 
                and pr.vertical_diploma_id = pvd.id   
                and pr.relacion_tdc_id  = p.id
                union all 
                select p.categoria ,p.opcion,0 as pvd_tdc_id,p.id tdc_id, 
                case 
                when null is not null then true else false
                end as es_seleccionado
                from planificacion_opciones p
                where p.tipo='CONEXION_CAS'
                and p.id not in 
                ( select relacion_tdc_id  from planificacion_vertical_diploma_relacion_tdc 
                where vertical_diploma_id = $planVertDiplId)
                order by opcion;
                ";
        $resultado = $con->createCommand($query)->queryAll();
        return $resultado;
    } 
    //REPORTE: 6 recursos
    private function recursos_reporte()
    {
        $colorCabeceraFondo = "#BEDBEC";
        $objPlanVertDip = $this->planVertDipl;
        $html='';
        $html.='<table style="font-size:10;" width="100%" cellspacing="0" cellpadding="5">'; 
                    $html.='<tr>'; 
                    $html.='<td class="border" style="background-color:'.$colorCabeceraFondo.'" ><b>RECURSOS</b></td>';                                            
                    $html.='</tr>';                    
                    $html.='<tr>'; 
                        $html.='<td class="border" >'.$objPlanVertDip->recurso.'</td>';                                            
                    $html.='</tr>';                    
            $html.='</table>';
            $html.='<br>';
        return $html;
    } 
    //REPORTE 7 - lo que funciono / lo que no funcino / observaciones, cabios y sugerencias
    private function reflexion_reporte()
    {
        $colorCabeceraFondo = "#BEDBEC";
        $objPlanVertDip = $this->planVertDipl;
        $text_funciono = '<b>LO QUE FUNCIONÓ BIEN: </b>';
        $text_no_funciono = '<b>LO QUE NO FUNCIONÓ BIEN</b>';
        $text_observacion = '<b>OBSERVACION, CAMBIOS Y SUGERENCIAS</b>';       

        $html ='<br>';
        $html ='<b>REFLEXION:</b> Consideración de la planificación, el proceso y el impacto de la indagación';        
        $html.='<table style="font-size:10;" width="100%" cellspacing="0" cellpadding="5">';
                    $html.='<tr>';
                        $html.='<td class="border" style="background-color:'.$colorCabeceraFondo.'" >'.$text_funciono.'</td>'; 
                        $html.='<td class="border" style="background-color:'.$colorCabeceraFondo.'" >'.$text_no_funciono.'</td>';   
                        $html.='<td class="border" style="background-color:'.$colorCabeceraFondo.'" >'.$text_observacion.'</td>';                       
                    $html.='</tr>';
                    $html.='<tr>';
                        $html.='<td class="border" align="">'.$objPlanVertDip->reflexion_funciono.'</td>'; 
                        $html.='<td class="border" align="">'.$objPlanVertDip->reflexion_no_funciono.'</td>';   
                        $html.='<td class="border" align="">'.$objPlanVertDip->reflexion_observacion.'</td>';                       
                    $html.='</tr>';
                                     
            $html.='</table>';
        return $html;
    }
     /** 5.7 ESTUDIANTES CON NEE **/
     private function get_accion_nee($planBloqueUnidadId)
     {
        $periodoId = Yii::$app->user->identity->periodo_id;
        $planBloqueUnidad = PlanificacionBloquesUnidad::findOne($planBloqueUnidadId);        
        $opCourseTemplateId = $planBloqueUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->op_course_template_id;
       
        $objScritp = new Scripts();        
        $nee = $objScritp->get_nee($periodoId, $opCourseTemplateId);  

        $html = '<ul>';
        if($nee){
            foreach ($nee as $n){
                $html .= '<p>';
                $html .= '<li><b> '.$n['estudiante'].'</b></li>';
                $html .= $n['curso'].' | '.$n['paralelo'].' | '. $n['materia'].'<br>';
                $html .= 'grado: ('.$n['grado_nee'].') '.$n['diagnostico_inicia'];
                $html .= '</p>';
            }
        }        
        return $html;
    }
     /*     * * 5.8  ESTUDIANTES CON TALENTOS ESPECIALES*/
     private function get_accion_talentos($planBloqueUnidadId) 
     {          
        $resp = "";
        $pudDip = \backend\models\PudDip::find()->where([
            'planificacion_bloques_unidad_id' => $planBloqueUnidadId,
            'codigo' => 'TALENTOS'
         ])->one();

        if($pudDip){$resp = $pudDip->opcion_texto;}
        
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
                }';
        $html .= '</style>';
        return $html;
    }
}


?>