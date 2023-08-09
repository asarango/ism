<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisParametrosOpcionesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Configuración Inicial';
$this->params['breadcrumbs'][] = $this->title;

// echo"<pre>";
// print_r($menu);
// die();
?>

<style>
    .btn-outline-secondary {
        border-color: #f50057;
        color: #f50057;
    }

    .btn-outline-secondary:hover {
        background-color: #f50057;
        color: #fff;
    }

    .list-group-item {
        background-color: #ff4081;
        color: #fff;
        border-color: #ff4081;
        overflow: hidden;
    }

    .list-group-item:hover {
        background-color: #ff669a;
        color: #fff;
        border-color: #ff669a;
    }

    /* Estilo para el efecto hover y cristalizado de las tarjetas */
    .card {
        position: relative;
        transition: all 0.3s ease;
        font-size: 16px;
        color: #fff;
    }

    .card:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.2);
        z-index: -1;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .card:hover::before {
        opacity: 1;
    }

    .capa {
        position: absolute;
        top: 50px;
        left: 50px;
        width: 100%;
        height: 85%;
        overflow-y: scroll;
        /* background-color: #1c1c1d; */
        opacity: 0.9;

    }

    .card-advertencia {
        position: absolute;
        top: 10px;
        right: 10px;
    }

    .card.diferente {
        animation: breathe .5s ease-in-out infinite;
        border: 1px solid transparent;
        box-shadow: 0 0 10px #ff2825;
        transition: border 0.5s ease-in-out;
    }

    .card-exclamation {
        box-shadow: 0 0 10px red;
    }

    .card.diferente:hover {
        border-color: #ff2825;
    }

    @keyframes breathe {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }
    }
</style>

<div class="planificacion-semanal-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10 col-sm-10 bg-white">
            <div class="row align-items-center p-2">
                <div class="col-lg-1 col-md-1">
                    <h4><img src="../ISM/main/images/submenu/plan.png" width="64px" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11 col-md-11">
                    <h1 style="color: black;">
                        <?= Html::encode($this->title) ?>
                    </h1>
                </div>
                <hr>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
                <?php foreach ($menu as $item): ?>
                    <div class="col mb-4">
                        <a href="<?= Url::to(['sincronizar-tabla', 'table' => $item['tabla']]) ?>" class="card-link">
                            <div class="card <?= ($item['total_edux'] != $item['total_odoo'] && $item['total_odoo'] > $item['total_edux']) ? 'diferente' : '' ?>"
                                style="background-color: #ccc">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?= $item['tabla'] ?>
                                    </h5>
                                    <p class="card-text">Orden:
                                        <?= $item['orden'] ?>
                                    </p>
                                    <p class="card-text">Total Odoo:
                                        <?= $item['total_odoo'] ?>
                                    </p>
                                    <p class="card-text">Total Edux:
                                        <?= $item['total_edux'] ?>
                                        <?php if ($item['total_edux'] != $item['total_odoo'] && $item['total_odoo'] > $item['total_edux']): ?>
                                            <span class="card-advertencia" style="animation-delay: <?= rand(0, 2000) ?>ms"><?= advertencia() ?></span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="col-lg-4">
                <!-- Sección de Próximas Actividades -->
            </div>
        </div>
    </div>
</div>


<script>
    // Lista de colores juveniles predefinidos
    const colors = ['#F44336', '#2196F3', '#4CAF50', '#FF9800', '#9C27B0', '#FFC107', '#03A9F4', '#E91E63'];

    // Función para obtener un color aleatorio de la lista
    function getRandomColor() {
        const randomIndex = Math.floor(Math.random() * colors.length);
        return colors[randomIndex];
    }

    // Asignar colores aleatorios a las tarjetas
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        const randomColor = getRandomColor();
        card.style.backgroundColor = randomColor;
    });
</script>

<?php
function advertencia()
{
    return '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-exclamation-circle" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
    <path d="M12 9v4" />
    <path d="M12 16v.01" />
  </svg>';
}
?>