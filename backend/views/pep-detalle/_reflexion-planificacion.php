<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">


<!--inicio de reflexiones iniciales-->
<p class="text-primero"><b><u>Reflexiones Iniciales:</u></b></p>
<div class="form-group"> 
    <div id="editor-ref-inicial">
        <?php
            foreach ($registros as $reg){
                if($reg->tipo == 'reflexiones_iniciales'){
                    echo $reg->contenido_texto;
                    $registroId = $reg->id;
                }
            }            
        ?>
    </div>
    
    <button type="submit" class="btn btn-outline-success" 
            style="margin-top: 10px"
            onclick="grabarRefInicial(<?= $registroId ?>)">
        Guardar
    </button>
</div>
<!--fin de reflexiones iniciales-->

<hr>
<br>
<!--inicio de conocimientos previos-->
<p class="text-primero"><b><u>Conocimientos previos:</u></b></p>
<div class="form-group"> 
    <div id="editor-con-previos">
        <?php
            foreach ($registros as $reg){
                if($reg->tipo == 'conocimientos_previos'){
                    echo $reg->contenido_texto;
                    $registroId = $reg->id;
                }
            }            
        ?>
    </div>
    
    <button type="submit" class="btn btn-outline-success" 
            style="margin-top: 10px"
            onclick="grabarConPrevio(<?= $registroId ?>)">
        Guardar
    </button>
</div>
<!--fin de conocimientos previos-->

<hr>
<br>
<!--inicio de transdisciplinarias y con el pasado-->
<p class="text-primero"><b><u>Conexiones transdisciplinarias y con el pasado:</u></b></p>
<div class="form-group"> 
    <div id="editor-trans">
        <?php
            foreach ($registros as $reg){
                if($reg->tipo == 'transdisciplinarias_pasado'){
                    echo $reg->contenido_texto;
                    $registroId = $reg->id;
                }
            }            
        ?>
    </div>
    
    <button type="submit" class="btn btn-outline-success" 
            style="margin-top: 10px"
            onclick="grabarTrans(<?= $registroId ?>)">
        Guardar
    </button>
</div>
<!--fin de transdisciplinarias y con el pasado-->


<hr>
<br>
<!--inicio de objetivos de aprendizaje-->
<p class="text-primero"><b><u>Objetivos de aprendizaje y criterios de logro:</u></b></p>
<div class="form-group"> 
    <div id="editor-objetivos">
        <?php
            foreach ($registros as $reg){
                if($reg->tipo == 'objetivos_aprendizaje'){
                    echo $reg->contenido_texto;
                    $registroId = $reg->id;
                }
            }            
        ?>
    </div>
    
    <button type="submit" class="btn btn-outline-success" 
            style="margin-top: 10px"
            onclick="grabarObjetivos(<?= $registroId ?>)">
        Guardar
    </button>
</div>
<!--fin de transdisciplinarias y con el pasado-->


<hr>
<br>
<!--inicio de preguntas de maestros-->
<p class="text-primero"><b><u>Preguntas de los maestros:</u></b></p>
<div class="form-group"> 
    <div id="editor-maestros">
        <?php
            foreach ($registros as $reg){
                if($reg->tipo == 'preguntas_maestros'){
                    echo $reg->contenido_texto;
                    $registroId = $reg->id;
                }
            }            
        ?>
    </div>
    
    <button type="submit" class="btn btn-outline-success" 
            style="margin-top: 10px"
            onclick="grabarPregMaestros(<?= $registroId ?>)">
        Guardar
    </button>
</div>
<!--fin de preguntas de maestros-->


<hr>
<br>
<!--inicio de preguntas de alumnos-->
<p class="text-primero"><b><u>Preguntas de los alumnos:</u></b></p>
<div class="form-group"> 
    <div id="editor-alumnos">
        <?php
            foreach ($registros as $reg){
                if($reg->tipo == 'preguntas_alumnos'){
                    echo $reg->contenido_texto;
                    $registroId = $reg->id;
                }
            }            
        ?>
    </div>
    
    <button type="submit" class="btn btn-outline-success" 
            style="margin-top: 10px"
            onclick="grabarPregAlumnos(<?= $registroId ?>)">
        Guardar
    </button>
</div>
<!--fin de preguntas de alumnos-->


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

var quillRefInicial = new Quill('#editor-ref-inicial', {
  modules: {
    toolbar: toolbarOptions
  },
  theme: 'snow'
});

var quillConPrevio = new Quill('#editor-con-previos', {
  modules: {
    toolbar: toolbarOptions
  },
  theme: 'snow'
});

var quillTrans = new Quill('#editor-trans', {
  modules: {
    toolbar: toolbarOptions
  },
  theme: 'snow'
});

var quillObjetivos = new Quill('#editor-objetivos', {
  modules: {
    toolbar: toolbarOptions
  },
  theme: 'snow'
});

var quillMaestros = new Quill('#editor-maestros', {
  modules: {
    toolbar: toolbarOptions
  },
  theme: 'snow'
});

var quillAlumnos = new Quill('#editor-alumnos', {
  modules: {
    toolbar: toolbarOptions
  },
  theme: 'snow'
});




function grabarRefInicial(registroId){
    let editorIdea = quillRefInicial.container.firstChild.innerHTML;
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


function grabarConPrevio(registroId){
    let editorIdea = quillConPrevio.container.firstChild.innerHTML;
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

function grabarTrans(registroId){
    let editorIdea = quillTrans.container.firstChild.innerHTML;
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

function grabarObjetivos(registroId){
    let editorIdea = quillObjetivos.container.firstChild.innerHTML;
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

function grabarPregMaestros(registroId){
    let editorIdea = quillMaestros.container.firstChild.innerHTML;
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

function grabarPregAlumnos(registroId){
    let editorIdea = quillAlumnos.container.firstChild.innerHTML;
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