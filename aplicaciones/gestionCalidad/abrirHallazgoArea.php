<?php
// session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorGestionCalidad.php';
// require_once '../../clases/ControladorAuditoria.php';

$conexion = new Conexion ();
$cgc = new ControladorGestionCalidad ();
// $ca = new ControladorAuditoria();

// Validar sesion
// $conexion->verificarSesion();
$idHallazgo = htmlspecialchars ( $_POST ['id'], ENT_NOQUOTES, 'UTF-8' );
$hallazgo = pg_fetch_assoc($cgc->abrirHallazgo ( $conexion, $idHallazgo ));

$causas = $cgc->abrirCausas($conexion, $idHallazgo);
$acciones = $cgc->abrirAcciones($conexion, $idHallazgo);

$permitirModificarCausas = ($hallazgo['estado']=='Por ingresar causas')? true: false;
$permitirCalcularPriorizacion = ($hallazgo['estado']=='Por definir causa raíz')? true: false;
$permitirIngresarAcciones = ($hallazgo['estado']=='Por definir acciones')? true: false;

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Hallazgo</h1>
	</header>

	<div id="estado"></div>

	<fieldset>
		<legend>Detalle del hallazgo</legend>
		<div data-linea="0">
			<label for="area">Estado de tratamiento de hallazgo</label> <span id="estadoDeHallazgo"
				class="destacar"><?php echo $hallazgo['estado'];?></span>
		</div>
		<hr />
		<div data-linea="1">
			<label for="area">Área</label> 
			<?php echo $hallazgo['nombre'];?>
		</div>
		<div data-linea="2">
			<label for="tipo">Tipo de hallazgo</label> 
			<?php echo $hallazgo['tipo'];?>
		</div>
		<div data-linea="3">
			<label for="fecha">Fecha</label> 
			<?php echo $hallazgo['fecha_formateada'];?>
		</div>
		<hr />
		<div data-linea="4">
			<label for="hallazgo">Hallazgo detectado</label>
		</div>
		<div data-linea="5">
			<p><?php echo $hallazgo['hallazgo'];?></p>
		</div>
		<div data-linea="6">
			<label for="norma">Norma y clausula</label>
		</div>
		<div data-linea="7">
			<p><?php echo $hallazgo['norma'];?></p>
		</div>

		<hr />
		<div data-linea="9">
			<label for="auditor">Auditor</label> <?php echo $hallazgo['auditor'];?>
		</div>
		<div data-linea="10">
			<label for="auditor">Controlador</label> <?php echo $hallazgo['nombre_controlador'];?>
		</div>
	</fieldset>

	<?php 
		if($permitirModificarCausas) {
	?>
	<form id="nuevaCausa" data-rutaAplicacion="gestionCalidad"
		data-opcion="guardarCausa">
		<fieldset>
			<legend>Causas</legend>
			<input id="hallazgo" name="hallazgo" type="hidden"
				value="<?php echo $idHallazgo;?>" data-resetear="no" />
			<div data-linea="1">
				<label for="causa">Causa</label>
				<textarea name="causa" id="causa"></textarea>
			</div>
			<div>
				<button type="submit" class="mas">Añadir causa</button>
			</div>
		</fieldset>
	</form>
	<?php } ?>
	<fieldset>
		<legend>Causas propuestas</legend>
		<table id="causas">
			<tbody>
			<?php //Durante fase de ingreso de causas
							while ($causa = pg_fetch_assoc($causas)){
								echo $cgc->imprimirLineaCausa($causa['id_causa'], $causa['descripcion'], $permitirModificarCausas);
							}
			?>
			</tbody>
		</table>
		<?php 
			if($permitirModificarCausas){
				?>
				<form id="finalizarCausas" data-rutaAplicacion="gestionCalidad" data-opcion="generarMatrizPriorizacion">
					<div>
						<input type="hidden" name="hallazgo" value="<?php echo $idHallazgo;?>"/>
						<button type="submit" >
							Cerrar causas y calificar prioridad
						</button>
					</div>
				</form>
				
				<?php 
			} 
		?>
		<hr />
		<label>Causa raíz del hallazgo</label>
		<div id="matriz">
		<form id="causaRaiz" data-rutaAplicacion="gestionCalidad" data-opcion="calcularCausaRaiz">
			<input type="hidden" name="hallazgo" value="<?php echo $hallazgo['id_hallazgo'];?>"/>
			<input type="hidden" id="idCausaRaiz" name="idCausaRaiz" value="">
		<?php 
		if($permitirCalcularPriorizacion){
			echo  $cgc->imprimirMatrizDePriorizacion($conexion, $idHallazgo) ;
		}
		if($permitirIngresarAcciones){
			$causa = pg_fetch_assoc($cgc->obtenerCausaRaiz($conexion, $hallazgo['id_hallazgo']));
			echo $causa['descripcion'];
		}
		
		
		?>
			
		</form>
		</div>
	</fieldset>

	<fieldset>
		<legend>Acciones correctivas propuestas</legend>
		<?php 
			if($hallazgo['estado']!='Por definir acciones correctivas'){
				echo '<div>Para ingresar causas primero debe ingresar las posibles causas y luego calcular la causa raíz.</div>';
			} else {
				echo 'hola';
		?>
		<table id="acciones">
			
			<tbody>
			
			<?php //Despues de fase de ingreso de causas
				while ($accion = pg_fetch_assoc ($acciones)) {
					echo '<tr><td>' . $accion['descripcion'] . '</td><td>' . $acciones['fecha_culminacion'] . '</td><td>' . $acciones['estado'] . '</td></tr>';
				}
			?>
			</tbody>
		</table>
		<?php } ?>
	</fieldset>
