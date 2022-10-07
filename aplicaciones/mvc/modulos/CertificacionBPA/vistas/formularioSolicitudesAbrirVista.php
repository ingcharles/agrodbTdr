<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

	<input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $this->modeloSolicitudes->getIdSolicitud(); ?>" />
	<input type="hidden" id="estado" name="estado" value="<?php echo $this->modeloSolicitudes->getEstado(); ?>" />
			
	<fieldset>
		<legend>Datos Generales</legend>				

		<div data-linea="1">
			<label for="tipo_solicitud">Tipo de Solicitud: </label>
				<?php echo $this->modeloSolicitudes->getTipoSolicitud();?>
		</div>				

		<div data-linea="1">
			<label for="tipo_explotacion">Tipo de Explotación: </label>
				<?php echo ($this->modeloSolicitudes->getTipoExplotacion()=="SV"?"Sanidad Vegetal":($this->modeloSolicitudes->getTipoExplotacion()=="SA"?"Sanidad Animal":"Inocuidad de Alimentos"));?>
		</div>

		<div data-linea="2" class="num_animales">
			<label for="num_animales">Nº Animales: </label>
				<?php echo $this->modeloSolicitudes->getNumAnimales(); ?>
		</div>			

		<div data-linea="3" class="num_animales">
			<p class="nota">El campo Nº de animales solo aplica para porcinos, vacas, aves y cuyes</p>
		</div>
    	
	</fieldset >	
	
	<fieldset>
		<legend>Datos del Operador</legend>
		
		<div data-linea="5">
			<label for="identificador_operador">Identificador: </label>
				<?php echo $this->modeloSolicitudes->getIdentificadorOperador(); ?>
		</div>				

		<div data-linea="6">
			<label for="razon_social">Nombre/Razón Social: </label>
				<?php echo $this->modeloSolicitudes->getRazonSocial(); ?>
		</div>				

		<div data-linea="7">
			<label for="identificador_representante_legal">Identificación Representante: </label>
				<?php echo $this->modeloSolicitudes->getIdentificadorRepresentanteLegal(); ?>
		</div>				

		<div data-linea="7">
			<label for="nombre_representante_legal">Representante Legal: </label>
				<?php echo $this->modeloSolicitudes->getNombreRepresentanteLegal(); ?>
		</div>				

		<div data-linea="8">
			<label for="correo">E-mail: </label>
				<?php echo $this->modeloSolicitudes->getCorreo(); ?>
		</div>				

		<div data-linea="8">
			<label for="telefono">Teléfono: </label>
				<?php echo $this->modeloSolicitudes->getTelefono(); ?>
		</div>				

		<div data-linea="9">
			<label for="direccion">Dirección: </label>
				<?php echo $this->modeloSolicitudes->getDireccion(); ?>
		</div>		
		
		<div data-linea="10">
			<label for="provincia">Provincia: </label>
				<?php echo $this->modeloSolicitudes->getProvinciaUnidadProduccion(); ?>
		</div>		
		
		<div data-linea="11">
			<label for="canton">Cantón: </label>
				<?php echo $this->modeloSolicitudes->getCantonUnidadProduccion(); ?>
		</div>		
		
		<div data-linea="12">
			<label for="parroquia">Parroquia: </label>
				<?php echo $this->modeloSolicitudes->getParroquiaUnidadProduccion(); ?>
		</div>				

	</fieldset>
	
	<fieldset>
		<legend>Datos del Responsable Técnico de la Unidad de Producción Agrícola y/o Pecuaria</legend>
		
		<div data-linea="10">
			<label for="identificador_representante_tecnico">Identificación: </label>
				<?php echo $this->modeloSolicitudes->getIdentificadorRepresentanteTecnico(); ?>
		</div>				

		<div data-linea="10">
			<label for="nombre_representante_tecnico">Nombres: </label>
				<?php echo $this->modeloSolicitudes->getNombreRepresentanteTecnico(); ?>
		</div>				

		<div data-linea="11">
			<label for="correo_representante_tecnico">E-mail: </label>
				<?php echo $this->modeloSolicitudes->getCorreoRepresentanteTecnico(); ?>
		</div>				

		<div data-linea="11">
			<label for="telefono_representante_tecnico">Teléfono: </label>
				<?php echo $this->modeloSolicitudes->getTelefonoRepresentanteTecnico(); ?>
		</div>				

	</fieldset>
	
	<fieldset>
		<legend>Datos de la Unidad de Producción</legend>
		
		<div data-linea="12">
			<label for="id_sitio_unidad_produccion">Nombre del Sitio: </label>
				<?php echo $this->modeloSolicitudes->getSitioUnidadProduccion(); ?>
		</div>				

		<div data-linea="13">
			<label for="provincia_unidad_produccion">Provincia: </label>
				<?php echo $this->modeloSolicitudes->getProvinciaUnidadProduccion(); ?>
		</div>				

		<div data-linea="13">
			<label for="canton_unidad_produccion">Cantón: </label>
				<?php echo $this->modeloSolicitudes->getCantonUnidadProduccion(); ?>
		</div>				

		<div data-linea="14">
			<label for="parroquia_unidad_produccion">Parroquia: </label>
				<?php echo $this->modeloSolicitudes->getParroquiaUnidadProduccion(); ?>
		</div>				

		<div data-linea="15">
			<label for="direccion_unidad_produccion">Dirección: </label>
				<?php echo $this->modeloSolicitudes->getDireccionUnidadProduccion(); ?>
		</div>				

		<hr />
		
		<div data-linea="16">
			<b>Coordenadas </b>
		</div>
		
		<div data-linea="17">
			<label for="utm_x">UTM (X): </label>
				<?php echo $this->modeloSolicitudes->getUtmX(); ?>
		</div>				

		<div data-linea="17">
			<label for="utm_y">UTM (Y): </label>
				<?php echo $this->modeloSolicitudes->getUtmY(); ?>
		</div>				

		<div data-linea="17">
			<label for="altitud">Altitud: </label>
				<?php echo $this->modeloSolicitudes->getAltitud(); ?>
		</div>				

	</fieldset>
	
	<fieldset>
		<legend>Sitios y Áreas Agregados</legend>
		<div data-linea="4">
			<table id="tbSitiosAreasProductos" style="width:100%">
				<thead>
					<tr>
						<th style="width: 10%;">Nº</th>
						<th style="width: 15%;">Sitio</th>
                        <th style="width: 15%;">Área</th>
                        <th style="width: 15%;">Producto</th>
                        <th style="width: 15%;">Operación</th>
                        <th style="width: 15%;">Hectáreas</th>
                        <th style="width: 15%;">Estado</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
		
	</fieldset>		

	
	
	<fieldset>
		<legend>Alcance</legend>
		
		<div data-linea="18">
			<label for="tipo_certificado">Tipo de Certificado: </label>
				<?php echo $this->modeloSolicitudes->getTipoCertificado(); ?>
		</div>				

		<div data-linea="18">
			<label for="num_trabajadores">Nº de Trabajadores: </label>
				<?php echo $this->modeloSolicitudes->getNumTrabajadores(); ?>
		</div>				

		<div data-linea="19" class="equivalente">
			<label for="codigo_equivalente">Código Equivalente: </label>
				<?php echo $this->modeloSolicitudes->getCodigoEquivalente(); ?>
		</div>				

		<div data-linea="20" class="equivalente">
			<label for="fecha_inicio_equivalente">Fecha de Inicio: </label>
				<?php echo $this->modeloSolicitudes->getFechaInicioEquivalente(); ?>
		</div>				

		<div data-linea="20" class="equivalente">
			<label for="fecha_fin_equivalente">Fecha de Fin: </label>
				<?php echo $this->modeloSolicitudes->getFechaFinEquivalente(); ?>
		</div>				

		<div data-linea="21">
			<label for="observacion_alcance">Observación: </label>
				<?php echo $this->modeloSolicitudes->getObservacionAlcance(); ?>
		</div>				

		<div data-linea="22" class="equivalente">
			<label for="ruta">Certificado Equivalente: </label> 
				<?php echo ($this->modeloSolicitudes->getRutaCertificadoEquivalente()==''? '<span class="alerta">No ha cargado ningún certificado</span>':'<a href="'.$this->modeloSolicitudes->getRutaCertificadoEquivalente().'" target="_blank" class="archivo_cargado" id="archivo_cargado">Click aquí para ver el archivo</a>')?>
    	</div>
		
		<hr />
		
		<div data-linea="23">
			<b>Descripción de la población / producto </b>
		</div>
		
		<div data-linea="24">
			<label for="num_hectareas">Nº Hectáreas a certificar: </label>
				<?php echo $this->modeloSolicitudes->getNumHectareas(); ?>
		</div>
		
		<div data-linea="25" class="nacional">
			<label for="ruta">Documentos de Apoyo: </label> 
				<?php echo ($this->modeloSolicitudes->getAnexoNacional()==''? '<span class="alerta">No ha cargado ningún anexo</span>':'<a href="'.$this->modeloSolicitudes->getAnexoNacional().'" target="_blank" class="archivo_cargado" id="archivo_cargado">Click aquí para ver el archivo</a>')?>
    	</div>				
	
	</fieldset>
	
	<fieldset>
		<legend>Tipo de Auditoría Solicitada</legend>
		<div data-linea="26" id="contenedorAuditoria">
			<table id="tbAuditorias" style="width:100%">
				<thead>
					<tr>
						<th style="width: 10%;">Nº</th>
						<th style="width: 25%;">Tipo de Auditoría</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</fieldset>
	
	<fieldset class="subsanacion">
		<legend>Plan de Acción</legend>
		
		<div data-linea="27">
			<label for="ruta_plan_accion">Plan de Acción remitido: </label>
				<?php echo ($this->modeloSolicitudes->getRutaPlanAccion()==''? '<span class="alerta">No ha cargado ningún documento</span>':'<a href="'.$this->modeloSolicitudes->getRutaPlanAccion().'" target="_blank" class="archivo_cargado" id="archivo_cargado">Click aquí para ver el plan de acción remitido</a>')?>
		</div>
	</fieldset>
	
	<fieldset class="aprobacion">
		<legend>Certificado BPA</legend>
		
		<div data-linea="28">
			<label for="ruta_certificado">Certificado BPA: </label>
				<?php echo ($this->modeloSolicitudes->getRutaCertificado()==''? '<span class="alerta">No ha generado ningún documento</span>':'<a href="'.$this->modeloSolicitudes->getRutaCertificado().'" target="_blank" class="archivo_cargado" id="archivo_cargado">Click aquí para ver el certificado emitido</a>')?>
		</div>
	</fieldset>

