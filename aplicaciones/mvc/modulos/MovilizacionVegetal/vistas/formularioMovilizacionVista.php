<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<?php 
    if($_SESSION['nombreProvincia'] === null){//Es operador
        $identificadorOperador = $this->consultarTipoUsuario($_SESSION['usuario']);
        $operador = true;
    }else{
        $operador = false;
    }
?>

<!-- Despliegue de datos -->
<div id="datosMovilizacion">
		
	<fieldset>
		<legend>Datos Generales</legend>
		
		<div data-linea="1">
			<label for="tipo_solicitud">Tipo de Solicitud:</label> 
    			<?php echo $this->modeloMovilizacion->getTipoSolicitud(); ?>	
		</div>

		<div data-linea="2">
			<label for="id_provincia_emision">Provincia Emisión:</label>
     			<?php echo $this->modeloMovilizacion->getProvinciaEmision(); ?>	
		</div>

		<div data-linea="2">
			<label for="id_canton_emision">Cantón Emisión:</label>
    			<?php echo $this->modeloMovilizacion->getCantonEmision(); ?>
		</div>

		<div data-linea="3">
			<label for="id_oficina_emision">Oficina Emisión:</label> 
    			<?php echo $this->modeloMovilizacion->getOficinaEmision(); ?>
		</div>		
		
		<div data-linea="4">
			<label>Nº Permiso:</label>
			<?php echo $this->modeloMovilizacion->getNumeroPermiso(); ?>
		</div>
		
		<div data-linea="5">
			<label>Fecha Emisión:</label>
			<?php echo date('Y-m-d H:i',strtotime($this->modeloMovilizacion->getFechaCreacion())); ?>
		</div>
		
		<div data-linea="6">
			<label for="fecha_inicio_movilizacion">Fecha Inicio de Vigencia: </label>
				<?php echo date('Y-m-d',strtotime($this->modeloMovilizacion->getFechaInicioMovilizacion())) . ' ' . date('H:i',strtotime($this->modeloMovilizacion->getHoraInicioMovilizacion())); ?>
		</div>

		<div data-linea="7">
			<label for="fecha_inicio_movilizacion">Fecha Fin de Vigencia: </label>
				<?php echo date('Y-m-d',strtotime($this->modeloMovilizacion->getFechaFinMovilizacion())) . ' ' . date('H:i',strtotime($this->modeloMovilizacion->getHoraFinMovilizacion())); ?>
		</div>
		
		<div data-linea="0">
			<label>Ver Certificado:</label>
			<?php echo ($this->modeloMovilizacion->getRutaCertificado()==''? '<span class="alerta">No ha generado ningún certificado</span>':'<a href="'.$this->modeloMovilizacion->getRutaCertificado().'" target="_blank" class="archivo_cargado" id="archivo_cargado">Clic aquí para ver el Certificado</a>')?>
		</div>
	</fieldset>

	<fieldset>
		<legend>Datos Origen</legend>
		
		<div data-linea="8">
			<label for="identificador_operador_origen">Identificador Operador:</label> 
    			<?php echo $this->modeloMovilizacion->getIdentificadorOperadorOrigen(); ?>
		</div>

		<div data-linea="9">
			<label for="nombre_operador_origen">Nombre Operador:</label> 
				<?php echo $this->modeloMovilizacion->getNombreOperadorOrigen(); ?>
		</div>

		<div data-linea="10">
			<label id="lid_sitio_origen">Sitio: </label> 
				<?php echo $this->modeloMovilizacion->getSitioOrigen(); ?>
		</div>
		
		<div data-linea="10">
			<label id="lid_sitio_origen">Código de Sitio: </label> 
				<?php echo $this->modeloMovilizacion->getCodigoSitioOrigen(); ?>
		</div>
		
		<div data-linea="11">
			<label for="id_provincia_origen">Provincia:</label>
    			<?php echo $this->modeloMovilizacion->getProvinciaOrigen(); ?>
		</div>
		
		<div data-linea="11">
			<label for="canton_origen">Cantón:</label>
    			<?php echo $this->modeloMovilizacion->getCantonOrigen(); ?>
		</div>
		
		<div data-linea="12">
			<label for="parroquia_origen">Parroquia:</label>
    			<?php echo $this->modeloMovilizacion->getParroquiaOrigen(); ?>
		</div>
		
	</fieldset>
	
	<fieldset>
		<legend>Datos Destino</legend>
		
		<div data-linea="13">
			<label for="identificador_operador_destino">Identificador Operador:	</label> 
				<?php echo $this->modeloMovilizacion->getIdentificadorOperadorDestino(); ?>
		</div>

		<div data-linea="14">
			<label for="nombre_operador_destino">Nombre Operador: </label>
				<?php echo $this->modeloMovilizacion->getNombreOperadorDestino(); ?>
		</div>
		
		<div data-linea="15">
			<label id="lid_sitio_destino">Sitio: </label>
				<?php echo $this->modeloMovilizacion->getSitioDestino(); ?>
		</div>
		
		<div data-linea="15">
			<label id="lid_sitio_destino">Código de Sitio: </label>
				<?php echo $this->modeloMovilizacion->getCodigoSitioDestino(); ?>
		</div>
		
		<div data-linea="16">
			<label for="id_provincia_destino">Provincia: </label> 				
				<?php echo $this->modeloMovilizacion->getProvinciaDestino(); ?>
		</div>

		<div data-linea="16">
			<label for="canton_destino">Cantón: </label> 				
				<?php echo $this->modeloMovilizacion->getCantonDestino(); ?>
		</div>
		
		<div data-linea="17">
			<label for="parroquia_destino">Parroquia: </label> 				
				<?php echo $this->modeloMovilizacion->getParroquiaDestino(); ?>
		</div>

	</fieldset>

	<fieldset>
		<legend>Datos de Movilización</legend>

		<div data-linea="18">
			<label for="medio_transporte">Medio Transporte: </label> 
    			<?php echo $this->modeloMovilizacion->getMedioTransporte(); ?>
		</div>

		<div data-linea="18">
			<label for="placa_transporte">Placa Transporte: </label> 
				<?php echo $this->modeloMovilizacion->getPlacaTransporte(); ?>
		</div>

		<div data-linea="19">
			<label for="identificador_conductor">Identificador Conductor: </label>
				<?php echo $this->modeloMovilizacion->getIdentificadorConductor(); ?>
		</div>

		<div data-linea="19">
			<label for="nombre_conductor">Nombre Conductor: </label> 
				<?php echo $this->modeloMovilizacion->getNombreConductor(); ?>
		</div>		

		<div data-linea="20">
			<label for="observacion_transporte">Observación: </label> 
				<?php echo $this->modeloMovilizacion->getObservacionTransporte(); ?>
		</div>

	</fieldset>
	
	<fieldset>
		<legend>Detalle de Productos a Movilizar</legend>
    	
    	<div data-linea="22">
    		<div id="tablaMovilizaciones" name="tablaMovilizaciones"> </div>
		</div>
		
	</fieldset>

</div>

