<?php

namespace backend\controllers;

use backend\models\IsmContenidoPlanInterdiciplinar;
use Yii;
use backend\models\IsmRespuestaPlanInterdiciplinar;
use backend\models\IsmRespuestaPlanInterdiciplinarSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\PlanificacionBloquesUnidad;
use backend\models\pudpai\Datos;
use phpDocumentor\Reflection\Types\This;

/**
 * IsmRespuestaPlanInterdiciplinarController implements the CRUD actions for IsmRespuestaPlanInterdiciplinar model.
 */
class IsmRespuestaPlanInterdiciplinarController extends Controller
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
     * Lists all IsmRespuestaPlanInterdiciplinar models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new IsmRespuestaPlanInterdiciplinarSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Lists all IsmRespuestaPlanInterdiciplinar models.
     * @return mixed
     */
    private function crea_respuestas_en_blanco($idGrupoInter)
    {
        //busca si existe respuestas ya registradas para este grupo, caso contrario crea el catalogo de respuestas
        $resp = false;
        $modelRespuesta = IsmRespuestaPlanInterdiciplinar::find()
        ->where(['id_grupo_plan_inter'=>$idGrupoInter])
        ->one();

        if(!$modelRespuesta)
        {
            //se crean todas las respuesta acorde el grupo creado
            $modelIsmContenido = IsmContenidoPlanInterdiciplinar::find()
            ->where(['activo'=>true])
            ->all();

            foreach($modelIsmContenido as $model)
            {
                $objModelRespuesta = new IsmRespuestaPlanInterdiciplinar();
                $objModelRespuesta->id_grupo_plan_inter = $idGrupoInter;
                $objModelRespuesta->id_contenido_plan_inter = $model->id;
                $objModelRespuesta->respuesta = "";
                $objModelRespuesta->save();
            }
        }

        return $resp;
    }
    
    public function actionIndex1()
    {
        $planBloqueUnidadId = $_GET['plan_bloque_unidad_id'];
        $idGrupoInter = $_GET['idgrupointer'];

        $planUnidad = PlanificacionBloquesUnidad::findOne($planBloqueUnidadId);   
        //crea el catalogo de respuestas si estas no existen
        $this->crea_respuestas_en_blanco($idGrupoInter);
        return $this->render('index1', [
            'planUnidad' => $planUnidad,
            'idGrupoInter'=>$idGrupoInter,
        ]);
    }

    /**
     * Displays a single IsmRespuestaPlanInterdiciplinar model.
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
     * Creates a new IsmRespuestaPlanInterdiciplinar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new IsmRespuestaPlanInterdiciplinar();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing IsmRespuestaPlanInterdiciplinar model.
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
     * Deletes an existing IsmRespuestaPlanInterdiciplinar model.
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
     * Finds the IsmRespuestaPlanInterdiciplinar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return IsmRespuestaPlanInterdiciplinar the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = IsmRespuestaPlanInterdiciplinar::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    /*************************************************************************************************************** */
    /*************************************************************************************************************** */
    /*************************************************************************************************************** */
    public function actionMostrarPantallas()
    {
        $html = '';
        $planUnidadId = $_POST['plan_unidad_id'];
        $pestana = $_POST['pestana'];
        $idGrupoInter = $_POST['idgrupointer'];
       
        switch ($pestana) {
            case '1.1.-':
                $html = $this->datos_informativos($planUnidadId);
                break;
            case '2.1.-':             
                $html = $this->proposito_indagacion($idGrupoInter);
                break;
            case '2.2.-':  
                $html = $this->enunciado_indagacion($idGrupoInter);
                break;
        }        
        return $html;
    }
    public function actionUpdateRespuesta()
    {
        $html = '';
        $idRespuesta = $_POST['idRespuesta'];
        $nuevoDato =$_POST['nuevoDato'];   
        $planUnidadId = $_POST['planUnidadId'];  
        
        //buscamos el registro de respuesta
        $modelRespuesta = IsmRespuestaPlanInterdiciplinar::findOne($idRespuesta);
        $modelRespuesta->respuesta = $nuevoDato;
        $modelRespuesta->save();
    }

    //1.-
    public function datos_informativos($planUnidadId)
    {
        $titulo = '1.- DATOS INFORMATIVOS';
        $esEditable = false;
        $objDatos = new Datos($planUnidadId);
        $planUnidad   = PlanificacionBloquesUnidad::findOne($planUnidadId);

        $tiempo = $objDatos->calcula_horas(
            $planUnidad->planCabecera->ismAreaMateria->materia_id,
            $planUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->op_course_template_id
        );

        $html = '';
        // inicia row
        $html .= '<div class="row">';
        $html .= '<div class="col"><b>GRUPO DE ASIGNATURAS</b></div>';
        $html .= '<div class="col">' . $planUnidad->planCabecera->ismAreaMateria->materia->nombre . '</div>';
        $html .= '<div class="col"><b>PROFESOR(ES)</b></div>';
        //$docentes = $objDatos->get_docentes();
        //$html .= '<div class="col">';
        // foreach ($docentes as $docente) {
        //     $html .= $docente['docente'] . ' | ';
        // }
        // $html .= '</div>';
        $html .= '</div>';
        // //******finaliza row
        $html .= '<hr>';
        //inicia row
        $html .= '<div class="row">';
        $html .= '<div class="col"><b>UNIDAD Nº</b></div>';
        $html .= '<div class="col">' . $planUnidad->curriculoBloque->last_name . '</div>';
        $html .= '<div class="col"><b>TÍTULO DE LA UNIDAD</b></div>';
        $html .= '<div class="col">' . $planUnidad->unit_title . '</div>';
        $html .= '</div>';
        // //******finaliza row
        $html .= '<hr>';
        //inicia row
        $html .= '<div class="row">';
        $html .= '<div class="col"><b>AÑO PAI:</b></div>';
        $html .= '<div class="col">' . $planUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->name . '</div>';
        $html .= '<div class="col"><b>DURACIÓN EN HORAS:</b></div>';
        $html .= '<div class="col">' . $tiempo['horas'] . '</div>';
        $html .= '</div>';
        // //******finaliza row
        $html .= '<hr>';
        // //inicia row
        $html .= '<div class="row">';
        $html .= '<div class="col"><b>FECHA INICIO:</b></div>';
        $html .= '<div class="col">' . $tiempo['fecha_inicio'] . '</div>';
        $html .= '<div class="col"><b>FECHA FINALIZACIÓN:</b></div>';
        $html .= '<div class="col">' . $tiempo['fecha_final'] . '</div>';
        $html .= '</div>';

        $html = $this->generico_marco_general(0,'','1.1.-',$titulo, $esEditable,$html);

        return $html;
    }
   
    //2.-
    private function proposito_indagacion($idGrupoInter)
    {
        $titulo = '2.1.- Propósito de Indagación';
        $idIsmGrupoInter = $idGrupoInter;
        $esEditable = true;
        $pestana = '2.1.-';
        $campo ='PROPÓSITO DE LA INTEGRACIÓN';
        $seccion =2;     
         
        
        $html = $this->generico_consulta_base_campo_texto($seccion,$campo,$esEditable,$titulo,$pestana,$idIsmGrupoInter);

        return $html;
    }
    private function enunciado_indagacion($idGrupoInter)
    {
        
        $titulo = '2.2.- Conceptos Relacionados';
        $idIsmGrupoInter = $idGrupoInter;
        $esEditable = true;
        $pestana = '2.2.-';
        $campo ='ENUNCIADO DE LA INDAGACIÓN';
        $seccion =2;              
        
        $html = $this->generico_consulta_base_campo_texto($seccion,$campo,$esEditable,$titulo,$pestana,$idIsmGrupoInter);

        return $html;
                
    }
