<style>
  .trim {
    /* margin: 5px 10px;
    font-size: 1.3rem; */
    font-weight: bold;
    text-decoration: none;
    padding: 10px 20px;
    background-color: #65b2e8;
    color: white;
    border-radius: 10px;
    transition: background-color 0.3s;
  }

  .trim:hover {
    background-color: #ab0a3d;
    color: white;
  }
</style>

<?php

function transforma_notas_arreglo($arreglo)
{
  $arrayNotas = array();
  foreach ($arreglo as $key => $value) {
    $arrayNotas[] = $value['nota'];
  }

  return $arrayNotas;
}


function transforma_labels_arreglo($arreglo)
{
  $arrayLabels = array();
  foreach ($arreglo as $key => $value) {
    $arrayLabels[] = $value['estudiante'];
  }

  return $arrayLabels;
}

?>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<!-- Button trigger modal -->
<button type="button" class="btn trim" data-bs-toggle="modal" data-bs-target="#exampleModal">
  Estadísticas -
  <?php echo $trimestre->name ?>
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">
          <?= $trimestre->name ?>
        </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div style="width: 80%; margin: 0 auto;">
          <canvas id="grafico"></canvas>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
    </div>
  </div>
</div>


<script>
  // Obtén los datos de PHP
  <?php
  $data = transforma_notas_arreglo($notasFinales);
  // $data = [10, 20, 30, 40, 50]; // Ejemplo de datos, reemplaza con tus propios datos
  echo "var data = " . json_encode($data) . ";";
  $labels = transforma_labels_arreglo($notasFinales);
  echo "var labels = " . json_encode($labels) . ";";
  ?>

  // Configura el gráfico de barras

  var ctx = document.getElementById('grafico').getContext('2d');
  var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
      // labels: ['Dato 1', 'Dato 2', 'Dato 3', 'Dato 4', 'Dato 5', 'Dato 6'], // Etiquetas para las barras
      labels: labels, // Etiquetas para las barras
      datasets: [{
        label: 'Gráfico de estudiantes',

        data: data, // Datos de PHP
        backgroundColor: 'rgba(75, 192, 192, 0.6)', // Color de las barras
        borderColor: 'rgba(75, 192, 192, 1)', // Color del borde de las barras
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>