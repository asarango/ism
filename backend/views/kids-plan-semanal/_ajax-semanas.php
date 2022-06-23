<?php
// echo '<pre>';
// print_r($planSemanal);
// echo $experienciaId;
?>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="table table-responsive">
            <table class="table table-hover table-stripped" id="table-exp" >
                <thead>
                    <tr>
                        <th scope="col">ACCIONES</th>
                        <th scope="col">SEMANA</th>
                        <th scope="col">ESTADO</th>
                        <th scope="col">EXPERIENCIA</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach($planSemanal as $i => $plan){
                            ?>
                            <tr>
                                <td><?=$plan['nombre_semana']?></td>
                                <td><?=$plan['nombre_semana']?></td>
                                <td><?=$plan['estado']?></td>
                                <td><?=$plan['experiencia']?></td>
                            </tr>   
                            <?php
                        }
                    ?>
                    
                </tbody>
            </table>
        </div>
    </div>

</div>


<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>-->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
<script>
        // alert('entra');
        // console.log('olakse');
        $("#table-exp").dataTable();
        
        
    
    
</script>