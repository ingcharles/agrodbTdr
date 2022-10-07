<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMovilizacionProductos.php';
require_once '../../clases/ControladorRegistroOperador.php';

$conexion = new Conexion();
$cmp = new ControladorMovilizacionProductos();
$cro = new ControladorRegistroOperador();

$identificadorUsuario = $_SESSION['usuario'];
$filaTipoUsuario = pg_fetch_assoc($cmp->obtenerTipoUsuario($conexion, $identificadorUsuario));



$idMovilizacion = $_POST['id'];
$qMovilizacion = $cmp->abrirMovilizacionProducto($conexion, $idMovilizacion);
$filaMovilizacion = pg_fetch_assoc($qMovilizacion);

$qDetalleMovilizacion=$cmp->abrirDetalleMovilizacion($conexion, $idMovilizacion);
$qDetalleMovilizacionModificar=$cmp->abrirDetalleMovilizacion($conexion, $idMovilizacion);
$qFiscalizacionMovilizacion=$cmp->listaFiscalizacionXmovilizacion($conexion, $idMovilizacion);

$qAreaTipoOperacion = $cro->obtenerAreaTipoOperacionXIdentificadorOperador($conexion, $identificadorUsuario);
$areaTipoOperacion = pg_fetch_result($qAreaTipoOperacion, 0, 'codigo_tipo_operacion');


$banderaSolicitante = false;
$qOperacionesEmpresaUsuario = $cmp->obtenerOperacionesEmpresaUsuario($conexion, $identificadorUsuario, "('digitadorFaenador')", "('FAEAI')");
if(pg_num_rows($qOperacionesEmpresaUsuario) > 0)
    $banderaSolicitante = true;
 


?>

<header>
	<h1>Registro de Certificado de Movilización</h1>
</header>
<div id="estado"></div>

