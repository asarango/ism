<?php
namespace backend\models\interdiciplinarPai;


use backend\models\IsmGrupoMateriaPlanInterdiciplinar;
use backend\models\helpers\HelperGeneral;
use backend\models\IsmContenidoPlanInterdiciplinar;
use backend\models\IsmRespuestaContenidoPaiInterdiciplinar;
use backend\models\IsmRespuestaOpcionesPaiInterdiciplinar;
use backend\models\pudpai\Datos;
use backend\models\PlanificacionBloquesUnidad;
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
                            <td ><b>HABILIDAD</b></td>   
                            <td ><b>EXPLORACIÓN</b></td>
                            <td ><b>ACTIVIDAD</b></td>
                            <td ><b>ATRIBUTO DEL PERFIL</b></td>            
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


}
    
