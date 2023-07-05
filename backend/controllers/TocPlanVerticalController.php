<?php

namespace backend\controllers;

use backend\models\ScholarisClase;
use backend\models\toc\PdfTocAnual;
use backend\models\toc\TocCopy;
use backend\models\TocPlanUnidad;
use backend\models\TocPlanUnidadDetalle;
use backend\models\TocPlanUnidadHabilidad;
use backend\models\TocPlanVertical;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\BaseUrl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ScholarisActividadController implements the CRUD actions for ScholarisActividad model.
 */
class TocPlanVerticalController extends Controller {

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
    public function actionIndex1() {
        $classId = $_GET['clase_id'];        
        $this->inyecta_opciones($classId);
        $this->inyecta_unidades($classId);

        $vertical = TocPlanVertical::find()->where(['clase_id' => $classId])->all();
        $unidades = TocPlanUnidad::find()->where(['clase_id' => $classId])->orderBy('id')->all();

        $this->inyecta_habilidades($unidades);

        return $this->render('index', [
            'vertical' => $vertical,
            'unidades' => $unidades,
            'claseId' => $classId
        ]);
    }

    private function inyecta_unidades($claseId){
        $user = Yii::$app->user->identity->usuario;
        $today = date('Y-m-d H:i:s');

        $clase = ScholarisClase::findOne($claseId);
        $uso = $clase->tipo_usu_bloque;

        $con = Yii::$app->db;
        $query = "insert into toc_plan_unidad (bloque_id, clase_id, created, created_at, updated, updated_at)
                    select 	blo.id, $claseId, '$user', '$today', '$user', '$today'
                    from 	scholaris_bloque_actividad blo
                    where 	blo.tipo_uso = '$uso'
                            and tipo_bloque = 'PARCIAL'
                            and blo.id not in (
                                select bloque_id from toc_plan_unidad where clase_id = $claseId and bloque_id = blo.id 			
                            )
                    order by blo.orden;";
        $con->createCommand($query)->execute();
    }

    private function inyecta_opciones($claseId){
        $user = Yii::$app->user->identity->usuario;
        $today = date('Y-m-d H:i:s');

        $con = Yii::$app->db;
        $query = "insert into toc_plan_vertical (clase_id, opcion_descripcion, contenido, tipo, created_at, created, updated_at, updated)
                    select 	$claseId, op.descripcion, 'none', op.tipo ,'$today', '$user', '$today', '$user' 
                    from 	toc_opciones op
                    where 	op.planificacion = 'VERTICAL'
                            and estado = true
                            and seccion <> 'APRENDIZAJE'
                            and descripcion not in (select 	opcion_descripcion 
                                from 	toc_plan_vertical
                                where 	clase_id = $claseId);";

        $res = $con->createCommand($query)->execute();
    }



    public function actionUpdateField(){
        $user = Yii::$app->user->identity->usuario;
        $today = date("Y-m-d H:i:s");
        $id = $_POST['id'];
        $content = $_POST['contenido'];

        $model = TocPlanVertical::findOne($id);

        $classId = $model->clase->id;

        $model->contenido = $content;
        $model->updated = $user;
        $model->updated_at = $today;
        $model->save();

        return $this->redirect(['index1', 'clase_id' => $classId]);


    }


    /**
     * MÉTODO PARA ACTUALIZAR CAMPOS DE PLAN DE UNIDAD
     * creado       por: Arturo Sarango  el 2023-06-09
     * actualizado  por: Arturo Sarango  el 2023-06-09
     */
    public function actionUpdateUnits(){
        if(isset($_GET['id'])){
            $unidadId = $_GET['id'];
            $unidad = TocPlanUnidad::findOne($unidadId);
            return $this->render('update-units', [
                'unidad' => $unidad
            ]);
        }else{
            $user = Yii::$app->user->identity->usuario;
            $today = date("Y-m-d H:i:s");

            $id     = $_POST['id'];        
            $model = TocPlanUnidad::findOne($id);
            $model->titulo     = $_POST['titulo'];
            $model->objetivos  = $_POST['objetivos'];
            $model->conceptos_clave = $_POST['conceptos_clave'];
            $model->contenido  = $_POST['contenido'];
            $model->evaluacion_pd = $_POST['evaluacion_pd'];
            $model->updated = $user;
            $model->updated_at = $today;
            $model->save();      
            return $this->redirect(['index1',
                'clase_id' => $model->clase_id
            ]);      
        }
    }

