<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorLotes.php';


$mensaje = array();
$mensaje['estado'] = 'exito';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$idProducto=$_POST['idProducto'];
$idPlantilla=$_POST['idPlantilla'];
$opcion = $_POST['opcion'];


$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cl = new ControladorLotes();
try{
		
	try {
		
		$items = array();		
		
		$res=$cc->obtenerTipoSubtipoProductoOperacionMasivo($conexion, $idProducto);
		$fila=pg_fetch_assoc($res);
		
		$res=$cl->obtenerPlantillaxID($conexion, $idPlantilla);
		$plantilla=pg_fetch_assoc($res);
		
		switch ($fila['id_area']){
			case'SV':
				$area="Sanidad Vegetal";
				break;
				
			case'SA':
				$area="Sanidad Animal";
				break;
				
			case'LT':
				$area="Laboratorios";
				break;
				
			case'AI':
				$area="Inocuidad de los Alimentos";
				break;
		}
		
		if($plantilla['orientacion'] =="h"){
		    $item='<option value="6">6</option>';
		}
		
		$contenido= '
			<form id="frmPlantilla" data-rutaAplicacion="administracionEtiquetas" data-accionEnExito="NADA">	
			<input type="hidden" id="opcion" name="opcion"/>
			<input type="hidden" id="id" name="id" value="'.$idPlantilla.'"/>
			<fieldset>
			<div style="width:100%;text-align:center">
			<button id="btnEditar" class="editar">Editar</button>
			<button type="submit" id="btnGuardar" class="guardar" disabled="disabled">Actualizar</button>
			</div>
			<legend>Información del Producto</legend>
            <input type="hidden" name="idProducto" value="'.$idProducto.'">
			<div data-linea="1">
				<label>Área:</label>
				<input type="text" value="'.$area .'" disabled>
			</div>
			<div data-linea="2" id="resultadoTipoProducto">
				<label>Tipo Producto:</label>
				<input type="text" value="'. $fila['nombretipoproducto'] .'" disabled>
			</div>
			<div data-linea="3" id="resultadoSubTipoProducto">
				<label>Subtipo Producto:</label>
				<input type="text" value="'. $fila['nombresubtipoproducto'].'" disabled>
			</div>
			<div data-linea="4" id="resultadoProducto">
				<label>Producto:</label>
				<input type="text" value="'. $fila['nombre_comun'].'" disabled>
			</div>
			<div data-linea="5">
				<label for="cbPlantilla">Plantilla:</label>
					<select id="cbPlantilla" name="cbPlantilla" disabled>
						<option value="P1">Plantilla 1</option>
						<option value="P2">Plantilla 2</option>
				</select>
			</div>
			<div data-linea="7" >
			<label for="cbTamanio">Tamaño de papel:</label>
					<select id="cbTamanio" name="cbTamanio" disabled>
						<option value="A4">A4</option>
						<option value="etiqueta">Etiqueta 5cmx10cm</option>
				</select>
			</div>			
			<div data-linea="8" >
			<label for="cbOrientacion">Orientación de la hoja:</label>
					<select id="cbOrientacion" name="cbOrientacion" disabled>
						<option value="v">Vertical</option>
						<option value="h">Horizontal</option>
				</select>
			</div>
            <div data-linea="9" >
			<label for="cbEtiquetaPorHoja">Etiquetas por hoja:</label>
					<select id="cbEtiquetaPorHoja" name="cbEtiquetaPorHoja" disabled>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>'                        
						.$item.'
				</select>
			</div>
			<div data-linea="10" >
				<label for="txtNombreImpresion">Configuración impresión:</label>
					<input type="text" id="txtNombreImpresion" name="txtNombreImpresion" disabled>
			</div>
						
		</fieldset>
						
		<fieldset>
			<legend>Visualización de Plantilla</legend>
			<div data-linea="1" id="resultadoPlantilla">
				<div style="text-align:center;width:100%">
				<img alt="plantilla1" src="aplicaciones/administracionEtiquetas/img/plantilla1.png" width="250" height="150">
				</div>
			</div>
		</fieldset>
		<fieldset>
			<legend>Formato de Impresión</legend>
			<div style="text-align:center;width:100%">
				<button id="btnPrevizualizar">Previzualizar</button>
			</div>
			<div style="text-align:center;width:100%">
				<div id="vizualizador" >
				<!-- img alt="plantilla1" src="aplicaciones/administracionEtiquetas/img/plantilla1.png" width="250" height="150"-->
				</div>
			</div>
		</fieldset>
		</form>';				
		
		
		$items[] = array(contenido => $contenido,producto=>$plantilla['id_producto'],idPlantilla=>$plantilla['id_plantilla'],plantilla=>$plantilla['plantilla'], hoja=>$plantilla['hoja'],cantidad=>$plantilla['cantidad'],orientacion=>$plantilla['orientacion'],nombre=>$plantilla['nombre']);
					
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $items;
			
			
		echo json_encode($mensaje);
	} catch (Exception $ex){
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = 'Error al ejecutar sentencia';
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>