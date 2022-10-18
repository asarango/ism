<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">


<!--inicio de Diseñar experiencias de aprendizaje-->
<p class="text-primero"><b><u>Diseñar experiencias de aprendizaje interesantes:</u></b></p>

<div class="card p-2" style="border-color: #ff9e18">
    <ul>
        <?php
        foreach ($semanas as $semana) {
            echo '<li><b>' . $semana['nombre_semana'] . '</b></li>';
            echo '<ul>';
            foreach ($planesSemanales as $exp) {
                echo '<li><b>' . $exp['titulo'] . '</b><br>';
                echo $exp['indicaciones'];
                echo '</li>';
            }
            echo '</ul>';
        }
        ?>
    </ul>
</div>


<!--fin de experiencias de aprendizaje-->

<hr>
<br>
<!--inicio de apoyo a la agencia de los alumnos-->
<p class="text-primero"><b><u>Apoyo a la agencia de los alumnos:</u></b></p>
<div class="form-group">
    <div id="editor-agencia">
        <?php


        foreach ($registros as $reg) {
            if ($reg->tipo == 'agencia_alumnos') {
                echo $reg->contenido_texto;
                $registroId = $reg->id;
            }
        }
        ?>
    </div>

    <button type="submit" class="btn btn-outline-warning" style="margin-top: 10px" onclick="grabarAgencia(<?= $registroId ?>)">
        Guardar
    </button>
</div>
<!--fin de apoyo a la agencia de los alumnos-->


<hr>
<br>
<!--inicio de preguntas de los maestros y los alumnos-->
<p class="text-primero"><b><u>Preguntas de los maestros y los alumnos:</u></b></p>
<div class="form-group">
    <div id="editor-preguntasal">
        <?php
        foreach ($registros as $reg) {
            if ($reg->tipo == 'preguntas_mestros_alumnos') {
                echo $reg->contenido_texto;
                $registroId = $reg->id;
            }
        }
        ?>
    </div>

    <button type="submit" class="btn btn-outline-warning" style="margin-top: 10px" onclick="grabarPreguntasAl(<?= $registroId ?>)">
        Guardar
    </button>
</div>
<!--fin de apoyo a la agencia de los alumnos-->

<hr>
<br>
<!--inicio de Evaluación continua-->
<p class="text-primero"><b><u>Evaluación continua:</u></b></p>
<div class="card p-2" style="border-color: #ff9e18">
    <ul>
        <?php
        foreach ($semanas as $semana) {
            echo '<li><b>' . $semana['nombre_semana'] . '</b></li>';
            echo '<ul>';
            foreach ($planesSemanales as $exp) {
                echo '<li><b>' . $exp['titulo'] . '</b><br>';
                echo $exp['indicaciones'];
                echo '</li>';
            }
            echo '</ul>';
        }
        ?>
    </ul>
</div>

<!--fin de evaluación continua-->


<hr>
<br>
<!--inicio de Hacer uso flexible de los recursos-->
<p class="text-primero"><b><u>Hacer uso flexible de los recursos:</u></b></p>
<div class="form-group">
    <div id="editor-recursos">
        <?php
        foreach ($registros as $reg) {
            if ($reg->tipo == 'recursos') {
                echo $reg->contenido_texto;
                $registroId = $reg->id;
            }
        }
        ?>
    </div>

    <button type="submit" class="btn btn-outline-warning" style="margin-top: 10px" onclick="grabarRecursos(<?= $registroId ?>)">
        Guardar
    </button>
</div>
<!--fin de Hacer uso flexible de los recursos-->



<hr>
<br>
<!--inicio de Autoevaluación de los alumnos y comentarios de compañeros-->
<p class="text-primero"><b><u>Autoevaluación de los alumnos y comentarios de compañeros:</u></b></p>
<div class="form-group">
    <div id="editor-autoevaluacion">
        <?php
        foreach ($registros as $reg) {
            if ($reg->tipo == 'autoevaluacion') {
                echo $reg->contenido_texto;
                $registroId = $reg->id;
            }
        }
        ?>
    </div>

    <button type="submit" class="btn btn-outline-warning" style="margin-top: 10px" onclick="grabarAutoeval(<?= $registroId ?>)">
        Guardar
    </button>
