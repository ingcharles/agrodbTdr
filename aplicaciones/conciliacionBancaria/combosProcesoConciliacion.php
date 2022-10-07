<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorConciliacionBancaria.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cb = new ControladorConciliacionBancaria();

//$opcion = ($_POST['opcionDocumento']!= "") ? $_POST['opcionDocumento'] : $_POST['opcionCampos'];

$opcion = $_POST['opcionDocumento'];
$opcionCampos = $_POST['opcionCampos'];
$opcionConciliacion = $_POST['opcionConciliacion'];
$idDocumento = $_POST['idDocumento'];
$idRegistroProcesoConciliacion = $_POST['idRegistroProcesoConciliacion'];




switch ($opcion){
	
	case 'trama':
		$qTrama = $cb -> listadoTramas($conexion);
			
		echo '<label>Documento de entrada:</label>
				<select id="documentoEntradaUtilizarProcesoConciliacion" name="documentoEntradaUtilizarProcesoConciliacion">
				<option value="">Seleccione...</option>';
		while ($trama = pg_fetch_assoc($qTrama)){
			echo '<option value="'.$trama['id_trama'].'">'.$trama['nombre_trama'].'</option>';
		}
		echo '</select>';
	break;

	case 'documento':
		$qDocumento = $cb -> listadoDocumentos($conexion);
			
		echo '<label>Documento de entrada:</label>
				<select id="documentoEntradaUtilizarProcesoConciliacion" name="documentoEntradaUtilizarProcesoConciliacion">
				<option value="">Seleccione...</option>';
		while ($documento = pg_fetch_assoc($qDocumento)){
			echo '<option value="'.$documento['id_documento'].'">'.$documento['nombre_documento'].'</option>';
		}
		echo '</select>';
	break;
	
}

switch ($opcionCampos){
	
	case 'trama':
		$qDocumento = $cb -> obtenerCamposTramasCabeceraDetalleXIdTrama($conexion, $idDocumento);
			
		echo '<label>Datos/Columna:</label>
				<select id="datosColumnaDocumentosCamposComparar" name="datosColumnaDocumentosCamposComparar">
				<option value="">Seleccione...</option>';
		while ($documento = pg_fetch_assoc($qDocumento)){
			echo '<option value="'.$documento['id_campo'].'" data-tipoColumna="'.$documento['tipo_campo'].'" data-nombreColumna="'.$documento['nombre_campo'].'">'.$documento['nombre_campo_tipo'].'</option>';
		}
		echo '</select>';
	break;

	case 'documento':
		$qDocumento = $cb -> obtenerCamposDocumentoXIdDocumento($conexion, $idDocumento);

		echo '<label>Datos/Columna:</label>
				<select id="datosColumnaDocumentosCamposComparar" name="datosColumnaDocumentosCamposComparar">
				<option value="">Seleccione...</option>';
		while ($documento = pg_fetch_assoc($qDocumento)){
			echo '<option value="'.$documento['id_campo_documento'].'" data-tipoColumna="'.$documento['tipo_campo'].'" data-nombreColumna="'.$documento['nombre_campo'].'">'.$documento['nombre_campo_documento'].'</option>';
		}
		echo '</select>';
	break;

	case 'sistemaGUIA':
		$qDocumento = $cb -> obtenerCamposDocumentoXIdDocumento($conexion, $idDocumento);

		break;

}