<form id='abrirFiscalizacionMovilizacion' data-rutaAplicacion='movilizacionProducto'  data-accionEnExito="ACTUALIZAR">	
	
	<input type="hidden" id="idMovilizacion" name="idMovilizacion" value="<?php echo $filaMovilizacion['id_movilizacion'];?>" />													  	
	<input type="hidden" id="sitioOrigen" name="sitioOrigen" value="<?php echo $filaMovilizacion['sitio_origen'];?>" />													  	
	
	<input type="hidden" id="opcion" name="opcion" value="" />	
	<input type="hidden" id="idDetalleMovilizacion" name="idDetalleMovilizacion" value="0" />														  	
	<input type="hidden" id="identificadoresAgregados" name="identificadoresAgregados" value="" />
	<input type="hidden" id="usuarioResponsable" name="usuarioResponsable" value="<?php echo $identificadorUsuario;?>" />
	<input type="hidden" id="banderaTicket" name="banderaTicket" value="" />
	<input type="hidden" id="banderaDobleGuia" name="banderaDobleGuia" value="" />
	<input type="hidden" id="banderaMatadero" name="banderaMatadero" value="" />
	<input type="hidden" id="totalProductos" name="totalProductos" value="0" />
	<input type="hidden" id="tipoUsuario" name="tipoUsuario" value="<?php echo $filaTipoUsuario['codificacion_perfil'];?>" />
	<input type="hidden" id="usuarioResponsableMovilizacion" name="usuarioResponsableMovilizacion" value="<?php echo $filaMovilizacion['identificador_solicitante'];?>" />
		
	<fieldset>
		<legend>Datos Generales</legend>
		<div data-linea="1">
				<label>Tipo Solicitud: </label>	
				<input type="text" id="tipoSolicitud" name="tipoSolicitud" value="<?php echo $filaMovilizacion['tipo_solicitud'];?>" disabled="disabled"/>													  
		</div>
		<div data-linea="2">
				<label>Provincia Emisión: </label>	
				<input type="text" id="provinciaEmision" name="provinciaEmision" value="<?php echo $filaMovilizacion['provincia_emision'];?>" disabled="disabled"/>													  
		</div>
		<div data-linea="3">
				<label>Oficina Emisión: </label>	
				<input type="text" id="oficinaEmision" name="oficinaEmision" value="<?php echo $filaMovilizacion['oficina_emision'];?>" disabled="disabled"/>													  
		</div>
		<div data-linea="4">
			<label>N° Certificado: </label>
			<input type="text" id="numeroCertificado" name="numeroCertificado" value="<?php echo $filaMovilizacion['numero_certificado'];?>" disabled="disabled"/>													  
		</div>
		<div data-linea="5">
		    <label>Fecha Emision: </label> 
			<input type="text" id="fechaRegistro" name="fechaRegistro" value="<?php echo $filaMovilizacion['fecha_registro'] ;?>" disabled="disabled" readonly />
		</div>
		<div data-linea="6">
			<label>Fecha Inicio  de Vigencia: </label> 
			<input type="text" id="fechaInicioVigencia" name="fechaInicioVigencia" value="<?php echo $filaMovilizacion['fecha_inicio_vigencia'];?>" disabled="disabled"/>			
		</div>			
		<div data-linea="7">
			<label>Fecha Fin de Vigencia: </label> 
			<input type="text" id="fechaFinVigencia" name="fechaFinVigencia" value="<?php echo $filaMovilizacion['fecha_fin_vigencia'];?>" disabled="disabled"/>			
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Datos Sitio Origen</legend>	
		<div data-linea="1">
			<label>Identificación Operador: </label> 
			<input type="text" id="identificacionOperadorOrigen" name="identificacionOperadorOrigen" value="<?php echo $filaMovilizacion['identificador_operador_origen'];?>" disabled="disabled"/>
		</div> 
		<div data-linea="2">
			<label>Nombre Operador: </label> 
			<input type="text" id="nombreOperadorOrigen" name="nombreOperadorOrigen" value=" <?php echo $filaMovilizacion['nombre_operador_origen'];?>" disabled="disabled"/>
		</div> 
		<div data-linea="3">
			<label>Sitio: </label> 
			<input type="text" id="sitioOrigen" name="sitioOrigen" value="<?php echo $filaMovilizacion['nombre_sitio_origen'];?>" disabled="disabled"/>
		</div> 
		<div data-linea="5">
			<label>Codigo de  Sitio: </label> 
			<input type="text" id="codigoSitioOrigen" name="codigoSitioOrigen" value="<?php echo $filaMovilizacion['codigo_sitio_origen'];?>" disabled="disabled"/>
		</div> 
		<div data-linea="6">
			<label>Provincia: </label> 
			<input type="text" id="provinciaSitioOrigen" name="provinciaSitioOrigen" value="<?php echo $filaMovilizacion['provincia_sitio_origen'];?>" disabled="disabled"/>
		</div> 
		<div data-linea="7">
			<label>Cantón: </label> 
			<input type="text" id="cantonSitioOrigen" name="codigoSitioOrigen" value="<?php echo $filaMovilizacion['canton_sitio_origen'];?>" disabled="disabled"/>
		</div> 
		<div data-linea="8">
			<label>Parroquia: </label> 
			<input type="text" id="parroquiaSitioOrigen" name="parroquiaSitioOrigen" value="<?php echo $filaMovilizacion['parroquia_sitio_origen'];?>" disabled="disabled"/>
		</div> 
	</fieldset>
	<fieldset>
		<legend>Datos Sitio Destino</legend>	
		<div data-linea="1">
			<label>Identificación Operador: </label> 
			<input type="text" id="identificacionOperadorDestino" name="identificacionOperadorDestino" value="<?php echo $filaMovilizacion['identificador_operador_destino'];?>" disabled="disabled"/>
		</div> 
		<div data-linea="2">
			<label>Nombre Operador: </label> 
			<input type="text" id="nombreOperadorDestino" name="nombreOperadorDestino" value=" <?php echo $filaMovilizacion['nombre_operador_destino'];?>" disabled="disabled"/>
		</div> 
		<div data-linea="3">
			<label>Sitio: </label> 
			<input type="text" id="sitioDestino" name="sitioDestino" value="<?php echo $filaMovilizacion['nombre_sitio_destino'];?>" disabled="disabled"/>
		</div> 
		<div data-linea="4">
			<label>Codigo de  Sitio: </label> 
			<input type="text" id="codigoSitioDestino" name="codigoSitioDestino" value="<?php echo $filaMovilizacion['codigo_sitio_destino'];?>" disabled="disabled"/>
		</div> 
		<div data-linea="5">
			<label>Provincia: </label> 
			<input type="text" id="provinciaSitioDestino" name="provinciaSitioDestino" value="<?php echo $filaMovilizacion['provincia_sitio_destino'];?>" disabled="disabled"/>
		</div> 
		<div data-linea="6">
			<label>Cantón: </label> 
			<input type="text" id="cantonSitioDestino" name="codigoSitioDestino" value="<?php echo $filaMovilizacion['canton_sitio_destino'];?>" disabled="disabled"/>
		</div> 
		<div data-linea="7">
			<label>Parroquia: </label> 
			<input type="text" id="parroquiaSitioDestino" name="parroquiaSitioDestino" value="<?php echo $filaMovilizacion['parroquia_sitio_destino'];?>" disabled="disabled"/>
		</div> 
	</fieldset>
	
	<fieldset>
		<legend>Datos de Movilización</legend>
			<div data-linea="1">
				<label>Identificación Solicitante: </label> 
				<input type="text" id="identificadorSolicitante" name="identificadorSolicitante" value="<?php echo $filaMovilizacion['identificador_solicitante'];?>" disabled="disabled"/>
			</div>
			<div data-linea="2">
				<label>Nombre Solicitante: </label> 
				<input type="text" id="nombreSolicitante" name="nombreSolicitante" value="<?php echo $filaMovilizacion['nombre_solicitante'];?>" disabled="disabled"/>
			</div>	
			<div data-linea="3">
				<label>Medio de Transporte: </label> 
				<input type="text" id="medioTransporte" name="medioTransporte" value="<?php echo $filaMovilizacion['medio_transporte'];?>" disabled="disabled"/>
			</div>
			<div data-linea="4">
				<label>Placa  de Transporte: </label> 
				<input type="text" id="placaTransporte" name="placaTransporte" value="<?php echo $filaMovilizacion['placa_transporte'];?>" disabled="disabled"/>
			</div>	
			<div data-linea="5">
				<label>Identificación Conductor: </label> 
				<input type="text" id="identificacionConductor" name="identificacionConductor" value="<?php echo $filaMovilizacion['identificador_conductor'];?>" disabled="disabled"/>
			</div>
			<div data-linea="6">
				<label>Nombre Conductor: </label> 
				<input type="text" id="nombreConductor" name="nombreConductor" value="<?php echo $filaMovilizacion['nombre_conductor'];?>" disabled="disabled"/>
			</div>
			<div data-linea="7">
				<label>Observación: </label> 
				<input type="text" id="observacion" name="observacion" value="<?php echo $filaMovilizacion['observacion'];?>" disabled="disabled"/>
			</div>
									
	</fieldset>
	
	<fieldset>
		<legend>Detalle de Productos Movilizados</legend>
		<table style="width:100%"  id="registrosDetalleMovilizacion">
			<thead>
				<tr>
					<th>N° Reg.</th>
					<th title="Operación Origen">Origen</th>
					<th title="Operación Destino">Destino</th>
					<th>Producto</th>
					<th>Cant.</th>			
					<th>Letras</th>						
					<th title="Unidad Comercial">Unidad</th>
					<th>N° Identificadores</th>
				</tr>
			</thead>
			<?php 
			$contadorDetalle=1;

			$arrayhIdCatastro= array();
			$arrayhIdIdentificador= array();
			while($filaDetalleMovilizacion=pg_fetch_assoc($qDetalleMovilizacion)){
				echo '<tr>
						<td>'.$contadorDetalle.'<input type="hidden" id="hIdDetalle" name="hIdDetalle['.$contadorDetalle.']" value="'. $contadorDetalle.'" ></td>
						<td>'.$filaDetalleMovilizacion['operacion_origen'].' <input type=hidden id=hOperacionOrigen name=hOperacionOrigen['.$contadorDetalle.'] value='. $filaDetalleMovilizacion['tipo_operacion_origen'].' ></input><input type=hidden id=hAreaOrigen name=hAreaOrigen['.$contadorDetalle.'] value='. $filaDetalleMovilizacion['area_origen'].' ></input></td>
						<td>'.$filaDetalleMovilizacion['operacion_destino'].' <input type=hidden id=hOperacionDestino name=hOperacionDestino['.$contadorDetalle.'] value='. $filaDetalleMovilizacion['tipo_operacion_destino'].' ></input><input type=hidden id=hAreaDestino name=hAreaDestino['.$contadorDetalle.'] value='. $filaDetalleMovilizacion['area_destino'].' ></input></td>
						<td>'.$filaDetalleMovilizacion['producto'].' <input type=hidden id=hProducto name=hProducto['.$contadorDetalle.'] value='. $filaDetalleMovilizacion['id_producto'].' ></input></td>
						<td>'.$filaDetalleMovilizacion['cantidad'].' <input type=hidden id=hCantidad name=hCantidad['.$contadorDetalle.'] value='. $filaDetalleMovilizacion['cantidad'].' ></input></td>
						<td>'.$filaDetalleMovilizacion['letras'].' </td>
						<td>'.$filaDetalleMovilizacion['unidad_comercial'].' <input type=hidden id=hUnidadMedida name=hUnidadMedida['.$contadorDetalle.'] value='. $filaDetalleMovilizacion['unidad'].' ></input></td><td style="width:100%"><input type=hidden id=hCodigoOperacion name=hCodigoOperacion['.$contadorDetalle.'] value='. $filaDetalleMovilizacion['codigo_area_operacion'].' ></input><input type=hidden id=hCodigoOperacionDestino name=hCodigoOperacionDestino['.$contadorDetalle.'] value='. $filaDetalleMovilizacion['codigo_area_operacion_destino'].' ></input><div style="width:102%; max-height:120px; overflow:auto">';

				$qDetalleMovilizacionIdentificadores=$cmp->abrirDetalleMovilizacionIdentificadores($conexion, $filaDetalleMovilizacion['id_detalle_movilizacion']);
				
				$contador=0;
				
				while($filaDetalleMovilizacionIdentificadores=pg_fetch_assoc($qDetalleMovilizacionIdentificadores)){
					$arrayhIdCatastro[$contadorDetalle][]=$filaDetalleMovilizacionIdentificadores['id_catastro'];
					$arrayhIdIdentificador[$contadorDetalle][]=$filaDetalleMovilizacionIdentificadores['identificador'];
					echo $filaDetalleMovilizacionIdentificadores['identificador'].'<br>';				
					//$contador++;
				}
				
				echo '</td> </tr>';
                 $contadorDetalle++;
			}

		    ?>				
		</table>  			
	</fieldset>
	
	<fieldset>
		<legend>Resultado de Fiscalizaciones</legend>
		<table style="width:100%">
			<thead>
				<tr>
					<th>N° Reg.</th>
					<th>Fecha</th>
					<th>Fiscalizador</th>
					<th>Resultado</th>			
					<th>Acción Correctiva</th>						
					<th>Observación</th>					
				</tr>
			</thead>
			<?php 
			while($filaFiscalizacion=pg_fetch_assoc($qFiscalizacionMovilizacion)){
				echo '<tr>
						<td>'.$filaFiscalizacion['numero_fiscalizacion'].' </td>
						<td>'.$filaFiscalizacion['fecha_fiscalizacion'].' </td>
						<td>'.$filaFiscalizacion['fiscalizador'].' </td>
						<td>'.$filaFiscalizacion['resultado_fiscalizacion'].'</td>
						<td>'.$filaFiscalizacion['accion_correctiva'].' </td>
						<td>'.$filaFiscalizacion['observacion'].'</td>
				 </tr>';				
				
			}
		    ?>				
		</table>  			
	</fieldset>
	
	<fieldset id="nuevaFiscalizacion">
		<legend>Nueva Fiscalización</legend>
		<div data-linea="1">
			<label>Fecha de Fiscalización: </label>	
			<input type="text" id="fechaFiscalizacion" name="fechaFiscalizacion"  placeholder="12/12/2016" maxlength="10" data-inputmask="'mask': '99/99/9999'" readonly="readonly" />													  
		</div>
		<div data-linea="2">
			<label>Lugar de fiscalización: </label>	
			<?php 
				if($filaTipoUsuario['codificacion_perfil'] == "PFL_USUAR_INT"){

					echo '<select id="lugarFiscalizacion" name="lugarFiscalizacion" style="width:270px" >
							<option value="">Seleccione...</option>
							<option value="feria">Feria</option>
							<option value="matadero">Matadero</option>
							<option value="camper">Camper</option>
							<option value="operativo">Operativo</option>
							<option value="oficina">Oficina</option>
					</select>';	
					
				}else if($filaTipoUsuario['codificacion_perfil'] == "PFL_USUAR_EXT"){

					if(stristr($areaTipoOperacion, 'SAFER') == true && stristr($areaTipoOperacion, 'AIFAE') == true){
						echo '<select id="lugarFiscalizacion" name="lugarFiscalizacion" style="width:270px" >
						<option value="">Seleccione...</option>
						<option value="feria">Feria</option>
						<option value="matadero">Matadero</option>
						<option value="camper">Camper</option>
						<option value="operativo">Operativo</option>
						<option value="oficina">Oficina</option>
						</select>';
					}else if(stristr($areaTipoOperacion, 'SAFER') == true){
						echo '<select id="lugarFiscalizacion" name="lugarFiscalizacion" style="width:270px" >
									<option value="">Seleccione...</option>
									<option value="feria">Feria</option>
							 </select>';
					}else if(stristr($areaTipoOperacion, 'AIFAE') == true){
					    echo '<select id="lugarFiscalizacion" name="lugarFiscalizacion" style="width:270px" >
									<option value="">Seleccione...</option>';
							
					    if($banderaSolicitante == true)
					       	echo '<option selected value="matadero">Matadero</option>';
					    else
					        echo '<option value="matadero">Matadero</option>';
					    
                        echo '</select>';
						
					}
				}			
			?>														  
		</div>
		<div data-linea="2">
			<label> Cantidad de animales: </label>	
			<input type="text" id="cantidadAnimales" name="cantidadAnimales"  placeholder="10" onkeypress="soloNumeros()" />																	  
		</div>
		<div data-linea="3">
			<label>Resultado: </label>	
			<select id="resultado" name="resultado">
				<option value="0">Seleccione...</option>
				<option value="positivo">POSITIVO</option>
				<option value="negativo">NEGATIVO</option>
			</select>
		</div>
		<div data-linea="4">
			<label>Acción Correctiva: </label>
			<select id="accionCorrectiva" name="accionCorrectiva">
				<option value="0">Seleccione...</option>
			</select>	
		</div>
		<div data-linea="5" id="hrSuperior">
			<hr/>
		</div>
		<div data-linea="6" id="seccionJustificacion1">
			<label>Justificación: </label>
		</div>
		<div data-linea="6" id="seccionJustificacion2">
			<label>Oral</label>
			<input type="radio" id="justificacionOral" name="justificacion" value="oral" checked="checked">
		</div>
		<div data-linea="6" id="seccionJustificacion3">
			<label>Escrita</label>
			<input type="radio" id="justificacionEscrita" name="justificacion" value="escrita">
		</div>
		<div data-linea="7" id="hrInferior">
			<hr/>
		</div>
		<div data-linea="8">
			<label id="etiquetaObservacion" >Observación: </label>
		</div>
		<div data-linea="9">
			<textarea id="campoObservacion" name="campoObservacion" style="overflow:auto;resize:none" maxlength="256" rows="3" cols="20"></textarea>
		</div>		
	</fieldset>
	
	<fieldset id="anulacion">
		<legend>Datos de Anulación</legend>
		<div data-linea="1">
			<label>Motivo: </label>
			<select id="motivoAnulacion" name="motivoAnulacion">
				<option value="0">Seleccione...</option>
				<option value="destino erroneo">Destino erróneo</option>
				<option value="fecha de movilizacion erronea">Fecha de movilización errónea</option>
				<option value="identificadores erroneos">Identificadores erróneos</option>
				<option value="no utilizacion de CSM">No utilización de CSM</option>
				<option value="origen erroneo">Orígen erróneo</option>
				<option value="productos erroneos">Productos erróneos</option>
				<option value="transporte erroneo">Transporte erróneo</option>
			</select>
		</div>
		<div data-linea="2">
			<label>Observación: </label> 
			<input type="text" id="observacionAnulacion" name="observacionAnulacion" value="" />
		</div>
	</fieldset>
	
	<fieldset id="anulado">
		<legend>Datos de Anulación</legend>
		<div data-linea="1">
			<label>Motivo: </label>
			 <input type="text" id="motivoAnulado" name="motivoAnulado" value="<?php echo $filaMovilizacion['motivo_anulacion'];?>" disabled="disabled"/>
		</div>
		<div data-linea="2">
			<label>Observación: </label> 
			<input type="text" id="observacionAnulado" name="observacionAnulado"  value="<?php echo $filaMovilizacion['observacion_anulacion'];?>" disabled="disabled"/>
		</div>
	</fieldset>
	
	<fieldset id='modificar1'>
		<legend>Detalle de Productos Movilizados</legend>
		<table style="width:100%" id="detalleMovilizacion">
			<thead>
				<tr>
					<th>N° Reg.</th>
					<th title="Operación Origen">Origen</th>
					<th title="Operación Destino">Destino</th>
					<th>Producto</th>
					<th>Cantidad</th>								
					<th title="Unidad Comercial">Unidad</th>
				</tr>
			</thead>
			<tbody>
			<?php 
			$contadorModificar=1;
			while($filaDetalleMovilizacionModificar=pg_fetch_assoc($qDetalleMovilizacionModificar)){
				echo '<tr id='.$filaDetalleMovilizacionModificar['id_detalle_movilizacion'].' >
						<td>'.$contadorModificar++.'<input type=hidden id=hIdDetallee name=hIdDetallee[] value='. $filaDetalleMovilizacionModificar['id_detalle_movilizacion'].' /> </td>
						<td>'.$filaDetalleMovilizacionModificar['operacion_origen'].' </td>
						<td>'.$filaDetalleMovilizacionModificar['operacion_destino'].' </td>
						<td>'.$filaDetalleMovilizacionModificar['producto'].' </td>
						<td >'.$filaDetalleMovilizacionModificar['cantidad'].'</td>
						<td>'.$filaDetalleMovilizacionModificar['unidad_comercial'].'  <input type=hidden id=hCantidadd name=hCantidadd[] value='. $filaDetalleMovilizacionModificar['cantidad'].' ></input><input type=hidden id=hCantidadDetalle name=hCantidadDetalle[] value='. $filaDetalleMovilizacionModificar['cantidad'].' ></input><input type=hidden id=hIdProducto name=hIdProducto[] value='. $filaDetalleMovilizacionModificar['id_producto'].' ></input></td> </tr>';				
				
			}
		    ?>	
		    </tbody>			
		</table>  			
	</fieldset>
	
	<fieldset id='modificar2'>
		<legend>Detalle de identificadores</legend>
		
		<div style="width:100%; max-height:400px; overflow:auto">
		<table id="detalleIdentificadoresMovilizacion">
		
  			<thead>
				<tr>
					<th>N° Reg.</th>							
					<th>N° Identificador</th>	
					<th></th>
				<tr>
			</thead> 
			
			<tbody id="resultadoDetalleIdentifcadoresMovilizacion">
			</tbody>
			
		</table>
		</div>
	</fieldset>
	<button id="reset" type="button" class="reset">Limpiar</button>
	<button id="guardar" type="submit" class="guardar">Guardar</button>
	<input type="hidden" name="xx" data-rutaAplicacion="movilizacionProducto" data-opcion="abrirFiscalizacion" data-destino="detalleItem"/>
