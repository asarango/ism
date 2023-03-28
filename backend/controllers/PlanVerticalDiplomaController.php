<?php

namespace backend\controllers;

use backend\models\diplomaphpv\PdfNew;
use backend\models\OpInstitute;
use backend\models\PlanificacionDesagregacionCabecera;
use backend\models\PlanVerticalDiploma;
use backend\models\PlanVerticalDiplomaComponente;
use backend\models\ResUsers;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class PlanVerticalDiplomaController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if (Yii::$app->user->identity) {

            //OBTENGO LA OPERACION ACTUAL
            list($controlador, $action) = explode("/", Yii::$app->controller->route);
            $operacion_actual = $controlador . "-" . $action;
            //SI NO TIENE PERMISO EL USUARIO CON LA OPERACION ACTUAL
            if (!Yii::$app->user->identity->tienePermiso($operacion_actual)) {
                echo $this->render('/site/error', [
                    'message' => "Acceso denegado. No puede ingresar a este sitio !!!",
                    'name' => 'Acceso denegado!!',
                ]);
            }
        } else {
            header("Location:" . \yii\helpers\Url::to(['site/login']));
            exit();
        }
        return true;
    }



    /**
     * ACCION PARA PLAN VERTICAL DE DIPLOMA
     * ELABORADO POR: Arturo Sarango - 2023-03-20
     * ACTUALIZADO POR: Arturo Sarango - 2023-03-20
     */
    public function actionIndex1()
    {
        $periodoId = Yii::$app->user->identity->periodo_id;
        $planificacionCabeceraId = $_GET['cabecera_id'];
        $cabecera = PlanificacionDesagregacionCabecera::findOne($planificacionCabeceraId);
        $materiaId = $cabecera->ismAreaMateria->materia_id;

        $this->add_elements($cabecera);

        $plan = PlanVerticalDiploma::find()->where(['cabecera_id' => $planificacionCabeceraId])->orderBy('id')->all();
        $unidades = $this->get_planes_unidad($materiaId, $periodoId);

        $componentes = PlanVerticalDiplomaComponente::find()
            ->where(['cabecera_id' => $planificacionCabeceraId])
            ->orderBy('id')
            ->all();

        return $this->render('index', [
            'cabecera'  => $cabecera,
            'plan'      => $plan,
            'unidades'  => $unidades,
            'componentes' => $componentes
        ]);
    }


    /**
     * MÉTODO QUE INGRESA LOS ELEMENTOS DEL PLAN VERTICAL
     * ELABORADO POR: Arturo Sarango - 2023-03-20
     * ACTUALIZADO POR: Arturo Sarango - 2023-03-20
     */
    public function add_elements($cabecera)
    {
        $user = Yii::$app->user->identity->usuario;
        $inst = Yii::$app->user->identity->instituto_defecto;
        $today = date('Y-m-d H:i:s');

        $plan = PlanVerticalDiploma::find()->where(['cabecera_id' => $cabecera->id])->all();


        /** ingresa nombre de la asignatura **/
        $this->add_text('datos', 'asignatura', $cabecera->ismAreaMateria->materia->nombre, $user, $today, $cabecera->id, $plan);

        /** ingresa codigo del colegio **/
        $modelInstitute = OpInstitute::findOne($inst);
        $this->add_text('datos', 'colegio', $modelInstitute->name, $user, $today, $cabecera->id, $plan);

        /** ingresa nivel **/
        $this->add_select('datos', 'nivel', 'Superior', $user, $today, $cabecera->id, $plan);
        $this->add_select('datos', 'nivel', 'Medio completado en dos años', $user, $today, $cabecera->id, $plan);
        $this->add_select('datos', 'nivel', 'Medio completado en un año *', $user, $today, $cabecera->id, $plan);

        /** ingresa artes **/
        $this->add_text('datos', 'artes_visuales', '', $user, $today, $cabecera->id, $plan);
        $this->add_text('datos', 'artes_musica', '', $user, $today, $cabecera->id, $plan);

        /** Profesor que completa el esquema **/
        $resUser = ResUsers::find()->where(['login' => $user])->one();
        $this->add_text('datos', 'profesor', $resUser->partner->name, $user, $today, $cabecera->id, $plan);

        /** Fecha de capacitación de IB */
        $this->add_text('datos', 'fecha_cap', '0000-00-00', $user, $today, $cabecera->id, $plan);

        /** Fecha de esquema completo */
        $this->add_text('datos', 'fecha_completo', '0000-00-00', $user, $today, $cabecera->id, $plan);

        /** Nombre del taller */
        $this->add_text('datos', 'taller', '', $user, $today, $cabecera->id, $plan);

        /** Una clase dura */
        $this->add_text('datos', 'clase_dura', '', $user, $today, $cabecera->id, $plan);

        /** En una semana hay */
        $this->add_text('datos', 'semana_hay', '', $user, $today, $cabecera->id, $plan);

        /** Imprevistos */
        $this->add_text('datos', 'imprevisto', '', $user, $today, $cabecera->id, $plan);

        /** Total de semanas clase */
        $this->add_text('datos', 'semanas_total', '', $user, $today, $cabecera->id, $plan);

        /** Ejes transversales */
        $this->add_text('datos', 'ejes', '', $user, $today, $cabecera->id, $plan);

        /** VÍCULO CON TDC */
        $this->add_text('datos', 'tema_tdc', '', $user, $today, $cabecera->id, $plan);
        $this->add_text('datos', 'vinculo_tdc', '', $user, $today, $cabecera->id, $plan);


        /** VÍCULO CON TDC */
        $this->add_text('datos', 'tema_enfoque', '', $user, $today, $cabecera->id, $plan);
        $this->add_text('datos', 'contibu_enfoque', '', $user, $today, $cabecera->id, $plan);


        /** MENTALIDAD INTERNACIONAL */
        $this->add_text('datos', 'tema_menta', '', $user, $today, $cabecera->id, $plan);
        $this->add_text('datos', 'contibu_menta', '', $user, $today, $cabecera->id, $plan);

        /** DESARROLLO DE PERFIL DE LA COMUNIDAD DE APRENDIZAJE DEL IB */
        $this->add_text('datos', 'tema_perfil', '', $user, $today, $cabecera->id, $plan);
        $this->add_text('datos', 'contibu_perfil', '', $user, $today, $cabecera->id, $plan);

        /** INSTALACIONES Y EQUIPOS */
        $this->add_text('datos', 'equipos', '', $user, $today, $cabecera->id, $plan);

        /** RECURSOS */
        $this->add_text('datos', 'recursos', '', $user, $today, $cabecera->id, $plan);

        /** BIBLIOGRAFÍA */
        $this->add_text('datos', 'bibliografia', '', $user, $today, $cabecera->id, $plan);


        /** FIRMAS */
        $this->add_text('elaborado_por', 'nombre', '', $user, $today, $cabecera->id, $plan);
        $this->add_text('elaborado_el', 'fecha', '', $user, $today, $cabecera->id, $plan);
        $this->add_text('aprobado_por', 'nombre', '', $user, $today, $cabecera->id, $plan);
        $this->add_text('elaborado_por', 'fecha', '', $user, $today, $cabecera->id, $plan);
    }

    /**
     * MÉTODO QUE EJECUTA LA INSERCIÓN DEL REGISTRO DEL PLAN VERTICAL
     * ELABORADO POR: Arturo Sarango - 2023-03-20
     * ACTUALIZADO POR: Arturo Sarango - 2023-03-20
     */
    public function add_text($typeSection, $typeField, $text, $user, $today, $cabeceraId, $plan)
    {
        $model = PlanVerticalDiploma::find()->where([
            'cabecera_id'   => $cabeceraId,
            'tipo_seccion'  => $typeSection,
            'tipo_campo'    => $typeField,
            'opcion_texto'  => $text
        ])->one();

        if (!$model) {
            $pv = new PlanVerticalDiploma();
            $pv->cabecera_id    = $cabeceraId;
            $pv->tipo_seccion   = $typeSection;
            $pv->tipo_campo     = $typeField;
            $pv->opcion_texto   = $text;
            $pv->created        = $user;
            $pv->created_at     = $today;
            $pv->updated        = $user;
            $pv->updated_at     = $today;
            $pv->save();
        }
    }


    /**
     * MÉTODO QUE EJECUTA LA INSERCIÓN DEL REGISTRO DEL PLAN VERTICAL OPCIONES DE SELECCIÓN
     * ELABORADO POR: Arturo Sarango - 2023-03-20
     * ACTUALIZADO POR: Arturo Sarango - 2023-03-20
     */
    public function add_select($typeSection, $typeField, $option, $user, $today, $cabeceraId, $plan)
    {
        $model = PlanVerticalDiploma::find()->where([
            'cabecera_id'   => $cabeceraId,
            'tipo_seccion'  => $typeSection,
            'tipo_campo'    => $typeField,
            'opcion_texto'  => $option
        ])->one();

        if (!$model) {
            $pv = new PlanVerticalDiploma();
            $pv->cabecera_id    = $cabeceraId;
            $pv->tipo_seccion   = $typeSection;
            $pv->tipo_campo     = $typeField;
            $pv->opcion_texto   = $option;
            $pv->opcion_seleccion = false;
            $pv->created        = $user;
            $pv->created_at     = $today;
            $pv->updated        = $user;
            $pv->updated_at     = $today;
            $pv->save();
        }
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
    


    /**
     * ACCIÓN PARA ACTUALIZAR EL PLAN VERTICAL
     * ELABORADO POR: Arturo Sarango - 2023-03-22
     * ACTUALIZADO POR: Arturo Sarango - 2023-03-22
     */

    function actionUpdate()
    {
        $planId = $_POST['plan_id'];
        $field = $_POST['field'];

        $model = PlanVerticalDiploma::findOne($planId);

        if ($field == 'seleccion') {
            $active = $model->opcion_seleccion ? false : true;
            $model->opcion_seleccion = $active;
            $model->save();
        } elseif ($field == 'texto') {
            $planId = $_POST['plan_id'];
            $contenido = $_POST['content'];

            $model->opcion_texto = $contenido;
            $model->save();
        }
    }


    /**
     * ACCIÓN PARA AGREGAR EVALUACIONES AL PLAN VERTICAL
     * ELABORADO POR: Arturo Sarango - 2023-03-23
     * ACTUALIZADO POR: Arturo Sarango - 2023-03-23
     */
    public function actionComponentes(){
        print_r($_POST);
        $cabeceraId = $_POST['cabecera_id'];
        $evaluacion = $_POST['evaluacion'];
        $actividad = $_POST['actividad'];
        $fecha = $_POST['fecha'];

        $model = new PlanVerticalDiplomaComponente();
        $model->cabecera_id = $cabeceraId;
        $model->evaluacion = $evaluacion;
        $model->actividad = $actividad;
        $model->fecha = $fecha;
        $model->save();

        return $this->redirect(['index1', 'cabecera_id' => $cabeceraId]);
    }


    /**
     * ACCIÓN PARA ACTUALIZAR Y ELIMINAR COMPONENTES
     * ELABORADO POR: Arturo Sarango - 2023-03-24
     * ACTUALIZADO POR: Arturo Sarango - 2023-03-24
     */
    public function actionAccionComponente(){
        $id     = $_POST['id'];
        $field  = $_POST['field'];

        $model = PlanVerticalDiplomaComponente::findOne($id);

        if($field == 'consultar'){
            $json = json_encode(array(
                'id' => $model->id,
                'cabecera_id' => $model->cabecera_id,
                'evaluacion' => $model->evaluacion,
                'actividad' => $model->actividad,
                'fecha' => $model->fecha
            ));

            return $json;
        }else if($field == 'update'){
            
            $model->evaluacion = $_POST['evaluacion'];
            $model->actividad = $_POST['com-actividad'];
            $model->fecha = $_POST['com-fecha'];
            print_r($model);
            die();
            $model->save();

            return $this->redirect(['index1', 'cabecera_id' => $model->cabecera_id]); 
        }else if($field == 'delete'){
            $model->delete();
            return $this->redirect(['index1', 'cabecera_id' => $model->cabecera_id]); 
        }

    }


    /**
     * ACCIÓN PARA CONVERTIR A PDF
     * ELABORADO POR: Arturo Sarango - 2023-03-27
     * ACTUALIZADO POR: Arturo Sarango - 2023-03-27
     */
    public function actionPdf(){
        $cabeceraId = $_GET['cabecera_id'];
        new PdfNew($cabeceraId);
    }
}
