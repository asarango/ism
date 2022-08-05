
<div class="card">

    <p>
        <h3><b><u><?= $fecha ?></u></b></h3>
    </p>

    <hr>
    
    <div class="table table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="text-center">ACT.#</th>
                    <th class="text-center">ASIGNATURA</th>
                    <th class="text-center">FECHA</th>
                    <th class="text-center">HORA</th>
                    <th class="text-center">TÍTULO</th>
                    <th class="text-center" colspan="2">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($actividades as $actividad){
                        ?>
                <tr>
                    <td><?= $actividad['actividad_id'] ?></td>
                    <td><?= $actividad['materia'] ?></td>
                    <td><?= $actividad['inicio'] ?></td>
                    <td><?= $actividad['sigla'] ?></td>
                    <td><?= $actividad['title'] ?></td>
                    <td class="text-center">
                        <?=
                                yii\helpers\Html::a('<i class="fas fa-plus-square" style="color: green"></i>',['profesor-inicio/index']);
                        ?>
                    </td>
                    <td class="text-center">
                        <?=
                                yii\helpers\Html::a('<i class="fas fa-pen" style="color: #0a1f8f"></i></i>',['scholaris-actividad/actividad',
                                     'actividad' => $actividad['actividad_id']
                                    ]);
                        ?>
                    </td>
                </tr>
                    <?php
                    }
                ?>
            </tbody>
        </table>
    </div>
    
</div>


