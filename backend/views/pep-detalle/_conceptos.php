<?php

use backend\models\PepOpciones;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */



//buscamos los tipos de Coneptos claves
$tipoConceptosClaves = PepOpciones::find()
->select('categoria_principal_es')
->distinct(true)
->where(['tipo'=>'concepto_clave'])
->orderBy(['categoria_principal_es'=>SORT_ASC])
->all();


?>

<div class="row">
    <div class="col-lg-4 col-md-4">
        <p class="text-primero"><b><u><i class="fas fa-cog"> Conceptos clave</i></u></b></p>

        <?php
        foreach ($registros as $reg) {
            if ($reg->tipo == 'concepto_clave') {
                $reg->contenido_opcion ? $check = 'checked' : $check = '';
                ?>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" <?= $check ?>
                        onclick="update(<?= $reg->id ?>)">
                        <label class="form-check-label" for=""><?= $reg->contenido_texto ?></label>
                      </div>
                <?php
            }
        }
        ?>


    </div>
    <div class="col-lg-5 col-md-5">
        <p class="text-primero"><b><u><i class="fas fa-cogs"> Conceptos relacionados</i></u></b>            
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" >
                <span style="font-size:12px;">(Agregar)  </span>
                </button>          
        </p>        
        <?php
        foreach ($registros as $reg) 
        {
            if ($reg->tipo == 'concepto_relacionado') 
            {
                $reg->contenido_opcion ? $check = 'checked' : $check = '';
                $claveModel = \backend\models\PepOpciones::find()->where(['contenido_es' => $reg->contenido_texto])->one();
                ?>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" <?= $check ?>
                        onclick="update(<?= $reg->id ?>)">
                        <div class="row">
                            <div class="col-lg-5 col-md-5" style="font-size: 10px"><?= $claveModel->categoria_principal_es ?>:</div>
                            <div class="col-lg-7 col-md-7"><label class="form-check-label" for=""><b><?= $reg->contenido_texto ?></b></label></div>
                            
                        </div>
                        
                      </div>
                <?php
            }
        }
        ?> 
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Concepto Relacionado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- muestra los tipo de concetos -->
                
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-sm-4 col-form-label">Concepto Clave:</label>
                        <div class="col-sm-7">
                            <select id="conceptoClave" class="form-select" aria-label="Default select example">
                                <option selected>Seleccione Una Opción</option>
                                <?php
                                //for para cargar los tipo de conceptos claves
                                foreach($tipoConceptosClaves as $tipo)
                                {                                    
                                ?>
                                    <option value="<?=$tipo->categoria_principal_es?>"><?=$tipo->categoria_principal_es?></option>                                
                                <?php
                                }
                                //fin for
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="inputPassword" class="col-sm-5 col-form-label">Concepto Relacionado: </label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="conceptoRelacionado">
                        </div>
                    </div> 
                <!-- muestra cuadro para ingresar nombre de concepto -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="create_concepto_relacionado()">Guardar</button>
            </div>
            </div>
        </div>
        </div>       
    </div>
    <!--fin de conceptos relacionados-->
    
    
    <!--inicio de atributos de perfil-->
    <div class="col-lg-3 col-md-3">
        <p class="text-primero"><b><u><i class="fas fa-cogs"> Atributos del perfil</i></u></b></p>
        <?php
        foreach ($registros as $reg) {
            if ($reg->tipo == 'atributos_perfil') {
                $reg->contenido_opcion ? $check = 'checked' : $check = '';
                ?>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" <?= $check ?>
                        onclick="update(<?= $reg->id ?>)">
                        <label class="form-check-label" for=""><?= $reg->contenido_texto ?></label>
                      </div>
                <?php
            }
        }
        ?>
    </div>
    <!--fin de atributos de perfil-->
    
</div>


<script>
    function update(id){
        var url = '<?= yii\helpers\Url::to(['update-selection']) ?>';

        params = {
          id : id      
        };

        $.ajax({
                data: params,
                url: url,
                type: 'POST',
                beforeSend: function () {},
                success: function (resp) {   
                    respuesta = JSON.parse(resp);
                    var estado = respuesta.status;

                    if(estado == 'ok'){
                        //alert('Actualizado correctamente!');
                    }else{
                        alert('El registro no se actualizó!');
                    }
                }
            });
    }
    function create_concepto_relacionado()
    {
        var url = '<?= yii\helpers\Url::to(['create-concepto-relacionado']) ?>';
        
        var conceptoClave = $("#conceptoClave").val();
        var conceptoRelacionado = $("#conceptoRelacionado").val();  
        var temaId =<?=$tema->id?>;  
     
        params = {
            conceptoClave : conceptoClave,
            conceptoRelacionado : conceptoRelacionado,
            temaId : temaId   
        };

        $.ajax({
                data: params,
                url: url,
                type: 'GET',
                beforeSend: function () {},
                success: function (resp) 
                {   
                                      
                    if(resp){
                        alert("ITEM CREADO Y MARCADO DE FORMA CORRECTA");
                        location.reload();
                    }else
                    {
                        alert("ITEM YA EXISTE");
                    }
                }
            });
    }
</script>