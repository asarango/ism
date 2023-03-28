<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="card">
    <div class="card-header">
        <h6 id=""><b>9.	Bibliografía/Webgrafía. Utilizar normas APA (última edición) </b></h6>        
    </div>
    <div class="card-body">
        
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <form-group>
                    <?php $data = search_data_text($plan, 'datos', 'bibliografia'); ?>
                    <textarea class="form-control text-cerrado " id="div-bibliografia" 
                            onchange="update_text(<?= $data['id'] ?>, 'div-bibliografia');" 
                            name="div-bibliografia"><?= $data['contenido']; ?></textarea>
                </form-group>

            </div>

        </div>
    </div>
</div>