<?php

namespace backend\models\pca;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;

use backend\models\CurriculoMecNiveles;
use backend\models\OpInstitute;
use backend\models\PcaDetalle;
use backend\models\PlanificacionBloquesUnidadSubtitulo;
use backend\models\PlanificacionBloquesUnidadSubtitulo2;
use backend\models\PlanificacionDesagregacionCabecera;

use backend\models\pca\Pca;
use backend\models\ScholarisPeriodo;

class PcaPdf extends \yii\db\ActiveRecord{

    private $pcaId;
    private $modelPca;
    private $modelPcaDetalle;
    private $opCourseTemplateId;
    public $html;

    private $modelInstitute;
    private $modelPeriodo;

    public function __construct($pcaId){
        $this->modelInstitute = OpInstitute::findOne(Yii::$app->user->identity->instituto_defecto);
        $this->modelPeriodo = ScholarisPeriodo::findOne(Yii::$app->user->identity->periodo_id);
        $this->pcaId = $pcaId;
        $this->html = '';       
        $this->modelPca = PlanificacionDesagregacionCabecera::findOne($this->pcaId);
        $this->opCourseTemplateId = $this->modelPca->ismAreaMateria->mallaArea->periodoMalla->malla->op_course_template_id;
        $this->modelPcaDetalle = PcaDetalle::find()->where(['desagregacion_cabecera_id' => $pcaId])->all();

        $this->genera_pfd();       
    }

