<?php

namespace backend\controllers;

use backend\models\IsmGrupoMateriaPlanInterdiciplinar;
use Yii;
use yii\filters\AccessControl;
use backend\models\IsmGrupoPlanInterdiciplinar;
use backend\models\IsmGrupoPlanInterdiciplinarSearch;
use backend\models\OpCourse;
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

    // public function beforeAction($action)
    // {
    //     if (!parent::beforeAction($action)) {
    //         return false;
    //     }

    //     if (Yii::$app->user->identity) {

    //         //OBTENGO LA OPERACION ACTUAL
    //         list($controlador, $action) = explode("/", Yii::$app->controller->route);
    //         $operacion_actual = $controlador . "-" . $action;
    //         //SI NO TIENE PERMISO EL USUARIO CON LA OPERACION ACTUAL
    //         if (!Yii::$app->user->identity->tienePermiso($operacion_actual)) {
    //             echo $this->render('/site/error', [
    //                 'message' => "Acceso denegado. No puede ingresar a este sitio !!!",
    //                 'name' => 'Acceso denegado!!',
    //             ]);
    //         }
    //     } else {
    //         header("Location:" . \yii\helpers\Url::to(['site/login']));
    //         exit();
    //     }
    //     return true;
    // }

    /**
     * Lists all IsmGrupoPlanInterdiciplinar models.
     * @return mixed
     */
    public function actionIndex()
    {
        // $searchModel = new IsmGrupoPlanInterdiciplinarSearch();
        // $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $seleccion_a_buscar ="PAI";
        $cursos = $this->obtener_cursos($seleccion_a_buscar);
        $listaBloques = ScholarisBloqueActividad::find()
        ->where(['ILIKE','name','BLOQUE'])
        ->andWhere(['ILIKE','name','PAI'])
        ->all();        

        return $this->render('index', [
            // 'searchModel' => $searchModel,
            // 'dataProvider' => $dataProvider,
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
        //Devuelve los cursos del pai, septimo, octavo, noveno,decimo, bach1
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
        //carga en pantalla las materias, una vez seleccionado bloque y curso        
        $idCurso = $_POST['curso_id'];
        $idbloque = $_POST['idbloque'];
        $con = Yii::$app->db;
       
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
                        and materia_id not in (
                            select s3.materia_id  
                            from ism_grupo_materia_plan_interdiciplinar s, ism_area_materia  s3,
                            ism_grupo_plan_interdiciplinar s1 
                            where s.id_grupo_plan_inter  = s1.id
                            and s1.id_op_course = $idCurso and s1.id_bloque = $idbloque 
                            and s3.id = s.id_ism_area_materia
                        )
                    ) order by nombre ;";  
        
        $listaMaterias = $con->createCommand($query)->queryAll();

        return $this->html_materias_por_curso($listaMaterias);      
        
    }
    public function html_materias_por_curso($listaMaterias)
    {
        $html = "";
            //generamos la tabla para presentar en pantalla, las materias por curso
            $html.='<h6><b>Materias:'.count($listaMaterias).'</b></h6>';
            //$html.='<hr>';
            $html.='<table class="table table-striped ">
                    <thead>
                        <tr>
                            <th class="text-center">Id</th>
                            <th class="text-center">Asginaturas</th> 
                            <th class="text-center">Grupo</th>
                            <th class="text-center">Pasar</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">';
            foreach ($listaMaterias as $materias) 
            {
                $html .= '<tr>';
                $html .= '<td class="text-center">' . $materias['id'] . '</td>';            
                $html .= '<td class="text-center">' . $materias['nombre'] . '</td>';   
                $html .= '<td class="text-center">
                            <input class="form-control" type="text" id="'.$materias['id'].'_materia" style="width:35px;" />
                        </td>'; 
                $html .= '<td class="text-center">
                            <button style="border:0;" class="form-center" type="text" id="'.$materias['id'].'_materia"  onclick=asignar_grupo("'.$materias['id'].'","'.$materias['id'].'_materia")>
                                <i style="color:blue;" class="fas fa-arrow-circle-right"></i>
                            </button>
                        </td>'; 
                $html .= '</tr>';
            }

            $html .= '</tbody>
                        </table>';


            return $html;
    }
    
    public function actionAsignarGrupo()
    {
        //Se activa al momento de gar click en el boton pasar
        // $con = Yii::$app->db;
        // $fechaActual = date('Y-m-d');
        // $hora = date('H:i:s');

        $grupo= $_POST['grupo'];
        $idbloque = $_POST['idbloque'];
        $idcurso = $_POST['idcurso'];
        $idMateria = $_POST['idMateria'];
        $textGrupo = "Grupo ".$grupo;

        // $periodoId = Yii::$app->user->identity->periodo_id;
        // $user = Yii::$app->user->identity->usuario;

        //revisamos si el grupo existe
        $modelGrupoInter = IsmGrupoPlanInterdiciplinar::find()
        ->where(['id_bloque'=>$idbloque,'id_op_course'=>$idcurso,'nombre_grupo'=>$textGrupo])
        ->one();
        if(!$modelGrupoInter)
        {
            //guardamos el grupo
            $modelGrupoInter = $this->genera_grupo_interdiciplinar($textGrupo, $idbloque,$idcurso); 
        }            
        //busqueda de ism_area_materia
        $idAreaMateria = $this->obtener_ism_area_materia($idcurso,$idMateria);       
        //Guardamos las materias asociadas al grupo
        $modelGrupoMateriaInter = $this->genera_grupo_materia_inter($modelGrupoInter,$idAreaMateria);   
        

        $resp = $this->html_grupos_materias($idbloque,$idcurso);
       
        return $resp ;

    }
                    
    public function actionMateriasAgrupadas()
    {
        
        $idbloque = $_POST['idbloque'];
        $idcurso = $_POST['idcurso'];

        $resp = $this->html_grupos_materias($idbloque,$idcurso); 
        return  $resp;
    }
    public function actionEliminarGrupoMateria()
    {
        
        $idbloque = $_POST['idbloque'];
        $idcurso = $_POST['idcurso'];
        $idGrupo = $_POST['idGrupo'];

          //llamamos al grupo
          $modelGrupoInter = IsmGrupoPlanInterdiciplinar::find()
          ->where(['id_bloque'=>$idbloque,'id_op_course'=>$idcurso,'id'=>$idGrupo])
          ->one();
          
          //1.-Eliminamos las materias asociadas al grupo
        if( $modelGrupoInter)
        {
            $modelGrupoMateria = IsmGrupoMateriaPlanInterdiciplinar::find()
            ->where(['id_grupo_plan_inter'=>$modelGrupoInter->id])
            ->all();
            foreach($modelGrupoMateria as $materias){
                $materias->delete();
            }
            $modelGrupoInter->delete();
        }       
     
    }
    public function actionEliminarMateria()
    {
        
        $idMateria = $_POST['idMateria'];
        //llamamos al modelo de la materia
        $modelMateriaGrupo  = IsmGrupoMateriaPlanInterdiciplinar::findOne($idMateria);
        $modelMateriaGrupo->delete();
     
    }
    public function html_grupos_materias($idbloque,$idcurso)
    {
        //extraer Grupos,segun bloque, y curso
        $modelGrupoInter = IsmGrupoPlanInterdiciplinar::find()
        ->where(['id_bloque'=>$idbloque,'id_op_course'=>$idcurso])
        ->all();
        //extraer el curso
        $modelCurso = OpCourse::findOne($idcurso);          
        //<div class="row ">
        $html = "";
        $html.='<h6><b>Grupos Generados</b></h6>
                    
                        <div class="col-sm-10 mx-auto">'; 
                        foreach($modelGrupoInter as $grupo)
                        {   
                            $html.='<h6 style="background-color: while;color:brown"><b>'.$grupo->nombre_grupo.' - '.$modelCurso->name.'</b>';
                         
                            $html.='<button id="button" style="border:0;" onclick=eliminar_grupo('.$grupo->id.');>
                                        <i style="color:blue;" class="fas fa-trash-alt" ></i>
                                    </button>';
                            $html.='
                                    <table class="table table-striped table-bordered">
                                        <tr>
                                            <th>Materia</th>
                                            <th>Acción</th>
                                        </tr>';
                            $html.='</h6>';
                            //extraer las materias asociadas a los grupos
                            $modelGrupoMaterias = IsmGrupoMateriaPlanInterdiciplinar::find()
                            ->where(['id_grupo_plan_inter'=>$grupo->id])
                            ->all();
                            foreach($modelGrupoMaterias as $materia)
                            {
                            $html.='
                                            <tr>
                                                <td>* '.$materia->ismAreaMateria->materia->nombre.'</td>  
                                                <td>
                                                    <button id="button" style="border:0;" onclick=eliminar_materia('.$materia->id.')>
                                                        <i style="color:red;" class="fas fa-trash-alt"></i>
                                                    </button>
                                                </td>                               
                                            </tr>
                                        ';
                            }
                            $html.='</table>';
                        }
                $html.='</div>';
        
        return $html;       

    }

    private function genera_grupo_interdiciplinar($textGrupo,$idbloque,$idcurso)
    {
        $periodoId = Yii::$app->user->identity->periodo_id;
        $user = Yii::$app->user->identity->usuario;
        $fechaActual = date('Y-m-d');
        $hora = date('H:i:s');

        $modelGrupoInter = new IsmGrupoPlanInterdiciplinar();
        $modelGrupoInter->id_bloque = $idbloque;
        $modelGrupoInter->id_op_course = $idcurso;
        $modelGrupoInter->nombre_grupo = $textGrupo;
        $modelGrupoInter->id_periodo =  $periodoId;
        $modelGrupoInter->created_at =  $fechaActual.' '.$hora;
        $modelGrupoInter->created =  $user;
        $modelGrupoInter->save();

        return $modelGrupoInter;
    }
    private function genera_grupo_materia_inter($modelGrupoInter,$idAreaMateria)
    {
        $user = Yii::$app->user->identity->usuario;
        $fechaActual = date('Y-m-d');
        $hora = date('H:i:s');

        $modelGrupoMaterias = new IsmGrupoMateriaPlanInterdiciplinar();
        $modelGrupoMaterias->id_grupo_plan_inter = $modelGrupoInter->id;
        $modelGrupoMaterias->id_ism_area_materia = $idAreaMateria['id'];
        $modelGrupoMaterias->created_at =  $fechaActual.' '. $hora;
        $modelGrupoMaterias->created = $user;
        $modelGrupoMaterias->save();

        return $modelGrupoMaterias;
    }

    private function obtener_ism_area_materia($idcurso,$idMateria)
    {
        // $periodoId = Yii::$app->user->identity->periodo_id;
        // $user = Yii::$app->user->identity->usuario;
        // $fechaActual = date('Y-m-d');
        // $hora = date('H:i:s');
        $con = Yii::$app->db;
        $query = "select id,malla_area_id,materia_id 
                from ism_area_materia iam where id in 
                (
                    select  ism_area_materia_id from scholaris_clase 
                    where paralelo_id in (
                        select id
                        from op_course_paralelo where course_id ='$idcurso'
                    )
                ) and materia_id = '$idMateria' ;";
        
        $resp = $con->createCommand($query)->queryOne();   
       
        return $resp;

    }
    
}
