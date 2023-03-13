<?php

namespace backend\models\helpers;

use backend\models\PlanificacionBloquesUnidad;
use backend\models\PlanificacionOpciones;
use DateTime;
use Yii;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

class Scripts extends ActiveRecord {

    /**
     * Mètodo que devuelve el nombre y a que malla pertenece la materia
     * @return type
     */
    function sql_materias_x_periodo() {
        $periodoId = Yii::$app->user->identity->periodo_id;

        $con = Yii::$app->db;
        $query = "select 	iam.id
		,concat(im.nombre, ' ',malla.nombre) as materia
                from	ism_materia im 
                                inner join ism_area_materia iam on iam.materia_id = im.id
                                inner join scholaris_malla_area sma on sma.id = iam.malla_area_id 
                                inner join ism_periodo_malla ipm on ipm.id = sma.malla_id 
                                inner join ism_malla malla on malla.id = ipm.malla_id
                where 	ipm.scholaris_periodo_id = $periodoId
                order by 2;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    /**
     * Muestra las clases por profesor para realizar el registro de ingreso a la clase
     * Asistencia del docente
     * @return type
     */
    function sql_mostrar_clases_x_profesor() {
        $periodoId = Yii::$app->user->identity->periodo_id;
        $usuarioLog = Yii::$app->user->identity->usuario;
        $con = \Yii::$app->db;

        $query = "select 	mat.nombre as materia, hor.detalle_id ,cur.name as curso ,par.name as paralelo 
        ,sec.code
        ,hor.clase_id ,dia.id as dia_id ,dia.nombre as dia ,hora.id as hora_id 
        ,hora.nombre as hora ,hora.desde as desde ,hora.hasta as hasta 
        ,asi.id as asistencia_id ,asi.hora_ingresa 
from 	scholaris_horariov2_horario hor 
        inner join scholaris_horariov2_detalle det on det.id = hor.detalle_id 
        inner join scholaris_horariov2_hora hora on hora.id = det.hora_id 
        inner join scholaris_horariov2_dia dia on dia.id = det.dia_id 
        inner join scholaris_clase cla on cla.id = hor.clase_id                                     
        inner join ism_area_materia am on am.id = cla.ism_area_materia_id 
        inner join ism_materia mat on mat.id = am.materia_id 
        inner join op_course_paralelo par on par.id = cla.paralelo_id 
        inner join op_course cur on cur.id = par.course_id 
        inner join op_section sec on sec.id = cur.section
        left join scholaris_asistencia_profesor asi on asi.clase_id = hor.clase_id 
                        and asi.fecha = current_date and asi.hora_id = hora.id
where hor.clase_id in 
        ( select c.id from scholaris_clase c 
                inner join op_faculty f on f.id = c.idprofesor 
                inner join res_users u on u.partner_id = f.partner_id 
                inner join ism_area_materia amat on amat.id = c.ism_area_materia_id 
                inner join ism_malla_area mallaarea on mallaarea.id = amat.malla_area_id 
                inner join ism_periodo_malla pmalla on pmalla.id = mallaarea.periodo_malla_id 
                where u.login = '$usuarioLog' 
                and pmalla.scholaris_periodo_id  = $periodoId 
                and c.es_activo = true
                ) 
        and dia.numero = date_part('dow',current_date) 
order by to_timestamp(hora.desde, 'HH24:MI:SS')  asc;";

        // echo '<pre>';
        // echo $query;
        // die();

        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    /**
     * Muestras todas las clases que tiene un profesor
     * Metodo Usado en : PLANIFICACION NEE
     * Asistencia del docente
     * @return type
     */
    public function sql_mostrar_todas_las_clases_x_profesor() {
        $periodoId = Yii::$app->user->identity->periodo_id;
        $usuarioLog = Yii::$app->user->identity->usuario;
        $con = \Yii::$app->db;

        $query = "select 	distinct mat.nombre as materia, /*hor.detalle_id ,*/cur.name as curso ,par.name as paralelo 
        ,sec.code,hor.clase_id 
        /*,dia.id as dia_id ,dia.nombre as dia ,hora.id as hora_id 
        ,hora.nombre as hora ,hora.desde as desde ,hora.hasta as hasta 
        ,asi.id as asistencia_id ,asi.hora_ingresa */
        from 	scholaris_horariov2_horario hor 
                inner join scholaris_horariov2_detalle det on det.id = hor.detalle_id 
                inner join scholaris_horariov2_hora hora on hora.id = det.hora_id 
                inner join scholaris_horariov2_dia dia on dia.id = det.dia_id 
                inner join scholaris_clase cla on cla.id = hor.clase_id                                     
                inner join ism_area_materia am on am.id = cla.ism_area_materia_id 
                inner join ism_materia mat on mat.id = am.materia_id 
                inner join op_course_paralelo par on par.id = cla.paralelo_id 
                inner join op_course cur on cur.id = par.course_id 
                inner join op_section sec on sec.id = cur.section
                left join scholaris_asistencia_profesor asi on asi.clase_id = hor.clase_id 
                                and asi.fecha = current_date and asi.hora_id = hora.id
        where hor.clase_id in 
                ( select c.id from scholaris_clase c 
                        inner join op_faculty f on f.id = c.idprofesor 
                        inner join res_users u on u.partner_id = f.partner_id 
                        inner join ism_area_materia amat on amat.id = c.ism_area_materia_id 
                        inner join ism_malla_area mallaarea on mallaarea.id = amat.malla_area_id 
                        inner join ism_periodo_malla pmalla on pmalla.id = mallaarea.periodo_malla_id 
                        where u.login = '$usuarioLog' 
                        and pmalla.scholaris_periodo_id  = $periodoId 
                        and c.es_activo = true
                        ) 
                and dia.numero > 0  --date_part('dow',current_date) 
        ;";

        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    /**
     * Muestra la siguiente hora para generar timbrada o registro de la asistencia
     * @param type $claseId
     * @return type
     */
    public function sql_mostrar_hora_siguiente($claseId) {
        $periodoId = Yii::$app->user->identity->periodo_id;
        $usuarioLog = Yii::$app->user->identity->usuario;
        $con = \Yii::$app->db;

        $query = "select mat.nombre as materia, hor.detalle_id 
,cur.name as curso ,par.name as paralelo, hora.numero ,hor.clase_id 
,dia.id as dia_id ,dia.nombre as dia ,hora.id as hora_id 
,hora.nombre as hora ,hora.desde as desde ,hora.hasta as hasta 
,asi.id as asistencia_id ,asi.hora_ingresa 
from 	scholaris_horariov2_horario hor 
		inner join scholaris_horariov2_detalle det on det.id = hor.detalle_id 
		inner join scholaris_horariov2_hora hora on hora.id = det.hora_id 
		inner join scholaris_horariov2_dia dia on dia.id = det.dia_id 
		inner join scholaris_clase cla on cla.id = hor.clase_id 
		inner join ism_area_materia am on am.id = cla.ism_area_materia_id 
		inner join ism_materia mat on mat.id = am.materia_id 
		inner join op_course_paralelo par on par.id = cla.paralelo_id
		inner join op_course cur on cur.id = par.course_id 
		left join scholaris_asistencia_profesor asi on asi.clase_id = hor.clase_id 
		and asi.fecha = current_date and asi.hora_id = hora.id 
where hor.clase_id in 
	( select c.id 
			from scholaris_clase c 
				inner join ism_area_materia am on am.id = c.ism_area_materia_id 
				inner join ism_materia mat on mat.id = am.materia_id 
				inner join ism_malla_area mallaarea on mallaarea.id = am.malla_area_id 
				inner join ism_periodo_malla permalla on permalla.id = mallaarea.periodo_malla_id 
				inner join op_faculty f on f.id = c.idprofesor 
				inner join res_users u on u.partner_id = f.partner_id 
			where 	u.login = '$usuarioLog' 
					and permalla.scholaris_periodo_id = $periodoId 
	) 
	and dia.numero = date_part('dow',current_date) 
	and cla.id = $claseId order by hora.numero desc limit 1;";

        $res = $con->createCommand($query)->queryOne();
        return $res;
    }

    public function get_todas_clases_profesor() {
        $periodoId = Yii::$app->user->identity->periodo_id;
        $usuarioLog = Yii::$app->user->identity->usuario;
        $con = \Yii::$app->db;

        $query = "select 	c.id as clase_id ,cur.name as curso 
                                    ,par.name as paralelo ,m.nombre as materia 
                    from 	scholaris_clase c 
                                    inner join op_faculty f on f.id = c.idprofesor 
                                    inner join res_users u on u.partner_id = f.partner_id 
                                    inner join op_course_paralelo par on par.id = c.paralelo_id 
                                    inner join op_course cur on cur.id = par.course_id 
                                    inner join ism_area_materia am on am.id = c.ism_area_materia_id 
                                    inner join ism_materia m on m.id = am.materia_id 
                                    inner join ism_malla_area ma on ma.id = am.malla_area_id 
                                    inner join ism_periodo_malla pm on pm.id = ma.periodo_malla_id 
                    where 	u.login = '$usuarioLog' 
                                    and pm.scholaris_periodo_id = $periodoId
                                    and c.es_activo = true
                                    order by cur.name, par.name";

        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function pud_dip_consultar_lenguaje_y_aprendizaje_ckeck($planVertDiplId) {
        //consulta los los tdc que han sido marcados con check, mas los que aun no estan marcados
        $con = Yii::$app->db;
        $query = "select p.categoria ,p.opcion,pr.id as pvd_tdc_id ,p.id as tdc_id, 
                case 
                when p.id is not null then true else false
                end as es_seleccionado
                from planificacion_opciones p, planificacion_vertical_diploma_relacion_tdc pr,
                planificacion_vertical_diploma pvd 
                where p.tipo='LENGUAJE_Y_CONOCIMIENTO'  and pvd.id =$planVertDiplId 
                and pr.vertical_diploma_id = pvd.id   
                and pr.relacion_tdc_id  = p.id
                union all 
                select p.categoria ,p.opcion,0 as pvd_tdc_id,p.id tdc_id, 
                case 
                when null is not null then true else false
                end as es_seleccionado
                from planificacion_opciones p
                where p.tipo='LENGUAJE_Y_CONOCIMIENTO'
                and p.id not in 
                ( select relacion_tdc_id  from planificacion_vertical_diploma_relacion_tdc 
                where vertical_diploma_id = $planVertDiplId)
                order by opcion;
                ";
        $resultado = $con->createCommand($query)->queryAll();
        return $resultado;
        ;
    }

    //metodo usado para 5.6.- llamada a Conexion CAS
    public function pud_dip_consultar_conexion_cas_ckeck($planVertDiplId) {
        //consulta los los tdc que han sido marcados con check, mas los que aun no estan marcados
        $con = Yii::$app->db;
        $query = "select p.categoria ,p.opcion,pr.id as pvd_tdc_id ,p.id as tdc_id, 
                 case 
                 when p.id is not null then true else false
                 end as es_seleccionado
                 from planificacion_opciones p, planificacion_vertical_diploma_relacion_tdc pr,
                 planificacion_vertical_diploma pvd 
                 where p.tipo='CONEXION_CAS'  and pvd.id =$planVertDiplId 
                 and pr.vertical_diploma_id = pvd.id   
                 and pr.relacion_tdc_id  = p.id
                 union all 
                 select p.categoria ,p.opcion,0 as pvd_tdc_id,p.id tdc_id, 
                 case 
                 when null is not null then true else false
                 end as es_seleccionado
                 from planificacion_opciones p
                 where p.tipo='CONEXION_CAS'
                 and p.id not in 
                 ( select relacion_tdc_id  from planificacion_vertical_diploma_relacion_tdc 
                 where vertical_diploma_id = $planVertDiplId)
                 order by opcion;
                 ";
        $resultado = $con->createCommand($query)->queryAll();
        return $resultado;
    }

    //consulta para extraer el porcentaje de avance del PUD DIPLOMA, para guardar en BLOQUE UNIDAD
    public function pud_dip_porcentaje_avance($planVertDiplId, $planBloqueUniId) {
        $con = Yii::$app->db;
        $modelOpciones = PlanificacionOpciones::find()
                ->where(['tipo' => 'PUD_NUM_CART_VALIDACION'])
                ->one();
        $minimoCaracteres = $modelOpciones->categoria;

        $query = "select  coalesce(round(
            (((10)/*SE SUMA 10 POR DATOS EN DEFAULT (1.1 / 3.1 / 4.1 / 5.3 / 5.5 /5.7/5.8/7.1/ 7.2/7.3/ )*/+
            (select case when LENGTH(descripcion_texto_unidad) /*2.1*/ > $minimoCaracteres then 1 else 0 END from planificacion_vertical_diploma pvd where planificacion_bloque_unidad_id = $planBloqueUniId)+
            (select case when LENGTH(habilidades) /*4.2*/ > $minimoCaracteres then 1 else 0 END from planificacion_vertical_diploma pvd where planificacion_bloque_unidad_id = $planBloqueUniId)+
            (select case when count(*) /*5.2*/> 0 then 1 else 0 end  from pud_dip_proceso_aprendizaje pa where es_activo = true and plan_unidad_id = $planBloqueUniId)+
            (select case when LENGTH(recurso) /*6.1*/ > $minimoCaracteres then 1 else 0 END from planificacion_vertical_diploma pvd where planificacion_bloque_unidad_id = $planBloqueUniId)+
            --(select case when LENGTH(reflexion_funciono) /*7.1*/ > $minimoCaracteres then 1 else 0 END from planificacion_vertical_diploma pvd where planificacion_bloque_unidad_id = $planBloqueUniId)+
            --(select case when LENGTH(reflexion_no_funciono) /*7.2*/> $minimoCaracteres then 1 else 0 END from planificacion_vertical_diploma pvd where planificacion_bloque_unidad_id = $planBloqueUniId)+
            --(select case when LENGTH(reflexion_observacion) /*7.3*/> $minimoCaracteres then 1 else 0 END from planificacion_vertical_diploma pvd where planificacion_bloque_unidad_id = $planBloqueUniId)+
            (select case when count(p.categoria) /*5.4*/ > 0 then 1 else 0 end 
                             from planificacion_opciones p, planificacion_vertical_diploma_relacion_tdc pr,
                             planificacion_vertical_diploma pvd 
                             where p.tipo='CONEXION_CAS'  and pvd.id =$planVertDiplId 
                             and pr.vertical_diploma_id = pvd.id   
                             and pr.relacion_tdc_id  = p.id)+
            (select case when count(p.categoria) /*5.6*/ > 0 then 1 else 0 end 
                            from planificacion_opciones p, planificacion_vertical_diploma_relacion_tdc pr,
                            planificacion_vertical_diploma pvd 
                            where p.tipo='LENGUAJE_Y_CONOCIMIENTO'  and pvd.id =$planVertDiplId 
                            and pr.vertical_diploma_id = pvd.id   
                            and pr.relacion_tdc_id  = p.id)+
            (select case when count(*)>0 then 1 else 0 end /*5.3.1*/from pud_dip pd where codigo = 'METACOGNICION'  and campo_de ='seleccion' and opcion_boolean = true
                             and planificacion_bloques_unidad_id =$planBloqueUniId)+
            (select case when count(*)>0 then 1 else 0 end /*5.3.2*/from pud_dip pd where codigo = 'APRENDIZAJE-DIFERENCIADO'  and campo_de ='seleccion' and opcion_boolean = true
                             and planificacion_bloques_unidad_id =$planBloqueUniId)+
            (select case when count(*)>0 then 1 else 0 end from pud_dip_evaluaciones pde where plan_unidad_id = $planBloqueUniId and  length(formativa)>0 
                            and length(sumativa)>0 ) 
                            
                            ) * 100 )/ 
                            (select cast(categoria as numeric(18,2))from planificacion_opciones where tipo='PUD_CONTEO_PORCENTAJE'and seccion = 'DIP')

                           ,0),0) as porcentaje;
                 ";

        $resultado = $con->createCommand($query)->queryOne();
        return $resultado;
    }

    //consulta para extraer el porcentaje de avance del PUD PAI, para guardar en BLOQUE UNIDAD
    public function pud_pai_porcentaje_avance($planBloqueUniId) {
        $con = Yii::$app->db;
        $modelOpciones = PlanificacionOpciones::find()
                ->where(['tipo' => 'PUD_NUM_CART_VALIDACION'])
                ->one();
        $minimoCaracteres = $modelOpciones->categoria;

        $query = "select (( 4 /* se agraga 3 porque es el numero de item que el usuario no tiene que llenar*/ +
        /*2.3.-*/(
        select case when (  
        (select case when count(*)>0 then 1 else 0 end from pud_pai pp where planificacion_bloque_unidad_id  = $planBloqueUniId and seccion_numero  = '2'
        and tipo = 'facticas') +
        (select case when count(*)>0 then 1 else 0 end  from pud_pai pp where planificacion_bloque_unidad_id  = $planBloqueUniId and seccion_numero  = '2'
        and tipo = 'conceptuales') +
        (select case when count(*)>0 then 1 else 0 end  from pud_pai pp where planificacion_bloque_unidad_id  = $planBloqueUniId and seccion_numero  = '2'
        and tipo = 'debatibles') 
        ) = 3 then 1 else 0 end ) +
        /*3.1.-*/
        (
            select case when ((
                select case when count(*)>0  then 1 else  0 end from planificacion_vertical_pai_opciones pvpo 
                where plan_unidad_id = $planBloqueUniId and tipo = 'habilidad_enfoque' 
                ) +
                (
                select case when count(*)>0  then 0 else 1 end from planificacion_vertical_pai_opciones pvpo 
                                            where plan_unidad_id = $planBloqueUniId and tipo = 'habilidad_enfoque' 
                                            and (actividad is null or id_pudpai_perfil is null)
                )) = 2 then 1 else 0 end
        ) +
        /*7.1.-*/
        (select case when count(*)>0 then 1 else 0 end from pud_pai pp where planificacion_bloque_unidad_id  = $planBloqueUniId and seccion_numero  = '7')+
        /*9.1.-*/
        (
            select case when ( 
                (select case when length(contenido)>($minimoCaracteres) then 1 else 0 end from pud_pai pp 
                where planificacion_bloque_unidad_id  = $planBloqueUniId and seccion_numero = '9' and tipo = 'tecnologico')+
                (select case when length(contenido)>($minimoCaracteres) then 1 else 0 end from pud_pai pp 
                where planificacion_bloque_unidad_id  = $planBloqueUniId and seccion_numero = '9' and tipo = 'otros')+
                (select case when length(contenido)>($minimoCaracteres) then 1 else 0 end from pud_pai pp 
                where planificacion_bloque_unidad_id  = $planBloqueUniId and seccion_numero = '9' and tipo = 'bibliografico')
            )>0 then 1 else 0 end

        )+
        /*10.1.-*/
        (select case when count(*)>0 then 1 else 0 end from pud_pai pp where planificacion_bloque_unidad_id  = $planBloqueUniId and seccion_numero  ='10' 
        and tipo='antes' ) +
        /*5.1.-*/
        (
            select ( case when (
                (select 
                case when (length(contenido)>($minimoCaracteres))
                then 1 else 0 end
                from pud_pai pp 
                where planificacion_bloque_unidad_id  = $planBloqueUniId and seccion_numero  ='5' and tipo = 'relacion-suma-eval') 
                +
                (select 
                case when (length(contenido)>($minimoCaracteres))
                then 1 else 0 end
                from pud_pai pp 
                where planificacion_bloque_unidad_id  = $planBloqueUniId and seccion_numero  ='5' and tipo = 'eval_sumativa')
                +
                (select 
                case when (length(contenido)>($minimoCaracteres))
                then 1 else 0 end
                from pud_pai pp 
                where planificacion_bloque_unidad_id  = $planBloqueUniId and seccion_numero  ='5' and tipo = 'eval_formativa')
                ) = 3 then 1 else 0 end  )
        )+
        /*4.1*/
        (select case when count(*)>0 then 1 else 0 end from ism_contenido_pai_planificacion where planificacion_bloque_unidad_id = $planBloqueUniId) 
        )*100/(select cast(categoria as integer) from planificacion_opciones po where tipo = 'PUD_CONTEO_PORCENTAJE' and seccion='PAI')) 
        AS porcentaje;";

        //    print_r($query);
        //    die();

        $resultado = $con->createCommand($query)->queryOne();
        return $resultado;
    }

