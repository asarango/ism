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
use backend\models\PlanVerticalDiploma;
use backend\models\PlanVerticalDiplomaComponente;

class PdfNew extends \yii\db\ActiveRecord
{

    private $planCabecera;
    private $materiaId;
    private $unidades;
    private $componentes;

    public function __construct($cabeceraId)
    {
        $periodoId = Yii::$app->user->identity->periodo_id;
        $this->planCabecera = PlanVerticalDiploma::find()->where(['cabecera_id' => $cabeceraId])->all();
        $cabecera = PlanificacionDesagregacionCabecera::findOne($cabeceraId);
        $this->materiaId = $cabecera->ismAreaMateria->materia_id;

        $this->unidades = $this->get_planes_unidad($this->materiaId, $periodoId);

        $this->componentes = PlanVerticalDiplomaComponente::find()
            ->where(['cabecera_id' => $cabeceraId ])
            ->orderBy('id')
            ->all();

        $this->generate_pdf();
    }


    private function generate_pdf()
    {
        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 25,
            'margin_bottom' => 10,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);
        $cabecera = $this->cabecera();
        $mpdf->SetHtmlHeader($cabecera);
        $mpdf->showImageErrors = true;

        $html = $this->cuerpo();
        $html .= $this->firmas();
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
        $fecha = date('Y-m-d H:i:s');
        $fecha = date('Y-m-d');
        $html = <<<EOT
        <table width="100%" cellspacing="0" cellpadding="10"> 
            <tr> 
                <td class="" align="center" width="10%">
                    <img src="imagenes/instituto/logo/logoISM1.png" width="60px">
                </td>
                <td class="" align="center" width="" ></td>
                <td class="" align="right" width="10%">
                    <img src="imagenes/bi/bi.png" width="60px">
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
        <table width="100%" cellspacing="0" cellpadding="5">         
            <tr> 
                <td class="fondoGrisNormal border">ELABORADO POR:</td>
                <td class="fondoGrisNormal border">APROBADO POR:</td>                              
            </tr> 
            <tr> 
                <td class="border">Nombre:</td>                
                <td class="border">Nombre:</td>                
            </tr> 

            <tr> 
                <td class="border">Firma:</td>                
                <td class="border">Firma:</td>                
            </tr> 

