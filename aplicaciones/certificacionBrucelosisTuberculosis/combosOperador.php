<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorBrucelosisTuberculosis.php';
require_once '../../clases/ControladorUsuarios.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cbt = new ControladorBrucelosisTuberculosis();

$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');
$identificadorOperador = htmlspecialchars ($_POST['identificadorOperador'],ENT_NOQUOTES,'UTF-8');
$idSitio = htmlspecialchars ($_POST['predio'],ENT_NOQUOTES,'UTF-8');

switch ($opcion) {
		
	case 'buscarOperador':	
		
		$registroOperador = $cbt->buscarOperadorBovinos($conexion, $identificadorOperador);
		
		if((pg_num_rows($registroOperador) != 0)){
			
			$operador = pg_fetch_assoc($registroOperador);
		
			echo '
					<input type="hidden" id="identificador" name="identificador" value="'.$operador['identificador'].'" />
					
					<fieldset>
							<legend>Información de Localización del Predio</legend>
					
							<div data-linea="1">
								<label>Fecha:</label>
								<input type="text" id="fecha" name="fecha" />
							</div>
							
							<div data-linea="2">
								<label>Nombre del Encuestado:</label>
								<input type="text" id="nombreEncuestado" name="nombreEncuestado" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
							</div>
							
							<div data-linea="2">
								<label >Nombre del Predio: </label>
									<select id="predio" name="predio" required="required">
										<option value="">Predio....</option>';
										$buscarSitios = $cbt->buscarSitiosOperador($conexion, $identificadorOperador);
							
										while ($fila = pg_fetch_assoc($buscarSitios)){
											echo '<option value="'. $fila['id_sitio'].'" >'.$fila['nombre_lugar'].'</option>';
										}
							echo '		</select>
									<input type="hidden" id="nombrePredio" name="nombrePredio"/>
							</div>
							
							<div data-linea="3">
								<label>Num. Cert. Fiebre Aftosa:</label>
								<input type="text" id="numCertFiebreAftosa" name="numCertFiebreAftosa" maxlength="32" data-er="^[0-9-]+$" data-inputmask="\'mask\': \'9999-999-9999999\'"/>
							</div>
							
							<div data-linea="3">
								<label>Certificación:</label>
										<select id="certificacion" name="certificacion" required="required" >
											<option value="">Certificación....</option>
											<option value="Brucelosis">Brucelosis</option>
											<option value="Tuberculosis">Tuberculosis</option>
										</select> 	
							</div>
							
						</fieldset>
						
						<fieldset>
							<legend>Información del Propietario</legend>
							
							<div data-linea="4">
								<label>Nombre:</label>
								<input type="text" id="nombrePropietario" name="nombrePropietario" value="'.$operador['nombre_representante'].' '.$operador['apellido_representante'].'" readonly="readonly"/>
							</div>
							
							<div data-linea="4">
								<label>Cédula:</label>
								<input type="text" id="cedulaPropietario" name="cedulaPropietario" value="'.$operador['identificador'].'" readonly="readonly"/>
							</div>
							
							<div data-linea="5">
								<label>Teléfono:</label>
								<input type="text" id="telefonoPropietario" name="telefonoPropietario" data-inputmask="mask: (99) 999-9999" value="'.$operador['telefono_uno'].'" readonly="readonly"/>
							</div>
							
							
							<div data-linea="5">
								<label>Celular:</label>
								<input type="text" id="celularPropietario" name="celularPropietario" data-inputmask="mask: (99) 9999-9999" value="'.$operador['celular_uno'].'" readonly="readonly"/>
							</div>
							
							<div data-linea="6">
								<label>Correo Electrónico:</label>
								<input type="text" id="correoElectronicoPropietario" name="correoElectronicoPropietario" value="'.$operador['correo'].'" readonly="readonly" />
							</div>
							
						</fieldset>
			';			
						
		}
		
	break;	
	
	case 'buscarPredio':
            
                $cu = new ControladorUsuarios();
		
		$sitioOperador = $cbt->buscarSitioOperador($conexion, $identificadorOperador, $idSitio);
		$sitio = pg_fetch_assoc($sitioOperador);
		
		$provinciaSitio = $cc->obtenerIdLocalizacion($conexion, $sitio['provincia'], 'PROVINCIAS');
		$provincia = pg_fetch_assoc($provinciaSitio);
		
		$cantonSitio = $cc->obtenerIdLocalizacion($conexion, $sitio['canton'], 'CANTONES');
		$canton = pg_fetch_assoc($cantonSitio);
		
		$parroquiaSitio = $cc->obtenerIdLocalizacion($conexion, $sitio['parroquia'], 'PARROQUIAS');
		$parroquia = pg_fetch_assoc($parroquiaSitio);	
                
                $identificador=$_SESSION['usuario'];
                $perfilAdmin = pg_fetch_result($cu->buscarPerfilUsuario($conexion, $identificador, 'Administrador Certificación Brucelosis y Tuberculosis'),0,'id_perfil');
                
                $provinciaDelUsuario= $_SESSION['nombreProvincia'];
		
		echo '		<fieldset>
							<legend>Ubicación y Datos Generales</legend>
			
							<div data-linea="7" id="resultadoUbicacion" >
								<label>Provincia</label>
									<select id="provincia" name="provincia" readonly="readonly">
										<option value="'.$provincia['id_localizacion'].'">'.$provincia['nombre'].'</option>
									</select>
					
									<input type="hidden" id="nombreProvincia" name="nombreProvincia" value="'.$provincia['nombre'].'"/>
					
								</div>
		
							<div data-linea="7" id="resultadoCanton" >
							<label>Cantón</label>
								<select id="canton" name="canton" readonly="readonly">
									<option value="'.$canton['id_localizacion'].'">'.$canton['nombre'].'</option>
								</select>
		
								<input type="hidden" id="nombreCanton" name="nombreCanton" value="'.$canton['nombre'].'"/>
							</div>
				
							<div data-linea="8" id="resultadoParroquia" >
							<label>Parroquia</label>
								<select id="parroquia" name="parroquia" readonly="readonly">
									<option value="'.$parroquia['id_localizacion'].'">'.$parroquia['nombre'].'</option>
								</select>
		
								<input type="hidden" id="nombreParroquia" name="nombreParroquia" value="'.$parroquia['nombre'].'"/>
								<input type="hidden" id="codigoParroquia" name="codigoParroquia" value="'.$parroquia['codigo'].'"/>
							</div>
				
						</fieldset>';
                
		
                $otrosDatos = 
						'<fieldset>
							<legend>Coordenadas</legend>
				
			
							<div data-linea="9">
								<label>X:</label>
								<input type="text" id="x" name="x" maxlength="6" data-er="^[0-9]+$"  />
							</div>
				
							<div data-linea="9">
								<label>Y:</label>
								<input type="text" id="y" name="y" maxlength="7" data-er="^[0-9]+$"  />
							</div>
				
							<div data-linea="9">
								<label>Z:</label>
								<input type="text" id="z" name="z" maxlength="4" data-er="^[0-9]+$"  />
							</div>
				
							<div data-linea="9">
								<label>Huso/Zona:</label>
								<select id="huso" name="huso">
									<option value="">Seleccione....</option>
									<option value="17M">17M</option>
									<option value="17N">17N</option>
									<option value="18M">18M</option>
									<option value="18N">18N</option>
								</select>
								
							</div>

			
						</fieldset>
										
						<fieldset id="adjuntos">
							<legend>Mapa de Ubicación</legend>
					
							<div data-linea="11">
								<input type="file" class="archivo" name="informe" accept="application/pdf" /> 
								
								<input type="hidden" class="rutaArchivo" name="archivo" value="" />
								
								<div class="estadoCarga">
									En espera de archivo... (Tamaño máximo; <?php echo ini_get("upload_max_filesize");?>B)
								</div>
								
								<button type="button" class="subirArchivo" data-rutaCarga="aplicaciones/certificacionBrucelosisTuberculosis/mapa/certificacionBT">Subir mapa</button>
							</div>
						</fieldset>
						
						<fieldset id="adjuntosInforme">
							<legend>Documentos Habilitantes (Solicitud, carta de compromiso, CUV, formulario de visita)</legend>
					
							<div data-linea="12">
								<input type="file" class="archivo" name="informe" accept="application/pdf" /> 
								
								<input type="hidden" class="rutaArchivo" name="archivoInforme" value="" />
								
								<div class="estadoCarga">
									En espera de archivo... (Tamaño máximo; <?php echo ini_get("upload_max_filesize");?>B)
								</div>
								
								<button type="button" class="subirArchivoInforme" data-rutaCarga="aplicaciones/certificacionBrucelosisTuberculosis/informe/certificacionBT">Subir informe</button>
							</div>
						</fieldset>';
                
                if($perfilAdmin==''){
                    if($sitio['provincia'] == $provinciaDelUsuario){
                        echo $otrosDatos;
                        echo '<button type="submit" class="guardar">Guardar</button>';
                    }
                }else{
                    echo $otrosDatos;
                    echo '<button type="submit" class="guardar">Guardar</button>';
                }
                
	break;
		
	default:
		echo 'Tipo desconocido';
		
		break;
}

