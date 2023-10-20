<?php
// echo "<pre>";
// print_r($detallePlanes);
// die();

use yii\helpers\Url;
// variable para ver dia que cambia
$diaCambia = $detalleDesde->dia->nombre;

$codificado = json_encode($detalle);
$codPlan = json_encode($detallePlanes);

?>
<div class="row card col-lg-12" style="padding: 1rem;color: black; ">

    <div class="row ">
        <div class="col-lg-6 col-md-6">
            <h3 style="text-align: center;font-weight: bold;">Cambio novedades</h3>

            <table class="table table-bordered">
                <tr>
                    <th>Horario de Ingreso</th>
                    <th>Fecha</th>
                    <th>Día</th>
                    <th>Cambio</th>
                    <th>Nueva Fecha</th>
                    <th>Hora</th>
                </tr>

                <?php foreach ($detalle as $det) : ?>
                    <tr>
                        <td><?php echo $det['hora_ingresa']; ?></td>
                        <td><?php echo $det['fecha']; ?></td>
                        <td><?php echo $det['numero_dia']; ?></td>
                        <td title="Cambiar horario" style="text-align: center;"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-bar-to-right" width="32" height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="#00abfb" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M14 12l-10 0" />
                                <path d="M14 12l-4 4" />
                                <path d="M14 12l-4 -4" />
                                <path d="M20 4l0 16" />
                            </svg></td>
                        <td><?php echo $det['nueva_fecha']; ?></td>
                        <td><?php echo $det['hora']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div class="col-lg-6 col-md-6">

            <h3 style="text-align: center;font-weight: bold;">Planificaciones</h3>

            <!-- TABLA SEMANAL  -->

            <table class="table table-bordered">
                <tr>
                    <th>Fecha</th>
                    <th>Día</th>
                    <th>Cambio</th>
                    <th>Nueva Fecha</th>
                    <th>Hora</th>
                </tr>

                <?php foreach ($detallePlanes as $detPlanes) : ?>
                    <tr>
                        <td><?php echo $detPlanes['fecha']; ?></td>
                        <td><?php echo $detPlanes['numero_dia']; ?></td>
                        <td title="Cambiar horario" style="text-align: center;"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-bar-to-right" width="32" height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="#00abfb" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M14 12l-10 0" />
                                <path d="M14 12l-4 4" />
                                <path d="M14 12l-4 -4" />
                                <path d="M20 4l0 16" />
                            </svg></td>
                        <td><?php echo $detPlanes['nueva_fecha']; ?></td>
                        <td><?php echo $detPlanes['hora']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>

        </div>


    </div>
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6" style="text-align: center;">
            <button type="button" onclick="confirmarCambio()" class="btn btn-primary" style="font-weight: bold;">Cambiar Horario
            </button>
        </div>
        <div class="col-md-3"></div>

    </div>

</div>




<script>
    function confirmarCambio() {
        var confirmar = confirm('¿Estás seguro de que deseas realizar el cambio de horario?');
        if (confirmar) {
            cambiar();
        }
    }

    function cambiar() {
        var url = "<?= Url::to(['procesar-cambios'])  ?>";
        // alert('hola');
        let data = '<?php echo $codificado; ?>';
        let claseId = '<?php echo $claseId ?>';
        let codPlan = '<?php echo $codPlan ?>';
        let detalleDesdeId = '<?php echo $detalleDesde->id ?>';
        let detalleHastaId = '<?php echo $detalleHasta->id ?>';
        // alert(detalleDesdeId);
        // console.log(claseId);
        // alert(data);

        params = {
            data: data,
            claseId: claseId,
            detalleDesdeID: detalleDesdeId,
            detalleHastaID: detalleHastaId,
            dataPlan: codPlan,
        }

        $.ajax({
            type: "post",
            data: params,
            url: url,
            success: function(response) {
                console.log(response);

            }
        })

    }
</script>