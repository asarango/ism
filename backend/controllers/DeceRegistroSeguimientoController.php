<?php

namespace backend\controllers;

use backend\models\DeceCasos;
use backend\models\DeceMotivos;
use backend\models\dece\DeceAcompaniamientoPdf;
use Yii;
use backend\models\DeceRegistroSeguimiento;
use backend\models\DeceRegistroSeguimientoSearch;
use backend\models\DeceSeguimientoAcuerdos;
use backend\models\DeceSeguimientoFirmas;
use backend\models\ScholarisGrupoAlumnoClase;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\helpers\Scripts;
use backend\models\messages\Messages;
use yii\web\UploadedFile;
use backend\models\PlanificacionOpciones;
use yii\filters\AccessController;

/**
 * DeceRegistroSeguimientoController implements the CRUD actions for DeceRegistroSeguimiento model.
 */
class DeceRegistroSeguimientoController extends Controller
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
     * Lists all DeceRegistroSeguimiento models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DeceRegistroSeguimientoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DeceRegistroSeguimiento model.
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
     * Creates a new DeceRegistroSeguimiento model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    //recibe el id de scholarisgrupoalumnoclase
    public function actionCreate()
    {
        $userLog = \Yii::$app->user->identity->usuario;
        $user = \backend\models\Usuario::find()->where(['usuario' => $userLog])->one();
        $resUser = \backend\models\ResUsers::find()->where(['login' => $user->usuario])->one();

        $model = new DeceRegistroSeguimiento();
        $fechaActual = date('Y-m-d');
        $hora = date('H:i:s');



        if ($_GET) {
            $id_clase = $_GET['id_clase'];
            $id_estudiante = $_GET['id_estudiante'];
            $id_caso = $_GET['id_caso'];
            //extraigo model casos
            $modelCasos = DeceCasos::findOne($id_caso);
            $model->estado =  $modelCasos->estado;
            $model->motivo = $modelCasos->motivo;

            $model->id_estudiante = $id_estudiante;
            $model->id_clase = $id_clase;
            $model->id_caso = $id_caso;
            $model->fecha_inicio = $fechaActual;
            $model->pronunciamiento = '-';
            $model->acuerdo_y_compromiso = '-';
            $model->eviencia = '-';
            $model->numero_seguimiento = $this->extrae_numero_seguimiento($id_estudiante, $id_caso) + 1;
        }

        /** Extrae path donde se almacena los archivos */
        $path_archivo_dece_atencion = PlanificacionOpciones::find()->where([
            'tipo' => 'SUBIDA_ARCHIVO',
            'categoria' => 'PATH_DECE_SEG'
        ])->one();

        if ($model->load(Yii::$app->request->post())) {
            $imagenSubida = UploadedFile::getInstance($model, 'path_archivo');
            $model->fecha_inicio = $model->fecha_inicio . ' ' . $hora;
            $model->save();

            if (!empty($imagenSubida)) {
                $pathArchivos = $path_archivo_dece_atencion->opcion . $model->id_estudiante . '/' . $model->id . '/';

                //creamos la carpeta
                if (!file_exists($pathArchivos)) {
                    mkdir($pathArchivos, 0777, true);
                    chmod($pathArchivos, 0777);
                }
                $imagenSubida->name = str_replace(' ', '', $imagenSubida->name);
                $path = $pathArchivos . $imagenSubida->name;
                if ($imagenSubida->saveAs($path)) {
                    $model->path_archivo = $model->id_estudiante . '/' . $model->id . '##' . $imagenSubida->name;
                    $model->save();
                }
            }
            return $this->redirect(['update', 'id' => $model->id]);
        }
        return $this->render('create', [
            'model' => $model,
            'resUser' => $resUser,
        ]);
    }
    //metodo que extrae el numero de seguimiento
    private function extrae_numero_seguimiento($idEstudiante, $id_caso)
    {
        $modelRegSeguimiento = DeceRegistroSeguimiento::find()
            ->andWhere(['id_estudiante' => $idEstudiante])
            ->andWhere(['id_caso' => $id_caso])
            ->max('numero_seguimiento');

        return $modelRegSeguimiento;
    }

    /**
     * Updates an existing DeceRegistroSeguimiento model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {

        $userLog = \Yii::$app->user->identity->usuario;
        $periodId    = Yii::$app->user->identity->periodo_id;
        $user = \backend\models\Usuario::find()->where(['usuario' => $userLog])->one();
        $resUser = \backend\models\ResUsers::find()->where(['login' => $user->usuario])->one();
        $model = $this->findModel($id);
        $fechaActual = date('Y-m-d');
        $hora = date('H:i:s');
        $listadoActores = $this->listadoActores($model, $periodId);

        /** Extrae path donde se almacena los archivos */
        $path_archivo_dece_atencion = PlanificacionOpciones::find()->where([
            'tipo' => 'SUBIDA_ARCHIVO',
            'categoria' => 'PATH_DECE_SEG'
        ])->one();
        $pathArchivoModel = $model->path_archivo;

        if ($model->load(Yii::$app->request->post())) {
            $imagenSubida = UploadedFile::getInstance($model, 'path_archivo');

            //if(!empty($model->path_archivo))
            if ($imagenSubida) {
                $imagenSubida = UploadedFile::getInstance($model, 'path_archivo');
                $pathArchivos = $path_archivo_dece_atencion->opcion . $model->id_estudiante . '/' . $model->id . '/';

                //creamos la carpeta
                if (!file_exists($pathArchivos)) {
                    mkdir($pathArchivos, 0777, true);
                    chmod($pathArchivos, 0777);
                }
                $imagenSubida->name = str_replace(' ', '', $imagenSubida->name);
                $path = $pathArchivos . $imagenSubida->name;
                if ($imagenSubida->saveAs($path)) {
                    $model->path_archivo = $model->id_estudiante . '/' . $model->id . '##' . $imagenSubida->name;
                    $model->save();
                }
            } else {
                $model->path_archivo = $pathArchivoModel;
                $model->save();
            }

            return $this->redirect(['update', 'id' => $model->id]);
        }
        //se asigna la fecha de creacion del seguimiento con la fecha de modificacion, para cargar en pantalla
        if ($model) {
            $model->fecha_fin = $fechaActual;
        }

        return $this->render('update', [
            'model' => $model,
            'resUser' => $resUser,
            'listadoActores' => $listadoActores, //union de las tres variables (padres1, profesores, autoridades)
        ]);
    }

    /**
     * Deletes an existing DeceRegistroSeguimiento model.
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

    //*****   PDF  *******
    public function actionPdf()
    {
        // echo '<pre>';
        // print_r($_GET);
        // die();
        $id_acompaniamiento =  $_GET['id'];
        $objDecePdf = new DeceAcompaniamientoPdf($id_acompaniamiento);
    }
    //*****   ACUERDOS  *******
    public function actionGuardarAcuerdos()
    {
        // echo '<pre>';
        // print_r($_POST);
        // die();
        $secuencial = '-1';
        $acuerdo =  $_POST['acuerdo'];
        $responsable = $_POST['responsable'];
        $cumplio = $_POST['cumplio'];
        $id_seguimiento = $_POST['id_seguimiento'];
        $fecha_max_cumplimiento = $_POST['fecha_max_cumplimiento'];
        $parentesco = $_POST['parentesco'];
        $cargo = $_POST['cargo'];
        $cedula = $_POST['cedula'];


        $maxItemAcuerdos = DeceSeguimientoAcuerdos::find()
            ->where(['id_reg_seguimiento' => $id_seguimiento])
            ->max('secuencial');

        if (!($maxItemAcuerdos)) {
            $secuencial = 1;
        } else {
            $secuencial = $maxItemAcuerdos + 1;
        }


        $modelAcuerdo = new DeceSeguimientoAcuerdos();
        $modelAcuerdo->secuencial = $secuencial;
        $modelAcuerdo->acuerdo = $acuerdo;
        $modelAcuerdo->responsable = $responsable;
        $modelAcuerdo->cumplio = $cumplio;
        $modelAcuerdo->id_reg_seguimiento = $id_seguimiento;
        $modelAcuerdo->fecha_max_cumplimiento = $fecha_max_cumplimiento;
        $modelAcuerdo->parentesco = $parentesco;

        $modelAcuerdo->save();

        //agregamos enseguida el registro en la firma
        $modelFirma = new DeceSeguimientoFirmas();
        $modelFirma->nombre = $responsable;
        $modelFirma->cedula = $cedula;
        $modelFirma->parentesco = $parentesco;
        $modelFirma->cargo = $cargo;
        $modelFirma->id_reg_seguimiento = $id_seguimiento;
        $modelFirma->save();

        //Guardamos en un JSON, los html a mostrar en pantalla

        $acuerdos = $this->mostrar_acuerdo($id_seguimiento);
        $firmas = $this->mostrar_firmas($id_seguimiento);
        $arratRetornos['acuerdos'] = $acuerdos;
        $arratRetornos['firmas'] = $firmas;
        $jsonRetorno = json_encode($arratRetornos);


        return $jsonRetorno;
    }

    private function mostrar_acuerdo($id_seguimiento)
    {
        $listAcuerdos = DeceSeguimientoAcuerdos::find()
            ->where(['id_reg_seguimiento' => $id_seguimiento])
            ->orderBy(['secuencial' => SORT_ASC])
            ->all();

        $html = '';
        $html .= '
        <table class="table table-striped table-success">
            <thead>
                <td><b> Ítem </b></td>
                <td><b> Acuerdo </b></td>
                <td><b> Responsable </b></td>
                <td><b> Fecha Cumplimiento </b></td>
                <td><b> Cumplió </b></td>
                <td>Acción</td>
            </thead>
            <tbody>';

        foreach ($listAcuerdos as $acuerdo) {
            $html .= '<tr>';
            $html .= '<td>' . $acuerdo->secuencial . '</td>
                            <td>' . $acuerdo->acuerdo . ' </td>
                            <td>' . $acuerdo->responsable . ' </td>
                            <td> ' . substr($acuerdo->fecha_max_cumplimiento, 0, 10) . '</td>';
            if ($acuerdo->cumplio) {
                $html .= '<td> <input  type="checkbox" onclick="guardar_acuerdo_cumplido(' . $acuerdo->id . ',0)" checked/></td>';
            } else {
                $html .= '<td> <input  type="checkbox" onclick="guardar_acuerdo_cumplido(' . $acuerdo->id . ',1)" /></td>';
            }
            $html .= '<td>
                                        <button type="button" class="btn btn-primary"  id="icono_firmas" onclick="eliminar_acuerdo(' . $acuerdo->id . ')" title="Eliminar Firma">
                                        <i class="fas fa-trash-alt" style="color:white;"></i>
                                        </button>                                                             
                                     </td> ';
            $html .= '</tr>';
        }
        $html .= '</tbody>
        </table>';

        // echo '<pre>';
        // print_r($html);
        // die();


        return $html;
    }

    public function actionGuardarAcuerdosCumplido()
    {
        $id_seg_acuerdo =  $_POST['id_seg_acuerdo'];
        $cumplio = $_POST['cumplio'];
        $model = DeceSeguimientoAcuerdos::findOne($id_seg_acuerdo);
        $model->cumplio = $cumplio;
        $model->save();

        return $this->mostrar_acuerdo($model->id_reg_seguimiento);
    }
    public function actionEliminarAcuerdo()
    {
        $id_seg_acuerdo = $_POST['id_seg_acuerdo'];
        $model = DeceSeguimientoAcuerdos::findOne($id_seg_acuerdo);
        $id_reg_seguimiento = $model->id_reg_seguimiento;
        $model->delete();

        return $this->mostrar_acuerdo($id_reg_seguimiento);
    }

    //*****   FIN ACUERDOS  *******


    //*****   FIRMAS  *******
    public function actionGuardarFirmas()
    {
        $nombre = $_POST['nombre'];
        $cedula = $_POST['cedula'];
        $parentesco = $_POST['parentesco'];
        $cargo = $_POST['cargo'];
        $id_seguimiento = $_POST['id_seguimiento'];
        $modelFirma = new DeceSeguimientoFirmas();
        $modelFirma->nombre = $nombre;
        $modelFirma->cedula = $cedula;
        $modelFirma->parentesco = $parentesco;
        $modelFirma->cargo = $cargo;
        $modelFirma->id_reg_seguimiento = $id_seguimiento;
        $modelFirma->save();


        return $this->mostrar_firmas($id_seguimiento);
    }
    private function mostrar_firmas($id_seguimiento)
    {
        $listFirmas = DeceSeguimientoFirmas::find()
            ->where(['id_reg_seguimiento' => $id_seguimiento])
            ->orderBy(['id' => SORT_ASC])
            ->all();

        $html = '';
        $html .= '
        <table class="table table-striped table-success">
            <thead>
                <td><b> Nombre </b></td>
                <td><b> Cédula </b></td>
                <td><b> Parentesco </b></td>
                <td><b> Cargo </b></td>
            </thead>
            <tbody>';

        foreach ($listFirmas as $firmas) {
            $html .= '<tr>';
            $html .= '<td>' . $firmas->nombre . '</td>
                            <td>' . $firmas->cedula . ' </td>
                            <td>' . $firmas->parentesco . ' </td>
                            <td>' . $firmas->cargo . ' </td>';
            $html .= '<td>
                        <button type="button" class="btn btn-primary"  id="icono_firmas" onclick="eliminar_firma(' . $firmas->id . ')" title="Eliminar Firma">
                        <i class="fas fa-trash-alt" style="color:red;"></i>
                        </button>  
                            
                        </td>';
            $html .= '</tr>';
        }
        $html .= '</tbody>
        </table>';

        return $html;
    }
    public function actionEliminarFirmas()
    {
        $id_seg_firmas = $_POST['id_seg_firmas'];

        $model = DeceSeguimientoFirmas::findOne($id_seg_firmas);
        $id_reg_seguimiento = $model->id_reg_seguimiento;
        $model->delete();

        return $this->mostrar_firmas($id_reg_seguimiento);
    }

    //*****   FIN FIRMAS  *******


    /**
     * Finds the DeceRegistroSeguimiento model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DeceRegistroSeguimiento the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DeceRegistroSeguimiento::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function listadoActores($model, $periodId)
    {
        $con = Yii::$app->db;
        $query = "  select  rp.id, rp.name, rp.numero_identificacion, rp.email,oia.cargo_descripcion 
                    FROM op_institute_authorities oia
                        INNER JOIN res_users ru ON oia.usuario = ru.login
                        INNER JOIN res_partner rp ON rp.id = ru.partner_id
                    WHERE oia.es_activo = true
                    UNION ALL
                    SELECT cla.id as clase_id, CONCAT(fac.last_name, ' ', fac.x_first_name) as docente, rp.numero_identificacion, rp.email, mat.nombre
                    FROM scholaris_grupo_alumno_clase gru
                        INNER JOIN scholaris_clase cla ON cla.id = gru.clase_id 
                        INNER JOIN ism_area_materia iam ON iam.id = cla.ism_area_materia_id 
                        INNER JOIN ism_malla_area ima ON ima.id = iam.malla_area_id 
                        INNER JOIN ism_periodo_malla ipm ON ipm.id = ima.periodo_malla_id 
                        INNER JOIN op_faculty fac ON fac.id = cla.idprofesor  
                        INNER JOIN ism_materia mat ON mat.id = iam.materia_id 
                        INNER JOIN res_partner rp ON rp.id = fac.partner_id  
                    WHERE gru.estudiante_id = $model->id_estudiante AND ipm.scholaris_periodo_id = $periodId
                    UNION ALL 
                    SELECT rp.id, concat(op.first_surname,' ',op.second_surname,' ',op.first_name,' ',op.middle_name), rp.numero_identificacion, rp.email, op.x_state  
                    FROM op_parent op 
                        INNER JOIN res_partner rp ON rp.id = op.name
                        INNER JOIN op_parent_op_student_rel oposr ON op.id = oposr.op_parent_id 
                    WHERE oposr.op_student_id = $model->id_estudiante;";

        $listadoActores = $con->createCommand($query)->queryAll();
        return $listadoActores;
    }

    public function actionEnviarCorreo(){

        print_r($_POST);
        die();

        $studentId = $_POST['id_estudiante'];
        $model = new Messages();
        

        // Define los parámetros para el correo
        $arrayTo = 'destinatario@example.com'; // Cambia esto al correo real
        $from = 'info@ism.edu.ec';
        $subject = 'Asunto del Correo';
        $textBody = 'Cuerpo del correo en texto plano';
        $htmlBody = '<p>Cuerpo del correo en HTML</p>';

        // Llama a la función send_email para enviar el correo
        $model->send_email([$arrayTo], $from, $subject, $textBody, $htmlBody);

        // Puedes redirigir a una vista o realizar otras acciones después de enviar el correo
    }
}
