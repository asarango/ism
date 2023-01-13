<?php

use backend\models\helpers\HelperGeneral;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Adaptación curricular - ' . $lmsDocente->clase->ismAreaMateria->materia->nombre;


$helper = new HelperGeneral();

?>
<!--<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>-->
<script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>

<link rel="stylesheet" href="estilo.css" />


<div class="lms-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/aula.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-8">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small>
                        <?=
                        $lmsDocente->clase->paralelo->course->name .
                            ' ' . $lmsDocente->clase->paralelo->name
                        ?>
                    </small>
                </div>

                <div class="col-lg-3 col-md-3">
                    <?=
                    Html::a(
                        '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fas fa-step-backward"></i> Asignaturas</span>',
                        ['index1',
                            'semana_numero' => $semanaNumero,
                            'nombre_semana' => $nombre_semana,
                            'clase_id'      => $clase_id
                        ],
                        ['class' => 'link']
                    );
                    ?>
                    |
                </div>
            </div>
            <hr>

            <!--incia cuerpo-->
            <div class="row">
                <!-- inicio de lista de estudiantes -->
                <div class="col-lg-4 col-md-4">

                    <div class="row">
                        <div class="col-lg-4 col-md-4"><i class="fas fa-circle" style="color: green;"> Grado 1</i></div>
                        <div class="col-lg-4 col-md-4"><i class="fas fa-circle" style="color: orange;"> Grado 2</i></div>
                        <div class="col-lg-4 col-md-4"><i class="fas fa-circle" style="color: red;"> Grado 3</i></div>
                    </div>
                    
                    <hr>
                    
                    <?php
                    foreach ($nees as $nee) {
                        $grado = $nee['grado_nee'];
                        if($grado == 3){
                            $color = 'red';
                        }else if($grado == 2){
                            $color = 'orange';
                        }else{
                            $color = 'green';
                        }
                    ?>
                        <div class="card zoom" style="margin-bottom: 10px; padding: 5px; background-color: <?= $color ?>;">
                            <div class="row">
                                <div class="col-lg-1 col-md-1">
                                    <img src="../ISM/main/images/estudiante.png">
                                </div>
                                <div class="col-lg-11 col-md-11">
                                    <?php
                                    if ($nee['adaptacion_curricular'] != 'None') {
                                        echo '<i class="fas fa-check-circle" style="color: #0a1f8f"> </i>';
                                    } else {
                                        echo '<i class="fas fa-times-circle" style="color: #ab0a3d"> </i>';
                                    }

                                    echo ' '.'<a href="#" onclick="showDetail(' . $nee['id'] . ')" style="color: white">' . $nee['student'] . '</a>';
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <!-- fin de lista de estudiantes -->


                <!-- inicio de detalle -->
                <div class="col-lg-8 col-md-8">
                    <div id="div-nee-detalle"></div>
                </div>
                <!-- fin de detalle -->
            </div>
            <!--fin de cuerpo-->

        </div>
    </div>
</div>

<script>
    function showDetail(id) {
        var url = '<?= Url::to(['nee-detalle']) ?>';

        var params = {
            lms_docente_nee_id: id
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function() {},
            success: function(response) {
                $('#div-nee-detalle').html(response);
            }
        });
    }
</script>