</form>
	
<script type="text/javascript">
var estado= <?php echo json_encode($filaMovilizacion['estado']); ?>;
var estadoFiscalizacion= <?php echo json_encode($filaMovilizacion['estado_fiscalizacion']); ?>;
var idMovilizacion= <?php echo json_encode($idMovilizacion); ?>;
var tipoUsuario= <?php echo json_encode($filaTipoUsuario['codificacion_perfil']); ?>;

var arrayhIdCatastro= <?php echo json_encode(array_values($arrayhIdCatastro)); ?>;

var arrayhIdIdentificador= <?php echo json_encode(array_values($arrayhIdIdentificador)); ?>;

var banderaSolicitante = <?php echo json_encode($banderaSolicitante); ?>;


$(document).ready(function(){
	acciones(false,"#detalleIdentificadoresMovilizacion");
	distribuirLineas();

	$("#fechaFiscalizacion").datepicker({
		changeMonth: true,
		changeYear: true,
		maxDate:"0"
	});	
	
	$("#anulacion").hide();
	$("#anulado").hide();
	$("#modificar1").hide();
	$("#modificar2").hide();
	$("#hrSuperior").hide();
	$("#seccionJustificacion1").hide();
	$("#seccionJustificacion2").hide();
	$("#seccionJustificacion3").hide();
	$("#hrInferior").hide();

	if(estado=='anulado'){
		$("#anulado").show();
		$("#guardar").hide();
		$("#reset").hide();
		$("#nuevaFiscalizacion").hide();
	}
	if(estadoFiscalizacion=='Fiscalizado en matadero'){
		$("#anulado").hide();
		$("#guardar").hide();
		$("#reset").hide();
		$("#nuevaFiscalizacion").hide();
	}
	
	$("#registrosDetalleMovilizacion tbody tr").each(function () {

		var row = $(this).index();
		var items=$(this).find('input[id="hIdDetalle"]').val();
		
			 
	   	var grupoIdentificadoress='';
	   	var grupoIdentificadoresOculto='';
       	var inputIdentificadores = '';

       	var arrayIdentificadores = new Array();

    	for (var j = 0; j< arrayhIdIdentificador[row].length; j++) {
    		arrayIdentificadores.push({identificadores:arrayhIdIdentificador[row][j],idCatastro:arrayhIdCatastro[row][j]});
    	}
		
	    const result = [...arrayIdentificadores.reduce((hash, { identificadores, idCatastro }) => {
                  	
	                        const current = hash.get(idCatastro) || { idCatastro, identif: [] };
	                      
                      current.identif.push({ identificadores });
                     
                      return hash.set(idCatastro, current);
                      
                    }, new Map).values() ];

       				var newArr = [],
                          types = {},
                          newItem, i, j, cur;
                      
                        for (var i = 0; i< result.length; i++) {
                        	cur = result[i];
                          inputIdentificadores+="<input type='hidden' id='dIdCatastrosAgregados' name='dIdCatastrosAgregados["+items+"][]' value='"+cur['idCatastro']+"'>";
                          newArr=cur['identif'];

                       	var grupoIdentificadores='';
                          for (var k = 0 ;k< newArr.length;  k++) {
                          	curo = newArr[k];
                          	grupoIdentificadores+= curo['identificadores'] + ", ";
                          	grupoIdentificadoresOculto+= curo['identificadores'] + ', ';
                          	
                          }
            			    var xxy=grupoIdentificadores.substr(0, grupoIdentificadores.length - 2);
                          inputIdentificadores+='<input type="hidden" id="dIdentificadoresAgregados" name="dIdentificadoresAgregados['+items+'][]" value="'+xxy+'">';
                        }
       					$("#registrosDetalleMovilizacion tbody tr:eq("+row+")").append(inputIdentificadores);
       	
	});
});
	
