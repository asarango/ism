<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">


<!--inicio de Reflexión de los maestros-->
<p class="text-primero"><b><u>Reflexión de los maestros:</u></b></p>
<div class="form-group"> 
    <div id="editor-fl-maestros">
        <?php
            foreach ($registros as $reg){
                if($reg->tipo == 'reflexion_maestros'){
                    echo $reg->contenido_texto;
                    $registroId = $reg->id;
                }
            }            
        ?>
    </div>
    
    <button type="submit" class="btn btn-outline-success" 
            style="margin-top: 10px"
            onclick="grabarFlMaestros(<?= $registroId ?>)">
        Guardar
    </button>
</div>
<!--fin de experiencias de aprendizaje-->

<hr>
<br>
<!--inicio de Reflexiones de los alumnos-->
<p class="text-primero"><b><u>Reflexiones de los alumnos:</u></b></p>
<div class="form-group"> 
    <div id="editor-fl-alumnos">
        <?php
            foreach ($registros as $reg){
                if($reg->tipo == 'reflexion_alumnos'){
                    echo $reg->contenido_texto;
                    $registroId = $reg->id;
                }
            }            
        ?>
    </div>
    
    <button type="submit" class="btn btn-outline-success" 
            style="margin-top: 10px"
            onclick="grabarFlAlumnos(<?= $registroId ?>)">
        Guardar
    </button>
</div>
<!--fin de Reflexiones de los alumnos-->

<hr>
<br>
<!--inicio de Reflexiones sobre la evaluación-->
<p class="text-primero"><b><u>Reflexiones sobre la evaluación:</u></b></p>
<div class="form-group"> 
    <div id="editor-fl-evaluacion">
        <?php
            foreach ($registros as $reg){
                if($reg->tipo == 'reflexion_evaluacion'){
                    echo $reg->contenido_texto;
                    $registroId = $reg->id;
                }
            }            
        ?>
    </div>
    
    <button type="submit" class="btn btn-outline-success" 
            style="margin-top: 10px"
            onclick="grabarFlEvaluacion(<?= $registroId ?>)">
        Guardar
    </button>
</div>
<!--fin de Reflexiones sobre la evaluación-->



<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
  var toolbarOptions = [
  ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
  ['blockquote', 'code-block'],

  [{ 'header': 1 }, { 'header': 2 }],               // custom button values
  [{ 'list': 'ordered'}, { 'list': 'bullet' }],
  [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
  [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
  [{ 'direction': 'rtl' }],                         // text direction

  [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
  [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

  [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
  [{ 'font': [] }],
  [{ 'align': [] }],

  ['clean'],                                         // remove formatting button
  ['video']                                         // remove formatting button
];

var quillFlMaestros = new Quill('#editor-fl-maestros', {
  modules: {
    toolbar: toolbarOptions
  },
  theme: 'snow'
});

var quillFlAlumnos = new Quill('#editor-fl-alumnos', {
  modules: {
    toolbar: toolbarOptions
  },
  theme: 'snow'
});

var quillFlEvaluacion = new Quill('#editor-fl-evaluacion', {
  modules: {
    toolbar: toolbarOptions
  },
  theme: 'snow'
});




function grabarFlMaestros(registroId){
    let editorIdea = quillFlMaestros.container.firstChild.innerHTML;
    let url = '<?= yii\helpers\Url::to(['update']) ?>';
    
    params = {
      contenido_texto : editorIdea,
      registro_id: registroId
    };
    
    $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function (resp) {
                var resultado = JSON.parse(resp);
                var estado = resultado.status;
                if(estado == 'ok'){
                    alert('Actualizado correctamente!');
                }else{
                    alert('El registro no se actualizó correctamente!');
                }
            }
        });
}

function grabarFlAlumnos(registroId){
    let editorIdea = quillFlAlumnos.container.firstChild.innerHTML;
    let url = '<?= yii\helpers\Url::to(['update']) ?>';
    
    params = {
      contenido_texto : editorIdea,
      registro_id: registroId
    };
    
    $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function (resp) {
                var resultado = JSON.parse(resp);
                var estado = resultado.status;
                if(estado == 'ok'){
                    alert('Actualizado correctamente!');
                }else{
                    alert('El registro no se actualizó correctamente!');
                }
            }
        });
}

function grabarFlEvaluacion(registroId){
    let editorIdea = quillFlEvaluacion.container.firstChild.innerHTML;
    let url = '<?= yii\helpers\Url::to(['update']) ?>';
    
    params = {
      contenido_texto : editorIdea,
      registro_id: registroId
    };
    
    $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function (resp) {
                var resultado = JSON.parse(resp);
                var estado = resultado.status;
                if(estado == 'ok'){
                    alert('Actualizado correctamente!');
                }else{
                    alert('El registro no se actualizó correctamente!');
                }
            }
        });
}




</script>