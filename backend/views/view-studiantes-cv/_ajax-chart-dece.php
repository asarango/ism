<?php //print_r($chartDece);
?>
<div class="row">
    <div class="col" style="text-align: start;">
        <p class="card-title"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brain" width="100" height="100" viewBox="0 0 24 24" stroke-width="2.5" stroke="#9e9e9e" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M15.5 13a3.5 3.5 0 0 0 -3.5 3.5v1a3.5 3.5 0 0 0 7 0v-1.8" />
                <path d="M8.5 13a3.5 3.5 0 0 1 3.5 3.5v1a3.5 3.5 0 0 1 -7 0v-1.8" />
                <path d="M17.5 16a3.5 3.5 0 0 0 0 -7h-.5" />
                <path d="M19 9.3v-2.8a3.5 3.5 0 0 0 -7 0" />
                <path d="M6.5 16a3.5 3.5 0 0 1 0 -7h.5" />
                <path d="M5 9.3v-2.8a3.5 3.5 0 0 1 7 0v10" />
            </svg> &nbsp; DECE</p>
    </div>

    <div class="col" style="text-align: end;">
        <!-- Button trigger modal -->
        <a type="button" data-bs-toggle="modal" data-bs-target="#deceModal" onclick="show_dece_detalle()" style="background-color: #1b325f;color: white; padding: 7px;border-radius: 15px; cursor: pointer;font-size: 13px;">
            Ver tabla
        </a>
    </div>

</div>


<!-- Aqui se pinta el PIE -->
<div class="row">
    <div class="col-lg-12 col-md-12">
        <canvas id="chartDece"></canvas>
    </div>
</div>




<!-- Modal DECE-->
<div class="modal fade" id="deceModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal-title" id="exampleModalLabel">DECE</p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="div_dece_modal"></div>
            </div>

        </div>
    </div>
</div>

<script>
    <?php



    use yii\helpers\Url;

    $labelNuevo = [
        'total_casos' => 'Casos',
        'total_derivacion' => 'Derivación',
        'total_deteccion' => 'Detección',
        'total_intervencion' => 'Intervención',
        'total_seguimiento' => 'Seguimiento',
    ];

    $labelC = array_map(function ($label) use ($labelNuevo) {
        return $labelNuevo[$label];
    }, $chartDece["labels"]);

    // Agrega el porcentaje solo en el array $labelsWithPercentage
    $labelsWithPercentage = array_map(function ($label, $value) use ($chartDece) {
        $total = array_sum($chartDece["valores"]);
        $percentage = round(($value / $total) * 100, 2); // Redondea a dos decimales
        return $label; // Etiqueta sin el porcentaje
    }, $labelC, $chartDece["valores"]);

    echo "var labelsC = " . json_encode($labelC) . ";"; // Etiquetas sin el porcentaje
    echo "var labelsWithPercentage = " . json_encode($labelsWithPercentage) . ";"; // Etiquetas con el porcentaje
    echo "var dataS = " . json_encode($chartDece["valores"]) . ";";
    ?>


    const ctxF = document.getElementById('chartDece');

    new Chart(ctxF, {
        type: 'pie',
        data: {
            labels: labelsWithPercentage, // Usar labelsWithPercentage en lugar de labelsC
            datasets: [{
                label: 'DECE Estudiante',
                data: dataS,
                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            onClick: function(event, elements) {
                if (elements.length > 0) {
                    var index = elements[0].index;
                    var label = labelsC[index]; // Sin el porcentaje
                    var value = dataS[index];
                    var total = dataS.reduce((a, b) => a + b, 0);
                    var percentage = ((value / total) * 100).toFixed(2);
                    alert(label + ' '+ percentage + '%');
                }
            }
        }
    });



    //Funcion para mostrar detalle del DECE
    function show_dece_detalle() {
        var url = "<?= Url::to(['acciones']) ?>";
        var inscription_id = <?= $inscriptionId ?>;
        var params = {
            'accion': 'detalle-dece',
            'inscription_id': inscription_id
        };

        $.ajax({
            url: url,
            data: params,
            type: 'GET',
            success: function(resp) {
                $("#div_dece_modal").html(resp);
            }
        });
    }
</script>