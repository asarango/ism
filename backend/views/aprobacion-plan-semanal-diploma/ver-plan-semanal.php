<?php

use backend\models\diploma\PdfPlanSemanaDocente;
use backend\models\PlanSemanalBitacora;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;


$this->title = 'Verificador de plan Semanal';


// echo "<pre>";
// print_r($tempPdfPath);
// die();

// Genera el PDF usando la clase PdfPlanSemanaDocente
$pdf = new PdfPlanSemanaDocente($semanaId, $user, $periodo);
$pdfContent = $pdf->render();

// Guarda el contenido del PDF en un archivo temporal
$tempPdfPath = tempnam(sys_get_temp_dir(), 'pdf');
file_put_contents($tempPdfPath, $pdfContent);
?>



