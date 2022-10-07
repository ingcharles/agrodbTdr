<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';

$conexion = new Conexion();
$cro = new ControladorRegistroOperador();

$idOperacion = $_POST['id'];
//$identificadorOperador = $_SESSION['usuario'];

$qOperacion = $cro->abrirOperacionXid($conexion, $idOperacion);
$operacion = pg_fetch_assoc($qOperacion);

$idOperadorTipoOperacion = $operacion['id_operador_tipo_operacion'];
$idHistorialOperacion = $operacion['id_historial_operacion'];

$qInformacionColmenar = $cro->verificarRegistroInformacionColmenar($conexion, $idOperacion, $idOperadorTipoOperacion);

if (pg_num_rows($qInformacionColmenar) > 0){
    
    $informacionColmenar = pg_fetch_assoc($qInformacionColmenar);
    
    $duenioSitioColmenar = $informacionColmenar['duenio_sitio_colmenar'];
    $numeroColmenar = $informacionColmenar['numero_colmenar'];
    $numeroPromedioColmenas = $informacionColmenar['numero_promedio_colmenas'];
        
}

?>

<header>
	<h1>Declarar información de colmenares</h1>
</header>

<div id="estado"></div>

<form id="declararInformacionColmenar" data-rutaAplicacion="registroOperador" data-opcion="guardarDeclararInformacionColmenar" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" class="idOperacion" name="idOperacion" value="<?php echo $idOperacion;?>" />
	<input type="hidden" id="idOperadorTipoOperacion" name="idOperadorTipoOperacion" value="<?php echo $idOperadorTipoOperacion;?>" />
	<input type="hidden" id="idHistorialOperacion" name="idHistorialOperacion" value="<?php echo $idHistorialOperacion;?>" />
	<fieldset>
		<legend>Información de los colmenares</legend>		
		<div data-linea="1">			
			<label>¿El oper. es dueño del Sitio? </label>
		</div>
		<div data-linea="1">			
			<label>SI </label> <input type="radio" id="duenioSitioSi" name="duenioSitio" value="SI" checked >
		</div>
		<div data-linea="1">			
			<label>NO </label> <input type="radio" id="duenioSitioNo" name="duenioSitio" value="NO" >
		</div>
		<hr/>
		<div data-linea="2">			
			<label>Número de colmenares: </label> <input type="number" id="numeroColmenares" name="numeroColmenares" value="<?php echo $numeroColmenar; ?>" min="1" onkeypress="ValidaSoloNumeros()" onpaste="return false" >
		</div>
		<div data-linea="3">			
			<label>Número promedio de colmenas: </label> <input type="number" id="numeroPromedioColmenas" name="numeroPromedioColmenas" value="<?php echo $numeroPromedioColmenas; ?>" min="1" onkeypress="ValidaSoloNumeros()" onpaste="return false" >
		</div>
	</fieldset>
	<button type="submit" id="btnGuardar" name="btnGuardar" class="guardar">Guardar</button>
</form>

<script type="text/javascript">

$('input:radio[name="duenioSitio"]').filter('[value="<?php echo $duenioSitioColmenar; ?>"]').prop("checked", true);

	$(document).ready(function(){
		distribuirLineas();
	});

	function ValidaSoloNumeros() {
		 if ((event.keyCode < 48) || (event.keyCode > 57))
		  event.returnValue = false;
	}

	$("#declararInformacionColmenar").submit(function(event){
		
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;		

		if($.trim($("#numeroColmenares").val()) == "" || $("#numeroColmenares").val() == 0){
			error = true;
			$("#numeroColmenares").addClass("alertaCombo");
		}	

		if($.trim($("#numeroPromedioColmenas").val()) == "" || $("#numeroPromedioColmenas").val() == 0){
			error = true;
			$("#numeroPromedioColmenas").addClass("alertaCombo");
		}	
		
		if (!error){
			ejecutarJson(this);
			$("#btnGuardar").attr("disabled", "disabled");
		}else{
			$("#estado").html("Por favor verifique la información ingresada.").addClass("alerta");
		}
		
	});


</script>