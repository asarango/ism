<?php
namespace backend\models\pudpai;

use backend\controllers\PlanificacionVerticalDiplomaController;
use backend\models\CurriculoMecBloque;
use backend\models\OpCourseTemplate;
use backend\models\PlanificacionBloquesUnidad;
use backend\models\IsmLiteralDescriptores;
use backend\models\PlanificacionDesagregacionCabecera;
use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;
use backend\models\PlanificacionVerticalDiploma;
use backend\models\PlanificacionBloquesUnidadSubtitulo2;
use backend\models\PudPep;
use backend\models\ScholarisMateria;
use backend\models\ScholarisPeriodo;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;
use backend\models\helpers\Scripts;
use datetime;
use backend\models\helpers\HelperGeneral;
use backend\models\IsmCriterio;
use backend\models\IsmCriterioDescriptor;
use backend\models\IsmCriterioLiteral;
use backend\models\PlanificacionVerticalPaiDescriptores;
use backend\models\PlanificacionVerticalPaiOpciones;

class PdfPvPai extends \yii\db\ActiveRecord
{

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
            'margin_top' => 22,
            'margin_bottom' => 4,
            'margin_header' => 2,
            'margin_footer' => 0,
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
    private function cabecera()
    {
        $codigoISO = 'ISOM20-19';
        $version ="4.0";
        $fecha=date('Y-m-d H:i:s'); 
        $fecha=date('Y-m-d'); 
        $fecha ='28/06/2022';
        $html = <<<EOT
        <table width="100%" cellspacing="0" cellpadding="10"> 
            <tr> 
                <td class="border" align="center" width="20%">
                    <img src="imagenes/instituto/logo/logoISM1.png" width="40px"><br><font size = "1">Proceso Académico</font>
                </td>
                <td class="border" align="center" width="60%" >
                                             
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
        <br>  
             
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
    private function firmas()
    {
        $html = <<<EOD
        <br>
        <br>
        <table border = "1" width="100%" cellspacing="0" cellpadding="5">         
            <tr> 
                <td  align="center" style="font-size:10">
                    FIRMA DE APROBACIÓN JEFE DE ÁREA
                </td>
                <td  align="center" style="font-size:10">
                    FIRMA DE APROBACIÓN DE COORDINACIÓN
                </td>                
            </tr> 
            <tr> 
                <td align="left" style="font-size:10"><br><br><br><br><br><br></td>
                <td align="left" style="font-size:10"><br><br><br><br><br><br></td>
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
    /*** itera todos los cursos pai  ***/ 
    private function iteracion_por_materia()
    {
        $html = '';
        $idPeriodo = Yii::$app->user->identity->periodo_id;
        $con = yii::$app->db;
        $idMateria = $this->planCabecera->ismAreaMateria->materia->id;
        $query ="select p.id as id_cabecera
        from planificacion_desagregacion_cabecera p, ism_area_materia a,ism_materia m
        where p.ism_area_materia_id = a.id 
        and a.materia_id = m.id 
        and p.scholaris_periodo_id  = $idPeriodo
        and m.id=$idMateria;";

        $arrayIdCabecera = $con->createCommand($query)->queryAll();

        foreach($arrayIdCabecera as $arrayId)
        {
            $modelPlaniDesagregacion = PlanificacionDesagregacionCabecera::findOne($arrayId['id_cabecera']);

            $arrayNombrePai = array(
                "SEPTIMO"=>"UNO","OCTAVO"=>"DOS","NOVENO"=>"TRES","DECIMO"=>"CUATRO","BACH1"=>"CINCO",
            );

            $curso = $modelPlaniDesagregacion->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->name;        

            if (isset($arrayNombrePai[$curso]))
            {
                $cursoNumero =  $arrayNombrePai[$curso] ;

                $html .= $this->unidades_iteracion($modelPlaniDesagregacion->id ,$cursoNumero);
            }
            

        }

        return $html;

    }

    /*** REALIZA LA ITERACION POR CADA MATERIA EN BLOQUE UNIDAD  */
    private function unidades_iteracion($idPlanCabecera,$cursoNumero)
    {

        // $user = Yii::$app->user->identity->usuario;
        // $periodoId = Yii::$app->user->identity->periodo_id;
        // $objHelper = new HelperGeneral();       
        // $cursos = $objHelper->get_cursos_docente($user,$periodoId);

        $planUnidadBloque = PlanificacionBloquesUnidad::find()
        ->innerJoin('curriculo_mec_bloque c', 'c.id = planificacion_bloques_unidad.curriculo_bloque_id')
        ->where([
                         'plan_cabecera_id'=>$idPlanCabecera,
                         'c.is_active' => true
        ])
        ->orderBy(['curriculo_bloque_id'=> SORT_ASC])
        ->all();          
       
        $colorCabeceraFondo = "#BEDBEC";
        //cabecera tabla
        $html = '';
        $html.= <<<EOK
            <br>
            <table width="100%" cellspacing="0" cellpadding="10">
            <tr>
                <td colspan = "9" class="border" align="center" style="font-size:9">PAI $cursoNumero </td>
            </tr>
            <tr> 
                <td class="border" width="10%" style="background-color:$colorCabeceraFondo;font-size:9"><b>TÍTULO DE LA UNIDAD</b></td>     
                <td class="border" width="10%" style="background-color:$colorCabeceraFondo;font-size:9"><b>CONCEPTO CLAVE</b></td>           
                <td class="border" width="10%" style="background-color:$colorCabeceraFondo;font-size:9"><b>CONCEPTOS RELACIONADOS</b></td>           
                <td class="border" width="10%" style="background-color:$colorCabeceraFondo;font-size:9"><b>CONTEXTO GLOBAL</b></td>           
                <td class="border" width="10%" style="background-color:$colorCabeceraFondo;font-size:9"><b>ENUNCIADO DE LA INDAGACIÓN</b></td>  
                <td class="border" width="20%" style="background-color:$colorCabeceraFondo;font-size:9"><b>OBJETIVOS ESPECIFICOS</b></td>  
                <td class="border" width="10%" style="background-color:$colorCabeceraFondo;font-size:9"><b>HABILIDADES DE ENFOQUES DEL APRENDIZAJE</b></td>          
                <td class="border" width="10%" style="background-color:$colorCabeceraFondo;font-size:9"><b>CONTENIDOS</b></td>  
                <td class="border" width="10%" style="background-color:$colorCabeceraFondo;font-size:9"><b>TRAZABILIDAD</b>
                    Evaluación en Pantalla (E.P)
                    Planificador Parcialmente 
                    Completado (P.P.C) 
                    Destrezas Imprescindible del Ministerio de Educación (D.I.M.E)                
                </td>  
            </tr>
        EOK;  

        
        foreach($planUnidadBloque as $plaUni)
        {              

            $conc_clave = '<ul>';
            $conc_relac='<ul>';
            $contx_global='<ul>';
            $hab_enfoque ='';


            $planVerticalPai = PlanificacionVerticalPaiOpciones::find()
            ->where([
                'plan_unidad_id'=>$plaUni->id
            ])
            ->all(); 

            $bloques = CurriculoMecBloque::findOne($plaUni->curriculo_bloque_id);
            
            
            $html .= '<tr>';           
            $html .= '<td class="border" style="font-size:9"><b>'.$bloques->last_name.'</b><br><br>'.$plaUni->unit_title.'</td>';//TITULO DE LA UNIDAD
           
            //este for es para conceptos claves, relacionados, y contexto global
            foreach($planVerticalPai as $planPai)
            {
                
                if($planPai->tipo =='concepto_clave'){
                    $conc_clave .= '<li>'.$planPai->contenido.'</li>';
                }

                if($planPai->tipo =='concepto_relacionado'){
                    $conc_relac .= '<li>'.$planPai->contenido.'</li>';
                }

                if($planPai->tipo =='contexto_global'){
                    $contx_global .= '<li><b>'.$planPai->contenido.'</b> : '.$planPai->sub_contenido.'</li>';
                }
               
            }
            $conc_clave .= '</ul>';
            $conc_relac .= '</ul>';
            $contx_global .= '</ul>';
            // hailidades de enfoque
            $arrayHabilidades = array();
            //extraemos en un array los tipos de hab. de enfoque
            foreach($planVerticalPai as $planPai)
            {
                if($planPai->tipo =='habilidad_enfoque')
                {
                    if(!in_array($planPai->tipo2,$arrayHabilidades,true))
                    {
                        $arrayHabilidades[]= $planPai->tipo2;
                    }
                }
            }
            //iteramos los tipo de hsb. de enforque, para mostrarlos como arbol
            foreach($arrayHabilidades as $tipo)
            {
                $hab_enfoque .= '<ul><b>'.$tipo.'</b>';
                foreach($planVerticalPai as $planPai)
                {
                    if($planPai->tipo2 ==$tipo)
                    {
                       $hab_enfoque .= '<li>'.$planPai->contenido.'</li>';
                    }
                }
                $hab_enfoque .= '</ul><br>';

            }
            

            //Objetivos Especificos
            //extraccion de los descriptores
            $objEspecificos = $this->consultar_objetivos_especificos($plaUni->id);   
            $objContenidos = $this->consulta_contenidos($plaUni->id);   
            //$objContenidos = explode('##',$objContenidos);
            

            $html .= '<td class="border" style="font-size:9">' . $conc_clave. '</td>';    //CONCEP. CLAVE 
            $html .= '<td class="border" style="font-size:9">' . $conc_relac . '</td>';    //CONCEP. RELAC.   
            $html .= '<td class="border" style="font-size:9">' . $contx_global . '</td>';   //CONCEP. GLOBAL 
            $html .= '<td class="border" style="font-size:9">' . $plaUni->enunciado_indagacion . '</td>'; //ENUNCIADO INDAG.
            $html .= '<td class="border" style="font-size:9">' . $objEspecificos  . '</td>';   //OBJ. ESPEFICICOS
            $html .= '<td class="border" style="font-size:9">' . $hab_enfoque . '</td>';   //HBA. ENFOQUE
            $html .= '<td colspan ="2" class="border" style="font-size:9">' . $objContenidos  . '</td>';   //CONTEXTO
            //$html .= '<td class="border" style="font-size:9">' . $objContenidos[1] . '</td>';   //TRAZABILIDAD
            $html .= '</tr>';          
        
        } 
        
        $html .= '</table>';      
       
        return $html;       
    }
    //objetivos especificos del pai
    private function consultar_objetivos_especificos($planUnidadBloq)
    {
        $arrayCriterios = array();
        $con = yii::$app->db;       
        $criterios = ''; 

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
            $literalCriterio = IsmCriterioLiteral::findOne($criterio); 
            $criterios .= '<ul><b>'.$literalCriterio->criterio->nombre.' - '. $literalCriterio->nombre_espanol.'</b>
            <br><br>';
            foreach($respuesta as $resp)
            {
                if($criterio==$resp['id_literal_criterio'])
                {
                    //consulta descriptor y literal descriptor
                    $descriptor = IsmLiteralDescriptores::findOne($resp['id_literal_descriptor']);
                    $criterios .= '<li>'. $descriptor->descripcion .'</li>'; 
                }
            } 
            $criterios .= '</ul><br>'; 
        }
        
        
        return  $criterios;
    }
    /************  CONSULTA PARA CONTENIDOS PLAN VERTICAL PAI************ */
    private function consulta_contenidos($planUnidadBloq)
    {
        $arrayTemario = array();
        $html='';       
        $objScripts = new Scripts();
        $subtitulos = $objScripts->selecciona_subtitulos($planUnidadBloq);  

        foreach($subtitulos as $subtitulo)
        {            
            $subtitulo2 = PlanificacionBloquesUnidadSubtitulo2::find()->where([
                'subtitulo_id' => $subtitulo['id']
            ])->orderBy('orden')->all();

            $subtitulo['subtitulos'] = $subtitulo2;
        
            array_push($arrayTemario, $subtitulo);
            
        }
       
        if($arrayTemario)
        {
            $html = '<table border="0" cellspacing="0" cellpadding="5" style="font-size:10">';
            foreach ($arrayTemario as $temario) 
            {
                $html.='<tr><td>
                        <ul>*<b> '.$temario['subtitulo'].'</b>';
                        foreach ($temario['subtitulos'] as $subtitulos) 
                        {
                            $html.='<ul><li>'.$subtitulos['contenido'].'</li></ul>';
                        }   
                        $html.='</ul><br>
                        </td>';
                $html.='<td>'.$temario['trazabilidad'].'</td></tr>';
                    
            }
            $html .= '</table>';
        }

        return  $html ;
    }

    
    /***FIN UNIDADES ITERACION TITULOS */

    private function cuerpo()
    {
        $periodoId = Yii::$app->user->identity->periodo_id;
        $periodo = ScholarisPeriodo::findOne($periodoId);
       
        $cursos = \backend\models\OpCourseTemplate::find()->all();        

        $html = $this->estilos();
        $materia = $this->planCabecera->ismAreaMateria->materia->nombre;
        $anioEscolar =  $this->planCabecera->ismAreaMateria->mallaArea->periodoMalla->scholarisPeriodo->nombre;
        $texto = '"La finalidad de la planificación vertical es establecer una secuencia en el aprendizaje 
                que garantice la continuidad y la progresión a lo largo de cada año del programa, e incluso 
                para los futuros estudios de los alumnos" (De los principios a la práctica, 2014). Esta planificacion
                articula verticalmente el estudio de conceptos claves, relacionados, contextos globales y el desarrollo
                de enfoques del aprendizaje, brindando la oportunidad de alcanzar todos los objetivos esoecíficos de los ocho
                grupos de asignaturas de manera equilibrada.';

        $titulo = <<<EOK
            <br>
            <table border = "1" width="100%" cellspacing="0" cellpadding="5"  style="font-size:10">
                <tr >
                    <td align="center">ISM <br>International Scholastic Model</td>               
                </tr>
                <tr >
                    <td align="center">PLANIFICACIÓN VERTICAL </td>               
                </tr>
                <tr >
                    <td align="center">AÑO ESCOLAR $anioEscolar </td>               
                </tr>
                <tr >
                    <td align="">GRUPO DE ASIGNATURA DE:  <b>$materia</b> </td>               
                </tr>
                <tr >
                    <td align="">$texto</td>               
                </tr>
            </table>
        EOK;       

        
        $html .= $titulo;
        //$html .= $this->generaDatosCabeceras();      
             
        //$html .= $this->bloque_materia_iteracion();        
        $html .= $this->iteracion_por_materia(); 
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