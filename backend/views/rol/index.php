<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Perfiles de usuario';
?>

<div class="rol-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8">

            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/curriculum.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                </div>
            </div>
            <hr>


            <div class="row">
                <div class="col-lg-10 col-md-10">
                    |
                    <?= Html::a(
                        '<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="far fa-file"></i> Inicio</span>',
                        ['site/index'],
                        ['class' => 'link']
                    ); ?>
                    |
                    <?= Html::a(
                        '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="far fa-file"></i> Perfiles de usuario</span>',
                        ['operacion/index'],
                        ['class' => 'link']
                    ); ?>
                    |
                </div>

                <div class="col-lg-2 col-md-2" style="text-align: right;">
                    <?= Html::a('<i class="far fa-plus-square"> Crear perfil de usuario</i>', ['create'], ['class' => 'link']) ?>
                </div>
            </div>


            <!-- inicia tabla -->
            <div class="table table-responsive p-2">
                <table id="example" class="table table-striped table-hover">
                    <thead>
                        <tr bgcolor="#ff9e18">
                            <th class="text-center">ID</th>
                            <th>PERFIL</th>
                            <th class="text-center">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($perfiles as $perfil) {
                        ?>
                            <tr>
                                <td><?= $perfil->id ?></td>
                                <td><?= $perfil->rol ?></td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button style="font-size: 10px; border-radius: 0px" id="btnGroupDrop1" type="button" class="btn btn-outline-warning btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            Acciones
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                            <li>
                                                <?= Html::a(
                                                    'Editar',
                                                    ['update', 'id' => $perfil->id],
                                                    ['class' => 'dropdown-item', 'style' => 'font-size:10px']
                                                )
                                                ?>
                                            </li>

                                            <li>
                                                <?= Html::a(
                                                    'Permisos',
                                                    ['permisos', 'id' => $perfil->id],
                                                    [
                                                        'class' => 'dropdown-item', 'style' => 'font-size:10px'                                                       
                                                    ]
                                                )
                                                ?>
                                            </li>

                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr bgcolor="#ff9e18">
                            <th class="text-center">ID</th>
                            <th>PERFIL</th>
                            <th class="text-center">ACCIONES</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- finaliza tabla -->



        </div>
    </div>

</div>


<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>-->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
<script>
    $('#example').DataTable();
</script>