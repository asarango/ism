<?php
use yii\helpers\Html;
use yii\helpers\Url;

// print_r($micro);
// die();

?>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <!-- Button trigger modal -->
<a type="button"  data-bs-toggle="modal" data-bs-target="#modalObjetivo">
    <i class="fas fa-plus-square" style="color:#0a1f8f">Agregar objetivo</i>
</a>



    </div>
</div>


<!-- Muestra DataTable -->
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="table responsive">
            <table class="table table-hover table-striped my-text-medium">
                <thead style="background-color:#0a1f8f; color:white">
                    <tr>
                       <th style="text-align:center">OBJETIVO</th> 
                       <th>ACCION</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach($objetivosSeleccionados as $seleccionado){
                        ?>
                        <tr>
                            <td style="text-align:justify"><?='<strong>'.$seleccionado->objetivo->codigo.'</strong>'.$seleccionado->objetivo->detalle  ?></td>
                            <td>
                                <?=Html::a(
                                    'Eliminar',
                                    [
                                        'micro-objetivo',
                                        'bandera' => 'eliminar',
                                        'id' => $seleccionado['id'],
                                        'micro_id' => $seleccionado['micro_id']
                                    ],
                                    [
                                        'class' => 'link'
                                    ]
                                    ) 
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
</div>



<!-- Modal Agrega Objetivo-->
<div class="modal fade" id="modalObjetivo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar objetivo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div class="table responsive">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                       <th style="text-align:center" >OBJETIVO</th> 
                       <th style="text-align:center" >ACCION</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($objetivosDisponibles as $disponible){
                            ?>
                            <tr>
                                <td><?='<strong>'.$disponible['codigo'].'</strong>'.$disponible['detalle']?></td>
                                <?php $id = $disponible['id'];  ?>
                                <td>
                                    <?=Html::a(
                                        'Insertar',
                                        [
                                            'micro-objetivo',
                                            'bandera' => 'objetivo',
                                            'objetivo_id' => $disponible['id'],
                                            'micro_id' => $micro['id']
                                        ],
                                        [
                                            'class'=> 'link'
                                        ]
                                    )?>
                                </td>
                            </tr>
                            <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>
</div>