switch ($opcionConciliacion){
	
	case 'procesoConciliacion':
		if ($idRegistroProcesoConciliacion!=""){

	$qDocumentosProcesoConciliacion = $cb -> obtenerDocumentosProcesoConciliacionXIdRegistroProcesoConciliacion($conexion, $idRegistroProcesoConciliacion);
	echo '<fieldset><legend>Documentos de Conciliación</legend>';
	while ($documentosProcesoConciliacion = pg_fetch_assoc($qDocumentosProcesoConciliacion)){
			
		echo '<div><label>'.$documentosProcesoConciliacion['nombre_documento_entrada_proceso_conciliacion'].'</label><input type="hidden" class="rutaArchivo" id="rutaArchivo'.$documentosProcesoConciliacion['id_documento_entrada_proceso_conciliacion'].'" name="hRutaArchivo['.$documentosProcesoConciliacion['id_documento_proceso_conciliacion'].']" value="0"/>
				<input type="hidden" name="nombreDocumento'.$documentosProcesoConciliacion['id_documento_proceso_conciliacion'].$documentosProcesoConciliacion['id_documento_entrada_proceso_conciliacion'].'" id="nombreDocumento'.$documentosProcesoConciliacion['id_documento_proceso_conciliacion'].$documentosProcesoConciliacion['id_documento_entrada_proceso_conciliacion'].'" value="'.$documentosProcesoConciliacion['nombre_documento_entrada_proceso_conciliacion'].'"/>
				<input type="hidden" name="formatoDocumento'.$documentosProcesoConciliacion['id_documento_proceso_conciliacion'].$documentosProcesoConciliacion['id_documento_entrada_proceso_conciliacion'].'" id="formatoDocumento'.$documentosProcesoConciliacion['id_documento_proceso_conciliacion'].$documentosProcesoConciliacion['id_documento_entrada_proceso_conciliacion'].'" value="'.$documentosProcesoConciliacion['formato_documento_entrada_proceso_conciliacion'].'"/>
				<input type="file" class="archivo" name="informe" accept="application/msword | application/'.$documentosProcesoConciliacion['formato_entrada_proceso_conciliacion'].' | image/*"/ required>
				<div class="estadoCarga">En espera de archivo .'.$documentosProcesoConciliacion['formato_documento_entrada_proceso_conciliacion'].' (Tamaño máximo ' . ini_get('upload_max_filesize') . ') </div>
				<button type="button" id="cargarArchivo'.$documentosProcesoConciliacion['id_documento_proceso_conciliacion'].$documentosProcesoConciliacion['id_documento_entrada_proceso_conciliacion'].'" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/conciliacionBancaria/documentos" onclick=botones(id);return false; >Subir archivo</button></br></div>';
	}
	echo '</fieldset>';
}

echo '<fieldset id="informacionConciliacion">
		<legend>Información de Conciliación</legend>
		<div data-linea="3">
		<label>Año inicio:</label>
		<select class="anioProcesoConciliacionInicio" name="anioProcesoConciliacionInicio"  onchange="asignaDias()" required>
		<option value="">Seleccione...</option>
		<option value="2017">2017</option>
		<option value="2018">2018</option>
		<option value="2019">2019</option>
		<option value="2020">2020</option>
		<option value="2021">2021</option>
		<option value="2022">2022</option>
		<option value="2023">2023</option>
		<option value="2024">2024</option>
		<option value="2025">2025</option>
		</select>
		</div>
		<div data-linea="3">
		<label>Mes inicio:</label>
		<select class="mesProcesoConciliacionInicio" name="mesProcesoConciliacionInicio" onchange="asignaDias()" required>
		<option value="">Seleccione...</option>
		<option value="1">Enero</option>
		<option value="2">Febrero</option>
		<option value="3">Marzo</option>
		<option value="4">Abril</option>
		<option value="5" >Mayo</option>
		<option value="6">Junio</option>
		<option value="7">Julio</option>
		<option value="8">Agosto</option>
		<option value="9">Septiembre</option>
		<option value="10">Octubre</option>
		<option value="11">Noviembre</option>
		<option value="12">Diciembre</option>
		</select>
		</div>
		<div data-linea="3">
		<label>Día inicio:</label>
		<select class="diaProcesoConciliacionInicio" name="diaProcesoConciliacionInicio" required>
		<option value ="">Seleccione...</option>
		<option value="1">1</option>
		<option value="2">2</option>
		<option value="3">3</option>
		<option value="4">4</option>
		<option value="5">5</option>
		<option value="6">6</option>
		<option value="7">7</option>
		<option value="8">8</option>
		<option value="9">9</option>
		<option value="10">10</option>
		<option value="11">11</option>
		<option value="12">12</option>
		<option value="13">13</option>
		<option value="14">14</option>
		<option value="15">15</option>
		<option value="16">16</option>
		<option value="17">17</option>
		<option value="18">18</option>
		<option value="19">19</option>
		<option value="20">20</option>
		<option value="21">21</option>
		<option value="22">22</option>
		<option value="23">23</option>
		<option value="24">24</option>
		<option value="25">25</option>
		<option value="26">26</option>
		<option value="27">27</option>
		<option value="28">28</option>
		<option value="29">29</option>
		<option value="30">30</option>
		<option value="31">31</option>
		</select>
		</div>

		<div data-linea="4">
		<label>Año fin:</label>
		<select class="anioProcesoConciliacionFin" name="anioProcesoConciliacionFin"  onchange="asignaDias()" required>
		<option value="">Seleccione...</option>
		<option value="2017">2017</option>
		<option value="2018">2018</option>
		<option value="2019">2019</option>
		<option value="2020">2020</option>
		<option value="2021">2021</option>
		<option value="2022">2022</option>
		<option value="2023">2023</option>
		<option value="2024">2024</option>
		<option value="2025">2025</option>
		</select>
		</div>
		<div data-linea="4">
		<label>Mes fin:</label>
		<select class="mesProcesoConciliacionFin" name="mesProcesoConciliacionFin" onchange="asignaDias()" required>
		<option value="">Seleccione...</option>
		<option value="1">Enero</option>
		<option value="2">Febrero</option>
		<option value="3">Marzo</option>
		<option value="4">Abril</option>
		<option value="5">Mayo</option>
		<option value="6">Junio</option>
		<option value="7">Julio</option>
		<option value="8">Agosto</option>
		<option value="9">Septiembre</option>
		<option value="10">Octubre</option>
		<option value="11">Noviembre</option>
		<option value="12">Diciembre</option>
		</select>
		</div>
		<div data-linea="4">
		<label>Día fin:</label>
		<select class="diaProcesoConciliacionFin" name="diaProcesoConciliacionFin" required>
		<option value ="">Seleccione...</option>
		<option value="1">1</option>
		<option value="2">2</option>
		<option value="3">3</option>
		<option value="4">4</option>
		<option value="5">5</option>
		<option value="6">6</option>
		<option value="7">7</option>
		<option value="8">8</option>
		<option value="9">9</option>
		<option value="10">10</option>
		<option value="11">11</option>
		<option value="12">12</option>
		<option value="13">13</option>
		<option value="14">14</option>
		<option value="15">15</option>
		<option value="16">16</option>
		<option value="17">17</option>
		<option value="18">18</option>
		<option value="19">19</option>
		<option value="20">20</option>
		<option value="21">21</option>
		<option value="22">22</option>
		<option value="23">23</option>
		<option value="24">24</option>
		<option value="25">25</option>
		<option value="26">26</option>
		<option value="27">27</option>
		<option value="28">28</option>
		<option value="29">29</option>
		<option value="30">30</option>
		<option value="31">31</option>
		</select>
		</div>		
		<div data-linea="5">
		<label>Total recaudado:</label>
		<input type="text" name="totalRecaudado" id="totalRecaudado" required/>
		</div>;';

			
		$contador = 6;
		
$qBancosProcesoConciliacion = $cb -> listadoBancosRegistroProcesoConciliacion($conexion, $idRegistroProcesoConciliacion);

while ($bancosProcesoConciliacion = pg_fetch_assoc($qBancosProcesoConciliacion)){

	$bancos[]= array(idBanco=>$bancosProcesoConciliacion['id_banco_proceso_conciliacion'], nombreBanco=>$bancosProcesoConciliacion['nombre']);
	echo '<div data-linea="' . $contador . '"><label>' . $bancosProcesoConciliacion['nombre'] . ': </label><input type="text" id="' . $bancosProcesoConciliacion['id_banco_proceso_conciliacion'] . '" name="hBancoProcesoConciliacion['.$bancosProcesoConciliacion['id_banco_proceso_conciliacion'].']" required></div>' ;
	$contador++;
}


echo '</fieldset>';
	break;
}

?>

<script type="text/javascript">	

	$(document).ready(function(){	
    	distribuirLineas();	
	});

	$("#datosColumnaDocumentosCamposComparar").change(function (event){
    	if($.trim($("#documentoReporteCamposComparar").val())!=""){
    		 $("#tipoColumna").val($("#datosColumnaDocumentosCamposComparar option:selected").attr('data-tipoColumna'));
			 $("#nombreColumna").val($("#datosColumnaDocumentosCamposComparar option:selected").attr('data-nombreColumna'));
        }
    }); 
	
	$("#documentoEntradaUtilizarProcesoConciliacion").change(function (event){
    	$("#nombreDocumentoEntradaUtilizadoProcesoConciliacion").val($("#documentoEntradaUtilizarProcesoConciliacion option:selected").text());  
	})

</script>