    //Script para consultar de forma Independiente el porcentaje de avance de pud pai
    //consulta para extraer el porcentaje de avance del PUD PAI, para guardar en BLOQUE UNIDAD
    public function pud_pai_porcentaje_avance_individual($aBuscar, $planBloqueUniId) {
        $con = Yii::$app->db;
        $modelOpciones = PlanificacionOpciones::find()
                ->where(['tipo' => 'PUD_NUM_CART_VALIDACION'])
                ->one();
        $minimoCaracteres = $modelOpciones->categoria;
        $query = '';

        switch ($aBuscar) {
            case '2.3.-':
                $query = "select case when (  
                        (select case when count(*)>0 then 1 else 0 end from pud_pai pp where planificacion_bloque_unidad_id  = $planBloqueUniId and seccion_numero  = '2'
                        and tipo = 'facticas') +
                        (select case when count(*)>0 then 1 else 0 end  from pud_pai pp where planificacion_bloque_unidad_id  = $planBloqueUniId and seccion_numero  = '2'
                        and tipo = 'conceptuales') +
                        (select case when count(*)>0 then 1 else 0 end  from pud_pai pp where planificacion_bloque_unidad_id  = $planBloqueUniId and seccion_numero  = '2'
                        and tipo = 'debatibles') 
                        ) = 3 then 1 else 0 end";
                break;
            case '3.1.-':
                $query = "  select case when ((
                    select case when count(*)>0  then 1 else  0 end from planificacion_vertical_pai_opciones pvpo 
                    where plan_unidad_id = $planBloqueUniId and tipo = 'habilidad_enfoque' 
                    ) +
                    (
                    select case when count(*)>0  then 0 else 1 end from planificacion_vertical_pai_opciones pvpo 
                                                where plan_unidad_id = $planBloqueUniId and tipo = 'habilidad_enfoque' 
                                                and (actividad is null or id_pudpai_perfil is null)
                    )) = 2 then 1 else 0 end";
                break;
            case '4.1.-':
                    $query = "select case when count(*)>0 then 1 else 0 end from ism_contenido_pai_planificacion where planificacion_bloque_unidad_id = $planBloqueUniId 
                    and length(actividad)>0 and length(objetivo)>0 and length(relacion_ods)>0";
                break;
            case '5.1.-':
                $query = "select ( case when (
                    (select 
                    case when (length(contenido)>($minimoCaracteres))
                    then 1 else 0 end
                    from pud_pai pp 
                    where planificacion_bloque_unidad_id  = $planBloqueUniId and seccion_numero  ='5' and tipo = 'relacion-suma-eval') 
                    +
                    (select 
                    case when (length(contenido)>($minimoCaracteres))
                    then 1 else 0 end
                    from pud_pai pp 
                    where planificacion_bloque_unidad_id  = $planBloqueUniId and seccion_numero  ='5' and tipo = 'eval_sumativa')
                    +
                    (select 
                    case when (length(contenido)>($minimoCaracteres))
                    then 1 else 0 end
                    from pud_pai pp 
                    where planificacion_bloque_unidad_id  = $planBloqueUniId and seccion_numero  ='5' and tipo = 'eval_formativa')
                    ) = 3 then 1 else 0 end  )";
                break;
            case '7.1.-':
                $query = "select case when count(*)>0 then 1 else 0 end from pud_pai pp where planificacion_bloque_unidad_id  = $planBloqueUniId and seccion_numero  = '7'";
                break;
            case '9.1.-':
                $query = "select case when (
                        (select case when (length(contenido)>($minimoCaracteres))
                        then 1 else 0 end from pud_pai where planificacion_bloque_unidad_id =$planBloqueUniId and seccion_numero  ='9' and tipo='bibliografico')+
                        (select case when (length(contenido)>($minimoCaracteres))
                        then 1 else 0 end from pud_pai where planificacion_bloque_unidad_id =$planBloqueUniId and seccion_numero  ='9' and tipo='tecnologico')+
                        (select case when (length(contenido)>($minimoCaracteres))
                        then 1 else 0 end from pud_pai where planificacion_bloque_unidad_id =$planBloqueUniId and seccion_numero  ='9' and tipo='otros')
                        )>0 then 1 else 0 end as siete;";
                break;
            case '10.1.-':
                $query = "select case when count(*)>0 then 1 else 0 end from pud_pai pp where planificacion_bloque_unidad_id  = $planBloqueUniId and seccion_numero  ='10' and tipo='antes'";
                break;
            case 'todos':
                $query = "select /*2.3.-*/(
                        select case when (  
                        (select case when count(*)>0 then 1 else 0 end from pud_pai pp where planificacion_bloque_unidad_id  = $planBloqueUniId and seccion_numero  = '2'
                        and tipo = 'facticas') +
                        (select case when count(*)>0 then 1 else 0 end  from pud_pai pp where planificacion_bloque_unidad_id  = $planBloqueUniId and seccion_numero  = '2'
                        and tipo = 'conceptuales') +
                        (select case when count(*)>0 then 1 else 0 end  from pud_pai pp where planificacion_bloque_unidad_id  = $planBloqueUniId and seccion_numero  = '2'
                        and tipo = 'debatibles') 
                        ) = 3 then 1 else 0 end ) as dos,
                        /*7.1.-*/
                        (select case when count(*)>0 then 1 else 0 end from pud_pai pp where planificacion_bloque_unidad_id  = $planBloqueUniId and seccion_numero  = '7') as siete,
                        /*9.1.-*/
                        (select case when (
                            (select case when (length(contenido)>($minimoCaracteres))
                            then 1 else 0 end from pud_pai where planificacion_bloque_unidad_id =$planBloqueUniId and seccion_numero  ='9' and tipo='bibliografico')+
                            (select case when (length(contenido)>($minimoCaracteres))
                            then 1 else 0 end from pud_pai where planificacion_bloque_unidad_id =$planBloqueUniId and seccion_numero  ='9' and tipo='tecnologico')+
                            (select case when (length(contenido)>($minimoCaracteres))
                            then 1 else 0 end from pud_pai where planificacion_bloque_unidad_id =$planBloqueUniId and seccion_numero  ='9' and tipo='otros')
                            )>0 then 1 else 0 end )as nueve,
                        /*10.1.-*/
                        (select case when count(*)>0 then 1 else 0 end from pud_pai pp where planificacion_bloque_unidad_id  = $planBloqueUniId and seccion_numero  ='10' 
                        and tipo='antes' ) as diez,
                        /*3.1.-*/
                        (
                            select case when ((
                                select case when count(*)>0  then 1 else  0 end from planificacion_vertical_pai_opciones pvpo 
                                where plan_unidad_id = $planBloqueUniId and tipo = 'habilidad_enfoque' 
                                ) +
                                (
                                select case when count(*)>0  then 0 else 1 end from planificacion_vertical_pai_opciones pvpo 
                                                            where plan_unidad_id = $planBloqueUniId and tipo = 'habilidad_enfoque' 
                                                            and (actividad is null or id_pudpai_perfil is null)
                                )) = 2 then 1 else 0 end
                        ) as tres,
                        /*5.1.-*/
                        (
                            select ( case when (
                                (select 
                                case when (length(contenido)>($minimoCaracteres))
                                then 1 else 0 end
                                from pud_pai pp 
                                where planificacion_bloque_unidad_id  = $planBloqueUniId and seccion_numero  ='5' and tipo = 'relacion-suma-eval') 
                                +
                                (select 
                                case when (length(contenido)>($minimoCaracteres))
                                then 1 else 0 end
                                from pud_pai pp 
                                where planificacion_bloque_unidad_id  = $planBloqueUniId and seccion_numero  ='5' and tipo = 'eval_sumativa')
                                +
                                (select 
                                case when (length(contenido)>($minimoCaracteres))
                                then 1 else 0 end
                                from pud_pai pp 
                                where planificacion_bloque_unidad_id  = $planBloqueUniId and seccion_numero  ='5' and tipo = 'eval_formativa')
                                ) = 3 then 1 else 0 end  )
                        ) as cinco,
                        /*4.1*/
                        (select case when count(*)>0 then 1 else 0 end from ism_contenido_pai_planificacion where planificacion_bloque_unidad_id = $planBloqueUniId
                        and length(actividad)>0 and length(objetivo)>0 and length(relacion_ods)>0) as cuatro;";
                break;

        }

    //    echo $query;
    //            die();
        $resultado = $con->createCommand($query)->queryOne();
        return $resultado;
    }

    public function firmar_documento($usuario, $fecha) {

        if (isset($usuario)) {
            $con = \Yii::$app->db;
            $query = "select 	rp.name 
                    from	res_users ru 
                                    inner join res_partner rp on rp.id = ru.partner_id 
                    where 	ru.login = '$usuario';";

            $res = $con->createCommand($query)->queryOne();

            return array(
                'firmado_por' => $res['name'],
                'firmado_el' => $fecha
            );
        } else {
            return array(
                'firmado_por' => 'Sin firma aùn',
                'firmado_el' => $fecha
            );
        }
    }

    public function get_materias_kids_x_docente() {
        $userLog = \Yii::$app->user->identity->usuario;
        $periodoId = \Yii::$app->user->identity->periodo_id;
        $con = \Yii::$app->db;
        $query = "select 	iam.id 
                                    ,m.nombre 
                    from	scholaris_clase cla
                                    inner join op_faculty fac on fac.id = cla.idprofesor 
                                    inner join res_users use on use.partner_id = fac.partner_id
                                    inner join ism_area_materia iam on iam.id = cla.ism_area_materia_id 
                                    inner join ism_malla_area ima on ima.id = iam.malla_area_id 
                                    inner join ism_periodo_malla ipm on ipm.id = ima.periodo_malla_id 
                                    inner join ism_materia m on m.id = iam.materia_id 
                    where 	use.login = '$userLog'
                                    and ipm.scholaris_periodo_id = $periodoId;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function convertir_mes($mesNumero) {

        switch ($mesNumero) {
            case 1:
                $mes = 'Ene';
                break;
            case 2:
                $mes = 'Feb';
                break;

            case 3:
                $mes = 'Mar';
                break;

            case 4:
                $mes = 'Abr';
                break;

            case 5:
                $mes = 'May';
                break;

            case 6:
                $mes = 'Jun';
                break;

            case 7:
                $mes = 'Jul';
                break;

            case 8:
                $mes = 'Ago';
                break;

            case 9:
                $mes = 'Sep';
                break;

            case 10:
                $mes = 'Oct';
                break;

            case 11:
                $mes = 'Nov';
                break;

            case 12:
                $mes = 'Dic';
                break;
            default:
                $mes = 'Sin mes';
                break;
        }

        return $mes;
    }

    /**
     * Toma los cursos actualles del periodo por usuario
     * @param type $periodoId
     * @param type $userLog
     * @return type
     */
    public function get_cursos_x_periodo($periodoId, $userLog) {
        $con = Yii::$app->db;
        $query = "select 	te.id as course_template_id
                                    ,te.name as course_template
                    from	scholaris_clase cl 
                                    inner join op_faculty fa on fa.id = cl.idprofesor
                                    inner join res_users ru on ru.partner_id = fa.partner_id 
                                    inner join op_course_paralelo pa on pa.id = cl.paralelo_id 
                                    inner join op_course oc on oc.id = pa.course_id 
                                    inner join op_course_template te on te.id = oc.x_template_id 
                                    inner join op_section sec on sec.id = oc.section
                                    inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = sec.period_id 
                    where 	ru.login = '$userLog'
                                    and sop.scholaris_id = $periodoId
                    group by te.id, te.name;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function get_paralelo_x_periodo($periodoId, $userLog) {
        $con = Yii::$app->db;
        $query = "select 	par.id as paralelo_id 
                        ,cur.name as curso
                        ,par.name as paralelo
                from	scholaris_clase cla
                        inner join ism_area_materia am on am.id = cla.ism_area_materia_id 
                        inner join ism_malla_area ma on ma.id = am.malla_area_id 
                        inner join ism_periodo_malla pm on pm.id = ma.periodo_malla_id
                        inner join op_faculty f on f.id = cla.idprofesor 
                        inner join res_users u on u.partner_id = f.partner_id
                        inner join op_course_paralelo par on par.id = cla.paralelo_id 
                        inner join op_course cur on cur.id = par.course_id 
                where 	pm.scholaris_periodo_id = $periodoId
                        and u.login = '$userLog'
                group by cur.name, par.name, par.id
                order by cur.name, par.name; ";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function get_alumnos_x_paralelo($paraleloId) {
        $con = Yii::$app->db;
        $query = "select 	s.last_name 
                            ,s.first_name 
                            ,s.middle_name 
                            ,i.inscription_state 
                    from 	op_student_inscription i
                            inner join op_student s on s.id = i.student_id 
                    where 	i.parallel_id = $paraleloId
                    order by s.last_name, s.first_name, s.middle_name;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function mostrarAlumnosNeeClase($id_clase) {

        $con = Yii::$app->db;

        $query = "select n.id,n.student_id ,scholaris_periodo_id ,grado_nee,
                fecha_inicia,diagnostico_inicia,recomendacion_clase,estado,idcurso,
                idprofesor,paralelo_id
                from nee n
                inner join nee_x_clase nc on n.id = nc.nee_id 
                inner join scholaris_periodo p on p.id = n.scholaris_periodo_id 
                inner join scholaris_clase c on c.id = nc.clase_id 
                where nc.clase_id = '$id_clase';";

        $respuesta = $con->createCommand($query)->queryall();
        return $respuesta;
    }

    //devulve listado de usuario y sus cargos
    //se usa en seguimiento DECE
    public function mostrarUsuarioParaDece() {
        $con = Yii::$app->db;
        $query = 'select upper(cast(concat(rp."name",\' - \',rp."ref") as varchar(200))) as usuario
        from usuario u 
        inner join res_users ru on ru.login = u.usuario 
        inner join res_partner rp on rp.id = ru.partner_id 
        order by rp."name";';
        $respuesta = $con->createCommand($query)->queryAll();
        return $respuesta;
    }

    public function get_enfoques($planVerticalId) {
        $con = Yii::$app->db;
        $queryHabilidades = "select 	h.id 
                                    ,h.nombre 
                    from 	planificacion_vertical_diploma_habilidad ph
                                    inner join enfoques_diploma_sb_opcion op on op.id = ph.opcion_habilidad_id 
                                    inner join enfoques_diploma_sub_habilidad sub on sub.id = op.sub_habilidad_id 
                                    inner join enfoques_diploma_habilidad h on h.id = sub.habilidad_id 
                    where 	ph.plan_vertical_id = $planVerticalId
                    group by h.id, h.nombre 
                    order by h.nombre;";

        $queryOpciones = "select 	h.id 
                                            ,h.nombre as habilidad
                                            ,op.nombre as opcion
                            from 	planificacion_vertical_diploma_habilidad ph
                                            inner join enfoques_diploma_sb_opcion op on op.id = ph.opcion_habilidad_id 
                                            inner join enfoques_diploma_sub_habilidad sub on sub.id = op.sub_habilidad_id 
                                            inner join enfoques_diploma_habilidad h on h.id = sub.habilidad_id 
                            where 	ph.plan_vertical_id = $planVerticalId 
                            order by h.nombre, op.nombre ;";

        $habilidades = $con->createCommand($queryHabilidades)->queryAll(); //extrae las habilidades
        $opciones = $con->createCommand($queryOpciones)->queryAll(); //extrae las opciones elejidas en el plan vertical

        $html = '';

        $html .= '<ul>';
        foreach ($habilidades as $habilidad) {
            $html .= '<li><b>' . $habilidad['nombre'] . '</b></li>';
            $html .= '<ul>';
            foreach ($opciones as $op) {
                if ($op['id'] == $habilidad['id']) {
                    $html .= '<li><i class="far fa-arrow-circle-right"></i>' . $op['opcion'] . '</li>';
                }
            }
            $html .= '</ul><br>';
        }
        $html .= '</ul>';

//        return $arregloMaster;
        return $html;
    }

///fin de 5.3

    public function get_nee($periodId, $opCourseTemplateId) {
        $con = Yii::$app->db;
        $query = "select 	cur.id 
		,cur.name as curso
		,p.name as paralelo
		,mat.nombre as materia		
		,concat(s.first_name, ' ', s.middle_name, ' ', s.last_name) as estudiante
		,nxc.grado_nee 
		,nxc.diagnostico_inicia 
		,nxc.fecha_inicia 
from 	nee
		inner join scholaris_grupo_alumno_clase g on g.estudiante_id = nee.student_id 
		inner join scholaris_clase cla on cla.id  = g.clase_id
		inner join ism_area_materia am on am.id = cla.ism_area_materia_id
		inner join ism_malla_area ma on ma.id = am.malla_area_id
		inner join ism_periodo_malla pm on pm.id = ma.periodo_malla_id 
		inner join ism_malla mal on mal.id = pm.malla_id 
		inner join op_course_paralelo p on p.id = cla.paralelo_id 
		inner join op_course cur on cur.id = p.course_id 
		inner join ism_materia mat on mat.id = am.materia_id 
		inner join op_student s on s.id = g.estudiante_id 
		inner join nee_x_clase nxc on nxc.nee_id = nee.id 
						and nxc.clase_id = cla.id 
where 	pm.scholaris_periodo_id = $periodId
		and mal.op_course_template_id = $opCourseTemplateId;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    //trae el CONTENIDO del plan vertical pai
    public function selecciona_subtitulos($planUnidadId) {
        $con = Yii::$app->db;
        $query = "select 	id, plan_unidad_id, subtitulo, orden,trazabilidad 
                    from 	planificacion_bloques_unidad_subtitulo
                    where	plan_unidad_id = $planUnidadId
                    order by orden;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    /**
     * METODO PARA SUBIR ARCHIVOS AL SERVIDOR
     * @param type $files - arry con los archivos pasados desde la vista y el controlador
     * @param type $path directorio donde se almacenan los archivos
     */
    public function upload_files($files, $path) {
        foreach ($files["archivo"]['tmp_name'] as $key => $tmp_name) {
            //Validamos que el archivo exista
            if ($files["archivo"]["name"][$key]) {
                $filename = $files["archivo"]["name"][$key]; //Obtenemos el nombre original del archivo
                $source = $files["archivo"]["tmp_name"][$key]; //Obtenemos un nombre temporal del archivo

                $directorio = $path; //Declaramos un  variable con la ruta donde guardaremos los archivos
                //Validamos si la ruta de destino existe, en caso de no existir la creamos
                if (!file_exists($directorio)) {
                    mkdir($directorio, 0777) or die("No se puede crear el directorio de extracci&oacute;n");
                }

                $dir = opendir($directorio); //Abrimos el directorio de destino
                $target_path = $directorio . '/' . $filename; //Indicamos la ruta de destino, así como el nombre del archivo
                //Movemos y validamos que el archivo se haya cargado correctamente
                //El primer campo es el origen y el segundo el destino
                if (move_uploaded_file($source, $target_path)) {
                    echo "El archivo $filename se ha almacenado en forma exitosa.<br>";
                } else {
                    echo "Ha ocurrido un error, por favor inténtelo de nuevo.<br>";
                }
                closedir($dir); //Cerramos el directorio de destino
            }
        }
    }

    /**
     * UPLOAD FILE --> Sube un único archivo al servidor
     * @param type $file
     * @param type $path
     */
    public function upload_file($file, $path) {
               

        if ($file["archivo"]["name"]) {
            $filename = $file["archivo"]["name"]; //Obtenemos el nombre original del archivo
            $source = $file["archivo"]["tmp_name"]; //Obtenemos un nombre temporal del archivo
            $complementoRuta = '/var/www/html/';

            $directorio = $complementoRuta.$path; //Declaramos un  variable con la ruta donde guardaremos los archivos
            //Validamos si la ruta de destino existe, en caso de no existir la creamos
            if (!file_exists($directorio)) {
                mkdir($directorio, 0777) or die("No se puede crear el directorio de extracci&oacute;n");
                //chmod($directorio, 777);
            }

            $dir = opendir($directorio); //Abrimos el directorio de destino
            $target_path = $directorio . '/' . $filename; //Indicamos la ruta de destino, así como el nombre del archivo
            //Movemos y validamos que el archivo se haya cargado correctamente
            //El primer campo es el origen y el segundo el destino
            if (move_uploaded_file($source, $target_path)) {
                //echo "El archivo $filename se ha almacenado en forma exitosa.<br>";
                return $path . '/' . $filename;
            } else {
                return false;
            }
            closedir($dir); //Cerramos el directorio de destino
        }
    }

    /**
     * METODO PARA USO POR OPCOURSETEMPLATE
     * @param type $opcourseTemplateId
     * @return type
     */
    public function get_tipo_uso_op_course_template($opcourseTemplateId) {
        $periodoId = \Yii::$app->user->identity->periodo_id;
        $con = \Yii::$app->db;
        $query = "select 	c.tipo_usu_bloque  
                    from 	scholaris_clase c
                                    inner join op_course_paralelo p on p.id = c.paralelo_id 
                                    inner join op_course cur on cur.id = p.course_id
                                    inner join op_section sec on sec.id = cur.section
                                    inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = sec.period_id 
                                    inner join scholaris_bloque_actividad b on b.tipo_uso = c.tipo_usu_bloque 
                    where 	cur.x_template_id = $opcourseTemplateId
                                    and sop.scholaris_id = $periodoId
                    limit 1;";

        $res = $con->createCommand($query)->queryOne();

        return $res['tipo_usu_bloque'];
    }

    public function get_docentes_x_coordinador_academico($coordinadorId, $periodoId) {
        $con = \Yii::$app->db;
        $query = "select 	fac.id
                                ,concat(fac.last_name, ' ', fac.x_first_name) as docente 
                                ,sc.tipo_usu_bloque 
                from	scholaris_clase sc
                                inner join op_institute_authorities aut on aut.id = sc.coordinador_academico_id 
                                inner join usuario u on u.usuario = aut.usuario 
                                inner join res_users ru on ru.login = u.usuario 
                                inner join op_faculty fac on fac.id = sc.idprofesor 
                                inner join ism_area_materia am on am.id = sc.ism_area_materia_id 
                                inner join ism_malla_area ma on ma.id = am.malla_area_id 
                                inner join ism_periodo_malla pm on pm.id = ma.periodo_malla_id 
                where 	ru.login = '$coordinadorId'
                                and pm.scholaris_periodo_id = $periodoId
                group by fac.id, fac.last_name, fac.x_first_name, sc.tipo_usu_bloque 
                order by fac.last_name, fac.x_first_name ;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    /**
     * METODO QUE DEVUELVE EL CODIGO DE LA SECCION DEL CURSO
     * @param type $ismAreaMateriaId
     * @return type
     */
    public function get_seccion_x_ism_area_materia($ismAreaMateriaId) {
        $con = \Yii::$app->db;
        $query = "select sec.code 
                    from	ism_area_materia am
                                    inner join scholaris_clase cla on cla.ism_area_materia_id = am.id 
                                    inner join op_course_paralelo par on par.id = cla.paralelo_id 
                                    inner join op_course cur on cur.id = par.course_id 
                                    inner join op_section sec on sec.id = cur.section
                    where 	am.id = $ismAreaMateriaId
                    limit 1;";
        $res = $con->createCommand($query)->queryOne();
        return $res['code'];
    }

}
