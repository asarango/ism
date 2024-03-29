<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use kartik\mpdf\Pdf;
use backend\models\ScholarisClase;
use backend\models\ScholarisBloqueActividad;
use backend\models\ScholarisGrupoAlumnoClase;
use backend\models\ScholarisCalificaciones;
use backend\models\ScholarisParametrosOpciones;
use backend\models\ScholarisPeriodo;
use frontend\models\SentenciasSql;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class ReporteNotasProfesorNacController extends Controller {

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
     * Lists all Menu models.
     * @return mixed
     */
    public function actionParcial($bloqueId, $claseId) {
        $html = $this->html($claseId, $bloqueId);
        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('_reportView', [
            'html' => $html,
        ]);

        $cabecera = $this->cabecera($bloqueId, $claseId);

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            'marginHeader' => '0mm',
            // portrait orientation
            //'orientation' => Pdf::ORIENT_PORTRAIT,
            'orientation' => 'L',
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            // set mPDF properties on the fly
            'options' => ['title' => 'Krajee Report Title'],
            // call mPDF methods on the fly
            'methods' => [
                'SetHeader' => [$cabecera],
                'SetFooter' => ['{PAGENO}'],
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    protected function cabecera($bloqueId, $claseId) {
        
        $modelClase = ScholarisClase::find()->where(['id' => $claseId])->one();
        $modelBloque = ScholarisBloqueActividad::find()->where(['id' => $bloqueId])->one();

        $html = '';
        $html .= '<table width="100%">';
        $html .= '<tr>';
        $html .= '<td>';
        $html .= '<table  cellpadding="0" cellspacing="0">';
        $html .= '<tr><td width="33%"><font size="2">' . $modelClase->course->xInstitute->name . '</font></td></tr>';
        $html .= '<tr><td width="33%"><font size="1">' . $modelClase->course->name . ' - ' . $modelClase->paralelo->name . '</font></td></tr>';
        $html .= '<tr><td width="33%"><font size="1">' . $modelClase->materia->name . ' - ' . $modelClase->profesor->last_name . ' ' . $modelClase->profesor->x_first_name . '</font></td></tr>';
        $html .= '<tr><td width="33%"><font size="1">Periodo: ' . $modelBloque->scholaris_periodo_codigo . '</font></td></tr>';
        $html .= '</table>';

        $html .= '</td>';
//        $html .= '<td width="33%" align="center">{PAGENO}/{nbpg}</td>
        $html .= '<td width="33%" align="center">REGISTRO DE CALIFICACIONES PARCIALES ' . $modelBloque->name . '</td>';
        $html .= '<td width="33%" style="text-align: right;"><font size="1">Emitido: ' . date("Y-m-d") . '</font></td>';
        $html .= '</tr>';
        $html .= '</table>';

        $html .= "</font>";
        return $html;
    }

    protected function html($claseId, $bloqueId) {

        $sentencias = new SentenciasSql();
        $sentencias2 = new \backend\models\Notas();
        $sentenciasNP = new \backend\models\ReporteNotasProfesor();
        
        $sentenciasRC = new \backend\models\SentenciasRecalcularUltima();
        
        $sentenciasRC->genera_recalculo_por_clase($claseId);
        
        $modelParametros = ScholarisParametrosOpciones::find()->where(['codigo' => 'notaminima'])->one();
        $minimo = $modelParametros->valor;


        $html = '<table style="font-size: 7px" cellspacing="0" class="table-responsive table-bordered table-condensed table-striped">';
        $html .= '<tr bgcolor="#EDEDEE">';
        $html .= '<th rowspan="2">#</th>';
        $html .= '<th colspan="2">CRITERIOS Y ACTIVIDADES</th>';

        $titulos = $this->tituloCriterios($bloqueId, $claseId);

        foreach ($titulos as $tit) {

            $insumo = $this->insumos($tit['grupo_numero']);
            $html .= '<th colspan="' . $tit['total'] . '"><center>' . $insumo . '</center></th>';
        }

        $html .= '<th rowspan="2" bgcolor="#ECE6F8">FINAL</th>';

        $html .= "</tr>";
        $html .= "<tr>";
        $html .= '<th colspan="2">Descripción y aspectos eavaluados</th>';

        $tituloActividades = $this->titulosActividades($claseId, $bloqueId);

//        echo '<pre>';
//        print_r($tituloActividades);
//        die();

        foreach ($tituloActividades as $tit) {
            if ($tit['title'] == 'PROMEDIO') {
                $html .= '<th bgcolor="#A6EBF7">' . $tit['title'] . '</th>';
            } else {
                $html .= '<th>' . $tit['title'] . '</th>';
            }
        }

        $html .= "</tr>";

        /* inicio detalle */
        $alumnos = $this->alumnos($claseId);
        $i=0;
        foreach ($alumnos as $alumno) {
            $i++;
            $html .= '<tr>';
            $html .= '<td>' . $i . '</td>';
            $html .= '<td>' . $alumno->student->last_name . ' ' . $alumno->student->first_name . '</td>';
            $html .= '<td>' . $alumno->inscription_state . '</td>';

            foreach ($tituloActividades as $tit) {
                $notasA = $this->notas($tit['grupo'], $tit['id'], $alumno->student_id, $claseId, $bloqueId, $tit['criterio']);
                
                if ($tit['title'] == 'PROMEDIO') {
                    $html .= '<td align="center" bgcolor="#A6EBF7">' . $notasA . '</td>';
                } else {
                    $html .= '<td align="center">' . $notasA . '</td>';
                }
            }

            /* INICIO PROMEDIO SUMATIVAS */
//            $promSum = $sentencias->promedioSumativas($alumno->student->id, $bloqueId, $claseId);
//            $html .= '<td align="center" bgcolor="#F4F5E3">' . $promSum['nota'] . '</td>';
            /* FIN PROMEDIO SUMATIVAS */

            /* INICIO PROMEDIO FINAL */
//            $promFinal = $sentencias->promedioParcial($alumno->student->id, $bloqueId, $claseId);
//            if ($alumno->inscription_state == 'M') {
//                if ($promFinal['calificacion'] < $minimo) {
//                    $html .= '<td align="center" bgcolor="#ECE6F8"><font color="#FF0000">' . $promFinal['calificacion'] . '</font></td>';
//                } else {
//                    $html .= '<td align="center" bgcolor="#ECE6F8">' . $promFinal['calificacion'] . '</td>';
//                }
//            } else {
//                $html .= '<td align="center" bgcolor="#ECE6F8">-</td>';
//            }
            
            $modelGrupo = ScholarisGrupoAlumnoClase::find()->where(['estudiante_id' => $alumno->student->id, 'clase_id' => $claseId])->one();
            $promFinal = $sentencias2->get_nota_parcial($bloqueId, $modelGrupo->id);
            

            if ($alumno->inscription_state == 'M') {
                if ($promFinal < $minimo) {
                    $html .= '<td align="center" bgcolor="#ECE6F8"><font color="#FF0000">' . $promFinal . '</font></td>';
                } else {
                    $html .= '<td align="center" bgcolor="#ECE6F8">' . $promFinal . '</td>';
                }
            } else {
                $html .= '<td align="center" bgcolor="#ECE6F8">-</td>';
            }


            /* FIN PROMEDIO FINAL */

            $html .= '</tr>';
        }
        /* fin detalle */
        $html .= "</table>";


        $html .= $this->cuadroEstadistico($claseId, $bloqueId, $alumnos);
        
        $html .= $sentenciasNP->cuadro_refuerzos($claseId, $bloqueId);


        return $html;
    }

    /* TITULOS DE INSUMOS */

    protected function tituloCriterios($bloqueId, $claseId) {
        $sentencia = new SentenciasSql();
        $titulos = $sentencia->titutloCriterios($bloqueId, $claseId);
        return $titulos;
    }

    protected function tituloSumativas($bloqueId, $claseId) {
        $sentencia = new SentenciasSql();
        $titulos = $sentencia->tituloSumativas($bloqueId, $claseId);
        return $titulos;
    }

    protected function actividadesNoSumativas($bloqueId, $claseId) {
        $sentencia = new SentenciasSql();
        $actividades = $sentencia->actividadesNoSumativas($bloqueId, $claseId);
        return $actividades;
    }

    protected function insumos($grupo) {
        
//        $modelInsumo = \backend\models\ScholarisTipoActividad::find()
//                ->where(['orden' => $grupo])
//                ->one();
        
        $modelInsumo = \backend\models\ScholarisGrupoOrdenCalificacion::find()
                ->where(['grupo_numero' => $grupo])
                ->andWhere(['not in','codigo_nombre_pai',['A','B','C','D']])
                ->one();                
        
        $insumo = $modelInsumo->codigo_nombre_pai;
        
//        
//        if ($grupo == 1) {
//            $insumo = 'TAREAS';
//        } elseif ($grupo == 2) {
//            $insumo = 'ACT. INDIVIDUAL';
//        } elseif ($grupo == 3) {
//            $insumo = 'ACT. GRUPAL';
//        } elseif ($grupo == 4) {
//            $insumo = 'LECCION';
//        } elseif ($grupo == 5) {
//            $insumo = 'EVALUACIÓN';
//        }

        return $insumo;
    }

    protected function insumosSuma($claseId, $bloqueId, $grupo) {

        $sentencias = new SentenciasSql();
        $titulos = $sentencias->tituloSumativas1($bloqueId, $claseId, $grupo);

        return $titulos;
    }

    /* FIN TITULOS DE INSUMOS */

    /* INICIO TITULOS DE ACTIVIDADES */

    private function titulosActividades($claseId, $bloqueId) {
        $arreglo = array();

        $sentencia = new SentenciasSql();
        $insumos = $sentencia->grupoInsumos($bloqueId, $claseId);
        foreach ($insumos as $insumo) {
            $actividades = $sentencia->actividadesPorGrupo($bloqueId, $claseId, $insumo['grupo']);
            foreach ($actividades as $act) {
                array_push($arreglo, [
                    'id' => $act['id'],
                    'title' => $act['title'],
                    'grupo' => $insumo['grupo'],
                    'tipo' => 'actividad',
                    'criterio' => NULL,
                ]);
            }

            array_push($arreglo, [
                'id' => 'x',
                'title' => 'PROMEDIO',
                'grupo' => $insumo['grupo'],
                'tipo' => 'grupo',
                'criterio' => NULL,
                    ]
            );
        }

        $insumosSuma = $sentencia->grupoInsumosSumativas($bloqueId, $claseId);
        foreach ($insumosSuma as $insumo) {
            $actividades = $sentencia->actividadesPorGrupoSumativas($bloqueId, $claseId, $insumo['grupo']);
            foreach ($actividades as $act) {
                array_push($arreglo, [
                    'id' => $act['id'],
                    'title' => 'SUMATIVA',
                    'grupo' => $insumo['grupo'],
                    'tipo' => 'actividad',
                    'criterio' => $act['criterio_id'],
                ]);
            }
        }


        return $arreglo;
    }

    private function alumnos($claseId) {
        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $alumnos = \backend\models\OpStudentInscription::find()
                ->innerJoin("op_student", "op_student.id = op_student_inscription.student_id")
                ->innerJoin("scholaris_grupo_alumno_clase", "scholaris_grupo_alumno_clase.estudiante_id = op_student.id")
                ->innerJoin("scholaris_clase", "scholaris_clase.id = scholaris_grupo_alumno_clase.clase_id")
                ->innerJoin("scholaris_op_period_periodo_scholaris", "scholaris_op_period_periodo_scholaris.op_id = op_student_inscription.period_id")
                ->innerJoin("scholaris_periodo", "scholaris_periodo.id = scholaris_op_period_periodo_scholaris.scholaris_id")
                ->where([
                    "scholaris_grupo_alumno_clase.clase_id" => $claseId,
                    "scholaris_periodo.codigo" => $modelPeriodo->codigo
                ])
                ->orderBy('op_student.last_name','op_student.first_name','op_student.middle_name')
                ->all();

        return $alumnos;
    }

    private function notas($grupo, $actividad, $alumno, $claseId, $bloqueId, $criterio) {

        $sentencias = new SentenciasSql();

        if ($actividad != 'x') {

            if ($criterio) {
                $notas = ScholarisCalificaciones::find()
                        ->where([
                            'idactividad' => $actividad,
                            'idalumno' => $alumno,
                            'grupo_numero' => $grupo,
                            'criterio_id' => $criterio,
                        ])
                        ->one();
            } else {
                $notas = ScholarisCalificaciones::find()
                        ->where([
                            'idactividad' => $actividad,
                            'idalumno' => $alumno,
                            'grupo_numero' => $grupo,
                        ])
                        ->one();
            }


            if (isset($notas->calificacion)) {
                return $notas->calificacion;
            } else {
                return 0;
            }
        } else {
                       
            $promedios = $sentencias->promedioGrupo($grupo, $alumno, $bloqueId, $claseId);
            
            return $promedios['nota'];
        }
    }

    /* FIN TITULOS DE ACTIVIDADES */

    /* INICIO CUADRO ESTADISTICO */

    private function cuadroEstadistico($claseId, $bloqueId, $alumnos) {
        $sentencias = new SentenciasSql();
        $i=0;
        foreach ($alumnos as $alumno){
            if($alumno->inscription_state == 'M'){
                $i++;
            }
        }

        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        //echo $modelPeriodo->codigo;

        $cuadro = $sentencias->cuadroEstadistico($modelPeriodo->codigo, $claseId, $bloqueId);

        $html = '<br>';
        
        $html .= '<table width="100%">';
        $html .= '<tr>';
        $html .= '<td>';
        
        $html .= '<p style="font-size: 7px"><strong>DATOS GENERALES DEL PARCIAL - (Alumnos matriculados)</strong></p>';
        $html .= '<table style="font-size: 7px" cellspacing="0" class="table-responsive table-bordered table-condensed table-striped">';
        $html .= '<tr bgcolor="#EDEDEE">';
        $html .= "<th>DESCRIPCIÓN</th>";
        $html .= "<th>ABREVIATURA</th>";
        $html .= "<th>RANGOS</th>";
        $html .= "<th>CASOS</th>";
        $html .= "<th>PORCENTAJE</th>";
        $html .= "</tr>";

        foreach ($cuadro as $dato) {
            $html .= '<tr>';
            $html .= '<td>' . $dato['descripcion'] . '</td>';
            $html .= '<td>' . $dato['abreviatura'] . '</td>';
            $html .= '<td>' . $dato['rango_minimo'] . ' - ' . $dato['rango_maximo'] . '</td>';
            $html .= '<td>' . $dato['total'] . '</td>';
            $porcentaje = ($dato['total'] * 100) / $i;
            $porcentaje = number_format($porcentaje, 3);
            $html .= '<td>' . $porcentaje . '</td>';
            $html .= '</tr>';
        }

        $html .= "</table>";

        $promedio = $sentencias->promedioParcialClase($claseId, $bloqueId, $modelPeriodo->codigo);

        $html .= '<br>';
        $html .= '<p style="font-size: 10px"><strong>PROMEDIO PARCIAL DE LA CLASE: </strong> ' . $promedio['promedio'] . '  </p>';        
        $html .= '</td>';
        
        $html .= '<td></td>';
        $html .= '<td>'.$this->grafico($cuadro).'</td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        

        

        return $html;
    }

    private function grafico($cuadro) {
              
        
        $x = array();
        $y = array();

        foreach ($cuadro as $dato) {

            array_push($y, $dato['abreviatura']);
            array_push($x, $dato['total']);
        }


        $datos = urldecode(serialize($x));
        $labels = urlencode(serialize($y));
                        
        $html = '';
        //$graphLink = 'graphPage.php?id=1'; // create a new file, you can pass parameter to it also.
        $graphLink = "http://localhost/graficos/ejemplo1.php?datos=$datos&labels=$labels"; // create a new file, you can pass parameter to it also
//        echo $graphLink;
//        die();
        $html .= '<div><img src="' . $graphLink . '" ></div>';

        return $html;
    }

    /* FIN CUADRO ESTADISTICO */
}