            <tr> 
                <td class="border">Fecha:</td>                
                <td class="border">Fecha:</td>                
            </tr> 
        </table> 
        EOD;
        return $html;
    }



    /***FIN UNIDADES ITERACION TITULOS */

    private function cuerpo()
    {

        $html = $this->estilos();

        $html .= '<table class="border fondoGrisTitulo" width="1030px" cellpadding="0" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td align="center">
        <b>
            ISM <br>
            ESQUEMA DE ASIGNATURAS / PLAN ANUAL /PLAN VERTICAL<br>
            PROGRAMA DEL DIPLOMA
        </b>
        </td>';
        $html .= '</tr>';
        $html .= '</table>';

        $html .= $this->get_nombre_codigo();
        $html .= $this->get_nivel();
        $html .= $this->get_artes();
        $html .= $this->get_profesor();
        $html .= $this->get_completo_esquema();
        $html .= $this->get_clase_dura();
        $html .= $this->get_imprevistos();
        $html .= $this->get_ejes();

        /**** Para la parte 1 */
        $html .= $this->get_esquemas_curso();

        /** 2 COmponentes de evaluación */
        $html .= $this->get_componentes_evaluacion();
        
        /**  Vículos con TDC */
        $html .= $this->get_vinculos_tdc();
        
        /**  Enfoques de aprendizaje */
        $html .= $this->get_enfoques_aprendizaje();
    
        /**  5.	Mentalidad internacional */
        $html .= $this->get_mentalidad_internacional();

        /**  6.	Desarrollo del perfil de la comunidad de aprendizaje del IB  */
        $html .= $this->get_desarrollo_perfil();

         /** 7.	Instalaciones y equipos  */
         $html .= $this->get_equipos();

         /** 8. Otros recursos  */
         $html .= $this->get_otros_recursos();

         /** 9.	Bibliografía/Webgrafía. Utilizar normas APA (última edición)  */
         $html .= $this->get_bibliografia();



        return $html;
    }


    private function get_nombre_codigo()
    {

        $asignatura = $this->search_data_text('datos', 'asignatura');
        $colegio = $this->search_data_text('datos', 'colegio');

        $html = '<table width="" cellpadding="3" cellspacing="0" class="borderB">';
        $html .= '<tr>';
        $html .= '<td align=""
                    class="borderL fondoGrisNormal"
                    width="250px">
                    Nombre de la asignatura del Programa del Diploma (indique la lengua)
                </td>';

        $html .= '<td class="borderL" width="330px">' . $asignatura['contenido'] . '</td>';
        $html .= '<td class="borderL fondoGrisNormal" width="200px">Código del colegio</td>';
        $html .= '<td class="borderL borderR" width="250px">' . $colegio['contenido'] . '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }

    private function get_nivel()
    {

        $superior = $this->search_data_select('datos', 'nivel', 'Superior');
        $checkedSuperior = $superior['seleccion'] ? 'X' : '';

        $dosanios = $this->search_data_select('datos', 'nivel', 'Medio completado en dos años');
        $checkedDosAnios = $superior['seleccion'] ? 'X' : '';

        $unAnio = $this->search_data_select('datos', 'nivel', 'Medio completado en un año *');
        $checkedUnAnio = $superior['seleccion'] ? 'X' : '';

        $html = '<table width="" cellpadding="3" cellspacing="0" class="borderB">';
        $html .= '<tr>';
        $html .= '<td align=""
                    class="borderL fondoGrisNormal"
                    width="250px">
                    Nivel (marque con una X)
                </td>';

        $html .= '<td class="borderL fondoGrisNormal" width="105px">Superior</td>';
        $html .= '<td class="borderL borderR" width="25px" align="center">' . $checkedSuperior . '</td>';

        $html .= '<td class="borderL fondoGrisNormal" width="300px">Medio completado en dos anos</td>';
        $html .= '<td class="borderL borderR" width="25px" align="center">' . $checkedDosAnios . '</td>';

        $html .= '<td class="borderL fondoGrisNormal" width="300px">Medio completado en un año *</td>';
        $html .= '<td class="borderL borderR" width="25px" align="center">' . $checkedUnAnio . '</td>';

        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }


    private function get_artes()
    {

        $artes_visuales = $this->search_data_text('datos', 'artes_visuales');
        $artes_musica = $this->search_data_text('datos', 'artes_musica');

        $html = '<table width="" cellpadding="3" cellspacing="0" class="borderB">';
        $html .= '<tr>';
        $html .= '<td align=""
                    class="borderL fondoGrisNormal"
                    width="250px">
                    Completar solo para Artes
                </td>';

        $html .= '<td class="borderL" width="390px" align="center">'
            . '(Indique la opción o las opciones de Artes Visuales)<br>'
            . $artes_visuales['contenido'] .
            '</td>';

        $html .= '<td class="borderL borderR" width="390px" align="center">'
            . '(Indique la opción o las opciones de Música)<br>'
            . $artes_musica['contenido'] .
            '</td>';

        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }


    private function get_profesor()
    {

        $profesor = $this->search_data_text('datos', 'profesor');
        $fecha_cap = $this->search_data_text('datos', 'fecha_cap');

        $html = '<table width="" cellpadding="3" cellspacing="0" class="borderB">';
        $html .= '<tr>';
        $html .= '<td align=""
                    class="borderL fondoGrisNormal"
                    width="250px">
                    Nombre del profesor que completó este esquema
                </td>';

        $html .= '<td class="borderL" width="330px">' . $profesor['contenido'] . '</td>';
        $html .= '<td class="borderL fondoGrisNormal" width="200px">Fecha de capacitación del IB</td>';
        $html .= '<td class="borderL borderR" width="250px">' . $fecha_cap['contenido'] . '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }

    private function get_completo_esquema()
    {

        $fecha_completo = $this->search_data_text('datos', 'fecha_completo');
        $taller = $this->search_data_text('datos', 'taller');

        $html = '<table width="" cellpadding="3" cellspacing="0" class="borderB">';
        $html .= '<tr>';
        $html .= '<td align=""
                    class="borderL fondoGrisNormal"
                    width="250px">
                    Fecha en que se completó el esquema
                </td>';

        $html .= '<td class="borderL" width="330px">' . $fecha_completo['contenido'] . '</td>';
        $html .= '<td class="borderL fondoGrisNormal" width="200px">Nombre del taller
        (indique nombre de la asignatura y categoría del taller)
        </td>';
        $html .= '<td class="borderL borderR" width="250px">' . $taller['contenido'] . '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }


    private function get_clase_dura()
    {

        $clase_dura = $this->search_data_text('datos', 'clase_dura');
        $semana_hay = $this->search_data_text('datos', 'semana_hay');

        $html = '<table width="" cellpadding="3" cellspacing="0" class="borderB">';
        $html .= '<tr>';
        $html .= '<td align=""
                    class="borderL fondoGrisNormal"
                    width="250px">
                    Una clase dura:
                </td>';

        $html .= '<td class="borderL" width="330px">' . $clase_dura['contenido'] . '</td>';
        $html .= '<td class="borderL fondoGrisNormal" width="200px">En una semana hay:</td>';
        $html .= '<td class="borderL borderR" width="250px">' . $semana_hay['contenido'] . '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }

    private function get_imprevistos()
    {

        $imprevisto = $this->search_data_text('datos', 'imprevisto');
        $semanas_total = $this->search_data_text('datos', 'semanas_total');

        $html = '<table width="" cellpadding="3" cellspacing="0" class="borderB">';
        $html .= '<tr>';
        $html .= '<td align=""
                    class="borderL fondoGrisNormal"
                    width="250px">
                    Imprevistos:
                </td>';

        $html .= '<td class="borderL" width="330px">' . $imprevisto['contenido'] . '</td>';
        $html .= '<td class="borderL fondoGrisNormal" width="200px">Total de semanas clase:</td>';
        $html .= '<td class="borderL borderR" width="250px">' . $semanas_total['contenido'] . '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }


    private function get_ejes()
    {

        $ejes = $this->search_data_text('datos', 'ejes');


        $html = '<table width="" cellpadding="3" cellspacing="0" class="borderB">';
        $html .= '<tr>';
        $html .= '<td align=""
                    class="borderL fondoGrisNormal"
                    width="250px">
                    Imprevistos:
                </td>';

        $html .= '<td class="borderL borderR" width="780px">' . $ejes['contenido'] . '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }



    private function get_esquemas_curso()
    {
        $html = '<br><br><b>1.	Esquema del curso/Plan vertical:</b>';
        $html .= '<ul>';
        $html .= '<li>Utilice la siguiente tabla para organizar los temas que van a enseñarse en el curso. Si es necesario incluir temas que cubran otros requisitos (por ejemplo, programa de estudios nacional), hágalo de manera integrada pero márquelos con cursiva. Añada tantas filas como necesite.</li>';
        $html .= '<li>Este documento no debe explicar el día a día de cada unidad. Se trata de un esquema que debe mostrar cómo van a distribuirse los temas y el tiempo de modo que los alumnos estén preparados para cumplir los requisitos de la asignatura.</li>';
        $html .= '<li>Este esquema debe mostrar cómo se desarrollará la enseñanza de la asignatura. Debe reflejar las características individuales del curso en el aula y no limitarse a “copiar y pegar” de la guía de la asignatura.</li>';
        $html .= '<li>Si va a impartir tanto el Nivel Superior como el Nivel Medio, no olvide indicarlo claramente en el esquema.</li>';
        $html .= '</ul>';

        $html .= '<table cellpadding="3" cellspacing="0" style="font-size:10px">';
        $html .= '<tr>';
        $html .= '<td class="border" align="center"><b>CURSO</b></td>';
        $html .= '<td class="border" align="center"><b>N° DE UNIDAD</b></td>';
        $html .= '<td class="border" align="center"><b>TEMA DE LA UNIDAD</b></td>';
        $html .= '<td class="border" align="center"><b>OBJETIVO DE UNIDAD</b></td>';
        $html .= '<td class="border" align="center"><b>CONCEPTOS CLAVE</b></td>';
        $html .= '<td class="border" align="center"><b>CONTENIDO</b></td>';
        $html .= '<td class="border" align="center"><b>ENFOQUES DEL APRENDIZAJE</b></td>';
        $html .= '<td class="border" align="center"><b>EVALUACIÓN</b></td>';
        $html .= '<td class="border" align="center"><b>RECURSOS</b></td>';
        $html .= '</tr>';
        foreach($this->unidades as $unidad){
            $html .= '<tr>';
            $html .= '<td class="border centrarTexto">'.$unidad['curso'].'</td>';
            $html .= '<td class="border centrarTexto">'.$unidad['curriculo_bloque_id'].'</td>';
            $html .= '<td class="border centrarTexto">'.$unidad['unit_title'].'</td>';
            $html .= '<td class="border centrarTexto">'.$unidad['objetivo_asignatura'].'</td>';
            $html .= '<td class="border centrarTexto">'.$unidad['concepto_clave'].'</td>';
            $html .= '<td class="border centrarTexto">'.$unidad['contenido'].'</td>';
            $html .= '<td class="border centrarTexto">'.$unidad['detalle_len_y_aprendizaje'].'</td>';
            $html .= '<td class="border centrarTexto">'.$unidad['objetivo_evaluacion'].'</td>';
            $html .= '<td class="border centrarTexto">'.$unidad['recurso'].'</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';        

        return $html;
    }


    private function get_componentes_evaluacion()
    {
        $html = '<br><br><b>2.	Componentes de evaluación interna y externa del Programa del Diploma que se deben completar durante el curso</b>';
        $html .= '<ul>';
        $html .= '<li>Explique brevemente cómo y cuándo trabajará en ellos. Incluya la fecha en la que presente por primera vez a sus alumnos los componentes de evaluación.</li>';
        $html .= '<li>Explique las distintas etapas y plazos y cómo se preparará a los alumnos para completarlos.</li>';
        $html .= '</ul>';

        $html .= '<table cellpadding="3" cellspacing="0" style="font-size:10px" width="100%">';
        $html .= '<tr>';
        $html .= '<td class="border" align="center"><b>EVALUACIÓN</b></td>';
        $html .= '<td class="border" align="center"><b>ACTIVIDAD</b></td>';
        $html .= '<td class="border" align="center"><b>FECHA</b></td>';
        $html .= '<td class="border" align="center"><b>REVISIÓN DE CUMPLIMIENTO</b></td>';
        $html .= '</tr>';
        foreach($this->componentes as $componente){

            $cumplimiento = $componente->revision_cumplimiento ? 'X' : ''; 

            $html .= '<tr>';
            $html .= '<td class="border centrarTexto">'.$componente->evaluacion.'</td>';
            $html .= '<td class="border centrarTexto">'.$componente->actividad.'</td>';
            $html .= '<td class="border centrarTexto">'.$componente->fecha.'</td>';
            $html .= '<td class="border centrarTexto">'.$cumplimiento.'</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';        

        return $html;
    }


    private function get_vinculos_tdc()
    {
        $html = '<br><br><b>3.	Vínculos con Teoría del Conocimiento</b>';
        $html .= '<p>Los profesores deben explorar los vínculos que hay entre los temas de sus respectivas asignaturas y TdC. 
        Para dar un ejemplo de cómo lo haría, elija un tema del esquema del curso que permita a los alumnos establecer vínculos con TdC. 
        Explique brevemente por qué elige ese tema y describa cómo planificaría la clase.</p>';

        $tema_tdc = $this->search_data_text('datos', 'tema_tdc');
        $vinculo_tdc = $this->search_data_text('datos', 'vinculo_tdc');


        $html .= '<table width="" cellpadding="3" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td class="border fondoGrisNormal" width="250px">Tema</td>';
        $html .= '<td class="border fondoGrisNormal" width="780px">Vínculo con TdC (incluida la descripción de la planificación de clase)</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="border">' . $tema_tdc['contenido'] . '</td>';
        $html .= '<td class="border">' . $vinculo_tdc['contenido'] . '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }


    private function get_enfoques_aprendizaje()
    {
        $html = '<br><br><b>4.	Enfoques del aprendizaje</b>';
        $html .= '<p>Todas las asignaturas del IB deben contribuir al desarrollo de las habilidades de los enfoques del aprendizaje de los alumnos. 
        Para dar un ejemplo de cómo lo haría, elija un tema del esquema del curso que permita a los alumnos desarrollar 
        específicamente una o varias de las categorías de habilidades (sociales, de pensamiento, comunicación, autogestión e investigación).</p>';

        $tema_enfoque = $this->search_data_text('datos', 'tema_enfoque');
        $contibu_enfoque = $this->search_data_text('datos', 'contibu_enfoque');


        $html .= '<table width="" cellpadding="3" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td class="border fondoGrisNormal" width="250px">Tema</td>';
        $html .= '<td class="border fondoGrisNormal" width="780px">Contribución al desarrollo de las habilidades de los enfoques del 
        aprendizaje de los alumnos (incluida una o varias categorías de habilidades)</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="border">' . $tema_enfoque['contenido'] . '</td>';
        $html .= '<td class="border">' . $contibu_enfoque['contenido'] . '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }


    private function get_mentalidad_internacional()
    {
        $html = '<br><br><b>5.	Mentalidad internacional</b>';
        $html .= '<p>Todas las asignaturas del IB deben contribuir al desarrollo de una mentalidad internacional en los alumnos. 
        Para dar un ejemplo de cómo lo haría, elija un tema del esquema del curso que permita a los alumnos analizarlo desde distintas perspectivas culturales. 
        Explique brevemente por qué elige ese tema y qué recursos utilizaría para alcanzar este objetivo.</p>';

        $tema_menta = $this->search_data_text('datos', 'tema_menta');
        $contibu_menta = $this->search_data_text('datos', 'contibu_menta');


        $html .= '<table width="" cellpadding="3" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td class="border fondoGrisNormal" width="250px">Tema</td>';
        $html .= '<td class="border fondoGrisNormal" width="780px">Contribución al desarrollo de una mentalidad internacional (incluidos los recursos que utilizaría)</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="border">' . $tema_menta['contenido'] . '</td>';
        $html .= '<td class="border">' . $contibu_menta['contenido'] . '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }


    private function get_desarrollo_perfil()
    {
        $html = '<br><br><b>6.	Desarrollo del perfil de la comunidad de aprendizaje del IB</b>';
        $html .= '<p>También se espera que, mediante las asignaturas, los alumnos desarrollen los atributos del perfil de la comunidad de aprendizaje del IB. 
        Para dar un ejemplo de cómo lo haría, elija un tema del esquema del curso y explique de qué manera los contenidos y las 
        habilidades relacionadas fomentarían el desarrollo de los atributos del perfil de la comunidad de aprendizaje del IB que usted decida.</p>';

        $tema_perfil = $this->search_data_text('datos', 'tema_perfil');
        $contibu_perfil = $this->search_data_text('datos', 'contibu_perfil');


        $html .= '<table width="" cellpadding="3" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td class="border fondoGrisNormal" width="250px">Tema</td>';
        $html .= '<td class="border fondoGrisNormal" width="780px">Contribución al desarrollo de los atributos del perfil de la comunidad de aprendizaje del IB</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="border">' . $tema_perfil['contenido'] . '</td>';
        $html .= '<td class="border">' . $contibu_perfil['contenido'] . '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }
    
    
    private function get_equipos()
    {
        $html = '<br><br><b>7.	Instalaciones y equipos</b>';
        $html .= '<p>La enseñanza de esta asignatura requiere instalaciones y equipos para que el proceso de enseñanza y aprendizaje sea satisfactorio. 
        Describa las instalaciones y los equipos que haya en el colegio para permitir y fomentar el desarrollo del curso. 
        Incluya cualquier plan que haya para mejorarlos y los plazos.</p>';

        $equipos = $this->search_data_text('datos', 'equipos');

        $html .= '<table width="" cellpadding="3" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td class="border" width="1030px">' . $equipos['contenido'] . '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }


    private function get_otros_recursos()
    {
        $html = '<br><br><b>8.	Otros recursos</b>';
        $html .= '<p>Describa otros recursos que usted y sus alumnos puedan utilizar en el colegio, si hay planes para mejorarlos y los plazos. 
        Incluya cualquier recurso existente en la comunidad fuera del colegio que pueda contribuir a implementar satisfactoriamente su asignatura.</p>';

        $recursos = $this->search_data_text('datos', 'recursos');

        $html .= '<table width="" cellpadding="3" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td class="border" width="1030px">' . $recursos['contenido'] . '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }

    private function get_bibliografia()
    {
        $html = '<br><br><b>9.	Bibliografía/Webgrafía. Utilizar normas APA (última edición)</b>';

        $bibliografia = $this->search_data_text('datos', 'bibliografia');

        $html .= '<table width="" cellpadding="3" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td class="border" width="1030px">' . $bibliografia['contenido'] . '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }


    private function search_data_text($section, $typeField)
    {
        foreach ($this->planCabecera as $p) {
            if ($p->tipo_campo == $typeField && $p->tipo_seccion == $section) {
                return array(
                    'id'        => $p->id,
                    'contenido' => $p->opcion_texto
                );
            }
        }
    }

    private function search_data_select($section, $typeField, $option)
    {
        foreach ($this->planCabecera as $p) {
            if ($p->tipo_campo == $typeField && $p->tipo_seccion == $section && $p->opcion_texto == $option) {
                return array(
                    'id'        => $p->id,
                    'seleccion' => $p->opcion_seleccion
                );
            }
        }
    }



    private function estilos()
    {
        $html = '';
        $html .= '<style>';
        $html .= '.border {
                    border: 0.1px solid black;
                  }

                  .borderT{
                    border-top: 0.1px solid black;
                  }

                  .borderR{
                    border-right: 0.1px solid black;
                  }

                  .borderB{
                    border-bottom: 0.1px solid black;
                  }

                  .borderL{
                    border-left: 0.1px solid black;
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

                .fondoGrisTitulo{
                    background-color:#eee;
                    font-family: Arial, Helvetica, sans-serif;
                }

                .fondoGrisNormal{
                    background-color:#eee;
                    color: #898989;
                    font-family: Arial, Helvetica, sans-serif;                    
                }

                    ';
        $html .= '</style>';
        return $html;
    }


    /**
     * MÉTODO QUE CONSULTA LA PLANIFICACIÓN DE UNIDAD DE DIPLOMA
     * ELABORADO POR: Arturo Sarango - 2023-03-23
     * ACTUALIZADO POR: Arturo Sarango - 2023-03-23
     */
    private function get_planes_unidad($materiaId, $periodoId){
        $con = Yii::$app->db;
        $query = "select 	cur.name as curso ,uni.id
                        ,uni.curriculo_bloque_id 
                        ,uni.unit_title 
                        ,ver.objetivo_asignatura
                        ,ver.concepto_clave 
                        ,ver.contenido 
                        ,ver.detalle_len_y_aprendizaje 
                        ,ver.objetivo_evaluacion 
                        ,ver.recurso 
                from 	planificacion_bloques_unidad uni
                        left join planificacion_vertical_diploma ver on 
                                ver.planificacion_bloque_unidad_id = uni.id 
                        inner join planificacion_desagregacion_cabecera cab on cab.id = uni.plan_cabecera_id 
                        inner join ism_area_materia iam on iam.id = cab.ism_area_materia_id 		
                        inner join ism_malla_area ima on ima.id = iam.malla_area_id 
                        inner join ism_periodo_malla ipm on ipm.id = ima.periodo_malla_id 
                        inner join ism_malla mal on mal.id = ipm.malla_id 
                        inner join op_course_template tem on tem.id = mal.op_course_template_id 
                        inner join op_course cur on cur.x_template_id = tem.id 
                        inner join op_section sec on sec.id = cur.section
                        inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = sec.period_id 
                where 	iam.materia_id = $materiaId
                        and sec.code = 'DIPL'
                        and sop.scholaris_id = $periodoId
                order by uni.curriculo_bloque_id;";

        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
}
