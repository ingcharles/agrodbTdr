<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorVacunacionAnimal.php';

// ==> Vacunación para autoservicio
$conexion = new Conexion();
$cc = new ControladorCatalogos();
$ppc = new ControladorVacunacionAnimal();

$identificadorUsuario=$_SESSION['usuario'];

$filaTipoUsuario=pg_fetch_assoc($ppc->obtenerTipoUsuario($conexion, $identificadorUsuario));

switch($filaTipoUsuario['codificacion_perfil']) {
	case 'PFL_USUAR_EXT':
		$filaDigitador= pg_fetch_assoc($ppc->buscarEmpresaDigitador($conexion, $identificadorUsuario));
		break;

}

$lotes = $cc->listaLotes($conexion);
$laboratorios = $cc->listaLaboratorios($conexion,'6');

$validaCertificado = $ppc->validarCertificadosVacunacion($conexion);
?>
<header>
	<h1>Vacunación</h1>
</header>
<form id='nuevoVacunacionAnimal' data-rutaAplicacion='vacunacionAnimal'
	data-opcion='accionesVacunacionAnimal' data-accionEnExito="ACTUALIZAR" >
	<div id="estado"></div>
	<input type="hidden" id="idArete" name="idArete" value="0" />
	<input type="hidden" id="nombre_especie" name="nombre_especie" value="0" />	
	<input type="hidden" id="costo_vacuna" name="costo_vacuna" value="0" />
	<input type="hidden" id="operadorVacunacion" name="operadorVacunacion" value="<?php echo $filaDigitador['identificador'];?>" />
	<input type="hidden" id="opcion" name="opcion" value="0">
