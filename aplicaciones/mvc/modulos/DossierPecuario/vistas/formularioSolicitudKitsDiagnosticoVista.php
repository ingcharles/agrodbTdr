<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<div class="pestania">

	<fieldset class="aprobado">
		<legend>Certificados Emitidos</legend>				

		<div data-linea="1">
			<label for="ruta_expediente">Certificado de Registro de Producto: </label>
			<?php echo 
			($this->modeloSolicitud->getRutaCertificado() != '' ? '<a href="'.URL_GUIA_PROYECTO . '/' .$this->modeloSolicitud->getRutaCertificado().'" target="_blank" class="archivo_cargado" id="archivo_cargado">Click para descargar documento</a>' : 'No hay un archivo adjunto'); ?>
		</div>	
		
		<div data-linea="2">
			<label for="ruta_puntos_minimos">Certificado de Puntos Mínimos: </label>
			<?php echo 
			($this->modeloSolicitud->getRutaPuntosMinimos() != '' ? '<a href="'.URL_GUIA_PROYECTO . '/' .$this->modeloSolicitud->getRutaPuntosMinimos().'" target="_blank" class="archivo_cargado" id="archivo_cargado">Click para descargar documento</a>' : 'No hay un archivo adjunto'); ?>
		</div>
		
		<div data-linea="3">
			<label for="ruta_expediente">Expediente de registro de producto: </label>
			<?php echo 
			($this->modeloSolicitud->getRutaExpediente() != '' ? '<a href="'.URL_GUIA_PROYECTO . '/' .$this->modeloSolicitud->getRutaExpediente().'" target="_blank" class="archivo_cargado" id="archivo_cargado">Click para descargar documento</a>' : 'No hay un archivo adjunto'); ?>
		</div>		
		
		<div data-linea="4">
			<label>Nº de registro: </label>
			<?php echo $this->modeloSolicitud->getCodigoProductoFinal(); ?>
		</div>
    		
    </fieldset>
	
	<fieldset class="tecnico">
		<legend>Información de la Solicitud del Usuario Nº <?php echo $this->modeloSolicitud->getidExpediente();?></legend>				

		<div data-linea="1">
			<label for="ruta_expediente">Expediente de registro de producto: </label>
			<?php echo 
			($this->modeloSolicitud->getRutaExpediente() != '' ? '<a href="'.URL_GUIA_PROYECTO . '/' .$this->modeloSolicitud->getRutaExpediente().'" target="_blank" class="archivo_cargado" id="archivo_cargado">Click para descargar documento</a>' : 'No hay un archivo adjunto'); ?>
		</div>	
		
		<div data-linea="2">
			<label for="ruta_cambios_usuario">Informe de cambios realizados por el usuario: </label>
			<?php echo 
			($this->modeloSolicitud->getRutaCambiosUsuario() != '' ? '<a href="'.URL_GUIA_PROYECTO . '/' .$this->modeloSolicitud->getRutaCambiosUsuario().'" target="_blank" class="archivo_cargado" id="archivo_cargado">Click para descargar documento</a>' : 'No hay un archivo adjunto'); ?>
		</div>
		
		<div data-linea="3">
			<label for="observaciones_cambios">Resumen de cambios realizados por el usuario: </label>
			<?php echo ($this->modeloSolicitud->getObservacionesCambios() !='' ? $this->modeloSolicitud->getObservacionesCambios() : 'No existe información'); ?>
		</div>			
    		
    	<div data-linea="4" class="tiempo">
			<label for="tiempo_subsanacion">Tiempo del usuario para remitir subsanación: </label>
			<?php echo $this->modeloSolicitud->getTiempoSubsanacion() .' días hábiles.'; ?>
		</div>
    </fieldset>
    
    <fieldset class="resultadoRevision">
		<legend>Resultado de la Revisión del Técnico</legend>	
		
		<div data-linea="1">
			<label for="estado_solicitud">Resultado: </label>
			<?php echo ($this->modeloSolicitud->getEstadoSolicitud() !='' ? $this->modeloSolicitud->getEstadoSolicitud() : 'No existe información'); ?>
		</div>			

		<div data-linea="2">
			<label for="ruta_documento_subsanacion">Informe de revisión para subsanación:</label>
			<?php echo 
			($this->modeloSolicitud->getRutaDocumentoSubsanacion() != '' ? '<a href="'.URL_GUIA_PROYECTO . '/' .$this->modeloSolicitud->getRutaDocumentoSubsanacion().'" target="_blank" class="archivo_cargado" id="archivo_cargado">Click para descargar documento</a>' : 'No hay un archivo adjunto'); ?>
		</div>	
		
		<div data-linea="3">
			<label for="observacion_revision">Observaciones: </label>
			<?php echo ($this->modeloSolicitud->getObservacionRevision() !='' ? $this->modeloSolicitud->getObservacionRevision() : 'No existe información'); ?>
		</div>			
		
		<hr>
		
		<div data-linea="4">
			<label for="nombre_tenico">Técnico: </label>
			<?php echo $this->modeloFichaEmpleado->getNombre() .' ' . $this->modeloFichaEmpleado->getApellido() ?>
		</div>
		
		<div data-linea="5">
			<label for="provincia_tecnico">Provincia: </label>
			<?php echo $this->modeloLocalizacion->getNombre(); ?>
		</div>
		
		<div data-linea="6">
			<label for="correo_tecnico">Correo electrónico: </label>
			<?php echo $this->modeloFichaEmpleado->getMailInstitucional(); ?>
		</div>
		
		<hr class="tiempo">
		
		<div data-linea="7" class="tiempo">
			<label for="tiempo_subsanacion">Tiempo del usuario para remitir subsanación: </label>
			<?php echo $this->modeloSolicitud->getTiempoSubsanacion() .' días hábiles.'; ?>
		</div>
    		
    </fieldset>
    
    <fieldset class="resultadoRevisionFinal">
		<legend>Resultado de la Revisión del Técnico</legend>	
		
		<div data-linea="1">
			<label for="estado_solicitud">Resultado: </label>
			<?php echo ($this->modeloSolicitud->getEstadoSolicitud() !='' ? $this->modeloSolicitud->getEstadoSolicitud() : 'No existe información'); ?>
		</div>			

		<div data-linea="2">
			<label for="ruta_documento_subsanacion">Informe de revisión para subsanación:</label>
			<?php echo 
			($this->modeloSolicitud->getRutaDocumentoSubsanacion() != '' ? '<a href="'.URL_GUIA_PROYECTO . '/' .$this->modeloSolicitud->getRutaDocumentoSubsanacion().'" target="_blank" class="archivo_cargado" id="archivo_cargado">Click para descargar documento</a>' : 'No hay un archivo adjunto'); ?>
		</div>	
		
		<div data-linea="3">
			<label for="observacion_revision">Observaciones: </label>
			<?php echo ($this->modeloSolicitud->getObservacionRevision() !='' ? $this->modeloSolicitud->getObservacionRevision() : 'No existe información'); ?>
		</div>			
		
		<hr>
		
		<div data-linea="4">
			<label for="nombre_tenico">Técnico: </label>
			<?php echo $this->modeloFichaEmpleado->getNombre() .' ' . $this->modeloFichaEmpleado->getApellido() ?>
		</div>
		
		<div data-linea="5">
			<label for="provincia_tecnico">Provincia: </label>
			<?php echo $this->modeloLocalizacion->getNombre(); ?>
		</div>
		
		<div data-linea="6">
			<label for="correo_tecnico">Correo electrónico: </label>
			<?php echo $this->modeloFichaEmpleado->getMailInstitucional(); ?>
		</div>
    		
    </fieldset>
    
    <fieldset class="tecnicoAsignado">
		<legend>Técnico asignado</legend>	
		
		<div data-linea="1">
			<label for="nombre_tenico">Técnico: </label>
			<?php echo $this->modeloFichaEmpleado->getNombre() .' ' . $this->modeloFichaEmpleado->getApellido() ?>
		</div>
		
		<div data-linea="2">
			<label for="provincia_tecnico">Provincia: </label>
			<?php echo $this->modeloLocalizacion->getNombre(); ?>
		</div>
		
		<div data-linea="3">
			<label for="correo_tecnico">Correo electrónico: </label>
			<?php echo $this->modeloFichaEmpleado->getMailInstitucional(); ?>
		</div>
    </fieldset>
    
    <fieldset class="pago">
		<legend>Información de pago</legend>				

		<div data-linea="1">
			<label>Monto a pagar: </label>
			<?php echo ($this->montoPago != '' ? '$'.$this->montoPago : 'No hay un monto asignado'); ?>
		</div>	
		
		<div data-linea="2">
			<label>Orden pago: </label>
			<?php echo 
			($this->rutaPago != '' ? '<a href="'.URL_GUIA_PROYECTO . '/' .$this->rutaPago.'" target="_blank" class="archivo_cargado" id="archivo_cargado">Click para descargar documento</a>' : 'No hay un archivo adjunto'); ?>
		</div>		
    		
    </fieldset>

    <form id='formularioOperadorProducto' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>DossierPecuario' data-opcion='solicitud/guardar' data-destino="detalleItem" method="post" class="">
    	<input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $this->modeloSolicitud->getIdSolicitud(); ?>" />
    	
    	<fieldset class="Modificacion">
    		<legend>Tipo de Solicitud</legend>
    	
    		<div data-linea="0">
    			<label for="tipo_solicitud">Tipo de Solicitud: </label>
    			<select id="tipo_solicitud" name="tipo_solicitud" disabled class="formulario">
                    <option value="">Seleccionar....</option>
                    <?php
                        echo $this->comboTipoSolicitud($this->modeloSolicitud->getTipoSolicitud());
                    ?>
                </select>
    		</div>
    	</fieldset>
    	
    	<fieldset>
    		<legend>Información del Operador</legend>	
    		
    		<div data-linea="100">
    			<label for="identificador">Solicitante: </label>
    			<?php echo $this->solicitante;?>
    		</div>
    		
    		<hr />
    		
    		<div data-linea="0" class="Modificacion">
    			<label for="cambio_titular">¿Realizará un cambio de titular? </label>
    			<select id="cambio_titular" name="cambio_titular" disabled class="formulario">
                    <option value="">Seleccionar....</option>
                    <?php
                        echo $this->comboSiNo($this->modeloSolicitud->getCambioTitular());
                    ?>
                </select>
    		</div>	
    
    		<div data-linea="1">
    			<label for="identificador">RUC / RISE: </label>
    			<input type="text" id="identificador_titular" name="identificador_titular" value="<?php echo $this->modeloSolicitud->getIdentificadorTitular();?>" required="required" readonly="readonly" class="formulario" data-er="^[0-9]+$"/>
    		</div>
    		
    		<div data-linea="2">
    			<label for="razon_social">Razón Social: </label>
    			<input type="text" id="razon_social" readonly="readonly" class="formulario"/>
    		</div>				
    
    		<div data-linea="3">
    			<label for="direccion">Dirección: </label>
    			<input type="text" id="direccion" readonly="readonly" class="formulario" />
    		</div>
    		
    		<div data-linea="4">
    			<label for="provincia">Provincia: </label>
    			<input type="text" id="provincia" readonly="readonly" class="formulario" />
    		</div>
    		
    		<div data-linea="5">
    			<label for="canton">Cantón: </label>
    			<input type="text" id="canton" readonly="readonly" class="formulario" />
    		</div>
    		
    		<div data-linea="5">
    			<label for="parroquia">Parroquia: </label>
    			<input type="text" id="parroquia" readonly="readonly" class="formulario" />
    		</div>
    		
    		<div data-linea="6">
    			<label for="telefono">Teléfono: </label>
    			<input type="text" id="telefono" readonly="readonly" class="formulario" />
    		</div>
    		
    		<div data-linea="6">
    			<label for="celular">Celular: </label>
    			<input type="text" id="celular" readonly="readonly" class="formulario" />
    		</div>
    		
    		<div data-linea="7">
    			<label for="correo">Correo electrónico: </label>
    			<input type="text" id="correo" readonly="readonly" class="formulario" />
    		</div>
    		
    		<div data-linea="8">
    			<label for="representante_legal">Representante legal: </label>
    			<input type="text" id="representante_legal" readonly="readonly" class="formulario" />
    		</div>
    		
    	</fieldset >
    	
    	<fieldset>
    		<legend>Información del Producto</legend>				
    
    		<div data-linea="0">
    			<label for="id_grupo_producto">Grupo de Producto: </label>
    			<select id="id_grupo_producto" name="id_grupo_producto" disabled >
                    <option value="">Seleccionar....</option>
                    <?php
                        echo $this->comboGrupoProducto($this->modeloSolicitud->getIdGrupoProducto());
                    ?>
                </select>
    		</div>		
    		
    		<div data-linea="1" class="Modificacion">
    			<label for="cambio_subtipo">¿Realizará un cambio de tipo de producto? </label>
    			<select id="cambio_subtipo" name="cambio_subtipo" required class="formulario">
                    <option value="">Seleccionar....</option>
                    <?php
                        echo $this->comboSiNo($this->modeloSolicitud->getCambioTitular());
                    ?>
                </select>
    		</div>		
    
    		<div data-linea="2">
    			<label for="id_subtipo_producto">Tipo de Producto: </label>
    			<select id="id_subtipo_producto" name="id_subtipo_producto" disabled class="formulario" >
                    <option value="">Seleccionar....</option>
                </select>
                
                <input type="hidden" id="codificacion_subtipo_producto" name="codificacion_subtipo_producto" value="<?php echo $this->modeloSolicitud->getCodificacionSubtipoProducto(); ?>" required maxlength="32" />
    		</div>		
    		
    		<div data-linea="3">
    			<label for="nombre_producto">Nombre: </label>
    			<input type="text" id="nombre_producto" name="nombre_producto" value="<?php echo $this->modeloSolicitud->getNombreProducto(); ?>" required maxlength="512" class="formulario"/>
    		</div>						
    
    	</fieldset >
    	
        <div data-linea="4" class="editable">
    		<button type="submit" class="guardar">Actualizar</button>
    	</div>
    </form >
