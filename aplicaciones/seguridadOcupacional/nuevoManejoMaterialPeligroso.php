<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorSeguridadOcupacional.php';

$conexion = new Conexion();
$ce = new ControladorCatastro();
$cc = new ControladorCatalogos();
//$so = new ControladorSeguridadOcupacional();

$identificador=$_SESSION['usuario'];
//$laboratorios=$so->listaSubTipoLaboratoriosMaterialesPeligrosos($conexion);

?>

<header>
	<h1>Asignar Material Peligroso</h1>
</header>

<form id="datosManejoMaterialPeligroso" data-rutaAplicacion="seguridadOcupacional" data-opcion="guardarNuevoManejoMaterialPeligroso">
	<input type="hidden" name="usuario" id="usuario" value="<?php echo $identificador;?>" /> 
	<input type="hidden" id="opcion" value="Nuevo" name="opcion" />
	<div id="estado"></div>
	
	<fieldset>
		<legend>Asignar Químico a Laboratorio</legend>
		
		<div data-linea="1">
			<label>Coordinación laboratorio:</label> 
			<select name="coordinacionLaboratorio" id="coordinacionLaboratorio">
				<option value="" >Seleccione...</option>
				<?php
					$qLaboratoriosMaterialesPeligrosos = $cc->listaLaboratoriosMaterialesPeligrosos($conexion);
					while($fila = pg_fetch_assoc($qLaboratoriosMaterialesPeligrosos)){
						echo '<option value="'.$fila['id_laboratorio'].'">'.$fila['nombre_laboratorio'].'</option>';
					}
				?>
			</select>
		</div>
		
		<div data-linea="2">
			<label>Laboratorio:</label> 
			<select name="laboratorioNuevo" id="laboratorioNuevo">
				<option value="" >Seleccione...</option>
			</select>
		</div>
		
		<div data-linea="3">
			<label>Químico:</label> 
			<select name="materialPeligroso" id="materialPeligroso">
					<option value="" >Seleccione...</option>
					<?php
						$qMaterialesPeligrosos = $cc->listaMaterialesPeligrosos($conexion);
						while ($fila = pg_fetch_assoc($qMaterialesPeligrosos)){
						    echo '<option  value="' . $fila['id_material_peligroso'] . '">' . $fila['nombre_material_peligroso'] . '</option>';
						}		    
					?>
			</select>
		</div>
	</fieldset>
	
	<button  type="submit" class="guardar">Guardar</button>
	
</form>

<script type="text/javascript">

	//var array_laboratorio = < ?php echo json_encode($laboratorios); ?>;

	$(document).ready(function(){
		distribuirLineas();
	});

	$("#coordinacionLaboratorio").change(function(){	
		if($("#coordinacionLaboratorio").val() != 0){
			sLaboratorio = '<option value="">Seleccione...</option>';
			for(var i=0;i<array_laboratorio.length;i++){
				if ($("#coordinacionLaboratorio").val()==array_laboratorio[i]['idLaboratorioPadre'])	   
					sLaboratorio += '<option value="'+array_laboratorio[i]['idLaboratorio']+'"> '+ array_laboratorio[i]['nombreLaboratorio']+'</option>';
			}	   		    
			$('#laboratorioNuevo').html(sLaboratorio);
			$("#laboratorioNuevo").removeAttr("disabled");	  			
		}	 
	});


	$("#datosManejoMaterialPeligroso").submit(function(event){
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#coordinacionLaboratorio").val())){
			error = true;
			$("#coordinacionLaboratorio").addClass("alertaCombo");
		}
		if(!$.trim($("#laboratorioNuevo").val())){
			error = true;
			$("#laboratorioNuevo").addClass("alertaCombo");
		}
		
		if(!$.trim($("#materialPeligroso").val()) ){
			error = true;
			$("#materialPeligroso").addClass("alertaCombo");
		}			
		
		if (error){
			$("#estado").html("Ingresar información en campos obligatorios.").addClass('alerta');
		}else{ 
			ejecutarJson("#datosManejoMaterialPeligroso");
			if($('#estado').html()=='Los datos han sido ingresados satisfactoriamente')
				$('#_actualizar').click();
		}
		
	});

</script>