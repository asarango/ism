<?php

namespace backend\controllers;

use backend\models\IsmGrupoMateriaPlanInterdiciplinar;
use Yii;
use backend\models\IsmGrupoPlanInterdiciplinar;
use backend\models\IsmGrupoPlanInterdiciplinarSearch;
use backend\models\ScholarisBloqueActividad;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;

/**
 * IsmGrupoPlanInterdiciplinarController implements the CRUD actions for IsmGrupoPlanInterdiciplinar model.
 */
class IsmGrupoPlanInterdiciplinarController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all IsmGrupoPlanInterdiciplinar models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new IsmGrupoPlanInterdiciplinarSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $seleccion_a_buscar ="PAI";
        $cursos = $this->obtener_cursos($seleccion_a_buscar);
        $listaBloques = ScholarisBloqueActividad::find()
        ->where(['ILIKE','name','BLOQUE'])
        ->andWhere(['ILIKE','name','PAI'])
        ->all();        

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'cursos'=>$cursos,
            'listaBloques'=>$listaBloques,
        ]);
    }

    /**
     * Displays a single IsmGrupoPlanInterdiciplinar model.
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
     * Creates a new IsmGrupoPlanInterdiciplinar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new IsmGrupoPlanInterdiciplinar();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing IsmGrupoPlanInterdiciplinar model.
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
     * Deletes an existing IsmGrupoPlanInterdiciplinar model.
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
     * Finds the IsmGrupoPlanInterdiciplinar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return IsmGrupoPlanInterdiciplinar the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = IsmGrupoPlanInterdiciplinar::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**************************************************************************************************************** */
      /************************************************************************************************************** */
    public function obtener_cursos($seccion)
    {
        //$user = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;
        $con = Yii::$app->db;
        $query = "select id,code,name,x_template_id,x_institute,section  
                from op_course oc where section in (
                select id from op_section sec
                inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = sec.period_id 
                where sop.scholaris_id = $periodoId and code = '$seccion');";        
        
        $resp = $con->createCommand($query)->queryAll();

        return $resp;
        
    }
    public function actionObtenerMateria()
    {
        //$user = Yii::$app->user->identity->usuario;
        //$periodoId = Yii::$app->user->identity->periodo_id;
        $idCurso = $_POST['curso_id'];
        $con = Yii::$app->db;
        $html = "";
        $query = "select id,nombre,siglas from ism_materia im 
                    where id in 
                    (
                        select materia_id from ism_area_materia iam where id in 
                        (
                            select  ism_area_materia_id from scholaris_clase 
                            where paralelo_id in (
                                select id
                                from op_course_paralelo where course_id =$idCurso
                            )
                        )
                    ) order by nombre ;";        
        
        $listaMaterias = $con->createCommand($query)->queryAll();

        // echo '<pre>';
        // print_r($resp);
        // die();

        foreach ($listaMaterias as $materias) {
            $html .= '<tr>';
            $html .= '<td class="text-center">' . $materias['id'] . '</td>';            
            $html .= '<td class="text-center">' . $materias['nombre'] . '</td>';          
            $html .= '<td class="text-center">';
                $html .= '<div class="btn-group" role="group">';
                $html .= '<button style="font-size: 10px; border-radius: 0px" id="btnGroupDrop1" type="button" class="btn btn-outline-warning btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">';
                $html .= 'Acciones';
                $html .= '</button>';
                $html .= '<ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<li>';
                $html .= Html::a('Planificar', ['planificacion-bloques-unidad/index1', 'id' => $materias['id']], ['class' => 'dropdown-item', 'style' => 'font-size:10px']);
                $html .= '</li>';
                $html .= '</ul>';
                $html .= '</div>';
            $html .= '</td>';
            $html .= '<td class="text-center">
                        <input class="form-control" type="text" id="'.$materias['id'].'_materia" style="width:35px;" />
                    </td>'; 
            $html .= '<td class="text-center">
                        <button class="form-control" type="text" id="'.$materias['id'].'_materia" style="width:35px;" onclick=asignar_grupo("'.$materias['id'].'_materia")>
                            pasar
                        </button>
                     </td>'; 
            $html .= '</tr>';
        }

        return $html;
        
    }
    public function actionAsignarGrupo()
    {
        $con = Yii::$app->db;
        $fechaActual = date('Y-m-d');
        $hora = date('H:i:s');

        $grupo= $_POST['grupo'];
        $idbloque = $_POST['idbloque'];
        $idcurso = $_POST['idcurso'];
        $periodoId = Yii::$app->user->identity->periodo_id;
        $user = Yii::$app->user->identity->usuario;

        $modelGrupoInter = new IsmGrupoPlanInterdiciplinar();
        $modelGrupoInter->id_bloque = $idbloque;
        $modelGrupoInter->id_op_course = $idcurso;
        $modelGrupoInter->nombre_grupo = "Grupo ".$grupo;
        $modelGrupoInter->id_periodo =  $periodoId;
        $modelGrupoInter->created_at =  $fechaActual.' '.$hora;
        $modelGrupoInter->created =  $user;
        $modelGrupoInter->save();
        //pendiente modificar en el modelo el nol null de las fechas de modificacion




        $modelMateriaGrupo = new IsmGrupoMateriaPlanInterdiciplinar();
        $modelMateriaGrupo->


        
        $html = "";
        $query = "select id,nombre,siglas from ism_materia im 
                    where id in 
                    (
                        select materia_id from ism_area_materia iam where id in 
                        (
                            select  ism_area_materia_id from scholaris_clase 
                            where paralelo_id in (
                                select id
                                from op_course_paralelo where course_id =$idCurso
                            )
                        )
                    ) order by nombre ;";        
        
        $listaMaterias = $con->createCommand($query)->queryAll();

    }
    
}
