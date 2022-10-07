<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';


$conexion = new Conexion();
$cv = new controladorVehiculos();

//Identificador Usuario Administrador o Apoyo de Transportes
if($_SESSION['usuario'] != '' && $_SESSION['usuario']!=$mantenimiento['identificador_registro']){
	$identificadorUsuarioRegistro = $_SESSION['usuario'];
}else if($_SESSION['usuario'] != '' && $_SESSION['usuario']==$mantenimiento['identificador_registro']){
	$identificadorUsuarioRegistro = $mantenimiento['identificador_registro'];
}else{
	$identificadorUsuarioRegistro = '';
}

$lavada=explode(",",$_POST['elementos']);

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<header>
	<h1>Liquidar ordenes de lavado</h1>
</header>



<div id="estado"></div>

	<p>Las <b>órdenes</b> a ser liquidadas son: </p>
 
            <?php
	
			for ($i = 0; $i < count ($lavada); $i++) {
				$res = $cv->abrirMantenimiento($conexion, $lavada[$i]);
				$mantenimiento = pg_fetch_assoc($res);

				
				echo'<fieldset>
						<legend>Orden N° </label>' .$mantenimiento['id_mantenimiento'].'</legend>
				
						<div><label>Vehículo: </label>' .$mantenimiento['marca'].' - '.$mantenimiento['modelo'].' - '.$mantenimiento['tipo'].'</div>
						<div><label>Placa: </label>' .$mantenimiento['placa'].'</div>
						<div><label>Fecha solicitud: </label>'. date('j/n/Y (G:i)',strtotime($mantenimiento['fecha_solicitud'])).'</div>
			
				</fieldset>';
			}
			
			?>
  
  <fieldset id="subirFactura">
	<legend>Subir factura</legend>
		<form id="subirArchivo" action="aplicaciones/transportes/subirArchivo.php" method="post" enctype="multipart/form-data" target="ventanaEmergente" onsubmit="window.open('', this.target, 'width=250,height=250,resizable,scrollbars=yes');">
				<input type="file" name="archivo" id="archivo" accept="application/pdf"/>
				
				<?php 
					for ($i = 0; $i < count ($lavada); $i++) {
						echo'<input type="hidden" name="id[]" value="'.$lavada[$i].'"/>';
					}
				?> 

				<button type="submit" name="boton" value="lavado" disabled="disabled" class="adjunto" >Subir Archivo</button>
		</form>
	<iframe name="ventanaEmergente" class="ventanaEmergente"></iframe>
</fieldset>

<form id="datosLiquidacion" data-rutaAplicacion="transportes" data-opcion="finalizarLavado" data-accionEnExito="ACTUALIZAR" >

	<input type='hidden' id='identificadorUsuarioRegistro' name='identificadorUsuarioRegistro' value="<?php echo $identificadorUsuarioRegistro;?>" />
		
	<fieldset>
		<legend>Información de liquidación de factura</legend>
		
		<div data-linea="1">
			
			<label>N° factura</label>
				<input type="text" name="numeroFactura" required="required" placeholder="Ej: 123456789"> 
				
		</div><div data-linea="1">
				
			<label>Valor Total</label> 
				<input type="number" step="0.01" name="valorTotal"  id="valorTotal" required="required" placeholder="Ej: 12345"/>
				
		</div>
				
			<?php 
					for ($i = 0; $i < count ($lavada); $i++) {
						echo'<input type="hidden" name="id[]" value="'.$lavada[$i].'"/>';
					}
				?>  
				
	</fieldset>
			
	<button id="detalle" type="submit" class="guardar" >Guardar liquidación de lavado</button>
	
</form>

</body>

<script type="text/javascript">

	var array_lavado= <?php echo json_encode($lavada); ?>;

	$("#archivo").click(function(){
		$("#subirArchivo button").removeAttr("disabled");});

	$("#datosLiquidacion").submit(function(event){
			event.preventDefault();

			$(".alertaCombo").removeClass("alertaCombo");
			var error = false;

			if(Number($("#valorTotal").val()) <= "0" ){
				error = true;
				$("#valorTotal").addClass("alertaCombo");
			}

			if(!error)
				ejecutarJson($(this));
	});

	$(document).ready(function(){
		distribuirLineas();
		if(array_lavado == ''){
			$("#detalleItem").html('<div class="mensajeInicial">Seleccione una o varias ordenes de lavado y a continuación presione el boton liquidar.</div>');
		}
		
	});
	
	
</script>

</html>
