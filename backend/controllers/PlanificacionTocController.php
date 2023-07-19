<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ScholarisActividadController implements the CRUD actions for ScholarisActividad model.
 */
class PlanificacionTocController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
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

    public function beforeAction($action) {
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

    /** ACCIÓN PARA REALIZAR LA PANTALLA DE OPCIONES DE PLANIFICACIÓN TOC
     * Creado por Arturo Sarango 2023-06-02
     * Actualizado por Arturo Sarango 2023-06-02
    */
    public function actionIndex() {
        $user = Yii::$app->user->identity->usuario;
        $periodId = Yii::$app->user->identity->periodo_id;

        $classes = $this->get_classes($user, $periodId);

        return $this->render('index', [
            'classes' => $classes
        ]);
    }


    /** Método para tomar los cursos de toc 
     * Creado por Arturo Sarango 2023-06-02
     * Actualizado por Arturo Sarango 2023-06-02
    */
    private function get_classes($user, $periodId){
        $con = Yii::$app->db;
        $query = "select 	cur.name as curso
                            ,par.name as paralelo
                            ,mat.nombre as materia
                            ,mat.siglas 
                            ,cla.id as clase_id
                    from 	scholaris_clase cla
                            inner join op_faculty fac on fac.id = cla.idprofesor 
                            inner join res_users rus on rus.partner_id = fac.partner_id 
                            inner join ism_area_materia iam on iam.id = cla.ism_area_materia_id 
                            inner join ism_malla_area ima on ima.id = iam.malla_area_id 
                            inner join ism_periodo_malla ipm on ipm.id = ima.periodo_malla_id 
                            inner join op_course_paralelo par on par.id = cla.paralelo_id 
                            inner join op_course cur on cur.id = par.course_id 
                            inner join ism_materia mat on mat.id = iam.materia_id 
                    where 	rus.login = '$user'
                            and ipm.scholaris_periodo_id = $periodId
                            and (mat.nombre ilike 'Teoria del conocimiento'
                                or mat.siglas ilike 'TDC'
                                or mat.siglas ilike 'TOC'
                                or mat.nombre ilike 'Teoría del conocimiento'
                            )
                    order by cur.name, par.name;";
    
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    

}
