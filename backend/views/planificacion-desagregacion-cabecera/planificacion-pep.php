<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<p>
<h3><img src="ISM/main/images/submenu/programa-primaria.png" alt="bi-primaria"/>
    <b>PLANIFICACIÓN PEP</b></h3>
</p>
<div class="row">
    <?php
    foreach ($cursos as $curso) {
        ?>


        <div class="card mb-3" style="max-width: 540px; margin: 10px">
            <div class="row">
                <div class="col-md-3 text-center" 
                     style="background: linear-gradient(90deg, rgba(252,219,169,1) 0%, 
                                rgba(238,232,112,1) 100%, 
                                rgba(13,19,204,0) 100%); 
                                color: #0a1f8f;
                                display: flex;    
                                justify-content: center;                                 
                                flex-direction: column;
                                 ">
                    <div>
                        <h1>100%</h1>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="card-body">
                        <h5 class="card-title"><?= $curso['name'] ?></h5>
                        <p class="card-text">Sistema de planificaciones Transdisciplinar, orientado a sección PEP de la institución</p>
                        <p class="card-text">
                            <?= Html::a('<i class="fas fa-cogs zoom" style="color: #0a1f8f;"> Planificar</i>',[
                                'pep-planificacion/index1',
                                'op_course_template_id' => $curso['id']
                            ]) ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>                   




        <?php
    }
    ?>
</div>