</div>

<div class="pestania">
	<form id='formularioOrigen' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>DossierPecuario' data-opcion='OrigenProducto/guardar' data-destino="detalleItem" method="post" class="editable">
    	<input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $this->modeloSolicitud->getIdSolicitud(); ?>" />
    
	<fieldset>
		<legend>Origen del Producto</legend>	
		
		<div data-linea="1">
			<label for="origen_fabricacion">Origen de fabricación: </label>
			<select id="origen_fabricacion" name="origen_fabricacion" >
                <option value="">Seleccionar....</option>
                <?php
                    echo $this->comboOrigenProductoCompleto();
                ?>
            </select>
		</div>			

		<div data-linea="2" class="TitularNacional">
			<label for="identificador_fabricante">RUC/RISE: </label>
			<input type="text" id="identificador_fabricante" name="identificador_fabricante" readonly="readonly" required maxlength="13" />
		</div>				

		<div data-linea="3" class="TitularNacionalExtranjero">
			<label id="lNombreFabricante" for="nombre_fabricante">Razón social: </label>
			<input type="text" id="nombre_fabricante" name="nombre_fabricante" readonly="readonly" required maxlength="512" />
		</div>				

		<div data-linea="4" class="TitularNacionalExtranjero">
			<label for="direccion_fabricante">Dirección: </label>
			<input type="text" id="direccion_fabricante" name="direccion_fabricante" readonly="readonly" required maxlength="1024" />
		</div>				

		<div data-linea="5" class="TitularNacional">
			<label for="id_provincia_fabricante">Provincia: </label>
			<input type="hidden" id="id_provincia_fabricante" name="id_provincia_fabricante"  maxlength="8" />
			<input type="text" id="provincia_fabricante" name="provincia_fabricante" readonly="readonly" maxlength="64" />
		</div>							

		<div data-linea="7" class="Extranjero">
			<label for="id_pais">País: </label>
			<input type="hidden" id="id_fabricante_extranjero" name="id_fabricante_extranjero" readonly="readonly" maxlength="8" />
			<input type="hidden" id="id_pais" name="id_pais"  maxlength="8" />
			<input type="text" id="pais" name="pais" readonly="readonly" maxlength="64" />
		</div>				

		<div data-linea="8" class="Extranjero">
			<label for="tipo_producto_fabricante">Tipos de productos: </label>
			<input type="text" id="tipo_producto_fabricante" name="tipo_producto_fabricante" readonly="readonly" maxlength="2048" />
		</div>		

		<div data-linea="9">
    		<button type="submit" class="guardar">Agregar</button>
    	</div>
    	
    	</fieldset>
    </form >
    
    <div id="tablaOrigen">
        <fieldset>
        	<legend>Origen del Producto</legend>
            	<div data-linea="10">
        			<table id="tbItemsOrigen" style="width:100%">
        				<thead>
        					<tr>
        						<th style="width: 5%;">#</th>
        						<th style="width: 15%;">Fabricado por</th>
        						<th style="width: 20%;">Nombre</th>
                                <th style="width: 15%;">País</th>
                                <th style="width: 20%;">Dirección</th>
                                <th style="width: 20%;">Tipo de Productos</th>
                                <th style="width: 5%;"></th>
        					</tr>
        				</thead>
        				<tbody>
        				</tbody>
        			</table>
        		</div>		
    	</fieldset>
    </div>
    
</div>

<div class="pestania">
	<form id='formularioClasificacion' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>DossierPecuario' data-opcion='solicitud/guardar' data-destino="detalleItem" method="post">
    	<input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $this->modeloSolicitud->getIdSolicitud(); ?>" />
    
    	<fieldset>
    		<legend>Clasificación</legend>
    		
    		<div data-linea="1">
    			<label for="id_clasificacion">Clasificación: </label>
    			<select id="id_clasificacion" name="id_clasificacion" class="formulario">
                    <option value="">Seleccionar....</option>
                    <?php
                        echo $this->comboClasificacion($this->modeloSolicitud->getIdClasificacion());
                    ?>
                </select>
    		</div>	
    		
    		<div data-linea="2" class="editable">
        		<button type="submit" class="guardar">Guardar</button>
        	</div>
        	
		</fieldset>		
	</form>			
	
	<form id='formularioPartidaArancelaria' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>DossierPecuario' data-opcion='PartidaCodigos/guardar' data-destino="detalleItem" method="post" class="editable">
    	<input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $this->modeloSolicitud->getIdSolicitud(); ?>" />
    
    	<fieldset>
    		<legend>Partida arancelaria y Códigos complementario / suplementario</legend>
    					
    		<div data-linea="1">
    			<label for="partida_arancelaria">Partida arancelaria: </label>
    			<input type="text" id="partida_arancelaria" name="partida_arancelaria" maxlength="10" data-er="^[0-9]+$"/>
    		</div>				
    
    		<div data-linea="2">
    			<label for="id_codigo_complementario">Código complementario: </label>
    			<select id="id_codigo_complementario" name="id_codigo_complementario" >
                    <option value="">Seleccionar....</option>
                    <?php
                            echo $this->comboCodigoComplementario();
                    ?>
                </select>
    			
    			<input type="hidden" id="codigo_complementario" name="codigo_complementario" maxlength="16" />
    		</div>				
    
    		<div data-linea="3">
    			<label for="id_codigo_suplementario">Código suplementario: </label>
    			<select id="id_codigo_suplementario" name="id_codigo_suplementario" >
                    <option value="">Seleccionar....</option>
                    <?php
                        echo $this->comboCodigoSuplementario();
                    ?>
                </select>
    			
    			<input type="hidden" id="codigo_suplementario" name="codigo_suplementario" maxlength="16" />
    		</div>				
    
    		<div data-linea="4">
    			<button type="submit" class="guardar">Agregar</button>
    		</div>
	</fieldset >
    </form >
    
    <div id="tablaPartidaCodigos">
        <fieldset>
        	<legend>Partida arancelaria y códigos complementario/suplementario</legend>
            	<div data-linea="5">
        			<table id="tbItemsPartidaCodigos" style="width:100%">
        				<thead>
        					<tr>
        						<th style="width: 5%;">#</th>
        						<th style="width: 35%;">Partida arancelaria</th>
        						<th style="width: 20%;">Código complementario</th>
                                <th style="width: 20%;">Código suplementario</th>
                                <th style="width: 10%;"></th>
        					</tr>
        				</thead>
        				<tbody>
        				</tbody>
        			</table>
        		</div>		
    	</fieldset>
    </div>
    
    <form id='formularioPresentacionComercial' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>DossierPecuario' data-opcion='PresentacionComercial/guardar' data-destino="detalleItem" method="post" class="editable">
    	<input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $this->modeloSolicitud->getIdSolicitud(); ?>" />
    
    	<fieldset>
    		<legend>Presentaciones Comerciales</legend>				
    
    		<div data-linea="1">
    			<label for="presentacion">Presentación y Descripción del envase: </label>
    			<input type="text" id="presentacion" name="presentacion" maxlength="512" />
    		</div>				
    
    		<div data-linea="2">
    			<label for="cantidad">Cantidad: </label>
    			<input type="number" id="cantidad" name="cantidad" min="0" step="0.01" />
    		</div>				
    
    		<div data-linea="3">
    			<label for="id_unidad_presentacion_comercial">Unidad: </label>
    			<select id="id_unidad_presentacion_comercial" name="id_unidad" >
                    <option value="">Seleccionar....</option>
                    <?php
                        echo $this->comboUnidadMedidaXId();
                    ?>
                    <option value="0">Otro</option>
                </select>
    		</div>				
    
    		<div data-linea="4" class="NombreUnidadPresentacion">
    			<label for="nombre_unidad_presentacion_comercial">Nombre unidad: </label>
    			<input type="text" id="nombre_unidad_presentacion_comercial" name="nombre_unidad" maxlength="128" />
    		</div>				
    
    		<div data-linea="5">
    			<button type="submit" class="guardar">Agregar</button>
    		</div>
    	</fieldset >
    </form >	
    
    <div id="tablaPresentacionComercial">
        <fieldset>
        	<legend>Presentaciones comerciales</legend>
            	<div data-linea="5">
        			<table id="tbItemsPresentacionComercial" style="width:100%">
        				<thead>
        					<tr>
        						<th style="width: 10%;">#</th>
        						<th style="width: 15%;">Subcódigo</th>
        						<th style="width: 35%;">Presentación y descripción del envase</th>
                                <th style="width: 25%;">Cantidad (U)</th>
                                <th style="width: 15%;"></th>
        					</tr>
        				</thead>
        				<tbody>
        				</tbody>
        			</table>
        		</div>		
    	</fieldset>
    </div>
</div>

<div class="pestania">
    <form id='formularioComposicion' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>DossierPecuario' data-opcion='Composicion/guardar' data-destino="detalleItem" method="post"  class="editable">
    	<input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $this->modeloSolicitud->getIdSolicitud(); ?>" />
    
    	<fieldset>
    		<legend>Composición del producto</legend>				
    
    		<div data-linea="1">
    			<label for="cada">Cada: </label>
    			<input type="text" id="cada" name="cada" maxlength="128" />
    		</div>				
    
    		<div data-linea="2">
    			<label for="id_unidad">Unidad de medida: </label>
    			<select id="id_unidad_composicion" name="id_unidad" >
                    <option value="">Seleccionar....</option>
                    <?php
                        echo $this->comboUnidadMedidaXId();
                    ?>
                    <option value="0">Otro</option>
                </select>
    		</div>	
    		
    		<div data-linea="3" class="NombreUnidadComposicion">
    			<label for="nombre_unidad_composicion">Nombre unidad: </label>
    			<input type="text" id="nombre_unidad_composicion" name="nombre_unidad" maxlength="128" />
    		</div>	
    		
    		<div data-linea="4">
    			<label>Contiene: </label>
    		</div>			
    
    		<div data-linea="5">
    			<label for="id_tipo_componente">Tipo de componente: </label>
    			<select id="id_tipo_componente" name="id_tipo_componente" >
                    <option value="">Seleccionar....</option>
                    <?php
                        echo $this->comboTipoComponente('IAV');
                    ?>
                </select>
    		</div>				
    
    		<div data-linea="6">
    			<label for="id_nombre_componente">Nombre de componente: </label>
    			<select id="id_nombre_componente" name="id_nombre_componente" >
                    <option value="">Seleccionar....</option>
                    <?php
                        echo $this->comboIngredienteActivo('IAV');
                    ?>
                </select>
    		</div>				
    
    		<div data-linea="7">
    			<label for="cantidad_composicion">Cantidad: </label>
    			<input type="text" id="cantidad_composicion" name="cantidad" maxlength="128" />
    		</div>		
    		
    		<div data-linea="8">
    			<label for="id_unidad_componente">Unidad de medida: </label>
    			<select id="id_unidad_componente" name="id_unidad_componente" >
                    <option value="">Seleccionar....</option>
                    <?php
                        echo $this->comboUnidadMedidaXId();
                    ?>
                    <option value="0">Otro</option>
                </select>
    		</div>	
    		
    		<div data-linea="9" class="NombreUnidadComponente">
    			<label for="nombre_unidad_componente">Nombre unidad: </label>
    			<input type="text" id="nombre_unidad_componente" name="nombre_unidad_componente" maxlength="128" />
    		</div>		
    
    		<div data-linea="10">
    			<button type="submit" class="guardar">Agregar</button>
    		</div>
    	</fieldset >
    </form >
    
    <div id="tablaComposicion">
        <fieldset>
        	<legend>Composición</legend>
            	<div data-linea="5">
        			<table id="tbItemsComposicion" style="width:100%">
        				<thead>
        					<tr>
        						<th style="width: 10%;">#</th>
        						<th style="width: 80%;">Composición del producto</th>
                                <th style="width: 10%;"></th>
        					</tr>
        				</thead>
        				<tbody>
        				</tbody>
        			</table>
        		</div>		
    	</fieldset>
    </div>
    
    <form id='formularioReactivoMaterial' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>DossierPecuario' data-opcion='ReactivoMaterial/guardar' data-destino="detalleItem" method="post" class="editable">
		<input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $this->modeloSolicitud->getIdSolicitud(); ?>" />
    
    	<fieldset>
    		<legend>Reactivos y Materiales</legend>				
    
    		<div data-linea="1">
    			<label for="reactivo_material">Reactivos y materiales: </label>
    			<input type="text" id="reactivo_material" name="reactivo_material" maxlength="1024" />
    		</div>
    
    		<div data-linea="2">
    			<button type="submit" class="guardar">Agregar</button>
    		</div>
    	</fieldset >
    </form >
    
    <div id="tablaReactivoMaterial">
        <fieldset>
        	<legend>Reactivos y Materiales</legend>
            	<div data-linea="3">
        			<table id="tbItemsReactivoMaterial" style="width:100%">
        				<thead>
        					<tr>
        						<th style="width: 10%;">#</th>
        						<th style="width: 80%;">Reactivos y Materiales</th>
                                <th style="width: 10%;"></th>
        					</tr>
        				</thead>
        				<tbody>
        				</tbody>
        			</table>
        		</div>		
    	</fieldset>
    </div>
</div>

