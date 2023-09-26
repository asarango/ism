<?php

use yii\helpers\Url;

// echo "<pre>";
// print_r($coordinadores);
// die ();

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
            <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: -5px;"
                class="icon icon-tabler icon-tabler-info-circle" width="20" height="20" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="#9e9e9e" fill="none" stroke-linecap="round" stroke-linejoin="round">
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
                // echo 'Iniciado' . '<br>';
                ?>
                <a href="#" onclick="enviar_coordinador('<?= $coordinador['usuario'] ?>', <?= $weekId ?>)" class="envio">Enviar
                    a Coordinador
                </a>
                <?php
            } elseif (isset($coordinador['estado']) == 'coordinador') {
                echo '<p class="en-coord" id="texto">Esperando respuesta de coordinador</p>';
            } elseif (isset($coordinador['estado']) == 'devuelto') {
                echo '<p class="devuelta">Su planificación ha sido devuelta!</p>';
                echo '<a href="#" onclick="enviar_coordinador(\'' . $coordinador['usuario'] . '\', ' . $weekId . ')" class="envio">Reenviar Planificación</a>';
            } else {
                echo '<p class="aprov">Su planificación ha sido aprobada!</p>';
            }
        }
        ?>

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
                beforeSend: function () { },
                success: function (response) {
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
            beforeSend: function () { },
            success: function (response) {
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

            const interval = setInterval(function () {
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