<input type="hidden" id="usuarioResponsable" name="usuarioResponsable" value="<?php echo $_SESSION['usuario'];?>" />
	<input type="hidden" id="numeroCertificadoVacunacionTemp" name="numeroCertificadoVacunacionTemp" value="0" />
	
	<fieldset>
		<legend>Búsqueda del certificado de vacunación</legend>						
		<div data-linea="1">
			<label>Especie: </label> 
			<select id="cmbEspecie" name="cmbEspecie">
				<option value="0">Seleccione...</option>
				<?php
					$especie = $cc-> especiesVacunacion($conexion);
					while ($fila = pg_fetch_assoc($especie)){
				    	echo '<option value="' . $fila['id_especies'] . '">' . $fila['nombre'] . '</option>';
				    }
				?>
			</select>
		</div>																													
		<div data-linea="1" >
			<label>No.Certificado: </label> 
			<input type="text" id="txtEspecieValorada" name="txtEspecieValorada" value="" maxlength="7" />
		</div>
		<div data-linea="2" style="text-align: center">
			<button type="button" id="btn_especie" name="btn_especie" >Buscar No.Certificado</button>
		</div>			
	</fieldset>
	<fieldset id="busquedaVacunador" name="busquedaVacunador">
		<legend>Búsqueda del vacunador</legend>	
		<div data-linea="1">
			<label>Identificación: </label> 
			<input type="text" id="identificacionVacunador" name="identificacionVacunador" value="" placeholder="Ej: 9999999999"  maxlength="250" />
		</div>		
		<div data-linea="1">
			<label>Nombres: </label> 
			<input type="text" id="nombreVacunador" name="nombreVacunador" value="" placeholder="Ej: Juan Alvarez"  maxlength="22" />
		</div>
		<div data-linea="2" style="text-align: center">
			<button type="button" id="btnBuscarVacunador" name="btnBuscarVacunador" >Buscar vacunador</button>
		</div>
	</fieldset>
	<fieldset id="infoVacuna" name="infoVacuna">
		<legend>Información de la vacunación</legend>	
		<div data-linea="1"  id="resultadoNumeroCertificado">
			<label>No. Certificado: </label>
			<select id="campoResultadoNumeroCertificado" name="campoResultadoNumeroCertificado">
				<option value="0">Seleccione...</option>
			</select>	
		</div>		
		<div data-linea="1">
			<label>Fecha de vacunación: </label> 
			<input type="text" id="fecha_emision" name="fecha_emision"/>
		</div>	
		<div data-linea="2"  id="resultadoVacunadorTecnico">
			<label>Vacunador: </label> 
			<select id="cmbVacunador" name="cmbVacunador">	
			<?php
				switch($filaTipoUsuario['codificacion_perfil']) {
					case 'PFL_USUAR_INT':
						$qResultadoUsuarioTecnico=$ppc->verificarTecnicoAgrocalidad($conexion,$identificadorUsuario);
						while ($filas = pg_fetch_assoc($qResultadoUsuarioTecnico)){
							echo '<option value="' . $filas['identificador'] . '">' . $filas['nombres'] .' - '. $filas['identificador'] . '</option>';
						}
					break;
					case 'PFL_USUAR_EXT':						?>
							<option value="0">Seleccione...</option>				
				<?php 
					$vacunadores=$ppc->listarVacunadoresEmpresa($conexion,$filaDigitador['id_empresa']);
					while ($fila= pg_fetch_assoc($vacunadores)){
						echo '<option value="'. $fila['identificador'] .'">'. $fila['nombres'] . ' - ' . $fila['identificador'] .'</option>';
					}
					break;
				}
			?>
		</select>
		</div>
		<div data-linea="2">
			<label>Tipo vacunación: </label> <select id="tipoVacuna" name="tipoVacuna">
				<option value="0">Seleccione...</option>
				<?php 
					$tipoVacuna = $cc->listaTipoVacuna($conexion);
					while ($fila = pg_fetch_assoc($tipoVacuna)){							
							echo '<option data-costo="' . $fila['costo'] . '"  value="' . $fila['id_tipo_vacuna'] . '">' . $fila['nombre_vacuna'] . '</option>';
				    }
			    ?>
			</select>
		</div>				
		<div data-linea="3">
			<label>Laboratorio: </label>
			<select id="laboratorio" name="laboratorio" >
			<option value="0">Seleccione...</option>
			</select> 				
		</div>
		<div data-linea="3">
			<label>Distribuidor: </label> 
			<select id="cmbDistribuidor" name="cmbDistribuidor">
				<option value="0">Seleccione...</option>		
				<?php
				switch($filaTipoUsuario['codificacion_perfil']) {
					case 'PFL_USUAR_INT':
						$qResultadoUsuarioTecnico=$ppc->listarTecnicosDistribuidores($conexion);
						while ($filas = pg_fetch_assoc($qResultadoUsuarioTecnico)){
							echo '<option value="' . $filas['identificador'] . '">' . $filas['nombres'] . ' - ' . $filas['identificador'] . '</option>';
						}
					break;
					case 'PFL_USUAR_EXT':
						$distribuidores=$ppc->listarDistribuidoresEmpresa($conexion,$filaDigitador['id_empresa']);
						while ($fila= pg_fetch_assoc($distribuidores)){
							echo '<option value="'. $fila['identificador'] .'">'. $fila['nombres'] . ' - ' . $fila['identificador'] .'</option>';
						}
					break;
				}
				?>
			</select>
		</div>
		<div data-linea="4">
			<label>Lote de Vacuna: </label> 
			<select id="lote" name="lote" >
			<option value="0">Seleccione...</option>
			</select>
		</div>
	</fieldset>
	
		<fieldset id="infoSitio" name="infoSitio">		
		<legend>Busqueda del sitio</legend>
			<div data-linea="1">
				<label>Identificación operador: </label> 
				<input type="text" id="identificadorOperadorV" name="identificadorOperadorV" value="" placeholder="Ej. 9999999999"  maxlength="13" />
			</div>	
			<div data-linea="1">
				<label>Nombre operador: </label> 
				<input type="text" id="nombreOperador" name="nombreOperador" value="" placeholder="Ej: Diego Andino"  maxlength="250" />
			</div>
			<div data-linea="2">
				<label>Nombre del sitio: </label> 
				<input type="text" id="nombreSitioOperador" name="nombreSitioOperador" value="" placeholder="Ej: Hacienda San Vicente"  maxlength="250" />
			</div>				
			<div data-linea="2">
				<label>Codigo del área: </label> 
				<input type="text" id="codigoArea" name="codigoArea" value="" placeholder="Ej: 1712387123.17010601"  maxlength="22" />
			</div>	
			<div data-linea="3" style="text-align: center">
				<button type="button" id="buscarSitio" name="buscarSitio" >Buscar sitio</button>
			</div>
		</fieldset>
		
		<fieldset id="resulSitio" >		
			<legend>Detalle de la vacunación</legend>
			<div data-linea="1" id="res_sitio">
			<label>Nombre del sitio: </label> 
				<select id="campoSitio">
				<option value="0">Seleccione...</option>
				</select>
			</div>
			<div data-linea="1" id="resultadoAreas">
			<label>Nombre del área: </label> 
				<select id="campoAreas">
				<option value="0">Seleccione...</option>
				</select>
			</div>
			
			<div data-linea="2" id="res_catastro_productos">
			</div>	
		</fieldset>
			<fieldset>
				<legend>Detalle de serie de aretes</legend>
				<div data-linea="13">
					<label>Total vacunados</label>
				</div>
				<div data-linea="13">
					<label>Serie inicio</label>
				</div>
				<div data-linea="13">
					<label>Serie fin</label>
				</div>	
				<div data-linea="13">
					<label>Agregar aretes</label>
				</div>
							
				<div data-linea="14">
					<input type="text" id="tVacunados" name="tVacunados" disabled="disabled" />
				</div>
				<div data-linea="14">
					<input type="text" id="serie_inicio" name="serie_inicio" placeholder="Ej: 10" maxlength="7" data-er="^[0-9]+(\[0-9]{1,2})?$" />
				</div>
				
				<div data-linea="14">
					<input type="text" id="serie_fin" name="serie_fin" placeholder="Ej: 15" maxlength="7" data-er="^[0-9]+(\[0-9]{1,2})?$" />
				</div>
				 
				<div data-linea="14">
					<button type="button" id="btn_serie_aretes" name="btn_serie_aretes" onclick="agregarAretes()" class="mas">Agregar</button>
				</div>
				<div>
					<table id="tablaArete">
						<tr>
							<th width="78px">Quitar</th>
							<th>Serie inicio</th>
							<th>Serie fin</th>
						</tr>
						<tbody id="serie_aretes">
						</tbody>
					</table>
				</div>
				<div data-linea="16"></div>
				<div data-linea="16">
					<label>Total aretes : </label> <input type="text" id="totalAretes" name="totalAretes" value="0" disabled="disabled" />
				</div>
			</fieldset>

		<button id="btn_guardar" type="button" name="btn_guardar" onclick="grabarVacunacion()">Guardar vacunación</button>
	
