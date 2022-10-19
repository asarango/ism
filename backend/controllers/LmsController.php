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

        
        $lmsAux = Lms::findOne($lmsId);
        $this->update_estado_activo_falso($lmsAux->ism_area_materia_id, $lmsAux->semana_numero);                
        
        $lms = Lms::findOne($lmsId);
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
        }else if($campo == 'indicaciones'){
            $valor = $_POST['valor'];        
            $model = Lms::findOne($lmsId);
            $model->$campo = $valor;
            $model->save();
        }
        else if($campo == 'actividad'){
            
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
            $seccion = $_POST['seccion'];
            
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
                    'nombreSemana' => $_POST['nombreSemana'],                
                    'seccion' => $_POST['seccion']                
                ]);
            }
        }else if($campo == 'actualizar'){
            $userLog = Yii::$app->user->identity->usuario;
            $hoy = date('Y-m-d H:i:s');
            
            $id             = $_POST['id'];
            $titulo         = $_POST['titulo'];
            $descripcion    = $_POST['descripcion'];
            $tarea          = $_POST['tarea'];
            $material       = $_POST['material'];
            $seccion       = $_POST['seccion'];
            
            $model = \backend\models\LmsActividad::findOne($id);
            $model->titulo      = $titulo;
            $model->descripcion = $descripcion;
            $model->tarea       = $tarea;
            $model->material_apoyo = $material;
                        
            isset($_POST['es_calificado']) ? $model->es_calificado = true : $model->es_calicado = false;
            isset($_POST['es_publicado'])  ? $model->es_publicado = true : $model->es_publicado = false;
            
            $model->updated = $userLog;
            $model->updated_at = $hoy;            
            
            $model->save();
            
            return $this->redirect(['acciones-get',
                    'lms_id'        => $model->lms_id,
                    'campo'         => 'update',
                    'actividad_id'  => $id,
                    'claseId'       => $_POST['clase_id'],
                    'numeroSemana'  => $_POST['semana_numero'],
                    'nombreSemana'  => $_POST['nombre_semana'],                
                    'seccion'       => $_POST['seccion']                
                ]);
            
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
        $seccion        = $_GET['seccion'];
                               
        $modelActividad = \backend\models\LmsActividad::findOne($actividadId);
        $modelClase = \backend\models\ScholarisClase::findOne($claseId);
        $uso = $modelClase->tipo_usu_bloque;
        
        $scholarisPeriodoId = Yii::$app->user->identity->periodo_id;
        
        $criterios = $this->get_criterios_pai($uso, $scholarisPeriodoId, $modelActividad->lms->ism_area_materia_id, $numeroSemana, $actividadId);
        
        return $this->render('update-actividad',[
            'modelActividad' => $modelActividad,
            'clase_id' => $claseId,
            'nombre_semana' => $nombreSemana,
            'numero_semana' => $numeroSemana,
            'seccion' => $seccion,
            'criterios' => $criterios
        ]);
        
    }
    
    
    private function get_criterios_pai($uso, $scholarisPeriodoId, $ismAreaMateriaId, $semanaNumero, $actividadId){
        $con = Yii::$app->db;
        $query = "select 	des.id  
                                    ,cri.nombre as criterio
                                    ,ide.nombre as descriptor
                                    ,ild.descripcion 
                                    ,lpai.id as lms_criterio_id
                    from 	planificacion_vertical_pai_descriptores des
                                    inner join planificacion_bloques_unidad uni on uni.id = des.plan_unidad_id
                                    inner join planificacion_desagregacion_cabecera cab on cab.id = uni.plan_cabecera_id 
                                    inner join curriculo_mec_bloque cbl on cbl.id = uni.curriculo_bloque_id 
                                    inner join scholaris_bloque_actividad blo on blo.orden = cbl.code 
                                                    and blo.tipo_uso = '$uso'
                                    inner join scholaris_bloque_semanas sem on sem.bloque_id = blo.id 
                                    inner join ism_criterio_descriptor_area isc on isc.id = des.descriptor_id 
                                    inner join ism_criterio cri on cri.id = isc.id_criterio  
                                    inner join ism_descriptores ide on ide.id = isc.id_descriptor 
                                    inner join ism_literal_descriptores ild on ild.id = isc.id_literal_descriptor 
                                    left join lms_actividad_criterios_pai lpai on lpai.plan_vertical_descriptor_id = des.id 
                                            and lpai.lms_actividad_id = $actividadId
                    where 	cab.scholaris_periodo_id = $scholarisPeriodoId
                                    and cab.ism_area_materia_id = $ismAreaMateriaId
                                    and sem.semana_numero = $semanaNumero
                    order by cri.nombre asc
                                    ,ide.nombre;";
        
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    private function insertar_actividad($model){
        
        $con    = \Yii::$app->db;
        $query  = "insert into lms_actividad (lms_id, tipo_actividad_id, titulo, descripcion, tarea, es_calificado, es_publicado, material_apoyo, es_aprobado, created, created_at, updated, updated_at) "
                . "values($model->lms_id, $model->tipo_actividad_id, '$model->titulo', '$model->descripcion', '$model->tarea', $model->es_calificado,"
                . "$model->es_publicado, '$model->material_apoyo', false, '$model->created', '$model->created_at', '$model->updated', '$model->updated_at')";
        $con->createCommand($query)->execute();        
    }
    
    
    public function actionAsignar(){
        
        $lmsActividadId             = $_GET['actividadId'];
        $lmsId                      = $_GET['lms_id'];
        $planVerticalDescriptorId   = $_GET['plan_vertical_descriptor_id'];
        $campo                      = $_GET['campo'];
        $claseId                    = $_GET['clase_id'];
        $semanaNumero               = $_GET['semana_numero'];
        $nombreSemana               = $_GET['nombre_semana'];
        $seccion                    = $_GET['seccion'];
        
        $model = new \backend\models\LmsActividadCriteriosPai();
        $model->lms_actividad_id            = $lmsActividadId;
        $model->plan_vertical_descriptor_id = $planVerticalDescriptorId;
        $model->save();
        
        return $this->redirect(['acciones-get',
                'lms_id'        => $lmsId,
                'campo'         => $campo,
                'actividad_id'  => $lmsActividadId,
                'claseId'       => $claseId,
                'numeroSemana'  => $semanaNumero,
                'nombreSemana'  => $nombreSemana,
                'seccion'       => $seccion            
            ]);
    }
    
    
    public function actionQuitar(){
                
        $id                         = $_GET['id'];
        $lmsId                      = $_GET['lms_id'];
        $campo                      = $_GET['campo'];
        $lmsActividadId                = $_GET['actividadId'];
        $claseId                    = $_GET['clase_id'];
        $semanaNumero               = $_GET['semana_numero'];
        $nombreSemana               = $_GET['nombre_semana'];
        $seccion                    = $_GET['seccion'];
        
        $model = \backend\models\LmsActividadCriteriosPai::findOne($id);
        $model->delete();
        
        return $this->redirect(['acciones-get',
                'lms_id'        => $lmsId,
                'campo'         => $campo,
                'actividad_id'  => $lmsActividadId,
                'claseId'       => $claseId,
                'numeroSemana'  => $semanaNumero,
                'nombreSemana'  => $nombreSemana,
                'seccion'       => $seccion            
            ]);
        
    }

    
}

