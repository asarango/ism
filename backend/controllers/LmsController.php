<?php

namespace backend\controllers;

use Yii;
use backend\models\Lms;
use backend\models\LmsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * LmsController implements the CRUD actions for Lms model.
 */
class LmsController extends Controller
{
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

    /**
     * Lists all Lms models.
     * @return mixed
     */
    public function actionIndex1()
    {
        
        $claseId        = $_GET['clase_id'];
        $semanaNumero   = $_GET['semana_numero'];
        $nombreSemana   = $_GET['nombre_semana'];
        
        $modelClase = \backend\models\ScholarisClase::findOne($claseId);        
        $this->inyecta_plan_x_hora($modelClase->ismAreaMateria->total_horas_semana, $semanaNumero, $modelClase->ism_area_materia_id, $modelClase->tipo_usu_bloque);                        
        
        $lms = Lms::find()->where([
            'ism_area_materia_id' => $modelClase->ism_area_materia_id,
            'semana_numero' => $semanaNumero
        ])
                ->orderBy('hora_numero')
                ->all();
        
        
        $modelDetalleActivo = Lms::find()->where([
            'ism_area_materia_id' => $modelClase->ism_area_materia_id,
            'semana_numero' => $semanaNumero,
            'estado_activo' => true
        ])->one();                
        
        isset($modelDetalleActivo) ? $detalleId = $modelDetalleActivo->id : $detalleId = 0;
        
        $helper = new \backend\models\helpers\Scripts();
        $seccion = $helper->get_seccion_x_ism_area_materia($modelClase->ism_area_materia_id);
        
        $tipoActividadNac = \backend\models\ScholarisTipoActividad::find()
                ->where(['tipo' => 'N'])
                ->orderBy('nombre_pai')->all();
        
        $tipoActividadPai = \backend\models\ScholarisTipoActividad::find()
                ->where(['tipo' => 'P'])
                ->orderBy('nombre_pai')->all();
        
        
        
        $actividades = \backend\models\LmsActividad::find()->where([
            'lms_id' => $detalleId
        ])->all();
        
        
        return $this->render('index', [
            'modelClase' => $modelClase,
            'nombreSemana' => $nombreSemana,
            'lms' => $lms,
            'modelDetalleActivo' => $modelDetalleActivo,
            'seccion' => $seccion,
            'tipoActividadNac' => $tipoActividadNac,
            'tipoActividadPai' => $tipoActividadPai,
            'actividades' => $actividades
        ]);
    }
    
    private function inyecta_plan_x_hora($totalHoras, $semanNumero, $ismAreaMateriaId, $uso){

        $usuarioLog = Yii::$app->user->identity->usuario;
        $hoy        = date("Y-m-d H:i:s");
        
        $lms = Lms::find()->where([
            'ism_area_materia_id' => $ismAreaMateriaId,
            'semana_numero' => $semanNumero
        ])
                ->orderBy('semana_numero')
                ->all();
        
        isset($lms) ? $totalPlanificado = count($lms) : $totalPlanificado = 0;
        
        $totalInyectar = $totalHoras - $totalPlanificado;
        
        $horaNumero = $this->toma_ultima_hora($semanNumero, $ismAreaMateriaId); //Para toma el ultimo numero de la tabla LMS 
        
        $horaNumero ? $horaNumero = $horaNumero : $horaNumero = 0; //Convierte a 0 si no existe ultimo numero en la tabla lms
        
        
        
        if($totalInyectar > 0){
            //inyectamos las horas con texto sin planificar            
            for($i=0; $i<$totalInyectar; $i++){
                $horaNumero++;
                $modelLms = new Lms();
                $modelLms->ism_area_materia_id          = $ismAreaMateriaId;
                $modelLms->tipo_bloque_comparte_valor   = $uso;
                $modelLms->semana_numero                = $semanNumero;
                $modelLms->hora_numero                  = $horaNumero;
                $modelLms->tipo_recurso                 = 'TEMA-HORA';
                $modelLms->titulo                       = 'NO CONFIGURADO';
                $modelLms->publicar                     = false;
                $modelLms->created                      = $usuarioLog;
                $modelLms->created_at                   = $hoy;
                $modelLms->updated                      = $usuarioLog;
                $modelLms->updated_at                   = $hoy;
                $modelLms->save();
            }
        }
        
    }
    
    private function toma_ultima_hora($semanNumero, $ismAreaMateriaId){
        $con = Yii::$app->db;
        $query = "select 	max(hora_numero) as ultima from 	lms where ism_area_materia_id = $ismAreaMateriaId and semana_numero = $semanNumero;";
        $res = $con->createCommand($query)->queryOne();
        
        if($res){
            return $res['ultima'];
        }else{
            return 0;
        }
        
        
    }
    
    
    
    public function actionDetalle(){
        
        $lmsId = $_GET['lms_id'];
        $claseId = $_GET['clase_id'];
        $nombreSemana = $_GET['nombre_semana'];

        
        $lms = Lms::findOne($lmsId);
        $this->update_estado_activo_falso($lms->ism_area_materia_id, $lms->semana_numero);
        $lms->estado_activo = true;
        $lms->save();
        return $this->redirect(['index1',
            'clase_id'      => $claseId,
            'semana_numero' => $lms->semana_numero,
            'nombre_semana' => $nombreSemana
            
            ]);
        
    }
    
    
    private function update_estado_activo_falso($ismAreaMateriaId, $semanaNumero){
        $con = \Yii::$app->db;
        $query = "update lms set estado_activo = false where ism_area_materia_id = $ismAreaMateriaId and semana_numero = $semanaNumero";
        $con->createCommand($query)->execute();
    }
    
