<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

// echo"<pre>";
// print_r($planificacionSemanalId);
// die();

?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/33.0.0/classic/ckeditor.js"></script>


<style>
    .input-container {
        display: flex;
        border: 1px solid #ccc;
        background-color: #f9f9f9;
        padding: 10px;
        border-radius: 10px;
        text-align: center;
        align-items: center;
    }

    .input-container label {
        margin-right: 10px;
    }

    .input-container textarea {
        resize: none;
        justify-content: center;
        text-align: center;
        weight: 30px;
        height: 30px;
        border-radius: 10px;
    }

    .iconos {
        margin-top: 0.8rem;
        margin-bottom: -0.5rem;
        display: flex;
        border: 1px solid #ccc;
        background-color: #f9f9f9;
        padding: 0.5rem;
        border-radius: 0.5rem;
        text-align: center;
        align-items: center;
        justify-content: center;
        align-items: center
    }

    .formulario {
        border: 1px solid #ccc;
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 10px;
        margin-top: 20px;
    }

    .form-input {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .btn-borr-arch {
        background-color: #ff9e18;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-cerrar {
        background-color: #ab0a3d;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 10px;
    }
</style>


<div class="planificacion-semanal-recursos-form centrar">
    <!-- INICIO DE FORMULARIO -->
    <?= Html::beginForm(['create'], 'post', ['enctype' => 'multipart/form-data']) ?>

    <div class="row align-items-center p-2">
        <div style="margin-bottom: 1rem;margin-top: -2rem">
            <div class="" style="margin:1rem;">
                <!-- plan_semanal_id -->
                <input type="hidden" name="plan_semanal_id" value="<?= $planificacionSemanalId; ?>">
                <input type="hidden" name="bandera" id="input-bandera">
            </div>

            <div class="input-container">
                <label for="tema">
                    <h4 style="margin: 0;">Descripción del recurso:</h4>
                </label>
                <textarea name="tema1" id="tema"></textarea>
            </div>

            <div class="iconos">
                <input type="radio" id="icono1" name="icono" value="icono1" class="radio-icon"
                    onchange="showform('file')" style="margin-right: 4rem">
                <?php echo IconosRecur1('planificacion-semanal-recursos/create.png'); ?>

                <input type="radio" id="icono2" name="icono2" value="icono2" class="radio-icon"
                    onchange="showform('link')" style="margin-right: 4rem;margin-left: 4rem;">
                <?php echo IconosRecur2('planificacion-semanal-recursos/create.png'); ?>

                <input type="radio" id="icono3" name="icono3" value="icono3" class="radio-icon"
                    onchange="showform('video-conferencia')" style="margin-right: 4rem;margin-left: 4rem;">
                <?php echo IconosRecur3('planificacion-semanal-recursos/create.png'); ?>

                <input type="radio" id="icono4" name="icono4" value="icono4" class="radio-icon"
                    onchange="showform('texto')" style="margin-right: 4rem;margin-left: 4rem;">
                <?php echo IconosRecur4('planificacion-semanal-recursos/create.png'); ?>
            </div>

            <!-- FORMULARIO DE AGG ARCHIVO -->

            <form method="POST" action="procesar.php">

                <!-- SUBIR ARCHIVO -->
                <div class="formulario" id="formularioArchivo" style="display: none;">
                    <label id="archivos-subidos">
                        <h5>Agregar un Archivo:</h5>
                    </label>
                    <br>
                    <div id="campo-archivo">
                        <input type="file" id="documento" name="documento">
                    </div>
                    <br>

                    <!-- <input type="submit" value="Enviar" class="btn-submit"> -->
                    <button class="btn-borr-arch" type="button" id="borrar-archivos">Borrar todos los archivos</button>
                    <br>

                </div>
                <!-- FIN SUBIR ARCHIVO -->

                <!-- URL -->
                <!-- <hr> -->
                <div class="formulario" id="formularioUrl" style="margin-top: 1.3rem; display: none;">
                    <label for="url">
                        <h5>Dirección de la web:</h5>
                    </label>
                    <input style="border-radius: 10px;" type="url" id="url" name="url" class="form-input">
                </div>
                <!-- FIN URL -->

                <!-- Video-Conferencia -->
                <!-- <hr> -->
                <div class="formulario" id="formularioVideo" style="display: none;">
                    <label for="fecha">
                        <h5>URL de la Reunión:</h5>
                    </label>
                    <input style="border-radius: 10px;" type="url" name="video-conferencia" class="form-input">
                </div>
                <!-- fin de Video-Conferencia -->

                <!-- Descripción General -->
                <!-- <hr> -->
                <div class="formulario" id="formularioDescripcion" style="display: none;margin-top: 1.4rem;">
                    <label for="url">
                        <h5>Descripción General:</h5>
                    </label>
                    <textarea name="texto" id="texto"></textarea>
                </div>
                <!-- fin Descripción General -->
            </form>

            <div class="" style="margin-top: 1rem;">
                <?= Html::submitButton('Guardar', ['class' => 'btn btn-success', 'style' => 'background-color: #4caf50;  border: none; padding: 9px']) ?>
            </div>

            <!-- FIN FORMULARIO DE AGG ARCHIVO -->
        </div>
    </div>

    <?= Html::endForm() ?>
    <!-- FIN DE FORMULARIO -->
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['documento'])) {
        $file = $_FILES['documento'];

        if ($file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'ruta/directorio/destino'; // Poner la ruta para subir archivos OJO!
            $filename = $file['name'];
            $tempPath = $file['tmp_name'];

            $destination = $uploadDir . '/' . $filename;

            move_uploaded_file($tempPath, $destination);
            echo 'El archivo se cargó correctamente.';
        } else {
            echo 'Error al cargar el archivo: ' . $file['error'];
        }
    }
}
?>

