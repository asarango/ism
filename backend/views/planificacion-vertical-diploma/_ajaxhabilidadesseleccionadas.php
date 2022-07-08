<ul>
    <?php
    foreach ($habilidades as $habilidad) {
        ?>
            <li><i class="far fa-check-square" style="color: green"></i> <?= $habilidad['nombre'] ?></li>
        <?php
    }
    ?>
</ul>