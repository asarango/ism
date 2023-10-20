<?php

namespace backend\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\View;

class EnviaAplicacionController extends \yii\web\Controller
{
    public function actionIndex1()
    {
        $application = $_GET['app'];
        // $urlExterna = 'https://www.google.com';
        // // Genera la URL externa
        // $urlGenerada = Url::to($urlExterna, true);

        // // Redirige al usuario a la URL externa
        // Yii::$app->getResponse()->redirect($urlGenerada)->send();
        // Yii::$app->end();

        $urlExterna = 'https://www.google.com/search?q=autos&sca_esv=568200277&sxsrf=AM9HkKkS_dYbrDSEqYnX23FFAY0vtP4yvg%3A1695651968336&source=hp&ei=gJgRZf2XEu3ykPIPn7m58As&iflsig=AO6bgOgAAAAAZRGmkDtqSK7d0J1UvrPKUyO-lE6eeIke&ved=0ahUKEwj91-7p-8WBAxVtOUQIHZ9cDr4Q4dUDCAg&uact=5&oq=autos&gs_lp=Egdnd3Mtd2l6IgVhdXRvczIFEAAYgAQyBRAAGIAEMggQABiABBixAzILEC4YgAQYxwEYrwEyCBAAGIAEGLEDMgUQABiABDIFEAAYgAQyCxAAGIAEGLEDGIMBMgUQABiABDILEAAYgAQYsQMYgwFIjilQqwxYqCdwBXgAkAEAmAGyAaABkwqqAQMwLjm4AQPIAQD4AQGoAgrCAgcQIxjqAhgnwgIEECMYJ8ICBxAjGIoFGCfCAggQLhiABBixA8ICBRAuGIAEwgIOEC4YgAQYsQMYxwEYrwHCAgcQABiABBgKwgIHEC4YgAQYCsICDRAuGIAEGMcBGNEDGArCAhEQLhiABBixAxiDARjHARjRA8ICDhAuGIAEGLEDGMcBGNEDwgILEC4YigUYsQMYgwHCAgsQLhiABBixAxiDAcICBBAAGAPCAgsQABiKBRixAxiDAQ&sclient=gws-wiz'; // Reemplaza esto con la URL externa que desees redireccionar

        // Renderizar una vista que ejecute JavaScript para abrir la URL en una nueva pestaña
        return $this->render('redireccionar', ['urlExterna' => $urlExterna]);
    }

}
