<?php

use yii\helpers\Html;

// echo '<pre>';
// print_r($clases);
// die();
?>


<ol class="list-group list-group-numbered" style="margin-top: 5px; margin-bottom: 5px">
    <?php
    foreach ($clases as $clase) {
    ?>
        <li class="list-group-item list-group-item-action  d-flex justify-content-between align-items-start" style="font-size: 12px;">
            <div class="ms-2 me-auto">
                <div class="fw-bold"><?= $clase['materia'] ?></div>
                <?= $clase['curso'] ?>
                <!-- <a href="#" onclick="muestra_detalle_curso(<?= $clase['clase_id'] ?>)"> -->
                    <span class="badge bg-primary rounded-pill zoom"><?= $clase['paralelo'] ?></span>
                </a>
            </div>
            |
            <div>
                <?= Html::a(
                    '<span class="badge rounded-pill" style="background-color: #ff9e18">
                <i class="far fa-file"></i> Ver lista de estudiantes</span>',
                    ['agregar-alumnos', 'clase_id' => $clase['clase_id']]
                );
                ?>
            </div>
        </li>
    <?php
    }
    ?>
</ol>

<!----------------------------->
<script>
    function muestra_detalle_curso(claseId) {
        muestra_detalle(claseId, 'bloques');
        $('#div-semanas').hide();
    }
</script>