    /**
     * METODO PARA DEVOLVER LAS HABLIDADES DE UNIDAD
     */
    public function actionHabilidades(){
        $tocPlanUnidadId = $_GET['id'];
        $unidad = TocPlanUnidad::findOne($tocPlanUnidadId);
        $habilidades = TocPlanUnidadHabilidad::find()
            ->where(['toc_plan_unidad_id' => $tocPlanUnidadId])
            ->orderBy('id')
            ->all();

        return $this->render('habilidades', [
            'unidad' => $unidad,
            'habilidades' => $habilidades
        ]);
    }

    private function inyecta_habilidades($unidades){
        $user = Yii::$app->user->identity->usuario;
        $today = date('Y-m-d H:i:s');
        $con = Yii::$app->db;
        
        foreach($unidades as $unidad){
            $unidadId = $unidad->id;
            $query = "insert into toc_plan_unidad_habilidad (toc_plan_unidad_id, toc_opciones_id, created, created_at, updated, updated_at)
                        select 	$unidadId
                                ,op.id
                                ,'$user'
                                ,'$today'
                                ,'$user'
                                ,'$today'
                        from 	toc_opciones op
                        where 	op.seccion = 'ENFOQUES'
                                and op.id not in (select toc_opciones_id 
                                                    from toc_plan_unidad_habilidad
                                                    where	toc_opciones_id = op.id
                                                            and toc_plan_unidad_id = $unidadId)
                        order by op.opcion, op.id;";
            // echo '<pre>';                        
            // print_r($query);
            $con->createCommand($query)->execute();
        }
        
    }


    /**
     * metodo para cambiar la opcion de unidades
     */
    public function actionChangeHabilidad(){
        $id = $_POST['id'];
        
        $model = TocPlanUnidadHabilidad::findOne($id);

        if($model->is_active){
            $model->is_active = false;
        }else{
            $model->is_active = true;
        }

        $model->save();
    }


    /**
     * MÉTODO PARA REALIZAR LA COPIA DE LOS PLANES DE TOC
     */
    public function actionCopy(){
        $claseId = $_GET['clase_id'];
        $clase = ScholarisClase::findOne($claseId);
        $clases = $this->get_clases($clase->ism_area_materia_id, $clase->paralelo->course_id);

        return $this->render('copy', [
            'clase' => $clase,
            'clases' => $clases
        ]);
    }

    private function get_clases($ismAreaMateriaId, $cursoId){

        $con = Yii::$app->db;
        $query = "select 	cla.id as clase_id
                    ,cur.name as curso
                    ,par.name as paralelo
                    ,cur.x_institute 
                    ,cla.idprofesor 
                    ,concat(fac.x_first_name, ' ', fac.last_name) as docente 
                    ,concat('toc-plan-vertical/pdf?clase_id=', cla.id) as url
            from 	scholaris_clase cla
                    inner join op_course_paralelo par on par.id = cla.paralelo_id 
                    inner join op_course cur on cur.id = par.course_id 
                    inner join op_faculty fac on fac.id = cla.idprofesor 
            where 	cla.ism_area_materia_id = $ismAreaMateriaId
                    and par.course_id = $cursoId
            order by par.name;";
        // echo $query;
        $res = $con->createCommand($query)->queryAll();
        return $res;
    } 


    public function actionExecuteCopy(){
        $claseIdDesde = $_GET['clase_desde'];
        $claseIdHasta  = $_GET['clase_hasta'];
        $user = Yii::$app->user->identity->usuario;
        $today = date('Y-m-d H:i:s');
        new TocCopy($claseIdDesde, $claseIdHasta);
        
        return $this->redirect(['index1', 'clase_id' => $claseIdHasta]);                
    }


    /**
     * Método para realizar el PDF de Toc
     */
    public function actionPdf(){
        $claseId = $_GET['clase_id'];

        new PdfTocAnual($claseId);
    }
    


}