<div class="pestania">
	<form id='formularioFormaFisFarCosProducto' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>DossierPecuario' data-opcion='FormaFisFarCosProducto/guardar' data-destino="detalleItem" method="post" class="editable">
		<input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $this->modeloSolicitud->getIdSolicitud(); ?>" />
		<input type="hidden" id="idGrupoProducto" name="idGrupoProducto" value="<?php echo $this->modeloSolicitud->getIdGrupoProducto(); ?>" />
    
    	<fieldset>
    		<legend>Forma Farmacéutica</legend>				
    
    		<div data-linea="1">
    			<label for="id_forma">Forma farmacéutica: </label>
    			<select id="id_forma" name="id_forma" >
                    <option value="">Seleccionar....</option>
                    <?php
                        echo $this->comboFormaFisFarCos('IAV');
                    ?>
                </select>
    		</div>
    
    		<div data-linea="2">
    			<button type="submit" class="guardar">Agregar</button>
    		</div>
    	</fieldset >
    </form >
    
    <div id="tablaFormaFisFarCosProducto">
        <fieldset>
        	<legend>Forma Farmacéutica</legend>
            	<div data-linea="3">
        			<table id="tbItemsFormaFisFarCosProducto" style="width:100%">
        				<thead>
        					<tr>
        						<th style="width: 10%;">#</th>
        						<th style="width: 80%;">Forma farmacéutica</th>
                                <th style="width: 10%;"></th>
        					</tr>
        				</thead>
        				<tbody>
        				</tbody>
        			</table>
        		</div>		
    	</fieldset>
    </div>
    
    <form id='formularioPaso5' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>DossierPecuario' data-opcion='solicitud/guardar' data-destino="detalleItem" method="post">
    	<input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $this->modeloSolicitud->getIdSolicitud(); ?>" />
    
    	<fieldset>
    		<legend>Especificaciones y características del producto</legend>
    		
    		<div data-linea="5">
    			<label for="principios_tecnica">Principios de la técnica: </label>
    			<input type="text" id="principios_tecnica" name="principios_tecnica" value="<?php echo $this->modeloSolicitud->getPrincipiosTecnica(); ?>" maxlength="1024" class="formulario" />
    		</div>	
    		
    		<div data-linea="6">
    			<label for="deteccion_antigenos">Detección de antígenos o anticuerpos en la técnica: </label>
    			<input type="text" id="deteccion_antigenos" name="deteccion_antigenos" value="<?php echo $this->modeloSolicitud->getDeteccionAntigenos(); ?>" maxlength="1024" class="formulario" />
    		</div>
    		
    		<div data-linea="7">
    			<label for="muestras_usadas">Muestras usadas para la técnica: </label>
    			<input type="text" id="muestras_usadas" name="muestras_usadas" value="<?php echo $this->modeloSolicitud->getMuestrasUsadas(); ?>" maxlength="1024" class="formulario" />
    		</div>
    		
    		<div data-linea="8">
    			<label for="caracteristicas_fis_quim_org">Características físicas, químicas y organolépticas: </label>
    			<input type="text" id="caracteristicas_fis_quim_org" name="caracteristicas_fis_quim_org" value="<?php echo $this->modeloSolicitud->getCaracteristicasFisQuimOrg(); ?>" maxlength="1024" class="formulario" />
    		</div>

    	</fieldset>
    	
    	
    	<fieldset>
    		<legend>Métodos de Control y Evaluación, cuando corresponda</legend>
    		
    		<div data-linea="9">
    			<label for="pruebas_fis_quim">Pruebas físico-químicas: </label>
    			<input type="text" id="pruebas_fis_quim" name="pruebas_fis_quim" value="<?php echo $this->modeloSolicitud->getPruebasFisQuim(); ?>" maxlength="512" class="formulario" />
    		</div>	    		
    		    		
    		<div data-linea="10">
    			<label for="inocuidad_esterilidad">Inocuidad/Esterilidad: </label>
    			<input type="text" id="inocuidad_esterilidad" name="inocuidad_esterilidad" value="<?php echo $this->modeloSolicitud->getInocuidadEsterilidad(); ?>" maxlength="512" class="formulario" />
    		</div>
		
			<div data-linea="11">
    			<label for="sensibilidad">Sensibilidad: </label>
    			<input type="text" id="sensibilidad" name="sensibilidad" value="<?php echo $this->modeloSolicitud->getSensibilidad(); ?>" maxlength="512" class="formulario" />
    		</div>	
    		
    		<div data-linea="12">
    			<label for="especificidad">Especificidad: </label>
    			<input type="text" id="especificidad" name="especificidad" value="<?php echo $this->modeloSolicitud->getEspecificidad(); ?>" maxlength="512" class="formulario" />
    		</div>	
    		
    		<div data-linea="13">
    			<label for="datos_repetibilidad">Datos relativos a la repetibilidad: </label>
    			<input type="text" id="datos_repetibilidad" name="datos_repetibilidad" value="<?php echo $this->modeloSolicitud->getDatosRepetibilidad(); ?>" maxlength="512" class="formulario" />
    		</div>	
    		
    		<div data-linea="14">
    			<label for="datos_especificidad">Datos sobre la especificidad analítica: </label>
    			<input type="text" id="sensibidatos_especificidadlidad" name="datos_especificidad" value="<?php echo $this->modeloSolicitud->getDatosEspecificidad(); ?>" maxlength="512" class="formulario" />
    		</div>	
    		
    		<div data-linea="15">
    			<label for="datos_sensibilidad">Datos sobre la sensibilidad analítica: </label>
    			<input type="text" id="datos_sensibilidad" name="datos_sensibilidad" value="<?php echo $this->modeloSolicitud->getDatosSensibilidad(); ?>" maxlength="512" class="formulario" />
    		</div>				
    	</fieldset>	
    	
    	<div data-linea="16" class="editable">
			<button type="submit" class="guardar">Guardar</button>
		</div>
	</form>    	
</div>

<div class="pestania">
    <form id='formularioUsoEspecie' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>DossierPecuario' data-opcion='UsoEspecie/guardar' data-destino="detalleItem" method="post" class="editable">
    	<input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $this->modeloSolicitud->getIdSolicitud(); ?>" />
    	
    	<fieldset>
    		<legend>Especies de Destino y Usos</legend>				
    
    		<div data-linea="1">
    			<label for="id_uso">Uso: </label>
    			<select id="id_uso" name="id_uso" >
                    <option value="">Seleccionar....</option>
                    <?php
                        echo $this->comboUsos('IAV');
                    ?>
                </select>
    		</div>				
    
    		<div data-linea="2">
    			<label for="id_especie">Especie: </label>
    			<select id="id_especie" name="id_especie" >
                    <option value="">Seleccionar....</option>
                    <?php
                        echo $this->comboEspecies();
                    ?>
                </select>
    		</div>				
    
    		<div data-linea="3">
    			<label for="nombre_especie_uso">Nombre especie: </label>
    			<input type="text" id="nombre_especie_uso" name="nombre_especie" maxlength="128" />
    		</div>
    
    		<div data-linea="4">
    			<button type="submit" class="guardar">Agregar</button>
    		</div>
    	</fieldset >
    </form >
    
    <div id="tablaUsoEspecie">
        <fieldset>
        	<legend>Especies de Destino y Usos</legend>
            	<div data-linea="3">
        			<table id="tbItemsUsoEspecie" style="width:100%">
        				<thead>
        					<tr>
        						<th style="width: 5%;">#</th>
        						<th style="width: 30%;">Uso</th>
        						<th style="width: 30%;">Especie</th>
        						<th style="width: 30%;">Nombre especie</th>
                                <th style="width: 5%;"></th>
        					</tr>
        				</thead>
        				<tbody>
        				</tbody>
        			</table>
        		</div>		
    	</fieldset>
    </div>
    
    <form id='formularioPaso6' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>DossierPecuario' data-opcion='solicitud/guardar' data-destino="detalleItem" method="post">
    	<input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $this->modeloSolicitud->getIdSolicitud(); ?>" />
    
    	<fieldset>
    		<legend>Modo de uso, resultados e interprestaciones, cuando corresponda</legend>
    		
    		<div data-linea="5">
    			<label for="determinacion_anticuerpos">Detección de anticuerpos de vacunación o infección: </label>
    			<input type="text" id="determinacion_anticuerpos" name="determinacion_anticuerpos" value="<?php echo $this->modeloSolicitud->getDeterminacionAnticuerpos(); ?>" maxlength="512" class="formulario" />
    		</div>	
    		
    		<div data-linea="6">
    			<label for="determinacion_microorganismos">Determinación de microorganismos: </label>
    			<input type="text" id="determinacion_microorganismos" name="determinacion_microorganismos" value="<?php echo $this->modeloSolicitud->getDeterminacionMicroorganismos(); ?>" maxlength="512" class="formulario" />
    		</div>
    		
    		<div data-linea="7">
    			<label for="determinacion_estados_fisiologicos">Determinación de estados fisiológicos: </label>
    			<input type="text" id="determinacion_estados_fisiologicos" name="determinacion_estados_fisiologicos" value="<?php echo $this->modeloSolicitud->getDeterminacionEstadosFisiologicos(); ?>" maxlength="512" class="formulario" />
    		</div>
    		
    		<div data-linea="8">
    			<label for="determinacion_datos_clinicos">Determinación de datos clínicos: </label>
    			<input type="text" id="determinacion_datos_clinicos" name="determinacion_datos_clinicos" value="<?php echo $this->modeloSolicitud->getDeterminacionDatosClinicos(); ?>" maxlength="512" class="formulario" />
    		</div>

    		<div data-linea="9">
    			<label for="modo_uso">Modo de uso correcto del producto: </label>
    			<input type="text" id="modo_uso" name="modo_uso" value="<?php echo $this->modeloSolicitud->getModoUso(); ?>" maxlength="1024" class="formulario" />
    		</div>	    		
    		    		
    		<div data-linea="10">
    			<label for="resultado_interpretacion">Resultados e interpretaciones: </label>
    			<input type="text" id="resultado_interpretacion" name="resultado_interpretacion" value="<?php echo $this->modeloSolicitud->getResultadoInterpretacion(); ?>" maxlength="1024" class="formulario" />
    		</div>				
    	</fieldset>	
    	
    	<div data-linea="16" class="editable">
			<button type="submit" class="guardar">Guardar</button>
		</div>
	</form>  
</div>
    	
<div class="pestania">
	<form id='formularioPeriodoVidaUtil' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>DossierPecuario' data-opcion='PeriodoVidaUtil/guardar' data-destino="detalleItem" method="post" class="editable">
		<input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $this->modeloSolicitud->getIdSolicitud(); ?>" />
		
    	<fieldset>
    		<legend>Período de Vida Útil</legend>				
    
    		<div data-linea="1">
    			<label for="descripcion_envase">Descripción del envase: </label>
    			<input type="text" id="descripcion_envase" name="descripcion_envase" maxlength="512" />
    		</div>				
    
    		<div data-linea="5">
    			<label for="periodo_vida_util">Período de vida útil: </label>
    			<input type="number" id="periodo_vida_util" name="periodo_vida_util" min="0" step="0.01" />
    		</div>				
    
    		<div data-linea="6">
    			<label for="id_unidad_tiempo">Tiempo: </label>
    			<select id="id_unidad_tiempo" name="id_unidad_tiempo">
                    <option value="">Seleccionar....</option>
                    <?php
                        echo $this->comboUnidadTiempoXId();
                    ?>
                </select>
    			
    			<input type="hidden" id="nombre_unidad_periodo_vida" name="nombre_unidad_periodo_vida" maxlength="32" />
    		</div>				
    
    		<div data-linea="8">
    			<button type="submit" class="guardar">Agregar</button>
    		</div>
    	</fieldset >
    </form >
    
    <div id="tablaPeriodoVidaUtil">
        <fieldset>
        	<legend>Período de Vida Útil</legend>
            	<div data-linea="3">
        			<table id="tbItemsPeriodoVidaUtil" style="width:100%">
        				<thead>
        					<tr>
        						<th style="width: 5%;">#</th>
        						<th style="width: 45%;">Descripción del envase</th>
        						<th style="width: 45%;">Período de vida útil</th>
                                <th style="width: 5%;"></th>
        					</tr>
        				</thead>
        				<tbody>
        				</tbody>
        			</table>
        		</div>		
    	</fieldset>
    </div>
    
    <form id='formularioConservacion' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>DossierPecuario' data-opcion='solicitud/guardar' data-destino="detalleItem" method="post">
    	<input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $this->modeloSolicitud->getIdSolicitud(); ?>" />
    	
    	<fieldset>
    		<legend>Precauciones Generales y Control sobre Residuos</legend>
    		
    		<div data-linea="65">
    			<label for="precauciones_generales">Precauciones generales: </label>
    			<input type="text" id="precauciones_generales" name="precauciones_generales" value="<?php echo $this->modeloSolicitud->getPrecaucionesGenerales(); ?>" maxlength="1024" class="formulario" />
    		</div>				
    
    		<div data-linea="66">
    			<label for="efectos_colaterales">Efectos colaterales, locales o generales, incompatibles, antagonismos y contraindicaciones: </label>
    		</div>
    		
    		<div data-linea="67">
    			<input type="text" id="efectos_colaterales" name="efectos_colaterales" value="<?php echo $this->modeloSolicitud->getEfectosColaterales(); ?>" maxlength="1024" class="formulario" />
    		</div>				
    		
    	</fieldset>
    	
    	<fieldset>
    		<legend>Conservación del producto</legend>
    		
    		<div data-linea="1">
    			<label for="temperatura_almacenamiento">Temperatura de almacenamiento: </label>
    			<input type="text" id="temperatura_almacenamiento" name="temperatura_almacenamiento" value="<?php echo $this->modeloSolicitud->getTemperaturaAlmacenamiento(); ?>" maxlength="64" class="formulario" />
    		</div>				
    
    		<div data-linea="2">
    			<label for="humedad_almacenamiento">Humedad de almacenamiento: </label>
    			<input type="text" id="humedad_almacenamiento" name="humedad_almacenamiento" value="<?php echo $this->modeloSolicitud->getHumedadAlmacenamiento(); ?>" maxlength="64" class="formulario" />
    		</div>	
    	</fieldset>
    	
    	<div data-linea="3" class="editable">
			<button type="submit" class="guardar">Guardar</button>
		</div>
    </form>
</div>    	
    	
