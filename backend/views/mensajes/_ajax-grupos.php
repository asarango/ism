<?php use yii\helpers\Html; ?>
<?php

    foreach($groups as $g){
        echo '<p>';
        echo Html::a('<i class="fas fa-users" style="color: #65b2e8"> '.$g['nombre'].'</i>', ['acciones',
            'message_header_id' => $messageId,
            'tipo_busqueda' => 'grabar_grupo',
            'word' => 'none',
            'grupo_id' => $g['id'],
            'tipo' => $g['tipo']
        ]);
        echo '</p>';
    }
?>