/********************************************************************************************************* */
/********************************************************************************************************* */
/********************************************************************************************************* */
     //metodos genericos para todos los campos de texto
     private function generico_consulta_base_campo_texto($seccion,$campo,$esEditable,$titulo,$pestana,$idIsmGrupoInter)
     {
        
        //extraemos el Id de la pregunta
         $modelPreguntaContenido = IsmContenidoPlanInterdiciplinar::find()
         ->where(['id_seccion_interdiciplinar'=>$seccion])
         ->andWhere(['nombre_campo'=>$campo])
         ->andWhere(['activo'=>true])
         ->andWhere(['heredado'=>false])
         ->one();         

        
         //Extraemos la respuesta
         $modelRespuesta = IsmRespuestaPlanInterdiciplinar::find()
         ->where(['id_grupo_plan_inter'=>$idIsmGrupoInter])
         ->andwhere(['id_contenido_plan_inter'=>$modelPreguntaContenido->id])        
         ->one();

         $html="";

         if($modelRespuesta)
         {
            $respuesta = $modelRespuesta->respuesta;
            $idRespuesta = $modelRespuesta->id;         
         
            $html = $this->generico_marco_general($idRespuesta,$respuesta,$pestana,$titulo, $esEditable,$respuesta);  
         }
         else{
            $html="<h1> no hay información</h1>";
         }
 
         
         return $html;        
     }
    private function generico_marco_general($idRespuesta,$respuesta,$pestana,$titulo,$esEditable,$htmlEntrada)
    {
        $html = '';        
        $html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">';
            $html .= '<div class="card" style="width: 70%; margin-top:20px">';
                $html .= '<div class="card-header">';
                    $html .= '<h5 class="text-center"><b>' . $titulo . '</b></h5>';
                $html .= '</div>';
                $html .= '<div class="card-body">';
                if($esEditable)
                { 
                    $html .= $this->generico_editar_texto($idRespuesta,$respuesta,$pestana,$titulo);
                    $html .= '<br>';
                }
                $html .= $htmlEntrada;
                $html .= '</div>';
            $html .= '</div>';
        $html .= '</div>';
       
        return $html;
    }
    private function generico_editar_texto($id, $respuesta,$pestana,$titulo)
    {
        $html = '<br><a href="#"  data-bs-toggle="modal" data-bs-target="#modalS2' . $id . '"> 
                    <i class="fas fa-edit"></i>Editar';
        $html .= '</a>';

        $html .= '<div class="modal fade" id="modalS2' . $id . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">' . $titulo . '</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>';
        $html .= '<div class="modal-body"> <hr>               
                                        <textarea id="editor-sumativa2' . $id . '" name="sumativas" " class="form-control">' . $respuesta .'</textarea>
                                            <script>
                                            CKEDITOR.replace("editor-sumativa2' . $id . '", {
                                            customConfig: "/ckeditor_settings/config.js"
                                            })
                                            </script>';
        $html .= '</div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="update_campo('. $id .',\''.$pestana.'\')">Actualizar</button>
                                    </div>
                                </div>
                        </div>
                </div>';
        $html.='<br>';
        return $html;
    }
}