?>
<script type="text/javascript">
	$(document).ready(function(event){		
		distribuirLineas();	
		construirValidador();
	});

	$("#fecha").datepicker({
	      changeMonth: true,
	      changeYear: true
	});

	$("#predio").change(function(event){
		$('#nombrePredio').val($("#predio option:selected").text());

		$('#nuevaCertificacionBT').attr('data-destino','localizacion');
		$('#nuevaCertificacionBT').attr('data-opcion','combosOperador');
	    $('#opcion').val('buscarPredio');
	    		
		abrir($("#nuevaCertificacionBT"),event,false); 
	});

	//Archivo Mapa
	$('button.subirArchivo').click(function (event) {
	
	    var boton = $(this);
	    var archivo = boton.parent().find(".archivo");
	    var rutaArchivo = boton.parent().find(".rutaArchivo");
	    var extension = archivo.val().split('.');
	    var estado = boton.parent().find(".estadoCarga");
	    numero = Math.floor(Math.random()*100000000);
	    
	    if (extension[extension.length - 1].toUpperCase() == 'PDF') {
	        subirArchivo(archivo, $("#identificador").val() +"_"+numero, boton.attr("data-rutaCarga"), rutaArchivo, new carga(estado, archivo, boton)); 
	    } else {
	        estado.html('Formato incorrecto, sólo se admite archivos en formato PDF');
	        archivo.val("0");
	    }
	});

	//Archivo informe
	$('button.subirArchivoInforme').click(function (event) {
	
		var boton = $(this);
	    var archivo = boton.parent().find(".archivo");
	    var rutaArchivo = boton.parent().find(".rutaArchivo");
	    var extension = archivo.val().split('.');
	    var estado = boton.parent().find(".estadoCarga");
	    numero = Math.floor(Math.random()*100000000);
	    
	    if (extension[extension.length - 1].toUpperCase() == 'PDF') {
	        subirArchivo(archivo, $("#identificador").val() +"_"+numero, boton.attr("data-rutaCarga"), rutaArchivo, new carga(estado, archivo, boton)); 
	    } else {
	        estado.html('Formato incorrecto, sólo se admite archivos en formato PDF');
	        archivo.val("0");
	    }        
	});
</script>