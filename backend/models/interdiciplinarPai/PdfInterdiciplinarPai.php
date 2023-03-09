<?php
namespace backend\models\interdiciplinarPai;


use backend\models\IsmGrupoMateriaPlanInterdiciplinar;
use backend\models\helpers\HelperGeneral;
use backend\models\IsmContenidoPlanInterdiciplinar;
use backend\models\IsmRespuestaContenidoPaiInterdiciplinar;
use backend\models\IsmRespuestaContenidoPaiInterdiciplinar2;
use backend\models\IsmRespuestaOpcionesPaiInterdiciplinar;
use backend\models\IsmRespuestaPlanInterdiciplinar;
use backend\models\IsmRespuestaReflexionPaiInterdiciplinar;
use backend\models\pudpai\Datos;
use backend\models\PlanificacionBloquesUnidad;
use backend\models\IsmGrupoPlanInterdiciplinar;
use backend\models\CurriculoMecBloque;
use backend\models\IsmCriterioDescriptorArea;
use backend\models\PlanificacionDesagregacionCabecera;
use backend\models\PlanificacionVerticalPaiDescriptores;
use backend\models\PlanificacionVerticalPaiOpciones;
use backend\models\PlanificacionBloquesUnidadSubtitulo2;
use backend\models\helpers\Scripts;
use backend\models\AdaptacionCurricularXBloque;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;
use datetime;




class PdfInterdiciplinarPai extends \yii\db\ActiveRecord
{

    private $grupoPlanInterdisciplinar;
    private $idPlanUndiad;
    
    public function __construct($idGrupoInter,$idPlanUndiad)
    {     
        $this->idPlanUndiad = $idPlanUndiad; 
        $this->grupoPlanInterdisciplinar = $idGrupoInter;

        $this->generate_pdf();
    }

