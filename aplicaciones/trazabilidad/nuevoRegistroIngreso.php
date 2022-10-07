<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorTrazabilidad.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';

$conexion = new Conexion();
$ct = new ControladorTrazabilidad();
$cc = new ControladorCatalogos();
$cr = new ControladorRegistroOperador();

$identificador = $_SESSION['usuario'];

$unidades = $cc->listarUnidadesMedidaXTipo($conexion, 'Peso');
$bultos = $cc->ListarBultos($conexion);

$proveedores = $cr->listarNombresProveedoresOperador($conexion, $identificador);

$variedades = $cc->ListarVariedades($conexion);
$calidades = $cc->ListarCalidades($conexion);


while ($fila = pg_fetch_assoc($calidades)) {
    $calidadesProd[] = array(id_calidad_producto => $fila['id_calidad_producto'], nombre => $fila['nombre'], id_variedad_producto => $fila['id_variedad_producto']);
}


?>

<header>
	<h1>Nuevo Registro Ingreso</h1>
</header>



<form id='nuevoIngreso' data-rutaAplicacion='trazabilidad' data-opcion='opcionesTrazabilidad' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">

	<input type="hidden" id="opcion" name="opcion" />
	<input type="hidden" id="identificador" name="identificador" value="<?php echo $identificador; ?>" />
	<div id="estado"></div>
	<fieldset>
		<legend>Datos Proveedor</legend>

		<div data-linea="1">

			<label>Código Proveedor</label> 
				<select id="codproveedor" name="codproveedor">
					<option value="" selected="selected">Código Proveedor....</option>
                    <?php
                    while ($fila = pg_fetch_assoc($proveedores)) {
                        echo '<option value="' . $fila['codigo_proveedor'] . '" data-razonSocial="' . $fila['razon_social'] . '">' . $fila['codigo_proveedor'] . '</option>';
                    }
                    ?>
			</select>

		</div>
		
		<div data-linea="1">
			<label>Proveedor</label> 
				<input type="text" id="proveedor" name="proveedor" disabled="disabled" />
		</div>
	</fieldset>

	<fieldset>
		<legend>Área y producto de egreso</legend>

		<div data-linea="1">
			<div id="dSitio"></div>
			<input type="hidden" id="nombreSitioProveedor" name="nombreSitioProveedor"/>
		</div>

		<div data-linea="1">
			<div id="dArea"></div>
			<input type="hidden" id="nombreAreaProveedor" name="nombreAreaProveedor"/>

		</div>

		<div data-linea="2">
			<div id="dProducto"></div>
			<input type="hidden" id="nombreProducto" name="nombreProducto"/>
		</div>
		
		<div data-linea="4">
			<div id="dOperacionProveedor"></div>
			<input type="hidden" id="nombreOperacionProveedor" name="nombreOperacionProveedor"/>
		</div>

		<div data-linea="3">
			<label>Variedad</label> <select id="variedad" name="variedad">
				<option value="" selected="selected">Variedad....</option>
                <?php
                while ($fila = pg_fetch_assoc($variedades)) {
                    echo '<option value="' . $fila['id_variedad_producto'] . '" >' . $fila['nombre'] . '</option>';
                }
                ?>
			</select>
		</div>

		<div data-linea="3">
			<label>Calidad</label> <select id="calidad" name="calidad" disabled="disabled">
				<option value="" selected="selected">Calidad....</option>
			</select>
		</div>
	</fieldset>

    <fieldset>
		<legend>Área de ingreso</legend>
		
		<div data-linea="3">
			<div id="dAreaOperador"></div>
			<input type="hidden" id="nombreAreaOperador" name="nombreAreaOperador"/>
		</div>
		
		<div data-linea="4">
			<div id="dOperacionOperador"></div>
			<input type="hidden" id="nombreOperacionOperador" name="nombreOperacionOperador"/>
		</div>

     </fieldset>

	<fieldset>
		<legend>Detalle Ingreso</legend>

		<div data-linea="1">

			<label>Cantidad de Producto</label> 
				<input id="cantidad" type="text" name="cantidad" placeholder="Ej: 28.5" data-er="^[0-9]+(\.[0-9]{1,3})?$" />

		</div>
		<div data-linea="1">

			<label>Unidad de Medida</label> <select id="unidadmedida"
                name="unidadmedida">
				<option value="" selected="selected">Unidad....</option>
                <?php
                while ($fila = pg_fetch_assoc($unidades)) {
                    echo '<option value="' . $fila['id_unidad_medida'] . '" >' . $fila['nombre'] . '</option>';
                }
                ?>
			</select>

		</div>
		<div data-linea="2">

			<label>Número de Bultos</label> 
				<input type="text" id="bultos" name="bultos" placeholder="Ej: 28" data-er="^[0-9]+(\.[0-9]{1,3})?$" />


		</div>
		<div data-linea="2">
			<label>Descripción de Bultos</label> <select id="tipo" name="tipo">
				<option value="" selected="selected">Bultos....</option>
                <?php
                while ($fila = pg_fetch_assoc($bultos)) {
                    echo '<option value="' . $fila['id_descripcion_bultos'] . '" >' . $fila['nombre'] . '</option>';
                }
                ?>
			</select>
		</div>

	</fieldset>

	<button type="submit" class="guardar">Guardar Registro</button>
