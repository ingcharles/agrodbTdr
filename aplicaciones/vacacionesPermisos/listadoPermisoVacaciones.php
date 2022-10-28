<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorVacaciones.php';
?>
<header>
<h1>Registros de Vacaciones y Permisos</h1>
<nav>
<?php 	

$identificador=$_SESSION['usuario'];

if($identificador==''){
	$usuario=0;
}else{
	$usuario=1;
}

$conexion = new Conexion();
$ca = new ControladorAplicaciones();
$cd = new ControladorVacaciones();
$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $identificador);

while($fila = pg_fetch_assoc($res)){
	echo '<a href="#"
			id="' . $fila['estilo'] . '"
					data-destino="detalleItem"
					data-opcion="' . $fila['pagina'] . '"
							data-rutaAplicacion="' . $fila['ruta'] . '"
									>'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';
}

$minutos=pg_fetch_result($cd->consultarSaldoFuncionario($conexion,$identificador), 0, 'minutos_disponibles');
$minutosDisponibles=$cd->devolverFormatoDiasDisponibles($minutos);
//-----------------------------devolver jefe inmediato-------------------------------------------------------
	$resultadoConsulta=$cd->devolverJefeImnediato($conexion, $identificador);
	//print_r($resultadoConsulta);
//-----------------------------------------------------------------------------------------------------------

//$dias=floor(intval($minutos['minutos_disponibles'])/480);
//$horas=floor((intval($minutos['minutos_disponibles'])-$dias*480)/60);
//$minutos=(intval($minutos['minutos_disponibles'])-$dias*480)-$horas*60;

?>

</nav>
</header><header>
<nav><H2>Disponibilidad <?php echo $minutosDisponibles; ?>
      </H2></nav></header>
     <?php  
     	if($resultadoConsulta['usuario']!='')echo "<header><nav>Jefe Inmediato:  ".$resultadoConsulta['usuario']."</nav>	</header>";
      ?>

	
	<div id="estadoSesion"></div>
	
	<div id="nojustificadas"><div class="elementos">
		<h2>Solicitudes no justificadas	</h2>
		</div>
	</div>
	
	<div id="creado">
		<h2>Solicitudes creadas</h2>
		<div class="elementos"></div>
	</div>
			
	<div id="informe">
		<h2>Solicitudes con acción de personal</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="aprobado">
		<h2>Solicitudes aprobadas por jefe inmediato</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="rechazado">
		<h2>Solicitudes rechazadas por jefe inmediato</h2>
		<div class="elementos"></div>
	</div>
    <div id="descuento">
		<h2>Descuento Vacaciones</h2>
		<div class="elementos"></div>
	</div>
<?php 

if($identificador != ''){
	$res = $cd->listarPermisosSolicitados($conexion,$identificador);
	$contador = 0;
	$cantidadCaracteres = 50;
	
	while($fila = pg_fetch_assoc($res)){
		
	         if($fila['estado_minutos']==2){
		           $categoria ="nojustificadas";
	            }      
	         if($fila['estado']=='creado' ){
	               $categoria ="creado"; 
	               $estado='modificarPermisoVacaciones';
	            }
	         else if($fila['estado']=='Aprobado' and $fila['codigo'] != 'PE-DA'){
	               $categoria ="aprobado"; 
	               $estado='cargarArchivoVacaciones';
	            }
	         else if($fila['estado']=='Rechazado'){
	          	$categoria ="rechazado";
	          	$estado='modificarPermisoVacaciones';
	            }
	         else if($fila['estado']=='InformeGenerado' and $fila['codigo'] != 'PE-DA'){
	               $categoria ="informe"; 
	               $estado='listaAccionPersonal';
	            }
			 else if($fila['estado']=='eliminado'){
	               $categoria ="eliminado";
	               $estado='listaAccionPersonal';
	            }
	       	 else if($fila['codigo'] == 'PE-DA'){
	            	$categoria ="descuento";
	            	$estado='listaAccionPersonal';
	            }
	           
		//$descripcion = (strlen($fila['descripcion_subtipo'])>$cantidadCaracteres?(substr($fila['descripcion_subtipo'], 0, (($cadenaDescripcion)?$cadenaDescripcion:$cantidadCaracteres)).'...'):(strlen($fila['descripcion_subtipo'])>0?$fila['descripcion_subtipo']:'Sin asunto'));
		$descripcion = $fila['descripcion_subtipo'];	
		$contenido ='<article
	    		id="'.$fila['id_permiso_empleado'].'"
				class="item"
				data-rutaAplicacion="vacacionesPermisos"
				data-opcion="'. $estado .'"
				ondragstart="drag(event)"
				draggable="true"
				data-destino="detalleItem">
				<span class="ordinal">'.++$contador.'</span>
				<span>'.$descripcion.'</span>
				<aside><strong>Cod: </strong>'	.$fila['id_permiso_empleado'].'<br />Desde:'.date('j/n/Y',strtotime($fila['fecha_inicio'])).'<br/>Hasta: '.date('j/n/Y',strtotime($fila['fecha_fin'])).'</aside>
			</article>';
	
?>

	<script type="text/javascript">
					var contenido = <?php echo json_encode($contenido);?>;
					var categoria = <?php echo json_encode($categoria);?>;
					$("#"+categoria+" div.elementos").append(contenido);
	</script>
<?php	
		}				
	}
	?>
<script>
	var usuario = <?php echo json_encode($usuario); ?>;

	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un ítem para revisarlo.</div>');	
		$("#creado div> article").length == 0 ? $("#creado").remove():"";
		$("#rechazado div> article").length == 0 ? $("#rechazado").remove():"";
		$("#aprobado div> article").length == 0 ? $("#aprobado").remove():"";
		$("#nojustificadas div> article").length == 0 ? $("#nojustificadas").remove():"";
		$("#informe div> article").length == 0 ? $("#informe").remove():"";
		$("#descuento div> article").length == 0 ? $("#descuento").remove():"";
		

		if(usuario == '0'){
			$("#estadoSesion").html("Su sesión ha expirado, por favor ingrese nuevamente al Sistema GUIA.").addClass("alerta");
		}
	});
</script>
