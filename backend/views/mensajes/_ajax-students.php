<?php use yii\helpers\Html; ?>
<?php
    foreach($students as $g){
        echo '<p>';
        echo Html::a('<i class="fas fa-users" style="color: #65b2e8"> '.$g['student'].' | '.$g['x_institutional_email'].'</i>', ['acciones',
            'message_header_id' => $messageId,
            'tipo_busqueda' => 'grabar_user',
            'word' => 'none',
            'user_id' => $g['x_institutional_email']
        ]);
        echo '</p>';
    }
?>