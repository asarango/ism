<?php
use yii\helpers\Html;
use yii\grid\GridView;
?>
<?php
use yii\helpers\Html;
use yii\grid\GridView;

?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.11/jstree.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.11/themes/default/style.min.css"></script>

<style>
    ul {
        list-style-type: none;
        position: relative;
        padding-left: 20px;
    }

    li {
        margin-left: 20px;
        position: relative;
    }

    .node {
        cursor: pointer;
    }

    .add {
        margin-left: 10px;
    }

    /* Línea vertical entre nodos */
    li:before {
        content: '';
        position: absolute;
        top: 0;
        left: -10px;
        border-left: 1px solid #000;
        height: 100%;
    }

    /* Línea horizontal entre nodos hijos */
    li:after {
        content: '';
        position: absolute;
        top: 0;
        left: -10px;
        border-top: 1px solid #000;
        width: 10px;
    }
</style>

<div id="tree">
    <ul id="root">
        <li>
            <span class="node">
                <?= '<b>' . $planUnidad . '</b>' ?>
            </span>
            <ul>
                <li>
                    <span class="node">Nodo 1.1</span>
                    <!-- <button class="add">Agregar Hijo</button> -->
                </li>
            </ul>
            <button class="add">Agregar Hijo</button>
        </li>
    </ul>
    <button id="addRoot">Agregar Raíz</button>
</div>

<script>

    $(document).ready(function () {
        // Agregar hijo a un nodo
        $(document).on("click", ".add", function () {
            var newNodeText = prompt("Nombre del nuevo nodo:");
            if (newNodeText) {
                var newNode = $("<li><span class='node'>" + newNodeText + "</span><ul><button class='add'>Agregar Hijo</button></ul></li>");
                $(this).siblings("ul").append(newNode);
            }
        });

        // Agregar raíz
        $("#addRoot").on("click", function () {
            var newNodeText = prompt("Nombre de la nueva raíz:");
            if (newNodeText) {
                var newNode = $("<li><span class='node'>" + newNodeText + "</span><ul><button class='add'>Agregar Hijo</button></ul></li>");
                $("#root").append(newNode);
            }
        });

        // Editar nodo al hacer clic en el texto del nodo
        $(document).on("click", ".node", function () {
            var newText = prompt("Editar nodo:", $(this).text());
            if (newText) {
                $(this).text(newText);
            }
        });
    });

</script>