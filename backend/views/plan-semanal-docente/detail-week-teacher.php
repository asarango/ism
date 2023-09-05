<?php

use yii\helpers\Url;

?>
<iframe width="100%" height="600" src="<?= Url::toRoute([
                                            'acciones',
                                            'action' => 'pdf',
                                            'week_id' => $weekId
                                        ]) ?>">
</iframe>