<div class="pestania">    
    <form id='formularioPrecauciones' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>DossierPecuario' data-opcion='solicitud/guardar' data-destino="detalleItem" method="post">
    	<input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $this->modeloSolicitud->getIdSolicitud(); ?>" />
    	
   		<fieldset>
			<legend>Declaración de Venta</legend>
			
			<div data-linea="67">
    			<label for="id_declaracion_venta">Declaración de venta: </label>
    			<select id="id_declaracion_venta" name="id_declaracion_venta" class="formulario" >
                    <option value="">Seleccionar....</option>
                    <?php
                        echo $this->comboDeclaracionVenta($this->modeloSolicitud->getIdDeclaracionVenta());
                    ?>
                </select>
    		</div>				
			
		</fieldset>
		
		<fieldset>
			<legend>Observaciones del Producto</legend>
			
			<div data-linea="68">
    			<label for="observaciones_producto">Observaciones: </label>
    			<input type="text" id="observaciones_producto" name="observaciones_producto" value="<?php echo $this->modeloSolicitud->getObservacionesProducto(); ?>" maxlength="2048" class="formulario" />
    		</div>				
		</fieldset>
		
		<div data-linea="6" class="editable">
			<button type="submit" class="guardar">Guardar</button>
		</div>
	</form>
</div>    	
    	
<div class="pestania">
	<p class="nota">Se permitirá solamente el ingreso de un <b>máximo de 7 documentos anexos de 6Mbs cada uno</b>. <br/>
		Para agregar más elementos puede usar la sección inferior para remitir documentos cargados en un servidor externo.<br/>
		El solicitante debe asegurar que estos documentos (enlaces) se encuentren disponibles para consulta por un <b>tiempo mínimo de 7 años</b>.
	</p>
	<form id='formularioDocumentoAnexo' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>DossierPecuario' data-opcion='DocumentoAnexo/guardar' data-destino="detalleItem" method="post" class="editable">
		<input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $this->modeloSolicitud->getIdSolicitud(); ?>" />
		
    	<fieldset>
    		<legend>Documentos Anexos</legend>				
    
    		<div data-linea="1">
    			<label for="id_tipo_documento">Tipo de documento anexo: </label>
    			<select id="id_tipo_documento" name="id_tipo_documento" >
                    <option value="">Seleccionar....</option>
                    <?php
                        echo $this->comboDocumentosAnexosPecuarios($this->modeloSolicitud->getIdGrupoProducto(), $this->modeloSolicitud->getTipoSolicitud());
                    ?>
                </select>
    		</div>				
    
    		<div data-linea="2">
    			<label for="descripcion_documento">Descripción del documento: </label>
    			<input type="text" id="descripcion_documento_anexo" name="descripcion_documento" maxlength="512" />
    		</div>				
    
    		<div data-linea="3">
    			<label for="ruta_documento">Documento anexo: </label>
    			<input type="file" id="archivoAnexo" class="archivoAnexo" accept="application/pdf" /> 
    			<input type="hidden" class="rutaArchivoAnexo" name="ruta_documento" id="ruta_documento_anexo" />
    				
        		<div class="estadoCargaAnexo">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
        		<button type="button" class="subirArchivoAnexo adjunto" data-rutaCarga="<?php echo DOSS_PEC_URL . $this->modeloSolicitud->getIdExpediente();?>">Subir archivo</button>
        	</div>

    		<div data-linea="4">
    			<button type="submit" class="guardar">Agregar</button>
    		</div>
    	</fieldset >
    </form >

	<div id="tablaDocumentoAnexo">
        <fieldset>
        	<legend>Documentos anexos</legend>
            	<div data-linea="3">
        			<table id="tbItemsDocumentoAnexo" style="width:100%">
        				<thead>
        					<tr>
        						<th style="width: 5%;">#</th>
        						<th style="width: 30%;">Tipo de documento</th>
        						<th style="width: 40%;">Descripción</th>
        						<th style="width: 20%;">Enlace</th>
                                <th style="width: 5%;"></th>
        					</tr>
        				</thead>
        				<tbody>
        				</tbody>
        			</table>
        		</div>		
    	</fieldset>
    </div>
    
    
    <form id='formularioDocumentoExterno' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>DossierPecuario' data-opcion='DocumentoAnexo/guardar' data-destino="detalleItem" method="post" class="editable">
		<input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $this->modeloSolicitud->getIdSolicitud(); ?>" />
		<input type="hidden" id="id_tipo_documento_externo" name="id_tipo_documento" value="0" maxlength="8" />
		
    	<fieldset>
    		<legend>Documentos Externos</legend>				
    
    		<div data-linea="1">
    			<label for="descripcion_documento">Descripción del documento: </label>
    			<input type="text" id="descripcion_documento_externo" name="descripcion_documento" maxlength="512" />
    		</div>				
    
    		<div data-linea="6">
    			<label for="ruta_documento_externo">Enlace a documentos externos: </label>
    			<input type="text" id="ruta_documento_externo" name="ruta_documento" maxlength="512" />
    		</div>
    
    		<div data-linea="7">
    			<button type="submit" class="guardar">Agregar</button>
    		</div>
    	</fieldset >
    </form >

	<div id="tablaDocumentoExterno">
        <fieldset>
        	<legend>Documentos externos</legend>
            	<div data-linea="3">
        			<table id="tbItemsDocumentoExterno" style="width:100%">
        				<thead>
        					<tr>
        						<th style="width: 5%;">#</th>
        						<th style="width: 30%;">Tipo de documento</th>
        						<th style="width: 40%;">Descripción</th>
        						<th style="width: 20%;">Enlace</th>
                                <th style="width: 5%;"></th>
        					</tr>
        				</thead>
        				<tbody>
        				</tbody>
        			</table>
        		</div>		
    	</fieldset>
    </div>
</div>
    	
<div class="pestania">  	
	<form id='formularioCondiciones' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>DossierPecuario' data-opcion='solicitud/guardar' data-accionEnExito="ACTUALIZAR" data-destino="detalleItem" method="post">
    	<input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $this->modeloSolicitud->getIdSolicitud(); ?>" />
    	<input type="hidden" id="estado_solicitud" name="estado_solicitud" value="<?php echo $this->modeloSolicitud->getEstadoSolicitud(); ?>" />
    	<input type="hidden" id="tipo_solicitud" name="tipo_solicitud" value="<?php echo $this->modeloSolicitud->getTipoSolicitud(); ?>" />
    	<input type="hidden" id="fase_revision" name="fase_revision" value="Operador" />
    	
    	<fieldset>
    		<legend>Condiciones de la información:</legend>
    		
    		<div data-linea="1">
    			<p style="text-align: center;"><b>TÉRMINOS Y CONDICIONES GENERALES DE USO</b></p>
    			<p>La utilización del sistema le atribuye la condición de Usuario e implica la aceptación plena de todas y 
    			cada una de las disposiciones, reglamentos y/o normativas emitidas por la AUTORIDAD NACIONAL COMPETENTE (ANC) 
    			en el momento mismo en que el Usuario acceda al sistema. En consecuencia, el Usuario debe leer atentamente el 
    			presente Aviso en cada una de las ocasiones en que se proponga utilizar el sistema, ya que los módulos
    			automatizados pueden sufrir modificaciones.</p>
    			<p>1.- El usuario garantiza la autenticación y veracidad de todos aquellos datos que ingrese al completar 
    			el/los formulario/s de registro y/o modificación de registro.</p>
    			<p>2.- El usuario se compromete y se responsabiliza de que toda la información  ingresada sea actualizada 
    			y verídica.</p>
    			<p>3.- Se prohíbe el uso de cualquier tipo de programa que pretenda extraer información de sistema de forma 
    			automatizada y no autorizada.</p>
    			<p>4.- El usuario no podrá utilizar la información contenida en el sistema con propósitos diferentes a los
    			autorizados o permitidos por la ANC.</p>
    			<p>5.- Al usar el presente módulo, usted acepta y está de acuerdo con estos términos y condiciones en lo 
    			que se refiere al uso del mismo, al manejo, almacenamiento y uso de datos previamente ingresados.</p>
    		</div>
    		
    		<div data-linea="2">
    			<label for="condiciones">Acepto las condiciones </label>
    			<select id="condiciones" required class="formulario">
                    <?php
                        echo $this->comboSiNo('Si');
                    ?>
                </select>
    		</div>
    
    	</fieldset >
    	
    	<fieldset class="archivoCambios">
    		<legend>Información de Cambios</legend>
    		
    		<div data-linea="3">
    			<p class="nota">Por favor detallar la información que fue modificada y el motivo del cambio.</p>
    		</div>
    		
    		<div data-linea="4">
    			<label for="observaciones_cambios">Resumen de cambios realizados por el usuario: </label>
    			<input type="text" id="observaciones_cambios" name="observaciones_cambios"  maxlength="2048" />
        	</div>
    		
    		<div data-linea="5">
    			<label for="ruta_cambios_usuario">Informe de cambios realizados por el usuario: </label>
    			<input type="file" id="archivoCambios" class="archivoCambios" accept="application/pdf"/>
    			<input type="hidden" class="rutaArchivoCambios" name="ruta_cambios_usuario" id="ruta_cambios_usuario" />
    				
        		<div class="estadoCargaCambios">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
        		<button type="button" class="subirArchivoCambios adjunto" data-rutaCarga="<?php echo DOSS_PEC_URL . $this->modeloSolicitud->getIdExpediente();?>">Subir archivo</button>
        	</div>

    	</fieldset>
    	
    	<div data-linea="6" class="editable">
			<button id="botonEnviar" type="submit" class="guardar">Finalizar y enviar</button>
		</div>
    </form>
</div >

<div id="tecnico" class="pestania">
	<form id='formularioRevision' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>DossierPecuario' data-opcion='solicitud/guardar' data-accionEnExito="ACTUALIZAR" data-destino="detalleItem" method="post" class="tecnico" >
    	<input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $this->modeloSolicitud->getIdSolicitud(); ?>" />
    	<input type="hidden" id="tipo_solicitud" name="tipo_solicitud" value="<?php echo $this->modeloSolicitud->getTipoSolicitud(); ?>" />
    	<input type="hidden" id="fase_revision" name="fase_revision" value="Tecnico" />
    	
    	<fieldset>
    		<legend>Resultado de la Revisión</legend>
    		
    		<div data-linea="1" class="tiempo">
    			<label for="tiempo_subsanacion">Tiempo del usuario para remitir subsanación: </label>
				<?php echo $this->modeloSolicitud->getTiempoSubsanacion() .' días hábiles.'; ?>
    		</div>
    		
    		<div data-linea="2">
    			<label for="estado_solicitud">Resultado: </label>
    			<select id="estado_solicitud_tecnico" name="estado_solicitud" required>
    				<option value="">Seleccionar....</option>
                    <?php
                        echo $this->comboEstadosRevisionDossierPecuarioTecnico(null);
                    ?>
                </select>
    		</div>
    		
    		<div data-linea="3">
    			<label for="observacion_revision">Observaciones: </label>
				<input type="text" id="observacion_revision" name="observacion_revision" maxlength="2048" />
    		</div>
    		
    		<div data-linea="4" class="archivoSubsanacion">
    			<label for="ruta_documento_subsanacion">Documento anexo: </label>
    			<input type="file" id="archivoSubsanacion" class="archivoSubsanacion" accept="application/pdf" /> 
    			<input type="hidden" class="rutaArchivoSubsanacion" name="ruta_documento_subsanacion" id="ruta_documento_subsanacion" />
    				
        		<div class="estadoCargaSubsanacion">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
        		<button type="button" class="subirArchivoSubsanacion adjunto" data-rutaCarga="<?php echo DOSS_PEC_URL . $this->modeloSolicitud->getIdExpediente();?>">Subir archivo</button>
        	</div>
   
    	</fieldset >
    	
    	<div id="cargarMensajeTemporal"></div>
    	
    	<div data-linea="5">
    			<button type="submit" class="guardar">Guardar</button>
    		</div>
    </form>
</div>

