<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;



class ConfiguracionInicialController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }
        if (Yii::$app->user->identity) {

            //OBTENGO LA OPERACION ACTUAL
            list($controlador, $action) = explode("/", Yii::$app->controller->route);
            $operacion_actual = $controlador . "-" . $action;
            //SI NO TIENE PERMISO EL USUARIO CON LA OPERACION ACTUAL
            if (!Yii::$app->user->identity->tienePermiso($operacion_actual)) {
                echo $this->render('/site/error', [
                    'message' => "Acceso denegado. No puede ingresar a este sitio !!!",
                    'name' => 'Acceso denegado!!',
                ]);
            }
        } else {
            header("Location:" . \yii\helpers\Url::to(['site/login']));
            exit();
        }
        return true;
    }


    public function actionIndex()
    {
        $menuFabricado = $this->fabrica_menu();
        return $this->render('index', [
            'menu' => $menuFabricado
        ]);
    }

    // Metodo que fabrica el menu

    private function fabrica_menu()
    {
        $catalogo = $this->catalogo_menu();
        $menu = array(); // Variable que muta mi catálogo

        foreach ($catalogo as $item) {
            $verificaCambios = $this->consulta_tabla($item['tabla']);
            $item['total_odoo'] = $verificaCambios['total_odoo'];
            $item['total_edux'] = $verificaCambios['total_edux'];


            array_push($menu, $item);
        }

        return $menu;
    }





    // Catalogo de menu
    private function catalogo_menu()
    {
        $catalogo = array(
            array('orden' => 1, 'tabla' => 'res_partner', 'total_odoo' => 0, 'total_edux' => 0),
            array('orden' => 2, 'tabla' => 'res_users', 'total_odoo' => 0, 'total_edux' => 0),
            array('orden' => 3, 'tabla' => 'op_course_template', 'total_odoo' => 0, 'total_edux' => 0),
            array('orden' => 4, 'tabla' => 'op_faculty', 'total_odoo' => 0, 'total_edux' => 0),
            array('orden' => 5, 'tabla' => 'op_student', 'total_odoo' => 0, 'total_edux' => 0),
            array('orden' => 6, 'tabla' => 'op_student_inscription', 'total_odoo' => 0, 'total_edux' => 0),
            array('orden' => 7, 'tabla' => 'op_student_enrollment', 'total_odoo' => 0, 'total_edux' => 0),
            array('orden' => 8, 'tabla' => 'op_parent', 'total_odoo' => 0, 'total_edux' => 0),
           
            array('orden' => 9, 'tabla' => 'op_institute', 'total_odoo' => 0, 'total_edux' => 0),
            array('orden' => 10, 'tabla' => 'op_period', 'total_odoo' => 0, 'total_edux' => 0),
            array('orden' => 11, 'tabla' => 'op_section', 'total_odoo' => 0, 'total_edux' => 0),
            array('orden' => 12, 'tabla' => 'op_course', 'total_odoo' => 0, 'total_edux' => 0),
            array('orden' => 13, 'tabla' => 'op_course_paralelo', 'total_odoo' => 0, 'total_edux' => 0),
            //array('orden' => 14, 'tabla' => 'op_parent_op_student_rel', 'total_odoo' => 0, 'total_edux' => 0),

        );
        return $catalogo;
    }

    private function consulta_tabla($tabla)
    {
        $con = Yii::$app->db;
        $queryEdux = "select  count(id) as total from $tabla;";
        $queryOdoo = "select count(id) as total from esquema_odoo.$tabla;";
        $resEdux = $con->createCommand($queryEdux)->queryOne();
        $resOdoo = $con->createCommand($queryOdoo)->queryOne();

        $valores = array(
            'total_odoo' => $resOdoo['total'],  // Total de usuarios
            'total_edux' => $resEdux['total'],  // Total de usuarios
        );

        return $valores;
    }

    public function actionSincronizarTabla()
    {

        $tabla = $_GET['table'];

        $con = Yii::$app->db;
        if ($tabla == 'res_users') {
            $query = "insert    into res_users (active, login, password, company_id, 
                                partner_id, create_date, create_uid, share, write_uid, write_date, 
                                signature, action_id, password_crypt, alias_id, chatter_needaction_auto, 
                                store_id, sale_team_id, pos_security_pin, pos_config, target_sales_done, 
                                target_sales_won, target_sales_invoiced, sip_external_phone, sip_ring_number, 
                                sip_login, sip_password, sip_always_transfer)
                                select active, login, password, company_id, partner_id, create_date, create_uid, 
                                share, write_uid, write_date, signature, action_id, password_crypt, 
                                alias_id, chatter_needaction_auto, store_id, sale_team_id, pos_security_pin, 
                                pos_config, target_sales_done, target_sales_won, target_sales_invoiced, 
                                sip_external_phone, sip_ring_number, sip_login, sip_password, 
                                sip_always_transfer  
                        from    esquema_odoo.res_users
                        where   login not in (select login from res_users);";
        } else {
            $query = "insert into $tabla 
                  select * from esquema_odoo.$tabla
                  where id not in (select id from $tabla);";
        }
        $con->createCommand($query)->execute();
        return $this->redirect('index');
    }
}
