<?php

namespace backend\controllers;

use backend\models\IsmArea;
use backend\models\IsmCriterio;
use backend\models\IsmCriterioDescriptorArea;
use backend\models\IsmCriterioLiteral;
use backend\models\IsmDescriptores;
use backend\models\IsmLiteralDescriptores;
use backend\models\OpCourseTemplate;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ScholarisActividadController implements the CRUD actions for ScholarisActividad model.
 */
class CriteriosPaiController extends Controller {
    
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
    
    public function actionIndex(){
        
        $areas              = IsmArea::find()->orderBy('nombre')->all();
        $courses            = OpCourseTemplate::find()->orderBy('next_course_id')->all();
        $criterios          = IsmCriterio::find()->orderBy('nombre')->all();
        $criteriosLiteral   = IsmCriterioLiteral::find()->orderBy('nombre_espanol')->all();
        $descriptores       = IsmDescriptores::find()->orderBy('nombre')->all();
        $decripLiteral      = IsmLiteralDescriptores::find()->orderBy('descripcion')->all();

        $distribucion = IsmCriterioDescriptorArea::find()->all();

        return $this->render('index',[
            'areas' => $areas,
            'courses' => $courses,
            'criterios' => $criterios,
            'criteriosLiteral' => $criteriosLiteral,
            'descriptores' => $descriptores,
            'decripLiteral' => $decripLiteral,
            'distribucion' => $distribucion
        ]);
        
    }

    public function actionActions(){
        $field = $_POST['field'];
        $periodId = Yii::$app->user->identity->periodo_id;
        
        if($field == 'search_areas'){

        }else if($field == 'search_by_area'){            
            $courseId = $_POST['course_id'];
            $areaId = $_POST['area_id'];

            $criteria = $this->get_x_area($courseId, $areaId);

            return $this->renderPartial('by-area-one', [
                'criteria' => $criteria
            ]);
        }else if($field == 'delete'){
            $model = IsmCriterioDescriptorArea::findOne($_POST['id']);
            $model->delete();
        }else if($field == 'add_descriptor'){            
            $model = new IsmCriterioDescriptorArea();
            $model->id_area     = $_POST['area_id'];
            $model->id_curso    = $_POST['course_id'];
            $model->id_criterio = $_POST['criterio_id'];
            $model->id_literal_criterio = $_POST['criterio_literal_id'];
            $model->id_descriptor = $_POST['descriptor_id'];
            $model->id_literal_descriptor = $_POST['descriptor_literal_id'];
            $model->save();
        }
    }

    private function get_x_area($courseId, $areaId){
        $con = Yii::$app->db;
        $query = "select 	dis.id
                            ,tem.name as curso
                            ,area.nombre as area
                            ,cri.nombre as criterio
                            ,cli.nombre_espanol 
                            ,des.nombre as descriptor
                            ,ild.descripcion 
                    from 	ism_criterio_descriptor_area dis
                            inner join op_course_template tem on tem.id = dis.id_curso 
                            inner join ism_area area on area.id = dis.id_area 
                            inner join ism_criterio cri on cri.id = dis.id_criterio 
                            inner join ism_criterio_literal cli on cli.id = dis.id_literal_criterio 
                            inner join ism_descriptores des on des.id = dis.id_descriptor 
                            inner join ism_literal_descriptores ild on ild.id = dis.id_literal_descriptor 
                    where 	dis.id_curso = $courseId 
                            and dis.id_area = $areaId
                    order by cri.nombre, des.nombre ;";
                   
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
  
}

?>