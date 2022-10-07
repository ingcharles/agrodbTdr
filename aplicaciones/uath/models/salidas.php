<?php
	function fechaSubrogacion($fechaInicio,$fechaFin,$responsabilidad){
?>
		<fieldset id="detalle" >
		<legend>Fecha de Subrogación</legend>
		<div data-linea="1">
		<label>Fecha de Inicio</label>
		<input type="text" id="fechaSalida" name="fechaSalida" value="<?php echo $fechaInicio;?>" disabled="disabled" readonly/>
		</div>
		<div data-linea="1">
		<label>Fecha de Fin</label>
		<input type="text" id="fechaRetorno" name="fechaRetorno" value="<?php echo $fechaFin;?>"  disabled="disabled" readonly/>
		</div>
		<input type="hidden" id="idResponsable" name="idResponsable" value="<?php echo $responsabilidad;?>" />
		</fieldset>

<?php 	}
	function menuSubrogacion(){
?>		
		<fieldset id="seleccionOpcion">
		<legend>Seleccione Opcion</legend>
		<div data-linea="1">
		<label>Responsabilidad:</label> <input type="radio" id="op1" name="op"
					value="responsable" disabled="disabled" onclick="agregarOpcion(id);"/> <label>Subrogación:</label> <input type="radio" id="op2" disabled="disabled"
					name="op"  value="subrogacion" onclick="agregarOpcion(id);"/><div id="estadoOpcion"></div>
		</div>
		<input type="hidden" id="opcion" name="opcion"
					value="" />
		</fieldset>
<?php 	
	}
	
	function mensajesSalidas($mensaje){
		echo '<header> </header>
			 <div id="estado"></div>';
		switch ($mensaje['estado']) {
			case 'exito':
				echo '<div class="mensajeInicial"></div>';
				echo '<script>mostrarMensaje("'.$mensaje['mensaje'].'","EXITO");</script>';
			break;
			case 'error':
				echo '<div id="error"></div>';
				echo '<script>mostrarMensaje("'.$mensaje['mensaje'].'","FALLO");</script>';
			break;
			
			default:
				echo '<div class="mensajeInicial"></div>';
			break;
		}
	}
	function verificarDistrital($area){
			$valor='';
			$areas []=array(
					'DDAT03'=>'Z1', 'DDAT04'=>'Z2','DDAT07'=>'Z3','DDAT10'=>'Z4','DDAT12'=>'Z5','DDAT14'=>'Z6','DDAT16'=>'Z7'
			);
			foreach($areas as $val) {
				if ($val[$area] != '') {
					$valor=$val[$area];
				}
			}
			return $valor;
		}
		
?>

