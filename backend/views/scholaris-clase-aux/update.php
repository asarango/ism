<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

$this->title = 'Actualizar clase';
?>

<!-- JS y CSS Ckeditor -->
<script src="https://cdn.ckeditor.com/4.17.1/full/ckeditor.js"></script>


<div class="scholaris-actividad-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/retroalimentacion.png" width="64px" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4>
                        <?= Html::encode($this->title) ?><br>
                        <small>
                            |<?= $model->id ?>
                            |<?= $model->ismAreaMateria->materia->nombre ?>
                            |<?= $model->paralelo->course->name ?>
                            |<?= $model->paralelo->name ?>
                        </small>
                    </h4>

                </div>
            </div>
            <hr>

            <div class="row">
                <div class="col-lg-6 col-md-6"> 
                    |
                    <?php
                    echo Html::a(
                            '<span class="badge rounded-pill" style="background-color: #898b8d"><i class="fa fa-plus-circle" aria-hidden="true"></i> Inicio</span>',
                            ['site/index']
                    );
                    ?>                    
                    |
                    <?php
                    echo Html::a(
                            '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fas fa-users-class"></i> Clases</span>',
                            ['scholaris-clase/index']
                    );
                    ?>                    
                    |
                </div>
                <!-- fin de primeros botones -->                
                
                <!--botones derecha-->
                <div class="col-lg-6 col-md-6" style="text-align: right;">                 
                    
                </div> <!-- FIN DE BOTONES DE ACCION Y NAVEGACIÓN -->
            </div>


            <!-- /****************************************************************************************************/  -->
            <!-- comienza cuerpo  -->
            
            <div class="row" style="margin-top: 20px; margin-bottom: 20px">
                
                <div class="col-lg-5 col-md-5">
                                        
                    <div class="row" style="padding: 10px;"> <!-- INICIA CON FORMULARIO DE UPDATE ?> -->
                    
                        <div class="card bg-quinto" style="padding: 20px; color: white">
                            <?php
                                if (isset($model->paralelo->name)) {
                                    horarios($modelDias, $modelHoras, $model->paralelo_id, $model->id, $model->asignado_horario);
                                } 
                            ?>
                        </div>
                    </div>
                    
                    
                    <div class="row" style="padding: 10px;"> <!-- INICIA CON FORMULARIO DE UPDATE ?> -->
                        <?php
                            echo $this->render('_form',[
                                'model' => $model,
                                'modelDocentes' => $modelDocentes,
                                'modelHorarioA' => $modelHorarioA,
                                'modelTipoBloque' => $modelTipoBloque,
                                'modelAutoridades' => $modelAutoridades
                            ]);
                        ?>
                        <!-- FINALIZA CON FORMULARIO DE UPDATE ?> -->
                    </div>                                                                                    
                </div>
                
                
                <div class="col-lg-7 col-md-7">
                    <?php
                    if ($model->todos_alumnos == 1) {
                        echo Html::a('Ingresar Alumnos', ['scholaris-clase/todos', 'id' => $model->id], ['class' => 'btn btn-success']);
                    } else {
                        echo Html::a('Ingresar Alumnos', ['scholaris-clase/unitario', 'id' => $model->id], ['class' => 'btn btn-warning']);
                    }
                    ?>
                    <hr>
                    <div class="table table-responsive">
                        <table class="table table-condensed table-striped table-hover tamano10">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Estudiante</th>
                                    <th>Curso</th>
                                    <th>Paralelo</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                foreach ($modelGrupo as $grupo) {
                                    $i++;
                                    echo '<tr>';
                                    echo '<td>' . $i . '</td>';
                                    echo '<td>' . $grupo['last_name'] . ' ' . $grupo['first_name'] . ' ' . $grupo['middle_name'] . '</td>';
                                    echo '<td>' . $grupo['curso'] . '</td>';
                                    echo '<td>' . $grupo['paralelo'] . '</td>';
                                    echo '<td>' . $grupo['inscription_state'] . '</td>';
                                    echo '<td>';
                                    echo Html::a('<p class="tamano10">Retirar</p>', ['scholaris-clase/retirar', 'grupoId' => $grupo['grupo_id']], ['class' => 'btn btn-link']);
                                    echo '</td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- finaliza cuerpo -->
        </div>
    </div>
</div>



<?php

function horarios($modelDias, $modelHoras, $paralelo, $clase, $cabecera) {
    echo '<div class="table table-responsive">';
    echo '<table class="table table-condensed table-striped table-hover table-bordered tamano10" style="color: white">';
    echo '<thead>';
    echo '<tr>';
    echo '<td></td>';
    foreach ($modelDias as $dia) {
        echo '<td>' . $dia->nombre . '</td>';
    }
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    foreach ($modelHoras as $hora) {
        echo '<tr>';
        echo '<td style="color: white">' . $hora['sigla'] . '</td>';
        foreach ($modelDias as $d) {
            $materia = recuperaMateria($paralelo, $d->id, $hora['id']);


            if (!isset($materia['clase_id'])) {
                echo '<td bgcolor="">' . Html::a('<p class="text-danger">Asignar aquí</p>',
                        ['asignar', 'dia' => $d->id, 'hora' => $hora['id'], 'cabecera' => $cabecera, 'clase' => $clase],
                        ['class' => '']) . '</td>';
            } else if ($materia['clase_id'] == $clase) {
                echo '<td style="color: white">' . Html::a($materia['materia'], 
                                                    ['quitar', 'detalle' => $materia['detalle_id'], 
                                                    'clase' => $clase]) . '</td>';
            } else {
                echo '<td style="color: white">' . $materia['materia'] . '</td>';
            }
        }
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
}

function recuperaMateria($paralelo, $dia, $hora) {
    $sentencias = new \backend\models\SentenciasClase();
    $model = $sentencias->get_materia_horario($paralelo, $dia, $hora);
    return $model;
}
?>