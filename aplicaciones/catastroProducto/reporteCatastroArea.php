<?php 
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastroProducto.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAplicaciones.php';

session_start();
$cc = new ControladorCatalogos();
$cp = new ControladorCatastroProducto();
$conexion = new Conexion();

$identificadorUsuario=$_SESSION['usuario'];
$filaTipoUsuario=pg_fetch_assoc($cp->obtenerTipoUsuario($conexion, $identificadorUsuario));

$banderaSolicitante = false;
$identificadorSolicitante = "";
$banderaValidarAcciones = false;

switch ($filaTipoUsuario['codificacion_perfil']){
    
    case 'PFL_USUAR_INT':
        
        $qResultadoUsuarioTecnico = $cp->verificarTecnicoAgrocalidad($conexion, $identificadorUsuario);
        
        if(pg_num_rows($qResultadoUsuarioTecnico) > 0){
            //echo "<br/> Es técnico con ficha -> catastro libre<br/>";
        }else{
            //echo "<br/> Es técnico sin ficha-> bloqueo catastro<br/>";
            //$banderaValidarAcciones = true;
        }
        
    break;
        
    case 'PFL_USUAR_EXT':
        
        $qOperacionesEmpresaUsuario = $cp->obtenerOperacionesEmpresaUsuario($conexion, $identificadorUsuario, "('digitadorVacunacion')", "('OPT')");
        $operacionesEmpresaUsuario = pg_fetch_assoc($qOperacionesEmpresaUsuario);
        
        $codigoTipoOperacion = $operacionesEmpresaUsuario['codigo_tipo_operacion'];
        
        if(stristr($codigoTipoOperacion, 'OPT') == true){
            //echo "<br/> Es empleado traspatio-> catastra libre cualquier categoria<br/>";
        }else{
            
            $qResultadoEmpresaOperador = $cp->consultarEmpresaPorOperacion($conexion, "('OPI', 'FER')" , $identificadorUsuario);
            $resultadoEmpresaOperador = pg_fetch_assoc($qResultadoEmpresaOperador);
            
            $identificadorEmpresa = $resultadoEmpresaOperador['identificador_empresa'];
            
            if(pg_num_rows($qResultadoEmpresaOperador) > 0){
                //echo "<br/> Es empleado -> catastra de la empresa solo lechones y lechonas<br/>";
                $banderaSolicitante = true;
                $identificadorSolicitante = $identificadorEmpresa;
            }else{
                
                $qOperacionesUsuario = $cp->obtenerOperacionesUsuario($conexion, $identificadorUsuario, "( 'OPI', 'PRO', 'COM')");
                
                if(pg_num_rows($qOperacionesUsuario) > 0){
                    //echo "<br/> Es operador-> catastra sus propios cerdos solo lechones y lechonas<br/>";
                    $banderaSolicitante = true;
                    $identificadorSolicitante = $identificadorUsuario;
                }else{
                    //echo "<br/> No es empleado-> se bloquea el catastro<br/>";
                    $banderaValidarAcciones = true;
                }
                
            }
            
        }
        
    break;
        
}

?>

<header>
	<h1>Reporte Catastro por Sitio</h1>
	<nav>
		<form id="nuevoFiltroCatastro" data-rutaAplicacion="catastroProducto" data-opcion="listaReporteCatastroArea" data-destino="tabla">
			
			<input type="hidden" id="identificadorResponsableH" name="identificadorResponsableH" value="<?php echo $identificadorUsuario;?>" />
			<table class="filtro">
				<tr>
					<th colspan="4">Consultar Catastro:</th>
				</tr>
				<tr>
					<td>* Identificación Operador:</td>
					<td><input id="identificadorSolicitanteH" name="identificadorSolicitanteH" type="text" maxlength="13" <?php if ($banderaSolicitante){ ?> value="<?php echo $identificadorSolicitante; ?>" readonly="readonly" <?php }?> /></td>
					<td>* Nombre Operador:</td>
					<td><input name="nombreOperador"  id="nombreOperador" type="text" /></td>		
				</tr>
				
				<tr>
					<td>* Nombre del Sitio:</td>
					<td><input name="nombreSitio"  id="nombreSitio" type="text" /></td>
					<td>* Provincia:</td>
					<td>
					<select id="provincia" name="provincia" style="width:99%" >
						<option value="0">Seleccione...</option>
						<?php 
							$qProvincias = $cc->listarLocalizacion($conexion, "PROVINCIAS");
							while($fila = pg_fetch_assoc($qProvincias)){
								echo '<option value="' . $fila['nombre'] . '">' . $fila['nombre'] . '</option>';
							}
						?>		
					</select>
					</td>
				</tr>
				<tr>
					<td colspan="4" style='text-align:center'><button>Consultar Catastro</button></td>
				</tr>
				<tr>
					<td colspan="4" style='text-align:center' id="estadoFiltro"></td>
				</tr>
			</table>
		</form>
	</nav>
</header>
<div id="tabla"></div>

<script>

	banderaValidarAcciones = <?php echo json_encode($banderaValidarAcciones);?>
	
	$(document).ready(function(event){
		$("#listadoItems").removeClass("programas");
		$("#listadoItems").removeClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un item para visualizar.</div>');
	});

	$("#nuevoFiltroCatastro").submit(function(event){
		event.preventDefault();
			
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#identificadorSolicitanteH").val()=="" && $("#nombreSitio").val()=="" && $("#provincia").val()==0 && $("#nombreOperador").val().length<3 ){	
			 error = true;		
		 	$("#estadoFiltro").html("Por favor ingrese al menos 3 letras para buscar las coincidencias.").addClass('alerta');
		}

		if($("#identificadorSolicitanteH").val()=="" && $("#nombreOperador").val()=="" && $("#provincia").val()==0 && $("#nombreSitio").val().length<3 ){	
			 error = true;		
		 	$("#estadoFiltro").html("Por favor ingrese al menos 3 letras para buscar las coincidencias.").addClass('alerta');
		}
		
		if($("#identificadorSolicitanteH").val()=="" && $("#nombreOperador").val()=="" && $("#nombreSitio").val()=="" && $("#provincia").val()==0  ){	
			 error = true;
			$("#estadoFiltro").html("Por favor ingrese al menos un campo que contiene (*) para realizar la consulta").addClass('alerta');			
		}
		if(!error){ 
			$("#estadoFiltro").html('');   
			abrir($(this),event,false);
		}	
	});
	
	if(banderaValidarAcciones){
		$("input").attr("disabled", true);
		$("button").attr("disabled", true);		
		$("select").attr("disabled", true);		
	}
	
</script>