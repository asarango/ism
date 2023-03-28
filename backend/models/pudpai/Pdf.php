<?php

namespace backend\models\pudpai;

use backend\models\IsmContenidoPaiPlanificacion;
use backend\models\pudpai\Evaluacion;
use backend\models\IsmCriterio;
use backend\models\IsmCriterioLiteral;
use backend\models\IsmLiteralDescriptores;
use backend\models\PlanificacionBloquesUnidad;
use backend\models\PlanificacionBloquesUnidadSubtitulo;
use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;
use backend\models\PlanificacionVerticalPaiDescriptores;
use backend\models\PlanificacionVerticalPaiOpciones;
use backend\models\PlanUnidadNee;
use backend\models\PudPai;
use backend\models\pudpep\DatosInformativos;
use backend\models\ScholarisPeriodo;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;

class Pdf extends \yii\db\ActiveRecord {

    private $planUnidad;
    private $pudPai;

    public function __construct($planUnidadId) {
        $this->planUnidad = PlanificacionBloquesUnidad::findOne($planUnidadId);

        $this->pudPai = PudPai::find()->where([
                    'planificacion_bloque_unidad_id' => $planUnidadId
                ])
                ->orderBy('seccion_numero')
                ->all();

        $this->generate_pdf();
    }

    private function generate_pdf() {
        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 40,
            'margin_bottom' => 10,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);

        $cabecera = $this->cabecera();
        //$pie = $this->genera_pie_pdf();

        $mpdf->SetHtmlHeader($cabecera);
        $mpdf->showImageErrors = true;

        $html = $this->cuerpo();
        
        $mpdf->WriteHTML($html);

        $piePagina=$this->piePagina();
        $mpdf->SetFooter($piePagina);  