</body>
<script type="text/javascript">

var matriz = {};
$("document").ready(function(){
	distribuirLineas();
	acciones("#nuevaCausa","#causas");
});

$("#finalizarCausas").submit(function(event){
	event.preventDefault();
	if($("#causas tbody tr").length >= 3){
		ejecutarJson($(this),new exitoGenerarMatriz());	
	} else {
		//alert("Debe ingresar al menos tres causas");
		mostrarMensaje("Debe ingresar al menos tres causas","FALLO");
	}
});

function exitoGenerarMatriz(){
	this.ejecutar = function(msg){
		mostrarMensaje(msg.mensaje,"EXITO");
		$("#nuevaCausa").remove();
		$("#causas .borrar").remove();
		$("#finalizarCausas").remove();
		$("#estadoDeHallazgo").html(msg.nuevoEstado);
		$("#matriz #causaRaiz").append(msg.matriz);
		
	};
}

$("#causaRaiz").on("submit",(function(event){
	event.preventDefault();
	if($("#causaRaiz select:disabled").size() == $("#causaRaiz select").size()) {
		/*for (var propiedad in matriz){
			$("#causaRaiz").append('<input type="text" name="causas" value="'+ propiedad +'">');
			$("#causaRaiz").append('<input type="text" name="valores" value="'+ matriz[propiedad] +'">');
		}*/
		$("#matriz select").removeAttr("disabled");
		if(verificarCausas()){//TODO: verificar que solo exista una causa alta
			ejecutarJson($(this),new exitoCalcularPriorizacion());
			//mostrarMensaje("Todo bien!!","EXITO");
		} else {
			$("#matriz select").prop("disabled",true);
			alert("Existe más de una causa alta, por favor revise los valores y reinicielos");
		}
	} else {
		//alert("Por favor completar la matriz");
		mostrarMensaje("Por favor completar la matriz","EXITO");
	}
}));

function verificarCausas(){
	var mayor = -1;
	var unicoMayor = true;
	for (var i in matriz)
		if (matriz[i] > mayor){
				mayor = matriz[i];
				$("#idCausaRaiz").val(i);
		} else if (mayor == matriz[i]) {
			unicoMayor = false;
			break;
		}
	return unicoMayor;
}

function exitoCalcularPriorizacion(){
	this.ejecutar = function(msg){
		mostrarMensaje(msg.mensaje,"EXITO");
		$("#causaRaiz").remove();
		$("#estadoDeHallazgo").html(msg.nuevoEstado);
		$("#matriz").html(msg.causaRaiz);
	};
}

$("#causaRaiz").on("click","#reiniciar", function(){
	matriz = {};
	$("#matriz select option").removeAttr("selected");
	$("#matriz select").removeAttr("disabled");
});

$("#matriz").on("change", "select", function(){
	var seleccion = $(this).prop("value");
	var causas = $(this).prop("name").split("c");
	var valores = new Array((seleccion=="mayor")?5:(seleccion=="igual")?1:.2,(seleccion=="mayor")?.2:(seleccion=="igual")?1:5);

	if(matriz[causas[1]])
		matriz[causas[1]] += valores[0];		
	else
		matriz[causas[1]] = valores[0];
		
	if(matriz[causas[2]])
		matriz[causas[2]] += valores[1];
	else
		matriz[causas[2]] = valores[1];
	
	$(this).prop("disabled",true);
	//alert("cambio de " + causas[1]+ " : " + matriz[causas[1]] + " (" + matriz.length + ")");
});
</script>
</html>