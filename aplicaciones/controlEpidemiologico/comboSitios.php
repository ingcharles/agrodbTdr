<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRegistroOperador.php';
	
	$conexion = new Conexion();
	$cr = new ControladorRegistroOperador();
	
	$identificador = htmlspecialchars ($_POST['identificadorOperador'],ENT_NOQUOTES,'UTF-8');
	$identificadorNotificante = htmlspecialchars (trim($_POST['identificadorNotificante']),ENT_NOQUOTES,'UTF-8');
	$nombreNotificante = htmlspecialchars (trim($_POST['nombreNotificante']),ENT_NOQUOTES,'UTF-8');
	$apellidoNotificante = htmlspecialchars (trim($_POST['apellidoNotificante']),ENT_NOQUOTES,'UTF-8');
	$telefonoNotificante = htmlspecialchars (trim($_POST['telefonoNotificante']),ENT_NOQUOTES,'UTF-8');
	$celularNotificante = htmlspecialchars (trim($_POST['celularNotificante']),ENT_NOQUOTES,'UTF-8');
	
	$vigilancia[] = array(identificador=>$identificadorNotificante,
						  nombre=>$nombreNotificante,
			 			  apellido=>$apellidoNotificante,
						  telefono=>$telefonoNotificante,
						  celular=>$celularNotificante);
	
	$qSitios = $cr->listarSitios($conexion, $identificador);
	
	if(pg_num_rows($qSitios) != 0){
		echo '<div data-linea="2">
				<label>Sitio</label>
					<select id="sitio" name="sitio" style="width:76%;">
						<option value="">Sitio....</option>';
		
		while ($fila = pg_fetch_assoc($qSitios)){
			echo '<option value="'.$fila['codigo'].'"><b>'.$fila['nombre_lugar'].'</b>-'.$fila['provincia'].'-'.$fila['canton'].'-'.$fila['parroquia'].'</option>';
		}	
			
		echo 	'	</select>
			  </div>';
		
		$mensaje = '';
	}else{
		$mensaje = 'Debe registrar a este operador.';
	}
?>

<script type="text/javascript">
	$('#estado').html("<?php echo $mensaje;?>").addClass('alerta');

	if($('#estado').html() != ''){
		$("#registrarOperador").show();
	}else{
		$("#registrarOperador").hide();
	}
</script>