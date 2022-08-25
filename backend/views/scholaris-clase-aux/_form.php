<?php
    use yii\helpers\Html;
?>

<div class="card bg-sexto" style="padding: 20px">
    <?= Html::beginForm(['update'], 'post') ?>
    
    <input type="hidden" name="id" value="<?= $model->id ?>">

    <label class="">Docente:</label>
    <select name="idprofesor" class="form-control" required="">
        <option value="<?= $model->idprofesor ?>"><?= $model->profesor->last_name.' '.$model->profesor->x_first_name ?></option>
        <?php
        foreach ($modelDocentes as $docente) {
            ?>
            <option value="<?= $docente->id ?>"><?= $docente->last_name.' '.$docente->x_first_name ?></option>
            <?php
        }
        ?>
    </select>
    
    <label class="">Horario asiganado:</label>
    <select name="horario_asignado" class="form-control" required="">
        <?php $mHorario = \backend\models\ScholarisHorariov2Cabecera::findOne($model->asignado_horario); ?>
        <option value="<?= $model->asignado_horario ?>"><?= $mHorario->descripcion ?></option>
        <?php
        foreach ($modelHorarioA as $horario) {
            ?>
            <option value="<?= $horario->id ?>"><?= $horario->descripcion ?></option>
            <?php
        }
        ?>
    </select>
    
    <label class="">Tipo bloque:</label>
    <?php 
        // $mHorario = \backend\models\ScholarisBloqueComparte::findOne($model->tipo_usu_bloque); 
        $mHorario = \backend\models\ScholarisBloqueComparte::find()
            ->where(['valor' => $model->tipo_usu_bloque])
            ->one(); 
    ?>
    <select name="tipo_usu_bloque" class="form-control" required="">
        <option value="<?= $model->tipo_usu_bloque ?>"><?= $mHorario->nombre ?></option>
        <?php
        foreach ($modelTipoBloque as $tipo) {
            ?>
            <option value="<?= $tipo->valor ?>"><?= $tipo->nombre ?></option>
            <?php
        }
        ?>
    </select>    
    
    <label class="">Todos los alumnos?:</label>
    <select name="todos_alumnos" class="form-control" required="">
        <option value="<?= $model->todos_alumnos ?>"><?= ($model->todos_alumnos) == 1 ? 'SI' : 'NO';  ?></option>
        <option value="1">SI</option>
        <option value="0">NO</option>
    </select>
    
    
    <?php
    $mRector = backend\models\OpInstituteAuthorities::findOne($model->rector_id);
    $mCoordD = backend\models\OpInstituteAuthorities::findOne($model->coordinador_dece_id);
    $mSecret = backend\models\OpInstituteAuthorities::findOne($model->secretaria_id);
    $mCoordA = backend\models\OpInstituteAuthorities::findOne($model->coordinador_academico_id);
    $mInspec = backend\models\OpInstituteAuthorities::findOne($model->inspector_id);
    $mDeceDh = backend\models\OpInstituteAuthorities::findOne($model->dece_dhi_id);
    $mTutorI = backend\models\OpInstituteAuthorities::findOne($model->tutor_id);
    $mIsmArM = backend\models\OpInstituteAuthorities::findOne($model->ism_area_materia_id);
    
    ?>    
    
    <label class="">Rector:</label>
    <select name="rector_id" class="form-control" required="">
        <option value="<?= $model->rector_id ?>"><?= $mRector->usuario0->usuario ?></option>
        <?php
        foreach ($modelAutoridades as $aut) {
            ?>
            <option value="<?= $aut['id'] ?>"><?= $aut['usuario'] ?></option>
            <?php
        }
        ?>
    </select>
    
    <label class="">Coordinador dece:</label>
    <select name="coordinador_dece_id" class="form-control" required="">
        <option value="<?= $model->coordinador_dece_id ?>"><?= $mCoordD->usuario0->usuario ?></option>
        <?php
        foreach ($modelAutoridades as $aut) {
            ?>
            <option value="<?= $aut['id'] ?>"><?= $aut['usuario'] ?></option>
            <?php
        }
        ?>
    </select>
    
    <label class="">Secretaria:</label>
    <select name="secretaria_id" class="form-control" required="">
        <option value="<?= $model->secretaria_id ?>"><?= $mSecret->usuario0->usuario ?></option>
        <?php
        foreach ($modelAutoridades as $aut) {
            ?>
            <option value="<?= $aut['id'] ?>"><?= $aut['usuario'] ?></option>
            <?php
        }
        ?>
    </select>
    
    <label class="">Coordinador académico:</label>
    <select name="coordinador_academico_id" class="form-control" required="">
        <option value="<?= $model->coordinador_academico_id ?>"><?= $mCoordA->usuario0->usuario ?></option>
        <?php
        foreach ($modelAutoridades as $aut) {
            ?>
            <option value="<?= $aut['id'] ?>"><?= $aut['usuario'] ?></option>
            <?php
        }
        ?>
    </select>
    
    <label class="">Inspector:</label>
    <select name="inspector_id" class="form-control" required="">
        <option value="<?= $model->inspector_id ?>"><?= $mInspec->usuario0->usuario ?></option>
        <?php
        foreach ($modelAutoridades as $aut) {
            ?>
            <option value="<?= $aut['id'] ?>"><?= $aut['usuario'] ?></option>
            <?php
        }
        ?>
    </select>
    
    <label class="">Dece DHI:</label>
    <select name="dece_dhi_id" class="form-control" required="">
        <option value="<?= $model->dece_dhi_id ?>"><?= $mDeceDh->usuario0->usuario ?></option>
        <?php
        foreach ($modelAutoridades as $aut) {
            ?>
            <option value="<?= $aut['id'] ?>"><?= $aut['usuario'] ?></option>
            <?php
        }
        ?>
    </select>
    
    <label class="">Tutor:</label>    
    <select name="tutor_id" class="form-control" required="">        
        <option value="<?= $model->tutor_id ?>"><?= $mTutorI->usuario0->usuario ?></option>
        <?php
        foreach ($modelAutoridades as $aut) {
            ?>
            <option value="<?= $aut['id'] ?>"><?= $aut['usuario'] ?></option>
            <?php
        }
        ?>
    </select>
    
    <label class="">Es activo:</label>
    <select name="es_activo" class="form-control" required="">
        <option value="<?= $model->es_activo ?>"><?= ($model->es_activo) == 1 ? 'SI' : 'NO';  ?></option>
        <option value="1">SI</option>
        <option value="0">NO</option>
    </select>
    

    <br>
    <?= Html::submitButton('Actualizar', ['class' => 'btn btn-outline-warning']) ?>
    <?= Html::endForm() ?>
</div>