<script type ="text/javascript">
var grupoProducto = <?php echo json_encode($this->modeloSolicitud->getIdGrupoProducto()); ?>;
var estadoSolicitud = <?php echo json_encode($this->modeloSolicitud->getEstadoSolicitud()); ?>;
var tipoSolicitud = <?php echo json_encode($this->modeloSolicitud->getTipoSolicitud()); ?>;
var identificadorUsuario = <?php echo json_encode($this->modeloSolicitud->getIdentificadorTitular()); ?>;
//var identificadorUsuario = $("#identificador_titular").val();
var bandera = <?php echo json_encode($this->formulario); ?>;
var combo = "<option>Seleccione....</option>";

	$(document).ready(function() {
		fn_limpiar();		
		
		//***PASO 1
		//Obtiene la información del usuario de su registro de operador
		fn_buscarDatosOperador();
		
		if(tipoSolicitud != 'Registro'){
			$(".Modificacion").show();
			fn_activarCamposModificacion();
		}else{
			$(".Modificacion").hide();
			fn_inactivarCamposModificacion();
		}
				
		fn_cargarSubtipoProducto();
		cargarValorDefecto("id_subtipo_producto","<?php echo $this->modeloSolicitud->getIdSubtipoProducto();?>");

		//***PASO 2
		$(".TitularNacional").hide();
		$(".TitularNacionalExtranjero").hide();
		$(".Extranjero").hide();
		fn_mostrarDetalleOrigen();

		//***PASO 3
		$(".NombreUnidadPresentacion").hide();
		fn_mostrarDetallePartidaCodigos();
		fn_mostrarDetallePresentacionComercial();
		
		//***PASO 4
		$(".NombreUnidadComposicion").hide();
		$(".NombreUnidadComponente").hide();
		fn_mostrarDetalleComposicion();
		fn_mostrarDetalleReactivoMaterial();
		
		//***PASO 5
		fn_mostrarDetalleFormaFisFarCosProducto();

		//***PASO 6
		fn_mostrarDetalleUsoEspecie();

		//***PASO 7
		fn_mostrarDetallePeriodoVidaUtil();

		//***PASO 8
		fn_mostrarDetalleDocumentoAnexo();
		fn_mostrarDetalleDocumentoExterno();
		
		//***PASO 9
		cargarValorDefecto("condiciones","Si");
		
		//***PASO 10
		$(".archivoSubsanacion").hide();

		if(bandera == 'editar'){
			$(".aprobado").hide();
			$(".tecnico").hide();
			$("#tecnico").remove();
			$(".editable").show();
			$(".pago").hide();
			$(".tecnicoAsignado").hide();
			$(".resultadoRevisionFinal").hide();
			
			if(estadoSolicitud == 'Creado'){
				$(".resultadoRevision").hide();
				$(".archivoCambios").hide();
			}else{
				$(".resultadoRevision").show();
				$(".archivoCambios").show();
				if(tipoSolicitud != 'Modificacion'){
					$(".tiempo").show();
				}else{
					$(".tiempo").hide();
				}
			}
		}else if (bandera == 'abrir'){
			$(".aprobado").hide();
			$(".tecnico").hide();
			$("#tecnico").remove();
			$(".resultadoRevision").hide();
			$(".resultadoRevisionFinal").hide();
			$(".editable").hide();
			$(".archivoCambios").hide();
			$(".formulario").attr('disabled','disabled');
			$(".pago").hide();
			$(".tecnicoAsignado").hide();
			$(".detallePreparacion").show();

			if(estadoSolicitud == 'EnTramite'){
				$(".resultadoRevision").hide();
				$(".tecnicoAsignado").show();
				$(".pago").hide();
			}else if(estadoSolicitud == 'Aprobado'){
				$(".aprobado").show();
				$(".resultadoRevisionFinal").show();	
				$(".pago").hide();			
			}else if(estadoSolicitud == 'verificacion'){
				$(".pago").show();
				$(".resultadoRevision").hide();
			}else if(estadoSolicitud == 'Modificado'){
				$(".pago").hide();
				$(".aprobado").show();				
			}else if(estadoSolicitud == 'Rechazado'){
				$(".pago").hide();
				$(".resultadoRevisionFinal").show();				
			}else{
				$(".resultadoRevision").hide();
				$(".pago").hide();
			}

			if(tipoSolicitud != 'Modificacion'){
				$(".tiempo").show();
			}else{
				$(".tiempo").hide();
			}

			//$("input").attr('disabled','disabled');
			//$("select").attr('disabled','disabled');
			//cargarValorDefecto("condiciones","Si");
		}else if(bandera == 'abrirTecnico'){
			$(".aprobado").hide();
			$(".tecnico").show();
			$(".resultadoRevision").hide();
			$(".resultadoRevisionFinal").hide();
			$(".editable").hide();
			$(".archivoCambios").hide();
			$(".tiempo").hide();
			$(".pago").hide();
			$(".tecnicoAsignado").hide();
			$(".detallePreparacion").show();
			$(".formulario").attr('disabled','disabled');
			//cargarValorDefecto("condiciones","Si");

			if(estadoSolicitud == 'Modificado'){
				$(".aprobado").show();
			}

			if(tipoSolicitud != 'Modificacion'){
				$(".tiempo").show();
			}else{
				$(".tiempo").hide();
			}
		}

		construirAnimacion($(".pestania"));
		construirValidador();
		distribuirLineas();
		
	 });

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	/************************************* PASO 1 ***************************************/
	//Función para mostrar los datos del operador
    function fn_buscarDatosOperador() {
    	var identificador = identificadorUsuario;
        
        if (identificador != "" ){
        	$.post("<?php echo URL ?>DossierPecuario/Solicitud/obtenerDatosOperador",
               {
                identificador : identificador
               }, function (data) {
				if(data.validacion == "Fallo"){
	        		mostrarMensaje(data.resultado,"FALLO");    
	        		fn_limpiarDatosOperador();   		
				}else{
					fn_cargarDatosOperador(data);
				}
            }, 'json');
        }
    } 

  //Función para mostrar los datos obtenidos del operador/asociación
    function fn_cargarDatosOperador(data) {
    	$("#razon_social").val(data.razon_social);
    	$("#identificador_titular").val(data.id);
    	$("#direccion").val(data.direccion);
    	$("#provincia").val(data.provincia);
    	$("#canton").val(data.canton);
    	$("#parroquia").val(data.parroquia);
    	$("#telefono").val(data.telefono);
    	$("#celular").val(data.celular);
    	$("#correo").val(data.correo);
		$("#representante_legal").val(data.nombre_representante);
    } 
    
    $("#cambio_titular").change(function () {
		if ($("#cambio_titular").val() == 'Si' ) {
			fn_limpiarDatosOperador();
			$("#identificador_titular").removeAttr('readonly');
			$("#identificador_titular").attr('required', 'required');
        }
    });

    $("#identificador_titular").change(function () {
		if ($("#identificador_titular").val() != '' ) {
			fn_buscarDatosOperadorTitular();
        }else{
			alert('Debe ingresar un número de cédula o RUC válido');
        }
    });

  	//Función para mostrar los datos del operador en cambio de titular
    function fn_buscarDatosOperadorTitular() {
    	var identificador = $("#identificador_titular").val();
        
        if (identificador != "" ){
        	$.post("<?php echo URL ?>DossierPecuario/Solicitud/obtenerDatosOperadorContratoNacional",
               {
                identificador : identificador
               }, function (data) {
				if(data.validacion == "Fallo"){
	        		mostrarMensaje("El usuario seleccionado no cumple con los parámetros requeridos.","FALLO");  
	        		fn_limpiarDatosOperador();    
	        		fn_buscarDatosOperador();
	        		cargarValorDefecto("cambio_titular","No");		
				}else{
					fn_cargarDatosOperador(data);
				}
            }, 'json');
        }
    }     

    function fn_limpiarDatosOperador() {
    	$("#razon_social").val('');
    	$("#identificador_titular").val('');
    	$("#direccion").val('');
    	$("#provincia").val('');
    	$("#canton").val('');
    	$("#parroquia").val('');
    	$("#telefono").val('');
    	$("#celular").val('');
    	$("#correo").val('');
		$("#representante_legal").val('');
    } 

    $("#nombre_producto").change(function () {
		if (($(this).val !== "")  ) {
			fn_validarNombre();
        }
    });
    
    //Función para buscar si existe un producto con el nombre ingresado
    function fn_validarNombre() {
        var nombreProducto = $("#nombre_producto").val();
        
        if (nombreProducto !== "") {
        	mostrarMensaje("","EXITO");
        	$.post("<?php echo URL ?>DossierPecuario/Solicitud/validarNombreProducto/" + nombreProducto, function (data) {
				if(data.validacion == "Exito"){
					mostrarMensaje(data.nombre,"EXITO");
				}else{					
					mostrarMensaje(data.nombre,"FALLO");
	        		$("#nombre_producto").val("");
	        		alert('Debe seleccionar otro nombre para el producto.');
				}
            }, 'json');
        }
    }

  	//Lista de Subtipo de Producto por grupo de producto
    function fn_cargarSubtipoProducto() {
        var idGrupo = $("#id_grupo_producto option:selected").val();
        
        if (idGrupo !== "") {
        	$.post("<?php echo URL ?>DossierPecuario/Solicitud/comboSubtipoProductoXGrupo", 
			{
        		idGrupo : <?php echo $this->modeloSolicitud->getIdGrupoProducto();?>,
        		idSubtipo : <?php echo $this->modeloSolicitud->getIdSubtipoProducto();?>
			},
            function (data) {
				$("#id_subtipo_producto").html(data);
            });
        }
    }

    $("#cambio_subtipo").change(function () {
		if ($("#cambio_subtipo").val() == 'Si' ) {
			$("#id_subtipo_producto").removeAttr('disabled');
			$("#id_subtipo_producto").attr('required', 'required');
        }else{
        	$("#id_subtipo_producto").attr('disabled', 'disabled');
        	$("#id_subtipo_producto").removeAttr('required');
        }
    });

    $("#id_subtipo_producto").change(function () {
    	$("#codificacion_subtipo_producto").val("");
    	
        if ($(this).val !== "") {
            $("#codificacion_subtipo_producto").val($("#id_subtipo_producto option:selected").attr('data-codigo'));
        }

        if($("#codificacion_subtipo_producto").val() == 'FM'){
        	$("#nombre_producto").removeAttr('required'); 
        	$("#nombre_producto").attr('disabled', 'disabled'); 
        }else{
        	$("#nombre_producto").attr('required', 'required'); 
        	$("#nombre_producto").removeAttr('disabled');
        }
    });
    
    //Función para buscar si existe un producto con el nombre ingresado
    function fn_validarNombre() {
        var nombreProducto = $("#nombre_producto").val();
        
        $.post("<?php echo URL ?>DossierPecuario/Solicitud/validarNombreProducto",
                {
                 nombre : nombreProducto
                }, function (data) {
                	if(data.validacion == "Exito"){
    					mostrarMensaje(data.nombre,"EXITO");
    				}else{					
    					mostrarMensaje(data.nombre,"FALLO");
    	        		$("#nombre_producto").val("");
    	        		alert('Debe seleccionar otro nombre para el producto.');
    				}
             }, 'json');
    }

    function fn_activarCamposModificacion() {
    	$("#tipo_solicitud").removeAttr('disabled');
    	$("#tipo_solicitud").attr('required', 'required');

    	$("#cambio_titular").removeAttr('disabled');
    	$("#cambio_titular").attr('required', 'required');
    } 

    function fn_inactivarCamposModificacion() {
    	$("#tipo_solicitud").attr('disabled', 'disabled');
    	$("#tipo_solicitud").removeAttr('required');

    	$("#cambio_titular").attr('disabled', 'disabled');
    	$("#cambio_titular").removeAttr('required');
    }

	$("#formularioOperadorProducto").submit(function (event) {
		event.preventDefault();
		var error = false;

		if(!$.trim($("#nombre_producto").val())){
        	error = true;
        	$("#nombre_producto").addClass("alertaCombo");
		}
		
		if (!error) {
			var respuesta = JSON.parse(ejecutarJson($(this)).responseText);

			if (respuesta.estado == 'exito'){		
				$("#estado").html("Se han guardado los datos con éxito.").addClass("exito");
			}else{
				$("#estado").html(respuesta.mensaje).addClass("fallo");
			}
	       
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	/************************************* PASO 2 ***************************************/
	$("#origen_fabricacion").change(function () {
		$(".TitularNacional").hide();
		$(".TitularNacionalExtranjero").hide();
		$(".Extranjero").hide();
		fn_limpiarOrigenProducto();
		
		if (($(this).val !== "")  ) {
			if (($("#origen_fabricacion option:selected").val() == "TitularRegistro")  ) {
				fn_buscarDatosOperadorFabricanteTitular();
				$(".TitularNacional").show();
				$(".TitularNacionalExtranjero").show();
				$(".Extranjero").hide();
				$("#lNombreFabricante").text('Razón social: ');
				$("#identificador_fabricante").attr("required", "required");
				$("#nombre_fabricante").attr("readonly", "readonly");
	        }else if (($("#origen_fabricacion option:selected").val() == "ContratoNacional")  ) {
				$("#identificador_fabricante").removeAttr('readonly');
				$(".TitularNacional").show();
				$(".TitularNacionalExtranjero").show();
				$(".Extranjero").hide();
				$("#lNombreFabricante").text('Razón social: ');
				$("#identificador_fabricante").attr("required", "required");
				$("#nombre_fabricante").attr("readonly", "readonly");
	        }else if (($("#origen_fabricacion option:selected").val() == "Extranjero")  ) {
				//Pendiente función para búsqueda de fabricantes extranjeros en módulo externo
				$(".TitularNacional").hide();
				$(".TitularNacionalExtranjero").show();
				$(".Extranjero").show();
				$("#lNombreFabricante").text('Nombre fabricante extranjero: ');
				$("#identificador_fabricante").removeAttr("required");
				$("#nombre_fabricante").removeAttr("readonly");

				fn_cargarFabricantesExterior();
	        }else{
	        	fn_limpiarOrigenProducto();
	        	$(".TitularNacional").hide();
	    		$(".TitularNacionalExtranjero").hide();
	    		$(".Extranjero").hide();
	    		$("#identificador_fabricante").attr("required", "required");
	    		$("#nombre_fabricante").attr("readonly", "readonly");
	    		$("#nombre_fabricante").autocomplete({
	    			disabled: true
	    		});
	        }
        }
    });

	//Función para mostrar los datos del operador
    function fn_buscarDatosOperadorFabricanteTitular() {
    	var identificador = identificadorUsuario;
    	        
        if (identificador !== "" ){
        	$.post("<?php echo URL ?>DossierPecuario/Solicitud/obtenerDatosOperador",
               {
                identificador : identificador
               }, function (data) {
				if(data.validacion == "Fallo"){
	        		mostrarMensaje(data.resultado,"FALLO");    
	        		fn_limpiarOrigenProductoCompleto();	
				}else{
					fn_cargarDatosOperadorFabricanteNacional(data);
				}
            }, 'json');
        }
    } 

    $("#identificador_fabricante").change(function () {
    	if (($(this).val !== "")  ) {
			fn_buscarDatosOperadorFabricanteContratoNacional();
    	}else{
			alert("Debe ingresar un número de RUC o cédula para proceder");
    	}
    });

  //Función para mostrar los datos del operador
    function fn_buscarDatosOperadorFabricanteContratoNacional() {
    	var identificador = $("#identificador_fabricante").val();
        
        if (identificador !== "" ){
        	$.post("<?php echo URL ?>DossierPecuario/Solicitud/obtenerDatosOperadorContratoNacional",
               {
                identificador : identificador
               }, function (data) {
				if(data.validacion == "Fallo"){
	        		mostrarMensaje(data.resultado,"FALLO");   
	        		fn_limpiarOrigenProductoCompleto();    		
				}else{
					fn_cargarDatosOperadorFabricanteNacional(data);
				}
            }, 'json');
        }
    } 

  	//Función para mostrar los datos del fabricante cuando es Titular del Registro o por Contrato Nacional
    function fn_cargarDatosOperadorFabricanteNacional(data) {
    	$("#identificador_fabricante").val(data.id);
    	$("#nombre_fabricante").val(data.razon_social);
    	$("#direccion_fabricante").val(data.direccion);
    	$("#id_provincia_fabricante").val(data.id_provincia);
    	$("#provincia_fabricante").val(data.provincia);
    	$("#id_pais").val(data.id_pais);
    	$("#pais").val(data.pais);
    } 

  //Para cargar los fabricantes en el exterior en estado Aprobado mostrando su nombre
    function fn_cargarFabricantesExterior() {
        var estado = "'Aprobado'";
        
    	if ($("#origen_fabricacion option:selected").val() == "Extranjero") {
        	$.post("<?php echo URL ?>DossierPecuario/Solicitud/comboProveedoresExterior/" + estado, function (data) {
            	if(data.mensaje.length != 0){
            		$("#nombre_fabricante").autocomplete({
						disabled: false,
            			source: data.mensaje,
            			change:function(event, ui){
                    		if (ui.item == null || ui.item == undefined) {
                    			$("#nombre_fabricante").val("");
                    			fn_limpiarOrigenProductoCompleto(); 
                    		}
                    		//fn_buscarDatosOperadorFabricanteExtranjero();
            			}
            		});
                }else{
                	$("#nombre_fabricante").val('');
                	fn_limpiarOrigenProductoCompleto(); 
                	mostrarMensaje("No existen proveedores disponibles.", "FALLO");
				}
        		
            }, 'json');
        }
    } 

    $("#nombre_fabricante").change(function () {
    	if (($(this).val !== "")  ) {
    		fn_buscarDatosOperadorFabricanteExtranjero();
    	}else{
			alert("Debe seleccionar un fabricante para proceder");
    	}
    });

  //Función para mostrar los datos del operador
    function fn_buscarDatosOperadorFabricanteExtranjero() {
    	var datos = $("#nombre_fabricante").val().split(' -> ');
        
        if (datos[0] !== "" ){
        	$.post("<?php echo URL ?>DossierPecuario/Solicitud/obtenerDatosProveedorExterior",
               {
        		codigo_aprobacion_solicitud : datos[0]
               }, function (data) {
				if(data.validacion == "Fallo"){
	        		mostrarMensaje(data.resultado,"FALLO");   
	        		fn_limpiarOrigenProductoCompleto();    		
				}else{
					fn_cargarDatosOperadorFabricanteExtranjero(data);
				}
            }, 'json');
        }
    }

  	//Función para mostrar los datos del fabricante cuando es Extranjero
    function fn_cargarDatosOperadorFabricanteExtranjero(data) {
    	$("#id_fabricante_extranjero").val(data.id);
    	$("#nombre_fabricante").val(data.nombre_fabricante);    	
    	$("#id_pais").val(data.id_pais);
    	$("#pais").val(data.pais);
    	$("#direccion_fabricante").val(data.direccion);
    	$("#tipo_producto_fabricante").val(data.tipo_producto);
    } 

  //Función para mostrar los datos del fabricante cuando es Titular del Registro o por Contrato Nacional
    function fn_limpiarOrigenProducto() {
    	$("#identificador_fabricante").val("");
    	$("#nombre_fabricante").val("");
    	$("#direccion_fabricante").val("");
    	$("#id_provincia_fabricante").val("");
    	$("#provincia_fabricante").val("");
    	$("#id_fabricante_extranjero").val("");
    	$("#id_pais").val("");
    	$("#pais").val("");
    	$("#tipo_producto_fabricante").val("");
    	$("#nombre_fabricante").autocomplete({
			disabled: true
		});
    } 

    function fn_limpiarOrigenProductoCompleto() {
    	$("#origen_fabricacion").val("");
    	$("#identificador_fabricante").val("");
    	$("#nombre_fabricante").val("");
    	$("#direccion_fabricante").val("");
    	$("#id_provincia_fabricante").val("");
    	$("#provincia_fabricante").val("");
    	$("#id_fabricante_extranjero").val("");
    	$("#id_pais").val("");
    	$("#pais").val("");
    	$("#tipo_producto_fabricante").val("");

    	$(".TitularNacional").hide();
		$(".TitularNacionalExtranjero").hide();
		$(".Extranjero").hide();
		$("#nombre_fabricante").autocomplete({
			disabled: true
		});
    }

    //Para origen de producto grid
    $("#formularioOrigen").submit(function (event) {
		fn_limpiar();
		event.preventDefault();
		var error = false;

		if (($("#origen_fabricacion option:selected").val() == "TitularRegistro" ) || ($("#origen_fabricacion option:selected").val() == "ContratoNacional") ) {
			
			if(!$.trim($("#identificador_fabricante").val()) || !esCampoValido("#identificador_fabricante") || $("#identificador_fabricante").val().length < 10 || ($("#identificador_fabricante").val().length > 10 && $("#identificador_fabricante").val().length < 13) || $("#identificador_fabricante").val().length > 13){
				error = true;
				$("#identificador_fabricante").addClass("alertaCombo");
			}
			
	        if(!$.trim($("#nombre_fabricante").val())){
	        	error = true;
	        	$("#nombre_fabricante").addClass("alertaCombo");
			}

	        if(!$.trim($("#direccion_fabricante").val())){
	        	error = true;
	        	$("#direccion_fabricante").addClass("alertaCombo");
			}

	        if(!$.trim($("#id_provincia_fabricante").val())){
	        	error = true;
	        	$("#id_provincia_fabricante").addClass("alertaCombo");
			}

	        if(!$.trim($("#provincia_fabricante").val())){
	        	error = true;
	        	$("#provincia_fabricante").addClass("alertaCombo");
			}

	        if(!$.trim($("#id_pais").val())){
	        	error = true;
	        	$("#id_pais").addClass("alertaCombo");
			}

	        if(!$.trim($("#pais").val())){
	        	error = true;
	        	$("#pais").addClass("alertaCombo");
			}
			
        }else if (($("#origen_fabricacion option:selected").val() == "Extranjero")  ) {
        	if(!$.trim($("#nombre_fabricante").val())){
	        	error = true;
	        	$("#nombre_fabricante").addClass("alertaCombo");
			}

	        if(!$.trim($("#direccion_fabricante").val())){
	        	error = true;
	        	$("#direccion_fabricante").addClass("alertaCombo");
			}

	        if(!$.trim($("#id_fabricante_extranjero").val())){
	        	error = true;
	        	$("#id_fabricante_extranjero").addClass("alertaCombo");
			}

	        if(!$.trim($("#id_pais").val())){
	        	error = true;
	        	$("#id_pais").addClass("alertaCombo");
			}

	        if(!$.trim($("#pais").val())){
	        	error = true;
	        	$("#pais").addClass("alertaCombo");
			}

	        if(!$.trim($("#tipo_producto_fabricante").val())){
	        	error = true;
	        	$("#tipo_producto_fabricante").addClass("alertaCombo");
			}
        }
		
		if (!error) {
	        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
	       	if (respuesta.estado == 'exito'){
	       		fn_mostrarDetalleOrigen();
	       		fn_limpiarOrigenProductoCompleto();
	        }else{
	        	fn_limpiarOrigenProductoCompleto();
	        }
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
    //Para cargar el detalle de miembros de la asociación registrados
    function fn_mostrarDetalleOrigen() {
        var idSolicitud = $("#id_solicitud").val();
        
    	$.post("<?php echo URL ?>DossierPecuario/OrigenProducto/construirDetalleOrigen",
    			{
					idSolicitud : idSolicitud,
					fase : bandera
    			}, function (data) {
            $("#tbItemsOrigen tbody").html(data);
        });
    }

  	//Funcion que elimina una fila de la lista 
    function fn_eliminarDetalleOrigen(idDetalleOrigen) { 
        $.post("<?php echo URL ?>DossierPecuario/OrigenProducto/borrar",
        {                
            elementos: idDetalleOrigen
        },
        function (data) {
        	fn_mostrarDetalleOrigen();
        });
	}

    /************************************* PASO 3 ***************************************/
	//Lista de elementos de Clasificación
    function fn_cargarClasificacion() {
        var idGrupo = $("#id_grupo_producto option:selected").val();
        
        if (idGrupo !== "") {
        	$.post("<?php echo URL ?>DossierPecuario/Solicitud/comboSubtipoProductoXGrupo", 
			{
        		idGrupo : <?php echo $this->modeloSolicitud->getIdGrupoProducto();?>,
        		idSubtipo : <?php echo $this->modeloSolicitud->getIdSubtipoProducto();?>
			},
            function (data) {
				$("#id_subtipo_producto").html(data);
            });
        }
    }

    $("#formularioClasificacion").submit(function (event) {
		event.preventDefault();
		var error = false;

		if(!$.trim($("#id_clasificacion").val())){
        	error = true;
        	$("#id_clasificacion").addClass("alertaCombo");
		}
		
		if (!error) {
			var respuesta = JSON.parse(ejecutarJson($(this)).responseText);		
			$("#estado").html("Se han guardado los datos con éxito.").addClass("exito");
	       
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

    $("#id_codigo_complementario").change(function () {
    	if ($("#id_codigo_complementario option:selected").val() !== "") {
    		$("#codigo_complementario").val($("#id_codigo_complementario option:selected").text());
    	}else{
    		$("#codigo_complementario").val("");
    	}
    });

    $("#id_codigo_suplementario").change(function () {
    	if ($("#id_codigo_suplementario option:selected").val() !== "") {
    		$("#codigo_suplementario").val($("#id_codigo_suplementario option:selected").text());
    	}else{
    		$("#codigo_suplementario").val("");
    	}
    });

  //Para origen de producto grid
    $("#formularioPartidaArancelaria").submit(function (event) {
		fn_limpiar();
		event.preventDefault();
		var error = false;
		
		if(!$.trim($("#partida_arancelaria").val()) || !esCampoValido("#partida_arancelaria") || $("#partida_arancelaria").val().length != $("#partida_arancelaria").attr("maxlength")){
        	error = true;
        	$("#partida_arancelaria").addClass("alertaCombo");
		}

        if(!$.trim($("#id_codigo_complementario").val())){
        	error = true;
        	$("#id_codigo_complementario").addClass("alertaCombo");
		}

        if(!$.trim($("#codigo_complementario").val())){
        	error = true;
        	$("#codigo_complementario").addClass("alertaCombo");
		}

        if(!$.trim($("#id_codigo_suplementario").val())){
        	error = true;
        	$("#id_codigo_suplementario").addClass("alertaCombo");
		}

        if(!$.trim($("#codigo_suplementario").val())){
        	error = true;
        	$("#codigo_suplementario").addClass("alertaCombo");
		}

		if (!error) {
	        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
	       	if (respuesta.estado == 'exito'){
	       		fn_mostrarDetallePartidaCodigos();
	       		fn_limpiarPartidaCodigosCompleto();
	        }else{
	        	fn_limpiarPartidaCodigosCompleto();
	        }
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
    //Para cargar el detalle de miembros de la asociación registrados
    function fn_mostrarDetallePartidaCodigos() {
        var idSolicitud = $("#id_solicitud").val();
        
    	$.post("<?php echo URL ?>DossierPecuario/PartidaCodigos/construirDetallePartidaCodigos/",
    	{
			idSolicitud : idSolicitud,
			fase : bandera
		}, function (data) {
            $("#tbItemsPartidaCodigos tbody").html(data);
        });
    }

  	//Funcion que elimina una fila de la lista 
    function fn_eliminarDetallePartidaCodigos(idDetallePartidaCodigos) { 
        $.post("<?php echo URL ?>DossierPecuario/PartidaCodigos/borrar",
        {                
            elementos: idDetallePartidaCodigos
        },
        function (data) {
        	fn_mostrarDetallePartidaCodigos();
        });
	}

    function fn_limpiarPartidaCodigosCompleto() {
    	$("#partida_arancelaria").val("");
    	$("#id_codigo_complementario").val("");
    	$("#codigo_complementario").val("");
    	$("#id_codigo_suplementario").val("");
    	$("#codigo_suplementario").val("");
    }

    $("#id_unidad_presentacion_comercial").change(function () {
    	if ($("#id_unidad_presentacion_comercial option:selected").val() !== "") {
    		if ($("#id_unidad_presentacion_comercial option:selected").val() != "0") {
        		$("#nombre_unidad_presentacion_comercial").val($("#id_unidad_presentacion_comercial option:selected").attr('data-codigo'));
        		$("#nombre_unidad_presentacion_comercial").removeAttr('required');
        		$(".NombreUnidadPresentacion").hide();
        	}else{
        		$("#nombre_unidad_presentacion_comercial").val('');
        		$("#nombre_unidad_presentacion_comercial").attr('required', 'required');
        		$(".NombreUnidadPresentacion").show();
        	}
    	}else{
    		$("#unidad_presentacion_comercial").val("");
    		$(".NombreUnidadPresentacion").hide();
    	}
    });

  //Para origen de producto grid
    $("#formularioPresentacionComercial").submit(function (event) {
		fn_limpiar();
		event.preventDefault();
		var error = false;
		
		if(!$.trim($("#presentacion").val())){
        	error = true;
        	$("#presentacion").addClass("alertaCombo");
		}

        if(!$.trim($("#cantidad").val())){
        	error = true;
        	$("#cantidad").addClass("alertaCombo");
		}

        if(!$.trim($("#id_unidad_presentacion_comercial").val())){
        	error = true;
        	$("#id_unidad_presentacion_comercial").addClass("alertaCombo");
		}

        if($("#id_unidad_presentacion_comercial option:selected").val() == '0'){
            if(!$.trim($("#nombre_unidad_presentacion_comercial").val())){
            	error = true;
            	$("#nombre_unidad_presentacion_comercial").addClass("alertaCombo");
    		}
        }

		if (!error) {
	        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
	       	if (respuesta.estado == 'exito'){
	       		fn_mostrarDetallePresentacionComercial();
	       		fn_limpiarPresentacionComercialCompleto();
	        }else{
	        	fn_limpiarPresentacionComercialCompleto();
	        }
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
    //Para cargar el detalle de miembros de la asociación registrados
    function fn_mostrarDetallePresentacionComercial() {
        var idSolicitud = $("#id_solicitud").val();
        
    	$.post("<?php echo URL ?>DossierPecuario/PresentacionComercial/construirDetallePresentacionComercial/",
    	{
			idSolicitud : idSolicitud,
			fase : bandera,
			grupoProducto : grupoProducto
		}, function (data) {
            $("#tbItemsPresentacionComercial tbody").html(data);
        });
    }

  	//Funcion que elimina una fila de la lista 
    function fn_eliminarDetallePresentacionComercial(idDetallePresentacionComercial) { 
        $.post("<?php echo URL ?>DossierPecuario/PresentacionComercial/borrar",
        {                
            elementos: idDetallePresentacionComercial
        },
        function (data) {
        	fn_mostrarDetallePresentacionComercial();
        });
	}

    function fn_limpiarPresentacionComercialCompleto() {
    	$("#presentacion").val("");
    	$("#cantidad").val("");
    	$("#id_unidad_presentacion_comercial").val("");
    	$("#nombre_unidad_presentacion_comercial").val("");
    }

    /************************************* PASO 4 ***************************************/
    $("#id_unidad_composicion").change(function () {
    	if ($("#id_unidad_composicion option:selected").val() !== "") {
    		if ($("#id_unidad_composicion option:selected").val() != "0") {
        		$("#nombre_unidad_composicion").val($("#id_unidad_composicion option:selected").attr('data-codigo'));
        		$("#nombre_unidad_composicion").removeAttr('required');
        		$(".NombreUnidadComposicion").hide();
        	}else{
        		$("#nombre_unidad_composicion").val('');
        		$("#nombre_unidad_composicion").attr('required', 'required');
        		$(".NombreUnidadComposicion").show();
        	}
    	}else{
    		$("#nombre_unidad_composicion").val("");
    		$(".NombreUnidadComposicion").hide();
    	}
    });

    $("#id_unidad_componente").change(function () {
    	if ($("#id_unidad_componente option:selected").val() !== "") {
    		if ($("#id_unidad_componente option:selected").val() != "0") {
        		$("#nombre_unidad_componente").val($("#id_unidad_componente option:selected").attr('data-codigo'));
        		$("#nombre_unidad_componente").removeAttr('required');
        		$(".NombreUnidadComponente").hide();
        	}else{
        		$("#nombre_unidad_componente").val('');
        		$("#nombre_unidad_componente").attr('required', 'required');
        		$(".NombreUnidadComponente").show();
        	}
    	}else{
    		$("#nombre_unidad_componente").val("");
    		$(".NombreUnidadComponente").hide();
    	}
    });

  //Para origen de producto grid
    $("#formularioComposicion").submit(function (event) {
		fn_limpiar();
		event.preventDefault();
		var error = false;
		
		if(!$.trim($("#cada").val())){
        	error = true;
        	$("#cada").addClass("alertaCombo");
		}

        if(!$.trim($("#id_unidad_composicion").val())){
        	error = true;
        	$("#id_unidad_composicion").addClass("alertaCombo");
		}

        if($("#id_unidad_composicion option:selected").val() == '0'){
            if(!$.trim($("#nombre_unidad_composicion").val())){
            	error = true;
            	$("#nombre_unidad_composicion").addClass("alertaCombo");
    		}
        }

        if(!$.trim($("#id_tipo_componente").val())){
        	error = true;
        	$("#id_tipo_componente").addClass("alertaCombo");
		}

        if(!$.trim($("#id_nombre_componente").val())){
        	error = true;
        	$("#id_nombre_componente").addClass("alertaCombo");
		}

        if(!$.trim($("#cantidad_composicion").val())){
        	error = true;
        	$("#cantidad_composicion").addClass("alertaCombo");
		}

        if(!$.trim($("#id_unidad_componente").val())){
        	error = true;
        	$("#id_unidad_componente").addClass("alertaCombo");
		}

        if($("#id_unidad_componente option:selected").val() == '0'){
            if(!$.trim($("#nombre_unidad_componente").val())){
            	error = true;
            	$("#nombre_unidad_componente").addClass("alertaCombo");
    		}
        }

		if (!error) {
	        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
	       	if (respuesta.estado == 'exito'){
	       		fn_mostrarDetalleComposicion();
	       		fn_limpiarComposicionCompleto();
	        }else{
	        	fn_mostrarDetalleComposicion();
	        }
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
	
    //Para cargar el detalle de miembros de la asociación registrados
    function fn_mostrarDetalleComposicion() {
        var idSolicitud = $("#id_solicitud").val();
        
    	$.post("<?php echo URL ?>DossierPecuario/Composicion/construirDetalleComposicion/",
    	{
			idSolicitud : idSolicitud,
			fase : bandera
		}, function (data) {
            $("#tbItemsComposicion tbody").html(data);
        });
    }

  	//Funcion que elimina una fila de la lista 
    function fn_eliminarDetalleComposicion(idDetalleComposicion) { 
        $.post("<?php echo URL ?>DossierPecuario/Composicion/borrar",
        {                
            elementos: idDetalleComposicion
        },
        function (data) {
        	fn_mostrarDetalleComposicion();
        });
	}

    function fn_limpiarComposicionCompleto() {
    	$("#cada").val("");
    	$("#id_unidad_composicion").val("");
    	$("#nombre_unidad_composicion").val("");
    	$("#id_tipo_componente").val("");
    	$("#id_nombre_componente").val("");
    	$("#cantidad_composicion").val("");
    	$("#id_unidad_componente").val("");
    	$("#nombre_unidad_componente").val("");
    }

    $("#formularioReactivoMaterial").submit(function (event) {
		fn_limpiar();
		event.preventDefault();
		var error = false;
		
		if(!$.trim($("#reactivo_material").val())){
        	error = true;
        	$("#reactivo_material").addClass("alertaCombo");
		}

		if (!error) {
	        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
	       	if (respuesta.estado == 'exito'){
	       		fn_mostrarDetalleReactivoMaterial();
	       		fn_limpiarReactivoMaterialCompleto();
	        }else{
	        	fn_mostrarDetalleReactivoMaterial();
	        }
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
	
    //Para cargar el detalle de reactivos y materiales
    function fn_mostrarDetalleReactivoMaterial() {
        var idSolicitud = $("#id_solicitud").val();
        
    	$.post("<?php echo URL ?>DossierPecuario/ReactivoMaterial/construirDetalleReactivoMaterial/",
    	{
			idSolicitud : idSolicitud,
			fase : bandera
		}, function (data) {
            $("#tbItemsReactivoMaterial tbody").html(data);
        });
    }

  	//Funcion que elimina una fila de la lista 
    function fn_eliminarDetalleReactivoMaterial(idDetalleReactivoMaterial) { 
        $.post("<?php echo URL ?>DossierPecuario/ReactivoMaterial/borrar",
        {                
            elementos: idDetalleReactivoMaterial
        },
        function (data) {
        	fn_mostrarDetalleReactivoMaterial();
        });
	}

    function fn_limpiarReactivoMaterialCompleto() {
    	$("#reactivo_material").val("");
    }
    
    /************************************* PASO 5 ***************************************/
    
    $("#formularioFormaFisFarCosProducto").submit(function (event) {
		fn_limpiar();
		event.preventDefault();
		var error = false;
		
		if(!$.trim($("#id_forma").val())){
        	error = true;
        	$("#id_forma").addClass("alertaCombo");
		}

		if (!error) {
	        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
	       	if (respuesta.estado == 'exito'){
	       		fn_mostrarDetalleFormaFisFarCosProducto();
	       		fn_limpiarFormaFisFarCosProductoCompleto();
	        }else{
	        	fn_mostrarDetalleFormaFisFarCosProducto();
	        }
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
	
    //Para cargar el detalle de miembros de la asociación registrados
    function fn_mostrarDetalleFormaFisFarCosProducto() {
        var idSolicitud = $("#id_solicitud").val();
        
    	$.post("<?php echo URL ?>DossierPecuario/FormaFisFarCosProducto/construirDetalleFormaFisFarCosProducto/",
    	{
			idSolicitud : idSolicitud,
			fase : bandera
		}, function (data) {
            $("#tbItemsFormaFisFarCosProducto tbody").html(data);
        });
    }

  	//Funcion que elimina una fila de la lista 
    function fn_eliminarDetalleFormaFisFarCosProducto(idDetalleFormaFisFarCosProducto) { 
        $.post("<?php echo URL ?>DossierPecuario/FormaFisFarCosProducto/borrar",
        {                
            elementos: idDetalleFormaFisFarCosProducto
        },
        function (data) {
        	fn_mostrarDetalleFormaFisFarCosProducto();
        });
	}

    function fn_limpiarFormaFisFarCosProductoCompleto() {
    	$("#id_forma").val("");
    }
    
    $("#formularioPaso5").submit(function (event) {
		event.preventDefault();
		var error = false;

		//Poner validacion de contenido de texto si hay informacion en los campos
		if($.trim($("#principios_tecnica").val())){
			if(!esCampoValido("#principios_tecnica")){
            	error = true;
            	$("#principios_tecnica").addClass("alertaCombo");
			}
		}

		if($.trim($("#deteccion_antigenos").val())){
			if(!esCampoValido("#deteccion_antigenos")){
            	error = true;
            	$("#deteccion_antigenos").addClass("alertaCombo");
			}
		}

		if($.trim($("#muestras_usadas").val())){
			if(!esCampoValido("#muestras_usadas")){
            	error = true;
            	$("#muestras_usadas").addClass("alertaCombo");
			}
		}

		if($.trim($("#caracteristicas_fis_quim").val())){
			if(!esCampoValido("#caracteristicas_fis_quim")){
            	error = true;
            	$("#caracteristicas_fis_quim").addClass("alertaCombo");
			}
		}

		if($.trim($("#pruebas_fis_quim").val())){
			if(!esCampoValido("#pruebas_fis_quim")){
            	error = true;
            	$("#pruebas_fis_quim").addClass("alertaCombo");
			}
		}

		if($.trim($("#inocuidad_esterilidad").val())){
			if(!esCampoValido("#inocuidad_esterilidad")){
            	error = true;
            	$("#inocuidad_esterilidad").addClass("alertaCombo");
			}
		}

		if($.trim($("#sensibilidad").val())){
			if(!esCampoValido("#sensibilidad")){
            	error = true;
            	$("#sensibilidad").addClass("alertaCombo");
			}
		}

		if($.trim($("#datos_repetibilidad").val())){
			if(!esCampoValido("#datos_repetibilidad")){
            	error = true;
            	$("#datos_repetibilidad").addClass("alertaCombo");
			}
		}

		if($.trim($("#datos_especificidad").val())){
			if(!esCampoValido("#datos_especificidad")){
            	error = true;
            	$("#datos_especificidad").addClass("alertaCombo");
			}
		}

		if($.trim($("#datos_sensibilidad").val())){
			if(!esCampoValido("#datos_sensibilidad")){
            	error = true;
            	$("#datos_sensibilidad").addClass("alertaCombo");
			}
		}
		if (!error) {
			var respuesta = JSON.parse(ejecutarJson($(this)).responseText);		
			$("#estado").html("Se han guardado los datos con éxito.").addClass("exito");
	       
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
    
    /************************************* PASO 6 ***************************************/
    $("#formularioUsoEspecie").submit(function (event) {
		fn_limpiar();
		event.preventDefault();
		var error = false;
		
		if(!$.trim($("#id_uso").val())){
        	error = true;
        	$("#id_uso").addClass("alertaCombo");
		}
		
		if(!$.trim($("#id_especie").val())){
        	error = true;
        	$("#id_especie").addClass("alertaCombo");
		}

		if($.trim($("#nombre_especie_uso").val())){
			if(!esCampoValido("#nombre_especie_uso")){
            	error = true;
            	$("#nombre_especie_uso").addClass("alertaCombo");
			}
		}

		if (!error) {
	        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
	       	if (respuesta.estado == 'exito'){
	       		fn_mostrarDetalleUsoEspecie();
	       		fn_limpiarUsoEspecieCompleto();
	        }else{
	        	fn_mostrarDetalleUsoEspecie();
	        }
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
	
    //Para cargar el detalle de miembros de la asociación registrados
    function fn_mostrarDetalleUsoEspecie() {
        var idSolicitud = $("#id_solicitud").val();
        
    	$.post("<?php echo URL ?>DossierPecuario/UsoEspecie/construirDetalleUsoEspecie/",
    	{
			idSolicitud : idSolicitud,
			fase : bandera
		}, function (data) {
            $("#tbItemsUsoEspecie tbody").html(data);
        });
    }

  	//Funcion que elimina una fila de la lista 
    function fn_eliminarDetalleUsoEspecie(idDetalleUsoEspecie) { 
        $.post("<?php echo URL ?>DossierPecuario/UsoEspecie/borrar",
        {                
            elementos: idDetalleUsoEspecie
        },
        function (data) {
        	fn_mostrarDetalleUsoEspecie();
        });
	}

    function fn_limpiarUsoEspecieCompleto() {
    	$("#id_uso").val("");
    	$("#id_especie").val("");
    	$("#nombre_especie_uso").val("");
    }

    $("#formularioPaso6").submit(function (event) {
		event.preventDefault();
		var error = false;

		//Poner validacion de contenido de texto si hay informacion en los campos
		if($.trim($("#determinacion_anticuerpos").val())){
			if(!esCampoValido("#determinacion_anticuerpos")){
            	error = true;
            	$("#determinacion_anticuerpos").addClass("alertaCombo");
			}
		}

		if($.trim($("#determinacion_microorganismos").val())){
			if(!esCampoValido("#determinacion_microorganismos")){
            	error = true;
            	$("#determinacion_microorganismos").addClass("alertaCombo");
			}
		}

		if($.trim($("#determinacion_estados_fisiologicos").val())){
			if(!esCampoValido("#determinacion_estados_fisiologicos")){
            	error = true;
            	$("#determinacion_estados_fisiologicos").addClass("alertaCombo");
			}
		}

		if($.trim($("#determinacion_datos_clinicos").val())){
			if(!esCampoValido("#determinacion_datos_clinicos")){
            	error = true;
            	$("#determinacion_datos_clinicos").addClass("alertaCombo");
			}
		}

		if($.trim($("#modo_uso").val())){
			if(!esCampoValido("#modo_uso")){
            	error = true;
            	$("#modo_uso").addClass("alertaCombo");
			}
		}

		if($.trim($("#resultado_interpretacion").val())){
			if(!esCampoValido("#resultado_interpretacion")){
            	error = true;
            	$("#resultado_interpretacion").addClass("alertaCombo");
			}
		}

		if (!error) {
			var respuesta = JSON.parse(ejecutarJson($(this)).responseText);		
			$("#estado").html("Se han guardado los datos con éxito.").addClass("exito");
	       
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

    /************************************* PASO 7 ***************************************/  
    $("#id_unidad_tiempo").change(function () {
    	if ($("#id_unidad_tiempo").val() !== "") {
    		$("#nombre_unidad_periodo_vida").val($("#id_unidad_tiempo option:selected").attr('data-codigo'));
    	}else{
    		$("#nombre_unidad_periodo_vida").val("");
    	}
    });
    
    $("#formularioPeriodoVidaUtil").submit(function (event) {
		fn_limpiar();
		event.preventDefault();
		var error = false;
		
		if(!$.trim($("#descripcion_envase").val())){
        	error = true;
        	$("#descripcion_envase").addClass("alertaCombo");
		}
		
		if(!$.trim($("#periodo_vida_util").val())){
        	error = true;
        	$("#periodo_vida_util").addClass("alertaCombo");
		}

		if(!$.trim($("#id_unidad_tiempo").val())){
        	error = true;
        	$("#id_unidad_tiempo").addClass("alertaCombo");
		}

		if (!error) {
	        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
	       	if (respuesta.estado == 'exito'){
	       		fn_mostrarDetallePeriodoVidaUtil();
	       		fn_limpiarPeriodoVidaUtilCompleto();
	        }else{
	        	fn_mostrarDetallePeriodoVidaUtil();
	        }
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
	
    //Para cargar el detalle de Período de vida útil
    function fn_mostrarDetallePeriodoVidaUtil() {
        var idSolicitud = $("#id_solicitud").val();
        
    	$.post("<?php echo URL ?>DossierPecuario/PeriodoVidaUtil/construirDetallePeriodoVidaUtil/",
    	{
			idSolicitud : idSolicitud,
			fase : bandera
		}, function (data) {
            $("#tbItemsPeriodoVidaUtil tbody").html(data);
        });
    }

  	//Funcion que elimina una fila de la lista 
    function fn_eliminarDetallePeriodoVidaUtil(idDetallePeriodoVidaUtil) { 
        $.post("<?php echo URL ?>DossierPecuario/PeriodoVidaUtil/borrar",
        {                
            elementos: idDetallePeriodoVidaUtil
        },
        function (data) {
        	fn_mostrarDetallePeriodoVidaUtil();
        });
	}

    function fn_limpiarPeriodoVidaUtilCompleto() {
    	$("#descripcion_envase").val("");
    	$("#periodo_vida_util").val("");
    	$("#id_unidad_tiempo").val("");
    	$("#nombre_unidad_periodo_vida").val("");
    }

    $("#formularioConservacion").submit(function (event) {
		event.preventDefault();
		var error = false;

		//Poner validacion de contenido de texto si hay informacion en los campos
		if($.trim($("#precauciones_generales").val())){
			if(!esCampoValido("#precauciones_generales")){
            	error = true;
            	$("#precauciones_generales").addClass("alertaCombo");
			}
		}

		if($.trim($("#efectos_colaterales").val())){
			if(!esCampoValido("#efectos_colaterales")){
            	error = true;
            	$("#efectos_colaterales").addClass("alertaCombo");
			}
		}

		if($.trim($("#temperatura_almacenamiento").val())){
			if(!esCampoValido("#temperatura_almacenamiento")){
            	error = true;
            	$("#temperatura_almacenamiento").addClass("alertaCombo");
			}
		}

		if($.trim($("#humedad_almacenamiento").val())){
			if(!esCampoValido("#humedad_almacenamiento")){
            	error = true;
            	$("#humedad_almacenamiento").addClass("alertaCombo");
			}
		}

		if (!error) {
			var respuesta = JSON.parse(ejecutarJson($(this)).responseText);		
			$("#estado").html("Se han guardado los datos con éxito.").addClass("exito");
	       
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

    /************************************* PASO 8 ***************************************/    
    $("#formularioPrecauciones").submit(function (event) {
		event.preventDefault();
		var error = false;

		//Poner validacion de contenido de texto si hay informacion en los campos
		if(!$.trim($("#id_declaracion_venta").val())){
        	error = true;
        	$("#id_declaracion_venta").addClass("alertaCombo");
		}

		if($.trim($("#observaciones_producto").val())){
			if(!esCampoValido("#observaciones_producto")){
            	error = true;
            	$("#observaciones_producto").addClass("alertaCombo");
			}
		}

		if (!error) {
			var respuesta = JSON.parse(ejecutarJson($(this)).responseText);		
			$("#estado").html("Se han guardado los datos con éxito.").addClass("exito");
	       
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

    /************************************* PASO 9 ***************************************/  

    $('button.subirArchivoAnexo').click(function (event) {
    	var nombre_archivo = "anexo_" + Math.floor(Math.random() * 10000000000);
        var boton = $(this);
        var archivo = boton.parent().find(".archivoAnexo");
        var rutaArchivo = boton.parent().find(".rutaArchivoAnexo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCargaAnexo");

        if (extension[extension.length - 1].toUpperCase() == 'PDF' || extension[extension.length - 1].toUpperCase() == 'PDF') {

            subirArchivo(
                archivo
                , nombre_archivo
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new carga(estado, archivo, boton)
            );

            $('#ruta_documento_anexo').val("<?php echo DOSS_PEC_URL.$this->modeloSolicitud->getIdExpediente(); ?>/"+nombre_archivo+".PDF");
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("");
        }
    });

    $("#formularioDocumentoAnexo").submit(function (event) {
		fn_limpiar();
		event.preventDefault();
		var error = false;
		
		if(!$.trim($("#id_tipo_documento").val())){
        	error = true;
        	$("#id_tipo_documento").addClass("alertaCombo");
		}
		
		if(!$.trim($("#ruta_documento_anexo").val())){
        	error = true;
        	$("#archivoAnexo").addClass("alertaCombo");
		}

		if (!error) {
	        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
	       	if (respuesta.estado == 'exito'){
	       		fn_mostrarDetalleDocumentoAnexo();
	       		fn_limpiarDocumentoAnexoCompleto();
	        }else{
	        	fn_mostrarDetalleDocumentoAnexo();
	        }
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
	
    //Para cargar el detalle de Documento anexo
    function fn_mostrarDetalleDocumentoAnexo() {
        var idSolicitud = $("#id_solicitud").val();

        $.post("<?php echo URL ?>DossierPecuario/DocumentoAnexo/construirDetalleDocumentoAnexo", 
    			{
        			idSolicitud : idSolicitud,
            		tipo : 'anexo',
            		fase : bandera
    			},
                function (data) {
    				$("#tbItemsDocumentoAnexo tbody").html(data);
                });
    }

  	//Funcion que elimina una fila de la lista 
    function fn_eliminarDetalleDocumentoAnexo(idDetalleDocumentoAnexo) { 
        $.post("<?php echo URL ?>DossierPecuario/DocumentoAnexo/borrar",
        {                
            elementos: idDetalleDocumentoAnexo
        },
        function (data) {
        	fn_mostrarDetalleDocumentoAnexo();
        });
	}

    function fn_limpiarDocumentoAnexoCompleto() {
    	$("#id_tipo_documento").val("");
    	$("#descripcion_documento_anexo").val("");
    	$("#ruta_documento_anexo").val("");
    	$("#archivoAnexo").val("");
    	$("#ruta_documento_anexo").val("");
    	$(".subirArchivoAnexo").removeAttr("disabled");  
    	$("#archivoAnexo").removeClass("verde");
    }

    $("#formularioDocumentoExterno").submit(function (event) {
		fn_limpiar();
		event.preventDefault();
		var error = false;
		
		if(!$.trim($("#descripcion_documento_externo").val())){
        	error = true;
        	$("#descripcion_documento_externo").addClass("alertaCombo");
		}

		if(!$.trim($("#ruta_documento_externo").val())){
        	error = true;
        	$("#ruta_documento_externo").addClass("alertaCombo");
		}

		if (!error) {
	        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
	       	if (respuesta.estado == 'exito'){
	       		fn_mostrarDetalleDocumentoExterno();
	       		fn_limpiarDocumentoExternoCompleto();
	        }else{
	        	fn_mostrarDetalleDocumentoExterno();
	        }
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
	
    //Para cargar el detalle de Documento externo
    function fn_mostrarDetalleDocumentoExterno() {
        var idSolicitud = $("#id_solicitud").val();

        $.post("<?php echo URL ?>DossierPecuario/DocumentoAnexo/construirDetalleDocumentoAnexo", 
    			{
        			idSolicitud : idSolicitud,
            		tipo : 'externo',
            		fase : bandera
    			},
                function (data) {
    				$("#tbItemsDocumentoExterno tbody").html(data);
                });
    }

  	//Funcion que elimina una fila de la lista 
    function fn_eliminarDetalleDocumentoExterno(idDetalleDocumentoAnexo) { 
        $.post("<?php echo URL ?>DossierPecuario/DocumentoAnexo/borrar",
        {                
            elementos: idDetalleDocumentoAnexo
        },
        function (data) {
        	fn_mostrarDetalleDocumentoExterno();
        });
	}

    function fn_limpiarDocumentoExternoCompleto() {
    	$("#descripcion_documento_externo").val("");
    	$("#ruta_documento_externo").val("");
    }

    /************************************* PASO 10 ***************************************/
    $('button.subirArchivoCambios').click(function (event) {
        var idExpediente = <?php echo json_encode($this->modeloSolicitud->getIdExpediente());?>;
    	var nombre_archivo = "Cambios_"+idExpediente;
        var boton = $(this);
        var archivo = boton.parent().find(".archivoCambios");
        var rutaArchivo = boton.parent().find(".rutaArchivoCambios");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCargaCambios");

        if (extension[extension.length - 1].toUpperCase() == 'PDF' || extension[extension.length - 1].toUpperCase() == 'PDF') {

            subirArchivo(
                archivo
                , nombre_archivo
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new carga(estado, archivo, boton)
            );

            $('#ruta_cambios_usuario').val("<?php echo DOSS_PEC_URL.$this->modeloSolicitud->getIdExpediente(); ?>/"+nombre_archivo+".PDF");
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("");
        }
    });

    $("#formularioCondiciones").submit(function (event) {
		event.preventDefault();
		var error = false;

		if($("#condiciones option:selected").val() != 'Si'){
        	error = true;
        	$("#condiciones").addClass("alertaCombo");
        	alert("Debe aceptar las condiciones para poder continuar.");
		}

		if($("#estado_solicitud").val() == 'Subsanacion'){
			if(!$.trim($("#observaciones_cambios").val())){
	        	error = true;
	        	$("#observaciones_cambios").addClass("alertaCombo");
			}

    		/*if(!$.trim($("#ruta_cambios_usuario").val())){
            	error = true;
            	$("#archivoCambios").addClass("alertaCombo");
    		}*/
		}
		
		if (!error) {//hacer q se bloquee el boton de guardar y enviar hasta q termine el proceso, si hay error activar de nuevo
			$("#botonEnviar").attr("disabled", "disabled");
			
	        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);

	       	if (respuesta.estado == 'exito'){
	       		$("#estado").html("Se han guardado los datos con éxito.").addClass("exito");
	        }else{
	        	$("#botonEnviar").removeAttr("disabled");
	        	$("#estado").html(respuesta.mensaje).addClass("alerta");
	        }
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

    /************************************* PASO 12 ***************************************/
    $("#estado_solicitud_tecnico").change(function () {
    	if ($("#estado_solicitud_tecnico option:selected").val() == "Subsanacion") {
    		$(".archivoSubsanacion").show();
    	}else{
    		$(".archivoSubsanacion").hide();
    	}
    });

    $('button.subirArchivoSubsanacion').click(function (event) {
        var idExpediente = <?php echo json_encode($this->modeloSolicitud->getIdExpediente());?>;
    	var nombre_archivo = "Subsanacion_"+idExpediente;
        var boton = $(this);
        var archivo = boton.parent().find(".archivoSubsanacion");
        var rutaArchivo = boton.parent().find(".rutaArchivoSubsanacion");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCargaSubsanacion");

        if (extension[extension.length - 1].toUpperCase() == 'PDF' || extension[extension.length - 1].toUpperCase() == 'PDF') {

            subirArchivo(
                archivo
                , nombre_archivo
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new carga(estado, archivo, boton)
            );

            $('#ruta_documento_subsanacion').val("<?php echo DOSS_PEC_URL.$this->modeloSolicitud->getIdExpediente(); ?>/"+nombre_archivo+".PDF");
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("");
        }
    });
    
    $("#formularioRevision").submit(function (event) {
		event.preventDefault();
		var error = false;

		if(!$.trim($("#estado_solicitud_tecnico").val())){
        	error = true;
        	$("#estado_solicitud_tecnico").addClass("alertaCombo");
		}else{
			if($("#estado_solicitud_tecnico").val() == 'Subsanacion'){
	    		/*if(!$.trim($("#ruta_documento_subsanacion").val())){
	            	error = true;
	            	$("#archivoSubsanacion").addClass("alertaCombo");
	    		}*/
			}
		}

		/*if($.trim($("#observacion_revision").val())){
			//validacion de contenido
        	error = true;
        	$("#observacion_revision").addClass("alertaCombo");
		}	*/	
		
		if (!error) {//hacer q se creen todos los documentos en aprobacion
			$("#cargarMensajeTemporal").html("<div id='cargando' style='position :fixed'>Cargando...</div>").fadeIn();

			setTimeout(function(){ 				
	        	var respuesta = JSON.parse(ejecutarJson($("#formularioRevision")).responseText);
    	       	if (respuesta.estado == 'exito'){
    	       		mostrarMensaje(data.mensaje,"EXITO");
    	        }else{
    	        	mostrarMensaje(data.mensaje,"FALLO");
    	        }
			}, 1000);
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
    
    
    
    
	function fn_limpiar() {
		$(".alertaCombo").removeClass("alertaCombo");
		$('#estado').html('');
	}
</script>