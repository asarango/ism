<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisClase;
use backend\models\ScholarisClaseSearch;
use backend\models\ScholarisPeriodo;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ScholarisClaseController implements the CRUD actions for ScholarisClase model.
 */
class ScholarisClaseAuxController extends Controller {
    /**
     * {@inheritdoc}
     */
//    public function behaviors() {
//        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'rules' => [
//                    [
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ]
//                ],
//            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['POST'],
//                ],
//            ],
//        ];
//    }
//    
//    public function beforeAction($action) {
//        if (!parent::beforeAction($action)) {
//            return false;
//        }
//
//        if (Yii::$app->user->identity) {
//            
//            //OBTENGO LA OPERACION ACTUAL
//            list($controlador, $action) = explode("/", Yii::$app->controller->route);
//            $operacion_actual = $controlador . "-" . $action;
//            //SI NO TIENE PERMISO EL USUARIO CON LA OPERACION ACTUAL
//            if(!Yii::$app->user->identity->tienePermiso($operacion_actual)){
//                echo $this->render('/site/error',[
//                   'message' => "Acceso denegado. No puede ingresar a este sitio !!!", 
//                    'name' => 'Acceso denegado!!',
//                ]);
//            }
//        } else {
//            header("Location:" . \yii\helpers\Url::to(['site/login']));
//            exit();
//        }
//        return true;
//    }

    /**
     * Creates a new ScholarisClase model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new ScholarisClase();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing ScholarisClase model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate() {
        
        $sentencias = new \backend\models\SentenciasClase();
        if(isset(Yii::$app->user->identity->periodo_id)){
            $periodoId = Yii::$app->user->identity->periodo_id;
            $institutoId = Yii::$app->user->identity->instituto_defecto;
        }else{
            return $this->redirect(['site/login']);
        }    
        
        
        $modelDocentes = \backend\models\OpFaculty::find()->orderBy('last_name')->all();
        $modelHorarioA = \backend\models\ScholarisHorariov2Cabecera::find()->where(['periodo_id' => $periodoId])->all();
        $modelTipoBloque = \backend\models\ScholarisBloqueComparte::find()
                ->where(['instituto_id' => $institutoId])
                ->orderBy('nombre')
                ->all();
        $modelAutoridades = $this->get_autoridades($institutoId);

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $model = $this->findModel($id);

            
            
            
            $modelGrupo = $sentencias->get_alumnos_clase($id, $periodoId);
            
            $modelDias = \backend\models\ScholarisHorariov2Dia::find()->orderBy('numero')->all();
            $modelHoras = $sentencias->get_horas_horario($model->asignado_horario);                        
            
            return $this->render('update', [
                        'model' => $model,
                        'modelDocentes' => $modelDocentes,
                        'modelHorarioA' => $modelHorarioA,
                        'modelTipoBloque' => $modelTipoBloque,
                        'modelAutoridades' => $modelAutoridades,
                        'modelGrupo' => $modelGrupo,
                        'modelDias' => $modelDias,
                        'modelHoras' => $modelHoras
            ]);
        } else {
            if ($_POST) {
                $id = $_POST['id'];
                $model = $this->findModel($id);
                
//                echo '<pre>';
//                print_r($_POST);
//                die();
                
                $model->idprofesor = $_POST['idprofesor'];
                                
                $model->asignado_horario = $_POST['horario_asignado'];
                $model->tipo_usu_bloque = $_POST['tipo_usu_bloque'];
                
                $model->todos_alumnos = $_POST['todos_alumnos'];
                $model->rector_id = $_POST['rector_id'];
                $model->coordinador_dece_id = $_POST['coordinador_dece_id'];
                $model->secretaria_id = $_POST['secretaria_id'];
                $model->coordinador_academico_id = $_POST['coordinador_academico_id'];
                $model->inspector_id = $_POST['inspector_id'];
                $model->dece_dhi_id = $_POST['dece_dhi_id'];
                $model->tutor_id = $_POST['tutor_id'];
                $model->es_activo = $_POST['es_activo'];
                
                $model->save();

//                $modelMalla = \backend\models\ScholarisMallaCurso::find()
//                        ->where(['curso_id' => $model->idcurso])
//                        ->one();

                $modelGrupo = $sentencias->get_alumnos_clase($id, $periodoId);
                
                $modelDias = \backend\models\ScholarisHorariov2Dia::find()->orderBy('numero')->all();
                $modelHoras = $sentencias->get_horas_horario($model->asignado_horario); 

                return $this->render('update', [
                            'model' => $model,
                            'modelDocentes' => $modelDocentes,
                            'modelHorarioA' => $modelHorarioA,
                            'modelTipoBloque' => $modelTipoBloque,
                            'modelAutoridades' => $modelAutoridades,
                            'modelGrupo' => $modelGrupo,
                            'modelDias' => $modelDias,
                            'modelHoras' => $modelHoras
                ]);
            }
        }
    }
    
    private function get_autoridades($institutoId){
        $con = Yii::$app->db;
        $query = "select 	a.id 
                                    ,concat(rp.name, ' (', u.usuario ,')') as usuario
                                    ,r.rol 
                    from 	usuario u
                                    inner join res_users ru on ru.login = u.usuario 
                                    inner join res_partner rp on rp.id = ru.partner_id
                                    inner join rol r on r.id = u.rol_id 
                                    inner join op_institute_authorities a on a.usuario = u.usuario 
                    where	u.instituto_defecto = $institutoId
                    order by rp.name;";
        $res = $con->createCommand($query)->queryAll();        
        
        return $res;
    }

    protected function findModel($id) {
        if (($model = ScholarisClase::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionQuitar(){
        $sentencias = new \backend\models\SentenciasClase();
        $sentencias->quitar_clase_horario($_GET['clase'], $_GET['detalle']);

        return $this->redirect(['update','id' => $_GET['clase']]);
    }
    
    public function actionAsignar(){               
        
            $sentencias = new \backend\models\SentenciasClase();
            $model = \backend\models\ScholarisHorariov2Detalle::find()
                    ->where([
                            'cabecera_id' => $_GET['cabecera'],
                            'hora_id' => $_GET['hora'],
                            'dia_id' => $_GET['dia'],
                        ])
                    ->one();

            $sentencias->asignar_clase_horario($_GET['clase'], $model->id);
            return $this->redirect(['update','id' => $_GET['clase']]);
                
    }

}
