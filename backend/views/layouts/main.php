<?php
/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use yii\helpers\Url;
use backend\models\Menu;

AppAsset::register($this);



$iden = Yii::$app->user->identity;

if (!Yii::$app->user->isGuest) {



    $usuario = Yii::$app->user->identity->usuario;
    $modelUser = backend\models\ResUsers::find()->where(['login' => $usuario])->one();
    $modelUserEducandi = backend\models\Usuario::find()->where(['usuario' => $usuario])->one();

    $periodId = Yii::$app->user->identity->periodo_id;
    $period = \backend\models\ScholarisPeriodo::findOne($periodId);

    //    print_r($periodId);
    //die();
}
?>
<?php $this->beginPage() ?>


<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
    <!--        
        Libreria para arbol
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />-->

    <!--Librerias css propias -->
    <link rel="stylesheet" href="../ISM/main/hamburger/style.css">
    <link rel="stylesheet" href="../ISM/main/menuvertical/style.css">
    <link rel="stylesheet" href="../css/ismStyles.css" />
    <link rel="stylesheet" href="../css/coloresPersonalizados.css" />
    <script src="../ISM/main/signature/signature_pad.js"></script>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php // $this->head()  
    ?>
</head>

<body class="animate__animated animate__fadeIn" style="background: #eee; font-size:14px; font-family: sans-serif; color: #898b8d">
    <?php $this->beginBody() ?>


    <header>
        <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #ab0a3d; color: white;">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">ISM</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Menú
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">

                                <?php
                                if (!Yii::$app->user->isGuest) {
                                    $usuario = Yii::$app->user->identity->usuario;
                                    $menusModel = new Menu();
                                    $menus = $menusModel->getMenus($usuario);

                                    foreach ($menus as $data) {
                                        $menuId = $data['id'];
                                        isset($data['icono']) ? $icono = $data['icono'] : $icono = '<i class="fas fa-laptop-code"></i>';

                                        echo Html::a(
                                            '<li>' . $icono . ' ' . $data['nombre'] . '</li>',
                                            ['site/get-sub-menu', 'menu_id' => $data['id']],
                                            ['class' => 'dropdown-item']
                                        );
                                        // echo '<hr>';
                                ?>


                                <?php
                                    }
                                }
                                ?>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <?=
                            Html::a(
                                'Inicio',
                                ['site/index'],
                                ['class' => 'nav-link']
                            );
                            ?>

                        </li>

                        <li class="nav-item">
                            <?=
                            Html::a(
                                'Cambiar clave',
                                ['profesor-inicio/cambiarclave'],
                                ['class' => 'nav-link']
                            );
                            ?>

                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="#">Link</a>
                        </li> -->


                    </ul>
                    <!-- <form class="d-flex"> -->
                    <div id="google_translate_element" class="google" style="margin-right: 10px;"></div>
                    <div>                    

                        <?php

                        $totalMessages = get_messages($usuario);

                        if ($totalMessages == 0) {
                            echo Html::a('<i class="fas fa-envelope-open"></i>', ['mensajes/received']);
                        } else {
                            echo Html::a('<i class="fas fa-envelope" style="color: #ff9e18"> ' . $totalMessages . '</i>', ['mensajes/received']);
                        }


                        echo Html::a("SALIR ( $usuario )", ['/site/logout'], ['data' => [
                            'method' => 'post',
                            'params' => ['derp' => 'herp'], // <- extra level
                        ], 'class' => 'nav__link'])
                        ?>
                    </div>
                    <!-- </form> -->
                </div>
            </div>
        </nav>
    </header>


    <div class="container-fluid">

        <!-- inicio modal de mensajes -->
        <?php
        if ($totalMessages > 0) {
        ?>
            <!-- inicio de Modal -->
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" 
                                id="staticBackdropLabel"
                                style="color: #9e28b5">
                                Tienes notificaciones sin leer
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center" style="text-align: center;">
                            <div style="margin: 0px auto; 
                                   
                                    height: 100%;
                                    width: 28%;
                                    font-size: 60px;">
                                <p style="color: #898b8d;"><?= $totalMessages ?></p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <?=
                                Html::a('Ver notificaciones',['mensajes/received'],['class' => 'btn btn-primary']);
                            ?>                            
                        </div>
                    </div>
                </div>
            </div>
            <!-- fin de modal -->
        <?php
        }
        ?>
        <!-- fin modal de mensajes -->

        <section id="section-content" style="display: none;">
            <div class="container-fluid m-2">
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </section>


    </div>

    <div class="m-5"></div>
    <br>
    <br>
    <br>
    <br>

    <footer class="fixed-bottom">

        <div class="logo">
            <img src="../ISM/main/assets/img/ism.jpg" class="" alt="">
            Designed and built with all the love in the world by the ISM team with the help of our contributors.
        </div>

        <div style="font-size: 10px">Iconos diseñados por <a href="https://www.flaticon.es/autores/ariefstudio" title="ariefstudio" target="_blank">ariefstudio</a>
            from <a href="https://www.flaticon.es/" title="Flaticon" target="_blank">www.flaticon.es</a></div>

    </footer>

 

<script type="text/javascript">
    function googleTranslateElementInit() {
        // new google.translate.TranslateElement({pageLanguage: 'es', includedLanguages: 'ca,eu,gl,en,fr,it,pt,de', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, gaTrack: true}, 'google_translate_element');
        new google.translate.TranslateElement({
            pageLanguage: 'es',
            includedLanguages: 'en,fr,es',
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
            gaTrack: true
        }, 'google_translate_element');
    }
</script>

<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="../ISM/main/assets/js/main.js"></script>
    <!--<script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>-->
    <script type="module" src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule="" src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons/ionicons.js"></script>

    <!--para arbol de opciones-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>


    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>


<script>
    $("document").ready(function() {

        $("#section-loading").hide();
        $("#section-content").show();
        $('#staticBackdrop').modal('toggle')
    });


    function showMenu() {
        if ($("#divMenu").is(':visible')) {
            $("#divMenu").hide();
        } else {
            $("#divMenu").show();
        }
        //
    }
</script>


<?php
function consumo_servicio_mensajes()
{
    $userLog = \Yii::$app->user->identity->usuario;

    //consultando web service de academico
    $service = new backend\models\services\WebServicesUrls('academico');
    $dataJson = $service->consumir_servicio($service->url . '/unread/' . $userLog);
    $messages = json_decode($dataJson);
    // fin de proceso web service academico

    return $messages->data;
}


function get_messages($user)
{
    $con = Yii::$app->db;
    $query = "select 	count(id) as total 
    from 	message_para
    where 	para_usuario = '$user'
            and estado = 'enviado';";
    $res = $con->createCommand($query)->queryOne();

    return $res['total'];
}

?>