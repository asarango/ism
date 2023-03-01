<?php

use yii\helpers\Url;

?>

<div class="col-lg-12 col-md-12">
    <div class="table table-responsive">
        <table class="table table-hover table-striped table-condensed table-bordered" style="font-size: 11px;">
            <thead>
                <tr>
                    <th class="text-center">CURSO</th>
                    <th class="text-center">AREA</th>
                    <th class="text-center">CRITERIO</th>
                    <th class="text-center">CRITERIO DESCRIPCIÓN</th>
                    <th class="text-center">DESCR</th>
                    <th class="text-center">DESCRIPTOR</th>
                    <th class="text-center">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($criteria as $cri){
                        ?>
                        <tr>
                            <td><?= $cri['curso'] ?></td>
                            <td><?= $cri['area'] ?></td>
                            <td class="text-center"><?= $cri['criterio'] ?></td>
                            <td><?= $cri['nombre_espanol'] ?></td>
                            <td class="text-center"><?= $cri['descriptor'] ?></td>
                            <td><?= $cri['descripcion'] ?></td>
                            <td class="text-center">
                                <a href="#" onclick="delete_dis(<?= $cri['id'] ?>)" style="color: #ab0a3d">Delete</a>
                            </td>
                        </tr>
                        <?php
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>


<script>
    function delete_dis(id){
        var url = '<?= Url::to(['actions']) ?>';
        var params = {
            id: id,
            field: 'delete'
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function() {},
            success: function(response) {
                showAreaOne();
            }
        });

    }
</script>