$("#resultado").change(function(){            	    
	sresultado ='';
	sresultado = '<option value="0">Seleccione...</option>';
	if($("#resultado").val()=='positivo'){															  
		sresultado += '<option value="fiscalizacion correcta">FISCALIZACION CORRECTA</option>';
		if(tipoUsuario!='PFL_USUAR_EXT'){
		sresultado += '<option value="modificar certificado">MODIFICAR CERTIFICADO</option>';
		sresultado += '<option value="activar emision de certificado">ACTIVAR EMISIÓN DE CERTIFICADO</option>';
		}
	}
	if($("#resultado").val()=='negativo'){
		sresultado += '<option value="inactivar emision de certificado">INACTIVAR EMISION DE CERTIFICADO</option>';
		if(tipoUsuario!='PFL_USUAR_EXT')
		sresultado += '<option value="anular certificado">ANULAR CERTIFICADO</option>';
	}   

    $('#accionCorrectiva').html(sresultado);
   	$("#accionCorrectiva").removeAttr("disabled");
});

$("#accionCorrectiva").change(function(){            	    
	if($("#accionCorrectiva").val()=='anular certificado'){															  
		$("#campoObservacion").hide();
		$("#etiquetaObservacion").hide();
		$("#anulacion").show();
	}else{
		$("#anulacion").hide();
		$("#anulado").hide();
		$("#guardar").show();
		$("#campoObservacion").show();
		$("#etiquetaObservacion").show();
	}

	if($("#accionCorrectiva").val()=='modificar certificado'){	
		$("#modificar1").show();
		$("#modificar2").show();
	}else{
		$("#modificar1").hide();
		$("#modificar2").hide();
	}	

	if($("#accionCorrectiva").val() == 'activar emision de certificado'){	
		$("#hrSuperior").show();
		$("#seccionJustificacion1").show();
		$("#seccionJustificacion2").show();
		$("#seccionJustificacion3").show();
		$("#hrInferior").show();
	}else{
		$("#hrSuperior").hide();
		$("#seccionJustificacion1").hide();
		$("#seccionJustificacion2").hide();
		$("#seccionJustificacion3").hide();
		$("#hrInferior").hide();
	}
	
});

