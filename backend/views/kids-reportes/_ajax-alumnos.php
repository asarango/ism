<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

// echo '<pre>';
// print_r($alumnos);
// print_r($quimestres);
// die();
?>

<div class="row" style="margin-top:20px">
    <div class="col-md-12 col-sm-12" style="text-align:end" >
    <?=
    Html::a(
        '<span class="badge rounded-pill bg-primero">Lib.Q1</span>',
        [
            'reportes',
            'paralelo_id' => $paraleloId,
            'quimestre_id' => $quimestres[0]['id'],
            'reporte' => 'libreta'
        ],
        [
            'class' => 'btn'
        ]
    );
    ?>
    &nbsp;
    <?=
    Html::a(
        '<span class="badge rounded-pill bg-segundo">Lib.Q2</span>',
        [
            'reportes',
            'paralelo_id' => $paraleloId,
            'quimestre_id' => $quimestres[1]['id'],
            'reporte' => 'libreta'
        ],
        [
            'class' => 'btn'
        ]
    );
    ?>

        
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="table table-responsive">
            <table class="table table-stripped table-condensed">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ALUMNO</th>
                        <th colspan="2">REPORTES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $cont = 1;
                    foreach ($alumnos as $key => $value) {
                        ?>
                    <tr>
                        <th><?=$cont?></th>
                        <td><?=$value['last_name'].' '.$value['first_name'].' '.$value['middle_name'] ?></td>
                        <td>
                            <?=
                            Html::a(
                                '<span class="badge rounded-pill bg-primero">Q1</span>'
                            );
                            ?>
                        </td>
                        <td>
                        <?=
                            Html::a(
                                '<span class="badge rounded-pill bg-segundo">Q2</span>'
                            );
                            ?>
                        </td>
                    </tr>
                        <?php
                        $cont++;
                    }
                    ?>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>