<!-- CONTAR,ELIMINAR ARCHIVOS, CK EDITOR -->

<script>
    ClassicEditor
        .create(document.querySelector('#texto'))
        .then(editor => {
            console.log(editor);
        })
        .catch(error => {
            console.error(error);
        });

    var borrarArchivosBtn = document.getElementById('borrar-archivos');

    borrarArchivosBtn.addEventListener('click', function () {
        var archivosSubidos = document.getElementById('archivos-subidos');
        archivosSubidos.innerHTML = '';
    });

    var archivoInput = document.getElementById('documento');
    archivoInput.addEventListener('change', function () {
        var archivosSubidos = document.getElementById('archivos-subidos');
        var contadorArchivos = archivosSubidos.getElementsByTagName('li').length;
        var archivo = this.files[0];
        var archivoNombre = archivo.name;
        var nuevoArchivoHTML = document.createElement('li');
        nuevoArchivoHTML.style.textAlign = "center";
        nuevoArchivoHTML.style.display = "flex";
        nuevoArchivoHTML.style.alignItems = "center";
        nuevoArchivoHTML.innerHTML = '<span class="card">' + archivoNombre + '</span>' + '<button style="border-radius: 10px;margin-left:10px" onclick="eliminarArchivo(this)" class="btn-eliminar-archivo"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash-x-filled" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 6a1 1 0 0 1 .117 1.993l-.117 .007h-.081l-.919 11a3 3 0 0 1 -2.824 2.995l-.176 .005h-8c-1.598 0 -2.904 -1.249 -2.992 -2.75l-.005 -.167l-.923 -11.083h-.08a1 1 0 0 1 -.117 -1.993l.117 -.007h16zm-9.489 5.14a1 1 0 0 0 -1.218 1.567l1.292 1.293l-1.292 1.293l-.083 .094a1 1 0 0 0 1.497 1.32l1.293 -1.292l1.293 1.292l.094 .083a1 1 0 0 0 1.32 -1.497l-1.292 -1.293l1.292 -1.293l.083 -.094a1 1 0 0 0 -1.497 -1.32l-1.293 1.292l-1.293 -1.292l-.094 -.083z" stroke-width="0" fill="currentColor" /><path d="M14 2a2 2 0 0 1 2 2a1 1 0 0 1 -1.993 .117l-.007 -.117h-4l-.007 .117a1 1 0 0 1 -1.993 -.117a2 2 0 0 1 1.85 -1.995l.15 -.005h4z" stroke-width="0" fill="currentColor" /></svg></button>';
        archivosSubidos.appendChild(nuevoArchivoHTML);
    });

    function eliminarArchivo(btn) {
        var archivo = btn.parentNode;
        archivo.parentNode.removeChild(archivo);
    }
</script>

<!-- COMPORTAMIENTO DE RADIO BUTTONS -->

