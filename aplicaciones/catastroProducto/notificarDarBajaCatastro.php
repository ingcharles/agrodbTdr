<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastroProducto.php';

$conexion = new Conexion();
$ccp = new ControladorCatastroProducto();
$idCatastro = $_POST['elementos'];
?>

<header>
	<h1>Confirmar dar de baja</h1>
</header>
<div id="estado"></div>
 
<form id="notificarEliminarCatastro" data-rutaAplicacion="catastroProducto" data-opcion="darBajaCatastro" data-accionEnExito="ACTUALIZAR" >
	<input type="hidden" name="idCatastro" value="<?php echo $idCatastro;?>"/>
	
	<p>El <b>registro de catastro</b> a dar de baja es: </p>
	
	<fieldset>
		<legend>Motivos para dar de baja</legend>
		<?php
			$idCatastro = $_POST['elementos'];
			if ($idCatastro!=''){
				$qCatastro=$ccp->abrirCatatroIndividualProducto($conexion, $idCatastro);
				$filaCatastro = pg_fetch_assoc($qCatastro);
				echo '<div data-linea="1" >' .$filaCatastro['nombre_area'].' - ' .$filaCatastro['nombre_producto'].'</div>';
			}
		?>				
		<p>
		<div data-linea="2">
			<label>Motivo: </label> 
			<select id="conceptoCatastro" name="conceptoCatastro">
				<option value="0">Seleccione...</option>
				<?php
					$qConceptoCatastro = $ccp-> listaConceptoCatastroDarDeBaja($conexion,'dar de baja');
					while ($fila = pg_fetch_assoc($qConceptoCatastro)){
					//	if($fila['codigo']=='MUER' || $fila['codigo']=='DESA' || $fila['codigo']=='AUTO' || $fila['codigo']=='SASA')
					    	echo '<option value="' . $fila['id_concepto_catastro'] . '">' . $fila['nombre_concepto'] . '</option>';
					}
			
				?>						
			</select>	
		</div>
	</fieldset>			
	
	 <button id="eliminar" type="submit" class="eliminar" >Dar de baja catastro</button>	
</form>

<script type="text/javascript">
	var idCatastro= <?php echo json_encode($idCatastro); ?>;
	
	$(document).ready(function(event){
		
		if(idCatastro == '')
			$("#detalleItem").html('<div class="mensajeInicial">Seleccione un registro de catastro a dar de baja.</div>');

		construirValidador();
		distribuirLineas();
	});
	
	$("#notificarEliminarCatastro").submit(function(event){

		event.preventDefault();	
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		
		if($("#conceptoCatastro").val()==0){
			 error = true;
			$("#conceptoCatastro").focus();
		    $("#conceptoCatastro").addClass("alertaCombo");
		    $("#estado").html('Por favor seleccione el motivo por el cual va a dar de baja.').addClass("alerta");
		}

		if (!error){
			ejecutarJson($(this));
			$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
			
		}  
	});	
</script>