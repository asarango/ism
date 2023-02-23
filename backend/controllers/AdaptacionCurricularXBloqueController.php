<?php

namespace backend\controllers;

use Yii;
use backend\models\AdaptacionCurricularXBloque;
use backend\models\AdaptacionCurricularXBloqueSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\helpers\HelperGeneral;
use backend\models\Nee;
use backend\models\NeeXClase;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use yii\helpers\Html;

/**
 * AdaptacionCurricularXBloqueController implements the CRUD actions for AdaptacionCurricularXBloque model.
 */
class AdaptacionCurricularXBloqueController extends Controller
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
            if(!Yii::$app->user->identity->tienePermiso($operacion_actual)){
                echo $this->render('/site/error',[
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
     * Lists all AdaptacionCurricularXBloque models.
     * @return mixed
     */
    public function actionIndex()
    {
        // $searchModel = new AdaptacionCurricularXBloqueSearch();
        // $dataProvider = $searchModel->search(Yii::$app->request->queryParams);        

        // return $this->render('index', [
        //     'searchModel' => $searchModel,
        //     'dataProvider' => $dataProvider,
        // ]);

        $user = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;
        $objHelper = new HelperGeneral();       
        $cursos = $objHelper->get_cursos_docente($user,$periodoId);
        $dato_profesor = $objHelper->obtener_curso_materias_estudiante($user,$periodoId,'5');

        // echo '<pre>';
        // print_r($dato_profesor);
        // die();

        return $this->render('index', [
            'cursos' => $cursos
        ]);
    }

    /**
     * Displays a single AdaptacionCurricularXBloque model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new AdaptacionCurricularXBloque model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AdaptacionCurricularXBloque();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AdaptacionCurricularXBloque model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing AdaptacionCurricularXBloque model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AdaptacionCurricularXBloque model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdaptacionCurricularXBloque the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdaptacionCurricularXBloque::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionListMaterias()
    {
        $user = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;
        $objHelper = new HelperGeneral();  
        $cursoId = $_POST['curso_id'];
        
        $dato_profesor = $objHelper->obtener_curso_materias_estudiante($user,$periodoId,$cursoId);

        $html = "";    
        $html .= '<table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">MATERIA</th>
                                    <th class="text-center">CURSO</th>
                                    <th class="text-center">ESTUDIANTE</th>
                                    <th class="text-center">MOSTRAR</th>
                                </tr>
                            </thead>';   
                $html .='<tbody id="table-body">'; 

                foreach ($dato_profesor as $datos) 
                {
                    $html .= '<tr>';
                                    // $html .= '<td class="text-center">' . $datos['idmateria'] . '</td>';            
                                    $html .= '<td class="text-center">' . $datos['materia'] . '</td>';
                                    // $html .= '<td class="text-center">' . $datos['idcurso'] . '</td>';
                                    $html .= '<td class="text-center">' . $datos['curso'] . '</td>';
                                    // $html .= '<td class="text-center">' . $datos['idestudiante'] . '</td>';
                                    $html .= '<td class="text-center">' . $datos['estudiante'] . '</td>';

                                    $html .= '<td class="text-center">';
                                        $html .= '<div class="btn-group" role="group">';                                    
                                        $html .= '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modelNee_'.$datos['idneexclase'].'">
                                                        Launch demo modal
                                                    </button>';
                                            $html .= '<ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                                            $html .= '<li>';
                                            //$html .= Html::a('Desagregación', ['desagregacion', 'id' => $asignatura['id']], ['class' => 'dropdown-item', 'style' => 'font-size:10px']);
                                            //$html .= Html::a('Planificar', ['planificacion-bloques-unidad/index1', 'id' => $asignatura['id']], ['class' => 'dropdown-item', 'style' => 'font-size:10px']);
                                            $html .= '</li>';
                                            $html .= '</ul>';
                                        $html .= '</div>';
                                    $html .= '</td>';
                                $html .= '</tr>';
                                $html .= '</tr>';                    
                }
                $html .='</tbody>';
        $html .= '</table>';       


        foreach ($dato_profesor as $datos) 
        {
            $html .= '<div class="modal face" id="modelNee_'.$datos['idneexclase'].'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <table>
                                        <tr>    
                                            <td><h2>Adaptación Curricular</h2></td>
                                        </tr>
                                        <tr>    
                                            <td><h6><b>'.$datos['estudiante'].'</b></h6></td>
                                        </tr>
                                    </table>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <table>
                                        <tr>
                                            <td>Grado: </td>
                                            <td>'.$datos['grado_nee'].'</td>
                                        </tr>
                                        <tr>
                                            <td>Fecha: </td>
                                            <td>'.$datos['fecha_inicia'].'</td>
                                        </tr>
                                        <tr>
                                            <td>Diagnóstico: </td>
                                            <td>'.$datos['diagnostico_inicia'].'</td>
                                        </tr>
                                        <tr>
                                            <td>Adaptación: </td>
                                            <td>
                                                <textarea class="form-control" id="adaptacion_clase_'.$datos['idneexclase'].'" rows="3"></textarea>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <button type="button" class="btn btn-primary" onclick="guardar_nee_x_clase('.$datos['idneexclase'].')">Guardar</button>
                                </div>
                            </div>
                        </div>
                    </div>';
        }
        $html .= '<hr>';
        $html .= $this->mostrarEstudiantesAdaptacionNee($user,$periodoId,$cursoId);

        return $html;
    }

    public function mostrarEstudiantesAdaptacionNee($user,$periodoId,$cursoId)
    {
        $objHelper = new HelperGeneral();  
        $dato_profesor = $objHelper->obtener_curso_materias_estudiante_nee($user,$periodoId,$cursoId);
        $html ='';

        echo '<pre>';
        print_r($dato_profesor);

        foreach ($dato_profesor as $datos) 
        {
            print_r("entreo");
            $html .= '<div class="modal face" id="modelNee_'.$datos['idneexclase'].'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <table>
                                            <tr>    
                                                <td><h2>Adaptación Curricular</h2></td>
                                            </tr>
                                            <tr>    
                                                <td><h6><b>'.$datos['estudiante'].'</b></h6></td>
                                            </tr>
                                        </table>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <table>
                                            <tr>
                                                <td>Grado: </td>
                                                <td>'.$datos['grado_nee'].'</td>
                                            </tr>';
                                            print_r($html);
                                            $html.='<tr>
                                                <td>Fecha: </td>
                                                <td>'.$datos['fecha_inicia'].'</td>
                                            </tr>
                                            <tr>
                                                <td>Diagnóstico: </td>
                                                <td>'.$datos['diagnostico_inicia'].'</td>
                                            </tr>
                                            <tr>
                                                <td>Adaptación: </td>
                                                <td>
                                                    <textarea class="form-control" id="adaptacion_clase_'.$datos['idneexclase'].'" rows="3"></textarea>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <button type="button" class="btn btn-primary" onclick="guardar_nee_x_clase('.$datos['idneexclase'].')">Guardar</button>
                                    </div>
                                </div>
                            </div>
                        </div>';
                        print_r($html);
                        print_r("salio");
        }
        echo '<pre>';
        print_r("fin de datos");
        //print_r($html);
        die();
        return $html;        
    }


    public function actionGuardarAdaptacion()
    {
        $user = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;
        $objHelper = new HelperGeneral();  
        $idNeeXClase = $_POST['idNeeXClase'];
        $id_adaptacion = $_POST['id_adaptacion'];
        $id_bloque = $_POST['id_bloque'];
        

        $fechaActual = date('Y-m-d');
        $hora = date('H:i:s');

        $modelAdaptacion = new AdaptacionCurricularXBloque();
        $modelAdaptacion->id_nee_x_clase = $idNeeXClase;
        $modelAdaptacion->adaptacion_curricular = $id_adaptacion;
        $modelAdaptacion->id_curriculum_mec_bloque = $id_bloque;
        $modelAdaptacion->creado_por = $user;
        $modelAdaptacion->fecha_creacion = $fechaActual.' '.$hora;
        $modelAdaptacion->actualizado_por = '-';
        $modelAdaptacion->fecha_actualizacion = '1900-01-01 00:00:00';
        $modelAdaptacion->save(); 

        echo '<pre>';
        print_r($modelAdaptacion);
        die();

        
    }
}