    private function generate_pdf()
    {
        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 38,
            'margin_bottom' => 10,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);
        $cabecera = $this->cabecera();
        $mpdf->SetHtmlHeader($cabecera);
        $mpdf->showImageErrors = true;  
        $html ='';
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
    private function cabecera()
    {
        $codigoISO = "ISMR20-23";
        $version ="5.0";
        $fecha=date('Y-m-d H:i:s'); 
        $fecha=date('Y-m-d'); 
        $fecha = '31/10/2022';
        $html = <<<EOT
        <table border="1" width="100%" cellspacing="0" cellpadding="10"> 
            <tr> 
                <td class="border" align="center" width="20%">
                    <img src="imagenes/instituto/logo/logoISM1.png" width="80px">
                    <br>
                    <span style="font-size:10;">Proceso Académico</span>
                </td>
                <td class="border" align="center" width="" >
                                             
                </td>
                <td class="border" align="left" width="20%">
                    <table style="font-size:10;">
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

    private function piePagina()
    {
        
    }
    private function firmas()
    {
        $html = <<<EOD
        <br>
        <br>
        <table border="1" width="100%" cellspacing="0" cellpadding="5">         
            <tr> 
                <td colspan="3"  align="left" style="font-size:10">
                    10.FIRMAS DE RESPONSABILIDAD
                </td>                           
            </tr> 
            <tr> 
                <td colspan="2" align="center" style="font-size:10">FIRMA DE DOCENTE</td>
                <td align="center" style="font-size:10">FIRMA DE COORDINACIÓN</td>
            </tr> 
            <tr> 
                <td align="left" style="font-size:10"><br><br><br><br><br><br></td>
                <td align="left" style="font-size:10"><br><br><br><br><br><br></td>
                <td align="left" style="font-size:10"><br><br><br><br><br><br></td>
            </tr> 
        </table> 
        EOD;      
        return $html;
    }

    private function cuerpo()
    {
        $periodoId = Yii::$app->user->identity->periodo_id;  

        $html ='';

        $titulo = <<<EOK
            <table border="1" width="100%" cellspacing="0" cellpadding="5">
                <tr >
                    <td align="center">ISM <br> International Scholastic Model</td>               
                </tr>
                <tr >
                    <td align="center">PLAN DE UNIDAD INTERDISCIPLINAR PAI <br> AÑO ESCOLAR ----</td>               
                </tr>
            </table>
        EOK; 
        $html .= $titulo;
        $html .=$this->datos_informativo();
        $html .= $this->datos_indagacion();
        $html .= $this->enfoque_aprendizaje();
        $html .= $this->objetivo_desarrollo();        
        $html .= $this->evaluacion_desemplenio();
        $html .= $this->accion_ensenianza_aprendizaje();
        $html .= $this->proceso_aprendizaje_interdisciplinar();
        $html .= $this->recursos();
        $html .= $this->reflexion();
       
        return $html;
    }  
    /******************************************************************************************************** */
    //1.- DATOS INFORMATIVOS
    private function datos_informativo()
    {
        $titulo = '1.- DATOS INFORMATIVOS';
         
        $esEditable = false;
        $objDatos = new Datos($this->idPlanUndiad);
        $planUnidad   = PlanificacionBloquesUnidad::findOne($this->idPlanUndiad);
        $anio_pai = $planUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->name;
        


        $tiempo = $objDatos->calcula_horas(
            $planUnidad->planCabecera->ismAreaMateria->materia_id,
            $planUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->op_course_template_id
        );

        $fecha_inicio = $tiempo['fecha_inicio'] ;
        $fecha_fin = $tiempo['fecha_inicio'];
        $horas = $tiempo['horas'] ;

        $get_materia = $this->get_materias($this->grupoPlanInterdisciplinar);
        $get_profesores = $this->get_docentes($this->grupoPlanInterdisciplinar);

        $html = <<<EOK
                <br>
                <table border="1" width="100%" cellspacing="0" cellpadding="5" style="font-size:10">
                    <tr >
                        <td colspan="6">1.- DATOS INFORMATIVOS</td>               
                    </tr>
                    <tr >
                        <td ><b>Grupos de Asignaturas</b></td>
                        <td colspan="2">$get_materia </td>
                        <td ><b>Profesor(es)</b></td>
                        <td colspan="2">$get_profesores</td>
                        <td ><b>Fecha de Finalización</b></td>
                        <td >$fecha_fin </td>               
                    </tr>
                    <tr >
                        <td ><b>Título de la Unidad: </b></td>
                        <td >$planUnidad->unit_title</td>
                        <td ><b>Fecha de Inicio</b></td>
                        <td > $fecha_inicio </td>
                        <td ><b>Año PAI</b></td>
                        <td >$anio_pai</td>  
                        <td ><b>Duración en Horas:</b></td>
                        <td >$horas </td>              
                    </tr>
                </table>
            EOK; 

        return $html;
    }
     /******************************************************************************************************** */

     private function get_materias($idGrupoInter)
     {
         $modelIsmGrupoMaterias = IsmGrupoMateriaPlanInterdiciplinar::find()
             ->where(['id_grupo_plan_inter' => $idGrupoInter])
             ->all();
 
         $html = "";
 
         $html .= '<div class="card ">
                     <div class="card-header">
                         <div class="row">                           
                             <div class="col"><span style="color:red">Materia</div>
                         </div>
                     </div>
                     <div class="card-body">';
         foreach ($modelIsmGrupoMaterias as $modelGrupo) {
             $html .= '<div class="col"> - ' .
                 $modelGrupo->ismAreaMateria->materia->nombre
                 . '</div>';
         }
         $html .= '</div>
                 </div>';
 
         return $html;
     }
 
     public function get_docentes($idGrupoInter)
     {
         $objHelper = new HelperGeneral();
         $modelIsmGrupoMaterias = IsmGrupoMateriaPlanInterdiciplinar::find()
             ->where(['id_grupo_plan_inter' => $idGrupoInter])
             ->all();
 
         $html = "";
         $periodoId = \Yii::$app->user->identity->periodo_id;
 
         $html .= '<div class="card ">
                     <div class="card-header">
                         <div class="row">                           
                             <div class="col"><span style="color:red">Profesores</div>
                         </div>
                     </div>
                     <div class="card-body">';
         foreach ($modelIsmGrupoMaterias as $modelGrupo) {
             $areaMateriaId = $modelGrupo->ismAreaMateria->id;
             $templateId = $modelGrupo->ismAreaMateria->mallaArea->periodoMalla->malla->op_course_template_id;
 
             $resp = $objHelper->obtener_docentes_por_curso($areaMateriaId, $periodoId, $templateId);
 
             foreach ($resp as $r) {
                 $html .= '<div class="col"> - ' .
                     $r['docente']
                     . '</div>';
             }
         }
         $html .= '</div>
                 </div>';
 
         return $html;
     }
    /******************************************************************************************************** */
     //2.- INDAGACION, ESTABLECIMIENTO DEL PROPOSITO DE LA UNIDAD INTERDISCIPLINAR
    private function campos_a_llenar_texto_plan_interdisciplinar($idSeccion)
    {
        $grupoPlanInter = $this->grupoPlanInterdisciplinar;
        $con = Yii::$app->db;
        $query ="select i1.id as idGrupo,i1.id_grupo_plan_inter ,i1.id_contenido_plan_inter,i1.respuesta ,
                i2.id as idContenido,i2.id_seccion_interdiciplinar ,i2.nombre_campo ,i2.activo ,i2.heredado 
                from ism_respuesta_plan_interdiciplinar i1, 
                ism_contenido_plan_interdiciplinar i2
                where i1.id_contenido_plan_inter = i2.id
                and i1.id_grupo_plan_inter ='$grupoPlanInter'
                and i2.id_seccion_interdiciplinar =$idSeccion
                and i2.activo =true;";

        $modelContenido = $con->createCommand($query)->queryAll();

        return  $modelContenido;
    }
    private function datos_indagacion()
    {
        $modelContenido = $this->campos_a_llenar_texto_plan_interdisciplinar(2);

       
        $propositoIntegracion = $this->get_id_respuesta_plan_inter_pai($modelContenido,'PROPÓSITO DE LA INTEGRACIÓN');
        $concepto_clave = $this->get_id_respuesta_plan_inter_pai($modelContenido,'CONCEPTO CLAVE');
        $contexto_global = $this->get_id_respuesta_plan_inter_pai($modelContenido,'CONTEXTO GLOBAL');
        $enunciado_indagacion = $this->get_id_respuesta_plan_inter_pai($modelContenido,'ENUNCIADO DE LA INDAGACIÓN');
        $facticas = $this->get_id_respuesta_plan_inter_pai($modelContenido,'Fácticas');
        $conceptuales = $this->get_id_respuesta_plan_inter_pai($modelContenido,'Conceptuales');
        $debatible = $this->get_id_respuesta_plan_inter_pai($modelContenido,'Debatibles');

        $modelConceptoClave = IsmRespuestaOpcionesPaiInterdiciplinar::find()
        ->where(['id_respuesta_plan_inter_pai'=>$concepto_clave['idgrupo']])
        ->andWhere(['mostrar'=>true])
        ->all();

        $modelContextoGlobal = IsmRespuestaOpcionesPaiInterdiciplinar::find()
        ->where(['id_respuesta_plan_inter_pai'=>$contexto_global['idgrupo']])
        ->andWhere(['mostrar'=>true])
        ->all();
    

        // echo '<pre>';
        // print_r($concepto_clave['idcontenido']);
        // die();

        $html = '
            <br>
            <table border="1" width="100%" cellspacing="0" cellpadding="5" style="font-size:10">
                <tr >
                    <td colspan="">2.- INDAGACIÓN: ESTABLECIMIENTO DEL PROPÓSITO DE LA UNIDAD INTERDISCIPLINARIA </td>               
                </tr>
                <tr >
                    <td style="text-align: center">PROPÓSITO DE LA INTEGRACIÓN</td>               
                </tr>
                <tr >
                    <td >'.$propositoIntegracion['respuesta'].'</td>               
                </tr>
            </table>
            <br>
            <table border="1" w cellspacing="0" cellpadding="5" style="font-size:10">               
                <tr >
                    <td width="400px"><b>CONCEPTO(S) CLAVE/(CONCEPTOS RELACIONADOS)</b></td>
                    <td width="400px"><b>CONTEXTO GLOBAL</b></td>                                              
                </tr>
                <tr >
                    <td width="400px">';
                        $html.='<ul>';
                    foreach($modelConceptoClave as $model)
                    {
                        $html.='<li>'.$model->contenido.'</li>';
                    }
                    
                    $html.='</ul>';
                    $html.='</td> 
                    <td width="400px">';
                        $html.='<ul>';
                    foreach($modelContextoGlobal as $model)
                    {
                        $html.='<li>'.$model->contenido.'</li>';
                    }
                    
                    $html.='</ul>';
                    $html.='</td>                          
                </tr>                             
            </table>
            <br>
            <table border="1" width="100%" cellspacing="0" cellpadding="5" style="font-size:10">
                <tr >
                    <td ><b>ENUNCIADO DE LA INDAGACIÓN: </b>
                    (expresa claramente una comprensión conceptual importante que tiene un profundo significado y un valor a largo plazo para los alumnos. 
                    Incluye claramente un concepto clave, conceptos relacionados y una exploración del contexto global específica, que da una perspectiva 
                    creativa y compleja del mundo real; describe una comprensión transferible y a la vez importante para la asignatura; establece un propósito 
                    claro para la indagación).</td>               
                </tr>
                <tr >
                    <td style="">'.$enunciado_indagacion['respuesta'].'</td>               
                </tr>
            </table>
            <br>
            <table border="1"  cellspacing="0" cellpadding="5" style="font-size:10">
                <tr >
                    <td colspan="2" style="text-align:center;"><b>PREGUNTAS DE INDAGACIÓN:  </b>
                    (inspiradas en el enunciado de indagación. Su fin es explorar el enunciado en mayor detalle. Ofrecen andamiajes).</td>               
                </tr>
                <tr >
                    <td width="400px"><b>Fácticas:</b> (se basan en conocimientos y datos, ayudan a comprender terminología del enunciado, 
                            facilitan la comprensión, se pueden buscar)</td>   
                    <td width="400px">'.$facticas['respuesta'].'</td>               
                </tr>
                <tr >
                    <td width="400px"><b>Conceptuales:</b> (conectar los datos, comparar y contrastar, explorar contradicciones, comprensión más profunda, 
                            transferir a otras situaciones, contextos e ideas, analizar y aplicar)</td>   
                    <td width="400px">'.$conceptuales['respuesta'].'</td>               
                </tr>
                <tr >
                    <td width="400px"><b>Debatibles:</b> (promover la discusión, debatir una posición, explorar cuestiones importantes desde múltiples 
                        perspectivas, deliberadamente polémicas, presentar tensión, evaluar)
                    </td>   
                    <td width="400px">'.$debatible['respuesta'].'</td>               
                </tr>
            </table>
        ';

        return $html;


    }
    
    //2 devolucion de id acorde el parametro a buscar de las respuestas en el plan inter pai
    private function get_id_respuesta_plan_inter_pai($modelContenido,$respuesta)
    {
        $arrayRespuesta = array();
        foreach($modelContenido as $m)
        {
            if($m['nombre_campo']==$respuesta)
            {
                
                $arrayRespuesta['idgrupo'] = $m['idgrupo'];
                $arrayRespuesta['respuesta'] = $m['respuesta'];
               
            }
        }

        return $arrayRespuesta;
    }
   
    /******************************************************************************************************** */
    //3 enfoque del aprendizaje
    public function enfoque_aprendizaje()
    {
        
        $modelContenido = $this->campos_a_llenar_texto_plan_interdisciplinar(3);       
        $arrayHabilidades =   $this->get_id_respuesta_plan_inter_pai($modelContenido,'HABILIDAD');

        $modelHabilidades = IsmRespuestaOpcionesPaiInterdiciplinar::find()
        ->where(['id_respuesta_plan_inter_pai'=>$arrayHabilidades['idgrupo']])
        ->andWhere(['mostrar'=>true])
        ->all();
       
        $html ='<br>
        <table border="1" width="100%" cellspacing="0" cellpadding="5" style="font-size:10">
                        <tr >
                            <td colspan="4"><b>3.- ENFOQUES DEL APRENDIZAJE </b></td>               
                        </tr>;
                        <tr >
                            <td width="25%"><b>HABILIDAD</b></td>   
                            <td width="25%"><b>EXPLORACIÓN</b></td>
                            <td width="25%"><b>ACTIVIDAD</b></td>
                            <td width="25%"><b>ATRIBUTO DEL PERFIL</b></td>            
                        </tr>';
                        foreach($modelHabilidades as $model)
                        {
                            $html .= '<tr>';
                                    $html .='<td >'.$model->tipo.'</td> 
                                             <td >'.$model->contenido.'</td> 
                                             <td >'.$model->actividad.'</td>'; 
                                    $html .='<td >';
                                            //buscamos ism_respuesta_contenido_pai_interdiciplinar
                                            $modelRespuesta = IsmRespuestaContenidoPaiInterdiciplinar::find()
                                            ->where(['id_respuesta_opciones_pai'=>$model->id])
                                            ->andWhere(['mostrar'=>true])
                                            ->all();
                                            $html.='<ul>';
                                            foreach($modelRespuesta as $model1)
                                            {    
                                                $html.='<li>';
                                                    $html.=$model1->contenido;
                                                $html.='</li>';
                                            }
                                            $html.='</ul>';
                                    $html .='</td>';
                            $html .= '</tr>';
                        }
        $html .='
                </table>
            ';


        return $html;

    }
    //4
    private function objetivo_desarrollo()
    {
        $idGrupoPlanInter= $this->grupoPlanInterdisciplinar;
        //buscamos el id, que corresponde a COMPETENCIA, de la seccion 4  EN ISM CONTwhENIDO_ PLNA INTERDICIPLINAR
        $modelContenido = IsmContenidoPlanInterdiciplinar::find()
        ->where(['nombre_campo'=>'COMPETENCIA'])
        ->andWhere(['activo'=>true])
        ->andWhere(['id_seccion_interdiciplinar'=>4])
        ->one();

        $modelRespuesta = IsmRespuestaPlanInterdiciplinar::find()
        ->where(['id_grupo_plan_inter'=>$idGrupoPlanInter ])
        ->andWhere(['id_contenido_plan_inter'=>$modelContenido->id])
        ->one();

        $modelRespuestaContenido = IsmRespuestaContenidoPaiInterdiciplinar2::find()
        ->where(['id_respuesta_pai_interdisciplinar'=>$modelRespuesta->id])
        ->all();        
       
        $html ='<br>
        <table border="1" width="100%" cellspacing="0" cellpadding="5" style="font-size:10">
                        <tr >
                            <td colspan="4"><b>4.- OBJETIVO DEL DESARROLLO SOSTENIBLE</b></td>               
                        </tr>;                       
                        <tr >
                            <td width="25%"><b>COMPETENCIA</b></td>   
                            <td width="25%"><b>ACTIVIDAD</b></td>
                            <td width="25%"><b>OBJETIVO</b></td>
                            <td width="25%"><b>RELACION ODS-IB</b></td>            
                        </tr>';
                 
                            foreach($modelRespuestaContenido as $model)
                            {
                                $html.='<tr >';
                                $html.='<td >'.$model->contenido.'</td>   
                                        <td >'.$model->actividad.'</td>
                                        <td >'.$model->objetivo.'</td>
                                        <td >'.$model->relacion_ods.'</td>';
                                $html.='</tr>';
                            }
                
        $html.='</table>';
        


        return $html;

    }
    //5.-
    private function evaluacion_desemplenio()
    {
        $idGrupoPlanInter= $this->grupoPlanInterdisciplinar;

        $html ='<br>
        <table border="1" width="100%" cellspacing="0" cellpadding="5" style="font-size:10">
                        <tr >
                            <td colspan="2"><b>5.- EVALUACIÓN: DESEMPEÑO(S) DE COMPRENSIÓN INTERDISCIPLINARIO(S)</b></td>               
                        </tr>;                      
                        <tr >
                            <td width="50%">
                                <table border="1" width="100%" cellspacing="0" cellpadding="5" style="font-size:10" >
                                    <tr>
                                        <td>CRITERIOS INTERDISCIPLINARIOS</td>
                                    </tr>
                                    <tr>
                                        <td>'.
                                        $this->criterios_interdisciplinar($idGrupoPlanInter)
                                        .'</td>
                                    </tr>
                                </table> 
                            </td>   
                            <td width="50%">
                                <table table border="1" width="100%" cellspacing="0" cellpadding="5" style="font-size:10">
                                    <tr><td><b>EVALUACIONES FORMATIVAS DISCIPLINARIAS</b></td></tr>
                                    <tr><td>'.$this->devolver_campo_respuesta('EVALUACIONES FORMATIVAS DISCIPLINARIAS',5).'</td></tr>
                                    <tr><td><b>EVALUACIONES FORMATIVAS INTERDISCIPLINARIAS</b></td></tr>
                                    <tr><td>'.$this->devolver_campo_respuesta('EVALUACIONES FORMATIVAS INTERDISCIPLINARIAS',5).'</td></tr>
                                    <tr><td><b>EVALUACION SUMATIVA</b></td></tr>
                                    <tr><td>'.$this->devolver_campo_respuesta('EVALUACION SUMATIVA',5).'</td></tr>
                                    <tr><td></td></tr>
                                </table> 
                            </td>      
                        </tr>';                 
                
        $html.='</table>';

        return $html;

    }
    //5
    private function devolver_campo_respuesta($nombreCampo,$id_seccion)
    {
        $idGrupoPlanInter= $this->grupoPlanInterdisciplinar;
        $con = Yii::$app->db;
        $query = "select id,id_grupo_plan_inter ,id_contenido_plan_inter ,respuesta  
                from ism_respuesta_plan_interdiciplinar irpi where id_grupo_plan_inter =  $idGrupoPlanInter and id_contenido_plan_inter in 
                (
                select id from ism_contenido_plan_interdiciplinar icpi 
                where id_seccion_interdiciplinar =$id_seccion and nombre_campo ='$nombreCampo' and activo = true
                );";
        $resp = $con->createCommand($query)->queryOne();

        return $resp['respuesta'];

    }
    //5
    private function criterios_interdisciplinar( $idGrupoInter)
    {
        $html ='';        
        $criteriosSeleccionados =$this->consulta_criterios_seleccionados( $idGrupoInter,true);
      
                $html .='<table border="1" width="100%" cellspacing="0" cellpadding="5" style="font-size:10">';
                    $html .='<tr>';
                        $html .= '<th class="text-center" style="background-color: while; color: black">AREA</th>';
                        $html .= '<th class="text-center" style="background-color: while; color: black">CRITERIO</th>';
                        $html .= '<th class="text-center" style="background-color: while; color: black">NOMBRE CRITERIO</th>';
                        $html .= '<th class="text-center" style="background-color: while; color: black">DESCRIPCIÒN</th>';
                    $html .='</tr>';
                foreach($criteriosSeleccionados as $model)
                {
                    $html .='<tr>';
                            $html .= '<td>'.$model['nombre'].'</td>';
                            $html .= '<td>'.$model['criterio'].'</td>';
                            $html .= '<td>'.$model['codigo_idioma_alterno'].'</td>';
                            $html .= '<td>'.$model['descriptor_detalle'].'</td>';
                    $html .= '</tr>';
                }
                $html .= '</table>';

        return $html;

    }
    //5
    private function consulta_criterios_seleccionados($idGrupoInter,$esInterdisciplinar)
    {
        $con = Yii::$app->db;
        $query = "select 	pd.id
                                , ic.nombre as criterio
                                , icl.nombre_espanol as codigo_idioma_alterno
                                , id.nombre as codigo
                                ,ild.descripcion as descriptor_detalle
                                ,ia.nombre
                from 	planificacion_vertical_pai_descriptores pd
                                inner join ism_criterio_descriptor_area maes on maes.id = pd.descriptor_id
                                inner join ism_criterio ic on ic.id = maes.id_criterio
                                inner join ism_criterio_literal icl on icl.id = maes.id_literal_criterio
                                inner join ism_descriptores id on id.id = maes.id_descriptor
                                inner join ism_literal_descriptores ild on ild.id = maes.id_literal_descriptor
                                inner join ism_area ia on ia.id = maes.id_area 
                where 	pd.plan_unidad_id in 
                (
                    select i4.id
                    from ism_grupo_plan_interdiciplinar i1,
                    ism_grupo_materia_plan_interdiciplinar i2,
                    planificacion_desagregacion_cabecera i3,
                    planificacion_bloques_unidad i4,
                    curriculo_mec_bloque i5,
                    scholaris_bloque_actividad i6
                    where i1.id  = i2.id_grupo_plan_inter 
                    and i2.id_ism_area_materia  = i3.ism_area_materia_id 
                    and i3.id = i4.plan_cabecera_id 
                    and i4.curriculo_bloque_id = i5.id 
                    and i5.shot_name  = i6.abreviatura 
                    and i1.id_bloque = i6.id 
                    and i1.id  = $idGrupoInter                  
                )
                and icl.es_interdisciplinar = '$esInterdisciplinar' 
                and ild.es_interdisciplinar = '$esInterdisciplinar'
                order by ic.nombre;";

        // echo '<pre>';
        // print_r($query);
        // die();
     
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    //6
    private function accion_ensenianza_aprendizaje()
    {
        $idGrupoInter= $this->grupoPlanInterdisciplinar;
        $html =' <br>';        
        $html .='<table border="1" width="100%" cellspacing="0" cellpadding="5" style="font-size:10">';
                    $html .='<tr>';
                        $html .= '<td colspan="2"><b>6.	ACCIÓN: ENSEÑANZA Y APRENDIZAJE A TRAVÉS DE LA INDAGACIÓN INTERDISCIPLINARIA</b></td>';
                    $html .='</tr>';
                    $html .='<tr>';
                        $html .= '<td colspan="2"  align="center">BASE DISCIPLINARIA</td>';
                    $html .='</tr>';
                    $html.='<tr>';
                        $html.='<td width="20%" style ="border: 1px solid grey">Objetivo específico del PAI</td>';
                        $html.='<td width="80%" style ="border: 1px solid grey">'.$this->obtener_objetivo_especifico_pai($idGrupoInter).'</td>';
                    $html.='</tr>';
                    $html.='<tr>';
                        $html.='<td width="20%" style ="border: 1px solid grey">Conceptos relacionados</td>';
                        $html.='<td style ="border: 1px solid grey">'.$this->obtener_concepto_relacionado_pai($idGrupoInter).'</td>';
                    $html.='</tr>';
                    $html.='<tr>';
                        $html.='<td width="20%" style ="border: 1px solid grey">Contenidos</td>';
                        $html.='<td style ="border: 1px solid grey">'.$this->obtener_contenido_pai($idGrupoInter).'</td>';
                    $html.='</tr>';
                    $html.='<tr>';
                        $html.='<td width="20%" style ="border: 1px solid grey">Actividades de Aprendizaje y estrategias de enseñanza disciplinarias</td>';
                        $html.='<td style ="border: 1px solid grey">'.$this->obtener_actividad_aprendizaje_pai($idGrupoInter).'</td>';
                    $html.='</tr>';               
        $html .= '</table>';

        return $html;

    }
    //6
    private function obtener_objetivo_especifico_pai($idGrupoInter)
    {
        $arrayInterno = array();
        $arrayHabilidades = array();
        $html='';

        $modelGrupoInte = IsmGrupoPlanInterdiciplinar::findOne($idGrupoInter);
        $abreviaturaBloque = $modelGrupoInte->bloque->abreviatura;

        $modelCurriculoMec = CurriculoMecBloque::find()
            ->where(['shot_name' => $abreviaturaBloque])
            ->one();

        //1.-buscamos los id_are_materias
        $modelGrupoMaterias = IsmGrupoMateriaPlanInterdiciplinar::find()
            ->where(['id_grupo_plan_inter' => $idGrupoInter])
            ->all();

        //2.- buscamos plan desag cab , con el ism_area_materia
        foreach ($modelGrupoMaterias as $modelGrupo) 
        {
            $modelPlanDesagCabecera = PlanificacionDesagregacionCabecera::find()
                ->where(['ism_area_materia_id' => $modelGrupo->id_ism_area_materia])
                ->one();

            if ($modelPlanDesagCabecera) 
            {
                $modelPlanBloqueUndiad = PlanificacionBloquesUnidad::find()
                    ->where(['plan_cabecera_id' => $modelPlanDesagCabecera->id])
                    ->andWhere(['curriculo_bloque_id' => $modelCurriculoMec->id])
                    ->one();

                $modelPlanVertPaiDesc = PlanificacionVerticalPaiDescriptores::find()
                    ->where(['plan_unidad_id'=>$modelPlanBloqueUndiad->id])
                    ->all();              

                foreach ($modelPlanVertPaiDesc as $modelDescri) 
                {
                    //buscamos en ism_criterio_descriptores_area
                    $modelCriterioDesc = IsmCriterioDescriptorArea::findOne($modelDescri->descriptor_id);
                    $area = $modelCriterioDesc->area->nombre;
                    $curso =$modelCriterioDesc->curso->name;
                    $criterio =$modelCriterioDesc->criterio->nombre;
                    $criterio_literal =$modelCriterioDesc->literalCriterio->nombre_espanol;
                    $descriptor =$modelCriterioDesc->descriptor->nombre;
                    $descriptor_literal =$modelCriterioDesc->literalDescriptor->descripcion;

                    $html.='<table border="0" width="100%" cellspacing="0" cellpadding="5" style="font-size:10">';
                    
                        $html.='<tr  >
                                    <td width="15%" style ="border: 1px solid grey;padding: 15px;">'.$area.'</td>
                                    <td width="10%" style ="border: 1px solid grey;padding: 15px;">'.$criterio.'</td> 
                                    <td width="15%" style ="border: 1px solid grey;padding: 15px;">'.$criterio_literal.'</td>   
                                    <td width="60%" style ="border: 1px solid grey;padding: 15px;">'.$descriptor_literal.'</td>
                                </tr>';
                    $html.='</table>';
                }
            }
        }
        return $html;
    }
    //6
    private function obtener_concepto_relacionado_pai($idGrupoInter)
    {
        $arrayInterno = array();
        $arrayHabilidades = array();
        $html='';

        $modelGrupoInte = IsmGrupoPlanInterdiciplinar::findOne($idGrupoInter);
        $abreviaturaBloque = $modelGrupoInte->bloque->abreviatura;

        $modelCurriculoMec = CurriculoMecBloque::find()
            ->where(['shot_name' => $abreviaturaBloque])
            ->one();

        //1.-buscamos los id_are_materias
        $modelGrupoMaterias = IsmGrupoMateriaPlanInterdiciplinar::find()
            ->where(['id_grupo_plan_inter' => $idGrupoInter])
            ->all();

        //2.- buscamos plan desag cab , con el ism_area_materia
        foreach ($modelGrupoMaterias as $modelGrupo) 
        {
            $modelPlanDesagCabecera = PlanificacionDesagregacionCabecera::find()
                ->where(['ism_area_materia_id' => $modelGrupo->id_ism_area_materia])
                ->one();

            if ($modelPlanDesagCabecera) 
            {
                $modelPlanBloqueUndiad = PlanificacionBloquesUnidad::find()
                    ->where(['plan_cabecera_id' => $modelPlanDesagCabecera->id])
                    ->andWhere(['curriculo_bloque_id' => $modelCurriculoMec->id])
                    ->one();

                $modelPlanVertPaiOp = PlanificacionVerticalPaiOpciones::find()
                    ->where(['plan_unidad_id'=>$modelPlanBloqueUndiad->id])
                    ->andWhere(['tipo'=>'concepto_relacionado'])
                    ->all();              

                foreach ($modelPlanVertPaiOp as $modelOpciones) 
                {
                    $materia = $modelOpciones->planUnidad->planCabecera->ismAreaMateria->materia->nombre;
                    $id = $modelOpciones->id;
                    $tipo = $modelOpciones->tipo;
                    $contenido = $modelOpciones->contenido;

                    $html.='<table border="0" width="" cellspacing="0" cellpadding="5" style="font-size:10">';
                        $html.='<tr>
                                    <td width="300px" style ="border: 1px solid grey;padding: 15px;">'.$materia.'</td>                                      
                                    <td width="300px" style ="border: 1px solid grey;padding: 15px;">'.$contenido.'</td>   
                                </tr>';
                    $html.='</table>';
                }
            }
        }
        return $html;
    }
    //6
    private function obtener_contenido_pai($idGrupoInter)
    {
        $arrayInterno = array();
        $arrayHabilidades = array();
        $html='';

        $modelGrupoInte = IsmGrupoPlanInterdiciplinar::findOne($idGrupoInter);
        $abreviaturaBloque = $modelGrupoInte->bloque->abreviatura;

        $modelCurriculoMec = CurriculoMecBloque::find()
            ->where(['shot_name' => $abreviaturaBloque])
            ->one();

        //1.-buscamos los id_are_materias
        $modelGrupoMaterias = IsmGrupoMateriaPlanInterdiciplinar::find()
            ->where(['id_grupo_plan_inter' => $idGrupoInter])
            ->all();

        $temario = array();

        //2.- buscamos plan desag cab , con el ism_area_materia
        foreach ($modelGrupoMaterias as $modelGrupo) 
        {
            $modelPlanDesagCabecera = PlanificacionDesagregacionCabecera::find()
                ->where(['ism_area_materia_id' => $modelGrupo->id_ism_area_materia])
                ->one();

            if ($modelPlanDesagCabecera) 
            {
                $modelPlanBloqueUndiad = PlanificacionBloquesUnidad::find()
                    ->where(['plan_cabecera_id' => $modelPlanDesagCabecera->id])
                    ->andWhere(['curriculo_bloque_id' => $modelCurriculoMec->id])
                    ->one();
                    
                    $objScripts = new Scripts();
                    $subtitulos = $objScripts->selecciona_subtitulos($modelPlanBloqueUndiad->id);
            
                    foreach($subtitulos as $subtitulo)
                    {                        
                        $subtitulo2 = PlanificacionBloquesUnidadSubtitulo2::find()->where([
                            'subtitulo_id' => $subtitulo['id']
                        ])->orderBy('orden')->all();
            
                        $subtitulo['subtitulos'] = $subtitulo2;
                    
                        array_push($temario, $subtitulo);
                    }
            }
        }


        $html.='<table border="0" width="" cellspacing="0" cellpadding="5" style="font-size:10">';
        foreach ($temario as $temario) 
        {                                                   
            $html.='<tr>';
                $html.='<td width="100px" style ="border: 1px solid grey;padding: 15px;"> '.$temario['subtitulo'].'</td>';
                $html.='<td width="200px" style ="border: 1px solid grey;padding: 15px;">';   
                    $html.='<ul>';             
                        foreach ($temario['subtitulos'] as $subtitulos) 
                        {
                        $html.='<li>'.
                                $subtitulos['contenido']
                            .'</li>';                                   
                        }         
                    $html.='</ul>';                    
                $html.='</td>';
            $html.='</tr>';        
        }
        $html.='</table>';
       
        return $html;
    }
    //6.-
    private function obtener_actividad_aprendizaje_pai($idGrupoInter)
    {
        $html = $this->devolver_campo_respuesta('ACCIÓN',6);
        
        return $html;
    }
    //7
    private function proceso_aprendizaje_interdisciplinar()
    {
         /*
            Creado Por: Santiago / Fecha Creacion: 2023-03-08 
            Modificado Por: 	/ Fecha Modificación:
            Detalle: Muestra la tabla principal, que se imprime en el PDF, Llamando a los diferentes metodos para completar el proceso 7
        */
        $idGrupoInter= $this->grupoPlanInterdisciplinar;
        $seccion = '7';
        $respusta = '';
        $campo = 'EXPERIENCIAS DE APRENDIZAJE Y ESTRATEGIAS DE ENSEÑANZA INTERDISCIPLINARIOS';
         //extraemos el Id de la pregunta
         $modelPreguntaContenido = IsmContenidoPlanInterdiciplinar::find()
         ->where(['id_seccion_interdiciplinar' => $seccion])
         ->andWhere(['nombre_campo' => $campo])
         ->andWhere(['activo' => true])
         ->andWhere(['heredado' => false])
         ->one();

        //Extraemos la respuesta
        $modelRespuesta = IsmRespuestaPlanInterdiciplinar::find()
            ->where(['id_grupo_plan_inter' => $idGrupoInter])
            ->andwhere(['id_contenido_plan_inter' => $modelPreguntaContenido->id])
            ->one();
        if($modelRespuesta)
        {
            $respusta=$modelRespuesta->respuesta;
        }

     $html = "";
        $html ='<br>
                <table border="1" width="800px" cellspacing="0" cellpadding="5" style="font-size:10">
                        <tr >
                            <td colspan="2">
                                <b>7.- PROCESO DE APRENDIZAJE INTERDISCIPLINARIO:</b> 
                            </td>               
                        </tr>
                        <tr >
                            <td width="400px">
                                <b>EXPERIENCIAS DE APRENDIZAJE Y ESTRATEGIAS DE ENSEÑANZA INTERDISCIPLINAR</b> 
                            </td> 
                            <td width="400px">
                                <b>ATENCIÓN A LAS NECESIDADES EDUCATIVAS ESPECIALES:</b> (Detalle las estrategias de trabajo a realizar para cada caso, 
                                las especificadas por el Tutor Psicólogo y las propias de su asignatura o enseñanza )
                            </td>               
                        </tr>   
                        <tr >
                            <td width="400px">'.
                                $respusta
                            .'</td> 
                            <td width="400px">'.
                                $this->html_necesidades_especiales()                                
                            .'</td>               
                        </tr> ';  
        $html.='</table>';

        return $html;

    }
    
    //7
    private function html_necesidades_especiales()
    {
        /*
            Creado Por: Santiago / Fecha Creacion: 2023-03-08 
            Modificado Por: 	/ Fecha Modificación:
            Detalle: Recopila la información de cada Grado nee, y lo une en una sola variable, para presentar en el PDF
        */
        $idIsmGrupoInter= $this->grupoPlanInterdisciplinar;
        $planUnidadId = $this->idPlanUndiad;
        $html ='';      
       
        $planUnidad   = PlanificacionBloquesUnidad::findOne($planUnidadId);
        $curso =$planUnidad ->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->id;
        $id_periodo = Yii::$app->user->identity->periodo_id;
        $usuario= Yii::$app->user->identity->usuario;
        $bloqueid = $planUnidad->curriculoBloque->id;
        $modelGrupoInte = IsmGrupoMateriaPlanInterdiciplinar::find()
        ->where(['id_grupo_plan_inter'=>$idIsmGrupoInter])
        ->all();

        $idsMaterias ='-1';
        foreach($modelGrupoInte as $model)
        {
            $idsMaterias = $idsMaterias.','.$model->ismAreaMateria->materia_id;             
        }
        //Grado 1
        $resp = $this->obtener_curso_materias_estudiante_nee($usuario,$id_periodo,$curso,'1',$idsMaterias);
        $html .= $this->html_estudiantes_nee($resp,$bloqueid,'1');
        //Grado 2
        $resp = $this->obtener_curso_materias_estudiante_nee($usuario,$id_periodo,$curso,'2',$idsMaterias);
        $html .= $this->html_estudiantes_nee($resp,$bloqueid,'2');
        //Grado 3
        $resp = $this->obtener_curso_materias_estudiante_nee($usuario,$id_periodo,$curso,'3',$idsMaterias);
        $html .= $this->html_estudiantes_nee($resp,$bloqueid,'3');

        return $html;
    }
    //7
    private function html_estudiantes_nee($arrayAlumnos,$bloqueid,$grado)
    {
        /*
            Creado Por: Santiago / Fecha Creacion: 2023-03-08 
            Modificado Por: 	/ Fecha Modificación:
            Detalle: Genera la estructura de la tabla, para presentar los Estudiantes NEE, y sus adaptaciones, extrae directo de la información 
            ingresada en módulo ADAPTACION CURRICULAR
        */
        $html='';
        foreach($arrayAlumnos as $array)
        { 
            $model = AdaptacionCurricularXBloque::find()
            ->where(['id_nee_x_clase'=>$array['idneexclase']])
            ->andWhere(['id_curriculum_mec_bloque'=>$bloqueid])
            ->one();

            $adaptacion ='';
            if($model){ $adaptacion =  $model->adaptacion_curricular;}      

            $html .= '<div class="content">';
                $html .= '<div class="card" style="width: 100%; margin-top:20px">';
                    $html .= '<h5>GRADO '.$grado.'</h5>';
                    $html .= '<table class="table table-condensed table-bordered">';
                        $html .= '<thead>';
                            $html .= '<tr >';
                                $html .= '<th class="text-center" style="background-color: grey; color: white;width: 10%;">Materia</th>';
                                $html .= '<th class="text-center" style="background-color: grey; color: white;width: 40%;">Estudiante</th>';
                                $html .= '<th class="text-center" style="background-color: grey; color: white;width: 10%;">Diagnostico</th>';
                                $html .= '<th class="text-center" style="background-color: grey; color: white;width: 40%;">Adaptación</th>';
                            $html .= '</tr>';
                        $html .= '</thead>';
                        $html .= '<tbody id="table-reflexion-selecionadas">';
                            $html .= '<tr>';
                                $html .= '<td>'.$array['materia'].'</td>';
                                $html .= '<td>'.ltrim(rtrim($array['estudiante'])).'</td>';
                                $html .= '<td>'.$array['diagnostico_inicia'].'</td>';
                                $html .= '<td>'.$adaptacion.'</td>';
                            $html .= '</tr>';
                        $html .= '</tbody>';
                    $html .= '</table>';
                $html .= '</div>';
            $html .= '</div>';
        }

        return $html;
    }
    //7
    public function obtener_curso_materias_estudiante_nee($usuario,$id_periodo,$curso/*op_course_paralelo*/,$grado,$ids_materias )
    {
        $con = Yii::$app->db;
        $query = "select  im.id as idMateria, im.nombre as materia, ocp.id as idcurso,ocp.name as curso, os.id as idEstudiante,
                    concat(os.last_name,' ',os.middle_name,' ',os.first_name)  as estudiante ,
                    nxc.id as idNeeXClase, nxc.clase_id as neeClaseId,nxc.nee_id as idnee,nxc.grado_nee,nxc.fecha_inicia ,
                    nxc.fecha_finaliza ,nxc.diagnostico_inicia 
                    from nee_x_clase nxc 
                    inner join scholaris_clase sc on sc.id = nxc.clase_id 
                    inner join op_course_paralelo ocp on ocp.id = sc.paralelo_id 
                    inner join op_course oc on oc.id = ocp.course_id 
                    inner join ism_area_materia iam on iam.id = sc.ism_area_materia_id 
                    inner join ism_malla_area ima on ima.id = iam.malla_area_id 
                    inner join ism_periodo_malla ipm on ipm.id  = ima.periodo_malla_id 
                    inner join nee n on n.id = nxc.nee_id 
                    inner join op_student os on os.id = n.student_id  
                    inner join op_faculty of2 on of2.id = sc.idprofesor 
                    inner join res_users ru on ru.partner_id = of2.partner_id 
                    inner join ism_materia im on im.id = iam.materia_id  
                    where oc.x_template_id ='$curso' and ipm.scholaris_periodo_id = $id_periodo                    
                    and im.id in ($ids_materias) and grado_nee = $grado
                    order by im.nombre,ocp.name,estudiante;"; 

        
                            

        $resp = $con->createCommand($query)->queryAll();  
        // echo '<pre>';
        // print_r($query);
        // die();
        return $resp;
    }



    //8
    private function recursos()
    {        
        $html ='<br>
        <table border="1" width="100%" cellspacing="0" cellpadding="5" style="font-size:10">
                        <tr >
                            <td colspan="2"><b>8.- RECURSOS:</b> En esta sección especificar claramente cada recurso que se utilizará. 
                                    Podría mejorarse incluyendo recursos que pudieran utilizarse para llevar a cabo la diferenciación, 
                                    así como también agregando, por ejemplo, oradores y entornos que pudieran generar mayor profundidad 
                                    en el trabajo reflexivo sobre el enunciado de la unidad.</td>               
                        </tr>';                      
                        $html.=$this->muestra_recursos('BIBLIOGRÁFICO'); 
                        $html.=$this->muestra_recursos('TECNOLÓGICO');
                        $html.=$this->muestra_recursos('OTROS');   
        $html.='</table>';

        return $html;

    }
    //8
    private function muestra_recursos($recurso)
    {
        $idGrupoPlanInter= $this->grupoPlanInterdisciplinar;
        $modelRecurso1 = IsmContenidoPlanInterdiciplinar::find()
        ->where(['nombre_campo'=>$recurso])
        ->andWhere(['activo'=>true])
        ->andWhere(['id_seccion_interdiciplinar'=>8])
        ->one();

        $modelRespuesta = IsmRespuestaPlanInterdiciplinar::find()
        ->where(['id_grupo_plan_inter'=>$idGrupoPlanInter])
        ->andWhere(['id_contenido_plan_inter'=>$modelRecurso1->id])
        ->one();

        $html='';
        $html.='<tr><td width="25%">'.$recurso.'</td><td width="80%">'.$modelRespuesta->respuesta.'</td></tr>';

        return  $html;
    }
    //9
    private function reflexion()
    {
        $html ='<br>
        <table border="1" width="100%" cellspacing="0" cellpadding="5" style="font-size:10">
                        <tr >
                            <td colspan="3"><b>9.	REFLEXIÓN: </b>CONSIDERACIÓN DE LA PLANIFICACIÓN, EL PROCESO Y EL IMPACTO DE LA INDAGACIÓN 
                            INTERDISCIPLINARIA</td>               
                        </tr>';  
                $html.='<tr>
                            <td>ANTES DE ENSEÑAR LA UNIDAD</td>
                            <td>MIENTRAS SE ENSEÑA LA UNIDAD</td>
                            <td>DESPUÉS DE ENSEÑAR LA UNIDAD</td>
                        </tr>';
                $html.='<tr>';
                        $html.='<td>'.$this->muestra_reflexion('ANTES').'</td>';
                        $html.='<td>'.$this->muestra_reflexion('MIENTRAS').'</td>';
                        $html.='<td>'.$this->muestra_reflexion('DESPUES').'</td>';
                $html.='</tr>';
        $html.='</table>';

        return $html;
    }

    private function muestra_reflexion($reflexion)
    {        

        $modelContenido = IsmContenidoPlanInterdiciplinar::find()
        ->where(['nombre_campo'=>$reflexion])
        ->andWhere(['activo'=>true])
        ->andWhere(['id_seccion_interdiciplinar'=>9])
        ->one();

        $idGrupoPlanInter= $this->grupoPlanInterdisciplinar;

        $modelRespPlanInter = IsmRespuestaPlanInterdiciplinar::find()
        ->where(['id_grupo_plan_inter'=>$idGrupoPlanInter])
        ->andWhere(['id_contenido_plan_inter'=>$modelContenido->id])
        ->one();

        $modelReflexion = IsmRespuestaReflexionPaiInterdiciplinar::find()
        ->where(['id_respuesta_plan_inter_pai'=>$modelRespPlanInter->id])
        ->all();

        $html='';
        $html.='<ul>';

        foreach($modelReflexion as $model)
        {
            $html.='<li>';
                $html.='<b>'.$model->planificacionOpciones->opcion.'</b>';
                $html .='<br>';
                $html.=$model->respuesta;
            $html.='</li>' ; 
        }
        $html.='</ul>';

        // echo '<pre>';
        // print_r($modelContenido);
        //die();

        return $html;
    }



}
    
