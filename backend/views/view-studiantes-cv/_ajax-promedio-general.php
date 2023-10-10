<!-- <php echo "<pre>"; print_r($promedios[0]); die();?> -->
<!-- <style>
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
        color: black;
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
</style> -->



<div class="row" style="color: black;">

    <div class="col-md-3 col-sm-3">
        <div class="card shadow">
            <?= $promedios[0]['bloque'] ?>
            <br>
            Promedio: <?= $promedios[0]['nota'] ?>
        </div>
    </div>
    <div class="col-md-3 col-sm-3">
        <div class="card shadow">
            <?= $promedios[1]['bloque'] ?>
            <br>
            Promedio: <?= $promedios[1]['nota'] ?>
        </div>
    </div>
    <div class="col-md-3 col-sm-3">
        <div class="card shadow">
            <?= $promedios[2]['bloque'] ?>
            <br>
            Promedio: <?= $promedios[2]['nota'] ?>
        </div>
    </div>
    <div class="col-md-3 col-sm-3">
        <div class="card shadow">
            Promedio general:
            <?= $promedios[3]['general'] ?>
        </div>
    </div>

</div>