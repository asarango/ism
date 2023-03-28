<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisAsistenciaProfesorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mi horario de clases';
?>

<div class="scholaris-asistencia-profesor-index" style="padding-left: 40px; padding-right: 40px">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/actividad-fisica.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                </div>
            </div>
            <hr>

            <p>
                |
                <?=
                Html::a(
                    '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                    ['site/index'],
                    ['class' => 'link']
                );
                ?>
                |
               
            </p>

            <!--comienza cuerpo de documento-->

            
            <div class="table table-responsive">
                <!-- <table class="table table-striped table-condensed table-hover" id="table-asistencias"> -->
                <table class="table table-striped table-condensed table-hover" id="table-asistencias">
                    <thead>
                        <tr bgcolor="ff9e18" class="text-center">
                            
                            <th>#</th>
                            <th>DIA</th>
                            <th>HORA</th>
                            <th>CURSO</th>
                            <th>PARALELO</th>
                            <th>ASIGNATURA</th>                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        
                        $i=0;
                        foreach ($horario as $data) 
                        {                            
                            $i++;
                            echo '<tr>';                                
                                echo '<td>' . $i. '</td>';
                                echo '<td>' . $data['dia']. '</td>';
                                echo '<td>' . $data['hora'] . '</td>';                                
                                echo '<td>' . $data['curso'] . '</td>';                                
                                echo '<td>' . $data['paralelo'] . '</td>';                                
                                echo '<td>' . $data['materia'] . '</td>';                                
                            echo '</tr>';                            
                        }
                        
                        
                        ?>
                    </tbody>                   
                </table>
               
            </div>

            <!--finaliza cuerpo de documento-->


        </div>
    </div>

</div>


<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>-->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
<script>
    $("#table-asistencias").DataTable({
        language: {
            "decimal": "",
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Entradas",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },

    });

       function alerta() {
           alert('Registrado exitosamente!!!');
       }

    function bloque_btn_registrar() {

        $('.btn_registrar').hide();
        $('.btn-sppiner').show();

        //alert(btn_id);

    }
</script>