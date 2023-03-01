<?php

namespace backend\controllers;

use backend\models\kids\PdfPlanSemanal;
use backend\models\KidsPca;
use Yii;
use backend\models\KidsPlanSemanal;
use backend\models\KidsPlanSemanalHoraClase;
use backend\models\KidsPlanSemanalSearch;
use backend\models\KidsUnidadMicro;
use backend\models\ScholarisHorariov2Dia;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * KidsPlanSemanalController implements the CRUD actions for KidsPlanSemanal model.
 */
class KidsPlanSemanalController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all KidsPlanSemanal models.
     * @return mixed
     */
    public function actionIndex()
    {

        $pcaId = $_GET['pca_id'];
        $pca = KidsPca::findOne($pcaId);
        $experiencias = KidsUnidadMicro::find()
            ->where(['pca_id' => $pcaId])
            ->orderBy('orden')
            ->all();

        return $this->render('index', [
            'pca' => $pca,
            'experiencias' => $experiencias
        ]);
    }

    /**
     * Muestra las semanas con las experiencia elejidas
     */
    public function actionAjaxSemanas()
    {

        $experienciaId  = $_GET['experiencia_id'];
        $opCourseId     = $_GET['op_course_id'];

        $planSemanal = $this->get_plan_semanal_cab($opCourseId);

        return $this->renderPartial('_ajax-semanas', [
            'planSemanal' => $planSemanal,
            'experienciaId' => $experienciaId
        ]);
    }

    private function get_plan_semanal_cab($courseId)
    {
        $con = Yii::$app->db;
        $query = "select 	s.id 
                            ,s.nombre_semana 
                            ,ex.experiencia
                            ,ks.id as plan_semanal_id, ks.kids_unidad_micro_id, ks.semana_id, ks.created_at
                            ,ks.created, ks.estado, ks.sent_at, ks.sent_by, ks.approved_at, ks.approved_by  
                    from	scholaris_clase c
                            inner join op_course_paralelo p on p.id = c.paralelo_id
                            inner join scholaris_bloque_actividad b on b.tipo_uso = c.tipo_usu_bloque 
                            inner join scholaris_bloque_semanas s on s.bloque_id = b.id 
                            left join kids_plan_semanal ks on ks.semana_id = s.id 
                            left join kids_unidad_micro ex on ex.id = ks.kids_unidad_micro_id 
                    where 	p.course_id = $courseId
                    group by s.id, s.nombre_semana, ks.id, ex.experiencia
                    order by s.semana_numero;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }



    public function actionAjaxInsertExperiencia()
    {

        $kidsUnidadMicroId = $_POST['experiencia_id'];
        $semanaId = $_POST['semana_id'];
        $today = date('Y-m-d H:i:s');
        $userLog = Yii::$app->user->identity->usuario;

        $model = new KidsPlanSemanal();
        $model->kids_unidad_micro_id = $kidsUnidadMicroId;
        $model->semana_id = $semanaId;
        $model->created_at = $today;
        $model->created = $userLog;
        $model->estado = 'INICIANDO';

        if ($model->save()) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * METODO PARA PASAR EL DETALLE DE LA PLANIFICACION SEMANAL
     */
    public function actionDetalle()
    {

        $periodoId = Yii::$app->user->identity->periodo_id;
        $usuario = Yii::$app->user->identity->usuario;

        $kidsPlanSemanalId = $_GET['kids_plan_semanal_id'];
        $kidsPlanSemanal = KidsPlanSemanal::findOne($kidsPlanSemanalId);        

        $courseId = $kidsPlanSemanal->kidsUnidadMicro->pca->op_course_id; //toma el surso asignado al plan
        
        $horarioAsignado = $this->get_horario_asignado($courseId); //Consulta el horario asignado a la planificacion
        $horario = $this->get_horario($horarioAsignado, $periodoId, $courseId, $usuario); //Trae el horario del docente del curso
        $dias = ScholarisHorariov2Dia::find()->orderBy('numero')->asArray()->all();        

        $arrayDias = array();

        foreach ($dias as $d) {
            $diaG =  $d['nombre'];
            $arrayHoras = array();

            foreach ($horario as $h) {
                $diaH = $h['dia'];
                if ($diaH == $diaG) {                    
                    // consulta si la actividad esta llena sino devuelve none
                    $actividad = KidsPlanSemanalHoraClase::find()->where([
                        'plan_semanal_id' => $kidsPlanSemanalId,
                        'clase_id' => $h['clase_id'],
                        'detalle_id' => $h['detalle_id']
                    ])->one();
                    if (isset($actividad->actividades)) {
                        $actividades = $actividad->actividades;
                    } else {
                        $actividades = 'none';
                    } //fin de consuta de si existe actidades                    

                    if($h['clase_id']){
                        $h['actividades'] = $actividades;       //Inyecta al arreglo las actividades                                 
                        $h['total_destrezas'] = $this->get_total_destrezas($kidsPlanSemanalId, $h['clase_id'], $h['detalle_id'], $usuario);                                                            
                        $h['total_tareas'] = $this->get_total_tareas($kidsPlanSemanalId, $h['clase_id'], $h['detalle_id'], $usuario);                    
                        $h['total_ambitos'] = $this->get_total_ambitos($kidsPlanSemanalId, $h['clase_id'], $h['detalle_id'], $usuario);                    
                    }else{
                        $h['actividades'] = 0;
                        $h['total_destrezas'] = 0;
                        $h['total_tareas'] = 0;
                        $h['total_ambitos'] = 0;
                    }                    
                    array_push($arrayHoras, $h);
                }
            }
        
            $d['horas'] = $arrayHoras;

            array_push($arrayDias, $d);
        }



        // echo '<pre>';
        // print_r($arrayDias);
        // die();

        return $this->render('detalle', [
            'kidsPlanSemanal' => $kidsPlanSemanal,
            'horario' => $horario,
            'arrayDias' => $arrayDias
        ]);
    }


    private function get_horario($cabeceraId, $periodoId, $courseId, $usuario)
    {
        $con = Yii::$app->db;
        $query = "select 	dia.nombre as dia, hor.nombre as hora, det.id as detalle_id
                            ,(select 	m.nombre 
                            from	scholaris_clase c
                                    inner join op_course_paralelo p on p.id = c.paralelo_id 
                                    inner join op_course cu on cu.id = p.course_id 
                                    inner join op_faculty f on f.id = c.idprofesor 
                                    inner join res_users u on u.partner_id = f.partner_id 
                                    inner join scholaris_horariov2_horario h on h.clase_id = c.id 
                                    inner join scholaris_horariov2_detalle d on d.id = h.detalle_id 
                                    inner join ism_area_materia am on am.id = c.ism_area_materia_id 
                                    inner join ism_materia m on m.id = am.materia_id 
                            where 	cu.id = $courseId
                                    and u.login = '$usuario'
                                    and d.id = det.id limit 1) as materia
                            ,(select c.id  
                            from scholaris_clase c
                            inner join op_course_paralelo p on p.id = c.paralelo_id
                            inner join op_course cu on cu.id = p.course_id
                            inner join op_faculty f on f.id = c.idprofesor
                            inner join res_users u on u.partner_id = f.partner_id
                            inner join scholaris_horariov2_horario h on h.clase_id = c.id
                            inner join scholaris_horariov2_detalle d on d.id = h.detalle_id
                            inner join ism_area_materia am on am.id = c.ism_area_materia_id 
                            inner join ism_materia m on m.id = am.materia_id 
                            where cu.id = $courseId
                            and u.login = '$usuario'
                            and d.id = det.id limit 1) as clase_id
                            ,(select concat(cu.name, ' ', p.name) 
                            from scholaris_clase c
                            inner join op_course_paralelo p on p.id = c.paralelo_id
                            inner join op_course cu on cu.id = p.course_id
                            inner join op_faculty f on f.id = c.idprofesor
                            inner join res_users u on u.partner_id = f.partner_id
                            inner join scholaris_horariov2_horario h on h.clase_id = c.id
                            inner join scholaris_horariov2_detalle d on d.id = h.detalle_id
                            inner join ism_area_materia am on am.id = c.ism_area_materia_id 
                            inner join ism_materia m on m.id = am.materia_id 
                            where cu.id = $courseId
                            and u.login = '$usuario'
                            and d.id = det.id limit 1) as curso
                    from 	scholaris_horariov2_cabecera cab
                            inner join scholaris_horariov2_detalle det on det.cabecera_id = cab.id 
                            inner join scholaris_horariov2_dia dia on dia.id = det.dia_id
                            inner join scholaris_horariov2_hora hor on hor.id = det.hora_id 
                    where 	cab.id = $cabeceraId
                            and cab.periodo_id = $periodoId
                    order by dia.numero, hor.numero;";

        $res = $con->createCommand($query)->queryAll();
        return $res;
    }


    /**
     * Método que entrega el horario asignado de la planificacion
     */
    private function get_horario_asignado($courseId)
    {
        $con = Yii::$app->db;
        $query = "select 	c.asignado_horario 
                        from	scholaris_clase c 
                                inner join op_course_paralelo p on p.id = c.paralelo_id 
                                inner join op_course cur on cur.id = p.course_id 
                        where 	cur.id = $courseId
                        limit 1;";

        $res = $con->createCommand($query)->queryOne();

        return $res['asignado_horario'];
    }

    private function get_total_destrezas($planSemanalId, $claseId, $detalleId, $userLog)
    {
        $con = Yii::$app->db;
        $query = "select 	count(hd.id) as total_destrezas
        from 	kids_plan_semanal_hora_destreza hd
                inner join kids_plan_semanal_hora_clase hc on hc.id = hd.hora_clase_id 
        where hc.created ilike '$userLog'
                and hc.plan_semanal_id = $planSemanalId
                and hc.clase_id = $claseId
                and hc.detalle_id = $detalleId;";
        $res = $con->createCommand($query)->queryOne();

        return $res['total_destrezas'];
    }

    private function get_total_ambitos($planSemanalId, $claseId, $detalleId, $userLog)
    {
        $con = Yii::$app->db;
        $query = "select 	a.nombre
        from 	kids_plan_semanal_hora_destreza hd
                inner join kids_plan_semanal_hora_clase hc on hc.id = hd.hora_clase_id
                inner join kids_micro_destreza md on md.id = hd.micro_destreza_id  
                inner join cur_curriculo_destreza d on d.id = md.destreza_id 
                inner join cur_curriculo_ambito a on a.id = d.ambito_id 
        where hc.created ilike '$userLog'
                and hc.plan_semanal_id = $planSemanalId
                and hc.clase_id = $claseId
                and hc.detalle_id = $detalleId
        group by a.nombre;";
        $res = $con->createCommand($query)->queryOne();

        $total = 0;
        if($res){
            $total = count($res);
        }

        return $total;
    }
    
    private function get_total_tareas($planSemanalId, $claseId, $detalleId, $userLog)
    {
        $con = Yii::$app->db;
        $query = "select 	count(hd.id) as total_destrezas
        from 	kids_plan_semanal_hora_destreza hd
                inner join kids_plan_semanal_hora_clase hc on hc.id = hd.hora_clase_id
                inner join kids_destreza_tarea t on t.plan_destreza_id = hd.id 
        where hc.created ilike '$userLog'
                and hc.plan_semanal_id = $planSemanalId
                and hc.clase_id = $claseId
                and hc.detalle_id = $detalleId;";
        $res = $con->createCommand($query)->queryOne();

        return $res['total_destrezas'];
    }

    public function actionPdf(){
        $planSemanalId = $_GET['plan_semanal_id'];
        new PdfPlanSemanal($planSemanalId);

        
    }
}