        //$mpdf->Output('Planificacion-de-unidad' . '.pdf', 'D');
        $mpdf->Output();
        exit;
    }

    private function cabecera() {
        $codigoISO = 'ISOM20-22';
        $version ="6.0";
        $fecha=date('Y-m-d H:i:s'); 
        $fecha=date('Y-m-d'); 
        $fecha ='28/06/2022';
        $html = <<<EOT
         ' <table width="100%" cellspacing="0" cellpadding="10">
            <tr>
                <td class="border" align="center" width="20%">
                    <img src="imagenes/instituto/logo/logoISM1.png" width="70px"><br><font size = "1">Proceso Académico</font>
                </td>
                <td class="border" align="center" width=""></td>
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
        </table>'
        EOT;
        return $html;
    }
    private function piePagina()
    {
        $html =<<<EOP
        <table  width="100%">
            <tr>
                <td >Basado en el formato estipulado por el Bachillerato Internacional y modificado por el ISM</td>
                <td ><img src="imagenes/instituto/logo/logoISO.png" width="40px" align = "right"></td>
            </tr>
        </table>

        EOP;

        return   $html;     
    }

    private function cuerpo() 
    {
        $periodoId = Yii::$app->user->identity->periodo_id;
        $periodo = ScholarisPeriodo::findOne($periodoId);

        $html = $this->estilos();

        $html .= '<table width="100%" cellspacing="0" cellpadding="5" style ="font-size:10">';
        $html .= '<tr>';
        $html .= '<td class="border" align="center" style ="font-size:22"><b>ISM</b> <br> International Scholastic Model</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="border" align="center" style ="font-size:12"><b>PLAN DE UNIDAD PAI</b> <br> AÑO ESCOLAR ' . $periodo->codigo . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="border" align=""><b>1.- DATOS INFORMATIVOS</b></td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= $this->uno();
        $html .= '<br>';
        $html .= $this->dos();
        $html .= '<br>';
        $html .= $this->tres();
        $html .= '<br>';
        $html .= $this->cuatro();
        $html .= '<br>';
        $html .= $this->cinco();
        $html .= '<br>';
        $html .= $this->seis();
        $html .= '<br>';
        $html .= $this->siete(); 
        $html .= '<br>';
        $html .= $this->ocho();
        $html .= '<br>';
        $html .= $this->nueve();
        $html .= '<br>';
        $html .= $this->diez();
        $html .= '<br>';
        $html .= $this->once();      
        return $html;
    }

    private function uno() {

        $datos = new Datos($this->planUnidad->id);
        //$tiempo = $datos->calcula_horas($this->planUnidad->planCabecera->scholaris_materia_id,
        $tiempo = $datos->calcula_horas($this->planUnidad->planCabecera->ismAreaMateria->materia_id,
                $this->planUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->op_course_template_id);

        $docentes = $datos->get_docentes();

        $html = '';
        $html .= '<table class="tamano10" width="100%" cellspacing="0" cellpadding="2">';
        $html .= '<tr>';
        $html .= '<td class="border" align="" width="10%"><b>GRUPO DE ASIGNATURAS Y DISCIPLINA: </b></td>';
        $html .= '<td class="border" align="center">';
        $html .= $this->planUnidad->planCabecera->ismAreaMateria->materia->nombre;
        $html .= '</td>';
        $html .= '<td class="border" align="center" width="10%"><b>UNIDAD Nº: </b>';
        $html .= '<td class="border" align="center">' . $this->planUnidad->curriculoBloque->last_name . '</td>';
        $html .= '</td>';
        $html .= '<td class="border" align="center" width="10%"><b>AÑO DEL PAI: </b>';
        $html .= '<td class="border" align="center">' . $this->planUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->name . '</td>';
        $html .= '</td>';
        $html .= '<td class="border" align="center" width="10%"><b>FECHA DE INICIO: </b>';
        $html .= '<td class="border" align="center">' . substr($this->planUnidad->fecha_inicio,0,10). '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border" align="center" width="10%"><b>PROFESOR </b>';
        $html .= '<td class="border" align="center">';
        foreach ($docentes as $docente) {
            $html .= $docente['docente'] . ' | ';
        }
        $html .= '</td>';
        $html .= '<td class="border" align="center" width="10%"><b>TÍTULO DE LA UNIDAD Nº </b>';
        $html .= '<td class="border" align="center">' . $this->planUnidad->unit_title . '</td>';
        $html .= '<td class="border" align="center" width="10%"><b>DURACIÓN DE LA UNIDAD (EN HORAS) </b>';
        $html .= '<td class="border" align="center">' .$this->planUnidad->horas. '</td>';
        $html .= '<td class="border" align="center" width="10%"><b>FECHA FINALIZACIÓN: </b>';
        $html .= '<td class="border" align="center">' . substr($this->planUnidad->fecha_fin,0,10) . '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }

    private function dos() {

        $conceptos = PlanificacionVerticalPaiOpciones::find()->where([
                    'plan_unidad_id' => $this->planUnidad->id
                ])
                ->orderBy('tipo', 'contenido')
                ->all();

        $html = '';
        $html .= '<table class="" width="100%" cellspacing="0" cellpadding="5" style ="font-size:10">';
        $html .= '<tr>';
        $html .= '<td class="border" align=""><b>2.- INDAGACIÓN: ESTABLECIMIENTO DEL PROPÓSITO DE LA UNIDAD</b></td>';
        $html .= '</tr>';
        $html .= '</table>';

        $html .= '<table class="tamano10" width="100%" cellspacing="0" cellpadding="2">';
        $html .= '<tr>';
        $html .= '<td class="border" align="center" width="33%"><b>CONCEPTO CLAVE</b></td>';
        $html .= '<td class="border" align="center" width="33%"><b>CONCEPTO(S) RELACIONADO(S)</b></td>';
        $html .= '<td class="border" align="center" width="34%"><b>CONTEXTO GLOBAL</b></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="border" align="center">';
        $html .= '<ul>';
        foreach ($conceptos as $clave) {
            if ($clave->tipo == 'concepto_clave') {
                $html .= '<li>';
                $html .= $clave->contenido;
                $html .= '</li>';
            }
        }
        $html .= '</ul>';
        $html .= '</td>';

        $html .= '<td class="border" align="center">';
        $html .= '<ul>';
        foreach ($conceptos as $clave) {
            if ($clave->tipo == 'concepto_relacionado') {
                $html .= '<li>';
                $html .= $clave->contenido;
                $html .= '</li>';
            }
        }
        $html .= '</ul>';
        $html .= '</td>';

        $html .= '<td class="border" align="center">';
        $html .= '<ul>';
        foreach ($conceptos as $clave) {
            if ($clave->tipo == 'contexto_global') {
                $html .= '<li>';
                $html .= $clave->contenido;
                $html .= '</li>';
            }
        }
        $html .= '</ul>';
        $html .= '</td>';

        $html .= '</tr>';
        $html .= '</table>';

        $html .= '<table class="tamano10" width="100%" cellspacing="0" cellpadding="5">';
        $html .= '<tr>';
        $html .= '<td class="border" align="center"><b style="color: #000">ENUNCIADO DE LA INDAGACIÓN: </b>
        (expresa claramente una comprensión conceptual importante que tiene un <b><u><i>profundo significado y un valor a largo plazo</i></u></b> para los alumnos. 
        Incluye claramente un concepto clave, conceptos relacionados y una exploración del contexto global específica, que da una perspectiva 
        creativa y compleja del mundo real; describe una comprensión <b><u><i>transferible</i></u></b> y a la vez 
        <b><u><i>importante para la asignatura;</i></u></b> establece un <b><u><i>propósito</i></u></b>
        claro para la </i></u></b>indagación</i></u></b>).
        </td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="border">' . $this->planUnidad->enunciado_indagacion . '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        $preguntas = PudPai::find()->where([
                    'in', 'tipo', ['facticas', 'conceptuales', 'debatibles']
                ])
                ->andWhere([
                    'planificacion_bloque_unidad_id' => $this->planUnidad->id
                ])
                ->all();

        $html .= '<table class="tamano10" width="100%" cellspacing="0" cellpadding="5">';
        $html .= '<tr>';
        $html .= '<td class="border" align="center" colspan="2"><b>PREGUNTAS DE INDAGACIÓN: </b>
        (inspiradas en el enunciado de indagación. Su fin es explorar el enunciado en mayor detalle. Ofrecen andamiajes).</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border"  width="20%"><b style="color: #000">Fácticas: </b>
        (se basan en conocimientos y datos, ayudan a comprender terminología del enunciado, facilitan la comprensión, se pueden buscar)</td>';
        $html .= '<td class="border">';
        $html .= $this->dos_recorre_preguntas($preguntas, 'facticas');
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border " width="20%"><b style="color: #000">Conceptuales: </b>(conectar los datos, comparar y contrastar, explorar contradicciones, comprensión  más  profunda,  transferir  a  otras situaciones, contextos e ideas, analizar y aplicar)</td>';
        $html .= '<td class="border">';
        $html .= $this->dos_recorre_preguntas($preguntas, 'conceptuales');
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border " width="20%"><b style="color: #000">Debatibles: </b>(promover la discusión, debatir una posición, explorar cuestiones importantes desde múltiples perspectivas, 
        deliberadamente polémicas, presentar tensión, evaluar)
        </td>';
        $html .= '<td class="border">';
        $html .= $this->dos_recorre_preguntas($preguntas, 'debatibles');
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '</table>';

        return $html;
    }

    private function dos_recorre_preguntas($preguntas, $tipo) {
        $html = '';

        foreach ($preguntas as $pregunta) {
            if ($pregunta->tipo == $tipo) {
                $html .= $pregunta->contenido . ' | ';
            }
        }

        return $html;
    }
      //4
      private function cuatro()
      {
          $idPlanUnidad=  $this->planUnidad->id;
          //buscamos el id, que corresponde a COMPETENCIA, de la seccion 4  EN ISM CONTwhENIDO_ PLNA INTERDICIPLINAR          
  
          $modelIsmContenidoPaiPlan = IsmContenidoPaiPlanificacion::find()
          ->where(['planificacion_bloque_unidad_id'=>$idPlanUnidad])
          ->all();        
         
          $html ='<table class="tamano10" width="100%" cellspacing="0" cellpadding="2">
                          <tr >
                              <td class="border" colspan="4"><b>4.- OBJETIVO DEL DESARROLLO SOSTENIBLE</b></td>               
                          </tr>;                       
                          <tr >
                              <td class="border" width="25%"><b>COMPETENCIA</b></td>   
                              <td class="border" width="25%"><b>ACTIVIDAD</b></td>
                              <td class="border" width="25%"><b>OBJETIVO</b></td>
                              <td class="border" width="25%"><b>RELACION ODS-IB</b></td>            
                          </tr>';
                   
                              foreach($modelIsmContenidoPaiPlan as $model)
                              {
                                  $html.='<tr >';
                                  $html.='<td class="border">'.$model->contenido.'</td>   
                                          <td class="border">'.$model->actividad.'</td>
                                          <td class="border">'.$model->objetivo.'</td>
                                          <td class="border">'.$model->relacion_ods.'</td>';
                                  $html.='</tr>';
                              }
                  
          $html.='</table>';
  
          return $html;
  
      }

    public function cinco() 
    {
        
        $planUnidadBloq = $this->planUnidad->id;
        $objEspecificos = $this->consultar_objetivos_especificos($planUnidadBloq);

        $campoSumativa = $this->mostrar_campos_evaluacion('eval_sumativa'); 
        $campoFormativa = $this->mostrar_campos_evaluacion('eval_formativa'); 
        $campoRelacion= $this->mostrar_campos_evaluacion('relacion-suma-eval'); 

        $html = '';

        $html .= '<table class="tamano10" width="100%" cellspacing="0" cellpadding="2">';
        $html .= '<tr>';
            $html .= '<td class="border text-center" colspan="3"><b>5. EVALUACIÓN</b></td>';
        $html .= '</tr>';
        

        $html .= '<tr>';
            $html .= '<td class="border" colspan="1" width="33%" align="center"><b style="color: #000">OBJETVOS ESPECÍFICOS Y ASPECTOS: 
                            </b><br>(copiar la redacción tal como aparece en la guía  de  la  asignatura,  para  cada  año  del PAI)
                           
                      </td>';
            $html .= '<td class="border" align="center" width="33%"><b>EVALUACIÓN</b></td>';
          
            $html .= '<td class="border" width="33%" align="center"><b>RELACION ENTRE LAS TAREAS DE EVALUACIÓN
                            SUMATIVAS Y EL ENUNCIADO DE LA INDAGACIÓN: </b>
                            <br>
                            Relación entre las tareas de evaluación formativa y sumativa con base en el enunciado de la indagación. 
                            Resumen de las tareas de evaluación formativa y sumativa con base en los criterios de evaluación correspondientes.
                       </td>';
        $html .= '</tr>';

        $html .= '<tr>';
                $html .= '<td class="border ">' .  $objEspecificos. '</td>';
                $html .= '<td class="border" valign="top">';
                        $html .= '<p class="colorAyudas">Resumen de las tareas de evaluación sumativa y criterios de evaluación correspondientes:</p><br>';
                        $html .= $this->evaluacion_sumativas();
                // $html .= '</td>';
                // $html .= '<td class="border" valign="top">';                        
                        $html .='<table >
                                    <tr>
                                        <td ><b>EVALUACIÓN FORMATIVA</b><br><br>
                                        '.$campoFormativa.'<hr>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td ><b>EVALUACIÓN SUMATIVA</b><br><br>
                                            '.$campoSumativa.'
                                        </td>
                                    </tr>
                                 </table>';
                $html .= '</td>';
                $html .= '<td class="border" valign="top">';                       
                        $html .= $campoRelacion;
                $html .= '</td>';
        $html .= '</tr>';

        $html .= '</table>';

        return $html;
    }
    //objetivos especificos y aspectos del pai
    //importante: este metodo es la copia del metodo en la clase PUDPAI/EVALUACION.PHP
    public function consultar_objetivos_especificos($planUnidadBloq)
    {
        $arrayCriterios = array();
        $con = yii::$app->db;       
        $criterios = '<p style = "text-align: left">'; 

        $query = "select * from ism_criterio_descriptor_area icda 
        where id in (select descriptor_id from planificacion_vertical_pai_descriptores pvpd 
        where plan_unidad_id =$planUnidadBloq order by descriptor_id )
        order by id_criterio ;";

        $respuesta = $con->createCommand($query)->queryAll();
        foreach($respuesta as $resp)
        {
            if(in_array($resp['id_literal_criterio'],$arrayCriterios,true))
            {
                //NO SE PRODUCE NADA;
            }else{
                $arrayCriterios[]=$resp['id_literal_criterio'];
            }
        }
        
        foreach ($arrayCriterios as $criterio)
        {
            //consulta descriptor y literal descriptor
            $literalCriterio = IsmCriterioLiteral::findOne($criterio);            
            $criterios .= '<b>'.$literalCriterio->criterio->nombre.' - '. $literalCriterio->nombre_espanol.'</b>
            <br><br>'; 
            foreach($respuesta as $resp2)            {
                if($criterio==$resp2['id_literal_criterio'])
                {   
                    $descriptor = IsmLiteralDescriptores::findOne($resp2['id_literal_descriptor']);
                    $criterios .=  $descriptor->descripcion ;  
                    $criterios .= '<br><br>';                                      
                }
            }            
        }         
        $criterios .= '</p>'; 
        return  $criterios;
    }

    private function consulta_sumativas() {
        $objetivos = PlanificacionVerticalPaiDescriptores::find()
                ->innerJoin('scholaris_criterio_descriptor d', 'd.id = planificacion_vertical_pai_descriptores.descriptor_id')
                ->innerJoin('scholaris_criterio c', 'c.id = d.criterio_id')
                ->where([
                    'plan_unidad_id' => $this->planUnidad->id
                ])
                ->orderBy('c.criterio')
                ->all();

        $html = '';
        $html .= '<ul>';
        foreach ($objetivos as $obj) {
            $html .= '<li>' . '<b>CRITERIO ' . $obj->descriptor->criterio->criterio . ': </b> ' . $obj->descriptor->descricpcion . '</li>';
            $html .= '<br>';
        }
        $html .= '</ul>';
        return $html;
    }

    private function evaluacion_sumativas() {
        $planUnidadId = $this->planUnidad->id;

        $con = Yii::$app->db;
        $query = "select 	p.id
                            ,c.criterio 
                            ,p.titulo 
                            ,p.contenido 
                    from 	pud_pai p
                            inner join scholaris_criterio c on c.id = p.criterio_id 
                    where 	p.tipo = 'eval_sumativa'
                            and p.planificacion_bloque_unidad_id = $planUnidadId 
                    order by c.criterio;";
        $sumativas = $con->createCommand($query)->queryAll();

        $html = '';
        $html .= '<ul>';
        foreach ($sumativas as $sumativa) {
            if ($sumativa['contenido'] == 'sin contenido') {
                $color = 'red';
                $titulo = 'SIN TITULO';
            } else {
                $color = '';
                $titulo = $sumativa['titulo'];
            }

            $html .= '<div class="" style="color: ' . $color . '">';
            //$html .= $this->modal_sumativa($sumativa['id'], $sumativa['contenido'], $titulo);
            $html .= '<b>Criterio ' . $sumativa['criterio'] . '</b>: ' . $titulo . '<br>';
            $html .= $sumativa['contenido'] . '<br>';
            $html .= '</div>';
        }
        $html .= '</ul>';
        return $html;
    }

    private function mostrar_campos_evaluacion($tipo) 
    {
        $planUnidadId = $this->planUnidad->id;

        $model = PudPai::find()->where([
                    'planificacion_bloque_unidad_id' => $planUnidadId,
                    'tipo' => $tipo
                ])->one();

        $html = '';
        $html .= '<p>';

        $model ? $html .= $model->contenido: $html .= '';
        
        $html .= '</p>';

        return $html;
    }

    private function tres() {

        $aspectosClass = new Aspecto($this->planUnidad->id);
        $indicadoresClass = new Indicadores($this->planUnidad->id);

        $habilidades = $this->cuatro_get_hablidades();
        $aspectos = $aspectosClass->get_hablidades();
        $indicadores = $indicadoresClass->get_hablidades();

        $html = '';

        $html .= '<table class="tamano10" width="100%" cellspacing="0" cellpadding="5">';
            $html .= '<tr>';
                    $html .= '<td class="border" colspan="6"><b>3. ENFOQUES DEl APRENDIZAJE / HABILIDADES</b></td>';
            $html .= '</tr>';

            $html .= '<tr>';
                    $html .= '<td width="25%" align="center" class="border"><b>HABILIDAD</b></td>';
                    $html .= '<td width="25%" align="center" class="border"><b>EXPLORACIÓN</b></td>';
                    $html .= '<td width="25%" align="center" class="border"><b>ACTIVIDAD</b></td>';
                    $html .= '<td width="25%" align="center" class="border"><b>ATRIBUTOS DEL PERFIL</b></td>';
            $html .= '</tr>';
           
                $html .=  $this->tipo_habilidades();
        $html .= '</table>';

        // $html .= '<tr>';      
        //     $html .= '<td class="border">' . $this->cuatro_busca_habilidades($habilidades, 'HABILIDADES DE COMUNICACIÓN') . '</td>';
        //     $html .= '<td class="border">' . $this->cuatro_busca_habilidades($habilidades, 'HABILIDADES DE SOCIALES') . '</td>';
        //     $html .= '<td class="border">' . $this->cuatro_busca_habilidades($habilidades, 'HABILIDADES DE AUTOGESTIÓN') . '</td>';
        //     $html .= '<td class="border">' . $this->cuatro_busca_habilidades($habilidades, 'HABILIDADES DE INVESTIGACIÓN') . '</td>';
        //     $html .= '<td class="border">' . $this->cuatro_busca_habilidades($habilidades, 'HABILIDADES DE PENSAMIENTO') . '</td>';
        // $html .= '</tr>';

        // $html .= '<tr>';
        // $html .= '<td class="border"><b>Aspecto del Objetivo</b></td>';
        // $html .= '<td class="border">' . $this->cuatro_busca_habilidades($aspectos, 'HABILIDADES DE COMUNICACIÓN') . '</td>';
        // $html .= '<td class="border">' . $this->cuatro_busca_habilidades($aspectos, 'HABILIDADES DE SOCIALES') . '</td>';
        // $html .= '<td class="border">' . $this->cuatro_busca_habilidades($aspectos, 'HABILIDADES DE AUTOGESTIÓN') . '</td>';
        // $html .= '<td class="border">' . $this->cuatro_busca_habilidades($aspectos, 'HABILIDADES DE INVESTIGACIÓN') . '</td>';
        // $html .= '<td class="border">' . $this->cuatro_busca_habilidades($aspectos, 'HABILIDADES DE PENSAMIENTO') . '</td>';
        // $html .= '</tr>';

        // $html .= '<tr>';
        // $html .= '<td class="border"><b>Indicadores de la habilidad</b></td>';
        // $html .= '<td class="border">' . $this->cuatro_busca_habilidades($indicadores, 'HABILIDADES DE COMUNICACIÓN') . '</td>';
        // $html .= '<td class="border">' . $this->cuatro_busca_habilidades($indicadores, 'HABILIDADES DE SOCIALES') . '</td>';
        // $html .= '<td class="border">' . $this->cuatro_busca_habilidades($indicadores, 'HABILIDADES DE AUTOGESTIÓN') . '</td>';
        // $html .= '<td class="border">' . $this->cuatro_busca_habilidades($indicadores, 'HABILIDADES DE INVESTIGACIÓN') . '</td>';
        // $html .= '<td class="border">' . $this->cuatro_busca_habilidades($indicadores, 'HABILIDADES DE PENSAMIENTO') . '</td>';
        // $html .= '</tr>';

        // $html .= '<tr>';
        // $html .= '<td class="border"><b>Cómo se enseñará explícitamente la habilidad (Actividades)</b></td>';
        // $html .= '<td class="border">' . $this->cuatro_busca_tipos_planificacion($this->pudPai, 'ensenara_comunicacion') . '</td>';
        // $html .= '<td class="border">' . $this->cuatro_busca_tipos_planificacion($this->pudPai, 'ensenara_sociales') . '</td>';
        // $html .= '<td class="border">' . $this->cuatro_busca_tipos_planificacion($this->pudPai, 'ensenara_autogestion') . '</td>';
        // $html .= '<td class="border">' . $this->cuatro_busca_tipos_planificacion($this->pudPai, 'ensenara_investigacion') . '</td>';
        // $html .= '<td class="border">' . $this->cuatro_busca_tipos_planificacion($this->pudPai, 'ensenara_pensamiento') . '</td>';
        // $html .= '</tr>';

        // $html .= '<tr>';
        // $html .= '<td class="border"><b>Perfil BI</b></td>';
        // $html .= '<td class="border">' . $this->cuatro_busca_tipos_planificacion($this->pudPai, 'comunicacion') . '</td>';
        // $html .= '<td class="border">' . $this->cuatro_busca_tipos_planificacion($this->pudPai, 'social') . '</td>';
        // $html .= '<td class="border">' . $this->cuatro_busca_tipos_planificacion($this->pudPai, 'autogestion') . '</td>';
        // $html .= '<td class="border">' . $this->cuatro_busca_tipos_planificacion($this->pudPai, 'investigacion') . '</td>';
        // $html .= '<td class="border">' . $this->cuatro_busca_tipos_planificacion($this->pudPai, 'pensamiento') . '</td>';
        // $html .= '</tr>';

        //$html .= '</table>';

        return $html;
    }

    private function cuatro_busca_tipos_planificacion($model, $tipo) {
        $html = '<ul>';
        foreach ($model as $data) {
            if ($data->tipo == $tipo) {
                $html .= $data->contenido . '<br>';
            }
        }
        $html .= '<ul>';

        return $html;
    }

    private function cuatro_busca_habilidades($habilidades, $tipo) {
        $html = '<ul>';
        foreach ($habilidades as $habilidad) {
            if ($habilidad['es_titulo1'] == $tipo) {
                $html .= '<li>' . $habilidad['contenido'] . '</li>';
            }
        }
        $html .= '</ul>';

        return $html;
    }
    private function get_habilidades()
    {
      $con = Yii::$app->db;
      $idBloqueUnidad = $this->planUnidad->id; 

      $query = "select 	h.es_titulo2  as contenido
                ,h.es_titulo1,h.es_exploracion,op.actividad,
                (select categoria  from planificacion_opciones po where id = op.id_pudpai_perfil)
                ,op.id_relacion,op.id
                from 	planificacion_vertical_pai_opciones op 
                inner join contenido_pai_habilidades h on h.es_exploracion = op.contenido 
                where 	op.plan_unidad_id = $idBloqueUnidad
                group  by h.es_titulo2,h.es_titulo1,h.es_exploracion,op.actividad,op.id_pudpai_perfil,op.id_relacion ,op.id 
                order by h.es_titulo1;";

      $res = $con->createCommand($query)->queryAll();
       
      return $res;
    }
    private function tipo_habilidades()
    {
        $habilidades = $this->get_habilidades();
        $html = '';
        
        foreach($habilidades as $hab)
        {
            $html .='<tr>'; 
                $html .='<td class="border">'; 
                    $html .=$hab['es_titulo1']; 
                $html .='</td>';

                $html .='<td class="border">'; 
                    $html .='* '.$hab['es_exploracion']; 
                $html .='</td>';

                $html .='<td class="border">';
                    $html .=$hab['actividad'];
                $html .='</td>';

                $html .='<td class="border">'; 
                    $html .=$hab['categoria']; 
                $html .='</td>';
            $html .='</tr>';          
        }        
      
       
        return $html;
    }  

    private function cuatro_get_hablidades() {
        $planUnidadId = $this->planUnidad->id;
        $con = Yii::$app->db;
        $query = "select 	h.es_titulo2  as contenido
                      ,h.es_titulo1 
                  from 	planificacion_vertical_pai_opciones op 
                      inner join contenido_pai_habilidades h on h.es_exploracion = op.contenido 
                  where 	op.plan_unidad_id = $planUnidadId
                  group  by h.es_titulo2,h.es_titulo1 
                  order by h.es_titulo2;";

        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function seis() {

        $contenidos = PlanificacionBloquesUnidadSubtitulo::find()
                ->where(['plan_unidad_id' => $this->planUnidad->id])
                ->orderBy('orden')
                ->all();

        $html = '';

        $html .= '<table class="tamano10" width="100%" cellspacing="0" cellpadding="3">';
        $html .= '<tr>';
        $html .= '<td class="border" colspan="4"><b>6. ACCIÓN: ENSEÑANZA Y APRENDIZAJE A TRAVÉS DE LA INDAGACIÓN</b></td>';
        $html .= '</tr>';
        $html .= '<tr>';
            $html .= '<td rowspan="2" class="border" align="center"><b> CONTENIDOS </b></td>';
            $html .= '<td rowspan="2" class="border" align="center"><b style="color: #000">VERIFICACIÓN </b>(SI/NO/REPLANIFICADO)</td>';
            $html .= '<td class="border" align="center" colspan="2"><b>PROCESO DE APRENDIZAJE</b></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border" align="center"><b style="color: #000">EXPERIENCIAS DE APRENDIZAJE Y ESTRATEGIAS DE ENSEÑANZA: </b>variedad que abarque el espectro de preferencias de los alumnos. Basadas en los conocimientos previos y en la indagación. (Todas las actividades a realizar en clase o para la casa)</td>';
        $html .= '<td class="border"><b style="color: #000">DIFERENCIACIÓN: </b>de contenido, de proceso (cómo se enseñará y se aprenderá) y de producto (lo que se evaluará). Definir las actividades correspondientes a los 3 diferentes estilos de aprendizaje más reconocidos: VISUAL, KINESTÉSICO, AUDITIVO.</td>';
        $html .= '</tr>';

        foreach ($contenidos as $contenido) {
            $html .= '<tr>';
            $html .= '<td class="border" align="center">' . $contenido->subtitulo . '</td>';
            $html .= '<td class="border" align="center">'.$contenido->verificacion.'</td>';
            $html .= '<td class="border">' . $contenido->experiencias . '</td>';
            //$html .= '<td class="border">' . $contenido->evaluacion_formativa . '</td>';
            $html .= '<td class="border">' . $contenido->diferenciacion . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';

        return $html;
    }

    public function siete() 
    {

        $categorias = $this->get_categoria($this->planUnidad->id);
        $acciones = \backend\models\PudPaiServicioAccion::find()->where([
                    'planificacion_bloque_unidad_id' => $this->planUnidad->id
                ])->all();          

        $html = '';    

        $html .= '<table class="tamano10 border" width="100%" cellspacing="0" cellpadding="5">';
            $html .= '<tr>';
                $html .= '<td class="border" colspan="6"><b style="color: #000">
                                7.	SERVICIO COMO ACCIÓN: </b>(Los tipos de acción son Servicio Directo, Servicio Indirecto, 
                                Promoción de una causa, Investigación, etc.)</td>';
            $html .= '</tr>';

            $html .= '<tr>';
                $html .= '<td class="border" align="center" rowspan="2"><b>TIPOS DE ACCION</b></td>';
                $html .= '<td class="border" align="center" rowspan="2"><b>ACTIVIDAD DE ACCIÓN</b></td>';
                $html .= '<td class="border" align="center" colspan="3"><b>SITUACIONES DE APRENDIZAJE</b></td>';
            $html .= '</tr>';

            $html .= '<tr>';
                $html .= '<td class="border" align="center"><b>PRESENCIAL</b></td>';
                $html .= '<td class="border" align="center"><b>EN LÍNEA</b></td>';
                $html .= '<td class="border" align="center"><b>COMBINADO</b></td>';
            $html .= '</tr>';

            
            
            foreach ($categorias as $cat) 
            {
                $categ = $cat['categoria'];
                 $html .= '<tr>';
                    $html .= '<td class="border" align="center">' . $categ. '</td>';
                    $html .= '<td class="border" align="center">';
                    foreach ($acciones as $acc) 
                    {
                         if ($acc->opcion->categoria == $cat['categoria']) 
                         {
                             $html .= '<ul>';
                             $html .= '<li>' . $acc->opcion->opcion . '</li>';
                             $html .= '</ul>';
                         }
                    }
                    $html .= '</td>';                    

                $presencial = $this->get_situacion_aprendizaje($this->planUnidad->id, $categ, 'presencial');
                    $html .= '<td align="center" class="border">';
                    if (!$presencial) {
                        $html .= '<i style="color: #ab0a3d"></i>';
                    } else {
                        $html .= '<i style="color: green">X</i>';
                    }
                    $html .= '</td>';                   

                $enLinea = $this->get_situacion_aprendizaje($this->planUnidad->id, $categ, 'en_linea');
                    $html .= '<td align="center" class="border">';
                    if (!$enLinea) {
                        $html .= '<i style="color: #ab0a3d"></i>';
                    } else {
                        $html .= '<i style="color: green">X</i>';
                    }
                    $html .= '</td>';                    

                $combinado = $this->get_situacion_aprendizaje($this->planUnidad->id, $categ, 'combinado');
                    $html .= '<td align="center" class="border">';
                    if (!$combinado) {
                        $html .= '<i style="color: #ab0a3d"></i>';
                    } else {
                        $html .= '<i style="color: green">X</i>';
                    }
                    $html .= '</td>';
                $html .= '</tr>';

               
            } //fin for
            

        $html .= '</table>';

     

        return $html;
    }

    private function get_categoria($planUnidadId) {
        $con = \Yii::$app->db;
        $query = "select 	op.categoria 
                    from 	pud_pai_servicio_accion p
                                    inner join planificacion_opciones op on op.id = p.opcion_id
                    where 	p.planificacion_bloque_unidad_id = $planUnidadId
                    group by op.categoria;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    private function get_situacion_aprendizaje($planUnidadId, $categoria, $opcion){
        $model = PudPai::find()->where([
            'planificacion_bloque_unidad_id' => $planUnidadId,
            'tipo' => $categoria,
            'contenido' => $opcion
        ])->one();
        
        if($model){
            return $model->id;
        }else{
            return false;
        }
    }
    
    
    //** inicio siete */
    private function buscar_nee_x_materia()
    {
       
        $idCurso = $this->planUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->id ;      
        $idMateria= $this->planUnidad->planCabecera->ismAreaMateria->materia->id ;
        $idPeriodo=  Yii::$app->user->identity->periodo_id; 
        $con = Yii::$app->db;

        $query = 'select 	s.id 
                            ,nxc.id as "idnxc"
                            ,nxc.clase_id as "idClase"';
                    $query .=",concat(s.first_name, ' ', s.middle_name, ' ', s.last_name) as student
                            ,nxc.grado_nee 
                            ,nxc.diagnostico_inicia 
                            ,nxc.diagnostico_finaliza 
                            ,nxc.recomendacion_clase 
                    from 	scholaris_clase cl
                            inner join op_course_paralelo pa on pa.id = cl.paralelo_id 
                            inner join op_course cu on cu.id = pa.course_id
                            inner join ism_area_materia am on am.id = cl.ism_area_materia_id 
                            inner join ism_malla_area ma on ma.id = am.malla_area_id 
                            inner join ism_periodo_malla pm on pm.id = ma.periodo_malla_id
                            inner join nee_x_clase nxc ON nxc.clase_id = cl.id 
                            inner join nee nee on nee.id = nxc.nee_id 
                            inner join op_student s on s.id = nee.student_id 
                    where 	cu.x_template_id = $idCurso
                            and pm.scholaris_periodo_id = $idPeriodo
                            and am.materia_id = $idMateria;";  
                                                 
        
        $resp = $con->createCommand($query)->queryAll();     
        return $resp;
    }
    private function getIniciales($nombre)
    {
        $name = '';
        $explode = explode(' ',$nombre);
        foreach($explode as $x){
            $name .=  $x[0];
        }
        
        return $name;    
    }
    private function devulve_lista_estudiante($arregloEstudiantes,$grado)
    {
        $html ='<ul>';
        foreach($arregloEstudiantes as $array)
        {
            if($array['grado_nee']==$grado)
            {
                $modelPNU = PlanUnidadNee::find()
                ->where(['nee_x_unidad_id'=>$array['idnxc'],'curriculo_bloque_unidad_id'=>1])
                ->one();               

                $iniciales = $this->getIniciales($array['student']);                
                $html .='<li>'; 
                    $html .='<b>'.$iniciales.': </b>'.'<b>Diagnóstico:</b> '.$array['diagnostico_inicia'].' / <b>Recomendación:</b> '.$array['recomendacion_clase']; 
                $html .='</li>'; 
               $html .='<li>'; 
                        $html .='<b>Plan Unidad Nee: </b>';
                        if($modelPNU){ $html.=$modelPNU->detalle_pai_dip; }
                $html .='</li>';   
                             
            }            
        }
        $html .='</ul>';
        return $html;
    }
    public function ocho() 
    {
        $estudiantesNee = $this->buscar_nee_x_materia();
        $html = '';

        $html .= '<table class="tamano10" width="100%" cellspacing="0" cellpadding="10">';
        $html .= '<tr>';
        $html .= '<td class="border" colspan="2"><b style="color: #000">8. ATENCIÓN A LAS NECESIDADES EDUCATIVAS ESPECIALES: </b>(Detalle  las estrategias de trabajo a realizar para cada caso, las especificadas por el Tutor Psicólogo  y las propias de su asignatura o enseñanza)</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td width="20%" class="border" align="center"><b>GRADO 1</b></td>';        
        $html .= '<td class="border">'.$this->devulve_lista_estudiante($estudiantesNee,1).'</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border" align="center"><b>GRADO 2</b></td>';
        $html .= '<td class="border">'.$this->devulve_lista_estudiante($estudiantesNee,2).'</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border" align="center"><b>GRADO 3</b></td>';
        $html .= '<td class="border">'.$this->devulve_lista_estudiante($estudiantesNee,3).'</td>';
        $html .= '</tr>';

        $html .= '</table>';

        return $html;
    }
    //** fin siete */

    public function nueve() {
        $html = '';

        $html .= '<table class="tamano10" width="100%" cellspacing="0" cellpadding="5">';
        $html .= '<tr>';
        $html .= '<td class="border" colspan="2"><b style="color: #000">9. RECURSOS: </b>
        <i>En esta sección especificar claramente cada recurso que se utilizará. Podría mejorarse incluyendo 
        recursos que pudieran utilizarse para llevar a cabo la diferenciación, así como también agregando, por ejemplo, oradores y 
        entornos que pudieran generar mayor profundidad en el trabajo reflexivo sobre el enunciado de la unidad</i>.</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td width="20%" class="border" align="center"><b>BIBLIOGRÁFICO: </b></td>';
        $html .= '<td class="border" >' . $this->busca_pud_pai('bibliografico') . '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border" align="center"><b>TECNOLÓGICO: </b></td>';
        $html .= '<td class="border" >' . $this->busca_pud_pai('tecnologico') . '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border" align="center"><b>OTROS: </b></td>';
        $html .= '<td class="border" >' . $this->busca_pud_pai('otros') . '</td>';
        $html .= '</tr>';

        $html .= '</table>';

        return $html;
    }

    public function diez() {
        $html = '';

        $html .= '<table class="tamano10" width="100%" cellspacing="0" cellpadding="5">';
        $html .= '<tr>';
        $html .= '<td class="border" colspan="3"><b style="color: #000">10. REFLEXIÓN: </b>
        <i>(Consideración de la planificación, el proceso y el impacto de la indagación. En el proceso de reflexión, 
        garantizar dar respuesta a varias de la preguntas planteadas en cada momento.)</i></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td width="33%" class="border" align="center"><b>ANTES DE ENSEÑAR LA UNIDAD</b></td>';
        $html .= '<td width="33%" class="border" align="center"><b>MIENTRAS SE ENSEÑA LA UNIDAD</b></td>';
        $html .= '<td width="34%" class="border" align="center"><b>DESPUÉS DE ENSEÑAR LA UNIDAD</b></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border" valign="top">' . $this->busca_pud_pai('antes') . '</td>';
        $html .= '<td class="border" valign="top">' . $this->busca_pud_pai('mientras') . '</td>';
        $html .= '<td class="border" valign="top">' . $this->busca_pud_pai('despues') . '</td>';
        $html .= '</tr>';

        $html .= '</table>';

        return $html;
    }

    private function busca_pud_pai($tipo) {
        $html = '';
        foreach ($this->pudPai as $pud) {
            if ($pud->tipo == $tipo && ($tipo == 'antes' || $tipo == 'mientras' || $tipo == 'despues')) {
                $html .= '* ' . $pud->contenido . '<br>';
                $html .= $pud->respuesta . '<br>';
            } elseif ($pud->tipo == $tipo) {
                $html .= $pud->contenido;
            }
        }

        return $html;
    }

    public function once() {
        $html = '';

        $html .= '<table class="tamano10" width="100%" cellspacing="0" cellpadding="5">';
        $html .= '<tr>';
        $html .= '<td class="border colorAyudas" colspan="3"><b style="color: #000">11. FIRMAS DE RESPONSABILIDAD</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td width="33%" class="border" align="center"><b>FIRMAS DE DOCENTE</b></td>';
        $html .= '<td width="33%" class="border" align="center"><b>FIRMAS DE JEFE DE ÁREA</b></td>';
        $html .= '<td width="34%" class="border" align="center"><b>FIRMAS DE COORDINACIÓN</b></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td height="80px" class="border"></td>';
        $html .= '<td class="border"></td>';
        $html .= '<td class="border"></td>';
        $html .= '</tr>';

        $html .= '</table>';

        return $html;
    }

    private function estilos() {
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

                .colorAyudas{
                    color: #65b2e8;
                }

                    ';
        $html .= '</style>';
        return $html;
    }

}

?>