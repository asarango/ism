<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanificacionSemanalRecursos */

$this->title = 'Recurso #' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Planificacion Semanal Recursos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

// echo"<pre>";
// print_r($model);
// die();
?>
<div class="planificacion-semanal-recursos-view">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class="row align-items-center p-2">
                <div class="col-lg-1 col-md-1">
                    <h4><img src="../ISM/main/images/submenu/plan.png" width="64px" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-8 col-md-8">
                    <h1>
                        <?= Html::encode($this->title) ?>
                    </h1>
                    <p>

                    </p>
                </div>
                <!-- INICIO BOTONES DERECHA -->
                <div class="col-lg-3 col-md-3" style="text-align: right;">
                    <?=
                        Html::a(
                            '<span class="badge rounded-pill" style="background-color: #ff9e18">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-home-up"
                            width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" 
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M9 21v-6a2 2 0 0 1 2 -2h2c.641 0 1.212 .302 1.578 .771" />
                            <path d="M20.136 11.136l-8.136 -8.136l-9 9h2v7a2 2 0 0 0 2 2h6.344" />
                            <path d="M19 22v-6" />
                            <path d="M22 19l-3 -3l-3 3" />
                            </svg> Regresar</span>',
                            ['planificacion-semanal-recursos/index', 'id' => $model->plan_semanal_id],
                            ['class' => '', 'title' => 'Regresar']
                        );
                    ?>
                    <!-- FIN BOTONES DERECHA -->
                </div>
                <hr>
            </div>
            <div class="row">
                <div>
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            // 'id',
                            // 'plan_semanal_id',
                            'tema',
                            'tipo_recurso',
                            'url_recurso:url',
                            'estado:boolean',
                        ],
                    ]) ?>
                </div>

                <div style="margin-top: 0.5rem;margin-bottom: 1rem;">
                    <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

</div>