<!-- Formulario de Ingreso -->
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>MovilizacionVegetal' data-opcion='movilizacion/generarCertificadoMovilizacion' data-destino="detalleItem" method="post">
	<input type="hidden" id="id_movilizacion" name="id_movilizacion" value="<?php echo $this->modeloMovilizacion->getIdMovilizacion(); ?>"/>
	<input type="hidden" id="estado_movilizacion" name="estado_movilizacion" value="<?php echo $this->modeloMovilizacion->getEstadoMovilizacion(); ?>" />
	<input type="hidden" id="id" name="id" />
		
	<fieldset>
		<legend>Datos Generales</legend>

		<div data-linea="1">
			<label for="tipo_solicitud">Tipo de Solicitud:</label> 
			<select id="tipo_solicitud" name="tipo_solicitud" required>
				<option value="">Seleccionar....</option>
				<option value="Fitosanitario">Fitosanitario</option>
            </select>
		</div>

		<div data-linea="2">
			<label for="id_provincia_emision">Provincia Emisión:</label>
 			<select id="id_provincia_emision" name="id_provincia_emision" required >
                <option value="">Seleccionar....</option>
                <?php
                    echo $this->comboProvinciasEc($this->modeloMovilizacion->getIdProvinciaEmision());
                ?>
            </select>	
            
            <input type="hidden" id="provincia_emision" name="provincia_emision" />		
		</div>

		<div data-linea="2">
			<label for="id_canton_emision">Cantón Emisión:</label>
			<select id="id_canton_emision" name="id_canton_emision" <?php echo ($operador==false?'required':'');?> >
                <option value="">Seleccionar....</option>
            </select>
            <input type="hidden" id="canton_emision" name="canton_emision" />
		</div>

		<div data-linea="3">
			<label for="id_oficina_emision">Oficina Emisión:</label> 
			<select id="id_oficina_emision" name="id_oficina_emision" <?php echo ($operador==false?'required':'');?> >
                <option value="">Seleccionar....</option>
            </select>
			<input type="hidden" id="oficina_emision" name="oficina_emision" />
		</div>		
	</fieldset>

	<fieldset>
		<legend>Datos Origen</legend>
		
		<div data-linea="4">
			<label for="id_provincia_origen">Provincia:</label>
			<select id="ide_provincia_origen" name="ide_provincia_origen" required >
                <option value="">Seleccionar....</option>
                <?php
                    echo $this->comboProvinciasEc($this->modeloMovilizacion->getIdProvinciaOrigen());
                ?>
            </select>	
            
            <input type="hidden" id="id_provincia_origen" name="id_provincia_origen" />
            <input type="hidden" id="provincia_origen" name="provincia_origen" />
		</div>

		<div data-linea="5">
			<label for="identificador_operador_origen">Identificador Operador:</label> 
			<input type="text" id="id_operador_origen" name="id_operador_origen" 
				value="<?php echo ($operador == true? $identificadorOperador['identificador']:"");  ?>" 
				placeholder="Identificador del operador de origen" required maxlength="13" 
				<?php echo ($operador == true? "readonly='readonly'":""); ?> />
				
			<input type="hidden" id="identificador_operador_origen" name="identificador_operador_origen" 
				value="<?php echo ($operador == true? $identificadorOperador['identificador']:"");  ?>" 
				placeholder="Identificador del operador de origen" required maxlength="13" 
				<?php echo ($operador == true? "readonly='readonly'":""); ?> />
		</div>

		<div data-linea="5">
			<label for="nombre_operador_origen">Nombre Operador:</label> 
			<input type="text" id="nom_operador_origen" name="nom_operador_origen" 
				value="<?php echo ($operador == true? $identificadorOperador['razon_social']:""); ?>"
				placeholder="Nombre del operador de origen" required maxlength="128" 
				<?php echo ($operador == true? "readonly='readonly'":""); ?>/>
				
			<input type="hidden" id="nombre_operador_origen" name="nombre_operador_origen" 
				value="<?php echo ($operador == true? $identificadorOperador['razon_social']:""); ?>"
				placeholder="Nombre del operador de origen" required maxlength="128" 
				<?php echo ($operador == true? "readonly='readonly'":""); ?>/>
		</div>
		
		<div data-linea="6">
			<button type="button" class="buscar" onclick="buscarOperadorSitioOrigen()" >Buscar</button>
		</div>
		
		<hr id ="dO"/>

		<div data-linea="7">
			<label id="lid_sitio_origen">Sitio Origen: </label> 
			<select id="id_sitio_origen" name="id_sitio_origen" required > 
				<option value="">Seleccionar....</option>
            </select>

			<input type="hidden" id="sitio_origen" name="sitio_origen" value="<?php echo $this->modeloMovilizacion->getSitioOrigen(); ?>" />
			<input type="hidden" id="codigo_sitio_origen" name="codigo_sitio_origen" value="<?php echo $this->modeloMovilizacion->getCodigoSitioOrigen(); ?>" />
			<input type="hidden" id="codigo_provincia_origen" name="codigo_provincia_origen" />
		</div>

		<div data-linea="8">
			<label id="lid_area_origen">Área Origen: </label> 
			<select id="id_area_origen" name="id_area_origen" required > 
				<option value="">Seleccionar....</option>
            </select>	
		</div>

		<div data-linea="8">
		<label id="lid_codigo_area_origen">Código área: </label> 
			<input type="text" id="codigo_area_origen_text" value="<?php echo $this->modeloMovilizacion->getCodigoAreaOrigen(); ?>" />
			<input type="hidden" id="area_origen" name="area_origen" value="<?php echo $this->modeloMovilizacion->getAreaOrigen(); ?>" />
			<input type="hidden" id="codigo_area_origen" name="codigo_area_origen" value="<?php echo $this->modeloMovilizacion->getCodigoAreaOrigen(); ?>" />
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Datos Destino</legend>
		
		<div data-linea="8">
			<label for="id_provincia_destino">Provincia: </label> 				
			<select id="ide_provincia_destino" name="ide_provincia_destino" required >
                <option value="">Seleccionar....</option>
                <?php
                    echo $this->comboProvinciasEc($this->modeloMovilizacion->getIdProvinciaDestino());
                ?>
        	</select>
			
			<input type="hidden" id="id_provincia_destino" name="id_provincia_destino" />
			<input type="hidden" id="provincia_destino" name="provincia_destino" />
		</div>

		<div data-linea="9">
			<label for="identificador_operador_destino">Identificador Operador:	</label> 
			<input type="text" id="id_operador_destino" name="id_operador_destino" 
				placeholder="Identificador del operador de destino" required maxlength="13" />
			
			<input type="hidden" id="identificador_operador_destino" name="identificador_operador_destino" 
				placeholder="Identificador del operador de destino" required maxlength="13" />
		</div>

		<div data-linea="9">
			<label for="nombre_operador_destino">Nombre Operador: </label>
			<input type="text" id="nom_operador_destino" name="nom_operador_destino" 
				placeholder="Nombre del operador de destino" required maxlength="128" />
				
			<input type="hidden" id="nombre_operador_destino" name="nombre_operador_destino" 
				placeholder="Nombre del operador de destino" required maxlength="128" />			
		</div>

		<div data-linea="10">
			<button type="button" class="buscar" onclick="buscarOperadorSitioDestino()" >Buscar</button>
		</div>
		
		<hr id ="dD"/>
		
		<div data-linea="11">
			<label id="lid_sitio_destino">Sitio Destino: </label>
			<select id="id_sitio_destino" name="id_sitio_destino" required >
                <option value="">Seleccionar....</option>
            </select>

			<input type="hidden" id="sitio_destino" name="sitio_destino" value="<?php echo $this->modeloMovilizacion->getSitioDestino(); ?>" />
			<input type="hidden" id="codigo_sitio_destino" name="codigo_sitio_destino" value="<?php echo $this->modeloMovilizacion->getCodigoSitioDestino(); ?>" />
			<input type="hidden" id="codigo_provincia_destino" name="codigo_provincia_destino" />
		</div>

		<div data-linea="12">
			<label id="lid_area_destino">Área Destino: </label> 
			<select id="id_area_destino" name="id_area_destino" required > 
				<option value="">Seleccionar....</option>
            </select>	
		</div>

		<div data-linea="12">
			<label id="lid_codigo_area_destino">Código área: </label>
			<input type="text" id="codigo_area_destino_text" value="<?php echo $this->modeloMovilizacion->getCodigoAreaDestino(); ?>" />
			<input type="hidden" id="area_destino" name="area_destino" value="<?php echo $this->modeloMovilizacion->getAreaDestino(); ?>" />
			<input type="hidden" id="codigo_area_destino" name="codigo_area_destino" value="<?php echo $this->modeloMovilizacion->getCodigoAreaDestino(); ?>" />
			
		</div>

	</fieldset>

	<fieldset>
		<legend>Datos de Movilización</legend>

		<div data-linea="12">
			<label for="medio_transporte">Medio Transporte: </label> 
			<select id="medio_transporte" name="medio_transporte" required>
				<option value="">Seleccionar....</option>
				<option value="Terrestre">Terrestre</option>
            </select>
		</div>

		<div data-linea="12">
			<label for="placa_transporte">Placa Transporte: </label> 
			<input type="text" id="placa_transporte" name="placa_transporte" value="<?php echo $this->modeloMovilizacion->getPlacaTransporte(); ?>"
				placeholder="Ej: AAA-0000" data-er="[A-Z]{3}-[0-9]{3,4}" data-inputmask="'mask': 'aaa-9999'" required maxlength="8" 
				onblur="this.value=this.value.toUpperCase()" />
		</div>

		<div data-linea="13">
			<label for="identificador_conductor">Identificador Conductor: </label>
			<input type="text" id="identificador_conductor" name="identificador_conductor" value="<?php echo $this->modeloMovilizacion->getIdentificadorConductor(); ?>"
				placeholder="Identificador del conductor del vehículo" required maxlength="13" />
		</div>

		<div data-linea="13">
			<label for="nombre_conductor">Nombre Conductor: </label> 
			<input type="text" id="nombre_conductor" name="nombre_conductor" value="<?php echo $this->modeloMovilizacion->getNombreConductor(); ?>"
				placeholder="Nombre del conductor del vehículo" required maxlength="128" />
		</div>

		<div data-linea="14">
			<label for="fecha_inicio_movilizacion">Fecha de Movilización: </label>
			<input type="text" id="fecha_inicio_movilizacion" name="fecha_inicio_movilizacion" value="<?php echo date('Y-m-d') ?>" readonly="readonly" required  /> <!-- < ?php echo $this->modeloMovilizacion->getFechaInicioMovilizacion(); ?> -->
			<input type="text" id="fecha_fin_movilizacion" name="fecha_fin_movilizacion" value="<?php echo date('Y-m-d',strtotime('+1 day', strtotime(date('Y-m-d'))));?>" readonly="readonly"/> <!-- < ?php echo $this->modeloMovilizacion->getFechaInicioMovilizacion(); ?> -->
		</div>

		<div data-linea="14">
			<label for="hora_inicio_movilizacion">Hora de Movilización: </label>
			<input type="time" id="hora_inicio_movilizacion" name="hora_inicio_movilizacion" value="<?php echo $this->modeloMovilizacion->getHoraInicioMovilizacion(); ?>"
				placeholder="Hora de inicio de la movilización" required maxlength="8" />
		</div>

		<div data-linea="15">
			<label for="observacion_transporte">Observación: </label> 
			<input type="text" id="observacion_transporte" name="observacion_transporte" value="<?php echo $this->modeloMovilizacion->getObservacionTransporte(); ?>"
				placeholder="Observaciones del medio de transporte" required maxlength="1024" />
		</div>

	</fieldset>
	
	<fieldset>
		<legend>Detalle de Productos a Movilizar</legend>
		
		<!--div data-linea="16">
			<label for="id_area_origen">Origen: </label>
			<select id="id_area_origen" name="id_area_origen"  >
                <option value="">Seleccionar....</option>
            </select>

			<input type="hidden" id="area_origen" name="area_origen" value="<?php echo $this->modeloDetalleMovilizacion->getAreaOrigen(); ?>" />
		</div>		
		
		<div data-linea="16">
			<label for="id_area_destino">Destino: </label>
			<select id="id_area_destino" name="id_area_destino" >
                <option value="">Seleccionar....</option>
            </select>
			<input type="hidden" id="area_destino" name="area_destino" value="<?php echo $this->modeloDetalleMovilizacion->getAreaDestino(); ?>"
			placeholder="Identificador del área de destino del producto a movilizar"  maxlength="8" />
		</div-->				

		<div data-linea="17">
			<label for="id_subtipo_producto">Subtipo Producto: </label>
			<select id="id_subtipo_producto" name="id_subtipo_producto" >
                <option value="">Seleccionar....</option>
            </select>
            
			<input type="hidden" id="subtipo_producto" name="subtipo_producto" value="<?php echo $this->modeloDetalleMovilizacion->getSubtipoProducto(); ?>"
				placeholder="Nombre del subtipo de producto"  maxlength="64" />
		</div>				

		<div data-linea="17">
			<label for="id_producto">Producto: </label>
			<select id="id_producto" name="id_producto"  >
                <option value="">Seleccionar....</option>
            </select>
			
			<input type="hidden" id="producto" name="producto" value="<?php echo $this->modeloDetalleMovilizacion->getProducto(); ?>"
				placeholder="Nombre del producto a movilizar"  maxlength="64" />
		</div>				

		<div data-linea="18">
			<label for="unidad">Unidad: </label>
			<select id="unidad" name="unidad"  >
                <option value="">Seleccionar....</option>
                <?php
                echo $this->comboUnidadesMovilizaciones($this->modeloDetalleMovilizacion->getUnidad());
                ?>
            </select>
		</div>				

		<div data-linea="18">
			<label for="cantidad">Cantidad: </label>
			<input type="number" id="cantidad" name="cantidad" data-er="^[0-9]+$"
			placeholder="Cantidad del producto a movilizar" maxlength="4" min="1" step="1"/>
		</div>	
		
		<div data-linea="19">
			<label for="requisitos">Requisitos fitosanitarios según el rubro: </label>
			<hr />
			<div id="requisitos" style="width:100%"> </div>
		</div>
		
		<hr />
	
		<div data-linea="21">
    		<button type="button" class="mas" id="btnAgregarProductos">Agregar</button>
    	</div>
    	
    	<hr />
    	
    	<div data-linea="22">
			<table id="tbItems" style="width:100%">
				<thead>
					<tr>
						<th style="width: 10%;">Nº Reg</th>
						<th style="width: 15%;">Origen</th>
                        <th style="width: 15%;">Destino</th>
                        <th style="width: 15%;">Subtipo Producto</th>
                        <th style="width: 15%;">Producto</th>
                        <th style="width: 15%;">Unidad</th>
                        <th style="width: 5%;">Cantidad</th>
                        <th style="width: 10%;"></th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
		
	</fieldset>
	
	<div id="checkUsuario" data-linea="23">
		<label for="cumplimiento">Acepto y declaro cumplir con los requisitos fitosanitarios: </label>
		<input type="checkbox" id="cumplimiento" name="cumplimiento" <?php echo ($operador==true?'required':'');?> />
	</div>
	
	<div id="cargarMensajeTemporal"></div>
	
	<div data-linea="24">
		<button type="submit" class="guardar">Guardar</button>
	</div>
