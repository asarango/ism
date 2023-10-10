<?php

namespace backend\controllers;

use backend\models\notas\NotasProfesor;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use kartik\mpdf\Pdf;
use backend\models\ScholarisClase;
use backend\models\ScholarisBloqueActividad;
use backend\models\ScholarisGrupoAlumnoClase;
use backend\models\ScholarisCalificaciones;
use backend\models\ScholarisParametrosOpciones;
use backend\models\ScholarisPeriodo;
use frontend\models\SentenciasSql;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class ReporteNotasProfesorNacController extends Controller {

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

    public function actionIndex1() {
        $claseId = $_GET['clase_id'];
        $periodoId = Yii::$app->user->identity->periodo_id;
        $periodo = ScholarisPeriodo::findOne($periodoId);

        $clase = ScholarisClase::findOne($claseId);


        // Para tomar el trimestre por defecto
        if(isset($_GET['trimestre_defecto'])){
            $trimestreId = $_GET['trimestre_defecto'];
            $trimestre = ScholarisBloqueActividad::findOne($trimestreId);
        }else{
            $trimestre = ScholarisBloqueActividad::find()->where([
                'scholaris_periodo_codigo' => $periodo->codigo,
                'orden' => 1 
            ])
            ->one();
        }
        // Fin de para tomar el trimestre por defecto

        //## Toma la información de los bloques trimestres 
        $trimestres = $this->get_trimestres($periodo->codigo, $claseId);


        //## Llamamos a nuestra clase de NotasProfesor para el reporte de los calculos
        // $notasProfesor->grupo 'CONTIENE LA INFORMACION DE LA LISTA DE GRUPOS'
        // $notasProfesor->tipoActividades 'CONTIENE EL TIPO DE ACTIVIDADES'
        // $notasProfesor->actividades 'CONTIENE LA LISTA DE ACTIVIDADES'
        // $notasProfesor->notas 'CONTIENE LAS CALIFICACIONES DE LAS ACTIVIDADES'
        // $notasProfesor->cabecera 'CONTIENE LA CABECERA DE LAS ACTIVIDADES'
        // $notasProfesor->promediosFinales 'CONTIENE LOS PROMEDIOS GENERALES'
        $notasProfesor = new NotasProfesor($claseId, $trimestre->id);

        // echo '<pre>';
         //print_r($notasProfesor->tipoActividades);
         //print_r($notasProfesor->grupo);
         //print_r($notasProfesor->notas_x_actividad);
        //  print_r($notasProfesor->cabecera);
         
        // die();


        return $this->render('index', [
            'trimestres' => $trimestres,
            'clase' => $clase,
            'trimestre' => $trimestre,
            'notasProfesor' => $notasProfesor
        ]);



    }


    private function get_trimestres($periodoCodigo, $claseId) {
        $con = Yii::$app->db;
        $query = "select 	b.id as bloque_id
                            ,b.name as trimestre
                            ,(
                                select 	trunc(avg(nota),2) as promedio
                                from 	lib_bloques_grupo_clase lib
                                        inner join scholaris_grupo_alumno_clase gru on gru.id = lib.grupo_id
                                where	gru.clase_id = $claseId
                                        and lib.bloque_id = b.id
                            )
                    from 	scholaris_bloque_actividad b
                    where 	b.scholaris_periodo_codigo = '$periodoCodigo'
                    order by b.orden;";

        $res = $con->createCommand($query)->queryAll();
        return $res;
    }


    
}