</div>
<!--fin de Hacer uso flexible de los recursos-->

<hr>
<br>
<!--inicio de Reflexi[on continua de todos los maestros-->
<p class="text-primero"><b><u>Reflexión continua de todos los maestros:</u></b></p>
<div class="form-group">
    <div id="editor-continua">
        <?php
        foreach ($registros as $reg) {
            if ($reg->tipo == 'evaluacion_maestros') {
                echo $reg->contenido_texto;
                $registroId = $reg->id;
            }
        }
        ?>
    </div>

    <button type="submit" class="btn btn-outline-warning" style="margin-top: 10px" onclick="grabarContinua(<?= $registroId ?>)">
        Guardar
    </button>
</div>
<!--fin de Reflexi[on continua de todos los maestros-->


<hr>
<br>
<!--inicio de Reflexiones adicionales específicas de una asignatura-->
<p class="text-primero"><b><u>Reflexiones adicionales específicas de una asignatura:</u></b></p>
<div class="form-group">
    <div id="editor-adicionales">
        <?php
        foreach ($registros as $reg) {
            if ($reg->tipo == 'adicionales') {
                echo $reg->contenido_texto;
                $registroId = $reg->id;
            }
        }
        ?>
    </div>

    <button type="submit" class="btn btn-outline-warning" style="margin-top: 10px" onclick="grabarAdicionales(<?= $registroId ?>)">
        Guardar
    </button>
</div>
<!--fin de Reflexiones adicionales específicas de una asignatura-->


<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    var toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'], // toggled buttons
        ['blockquote', 'code-block'],

        [{
            'header': 1
        }, {
            'header': 2
        }], // custom button values
        [{
            'list': 'ordered'
        }, {
            'list': 'bullet'
        }],
        [{
            'script': 'sub'
        }, {
            'script': 'super'
        }], // superscript/subscript
        [{
            'indent': '-1'
        }, {
            'indent': '+1'
        }], // outdent/indent
        [{
            'direction': 'rtl'
        }], // text direction

        [{
            'size': ['small', false, 'large', 'huge']
        }], // custom dropdown
        [{
            'header': [1, 2, 3, 4, 5, 6, false]
        }],

        [{
            'color': []
        }, {
            'background': []
        }], // dropdown with defaults from theme
        [{
            'font': []
        }],
        [{
            'align': []
        }],

        ['clean'], // remove formatting button
        ['video'] // remove formatting button
    ];

    var quillExperiencias = new Quill('#editor-experiencias', {
        modules: {
            toolbar: toolbarOptions
        },
        theme: 'snow'
    });

    var quillAgencia = new Quill('#editor-agencia', {
        modules: {
            toolbar: toolbarOptions
        },
        theme: 'snow'
    });

    var quillPreguntasAl = new Quill('#editor-preguntasal', {
        modules: {
            toolbar: toolbarOptions
        },
        theme: 'snow'
    });

    var quillEvalCont = new Quill('#editor-eval-continua', {
        modules: {
            toolbar: toolbarOptions
        },
        theme: 'snow'
    });

    var quillRecursos = new Quill('#editor-recursos', {
        modules: {
            toolbar: toolbarOptions
        },
        theme: 'snow'
    });

    var quillAutoevaluacion = new Quill('#editor-autoevaluacion', {
        modules: {
            toolbar: toolbarOptions
        },
        theme: 'snow'
    });

    var quillContinua = new Quill('#editor-continua', {
        modules: {
            toolbar: toolbarOptions
        },
        theme: 'snow'
    });

    var quillAdicionales = new Quill('#editor-adicionales', {
        modules: {
            toolbar: toolbarOptions
        },
        theme: 'snow'
    });


    function grabarExperiencias(registroId) {
        let editorIdea = quillExperiencias.container.firstChild.innerHTML;
        let url = '<?= yii\helpers\Url::to(['update']) ?>';

        params = {
            contenido_texto: editorIdea,
            registro_id: registroId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function() {},
            success: function(resp) {
                var resultado = JSON.parse(resp);
                var estado = resultado.status;
                if (estado == 'ok') {
                    alert('Actualizado correctamente!');
                } else {
                    alert('El registro no se actualizó correctamente!');
                }
            }
        });
    }

    function grabarAgencia(registroId) {
        let editorIdea = quillAgencia.container.firstChild.innerHTML;
        let url = '<?= yii\helpers\Url::to(['update']) ?>';

        params = {
            contenido_texto: editorIdea,
            registro_id: registroId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function() {},
            success: function(resp) {
                var resultado = JSON.parse(resp);
                var estado = resultado.status;
                if (estado == 'ok') {
                    alert('Actualizado correctamente!');
                } else {
                    alert('El registro no se actualizó correctamente!');
                }
            }
        });
    }

    function grabarPreguntasAl(registroId) {
        let editorIdea = quillPreguntasAl.container.firstChild.innerHTML;
        let url = '<?= yii\helpers\Url::to(['update']) ?>';

        params = {
            contenido_texto: editorIdea,
            registro_id: registroId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function() {},
            success: function(resp) {
                var resultado = JSON.parse(resp);
                var estado = resultado.status;
                if (estado == 'ok') {
                    alert('Actualizado correctamente!');
                } else {
                    alert('El registro no se actualizó correctamente!');
                }
            }
        });
    }

    function grabarEvaluacionCon(registroId) {
        let editorIdea = quillEvalCont.container.firstChild.innerHTML;
        let url = '<?= yii\helpers\Url::to(['update']) ?>';

        params = {
            contenido_texto: editorIdea,
            registro_id: registroId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function() {},
            success: function(resp) {
                var resultado = JSON.parse(resp);
                var estado = resultado.status;
                if (estado == 'ok') {
                    alert('Actualizado correctamente!');
                } else {
                    alert('El registro no se actualizó correctamente!');
                }
            }
        });
    }

    function grabarRecursos(registroId) {
        let editorIdea = quillRecursos.container.firstChild.innerHTML;
        let url = '<?= yii\helpers\Url::to(['update']) ?>';

        params = {
            contenido_texto: editorIdea,
            registro_id: registroId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function() {},
            success: function(resp) {
                var resultado = JSON.parse(resp);
                var estado = resultado.status;
                if (estado == 'ok') {
                    alert('Actualizado correctamente!');
                } else {
                    alert('El registro no se actualizó correctamente!');
                }
            }
        });
    }

    function grabarAutoeval(registroId) {
        let editorIdea = quillAutoevaluacion.container.firstChild.innerHTML;
        let url = '<?= yii\helpers\Url::to(['update']) ?>';

        params = {
            contenido_texto: editorIdea,
            registro_id: registroId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function() {},
            success: function(resp) {
                var resultado = JSON.parse(resp);
                var estado = resultado.status;
                if (estado == 'ok') {
                    alert('Actualizado correctamente!');
                } else {
                    alert('El registro no se actualizó correctamente!');
                }
            }
        });
    }

    function grabarContinua(registroId) {
        let editorIdea = quillContinua.container.firstChild.innerHTML;
        let url = '<?= yii\helpers\Url::to(['update']) ?>';

        params = {
            contenido_texto: editorIdea,
            registro_id: registroId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function() {},
            success: function(resp) {
                var resultado = JSON.parse(resp);
                var estado = resultado.status;
                if (estado == 'ok') {
                    alert('Actualizado correctamente!');
                } else {
                    alert('El registro no se actualizó correctamente!');
                }
            }
        });
    }

    function grabarAdicionales(registroId) {
        let editorIdea = quillAdicionales.container.firstChild.innerHTML;
        let url = '<?= yii\helpers\Url::to(['update']) ?>';

        params = {
            contenido_texto: editorIdea,
            registro_id: registroId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function() {},
            success: function(resp) {
                var resultado = JSON.parse(resp);
                var estado = resultado.status;
                if (estado == 'ok') {
                    alert('Actualizado correctamente!');
                } else {
                    alert('El registro no se actualizó correctamente!');
                }
            }
        });
    }
</script>