$('#detalleMovilizacion tr').dblclick(function(event){	
	var id = $(this).attr('id');
	$('#idDetalleMovilizacion').val(id);
	$('#abrirFiscalizacionMovilizacion').attr('data-opcion','accionesMovilizacionProducto');    
	$('#abrirFiscalizacionMovilizacion').attr('data-destino','resultadoDetalleIdentifcadoresMovilizacion');
	$('#opcion').val('resultadoDetalleIdentifcadoresMovilizacion');
		abrir($("#abrirFiscalizacionMovilizacion"),event,false); 	
});

$("#abrirFiscalizacionMovilizacion").submit(function(event){
	event.preventDefault();
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($("#accionCorrectiva").val()=='fiscalizacion correcta'){	
		if($("#campoObservacion").val()=="" ){	
			 error = true;		
			$("#campoObservacion").addClass("alertaCombo");
			$("#estado").html("Por favor digite la observación de la fiscalización.").addClass('alerta');
		}
	}	

	if($("#accionCorrectiva").val()=='modificar certificado'){	
		if($("#identificadoresAgregados").val()=="" ){	
			 error = true;		
			$("#identificadoresAgregados").addClass("alertaCombo");
			$("#estado").html("Por favor modifique algun registro del certificado para poder guardar.").addClass('alerta');
		}
		if($("#campoObservacion").val()=="" ){	
			 error = true;		
			$("#campoObservacion").addClass("alertaCombo");
			$("#estado").html("Por favor digite la observación de la fiscalización.").addClass('alerta');
		}
	
		var totalProductos=0;
		$('#detalleMovilizacion tbody tr ').each(function (event) {

			totalProductos+=parseInt($(this).find("input[id='hCantidadd']").val());
		})

		$("#totalProductos").val(totalProductos);
		
		if(totalProductos==0){
			 error = true;
			 $("#estado").html("Por favor no se puede guardar cuando todos los registros estan en cero.").addClass('alerta');
		}			
	}	
	
	if($("#accionCorrectiva").val()=='inactivar emision de certificado'){	
		if($("#campoObservacion").val()=="" ){	
			 error = true;		
			$("#campoObservacion").addClass("alertaCombo");
			$("#estado").html("Por favor digite la observación de la fiscalización.").addClass('alerta');
		}
	}

	if($("#accionCorrectiva").val()=='anular certificado'){	
		
		if($("#observacionAnulacion").val()=="" ){	
			error = true;		
			$("#observacionAnulacion").addClass("alertaCombo");
			$("#estado").html("Por favor digite la observación de anulación.").addClass('alerta');
		}	
		
		if($("#motivoAnulacion").val()==0 ){	
			 error = true;		
			$("#motivoAnulacion").addClass("alertaCombo");
			$("#estado").html("Por favor seleccione el motivo de anulación.").addClass('alerta');
		}		
	}

	if($("#cantidadAnimales").val()=="" ){	
		 error = true;		
		$("#cantidadAnimales").addClass("alertaCombo");
		$("#estado").html("Por favor ingrese la cantidad de animales fiscalizados.").addClass('alerta');
	}

	if($("#lugarFiscalizacion").val()=="" ){	
		 error = true;		
		$("#lugarFiscalizacion").addClass("alertaCombo");
		$("#estado").html("Por favor seleccione un lugar de fiscalización.").addClass('alerta');
	}

	totalProductos=0;
	$('#detalleMovilizacion tbody tr ').each(function (event) {
		totalProductos+=parseInt($(this).find("input[id='hCantidadd']").val());
	})
	
	if($("#cantidadAnimales").val() > totalProductos){
	     error = true;
		 $("#estado").html("La cantidad de animales fiscalizados no puede ser mayor a la de movilizados.").addClass('alerta');
	} 

	if($("#accionCorrectiva").val() == 'activar emision de certificado'){
		if($("#campoObservacion").val()=="" ){	
			 error = true;		
			$("#campoObservacion").addClass("alertaCombo");
			$("#estado").html("Por favor digite la observación de la fiscalización.").addClass('alerta');
		}
	}	

	var banderaTicket='';
	var banderaDobleGuia='';
	var banderaMatadero='';
	
	$('#registrosDetalleMovilizacion tbody tr').each(function(){
		if( $(this).find("input[id='hCodigoOperacionDestino']").val()=='EDRSA' || $(this).find("input[id='hCodigoOperacion']").val()=='EDRSA')
			banderaDobleGuia='SI';

		if( $(this).find("input[id='hCodigoOperacionDestino']").val()=='FEASA')
			banderaTicket='SI';

		if($(this).find("input[id='hCodigoOperacionDestino']").val()=='FAEAI' )
			banderaMatadero='SI';
		
	});
	
	
	$("#banderaDobleGuia").val(banderaDobleGuia);
	$("#banderaTicket").val(banderaTicket);
	$("#banderaMatadero").val(banderaMatadero);
	
	if($("#accionCorrectiva").val()==0 ){	
		 error = true;		
		$("#accionCorrectiva").addClass("alertaCombo");
		$("#estado").html("Por favor seleccione la accion correctiva.").addClass('alerta');
	}
	
	if($("#resultado").val()==0 ){	
		 error = true;		
		$("#resultado").addClass("alertaCombo");
		$("#estado").html("Por favor seleccione el resultado.").addClass('alerta');
	}

	if($("#fechaFiscalizacion").val()==0 ){	
		 error = true;		
		$("#fechaFiscalizacion").addClass("alertaCombo");
		$("#estado").html("Por favor seleccione la fecha de fiscalización.").addClass('alerta');
	}

	if($("#usuarioResponsable").val()==""){
		error = true;
		$("#estado").html("Su sesión expiró, por favor ingrese nuevamente al sistema.").addClass('alerta');
	}	
	
	if (!error){
		$('#identificacionOperadorDestino').attr('disabled',false);
		$('#identificacionOperadorOrigen').attr('disabled',false);
		$('#identificacionOperadorDestino').attr('readonly','readonly');
		$('#identificacionOperadorOrigen').attr('readonly','readonly');
		$('#abrirFiscalizacionMovilizacion').attr('data-opcion','guardarFiscalizacion');    
		$('#abrirFiscalizacionMovilizacion').attr('data-destino','detalleItem');
		ejecutarJson("#abrirFiscalizacionMovilizacion");
		$('#guardar').attr('disabled','disabled');
	 };
});

$("#reset").click(function(event){
	$('input[name="xx"]').attr('id',idMovilizacion);
	abrir($("#detalleItem input[name='xx']"),null,true);
});

function soloNumeros() { 
	if ((event.keyCode < 48) || (event.keyCode > 57))
		event.returnValue = false;	
}
</script>