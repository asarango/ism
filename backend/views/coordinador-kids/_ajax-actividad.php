<?php
use yii\helpers\Url;

?>
<hr>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <h4> <strong>Actividad:</strong> </h4>
        <strong>
            <?= $data[0]["actividades"] ?>
        </strong>
    </div>
</div>
<hr>
<div class="row">
    <?php
    foreach ($data as $keyDes => $des) {
        ?>
        <div class="col-lg-12 col-md-12 card shadow " style="margin-bottom: 10px; padding:10px">
            <strong class="text-primero">Ámbito:
                <?= $des["ambito_codigo"] . " " . $des["ambito_nombre"] ?>
            </strong>
            <br>
            <strong class="text-segundo">Destreza:</strong>
            <p class="text-segundo" style="text-align: justify;">
                <?= $des["destreza_codigo"] . " " . $des["destreza_nombre"] ?>
            </p>
            <br>
            <!-- Button trigger modal -->
            <a type="button" class="btn btn-primary btn-small" data-bs-toggle="modal" data-bs-target="#exampleModal"
                onclick="get_tareas(<?= $des['destreza_id'] ?>)">
                <i class="fas fa-pencil-alt"> Ver Evaluaciones/Tareas</i>
            </a>
        </div>
        <?php
    }
    ?>

</div>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Evaluaciones/Tareas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="div_tareas"></div>
            </div>
        </div>
    </div>
</div>

<script>
    //Funcion que muestra tareas de la destreza seleccionada
    function get_tareas(destreza_id) {
        var url = "<?= Url::to(["tareas"]) ?>";
        var params = { destreza_id: destreza_id };

        $.ajax({
            url: url,
            data: params,
            type: "POST",
            success: function (resp) {
                $("#div_tareas").html(resp);
            }
        });

    }
</script>