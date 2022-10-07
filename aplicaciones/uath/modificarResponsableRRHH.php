<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatastro.php';
	require_once '../../clases/ControladorAreas.php';

try {
	$conexion = new Conexion();
	$cc = new ControladorCatastro();
	$ca = new ControladorAreas();
	
} catch (Exception $e) {
	//echo $e;
}

?>

<header>
	<h1>Administrar Responsable</h1>
</header>
<body><div id="estado"></div>
<form id="actualizarResponsableRRHH" data-rutaAplicacion="uath" data-destino="areaTrabajo #detalleItem" data-opcion="actualizarResponsableRRHH"  >
<p>
		<button id="modificarRes" type="button" class="editar" <?php echo ($filaSolicitud['estado']=='Aprobado'? ' disabled=disabled':'')?>>Modificar</button>
		<button id="actualizarRes" type="submit" class="guardar" disabled="disabled">Guardar</button>
	</p>
	<fieldset>
		<legend>Funcionario</legend>		
		<table style="width: 100%">
			<thead>
				<tr>
					<th>Identificador</th>
					<th>Nombre funcionario</th>
					<th>Zona</th>
	                <th>Estado</th>
				</tr>
			</thead>
			<?php 
			$contador = 0;
			$consulta = $cc->obtenerResponsablesRRHH($conexion, '', '', '',$_POST['id']);
			while($fila = pg_fetch_assoc($consulta)) {	
				$estado='<input id="'.$fila['identificador'].'" checked type="checkbox" class="respon" value="'.$fila['identificador'].'" disabled="disabled" onclick="seleccionarFuncionario(id)">';
						
			echo '<tr>
					<td>'.$fila['identificador'].'</td>
					<td>'.$fila['servidor'].'</td>
					<td>'.$fila['zona'].'</td>
					<td>'.$estado.'</td>	
				</tr>';
			echo $ident='<input type="hidden" id="estadoResp" name="estadoResp" value="'.$fila['estado'].'" />';
			echo $ident='<input type="hidden" id="idarea" name="idarea" value="'.$fila['zona_area'].'" />';
			echo $ident='<input type="hidden" id="identificadorRRHH" name="identificadorRRHH" value="'.$fila['identificador'].'" />';
	 	}
	 	
	 	?>
		</table>
	</fieldset>
	<fieldset id="detalle">
		<legend>Buscar Funcionario</legend>
		
		<div data-linea="5">
			<label>Número de cédula:</label> 
			<input type="text"
					id="identificadorBusque" name="identificadorBusque"
					 value=""/>
					<div id="estadoBusque"></div>
			<input type="hidden"
					id="identificadorUsuario" name="identificadorUsuario"
					 value=""/>
		</div>
		<div data-linea="5">
					<button id="buscarUsuario" disabled="disabled" onclick="buscarDatos(id);  return false;">Buscar</button>
		</div>
		
	</fieldset>
	
	<fieldset id="resultadoconsulta">
		<legend>Funcionario</legend>		
		<table id="consulta" style="width: 100%">
			<thead>
				<tr>
					<th>Identificador</th>
					<th>Nombre funcionario</th>
					<th>Zona</th>
	                <th>estado</th>
				</tr>
				</thead>
				<tr id="fila">
					
				</tr>
			
		</table>
	</fieldset>
	</form>
<script>
$(document).ready(function(){
	$("#resultadoconsulta").hide();
});


function seleccionarFuncionario(id){
	$('.respon:checked').each(			
		    function() {
			    if($(this).val() != $("#"+id).val() )
		    	$(this).removeAttr('checked');
		    }
		);	
	if($("#"+id).prop('checked') ) {
		 $("#estadoResp").val('activo');
		 $("#identificadorUsuario").val($("#"+id).val());
		
	}else{
		$("#estadoResp").val('inactivo');

	}	
}

$("#modificarRes").click(function(){	
	$("#actualizarResponsableRRHH input").removeAttr("disabled");
	$("#actualizarRes").removeAttr("disabled");
	$(this).attr("disabled","disabled");
	$("#buscarUsuario").removeAttr("disabled");
});
	

function buscarDatos(id){
	
    var valor = $("#identificadorBusque").val();
    var identif = $("#idarea").val();
    
    if(valor.length>=9){

        if(valor != identif){
       var consulta = $.ajax({
          type:'POST',
          url:'aplicaciones/uath/buscarDatosUsuarioRRHH.php',
          data:{identificador:valor, idarea:identif},
          dataType:'JSON'
       });
       consulta.done(function(data){
          if(data.error!==undefined){
            $('#estadoBusque').html(data.error).addClass("alerta");
           	$("#resultadoconsulta").hide();
           	$("#consulta td").remove();
           	$("#consulta input").remove(); 
            $('#identificadorUsuario').val('');
           	
             return false;
          } else {
        	 $("#consulta td").remove();
             $("#consulta input").remove(); 
        	 $('#estadoBusque').html('');identificadorUsuario
             $('#identificadorBusque').val('');
        	 $('#identificadorUsuario').val(valor);
           	 $("#resultadoconsulta").show();
             if(data.nombre!==undefined){ $("#consulta").append("<td>"+valor+"</td>");}
             if(data.nombre!==undefined){ $("#consulta").append("<td>"+data.nombre+"</td>");}
             if(data.zona!==undefined){ $("#consulta").append("<td>"+data.zona+"</td>");}
             $('.respon:checked').each(			
         		    function() {
         		    	$(this).removeAttr('checked');
         		    }
         		);	
      		
             if(!$("#actualizarRes").is(':disabled')){
            	 if(data.nombre!==undefined){ $("#consulta").append("<input id="+valor+" type='checkbox' checked class='respon' value="+valor+" onclick='seleccionarFuncionario(id)'>");}
             }else{
                 if(data.nombre!==undefined){ $("#consulta").append("<input id="+valor+" type='checkbox' checked  class='respon' value="+valor+" disabled='disabled' onclick='seleccionarFuncionario(id)'>");}
             }

             return true;
          }
       });
       consulta.fail(function(){
          $('#estadoBusque').html('Ha habido un error contactando al servidor.').addClass("alerta");
          return false;
       });
        } else {
            $('#estadoBusque').html('El funcionario debe ser diferente del seleccionado..!!').addClass("alerta");
            return false;
         }    
    } else {
       $('#estadoBusque').html('La longitud debe ser mayor a 9 caracteres...').addClass("alerta");
       return false;
    }
}

$("#actualizarResponsableRRHH").submit(function(e) {
	e.preventDefault();
	var error = false;	
	$('.respon:checked').each(			
		    function() {
		    	error = true; 
		    });	
	
	if (error == true){
		$("#tabla").html('');
        abrir($(this), e, false);
   } else {
       mostrarMensaje("Por favor debe seleccionar un responsable.","FALLO");
   }
	
});
</script>


