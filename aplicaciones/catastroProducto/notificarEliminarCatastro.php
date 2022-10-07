<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastroProducto.php';

$conexion = new Conexion();
$ccp = new ControladorCatastroProducto();
?>

<header>
	<h1>Confirmar Eliminación</h1>
</header>
<div id="estado"></div>
	<p>El <b>registro de catastro</b> a ser eliminado es: </p>
	<?php
		$idCatastro = $_POST['elementos'];
		if ($idCatastro!=''){
			echo '<fieldset><legend>Catastro</legend>';
				    $qCatastro=$ccp->abrirCatatroIndividualProducto($conexion, $idCatastro);
					$filaCatastro = pg_fetch_assoc($qCatastro);
					
					$qCatastroNotificar=$ccp->notificarEliminarCatastro($conexion, $idCatastro);
					$bandera=true;
					while($filaNotificar = pg_fetch_assoc($qCatastroNotificar)){
						if($filaNotificar['estado_registro']=='eliminado' ){
							
							$qCatastroIdentificador=$ccp->verificarCatastroIdentificador($conexion, $filaNotificar['identificador_producto']);
							if(pg_num_rows($qCatastroIdentificador)>0){
								$bandera=false;
							 break;
							}
						}
						
						$qResultadoMovilizacion=$ccp->buscarIdentificadorProductoMovilizado($conexion, $filaNotificar['identificador_producto']);
						if(pg_num_rows($qResultadoMovilizacion)>0){
							$bandera=false;
							break;
						}
						
						$qResultadoVacunado=$ccp->buscarIdentificadorProductoVacunado($conexion, $filaNotificar['identificador_producto']);
						if(pg_num_rows($qResultadoVacunado)>0){
							$bandera=false;
							break;
						}
						
					}
				
					echo '<div>' .$filaCatastro['nombre_area'].' - ' .$filaCatastro['nombre_producto'].' '.($bandera?'<div id="eliminar"></div>':'<div id="nEliminar" class="alerta">No se puede eliminar porque esta en uso.</div>').'  </div>';
			echo'</fieldset>';
		}
	?>
	

<form id="notificarEliminarCatastro" data-rutaAplicacion="catastroProducto" data-opcion="eliminarCatastro" data-accionEnExito="ACTUALIZAR" >
	<input type="hidden" name="idCatastro" value="<?php echo $idCatastro;?>"/>
	<button id="eliminar" type="submit" class="eliminar" >Eliminar Catastro</button>
</form>

<script type="text/javascript">
	var array_catastro= <?php echo json_encode($idCatastro); ?>;

	$(document).ready(function(event){
		distribuirLineas();
		construirValidador();
		if(array_catastro == '')
			$("#detalleItem").html('<div class="mensajeInicial">Seleccione un registro de catastro a eliminar.</div>');
	
		if($("#nEliminar").text())
			$("#notificarEliminarCatastro").hide();
	});
	
	$("#notificarEliminarCatastro").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));
	});
	
	
</script>