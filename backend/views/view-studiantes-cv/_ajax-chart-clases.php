<?php

use yii\helpers\Url;

?>
<div class="row text-center">
    <div class="col">
        <h4 class="card-title"><i class="fas fa-book"></i> &nbsp; Materias</h3>
    </div>
</div>


<!-- <php print_r($chartClases); ?> -->
<div class="row">
    <div class="col-lg-12 col-md-12">
        <canvas id="myChart"></canvas>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="claseModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="claseModalLabel"></h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="div-detalle-clase"></div>
            </div>
        </div>
    </div>
</div>

<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->

<script>
    <?php echo "var labelsX = " . json_encode($chartClases["labels"]) . ";";  ?>
    <?php echo "var dataY = " . json_encode($chartClases["valores"]) . ";";  ?>
    const ctx = document.getElementById('myChart');

    var inscriptionId = '<?php echo $inscriptionId; ?>';

    // console.log(label);
    // var labelsA = ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'];
    // var dataY = [12, 19, 3, 5, 2, 3];

    // alert(labAel);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labelsX,
            datasets: [{
                label: 'Clases Estudiante',
                data: dataY,
                borderWidth: 1
            }]
        },
        options: {
            // maintainAspectRatio: false, // Desactiva el mantenimiento automático del aspecto
            responsive: true, // Activa la capacidad de respuesta
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            onClick: function(event, elements) {
                if (elements.length > 0) {
                    // Se hizo clic en una barra
                    var index = elements[0].index;
                    var label = labelsX[index];
                    var value = dataY[index];
                    // alert(labelsX[index]);
                    // alert('Se hizo clic en la barra: ' + labelsX + ' (Valor: ' + value + ')');
                    $("#claseModalLabel").html(label);
                    // $('#claseModal').modal('toggle');
                    show_detalle_clase(labelsX[index]);
                }
            }
        }
    });

    function show_detalle_clase(clase) {
        var url = "<?= Url::to(['acciones'])  ?>";
        var inscription_id = "<?= $inscriptionId ?>";
        var params = {
            'accion': 'detalle-clase',
            "inscription_id": inscription_id,
            "nombre_clase": clase
        };

        $.ajax({
            url: url,
            data: params,
            type: 'GET',
            success: function(resp) {
                $('#claseModal').modal('toggle');
                $("#div-detalle-clase").html(resp);
            }
        });

    }
</script>