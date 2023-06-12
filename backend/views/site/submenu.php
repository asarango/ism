<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanPlanificacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Opciones del Menú  ' . $menu->nombre;
$pdfTitle = $this->title;
$this->params['breadcrumbs'][] = $this->title;

// echo"<pre>";
// print_r($secciones);
// die();
// $totalSecciones=buscar_secciones($secciones);
// print_r($submenu);
?>
<div class="secretaria-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="card shadow col-lg-8 p-5">

            <h4>
                <?= Html::encode($this->title) ?>
            </h4>
            <hr>

            <div class="row">
                <?php
                foreach ($submenu as $sub) {
                    if ($sub['ruta_icono']) {
                        $photo = $sub['ruta_icono'];
                    } else {
                        $photo = 'no-photo.png';
                    }

                    $operacion = $sub['operacion'];
                    $ruta = str_replace("-index", "/index", $operacion);
                    ?>

                    <div class="col-lg-3 col-md-3 text-center">
                        <div class="text-center animate__animated animate__fadeInRight">
                            <img src="../ISM/main/images/submenu/<?= $photo ?>" width="60%" class=" img-thumbnail"
                                alt="<?= $photo ?>" style="align-items: center">
                            <div class="card-body">

                                <?= Html::a($sub['nombre'], ['/' . $ruta], ['class' => 'link']) ?>

                                <!--<a href="#" class="link"><?= $sub['nombre'] ?></a>-->
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>



</div>
<?php
function buscar_secciones($secciones){
    $contadorDiploma=0;
    $contadorPai=0;
    $contadorPres=0;
    $contadorPep=0;
    foreach($secciones as $seccion);{
        // print_r($seccion);
        if($seccion['code'] == 'DIPL'){
            $contadorDiploma++;       
        }
        if($seccion['code'] == 'PAI'){
            $contadorPai++;
        }
        if($seccion['code'] == 'PRES'){
            $contadorPres++;
        }
        if($seccion['code'] == 'PEP'){
            $contadorPep++;
        }
    }   
    $arregloRespuesta=array(
        'dipl' => $contadorDiploma,
        'pai' => $contadorPai,
        'pres' => $contadorPres,
        'pep' => $contadorPep
    );
    return $arregloRespuesta;
} 
?>
