
<p class="text-primero"><b><u>Tema Transdisciplinario:</u></b></p>
<p>
    <b><?= $tema->temaTransdisciplinar->categoria_principal_es ?></b><br>
    <?php //$tema->temaTransdisciplinar->contenido_es ?>
</p>


<div class="form-group">
    <div id="destrezas">
        <?php
            foreach ($registros as $reg){
                if($reg->tipo == 'destrezas'){
                    echo $reg->contenido_texto;
                    $registroId = $reg->id;
                }
            }            
        ?>
    </div>
    
    <button type="submit" class="btn btn-outline-success" 
            style="margin-top: 10px"
            onclick="grabarDestrezas(<?= $registroId ?>)">
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

var quillDestrezas1 = new Quill('#destrezas', {
  modules: {
    toolbar: toolbarOptions
  },
  theme: 'snow'
});




function grabarDestrezas(registroId){
    let editorDestrezas = quillDestrezas1.container.firstChild.innerHTML;
    let url = '<?= yii\helpers\Url::to(['update']) ?>';
    
    params = {
      contenido_texto : editorDestrezas,
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