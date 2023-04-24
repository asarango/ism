<div class="row">
    <div class="col-lg-12 col-md-12">
        <?php
        foreach ($tareas as $keyTarea => $tarea) {
            ?>
            <div class="card shadow" style="padding: 15px; margin-bottom: 10px; ">
                <div class="text-center">
                    <strong>
                        Título:
                        <?= $tarea["titulo"] ?>
                    </strong>
                </div>


                <br>

                <strong>Detalle:</strong>
                <p>
                    <?= $tarea["detalle_tarea"] ?>
                </p>

                <strong>Recursos:</strong>
                <p>
                    <?= $tarea["materiales"] ?>
                </p>

                <strong>Fecha de presentación:</strong>
                <p>
                    <?= $tarea["created_at"] ?>
                </p>

            </div>
            <?php
        }
        ?>

    </div>
</div>