</form>

<script type="text/javascript">
var operador = <?php echo json_encode($operador); ?>;
var bandera = <?php echo json_encode($this->formulario); ?>;
var combo = "<option>Seleccione....</option>";

	$(document).ready(function() {
		$("#formulario").hide();
		$("#datosMovilizacion").hide();

		if(operador==true){
			$("#checkUsuario").show();
		}else{
			$("#checkUsuario").hide();
		}
		
		if(bandera == 'nuevo'){
			$("#formulario").show();
			$("#datosMovilizacion").hide();
		}else{
			fn_mostrarDetalleMovilizacion();
			$("#formulario").hide();
			$("#datosMovilizacion").show();
		}
		
		construirValidador();
		distribuirLineas();

		$("#fecha_fin_movilizacion").hide();
		$("#estado").html("");
	 });

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}
	

	//------------------

	//Sección Datos Generales
    $("#id_provincia_emision").change(function () {
    	$("#id_canton_emision").html(combo);
    	$("#id_oficina_emision").html(combo);
    	
        if ($(this).val !== "") {
            fn_cargarCantones();
            $("#provincia_emision").val($("#id_provincia_emision option:selected").text());
        }
    }); 

    $("#id_canton_emision").change(function () {
    	$("#id_oficina_emision").html(combo);
    	
        if ($(this).val !== "") {
            fn_cargarOficinas();
            $("#canton_emision").val($("#id_canton_emision option:selected").text());
        }
    }); 

	$("#id_oficina_emision").change(function () {
        if ($(this).val !== "") {
            $("#oficina_emision").val($("#id_oficina_emision option:selected").text());
        }
    });

	//------------------

    //Sección Datos Origen
    $("#ide_provincia_origen").change(function () {
    	$("#id_provincia_origen").val("");
    	$("#provincia_origen").val("");
    	if(!operador){
        	$("#id_operador_origen").val("");
        	$("#identificador_operador_origen").val("");
        	$("#nom_operador_origen").val("");
        	$("#nombre_operador_origen").val("");
    	}
    	$("#id_sitio_origen").html(combo);
    	$("#sitio_origen").val("");
    	$("#codigo_sitio_origen").val("");
    	$("#codigo_provincia_origen").val("");
    	$("#tbItems tbody").html("");
    	
        /*if ($(this).val !== "") {
            $("#id_provincia_origen").val($("#ide_provincia_origen option:selected").val());
            $("#provincia_origen").val($("#ide_provincia_origen option:selected").text());
        }else{
        	$("#id_provincia_origen").val("");
        	$("#provincia_origen").val("");
        }*/
    });

    $("#id_sitio_origen").change(function () {
    	$("#id_provincia_origen").val("");
    	$("#provincia_origen").val("");
    	$("#identificador_operador_origen").val("");
    	$("#nombre_operador_origen").val("");
    	$("#sitio_origen").val("");
    	$("#codigo_sitio_origen").val("");
    	$("#codigo_provincia_origen").val("");
    	$("#id_area_origen").html(combo);
    	$("#area_origen").val("");
    	$("#id_area_destino").html(combo);
    	$("#area_destino").val("");
		$("#codigo_area_origen_text").val("");
    	//--
    	$("#ide_provincia_destino").val("");
    	$("#id_provincia_destino").val("");
    	$("#provincia_destino").val("");
    	$("#id_operador_destino").val("");
    	$("#identificador_operador_destino").val("");
    	$("#nom_operador_destino").val("");
    	$("#nombre_operador_destino").val("");
    	$("#id_sitio_destino").html(combo);
    	$("#sitio_destino").val("");
    	$("#codigo_sitio_destino").val("");
    	$("#codigo_provincia_destino").val("");
    	$("#id_area_destino").html(combo);
    	$("#area_destino").val("");
    	$("#tbItems tbody").html("");
    	//--
    	limpiarDetalle();
    	$("#tbItems tbody").html("");
    	
        if ($("#id_sitio_origen option:selected").val() !== "") {

        	if($("#id_sitio_origen").text() != $("#sitio_origen").val()){
            	$("#tbItems tbody").html("");
            	limpiarDetalle();
            	
            	//No puede cambiar el operador y su sitio de origen
            	$("#identificador_operador_origen").val($("#id_sitio_origen option:selected").attr('data-identificador'));
            	$("#nombre_operador_origen").val($("#id_sitio_origen option:selected").attr('data-nombre'));
            	
            	$("#id_operador_origen").val($("#id_sitio_origen option:selected").attr('data-identificador'));
            	$("#nom_operador_origen").val($("#id_sitio_origen option:selected").attr('data-nombre'));
    
            	$("#sitio_origen").val($("#id_sitio_origen option:selected").attr('data-nombre_sitio'));
            	$("#codigo_sitio_origen").val($("#id_sitio_origen option:selected").attr('data-codigo_sitio'));
            	$("#codigo_provincia_origen").val($("#id_sitio_origen option:selected").attr('data-codigo_provincia'));

            	$("#id_provincia_origen").val($("#ide_provincia_origen option:selected").val());
                $("#provincia_origen").val($("#ide_provincia_origen option:selected").text());

            	//Cargar las áreas con productos habilitados para movilización de SV
            	buscarOperadorAreaOrigen();
        	}
        }else{
            if(operador == false){
            	$("#sitio_origen").val("");
            	$("#codigo_sitio_origen").val("");
            	$("#codigo_provincia_origen").val("");
            }
        }
    });

  //------------------

    //Sección Detalle de Productos a Movilizar
    $("#id_area_origen").change(function () {
    		
    	$("#area_origen").val("");
    	$("#id_area_destino").html(combo);
    	$("#area_destino").val("");
    	$("#id_subtipo_producto").html(combo);
    	$("#subtipo_producto").val("");
    	$("#id_producto").html(combo);
    	$("#producto").val("");
    	//--
    	$("#ide_provincia_destino").val("");
    	$("#id_provincia_destino").val("");
    	$("#provincia_destino").val("");
    	$("#id_operador_destino").val("");
    	$("#identificador_operador_destino").val("");
    	$("#nom_operador_destino").val("");
    	$("#nombre_operador_destino").val("");
    	$("#id_sitio_destino").html(combo);
    	$("#sitio_destino").val("");
    	$("#codigo_sitio_destino").val("");
    	$("#codigo_provincia_destino").val("");
    	$("#id_area_destino").html(combo);
    	$("#area_destino").val("");
    	$("#tbItems tbody").html("");

		if (($("#id_area_origen option:selected").val() !== "") && ($("#id_sitio_destino option:selected").val() !== "")) {
			$("#area_origen").val($("#id_area_origen option:selected").text());
			$("#codigo_area_origen").val($("#id_area_origen option:selected").attr('data-codigo_area'));
			$("#codigo_area_origen_text").val($("#id_area_origen option:selected").attr('data-codigo_area'));
        }else{
        	$("#id_area_origen").val("");
        	$("#area_origen").val("");
        	$("#id_area_destino").val("");
        	$("#area_destino").val("");

        	if(!$.trim($("#id_area_origen").val())){
    			$("#id_area_origen").addClass("alertaCombo");
    		}

        	if(!$.trim($("#id_sitio_destino").val())){
    			$("#id_sitio_destino").addClass("alertaCombo");
    		}

        	$("#estado").html("Debe seleccionar la información solicitada.").addClass("alerta")
        }
    });

  //------------------

  //Sección Datos Destino
    $("#ide_provincia_destino").change(function () {
    	$("#id_provincia_destino").val("");
    	$("#provincia_destino").val("");
    	$("#id_operador_destino").val("");
    	$("#identificador_operador_destino").val("");
    	$("#nom_operador_destino").val("");
    	$("#nombre_operador_destino").val("");
    	$("#id_sitio_destino").html(combo);
    	$("#sitio_destino").val("");
    	$("#codigo_sitio_destino").val("");
    	$("#codigo_provincia_destino").val("");
    	$("#id_area_destino").html(combo);
    	$("#area_destino").val("");
    	$("#tbItems tbody").html("");
    	
        /*if ($("#ide_provincia_destino option:selected").val() !== "") {
            $("#id_provincia_destino").val($("#ide_provincia_destino option:selected").val());
            $("#provincia_destino").val($("#ide_provincia_destino option:selected").text());
        }else{
        	$("#id_provincia_destino").val("");
        	$("#provincia_destino").val("");
        }*/
    });

    $("#id_sitio_destino").change(function () {
    	$("#id_provincia_destino").val("");
    	$("#provincia_destino").val("");
    	$("#id_operador_destino").val("");
    	$("#identificador_operador_destino").val("");
    	$("#nom_operador_destino").val("");
    	$("#nombre_operador_destino").val("");
    	$("#sitio_destino").val("");
    	$("#codigo_sitio_destino").val("");
    	$("#codigo_provincia_destino").val("");
    	$("#id_area_destino").html(combo);
    	$("#area_destino").val("");
    	$("#tbItems tbody").html("");
		$("#codigo_area_destino_text").val("");
    	
        if ($(this).val !== "") {
			
            if($("#id_sitio_destino").text() != $("#sitio_destino").val()){
            	$("#tbItems tbody").html("");
            	limpiarDetalle();
            	
            	//No puede cambiar el operador y su sitio de destino            	
            	$("#id_operador_destino").val($("#id_sitio_destino option:selected").attr('data-identificador'));
            	$("#nom_operador_destino").val($("#id_sitio_destino option:selected").attr('data-nombre'));

            	$("#identificador_operador_destino").val($("#id_sitio_destino option:selected").attr('data-identificador'));
            	$("#nombre_operador_destino").val($("#id_sitio_destino option:selected").attr('data-nombre'));
    
            	$("#sitio_destino").val($("#id_sitio_destino option:selected").attr('data-nombre_sitio'));
            	$("#codigo_sitio_destino").val($("#id_sitio_destino option:selected").attr('data-codigo_sitio'));
            	$("#codigo_provincia_destino").val($("#id_sitio_destino option:selected").attr('data-codigo_provincia'));

            	$("#id_provincia_destino").val($("#ide_provincia_destino option:selected").val());
				$("#provincia_destino").val($("#ide_provincia_destino option:selected").text());
				
				buscarOperadorAreaDestino();

				buscarSubtipoProducto();     
            }
        }else{
        	$("#sitio_destino").val("");
        	$("#codigo_sitio_destino").val("");
        	$("#codigo_provincia_destino").val("");
        }		
    });

    $("#id_area_destino").change(function () {
		$("#area_destino").html("");
		$("#tbItems tbody").html("");
    	
        if ($("#id_area_destino option:selected").val() !== "") {
			$("#area_destino").val($("#id_area_destino option:selected").text());
			$("#codigo_area_destino").val($("#id_area_destino option:selected").attr('data-codigo_area'));
			$("#codigo_area_destino_text").val($("#id_area_destino option:selected").attr('data-codigo_area'));
        }else{
        	$("#area_destino").val("");
        }
		
    });

    $("#id_subtipo_producto").change(function () {
    	$("#id_producto").html(combo);
    	$("#producto").val("");    	
        
        if ($("#id_subtipo_producto option:selected").val() !== "") {
            $("#subtipo_producto").val($("#id_subtipo_producto option:selected").text());

            buscarProducto();
        }else{
        	$("#subtipo_producto").val("");
        }
    });

    $("#id_producto").change(function () {
        if ($("#id_producto option:selected").val() !== "") {
            $("#producto").val($("#id_producto option:selected").text());

            //Función para cargar los requisitos fitosanitarios de movilización en pantalla
            buscarRequisitosProducto();
        }else{
        	$("#producto").val("");
        }
    });

    $("#fecha_inicio_movilizacion").datepicker({ 
	    changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd',
	    onSelect: function(dateText, inst) {
        	var fecha=new Date($('#fecha_inicio_movilizacion').datepicker('getDate')); 
        	fecha.setDate(fecha.getDate()+1);	 
      		$('#fecha_fin_movilizacion').datepicker('setDate',fecha);
	    }
	 });

    $("#fecha_fin_movilizacion").datepicker({ 
	    changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd'
	 });

	$("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;
		
		//Sección Datos Generales
		if(!$.trim($("#tipo_solicitud").val())){
			error = true;
			$("#tipo_solicitud").addClass("alertaCombo");
		}
		
        if(!$.trim($("#id_provincia_emision").val())){
        	error = true;
        	$("#id_provincia_emision").addClass("alertaCombo");
		}
		
        if (!$.trim($("#id_canton_emision").val())) {
        	error = true;
        	$("#id_canton_emision").addClass("alertaCombo");
        }

        if(!$.trim($("#id_oficina_emision").val())){
        	error = true;
			$("#id_oficina_emision").addClass("alertaCombo");
		}
		
		//Sección Datos Origen
        if(!$.trim($("#id_provincia_origen").val())){
        	error = true;
			$("#id_provincia_origen").addClass("alertaCombo");
		}
		
        if(!$.trim($("#identificador_operador_origen").val())){
        	error = true;
        	$("#identificador_operador_origen").addClass("alertaCombo");
		}
		
        if (!$.trim($("#nombre_operador_origen").val())) {
        	error = true;
        	$("#nombre_operador_origen").addClass("alertaCombo");
        }

        if(!$.trim($("#id_sitio_origen").val())){
        	error = true;
			$("#id_sitio_origen").addClass("alertaCombo");
		}
		
		//Sección Datos Destino
        if(!$.trim($("#id_provincia_destino").val())){
        	error = true;
			$("#id_provincia_destino").addClass("alertaCombo");
		}
		
        if(!$.trim($("#identificador_operador_destino").val())){
        	error = true;
        	$("#identificador_operador_destino").addClass("alertaCombo");
		}
		
        if (!$.trim($("#nombre_operador_destino").val())) {
        	error = true;
        	$("#nombre_operador_destino").addClass("alertaCombo");
        }

        if(!$.trim($("#id_sitio_destino").val())){
        	error = true;
			$("#id_sitio_destino").addClass("alertaCombo");
		}

		//Sección Datos Movilización
        if(!$.trim($("#medio_transporte").val())){
        	error = true;
			$("#medio_transporte").addClass("alertaCombo");
		}
		
        if(!$.trim($("#placa_transporte").val())){
        	error = true;
        	$("#placa_transporte").addClass("alertaCombo");
		}
		
        if (!$.trim($("#identificador_conductor").val())) {
        	error = true;
        	$("#identificador_conductor").addClass("alertaCombo");
        }

        if(!$.trim($("#nombre_conductor").val())){
        	error = true;
			$("#nombre_conductor").addClass("alertaCombo");
		}
		
        if(!$.trim($("#fecha_inicio_movilizacion").val())){
        	error = true;
        	$("#fecha_inicio_movilizacion").addClass("alertaCombo");
		}
		
        if (!$.trim($("#hora_inicio_movilizacion").val())) {
        	error = true;
        	$("#hora_inicio_movilizacion").addClass("alertaCombo");
        }

        if(!$.trim($("#observacion_transporte").val())){
        	error = true;
			$("#observacion_transporte").addClass("alertaCombo");
		}

        if(operador == true){
    		//Verificación del check
    		if(!$('#cumplimiento').is(':checked')){
    			error = true;
    			$("#cumplimiento").addClass("alertaCombo");
    		}
        }

		//Información en tabla de detalle
		if (($("#iSubtipoProducto").length > 0)){
			error = false;
		}else{
			error = true;
			$("#estado").html("Por favor ingrese por lo menos un producto a movilizar.").addClass("alerta");
		}
		
		if (!error) {
			$("#cargarMensajeTemporal").html("<div id='cargando' style='position :fixed'>Cargando...</div>").fadeIn();

			setTimeout(function(){ 
				var respuesta = JSON.parse(ejecutarJson($("#formulario")).responseText);			
		       	if (respuesta.estado == 'exito'){
		       		//fn_filtrar();
		       		$("#id").val(respuesta.contenido);
		       		$("#formulario").attr('data-opcion', 'movilizacion/mostrarReporte');
					abrir($("#formulario"),event,false);
		        }

			}, 1000);
			
			
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	//---------------FUNCIONES-----------------------
	//Lista de cantones por provincia
    function fn_cargarCantones() {
        var idProvincia = $("#id_provincia_emision option:selected").val();
        
        if (idProvincia !== "") {
        	$.post("<?php echo URL ?>MovilizacionVegetal/Movilizacion/comboCantones/" + idProvincia, function (data) {
                $("#id_canton_emision").removeAttr("disabled");
                $("#id_canton_emision").html(data);               
            });
        }
    }

    //Lista de oficinas por cantón
	function fn_cargarOficinas() {
        var idCanton = $("#id_canton_emision option:selected").val();
        
        if (idCanton !== "") {
        	$.post("<?php echo URL ?>MovilizacionVegetal/Movilizacion/comboOficinas/" + idCanton, function (data) {
                $("#id_oficina_emision").removeAttr("disabled");
                $("#id_oficina_emision").html(data);               
            });
        }
    }
    
    //Función para buscar información del sitio del operador de origen
    function buscarOperadorSitioOrigen() {
    	fn_limpiar();
    	$("#id_sitio_origen").html("");
    	
        var provincia_origen = $("#ide_provincia_origen option:selected").val();
        var identificador_operador_origen = $("#id_operador_origen").val();
        var nombre_operador_origen = $("#nom_operador_origen").val();

        if ((provincia_origen !== "") && ((identificador_operador_origen !== "")||(nombre_operador_origen !== ""))) {
        	$.post("<?php echo URL ?>MovilizacionVegetal/Movilizacion/buscarOperadoresOrigen", 
    			{
            		provincia : $("#ide_provincia_origen option:selected").text(),
                    identificador_operador : $("#id_operador_origen").val(),
                    nombre_operador : $("#nom_operador_origen").val()
    			},
                function (data) {
                    $("#id_sitio_origen").html(data);
                });
        }else{
            $("#id_sitio_origen").html("");
        	
        	if(!$.trim($("#ide_provincia_origen").val())){
    			$("#ide_provincia_origen").addClass("alertaCombo");
    		}
    		
        	if(!$.trim($("#id_operador_origen").val())){
    			if (!$.trim($("#nom_operador_origen").val())) {
    				$("#id_operador_origen").addClass("alertaCombo");
        			$("#nom_operador_origen").addClass("alertaCombo");
                }
    		}
    		
            if (!$.trim($("#nom_operador_origen").val())) {
            	if(!$.trim($("#id_operador_origen").val())){
            		$("#id_operador_origen").addClass("alertaCombo");
    				$("#nom_operador_origen").addClass("alertaCombo");
            	}
            }

            $("#estado").html("Por favor ingrese la información requerida para continuar").addClass('alerta');
        }
    }

    //Buscar las áreas del sitio de origen
    function buscarOperadorAreaOrigen(){
    	fn_limpiar();
    	$("#id_area_origen").html("");
    	
        var provincia_origen = $("#id_provincia_origen option:selected").val();
        var identificador_operador_origen = $("#identificador_operador_origen").val();
        var nombre_operador_origen = $("#nombre_operador_origen").val();
        var id_sitio_origen = $("#id_sitio_origen option:selected").val();

        if ((provincia_origen !== "") && ((identificador_operador_origen !== "") && (nombre_operador_origen !== "")) && (id_sitio_origen !== "")) {
        	$.post("<?php echo URL ?>MovilizacionVegetal/Movilizacion/buscarAreasOperadoresOrigen", 
    			{
					id_sitio_origen : $("#id_sitio_origen").val(),
					identificador_operador : $("#id_operador_origen").val(),
    			},
                function (data) {
                    $("#id_area_origen").html(data);
                });
        }else{
            $("#id_area_origen").html("");

            if(!$.trim($("#id_provincia_origen").val())){
    			$("#id_provincia_origen").addClass("alertaCombo");
    		}
    		
            if(!$.trim($("#identificador_operador_origen").val())){
            	$("#identificador_operador_origen").addClass("alertaCombo");
    		}
    		
            if (!$.trim($("#nombre_operador_origen").val())) {
            	$("#nombre_operador_origen").addClass("alertaCombo");
            }

            if(!$.trim($("#id_sitio_origen").val())){
    			$("#id_sitio_origen").addClass("alertaCombo");
    		}

            $("#estado").html("Por favor ingrese la información requerida para continuar").addClass('alerta');
        }
    }

    //Función para buscar información del sitio del operador de destino
    function buscarOperadorSitioDestino() {
    	fn_limpiar();
    	$("#id_sitio_destino").html(combo);
    	$("#id_area_destino").html(combo);
    	
        var provincia_destino = $("#ide_provincia_destino option:selected").val();
        var identificador_operador_destino = $("#id_operador_destino").val();
        var nombre_operador_destino = $("#nom_operador_destino").val();
        var id_sitio_origen = $("#id_sitio_origen option:selected").val();

        if ((provincia_destino !== "") && ((identificador_operador_destino !== "")||(nombre_operador_destino !== "")) && ((id_sitio_origen !== "") && (id_sitio_origen !== "Seleccione...."))) {
        	$.post("<?php echo URL ?>MovilizacionVegetal/Movilizacion/buscarOperadoresDestino", 
    			{
            		provincia : $("#ide_provincia_destino option:selected").text(),
                    identificador_operador : $("#id_operador_destino").val(),
                    nombre_operador : $("#nom_operador_destino").val(),
                    id_sitio_origen : $("#id_sitio_origen option:selected").val()
    			},
                function (data) {
                    $("#id_sitio_destino").html(data);
                });
        }else{
            $("#id_sitio_destino").html("");

            if(!$.trim($("#id_sitio_origen").val())){
    			$("#id_sitio_origen").addClass("alertaCombo");
    		}

            if(!$.trim($("#ide_provincia_destino").val())){
    			$("#ide_provincia_destino").addClass("alertaCombo");
    		}
    		
        	if(!$.trim($("#id_operador_destino").val())){
    			if (!$.trim($("#nom_operador_destino").val())) {
    				$("#id_operador_destino").addClass("alertaCombo");
        			$("#nom_operador_destino").addClass("alertaCombo");
                }
    		}
    		
            if (!$.trim($("#nom_operador_destino").val())) {
            	if(!$.trim($("#id_operador_destino").val())){
            		$("#id_operador_destino").addClass("alertaCombo");
    				$("#nom_operador_destino").addClass("alertaCombo");
            	}
            }

            $("#estado").html("Por favor ingrese la información requerida para continuar").addClass('alerta');
        }
    }

    //Función para buscar información de las áreas del operador de destino
    function buscarOperadorAreaDestino() {		
    	fn_limpiar();
    	$("#id_area_destino").html("");
    	
        var provincia_destino = $("#ide_provincia_destino option:selected").val();
        var identificador_operador_destino = $("#identificador_operador_destino").val();
        var nombre_operador_destino = $("#nombre_operador_destino").val();
        var id_sitio_destino = $("#id_sitio_destino option:selected").val();
        var id_area_origen = $("#id_area_origen option:selected").val();

        if ((provincia_destino !== "") && ((identificador_operador_destino !== "") && (nombre_operador_destino !== "")) && ((id_sitio_destino !== "") && (id_sitio_destino !== "Seleccione...."))) {
        	$.post("<?php echo URL ?>MovilizacionVegetal/Movilizacion/buscarAreasOperadoresDestino", 
    			{
            		id_sitio_destino : $("#id_sitio_destino").val(),
					id_area_origen : $("#id_area_origen option:selected").val(),
					identificador_operador : $("#id_operador_destino").val(),
					codigo_sitio : $("#id_sitio_destino option:selected").attr('data-codigo_sitio')
    			},
                function (data) {
                    $("#id_area_destino").html(data);
                });
        }else{
            $("#id_area_destino").html("");

            if(!$.trim($("#id_provincia_destino").val())){
    			$("#id_provincia_destino").addClass("alertaCombo");
    		}
    		
            if(!$.trim($("#identificador_operador_destino").val())){
            	$("#identificador_operador_destino").addClass("alertaCombo");
    		}
    		
            if (!$.trim($("#nombre_operador_destino").val())) {
            	$("#nombre_operador_destino").addClass("alertaCombo");
            }

            if(!$.trim($("#id_sitio_destino").val())){
    			$("#id_sitio_destino").addClass("alertaCombo");
    		}

            if(!$.trim($("#id_area_origen").val())){
    			$("#id_area_origen").addClass("alertaCombo");
    		}

            $("#estado").html("Por favor ingrese la información requerida para continuar").addClass('alerta');
        }
    }

    //Función para buscar el subtipo de producto registrado en el área de origen seleccionada
    function buscarSubtipoProducto(){
    	fn_limpiar();
    	$("#id_producto").html(combo);
    	$("#producto").val("");
    	
        var id_sitio_origen = $("#id_sitio_origen option:selected").val();
        var id_area_origen = $("#id_area_origen option:selected").val();

        if ((id_sitio_origen !== "") && (id_area_origen !== "")) {
        	$.post("<?php echo URL ?>MovilizacionVegetal/Movilizacion/buscarSubtipoProductoAreasOperadoresOrigen", 
    			{
            		id_area_origen : $("#id_area_origen option:selected").val()
    			},
                function (data) {
                    $("#id_subtipo_producto").html(data);
                });
        }else{
            $("#id_subtipo_producto").html("");

            if(!$.trim($("#id_sitio_origen").val())){
    			$("#id_sitio_origen").addClass("alertaCombo");
    		}

            if(!$.trim($("#id_area_origen").val())){
    			$("#id_area_origen").addClass("alertaCombo");
    		}

            $("#estado").html("Por favor ingrese la información requerida para continuar").addClass('alerta');
        }
    }

    //Función para buscar el producto registrado en el área de origen seleccionada
    function buscarProducto(){
    	fn_limpiar();
    	$("#id_producto").html(combo);
    	$("#producto").val("");
    	
        var id_subtipo_producto = $("#id_subtipo_producto option:selected").val();
        var id_area_origen = $("#id_area_origen option:selected").val();

        if ((id_subtipo_producto !== "") && (id_area_origen !== "")) {
        	$.post("<?php echo URL ?>MovilizacionVegetal/Movilizacion/buscarProductoAreasOperadoresOrigen", 
    			{
            		id_area_origen : $("#id_area_origen option:selected").val(),
            		id_subtipo_producto : $("#id_subtipo_producto option:selected").val()
    			},
                function (data) {
                    $("#id_producto").html(data);
                });
        }else{
            $("#id_subtipo_producto").html("");

            if(!$.trim($("#id_sitio_origen").val())){
    			$("#id_sitio_origen").addClass("alertaCombo");
    		}

            if(!$.trim($("#id_area_origen").val())){
    			$("#id_area_origen").addClass("alertaCombo");
    		}

            $("#estado").html("Por favor ingrese la información requerida para continuar").addClass('alerta');
        }
    }

  	//Función para buscar requisitos fitosanitarios de movilización de un producto
	function buscarRequisitosProducto() {
        var idProducto = $("#id_producto option:selected").val();
        
        if (idProducto !== "") {
        	$.post("<?php echo URL ?>MovilizacionVegetal/Movilizacion/buscarRequisitoMovilizacion/",
            {
        		id_producto : $("#id_producto option:selected").val()
			},
			function (data) {
                $("#requisitos").html(data);               
            });
        }else{
        	$("#requisitos").val("");
        }
    }

    //Función para agregar elementos
    $('#btnAgregarProductos').click(function(){
    	$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#id_area_origen").val())){
			error = true;
			$("#id_area_origen").addClass("alertaCombo");
		}
		
		if(!$.trim($("#id_area_destino").val())){
			error = true;
			$("#id_area_destino").addClass("alertaCombo");
		}

		if(!$.trim($("#id_subtipo_producto").val())){
			error = true;
			$("#id_subtipo_producto").addClass("alertaCombo");
		}
		
		if(!$.trim($("#id_producto").val())){
			error = true;
			$("#id_producto").addClass("alertaCombo");
		}

		if(!$.trim($("#unidad").val())){
			error = true;
			$("#unidad").addClass("alertaCombo");
		}

		if(!$.trim($("#cantidad").val()) || ($("#cantidad").val() <= 0) || !esCampoValido("#cantidad")){
			error = true;
			$("#cantidad").addClass("alertaCombo");
		}

		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			$("#estado").html("").removeClass('alerta');

			if($("#id_area_origen").val()!="" && $("#id_area_destino").val()!="" && $("#id_subtipo_producto").val()!="" && $("#id_producto").val()!="" && $("#unidad").val()!="" && $("#cantidad").val()!=""){
				var codigo = $("#id_area_origen").val() + $("#id_area_destino").val() + $("#id_producto").val();	
				var cadena = '';

				verificarRegistro($(this).val());

				//revisar datos enviados y que se agregue al grid
				if($("#tbItems tbody #"+codigo.replace(/ /g,'')).length==0){
					cadena = "<tr id='"+codigo.replace(/ /g,'')+"'>"+
								"<td>"+
								"</td>"+
								"<td>"+$("#id_area_origen option:selected").text()+
								"	<input id='iAreaOrigen' name='iAreaOrigen[]' value='"+$("#id_area_origen option:selected").val()+"' type='hidden'>"+
								"	<input id='nAreaOrigen' name='nAreaOrigen[]' value='"+$("#id_area_origen option:selected").text()+"' type='hidden'>"+
								"</td>"+
								"<td>"+$("#id_area_destino option:selected").text()+
								"	<input id='iAreaDestino' name='iAreaDestino[]' value='"+$("#id_area_destino option:selected").val()+"' type='hidden'>"+
								"	<input id='nAreaDestino' name='nAreaDestino[]' value='"+$("#id_area_destino option:selected").text()+"' type='hidden'>"+
								"</td>"+
								"<td>"+$("#id_subtipo_producto option:selected").text()+
								"	<input id='iSubtipoProducto' name='iSubtipoProducto[]' value='"+$("#id_subtipo_producto option:selected").val()+"' type='hidden'>"+
								"	<input id='nSubtipoProducto' name='nSubtipoProducto[]' value='"+$("#id_subtipo_producto option:selected").text()+"' type='hidden'>"+
								"</td>"+
								"<td>"+$("#id_producto option:selected").text()+
								"	<input id='iProducto' name='iProducto[]' value='"+$("#id_producto option:selected").val()+"' type='hidden'>"+
								"	<input id='nProducto' name='nProducto[]' value='"+$("#id_producto option:selected").text()+"' type='hidden'>"+
								"</td>"+
								"<td>"+$("#unidad option:selected").text()+
								"	<input id='iUnidad' name='iUnidad[]' value='"+$("#unidad option:selected").val()+"' type='hidden'>"+
								"</td>"+
								"<td>"+$("#cantidad").val()+
								"	<input id='iCantidad' name='iCantidad[]' value='"+$("#cantidad").val()+"' type='hidden'>"+
								"</td>"+
								"<td>"+
								"	<button type='button' onclick='quitarProductos("+codigo.replace(/ /g,'')+")' class='menos'>Quitar</button>"+
								"</td>"+
							"</tr>"

					$("#tbItems tbody").append(cadena);
					enumerar();
					//limpiarDetalle();
				}else{
					$("#estado").html("No puede ingresar dos registros iguales.").addClass('alerta');
				}
			}
		}
    });

    function quitarProductos(fila){
		$("#tbItems tbody tr").eq($(fila).index()).remove();	  
		enumerar();
	}

	function verificarRegistro(produ){
		$('#tbItems tbody tr').each(function (rows) {		
			var rd= $(this).find('td').eq(1).find('input[id="idOperacion"]').val();
			filas=$('#tbItems tbody tr').length;
			if (filas>0){
				if(rd == produ){
					rDuplicado=true;
			    	return false;
			    } else{
			    	rDuplicado=false;		    			    		
			    }			        
			}	    
		});
	}

	function enumerar(){			    	    
	    var tabla = document.getElementById('tbItems');
	    con=0;   
	    $("#tbItems tbody tr").each(function(row){        
	    	con+=1;    	
	    	$(this).find('td').eq(0).html(con);    	  	
	    });
	}	

	function limpiarDetalle(){
		//$("#id_area_origen").val("");
    	//$("#area_origen").val("");
    	//$("#id_area_destino").html(combo);
    	$("#area_destino").val("");
    	$("#id_subtipo_producto").html(combo);
    	$("#subtipo_producto").val("");
    	$("#id_producto").html(combo);
    	$("#producto").val("");
    	$("#unidad").val("");
    	$("#cantidad").val("");
    	$("#requisitos").html("");
	}

	function fn_limpiar() {
		$(".alertaCombo").removeClass("alertaCombo");
		$('#estado').html('');
	}

	//Para cargar el detalle de movilizaciones registradas
    function fn_mostrarDetalleMovilizacion() {
        var idMovilizacion = $("#id_movilizacion").val();
        
    	$.post("<?php echo URL ?>MovilizacionVegetal/DetalleMovilizacion/construirDetalleMovilizacion/" + idMovilizacion, function (data) {
            $("#tablaMovilizaciones").html(data);
        });
    } 

    function esCampoValido(elemento){
    	var patron = new RegExp($(elemento).attr("data-er"),"g");
    	return patron.test($(elemento).val());
    }
</script>