<script type ="text/javascript">
var tipoSolicitud = <?php echo json_encode($this->modeloSolicitudes->getTipoSolicitud()); ?>;
var tipoExplotacion = <?php echo json_encode($this->modeloSolicitudes->getTipoExplotacion()); ?>;
var formatoPlanAccion = <?php echo json_encode($this->modeloSolicitudes->getRutaFormatoPlanAccion()); ?>;
var planAccion = <?php echo json_encode($this->modeloSolicitudes->getRutaPlanAccion()); ?>;
var estado = <?php echo json_encode($this->modeloSolicitudes->getEstado()); ?>;

	$(document).ready(function() {
		construirValidador();
		distribuirLineas();

		$(".num_animales").hide();
		$(".subsanacion").hide();
		$(".equivalente").hide();
		$(".nacional").hide();
		$(".aprobacion").hide();

		if(tipoSolicitud === 'Equivalente'){
			$(".equivalente").show();
		}

		if(tipoSolicitud === 'Nacional'){
			$(".nacional").show();
		}

		if(tipoExplotacion === 'SA'){
			$(".num_animales").show();
		}

		if(formatoPlanAccion != '' || planAccion != ''){
			$(".subsanacion").show();
		}

		if(estado === 'Aprobado'){
			$(".aprobacion").show();
		}

		fn_mostrarDetalleSitioAreaProducto();
		fn_mostrarDetalleAuditoriaSolicitada();
	 });

	//Para cargar el detalle de sitios/áreas/productos registrados
    function fn_mostrarDetalleSitioAreaProducto() {
        var idSolicitud = $("#id_solicitud").val();
        
    	$.post("<?php echo URL ?>CertificacionBPA/SitiosAreasProductos/construirDetalleSitioAreaProductoVisualizacion/" + idSolicitud, function (data) {
            $("#tbSitiosAreasProductos tbody").html(data);
        });
    }

  	//Para cargar el detalle de auditorías solicitadas
    function fn_mostrarDetalleAuditoriaSolicitada() {
        var idSolicitud = $("#id_solicitud").val();
        
    	$.post("<?php echo URL ?>CertificacionBPA/AuditoriasSolicitadas/construirDetalleAuditoriaVisualizacion/" + idSolicitud, function (data) {
            $("#tbAuditorias tbody").html(data);
        });
    }
</script>