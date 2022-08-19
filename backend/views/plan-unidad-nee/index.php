<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\PlanUnidadNee;
use backend\models\CurriculoMecBloque;
use backend\models\NeeXClase;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanUnidadNeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Plan Unidad - Necesidades Especiales';
$this->params['breadcrumbs'][] = $this->title;
$model = new PlanUnidadNee();

$idBloque1 = CurriculoMecBloque::find()
->where(['last_name'=>'BLOQUE 1'])
->one();
$idBloque2 = CurriculoMecBloque::find()
->where(['last_name'=>'BLOQUE 2'])
->one();
$idBloque3 = CurriculoMecBloque::find()
->where(['last_name'=>'BLOQUE 3'])
->one();
$idBloque4 = CurriculoMecBloque::find()
->where(['last_name'=>'BLOQUE 4'])
->one();
$idBloque5 = CurriculoMecBloque::find()
->where(['last_name'=>'BLOQUE 5'])
->one();
$idBloque6 = CurriculoMecBloque::find()
->where(['last_name'=>'BLOQUE 6'])
->one();

?>
<div class="materias-pai-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8">
            <div class="plan-unidad-nee-index">
                <div class=" row align-items-center p-2">
                    <div class="col-lg-1">
                        <h4><img src="ISM/main/images/submenu/libros.png" width="64px" class="img-thumbnail"></h4>
                    </div>
                    <div class="col-lg-11">
                        <h4><?= Html::encode($this->title) ?></h4>
                    </div>
                </div><!-- FIN DE CABECERA -->

                <?php // echo $this->render('_search', ['model' => $searchModel]); 
                ?>
                <div class="row ">
                    <hr>
                    <span style="color:#0a1f8f"> <b>PROFESOR:</b> <?= $nombreProfesor; ?> </span>
                    <hr>
                </div>
                <div class="row">
                    <div class="card shadow row-lg-6 row-md-6">
                        <table class="table  table-striped table-hover" style="font-size:10px">
                            <tr>
                                <td><b>Matéria</b></td>
                                <td><b>Curso</b></td>
                                <td><b>Paralelo</b></td>
                                <td><b>Sección</b></td>
                            </tr>
                            <?php
                            foreach ($modelCursos as $curso) {
                                $modelNeeClase = NeeXClase::find()
                                    ->where(['clase_id' => $curso['clase_id']])
                                    ->all();
                            ?>
                                <tr>
                                    <td style="color:red; font-size:12px;"><?= $curso['materia'] ?></td>
                                    <td style="color:red; font-size:12px;"><?= $curso['curso'] ?></td>
                                    <td style="color:red; font-size:12px;"><?= $curso['paralelo'] ?></td>
                                    <td style="color:red; font-size:12px;"><?= $curso['code'] ?></td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <div id="div-alumnos" class="card shadow row-lg-6 row-md-6">
                                            <table class="table table-striped table-hover" style="font-size:10px">
                                                <tr>
                                                    <td><b>Nombre</b></td>
                                                    <td><b>Grado</b></td>
                                                    <td><b>Diagnóstico</b></td>
                                                    <td><b>Recomendación</b></td>
                                                    <td><b>B1</b></td>
                                                    <td><b>B2</b></td>
                                                    <td><b>B3</b></td>
                                                    <td><b>B4</b></td>
                                                    <?php
                                                    if($curso['code']=='PEP' )
                                                    {
                                                    ?>
                                                        <td><b>B5</b></td>
                                                        <td><b>B6</b></td>
                                                    <?php
                                                       }
                                                    ?>

                                                </tr>
                                                <?php
                                                foreach ($modelNeeClase as $nee) {
                                                    $nombreAlumnno = $nee->nee->student->last_name . ' ' . $nee->nee->student->middle_name . ' ' . $nee->nee->student->first_name;
                                                ?>

                                                    <tr>
                                                        <td><?= $nombreAlumnno ?></td>
                                                        <td><?= $nee['grado_nee'] ?></td>
                                                        <td><?= $nee['diagnostico_inicia'] ?></td>
                                                        <td><?= $nee['recomendacion_clase'] ?></td>
                                                        <td> <?= Html::a(
                                                                    '<span class="badge  rounded-pill" style="background-color:#9e28b5 ;">
                                                                <i class="fas fa-eye"></i>
                                                            </span>',
                                                                    ['llamar-form', 'idNeeXCase' => $nee['id'],'idBloque'=>$idBloque1->id ],
                                                                    ['class' => 'link']
                                                                ); ?>
                                                        </td>
                                                        <td> <?= Html::a(
                                                                    '<span class="badge  rounded-pill" style="background-color:#9e28b5;">
                                                                <i class="fas fa-eye"></i>
                                                            </span>',
                                                                     ['llamar-form', 'idNeeXCase' => $nee['id'],'idBloque'=>$idBloque2->id ],
                                                                    ['class' => 'link']
                                                                ); ?>
                                                        </td>
                                                        <td> <?= Html::a(
                                                                    '<span class="badge  rounded-pill" style="background-color:#9e28b5;">
                                                                    <i class="fas fa-eye"></i>
                                                            </span>',
                                                                     ['llamar-form', 'idNeeXCase' => $nee['id'],'idBloque'=>$idBloque3->id ],
                                                                    ['class' => 'link']
                                                                ); ?>
                                                        </td>
                                                        <td> <?= Html::a(
                                                                    '<span class="badge  rounded-pill" style="background-color:#9e28b5;">
                                                                    <i class="fas fa-eye"></i>->id
                                                            </span>',
                                                                     ['llamar-form', 'idNeeXCase' => $nee['id'],'idBloque'=>$idBloque4->id ],
                                                                    ['class' => 'link']
                                                                ); ?>
                                                        </td>
                                                        <?php
                                                        if($curso['code']=='PEP' || $curso['code']=='KIDS' )
                                                        {
                                                        ?>
                                                            <td>
                                                                    <?= Html::a(
                                                                            '<span class="badge  rounded-pill" style="background-color:#9e28b5;">
                                                                            <i class="fas fa-eye"></i>
                                                                    </span>',
                                                                             ['llamar-form', 'idNeeXCase' => $nee['id'],'idBloque'=>$idBloque5->id ],
                                                                            ['class' => 'link']
                                                                        ); ?>
                                                            </td>
                                                            <td>
                                                                    <?= Html::a(
                                                                            '<span class="badge  rounded-pill" style="background-color:#9e28b5;">
                                                                            <i class="fas fa-eye"></i>
                                                                    </span>',
                                                                             ['llamar-form', 'idNeeXCase' => $nee['id'],'idBloque'=>$idBloque6->id ],
                                                                            ['class' => 'link']
                                                                        ); ?>
                                                            </td>
                                                        <?php
                                                        }
                                                        ?>
                                                    </tr>
                                                <?php } ?>
                                            </table>
                                        </div>

                                    </td>
                                </tr>
                            <?php
                            }
                            ?>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>