    private function genera_pfd(){

        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 0,
            'margin_header' => 1,
            'margin_footer' => 5,
        ]);


        $cabecera = $this->cabecera();
        // $pie = '<h4>Genera Pie</h4>';

        $mpdf->SetHtmlHeader($cabecera);
        $mpdf->showImageErrors = true;

        $html = $this->cuerpo();

        $mpdf->WriteHTML($html);
        $mpdf->SetFooter();

        $mpdf->Output('PCA' . "curso" . '.pdf', 'D');
        exit;
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


    private function cabecera() {
        $html = '';
        $html .= '<table width="100%" cellspacing="0" cellpadding="10">';
        $html .= '<tr>';
        // $html .= '<td class="border" align="center" width="20%"><img src="imagenes/instituto/logo/logo2.png" width="60px"></td>';
        $html .= '<td class="" align="center" width="20%">'.date('Y-m-d H:i').'</td>';
        $html .= '<td class="" align="center" width="">Plan Anual Curricular</td>';
        // $html .= '<td class="border" align="right" width="20%">
        //             Código: ISMR20-22 <br>
        //             Versión: 5.0<br>
        //             Fecha: 28/09/021<br>
        //             Página: {PAGENO} / {nb}<br>
        //           </td>';
        $html .= '<td class="" align="right" width="20%">'.$this->modelPeriodo->codigo.'</td>';
        $html .= '</tr>';
        $html .= '</table>';
        return $html;
    }
    
    
    private function cuerpo() {
        $periodoId = Yii::$app->user->identity->periodo_id;
        $periodo = \backend\models\ScholarisPeriodo::findOne($periodoId);

        $html = $this->estilos();

        $html .= '<table width="100%" cellspacing="0" cellpadding="10">';
        $html .= '<tr>';
        $html .= '<td class="" align="center" width="20%"><img src="imagenes/instituto/logo/educacion_nuevo.png" width="200px"></td>';
        
        $html .= '<td class="centrarTexto" align="center">'.
                $this->modelInstitute->name.'<br>'.
                'Código AMIE '. $this->modelInstitute->codigo_amie.
                '</td>';
        
        $html .= '<td class="" width="20%" align="right"><img src="imagenes/instituto/logo/logoISM1.png" width="80px"></td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= $this->uno();
        $html .= $this->dos();
        $html .= $this->tres();
        $html .= $this->cuatro();
        $html .= $this->cinco();
        $html .= $this->seis();
        $html .= $this->firmas();

        return $html;
    }
    
    private function uno(){
        
        $html = '';
        $html .= '<table width="100%" cellspacing="0" cellpadding="5">';        
        
        $html .= '<tr><td class="centrarTexto border" colspan="4"><b>PLAN ANUAL CURRICULAR</b></td></tr>';
        
        $html .= '<tr><td class="border" colspan="4" style="background-color: #eee"><b>1.- DATOS INFORMATIVOS</b></td></tr>';

        $html .= '<tr>';
        $html .= '<td width="20%" class="border"><b>Área:</b></td>';
            
        $html .= '<td width="30%" class="border">';
        $html .= $this->modelPca->ismAreaMateria->mallaArea->area->nombre;
        $html .= '</td>';

        $html .= '<td width="20%" class="border"><b>Asignatura:</b></td>';
            
        $html .= '<td width="30%" class="border">';
        $html .= $this->modelPca->ismAreaMateria->materia->nombre;
        $html .= '</td>';        
        $html .= '</tr>';


        $html .= '<tr>';
        $html .= '<td width="20%" class="border"><b>Docentes:</b></td>';
        $html .= '<td width="80%" class="border" colspan="3">';
        $teachers = $this->get_teachers();
        foreach($teachers as $teacher){
            $html.= $teacher['docente'].' ';
        }
        $html .= '</td>';
        $html .= '</tr>';
                
        $html .= '<tr>';
        $html .= '<td width="20%" class="border"><b>GRADO / CURSO:</b></td>';
        $html .= '<td width="30%" class="border">';
        $parallels = $this->get_parallels();
        foreach($parallels as $parallel){
            $html.= $parallel['paralelo'].' ';
        }        
        $html .= '</td>';        

        $html .= '<td width="20%" class="border"><b>Nivel Educativo:</b></td>';
            
        $html .= '<td width="30%" class="border">';
        $html .= $this->modelPca->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->name;
        $html .= '</td>'; 
        
        // $helpers = new \backend\models\helpers\HelperGeneral();
        // $paralelos = $helpers->get_paralelos_por_template_id($this->opCourseTemplateId);
        // $html .= '<td width="50%" class="border"><b>NIVELES:</b> ';
        // foreach ($paralelos as $p){
        //     $html .= ' | '.$p['name'];
        // }
        // $html .= '</td>';
        
        // $html .= '</tr>';
        $html .= '</table>';
        
        return $html;
    }

    private function get_parallels(){
        $ismAreaMateriaId = $this->modelPca->ism_area_materia_id;
        $con = Yii::$app->db;
        $query = "select 	concat(tem.name,' ',par.name) as paralelo
                    from 	planificacion_desagregacion_cabecera cab
                            inner join ism_area_materia iam on iam.id = cab.ism_area_materia_id 
                            inner join scholaris_clase cla on cla.ism_area_materia_id = cab.ism_area_materia_id 
                            inner join ism_malla_area ima on ima.id = iam.malla_area_id 
                            inner join ism_periodo_malla ipm on ipm.id = ima.periodo_malla_id 
                            inner join ism_malla im on im.id = ipm.malla_id 
                            inner join op_course_template tem on tem.id = im.op_course_template_id 
                            inner join op_course_paralelo par on par.id = cla.paralelo_id 
                    where 	cab.id = $this->pcaId
                            and im.op_course_template_id = $this->opCourseTemplateId
                    group by tem.name, par.name
                    order by par.name;";
                    
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function get_teachers(){

        $ismAreaMateriaId = $this->modelPca->ism_area_materia_id;
        $con = Yii::$app->db;
        $query = "select 	concat(fac.x_first_name,' ', fac.last_name) as docente
                    from 	scholaris_clase cla 
                            inner join op_faculty fac on fac.id = cla.idprofesor 
                    where 	cla.ism_area_materia_id = $ismAreaMateriaId
                    group by fac.x_first_name, fac.last_name;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    private function dos(){
        
        $html = '';
        $html .= '<table width="100%" cellspacing="0" cellpadding="5">';
        $html .= '<tr>';
        $html .= '<td class="border" colspan="6" style="background-color: #eee"><b>2.- TIEMPO</b></td>';
        $html .= '</tr>';
        
        // inicio de encabezados
        $html .= '<tr>';        
        $html .= '<td width="" class="border centrarTexto"><b>CARGA HORARIA SEMANAL</b></td>';
        $html .= '<td width="" class="border centrarTexto"><b>Nº SEMANAS TRABAJO</b></td>';
        $html .= '<td width="" class="border centrarTexto"><b>EVALUACIÓN DEL APRENDIZAJE E IMPREVISTOS</b></td>';
        $html .= '<td width="" class="border centrarTexto"><b>TOTAL DE SEMANAS CLASES</b></td>';        
        $html .= '<td width="" class="border centrarTexto"><b>TOTAL DE PERIODOS</b></td>';                
        
        $html .= '</tr>';
        // Fin de encabezados
              
        
        // incio de respuestas
        $html .= '<tr>';
        $html .= '<td class="border centrarTexto">'.$this->modelPca->carga_horaria_semanal.'</td>';
        $html .= '<td class="border centrarTexto">'.$this->modelPca->semanas_trabajo.'</td>';
        $html .= '<td class="border centrarTexto">'.$this->modelPca->evaluacion_aprend_imprevistos.'</td>';
        $html .= '<td class="border centrarTexto">'.$this->modelPca->total_semanas_clase.'</td>';
        $html .= '<td class="border centrarTexto">'.$this->modelPca->total_periodos.'</td>';
        $html .= '</tr>';
        // Fin de respuestas

        $html .= '</table>';
        
        return $html;
    }
    
    public function tres(){
        $html = '';
        $html .= '<table width="100%" cellspacing="0" cellpadding="5">';
        $html .= '<tr>';
        $html .= '<td class="border" colspan="2" style="background-color: #eee"><b>3.- OBJETIVOS GENERALES</b></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border centrarTexto" width="60%"><b>Objetivos del área</b></td>';
        $html .= '<td class="border centrarTexto" width="40%"><b>Objetivos del grado/curso</b></td>';
        $html .= '</tr>';
        
        $html .= '<tr>';  
        $html .= '<td class="border">';  
        
        foreach($this->modelPcaDetalle as $detalle){
            if($detalle->tipo == 'objetivos_generales'){
                $html.= '<b>'.$detalle->codigo.'</b><br>';
                $html.= $detalle->contenido;
            }
        }
        
        $html .= '</td>';

        $html .= '<td class="border">No existe proceso de objetivos especificos</td>';  
        
        $html .= '</tr>';
                    
        $html .= '</table>';
        
        return $html;
    }

    // public function get_general_objetives(){
    //     $con = Yii::$app->db;
    //     $query = "select 	obj.code, obj.description  
    //                 from 	planificacion_bloques_unidad pbl
    //                         inner join planificacion_desagregacion_criterios_evaluacion cri on cri.bloque_unidad_id = pbl.id
    //                         inner join curriculo_mec eva on eva.id = cri.criterio_evaluacion_id 
    //                         inner join curriculo_mec obj on obj.code = eva.belongs_to 
    //                                         and obj.reference_type = 'objgeneral'
    //                 where 	pbl.plan_cabecera_id = $this->pcaId;";
    //     $res = $con->createCommand($query)->queryAll();
    //     return $res;
    // }

    public function cuatro(){
        $html = '';
        $html .= '<table width="100%" cellspacing="0" cellpadding="5">';
        $html .= '<tr>';
        $html .= '<td class="border" colspan="1" style="background-color: #eee"><b>4.- EJES TRANSVERSALES</b></td>';
        $html .= '<td class="border">';  
        $html .= '<ul>';
        $html .= '<li>Justicia</li>';
        $html .= '<li>Solidaridad</li>';
        $html .= '<li>Innovador</li>';
        $html .= '</ul>';
        $html .= '</td>';
        $html .= '</tr>';                            
        $html .= '</table>';
        
        return $html;
    }
    
    public function cinco(){        
        
        $html = '';
        $html .= '<table width="100%" cellspacing="0" cellpadding="5">';
        $html .= '<tr>';
        $html .= '<td class="border" colspan="7" style="background-color: #eee"><b>5.- DESARROLLO DE UNIDADES DE PLANIFICACIÓN</b></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td>traer desde la PUD</td>';
        // $html .= '<td class="border centrarTexto"><b>N°</b></td>';
        // $html .= '<td class="border centrarTexto"><b>Título de la unidad de planificación</b></td>';
        // $html .= '<td class="border centrarTexto"><b>Objetivos específicos de la unidad de planificación</b></td>';
        // $html .= '<td class="border centrarTexto"><b>Contenidos de aprendizaje</b></td>';
        // $html .= '<td class="border centrarTexto"><b>Orientaciones metodológicas</b></td>';
        // $html .= '<td class="border centrarTexto"><b>Evaluación</b></td>';
        // $html .= '<td class="border centrarTexto"><b>Duración en semanas</b></td>';

        $html .= '</tr>';                                  
        $html .= '</table>';
        
        return $html;
    }
    
    public function seis(){
        
        $html = '';
        $html .= '<table width="100%" cellspacing="0" cellpadding="5">';
        $html .= '<tr>';
        $html .= '<td class="border" colspan="1" style="background-color: #eee" width="60%"><b>6.- BIBLIOGRAFÍA / WEBGRAFÍA: (UTILIZAR NORMAS APA VI EDICIÓN)</b></td>';
        $html .= '<td class="border" colspan="1" style="background-color: #eee" width="40%"><b>7.- OBSERVACIONES</b></td>';
        $html .= '</tr>';
        
        $html .= '<tr>';  
        $html .= '<td class="border">';  
        foreach($this->modelPcaDetalle as $detalle){
            if($detalle->tipo == 'bibliografia'){
                $html .= $detalle->contenido;
            }
        }
        $html .= '</td>';

        $html .= '<td class="border">';
        foreach($this->modelPcaDetalle as $detalle){
            if($detalle->tipo == 'observaciones'){
                $html .= $detalle->contenido;
            }
        }
        $html .= '</td>';

        $html .= '</tr>';
                  
        $html .= '</table>';
        
        return $html;
    }
    
    
    public function firmas(){
        
        $html = '';
        $html .= '<br>';
        $html .= '<table width="100%" cellspacing="0" cellpadding="10">';
        
        $html .= '<tr>';
        $html .= '<td align="center" class="border" colspan="1" style="background-color: #eee;" width="50%"><b>ELABORADO POR</b></td>';
        $html .= '<td align="center" class="border" colspan="1" style="background-color: #eee" width="50%"><b>REVISADO Y APROBADO POR COORDINACIÓN</b></td>';
        $html .= '</tr>';
        
        $html .= '<tr>';  
        $html .= '<td class="border">DOCENTES</td>';
        $html .= '<td class="border">NOMBRE</td>';
        $html .= '</tr>';
        
        $html .= '<tr>';  
        $html .= '<td class="border">FIRMA</td>';
        $html .= '<td class="border">FIRMA</td>';
        $html .= '</tr>';
        
        $html .= '<tr>';  
        $html .= '<td class="border">FECHA</td>';
        $html .= '<td class="border">FECHA</td>';
        $html .= '</tr>';
                  
        $html .= '</table>';
        
        return $html;
    }
    
}

?>