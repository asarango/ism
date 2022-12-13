<?php

use backend\models\PlanificacionOpciones;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ScholarisAsistenciaProfesor */

$this->title = 'Reporte Power BI';
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Asistencia Profesors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

//Link reporte power bi NOTAS PROFESOR
$modelPlanOpciones = PlanificacionOpciones::find()
->where(['tipo'=>'REPORTE POWER BI','categoria'=>'NOTAS_PROFESOR'])
->one();
//configuracion URL
$filterPowerBi="&filter=view_reporte_notas_profesor/usuario_docente eq '$usuario'";
$linkReporte = $modelPlanOpciones->opcion.$filterPowerBi;
?>
<div class="scholaris-asistencia-profesor-reporte">    
    <div class="card" >
        <div class="card-header ">
            <div class="row">
                <div class="col-lg-2 col-md-2">
                    <h4><img src="ISM/main/images/submenu/diagrama.png" width="50px" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-5 col-md-5">
                    <h4><?= Html::encode("$this->title") ?></h4>
                </div>
            </div>
        </div>
        <div class="card-body ">
            <iframe title="ejemplo" width="1450" height="800" 
                src="<?=$linkReporte?>"
                frameborder="0" allowFullScreen="true" scrolling="auto">
            </iframe>
        </div>
    </div>

</div>