</form>

<script type="text/javascript">	

	var array_lote = <?php echo json_encode($lotes); ?>;
	var array_validaCertificado = <?php echo json_encode($validaCertificado); ?>;
	var array_laboratorio = <?php echo json_encode($laboratorios); ?>;
	
	$(document).ready(function(){		
		
		construirAnimacion($(".pestania"));			
		$("#fecha_emision").datepicker({
		      changeMonth: true,
		      changeYear: true,
		      maxDate: "0"
		});	

		
		switch(<?php echo json_encode($filaTipoUsuario['codificacion_perfil']); ?>) {
		    case 'PFL_USUAR_INT':
				$("#busquedaVacunador").show();
			break;
		    case 'PFL_USUAR_EXT':
		    	$("#busquedaVacunador").hide();
			break;
		}
     	distribuirLineas();    	
	});	

	$("#cmbEspecie").change(function(event){	
		if($("#cmbEspecie").val() != 0){
			$("#nombre_especie").val($("#cmbEspecie option:selected").text());
		}
	});
	
	$("#laboratorio").change(function(){            	    
    	slote ='0';
    	slote = '<option value="0">Seleccione...</option>';
		for(var i=0;i<array_lote.length;i++){	
			if ($("#laboratorio").val()==array_lote[i]['id_laboratorio']){																						  
				slote += '<option value="'+array_lote[i]['id_lote']+'">'+ array_lote[i]['numero_lote']+'</option>';
			}			  
		}	   
	    $('#lote').html(slote);
	    $("#lote").removeAttr("disabled");
	});

	$("#tipoVacuna").change(function(){	
		if($("#tipoVacuna").val() != 0){
			$("#costo_vacuna").val($("#tipoVacuna option:selected").attr('data-costo'));
			sLaboratorio ='0';
			sLaboratorio = '<option value="0">Seleccione...</option>';
			for(var i=0;i<array_laboratorio.length;i++){	   
					sLaboratorio += '<option value="'+array_laboratorio[i]['id_laboratorio']+'"> '+ array_laboratorio[i]['nombre_laboratorio']+'</option>';
			}	   		    
			$('#laboratorio').html(sLaboratorio);
			$("#laboratorio").removeAttr("disabled");	  			
		}		 
	});
		
	$("#buscarSitio").click(function(event){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#identificadorOperadorV").val()=="" && $("#nombreOperador").val()=="" && $("#nombreSitioOperador").val()=="" && $("#codigoArea").val()==""){	
			 error = true;		
			$("#identificadorOperadorV").addClass("alertaCombo");
			$("#nombreOperador").addClass("alertaCombo");
			$("#nombreSitioOperador").addClass("alertaCombo");
			$("#codigoArea").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese al menos un campo para realizar la búsqueda.").addClass('alerta');
		}
		if (!error){
			$('#nuevoVacunacionAnimal').attr('data-opcion','accionesVacunacionAnimal');
			$('#nuevoVacunacionAnimal').attr('data-destino','res_sitio');
		    $('#opcion').val('1');		
			abrir($("#nuevoVacunacionAnimal"),event,false); 
		}
	 });
	 
	 $("#btn_especie").click(function(event){	
		 $(".alertaCombo").removeClass("alertaCombo");
			var error = false;

			if($("#txtEspecieValorada").val() == 0 ){	
				 error = true;		
				$("#txtEspecieValorada").addClass("alertaCombo");
				$("#estado").html("Por favor digite el número de certificado").addClass('alerta');
			}
			
			if($("#cmbEspecie").val() == 0 ){	
				 error = true;		
				$("#cmbEspecie").addClass("alertaCombo");
				$("#estado").html("Por favor seleccione la especie").addClass('alerta');
			}

			if (!error){		 
				$("#estado").html('');
				var h=("0000000" + $('#txtEspecieValorada').val() ).slice (-7);
				$('#numeroCertificadoVacunacionTemp').val(h);	
				$('#nuevoVacunacionAnimal').attr('data-opcion','accionesVacunacionAnimal');
				$('#nuevoVacunacionAnimal').attr('data-destino','resultadoNumeroCertificado');
			    $('#opcion').val('3');		
				abrir($("#nuevoVacunacionAnimal"),event,false); 
				}				 						
	 });	

	 $("#btnBuscarVacunador").click(function(event){
		 $(".alertaCombo").removeClass("alertaCombo");
			var error = false;

			if($("#identificacionVacunador").val()=="" && $("#nombreVacunador").val()=="" ){	
				 error = true;		
				$("#identificacionVacunador").addClass("alertaCombo");
				$("#nombreVacunador").addClass("alertaCombo");
				$("#estado").html("Por favor ingrese al menos un campo para realizar la búsqueda.").addClass('alerta');
			}

			if (!error){		 
			$("#estado").html('');
				$('#nuevoVacunacionAnimal').attr('data-opcion','accionesVacunacionAnimal');
				$('#nuevoVacunacionAnimal').attr('data-destino','resultadoVacunadorTecnico');
			    $('#opcion').val('6');		
				abrir($("#nuevoVacunacionAnimal"),event,false); 
				
			}				 
	 });
	 
	 $("#btn_guardar").click(function(event){
		 if($("#estado").html() == ''){
			 $("#btn_guardar").attr('disabled','disabled');
			 $("#estado").html("").removeClass('alerta');
				$("#nuevoVacunacionAnimal").attr('data-destino', 'detalleItem'); 
				$('#nuevoVacunacionAnimal').attr('data-opcion','guardarVacunacionAnimal');   
				ejecutarJson("#nuevoVacunacionAnimal");
		 }		 		 	
	 }); 

	function agregarAreas(){
		chequearCamposArea();
	}
		
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function quitarArete(fila){
		serieInicio = $("#serie_aretes tr").eq($(fila).index()).find("input[id='hSerie_inicio']").val();
		serieFin = $("#serie_aretes tr").eq($(fila).index()).find("input[id='hSerie_fin']").val();
		vRes = (parseInt(serieFin) - parseInt(serieInicio)) + 1;	
		vTRes =  parseInt($("#totalAretes").val())-vRes;
		$("#serie_aretes tr").eq($(fila).index()).remove();
		$("#totalAretes").val(vTRes);
	}
	
	var arr = [];
	var arrAux = [];
	var arrAnimal = [];
	var ban = 0;
	var sw1 = 0;						
	var sw2 = 0;
	var sw3 = 0;
	
	function agregarAretes(){	
	   error = false;

	   if ($("#serie_inicio").val() == ''){
		   error = true;
	       $("#serie_inicio").addClass("alertaCombo");
	   }
	   
	   if ($("#serie_inicio").val().length == 0){
		   error = true;
	       $("#serie_inicio").addClass("alertaCombo");
	   }

	   if ($("#serie_fin").val() == ''){
		   error = true;
	       $("#serie_fin").addClass("alertaCombo");
	   }

	   if ($("#serie_fin").val().length == 0){
		   error = true;
	       $("#serie_fin").addClass("alertaCombo");
	   }
	   		  
	   if (error == true){	    	
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
	   }else{	
			chequearCamposAreaAretes();	
	   } 			
	}

	function chequearCamposAreaAretes(){		
        res = false;
        resAreteVac = false;     
	    valFin = $("#serie_fin").val();	
	    valInicio = $("#serie_inicio").val();
	    
	    resAreteVac = parseInt(valInicio)  <=  parseInt(valFin);
		
		if (error == true){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			  if(resAreteVac==true){				
				id_arete = $("#idArete").val();
				
				if (id_arete==0)
					codigo_arete = $("#areas option:selected").val();
				else
					codigo_arete = $("#idArete").val()+1;		
				$("#idArete").val(codigo_arete);
				
				if($("#areas #r_"+codigo_arete.replace(/ /g,'')).length==0){	
					if ($("#totalAretes").val() !=""){
						TArete = $("#totalAretes").val();
					}else{
						TArete = 0;
					}				
						
					numArete = parseInt(TArete) + (($("#serie_fin").val() - $("#serie_inicio").val()) + 1);	
					res = numArete <= parseInt($("#tVacunados").val());
									
					if (res){
						$("#serie_aretes").append("<tr id='r_"+codigo_arete.replace(/ /g,'')+"'><td><button type='button' onclick='quitarArete(\"#r_"+codigo_arete.replace(/ /g,'')+"\")' class='menos'>Quitar</button></td><td><input id='hCodSerie_aretes' name='hCodSerie_aretes[]' value='"+codigo_arete+"' type='hidden'><input id='hSerie_aretes' name='hSerie_aretes[]' value='"+$("#areas_arete option:selected").text()+"'type='hidden'><input id='hSerie_inicio' name='hSerie_inicio[]' value='"+$("#serie_inicio").val()+"' type='hidden'>"+$("#serie_inicio").val()+"</td><td>"+$("#serie_fin").val()+"<input id='hSerie_fin' name='hSerie_fin[]' value='"+$("#serie_fin").val()+"' type='hidden'></td></tr>");
						$("#serie_inicio").val("");
						$("#serie_fin").val("");
						$("#totalAretes").val(numArete);	
						
					}
					else{
						TArete = 0;
						$("#serie_inicio").val("");
						$("#serie_fin").val("");																																				
						alert('Error al ingresar la serie de aretes');
					}													
				}
			  }
			  else {
				  alert('Error al ingresar la serie de aretes !!!');
			  }									
		 }
    }
    
	function grabarVacunacion(){
		chequearCamposGrabarVacunacion();
	}
		
	function chequearCamposGrabarVacunacion(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#cmbEspecie").val()==0){
			error = true;
			$("#cmbEspecie").addClass("alertaCombo");
		}
		
		if($("#tipoVacuna").val()==0){
			error = true;
			$("#tipoVacuna").addClass("alertaCombo");
		}
		
		if($("#laboratorio").val()==0){
			error = true;
			$("#laboratorio").addClass("alertaCombo");
		}

		if($("#campoResultadoNumeroCertificado").val()==0 || $("#numeroCertificadoVacunacion").val()==0){
			error = true;
			$("#campoResultadoNumeroCertificado").addClass("alertaCombo");
			$("#numeroCertificadoVacunacion").addClass("alertaCombo");
		}

		if($("#cmbVacunador").val()==0  ){
			error = true;
			$("#cmbVacunador").addClass("alertaCombo");
		}

		if($("#cmbDistribuidor").val()==0){
			error = true;
			$("#cmbDistribuidor").addClass("alertaCombo");
		}
		
		if($("#areas").val()==0 || $("#campoAreas").val()==0){
			error = true;
			$("#areas").addClass("alertaCombo");
			$("#campoAreas").addClass("alertaCombo");
		}

		if($("#lote").val()==0){
			error = true;
			$("#lote").addClass("alertaCombo");
		}
		if($("#fecha_emision").val()==""){
			error = true;
			$("#fecha_emision").addClass("alertaCombo");
		}					

		if($("#cmbSitio").val()==0 || $("#campoSitio").val()==0){
			error = true;
			$("#cmbSitio").addClass("alertaCombo");
			$("#campoSitio").addClass("alertaCombo");
		}						

		if (error == true){
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}else{                   
			$("#estado").html("").removeClass('alerta');			      	
		}//estado
	}//inicio
	
</script>
<style type="text/css">
#tablaArete td {
	font-size: 1em;
	border: 1px solid rgba(0, 0, 0, .1);
	padding: 3px 7px 2px 7px;
}

#tablaArete th {
	font-size: 1em;
	border: 1px solid rgba(0, 0, 0, .1);
	padding: 3px 7px 2px 7px;
	background-color: rgba(0, 0, 0, .1)
}

#tablaVacunaAnimal td {
	font-size: 1em;
	border: 1px solid rgba(0, 0, 0, .1);
	padding: 3px 7px 2px 7px;
}

#tablaVacunaAnimal th {
	font-size: 1em;
	border: 1px solid rgba(0, 0, 0, .1);
	padding: 3px 7px 2px 7px;
	background-color: rgba(0, 0, 0, .1)
}
</style>
