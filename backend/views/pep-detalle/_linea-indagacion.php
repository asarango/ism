<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">


<p class="text-primero"><b><u>Líneas de indagación:</u></b></p>

<div class="form-group">
    <div id="editor-indagacion">
        <?php
            foreach ($registros as $reg){
                if($reg->tipo == 'linea_indagacion'){
                    echo $reg->contenido_texto;
                    $registroId = $reg->id;
                }
            }            
        ?>
    </div>
    
    <button type="submit" class="btn btn-outline-success" 
            style="margin-top: 10px"
            onclick="grabarIndagacion(<?= $registroId ?>)">
        Guardar
    </button>
</div>


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

var quillLinea = new Quill('#editor-indagacion', {
  modules: {
    toolbar: toolbarOptions
  },
  theme: 'snow'
});




function grabarIndagacion(registroId){
    let htmlEditor = quillLinea.container.firstChild.innerHTML;
    let url = '<?= yii\helpers\Url::to(['update']) ?>';
    
    params = {
      contenido_texto : htmlEditor,
      registro_id: registroId
    };
    
    $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function (resp) {
                var estado = resp.status;
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