</form>
<script type="text/javascript">

function esCampoValido(elemento) {
    var patron = new RegExp($(elemento).attr("data-er"), "g");
    return patron.test($(elemento).val());
}

$("#nuevoIngreso").submit(function (event) {

    $(".alertaCombo").removeClass("alertaCombo");
    var error = false;

    if (!$.trim($("#codproveedor").val())) {
        error = true;
        $("#codproveedor").addClass("alertaCombo");
    }

    if (!$.trim($("#sitio").val())) {
        error = true;
        $("#sitio").addClass("alertaCombo");
    }

    if (!$.trim($("#area").val())) {
        error = true;
        $("#area").addClass("alertaCombo");
    }

    if (!$.trim($("#producto").val())) {
        error = true;
        $("#producto").addClass("alertaCombo");
    }

    if (!$.trim($("#operacionProveedor").val())) {
        error = true;
        $("#operacionProveedor").addClass("alertaCombo");
    }

    if (!$.trim($("#areaOperador").val())) {
        error = true;
        $("#areaOperador").addClass("alertaCombo");
    }

    if (!$.trim($("#operacionOperador").val())) {
        error = true;
        $("#operacionOperador").addClass("alertaCombo");
    }

    if (!$.trim($("#unidadmedida").val())) {
        error = true;
        $("#unidadmedida").addClass("alertaCombo");
    }

    if (!$.trim($("#tipo").val())) {
        error = true;
        $("#tipo").addClass("alertaCombo");
    }

    if (!$.trim($("#cantidad").val()) || !esCampoValido("#cantidad")) {
        error = true;
        $("#cantidad").addClass("alertaCombo");
    }

    if (!$.trim($("#bultos").val()) || !esCampoValido("#bultos")) {
        error = true;
        $("#bultos").addClass("alertaCombo");
    }

    if (!$.trim($("#variedad").val())) {
        error = true;
        $("#variedad").addClass("alertaCombo");
    }

    if (!$.trim($("#calidad").val())) {
        error = true;
        $("#calidad").addClass("alertaCombo");
    }

    if (error == true) {
        $("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
        event.preventDefault();
    } else {
        $("#estado").html("").removeClass('alerta');

        $("#nuevoIngreso").attr('data-opcion', 'guardarNuevoRegistroIngreso');
        $("#nuevoIngreso").attr('data-destino', 'detalleItem');

        abrir($(this), event, false);
    }
});


$("#codproveedor").change(function (event) {

    $("#proveedor").val($('#codproveedor option:selected').attr('data-razonSocial'));
    $("#nuevoIngreso").attr('data-destino', 'dSitio');
    $("#opcion").val('sitio');

    abrir($("#nuevoIngreso"), event, false); //Se ejecuta ajax, busqueda de sitios

});


var array_calidades = <?php echo json_encode($calidadesProd); ?>;

$("#variedad").change(function () {
    scalidad = '0';
    scalidad = '<option value="">Calidad...</option>';
    for (var i = 0; i < array_calidades.length; i++) {
        if ($("#variedad").val() == array_calidades[i]['id_variedad_producto']) {
            scalidad += '<option value="' + array_calidades [i]['id_calidad_producto'] + '">' + array_calidades[i]['nombre'] + '</option>';
        }
    }
    $('#calidad').html(scalidad);
    $('#calidad').removeAttr("disabled");
});


function esCampoValido(elemento) {
    var patron = new RegExp($(elemento).attr("data-er"), "g");
    return patron.test($(elemento).val());
}


$(document).ready(function () {
    distribuirLineas();
});

</script>