<script>
    var icono1 = document.getElementById('icono1');
    var icono2 = document.getElementById('icono2');
    var icono3 = document.getElementById('icono3');
    var icono4 = document.getElementById('icono4');

    var formularioArchivo = document.getElementById('formularioArchivo');
    var formularioUrl = document.getElementById('formularioUrl');
    var formularioVideo = document.getElementById('formularioVideo');
    var formularioDescripcion = document.getElementById('formularioDescripcion');

    icono1.addEventListener('click', function () {
        if (formularioArchivo.style.display === 'block') {
            formularioArchivo.style.display = 'none';
            icono1.checked = false;
        } else {
            formularioArchivo.style.display = 'block';
            formularioUrl.style.display = 'none';
            formularioVideo.style.display = 'none';
            formularioDescripcion.style.display = 'none';
            icono2.checked = false;
            icono3.checked = false;
            icono4.checked = false;
        }
    });

    icono2.addEventListener('click', function () {
        if (formularioUrl.style.display === 'block') {
            formularioUrl.style.display = 'none';
            icono2.checked = false;
        } else {
            formularioArchivo.style.display = 'none';
            formularioUrl.style.display = 'block';
            formularioVideo.style.display = 'none';
            formularioDescripcion.style.display = 'none';
            icono1.checked = false;
            icono3.checked = false;
            icono4.checked = false;
        }
    });

    icono3.addEventListener('click', function () {
        if (formularioVideo.style.display === 'block') {
            formularioVideo.style.display = 'none';
            icono3.checked = false;
        } else {
            formularioArchivo.style.display = 'none';
            formularioUrl.style.display = 'none';
            formularioVideo.style.display = 'block';
            formularioDescripcion.style.display = 'none';
            icono1.checked = false;
            icono2.checked = false;
            icono4.checked = false;
        }
    });

    icono4.addEventListener('click', function () {
        if (formularioDescripcion.style.display === 'block') {
            formularioDescripcion.style.display = 'none';
            icono4.checked = false;
        } else {
            formularioArchivo.style.display = 'none';
            formularioUrl.style.display = 'none';
            formularioVideo.style.display = 'none';
            formularioDescripcion.style.display = 'block';
            icono1.checked = false;
            icono2.checked = false;
            icono3.checked = false;
        }
    });

    function showForm(formulario) {
        if (formulario === 'archivo') {
            icono1.click();
        } else if (formulario === 'url') {
            icono2.click();
        } else if (formulario === 'video') {
            icono3.click();
        } else if (formulario === 'descripcion') {
            icono4.click();
        }
    }
</script>

<!-- BANDERA -->
<script>

    function showform(bandera) {
        $('#input-bandera').val(bandera);
    }

</script>

<!-- FUNCION DE ICONOS -->

<?php

function IconosRecur1($file)
{
    return Html::a('<span class="badge rounded-pill" style="background-color: #ff9e18">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-upload" width="36" height="36" viewBox="0 0 24 24" stroke-width="2" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
    <path d="M12 11v6" />
    <path d="M9.5 13.5l2.5 -2.5l2.5 2.5" />
    </svg></span>',
        '#',
        ['title' => 'Agregar Archivo']

    );
}

function IconosRecur2($link)
{
    return Html::a('<span class="badge rounded-pill" style="background-color: #ab0a3d">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-world-share" width="36" height="36" viewBox="0 0 24 24" stroke-width="2" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
        <path d="M20.94 13.045a9 9 0 1 0 -8.953 7.955" />
        <path d="M3.6 9h16.8" />
        <path d="M3.6 15h9.4" />
        <path d="M11.5 3a17 17 0 0 0 0 18" />
        <path d="M12.5 3a16.991 16.991 0 0 1 2.529 10.294" />
        <path d="M16 22l5 -5" />
        <path d="M21 21.5v-4.5h-4.5" />
        </svg></span>',
        '#',
        ['title' => 'Agregar Link/Url']

    );
}

function IconosRecur3($video)
{
    return Html::a('<span class="badge rounded-pill" style="background-color: #9e28b5">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-video-plus" width="36" height="36" viewBox="0 0 24 24" stroke-width="2" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
    <path d="M15 10l4.553 -2.276a1 1 0 0 1 1.447 .894v6.764a1 1 0 0 1 -1.447 .894l-4.553 -2.276v-4z" />
    <path d="M3 6m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z" />
    <path d="M7 12l4 0" />
    <path d="M9 10l0 4" />
    </svg></span>',
        '#',
        ['title' => 'Agregar Video-Conferencia']

    );
}

function IconosRecur4($texto)
{
    return Html::a('<span class="badge rounded-pill" style="background-color: #0a1f8f">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-text-size" width="36" height="36" viewBox="0 0 24 24" stroke-width="2" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
    <path d="M3 7v-2h13v2" />
    <path d="M10 5v14" />
    <path d="M12 19h-4" />
    <path d="M15 13v-1h6v1" />
    <path d="M18 12v7" />
    <path d="M17 19h2" />
    </svg></span>',
        '#',
        ['title' => 'Agregar Texto']


    );
}
?>