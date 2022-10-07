<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEtiquetas.php';
$conexion = new Conexion();
$ce = new ControladorEtiquetas();
$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');

switch ($opcion) {
	
	case 'listaAreas':
		$identificadorOperador=htmlspecialchars ($_POST['identificadorOperador'],ENT_NOQUOTES,'UTF-8');
		$idSitio = htmlspecialchars ($_POST['sitio'],ENT_NOQUOTES,'UTF-8');
		$qSitiosOperaciones=$ce->buscarSitiosOperadoresPorCodigoyAreaOperacion($conexion, $identificadorOperador, '{ACO,COM}','{SV}',$idSitio);
		echo '<label>Área: </label>';
		echo '<select id="area" name="area">';
		echo '<option value="0">Seleccione...</option>';
		while ($fila = pg_fetch_assoc($qSitiosOperaciones)){
			echo '<option data-codigoArea="'. $fila['codigo_area'].'" value="'. $fila['id_area'].'" >'.$fila['nombre_area'].'</option>';
		}
		echo '</select>';
	break;

	case 'solicitudSitio':
		$idEtiqueta = htmlspecialchars ($_POST['idSolicitudEtiqueta'],ENT_NOQUOTES,'UTF-8');
		$nombreSitio = htmlspecialchars ($_POST['nombreSitio'],ENT_NOQUOTES,'UTF-8');
		$idSitio = htmlspecialchars ($_POST['idSitio'],ENT_NOQUOTES,'UTF-8');
		$idArea = htmlspecialchars ($_POST['idArea'],ENT_NOQUOTES,'UTF-8');
		$datosEtiquetaSitio=pg_fetch_assoc($ce->obtenerSolicitudesEtiquetasXSitio($conexion, $idEtiqueta, $idSitio, $idArea));
	
		echo '<fieldset>		
			<legend>Ingreso Número de Etiquetas</legend>
					<input type="hidden" id="idEtiquetaSitio" name="idEtiquetaSitio" value="'.$datosEtiquetaSitio['id_etiqueta_sitio'].'" />
					<input type="hidden" id="saldoEtiquetasSitio" name="saldoEtiquetasSitio" value="'.$datosEtiquetaSitio['saldo_etiqueta_sitio'].'" />
					<input type="hidden" id="saldoEtiqueta" name="saldoEtiqueta" value="'.$datosEtiquetaSitio['saldo_etiqueta'].'" readOnly/>
				
				<div data-linea="1">
					<label>Nombre del Sitio: </label>
				</div>
					
				<div data-linea="1">
					'.$nombreSitio.' 
				</div>
							
				<div data-linea="2">
					<label>Número de Etiquetas Disponibles: </label>
				</div>
					
				<div data-linea="2">
					'.$datosEtiquetaSitio['saldo_etiqueta_sitio'].' 
				</div>
				
				<div data-linea="3">
					<label>Cuantas Desea Imprimir: </label> 
				</div>
				
				<div data-linea="3">
					<input type="text" id="numeroEtiquetasImprimir" name="numeroEtiquetasImprimir" value="" onkeypress='."'ValidaSoloNumeros()'".'  maxlength="4" data-er="^[0-9]+$" />
				</div>
				
				<div data-linea="4">
					<br>
					<label>Nota:</label>
					 Verifique la configuración de su impresora. Tamaño de la etiqueta es 5 X 10 cm.
				</div> 

		</fieldset>';
	break;
}

	
?>
<script type="text/javascript">
	$(document).ready(function(){
		distribuirLineas();
		
	});
	function ValidaSoloNumeros() {
		 if ((event.keyCode < 48) || (event.keyCode > 57))
		  event.returnValue = false;
	}
</script>