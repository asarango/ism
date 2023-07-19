<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

// echo"<pre>";
// print_r ($user);
// die();

$this->title = 'Firma de usuario';

?>
<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    td {
        border: 1px solid #ccc;
        padding: 8px;
    }

    .right-rows {
        height: 33%;
    }

    .blank {
        border: none;
        padding: 0;
    }

    .canvas-container {
        width: 200px;
        height: 150px;
    }
</style>

<div class="usuario-data-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <!-- INICIO CARD DE CUERPO -->
        <div class="card shadow col-lg-11 col-md-11">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/actividad-fisica.png" width="64px" style=""
                            class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-3">
                    <h2>
                        <?= Html::encode($this->title) ?>
                    </h2>
                </div>
                <!-- BOTONES DERECHA -->
                <div class="col-lg-8" style="text-align: right;">
                    <?=
                        Html::a(
                            '<span class="badge rounded-pill" style="background-color: #ff9e18"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                            ['site/index'],
                            ['class' => 'link']
                        );
                    ?>
                </div>
                <hr>
            </div>
            <div class="row align-items-center p-2" style="margin-top: -1rem;">
                <div class="card">
                    <?= Html::beginForm(['usuario-data'], 'post') ?>
                    <input type="hidden" name="id" value="<?= $user->rol_id; ?>">
                    <!-- <textarea name="titulo" id="editor1"><?= $user->usuario; ?></textarea> -->
                    <table>
                        <th>Firma</th>
                        <tr>

                            <td class="  right-rows" width="26%" rowspan="3">
                                <div class="canvas-container">
                                    <canvas class=" card canvas" id="canvas"></canvas>
                                </div>

                            </td>
                            <td><b>Correo:</b>
                                <?= $user->usuario ?>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                    </table>
                    <div style="margin-top: 1rem; margin-bottom: 1rem">
                        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']); ?>
                    </div>
                    <?= Html::endForm() ?>
                </div>
            </div>
        </div>
        <!-- FIN CARD DEL CUERPO -->
    </div>

</div>

<script>
    var canvas = document.getElementById('canvas');
    var ctx = canvas.getContext('2d');
    var isDrawing = false;
    var prevX = 0;
    var prevY = 0;

    canvas.addEventListener('mousedown', function (e) {
        isDrawing = true;
        prevX = e.offsetX;
        prevY = e.offsetY;
    });

    canvas.addEventListener('mousemove', function (e) {
        if (!isDrawing) return;
        ctx.beginPath();
        ctx.moveTo(prevX, prevY);
        ctx.lineTo(e.offsetX, e.offsetY);
        ctx.stroke();
        prevX = e.offsetX;
        prevY = e.offsetY;
    });

    canvas.addEventListener('mouseup', function () {
        isDrawing = false;
    });


</script>