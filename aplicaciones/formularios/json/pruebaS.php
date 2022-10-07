<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sistema GUIA</title>
    <!-- link
        href='http://fonts.googleapis.com/css?family=Text+Me+One|Poiret+One|Open+Sans'	rel='stylesheet' type='text/css'-->
    <script src="../../../aplicaciones/general/funciones/agrdbfunc.js" type="text/javascript"></script>
    <script src="../../../aplicaciones/general/funciones/jquery-1.9.1.js" type="text/javascript"></script>
    <script src="https://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>

</head>

<body>
<form data-destino="destino" data-rutaAplicacion="../../../formularios/json">
    <table>
        <tr>
            <td>
                Provincia
            </td>
            <td>
                <input type="text" name="parametroBusqueda" value="pichincha"/>
            </td>
            <td>
                <select id="pagina">
                    <option value="obtenerOperacionesPorProvincia">Operaciones</option>
                </select>
            </td>
            <td>
                <button type="submit">Traer</button>
            </td>
        </tr>
    </table>
</form>
<div id="estado">estado</div>
<pre>
    <div id="destino">destino</div>
</pre>

</body>
<script>
    $("form").submit(function (e) {
        e.preventDefault();
        $("#estado").html("consultando...");
        $("#destino").html("porcesando...");
        $(this).attr("data-opcion", $("#pagina").val());
        ejecutarJson($(this), new exito());
    });

    function exito() {
        this.ejecutar = function (msg) {
            $("#estado").html("fin.");
            //alert(msg.mensaje)
            $("#destino").html(JSON.stringify(msg.mensaje, null, 2));
        }
    }
</script>