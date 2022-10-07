<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorVacunacionAnimal.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cv = new ControladorVacunacionAnimal();

$operadoresAdministradoresVacunacion = $cv-> filtrarAdministradorVacunacion($conexion);


?>
<header>
	<h1>Nuevo datos vacunador</h1>
</header>

	<form id='nuevoAdministradorTecnicoVacunador' data-rutaAplicacion='vacunacionAnimal' data-opcion='guardarNuevoVacunador'>
	<input type="hidden" id="usuario_responsable" name="usuario_responsable" value="<?php echo $_SESSION['usuario'];?>" />
	<input type="hidden" id="opcion" name="opcion" value="0">
	
	<div id="estado"></div>
		<fieldset>
			<legend>Administración de los vacunadores</legend>
				<div data-linea="1">
					<label>* Seleccionar el administrador de vacunación, punto de distribución y vacunador. </label>								
			    </div>
			    <div data-linea="2">				
					<label>Especie</label>
					<select id="especieAdministradorOperador" name="especieAdministradorOperador">
						<option value="0">Seleccione....</option>
						<?php 
							$opEspecieVacunacion = $cc->listaEspecieOperadorVacunador($conexion);
							foreach ($opEspecieVacunacion as $especie){
								echo '<option value="' .$especie['id_especie'].'">'. $especie['nombre_especie'].'</option>';							
							}
						?>
					</select>									
			    </div>
			    <div data-linea="3">				
					<label>Administrador operador</label>
					<select id="cmbAdministradorVacunacion" name="cmbAdministradorVacunacion">
				    </select>					
			    </div>			    		  			
			    <div data-linea="4" id="res_punto_distribucion">												
			    </div>
			    <div data-linea="5">
					<label>Vacunador</label>									
					<select id="cmbVacunador" name="cmbVacunador" disabled="disabled">						
					</select>						
			    </div>
			    
			      			   		   			   			
		</fieldset>	
		<button id="btn_guardar" type="button" name="btn_guardar">Guardar técnico vacunador</button>
		

  </form>

<script type="text/javascript">	
			
    var array_operadoresAdministradorVacunacion = <?php echo json_encode($operadoresAdministradoresVacunacion); ?>;
    var array_vacunadorOficaial = <?php echo json_encode($vacunadorOficial); ?>;
    

    $(document).ready(function(){			
		distribuirLineas();		
	});

    $("#especieAdministradorOperador").click(function(event){	
		if($("#especieAdministradorOperador").val() != 0){
			sAdminOperador ='0';
			sAdminOperador = '<option value="0">Seleccionar...</option>';
			for(var i=0;i<array_operadoresAdministradorVacunacion.length;i++){	
				if ($("#especieAdministradorOperador").val()==array_operadoresAdministradorVacunacion[i]['id_especie']){	    
					sAdminOperador += '<option value="'+array_operadoresAdministradorVacunacion[i]['id_administrador_vacunacion']+'"> '+ array_operadoresAdministradorVacunacion[i]['nombre_administrador']+'</option>';
				}			  
			}	   		    
			$('#cmbAdministradorVacunacion').html(sAdminOperador);
			$("#cmbAdministradorVacunacion").removeAttr("disabled");			
		}					 	
	});   

    //combo administradores vacunacion
	$("#cmbAdministradorVacunacion").change(function(event){
		if($("#cmbAdministradorVacunacion").val() != 0){	
				 
			 $('#nuevoAdministradorTecnicoVacunador').attr('data-opcion','guardarNuevoVacunador');	
			 $('#nuevoAdministradorTecnicoVacunador').attr('data-destino','res_punto_distribucion');		 
		     $('#opcion').val('1');		
			 abrir($("#nuevoAdministradorTecnicoVacunador"),event,false); //Se ejecuta ajax, busqueda de vacunador
			 //$('#existentes').val(res_catastro_productos);			
		}					 	
	 }); 
	 
	$("#btn_guardar").click(function(event){
		 event.preventDefault();
		 $('#nuevoAdministradorTecnicoVacunador').attr('data-opcion','guardarNuevoVacunador');
		 $('#nuevoAdministradorTecnicoVacunador').attr('data-destino','res_vacunador');
	     $('#opcion').val('3');		     	
		 abrir($("#nuevoAdministradorTecnicoVacunador"),event,false); //Se ejecuta ajax, busqueda de sitio	
		 abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);			 		 	
	 });    

    /*
    $("#nuevoAdministradorTecnicoVacunador").submit(function(event){
        if($('#opcion').val('1')){		
			event.preventDefault();
			abrir($(this),event,false);
        }
	});
 	*/
    
    function chequearCamposGuardar(form){
		$("#estado").html("").addClass('correcto');
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false; 
        //campos 
      	
		if(!$.trim($("#especieAdministradorOperador").val())){
			error = true;
			$("#especieAdministradorOperador").addClass("alertaCombo");
		}		
		
		if (!error){
			return true;		
		}else{			
			$("#estado").html("Por favor revise el formato de la información ingresada").addClass('alerta');
			return false;
		}
		
	}
	
</script>