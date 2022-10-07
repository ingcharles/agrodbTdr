<?php
	function filtroBusqueda($identificador, $solicitud, $fechaBusque,$parametro){
		switch ($parametro) {
			case 1:
			$opcion = '<option value="">Seleccione...</option>
						<option value="creado">Creado</option>
						<option value="Aprobado">Aprobado</option>
						<option value="Subsanar">Subsanar</option>
						<option value="Subsanado">Subsanado</option>
						<option value="Pendiente de Aprobar">Pendiente de Aprobar</option>
						<option value="Cerrado">Cerrado</option>
						<option value="Rechazado">Rechazado</option>';
			break;
			case 2:
			$opcion = '<option value="">Seleccione...</option>
				<option value="creado">Creado</option>';
			break;
			case 3:
			$opcion = '<option value="">Seleccione...</option>
					<option value="creado">Creado</option>
					<option value="Subsanar">Subsanar</option>
					<option value="Subsanado">Subsanado</option>
					<option value="Pendiente de Aprobar">Pendiente de Aprobar</option>';
			break;
			case 4:
			$opcion = '<option value="">Seleccione...</option>
				<option value="creado">Creado</option>
				<option value="Subsanar">Subsanar</option>
				<option value="Subsanado">Subsanado</option>
				<option value="Pendiente de Aprobar">Pendiente de Aprobar</option>
				<option value="Cerrado">Cerrado</option>';
				break;
			default:
				
			break;
		}
?>
		<table class="filtro" style="width: 400px;" >
					<tbody>
					<tr>
					<th colspan="3">Buscar Solicitud:</th>
					</tr>
					<tr>
					<td >Identificación:</td>
					<td > <input id="identificador" type="text" name="identificador" maxlength="10" value="<?php echo $identificador;?>" style=" width:80%">	</td>
					
					</tr>
						
					<tr>
					<td >Número de Solicitud:</td>
					<td > <input id="solicitud" type="text" name="solicitud" maxlength="28" value="<?php echo $solicitud;?>" style=" width:80%">	</td>
					
					</tr>
						
					<tr>
					<td >Estado:</td>
					<td><select name="estadoSolicitud" id="estadoSolicitud" style="width:80%">
						<?php echo $opcion;?>
					</select></td>
					</tr>
					<tr>
					<td >Fecha:</td>
					<td ><input type="text" name="fechaBusque" id="fechaBusque" style=" width:80%" value="<?php echo $fechaBusque;?>" readonly/></td>
					</tr>	
						
					<tr>
					<td colspan="3"> <button id="buscar">Buscar</button>	</td>
					</tr>
					<tr>
					<td id="mensajeError" colspan="3"></td>
					</tr>
					</tbody>
					</table> 
			<script type="text/javascript">
				$("#identificador").numeric();
			</script>

<?php 	
	}
?>		