    public function actionAcciones(){                
        
        $lmsId = $_POST['lms_id'];
        $campo = $_POST['campo'];
        $usuarioLog = Yii::$app->user->identity->usuario;
        $hoy = date('Y-m-d H:i:s');
        
        
        if($campo == 'titulo'){
            $valor = $_POST['valor'];        
            $model = Lms::findOne($lmsId);
            $model->$campo = $valor;
            $model->save();
        }else if($campo == 'actividad'){
            
            //para ingresar en lms_actividad
            $model = new \backend\models\LmsActividad();
            $model->lms_id              = $lmsId;         
            $_POST['tipo_actividad'] == 'N' ? $model->tipo_actividad_id   = $_POST['tipo_actividad_nac_id'] : $model->tipo_actividad_id   = $_POST['tipo_actividad_pai_id'];
            $model->titulo              = $_POST['titulo'];
            $model->descripcion         = $_POST['descripcion'];
            $model->tarea               = $_POST['tarea'];
            
            $_POST['es_calificado'] == 'on' ? $model->es_calificado = 'true' : $model->es_calificado   = false;                        
            $_POST['es_publicado'] == 'on' ? $model->es_publicado = 'true' : $model->es_publicado   = false;
            
            $model->material_apoyo      = $_POST['material_apoyo'];

            $model->created             = $usuarioLog;
            $model->created_at          = $hoy;
            $model->updated             = $usuarioLog;
            $model->updated_at          = $hoy;
                        
            $this->insertar_actividad($model);
            
            return $this->redirect(['index1',
                    'clase_id' => $_POST['clase_id'],
                    'semana_numero' => $_POST['semana_numero'],
                    'nombre_semana' => $_POST['nombre_semana']                
                ]);
                
        }else if($campo == 'upload'){
            
            $ismAreaMateriaId = $_POST['path_ism_area_materia_id'];
            
            $path = 'files/docentes/lms/'.$ismAreaMateriaId;
            $file = $_FILES;
            
            $upload = new \backend\models\helpers\Scripts();
            $carga = $upload->upload_file($file, $path);
            
            if($carga == false){
                echo 'error al cargar archivo';
                die();                
            }else{   
                $this->insertar_registros_archivo($_POST, $carga);
                
                return $this->redirect(['acciones-get',                    
                    'lms_id' => $_POST['lms_id'],
                    'actividad_id' => $_POST['lms_actividad_id'],
                    'campo' => 'update',
                    'claseId' => $_POST['claseId'],
                    'numeroSemana' => $_POST['numeroSemana'],
                    'nombreSemana' => $_POST['nombreSemana']                
                ]);
            }
        }
        
        

    }
    
    private function insertar_registros_archivo($post, $pathArchivo){
        $con = \Yii::$app->db;
        $lmsActividadId = $post['lms_actividad_id'];
        $aliasArchivo   = $post['alias_archivo'];
        $archivo        = $pathArchivo;
        $ismMateriaId   = $post['path_ism_area_materia_id'];
        $post['es_publicado'] == 'on' ? $esPublicado = 'true' : $esPublicado = 'false';
        
        $query = "insert into lms_actividad_x_archivo (lms_actividad_id, alias_archivo, archivo, path_ism_area_materia_id, es_publicado) "
                . "values($lmsActividadId, '$aliasArchivo', '$archivo', $ismMateriaId, $esPublicado)";
        $con->createCommand($query)->execute();
    }
    
    public function actionAccionesGet(){
        $lmsId          = $_GET['lms_id'];
        $campo          = $_GET['campo'];
        $actividadId    = $_GET['actividad_id'];
        $claseId        = $_GET['claseId'];
        $nombreSemana   = $_GET['nombreSemana'];
        $numeroSemana   = $_GET['numeroSemana'];
       
                        
        $modelActividad = \backend\models\LmsActividad::findOne($actividadId);
//        $modelArchivos  = \backend\models\LmsActividadXArchivo::find()->where([
//            'lms_actividad_id' => $lmsId
//        ])->all();
        
        return $this->render('update-actividad',[
            'modelActividad' => $modelActividad,
            'clase_id' => $claseId,
            'nombre_semana' => $nombreSemana,
            'numero_semana' => $numeroSemana
        ]);
        
    }
    
    private function insertar_actividad($model){
        
        $con    = \Yii::$app->db;
        $query  = "insert into lms_actividad (lms_id, tipo_actividad_id, titulo, descripcion, tarea, es_calificado, es_publicado, material_apoyo, es_aprobado, created, created_at, updated, updated_at) "
                . "values($model->lms_id, $model->tipo_actividad_id, '$model->titulo', '$model->descripcion', '$model->tarea', $model->es_calificado,"
                . "$model->es_publicado, '$model->material_apoyo', false, '$model->created', '$model->created_at', '$model->updated', '$model->updated_at')";
        $con->createCommand($query)->execute();        
    }

    
}
