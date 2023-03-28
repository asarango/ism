<div class="card">
    <div class="card-header">
        <h6 id=""><b>1. Esquema del curso / Plan vertical</b></h6>
    </div>
    <div class="card-body">
        <div class="table table-responsive" style="font-size: 10px;">
            <table  class="table table-condensed table-hover table-striped table-bordered"
                    style="background-color: #f8f9fa;">
                <thead>
                    <tr>
                        <th class="text-center align-middle">CURSO</th>
                        <th class="text-center align-middle">N° DE UNIDAD</th>
                        <th class="text-center align-middle">TEMA DE LA UNIDAD</th>
                        <th class="text-center align-middle">OBJETIVO DE UNIDAD</th>
                        <th class="text-center align-middle">CONCEPTOS CLAVE</th>
                        <th class="text-center align-middle">CONTENIDO</th>
                        <th class="text-center align-middle">ENFOQUES DEL APRENDIZAJE</th>
                        <th class="text-center align-middle">EVALUACIÓN</th>
                        <th class="text-center align-middle">RECURSOS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php      
                        $noexiste = '<i class="fas fa-ban" style="color: #ab0a3d"></i>';   
                        $correcto = '<i class="fas fa-check" style="color: green"></i>';
                        foreach( $unidades as $unidad ){
                            echo '<tr>';
                            echo '<td>'.$unidad['curso'].'</td>';
                            echo '<td class="text-center">'.$unidad['curriculo_bloque_id'].'</td>';
                            echo '<td class="text-center">'.$unidad['unit_title'].'</td>';
                            
                            echo '<td class="text-center">';
                                if(strlen($unidad['objetivo_asignatura']) < 20 ){
                                    echo $noexiste;    
                                }else{
                                    echo $correcto;
                                }
                            echo '</td>';

                            echo '<td class="text-center">';
                                if(strlen($unidad['concepto_clave']) < 20 ){
                                    echo $noexiste;    
                                }else{
                                    echo $correcto;
                                }
                            echo '</td>';

                            echo '<td class="text-center">';
                                if(strlen($unidad['contenido']) < 20 ){
                                    echo $noexiste;    
                                }else{
                                    echo $correcto;
                                }
                            echo '</td>';

                            echo '<td class="text-center">';
                                if(strlen($unidad['detalle_len_y_aprendizaje']) < 20 ){
                                    echo $noexiste;    
                                }else{
                                    echo $correcto;
                                }
                            echo '</td>';

                            echo '<td class="text-center">';
                                if(strlen($unidad['objetivo_evaluacion']) < 20 ){
                                    echo $noexiste;    
                                }else{
                                    echo $correcto;
                                }
                            echo '</td>';

                            echo '<td class="text-center">';
                                if(strlen($unidad['recurso']) < 20 ){
                                    echo $noexiste;    
                                }else{
                                    echo $correcto;
                                }
                            echo '</td>';

                            

                            echo '</tr>';
                        }                        
                    ?>
                </tbody>
            </table>
        </div>
    </div>