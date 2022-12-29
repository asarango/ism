<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\IsmGrupoPlanInterdiciplinarSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Grupos Plan Interdiciplinar';
$this->params['breadcrumbs'][] = $this->title;


?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />

<div class="ism-grupo-plan-interdiciplinar-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-2">
                    <h4><img src="../ISM/main/images/submenu/reunion.png" width="100px" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-2">
                        |
                        <?=
                        Html::a(
                            '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                            ['site/index'],
                            ['class' => 'link']
                        );
                        ?>
                        |
                </div>
                <div class="col-lg-8">
                    <h2><?= Html::encode($this->title) ?></h2>
                </div>
            </div><!-- FIN DE CABECERA -->
           
            <!-- inicia cuerpo de card -->
            <div class="container" >
                <div class="row " >
                    <div class="col ">
                        <div class="row" style="margin-top: 25px;">
                            <select name="bloques" id="bloque" class="form-control" style="width: 99%;" tabindex="-1" aria-hidden="true">
                                <option selected="selected" value="">Escoja un bloque...</option>
                                <?php
                                foreach ($listaBloques as $bloque) {
                                    echo '<option value="' . $bloque['id'] . '">' . $bloque['name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="row" style="margin-top: 25px;">
                            <select name="niveles" onchange="showAsignaturas()" id="nivel" class="form-control select2 select2-hidden-accessible" style="width: 99%;" tabindex="-1" aria-hidden="true">
                                <option selected="selected" value="">Escoja un curso...</option>
                                <?php
                                foreach ($cursos as $nivel) {
                                    echo '<option value="' . $nivel['id'] . '">' . $nivel['name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div> <!-- /.form-group -->
                    </div>
                </div>
            </div>
            <hr>


            <div class="row border">
                <div class="col border">
                    <div id="div_tabla_materias" class="table table-responsive">
                        <h6><b>Materias</b></h6>
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Id</th>
                                    <th class="text-center">Asginaturas</th>
                                    <th class="text-center">Grupo</th>
                                    <th class="text-center">Accion</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col border">
                    <div id="div_tabla_grupos" class="table table-responsive">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- SCRIPT PARA SELECT2 -->
    <script>
        buscador();

        function buscador() {
            $('.select2').select2({
                closeOnSelect: true
            });
        }

        function showAsignaturas() {
            var nivel = $('#nivel').val();
            var idbloque = $("#bloque").val();
            var url = '<?= Url::to(['obtener-materia']) ?>';
            var params = {
                curso_id: nivel,
                idbloque: idbloque,
            };

            $.ajax({
                data: params,
                url: url,
                type: 'POST',
                beforeSend: function() {},
                success: function(response) {   
                    $("#div_tabla_materias").html(response);    
                    mostar_materias_agrupadas();                                    

                }
            });
        }
        function showAsignaturas2() {
            var nivel = $('#nivel').val();
            var idbloque = $("#bloque").val();
            var url = '<?= Url::to(['obtener-materia']) ?>';
            var params = {
                curso_id: nivel,
                idbloque: idbloque,
            };

            $.ajax({
                data: params,
                url: url,
                type: 'POST',
                beforeSend: function() {},
                success: function(response) {   
                    $("#div_tabla_materias").html(response);   
                                                       
   
                }
            });
        }

        function asignar_grupo(idMateria, idNombre) {
            var idcurso = $('#nivel').val();
            var idbloque = $("#bloque").val();
            var grupo = $("#" + idNombre).val();

            var url = '<?= Url::to(['asignar-grupo']) ?>';
            var params = {
                grupo: grupo,
                idbloque: idbloque,
                idcurso: idcurso,
                idMateria: idMateria,
            };

            $.ajax({
                data: params,
                url: url,
                type: 'POST',
                beforeSend: function() {},
                success: function(response) {
                    showAsignaturas2(); 
                    $("#div_tabla_grupos").html(response);      
                                  
                }
            });
        }
        function mostar_materias_agrupadas() 
        {
            var idcurso = $('#nivel').val();
            var idbloque = $("#bloque").val();

            var url = '<?= Url::to(['materias-agrupadas']) ?>';
           
            var params = {               
                idbloque: idbloque,
                idcurso: idcurso,
            };

            $.ajax({
                data: params,
                url: url,
                type: 'POST',
                beforeSend: function() {},
                success: function(response) {
                    $("#div_tabla_grupos").html(response);                                      
                }
            });
        }
        function eliminar_materia(idMateria) 
        {
            var url = '<?= Url::to(['eliminar-materia']) ?>';
           
            var params = {               
                idMateria: idMateria,
            };

            $.ajax({
                data: params,
                url: url,
                type: 'POST',
                beforeSend: function() {},
                success: function(response) {
                    showAsignaturas2();
                    mostar_materias_agrupadas();

                }
            });

        }
        function eliminar_grupo(idGrupo) 
        {   
            var idcurso = $('#nivel').val();
            var idbloque = $("#bloque").val();

            var url = '<?= Url::to(['eliminar-grupo-materia']) ?>';
           
            var params = {               
                idbloque: idbloque,
                idcurso: idcurso,
                idGrupo:idGrupo,
            };

            $.ajax({
                data: params,
                url: url,
                type: 'POST',
                beforeSend: function() {},
                success: function(response) {
                    showAsignaturas2();
                    mostar_materias_agrupadas();

                }
            });

        }
    </script>