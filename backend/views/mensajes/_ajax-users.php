<?php use yii\helpers\Html; ?>
<?php
    foreach($users as $g){
        echo '<p>';
        echo Html::a('<i class="fas fa-users" style="color: #65b2e8"> '.$g['name'].' | '.$g['usuario'].'</i>', ['acciones',
            'message_header_id' => $messageId,
            'tipo_busqueda' => 'grabar_user',
            'word' => 'none',
            'user_id' => $g['usuario']
        ]);
        echo '</p>';
    }
?>