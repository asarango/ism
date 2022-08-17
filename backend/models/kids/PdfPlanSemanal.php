<?php

namespace backend\models\kids;

use backend\models\KidsPlanSemanal;
use backend\models\KidsUnidadMicro;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;

use backend\models\pca\Pca;
use backend\models\ResUsers;

class PdfPlanSemanal extends \yii\db\ActiveRecord
{

    private $planSemanalId;
    private $planSemanal;
    private $user;
    // public $html;

    public function __construct($planSemanalId)
    {
        $this->planSemanalId = $planSemanalId;
        $this->planSemanal = KidsPlanSemanal::findOne($planSemanalId);
        $this->user = ResUsers::find()->where(['login' => Yii::$app->user->identity->usuario])->one();
        $this->genera_pfd();
    }

    private function genera_pfd()
    {

        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 20,
            'margin_bottom' => 0,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);


        $cabecera = $this->cabecera();
        $pie = '<h4>Genera Pie</h4>';

        $mpdf->SetHtmlHeader($cabecera);
        $mpdf->showImageErrors = true;


        $html = $this->cuerpo();

        $mpdf->WriteHTML($html);
        $mpdf->SetFooter($pie);

        $mpdf->Output('Libreta' . "curso" . '.pdf', 'D');
        exit;
    }

    private function estilos()
    {
        $html = '';
        $html .= '<style>';
        $html .= '.border {
                    border: 0.1px solid black;
                  }
                  
                  .centrarTexto {
                    text-align: center;
                  }
                  .derechaTexto {
                    text-align: right;
                  }
                  
                  .tamano6{
                    font-size: 6px;
                  }
                  
                  .tamano8{
                    font-size: 9px;
                  }
                  
                .tamano10{
                    font-size: 10px;
                 }
                 
                 .paddingTd{
                    padding: 2px;
                }
                
                .colorPlomo{
                    background-color:#c9cfcb;
                }
                
                .colorFinal{
                    background-color:#8ccaa0;
                }

                .colorAyudas{
                    color: #65b2e8;
                }

                    ';
        $html .= '</style>';
        return $html;
    }


    private function cabecera()
    {
        $html = '';
        $html .= '<table width="100%" cellspacing="0" cellpadding="10">';
        $html .= '<tr>';
        $html .= '<td class="" align="center" width="20%"><img src="imagenes/instituto/logo/logo2.png" width="60px"></td>';
        $html .= '<td class="" align="center" width=""></td>';
        $html .= '<td class="tamano8" align="right" width="20%">
                    Código: ISMR20-41 <br>
                    Versión: 2.0<br>
                    Fecha: 28/09/021<br>
                    Página: {PAGENO} / {nb}<br>
                  </td>';
        $html .= '</tr>';
        $html .= '</table>';
        return $html;
    }


    private function cuerpo()
    {
        $periodoId = Yii::$app->user->identity->periodo_id;
        $periodo = \backend\models\ScholarisPeriodo::findOne($periodoId);
        $mes = $this->toma_mes();

        $html = $this->estilos();

        $html .= '<table width="100%" cellspacing="0" cellpadding="10">';
        $html .= '<tr>';
        $html .= '<td class="border" align="center" colspan="5"><b>ISM</b> <br> International Scholastic Model</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="border" align="center" colspan="5"><b>PLAN SEMANAL INICIAL Y PREPARATORIA</b> <br> AÑO ESCOLAR ' . $periodo->codigo . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="border" style="background-color: #eee"><b>PROFESOR</b></td>';
        $html .= '<td class="border" style="background-color: #eee"><b>NIVEL</b></td>';
        $html .= '<td class="border" style="background-color: #eee"><b>MES</b></td>';
        $html .= '<td class="border" style="background-color: #eee"><b>EXPERIENCIA DE APRENDIZAJE</b></td>';
        $html .= '<td class="border" style="background-color: #eee"><b>SEMANA #</b></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="border">' . $this->user->partner->name . '</td>';
        $html .= '<td class="border">Preparatoria - Iniciales</td>';
        $html .= '<td class="border">'.$mes.'</td>';
        $html .= '<td class="border">'.$this->planSemanal->kidsUnidadMicro->experiencia.'</td>';
        $html .= '<td class="border">'.$this->planSemanal->semana->semana_numero.'</td>';
        $html .= '</tr>';
        $html .= '</table>';

        $html .= $this->uno();
        // $html .= $this->dos();
        // $html .= $this->tres();
        // $html .= $this->cuatro();
        // $html .= $this->cinco();
        // $html .= $this->seis();
        // $html .= $this->siete();
        $html .= $this->firmas();

        return $html;
    }

    private function toma_mes()
    {
        $fechaComoEntero = strtotime($this->planSemanal->semana->fecha_inicio);
        $mesNumero = date("m", $fechaComoEntero);
        switch ($mesNumero) {
            case 0:
                $mes = "Enero";
                break;
            case 2:
                $mes = "Febrero";
                break;
            case 3:
                $mes = "Marzo";
                break;
            case 4:
                $mes = "Abril";
                break;
            case 5:
                $mes = "Mayo";
                break;
            case 6:
                $mes = "Junio";
                break;
            case 7:
                $mes = "Julio";
                break;
            case 8:
                $mes = "Agosto";
                break;
            case 9:
                $mes = "Septiembre";
                break;
            case 10:
                $mes = "Octubre";
                break;
            case 11:
                $mes = "Noviembre";
                break;
            case 12:
                $mes = "Diciembre";
                break;
        }
        return $mes;
    }

    private function uno()
    {

        $html = '';
        $html .= '<table width="100%" cellspacing="0" cellpadding="10" style="margin-top: 15px;">';
        $html .= '<tr>';
        $html .= '<td width="" class="border centrarTexto" style="background-color: #eee"><b>FECHA</b></td>';
        $html .= '<td width="" class="border centrarTexto" style="background-color: #eee"><b>HORA</b></td>';
        $html .= '<td width="" class="border centrarTexto" style="background-color: #eee"><b>DESTREZAS</b></td>';
        $html .= '<td width="" class="border centrarTexto" style="background-color: #eee"><b>ACTIVIDADES</b></td>';
        $html .= '<td width="" class="border centrarTexto" style="background-color: #eee"><b>TAREAS / EVALUACIÓN</b></td>';

        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }




    public function firmas()
    {

        $html = '';
        $html .= '<br>';
        $html .= '<table width="100%" cellspacing="0" cellpadding="10">';

        $html .= '<tr>';
        $html .= '<td align="center" class="border" colspan="1" style="background-color: #eee;" width="50%"><b>ELABORADO POR</b></td>';
        $html .= '<td align="center" class="border" colspan="1" style="background-color: #eee" width="50%"><b>REVISADO Y APROBADO POR COORDINACIÓN</b></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border">DOCENTES</td>';
        $html .= '<td class="border">NOMBRE</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border">FIRMA</td>';
        $html .= '<td class="border">FIRMA</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border">FECHA</td>';
        $html .= '<td class="border">FECHA</td>';
        $html .= '</tr>';

        $html .= '</table>';

        return $html;
    }
}
