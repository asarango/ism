<?php

use backend\models\PlanSemanalBitacora;
use yii\helpers\Url;

// echo "<pre>";
// print_r($coordinadores);
// die();
$docenteUsuario = Yii::$app->user->identity->usuario;

?>
<style>
    .envio {
        display: inline-block;
        padding: 10px;
        background-color: #0a1f8f;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: transform 0.3s;
    }

    .envio:hover {
        background-color: #ab0a3d;
        color: #ff9e18;
        transform: scale(1.05);
    }


    .en-coord {
        display: inline-block;
        padding: 10px;
        background-color: #ff9e18;
        color: white;
        border: none;
        border-radius: 5px;
        /* animation: respirar 2s infinite alternate; */
    }

    .devuelta {
        display: inline-block;
        padding: 10px;
        background-color: #ff2825;
        color: white;
        border: none;
        border-radius: 5px;
        animation: respirar .5s alternate;
    }


    @keyframes respirar {
        0% {
            transform: scale(1);
        }

        100% {
            transform: scale(1.1);
        }
    }

    .aprov {
        display: inline-block;
        padding: 10px;
        background-color: #7bc62d;
        color: white;
        border: none;
        border-radius: 5px;
        animation: vibrar .5s 3;
    }

    @keyframes vibrar {
        0% {
            transform: translateX(0);
        }

        25% {
            transform: translateX(-2px);
        }

        50% {
            transform: translateX(2px);
        }

        75% {
            transform: translateX(-2px);
        }

        100% {
            transform: translateX(2px);
        }
    }

    .aler {
        animation: respirar .5s ease-in-out infinite;
    }

    @keyframes respirar {

        0%,
        100% {
            transform: scale(1.1);
        }

        50% {
            transform: scale(1);
        }
    }
</style>

<div class="row">
    <div class="col-lg-10 col-md-10">
        <iframe width="100%" height="600" src="<?= Url::toRoute([
                                                    'acciones',
                                                    'action' => 'pdf',
                                                    'week_id' => $weekId
                                                ]) ?>">
        </iframe>
    </div>
    <div class="col-lg-2 col-md-2" style="text-align: center; font-weight: bold;">
        <h6 style="font-weight: bold;" title="En este campo podrá visualizar el estado de su planificación.">
            <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: -5px;" class="icon icon-tabler icon-tabler-info-circle" width="20" height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="#9e9e9e" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                <path d="M12 9h.01" />
                <path d="M11 12h1v4h1" />
            </svg>Estados:
        </h6>

        <hr>

        <?php
        foreach ($coordinadores as $coordinador) {

            if (!isset($coordinador['estado'])) {
                // echo 'Iniciando envío' . '<br>';
        ?>
                <a href="#" onclick="enviar_coordinador('<?= $coordinador['usuario'] ?>', <?= $weekId ?>)" class="envio">Enviar
                    a Coordinador
                </a>
        <?php
            } elseif (($coordinador['estado']) == 'COORDINADOR') {
                echo '<p class="en-coord" id="texto">Esperando respuesta de coordinador</p>';
            } elseif (($coordinador['estado']) == 'DEVUELTO') {
                echo '<p class="aler "><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alert-circle" width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ff2825" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                <path d="M12 8v4" />
                <path d="M12 16h.01" />
              </svg>  Su planificación ha sido devuelta!</p>';
                echo '<a href="#" style="margin-bottom: 5px;" onclick="enviar_coordinador(\'' . $coordinador['usuario'] . '\', ' . $weekId . ')" class="envio">Reenviar Planificación</a>';
                // inicia ventana modal retroalimentacion
                retroalimentacion_coordinador($docenteUsuario, $weekId);
                //fin ventana modal retroalimentacion

            } elseif (($coordinador['estado']) == 'APROBADO') {
                echo '<p class="aprov">Su planificación ha sido aprobada!</p>';
            }
        }
        ?>

        <div class="row">

        </div>

    </div>
</div>

<script>
    function enviar_coordinador(coordinador, semanaId) {
        var confirmacion = confirm('¿Estás seguro de que deseas enviar la planificación al coordinador ' + coordinador + ' para la semana N°' + semanaId + '?');

        if (confirmacion) {
            let url = '<?= Url::to(['acciones']) ?>';

            params = {
                week_id: semanaId,
                action: 'enviar',
                coordinador: coordinador
            };

            $.ajax({
                data: params,
                url: url,
                type: 'GET',
                beforeSend: function() {},
                success: function(response) {
                    // $("#div-detail-week").html(response);
                    refresh(semanaId);
                }
            });
        } else {

        }
    }


    function refresh(semanaId) {
        let weekId = semanaId;
        let url = '<?= Url::to(['acciones']) ?>';

        params = {
            week_id: weekId,
            action: 'detail-week'
        };

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function() {},
            success: function(response) {
                $("#div-detail-week").html(response);
            }
        });
    }
</script>

<script>
    function agregarPuntosProgresivos() {
        const textoElement = document.getElementById("texto");

        function agregarYReiniciar() {
            let puntos = "";
            let contador = 0;

            const interval = setInterval(function() {
                puntos += ".";
                textoElement.textContent = "Esperando respuesta de coordinador" + puntos;
                contador++;

                if (contador === 3) {
                    clearInterval(interval);
                    setTimeout(agregarYReiniciar, 50);
                }
            }, 550);
        }

        agregarYReiniciar();
    }
    agregarPuntosProgresivos();
</script>



<?php

function retroalimentacion_coordinador($docenteUsuario, $weekId)
{

    $retroalimentacion = PlanSemanalBitacora::find()
        ->where([
            'semana_id' => $weekId,
            'docente_usuario' => $docenteUsuario,
        ])
        ->orderBy(['id' => SORT_DESC])
        ->one();


    // print_r($retroalimentacion);
    // die();
    // echo 100;
    $modal = '<!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
      Mostrar retroalimentación.
    </button>
    
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Tiene las siguientes observaciones:</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body"> '
        . $retroalimentacion->obervaciones .
        '</div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>';
    echo $modal;
}

?>