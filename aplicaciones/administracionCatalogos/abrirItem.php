<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAdministrarCatalogos.php';

$conexion = new Conexion();
$cac = new ControladorAdministrarCatalogos();

$idCatalogo= $_POST['idCatalogoPadre'];
$idItem= $_POST['idItemPadre'];
$nivel= $_POST['nivel'];
$idExclusionCatalogo = $_POST['idExclusionCatalogo'];
$idSubitemCatalogoPadre = ($_POST['idSubitemCatalogoPadre']=='' ? 'null': $_POST['idSubitemCatalogoPadre']);
$etapaProceso = unserialize($_POST['etapaProceso']);
$tipoProceso = $_POST['tipoProceso'];

if($tipoProceso == 'carga'){
    $etapaProceso[] = array('idCatalogoPadre'=>$idCatalogo,'pagina'=>'abrirItem', 'idItemPadre'=>$idItem, 'nivel'=>($nivel = '1'? $nivel : --$nivel), 'idExclusionCatalogo'=>$idExclusionCatalogo, 'idSubitemCatalogoPadre'=>$idSubitemCatalogoPadre);
}else{
    unset($etapaProceso[count($etapaProceso)-1]);
}

$ultimoProceso = $etapaProceso[count($etapaProceso)-2];

$datosRegreso = '';
foreach ($ultimoProceso as $key => $datos){
    if($key != 'pagina'){
        $datosRegreso .= '<input type="hidden" name="'.$key.'" value="'.$datos.'" />';
    }
}

$catalogos = $cac->listarCatalogosSinCatalogoPadre($conexion, $idExclusionCatalogo);
$catalogosAsignados = $cac->listarCatalogosAsignadosPorItem($conexion, $idCatalogo, $idItem, $nivel, $idSubitemCatalogoPadre);

?>

<header>
	<h1>Modificar Registro Ítem</h1>
</header>
<body>
<div id="estado"></div>
<form id="regresar" data-rutaAplicacion="administracionCatalogos" data-opcion="<?php echo $ultimoProceso['pagina'];?>" data-destino="detalleItem">
	<?php echo $datosRegreso;?>
	<input type="hidden" name="tipoProceso" value="descarga"/>
	<input type="hidden" name="etapaProceso" value=<?php echo serialize($etapaProceso);?>/>
	<button class="regresar">Regresar nivel anterior</button>
</form>

<form id="frmItem" data-rutaAplicacion="administracionCatalogos" data-opcion="actualizarItem" >	
	<input type="hidden" id="idItem" name="idItem" value="<?php echo $idItem;?>"/>
	<input type="hidden" name="idCatalogo" value="<?php echo $idCatalogo;?>"/>
	<input type="hidden" id="opcion" name="opcion" />
	<fieldset>
		<legend>Modificar Ítems:</legend>
		<?php
			$res=$cac->obtenerItemxID($conexion, $idItem);
			$fila=pg_fetch_assoc($res);			
		?>
		<div data-linea="1">
			<label for="txtNombreItem">Ítem:</label>			
			<input type="text" id="txtNombreItem" name="txtNombreItem" value="<?php echo $fila['nombre'];?>"  disabled="disabled">
		</div>
		<div data-linea="2">
			<label for="txtDescripcion">Descripción:</label>
			<input type="text" id="txtDescripcion" name="txtDescripcion" value="<?php echo $fila['descripcion'];?>"  disabled="disabled">
			<div style="text-align:center;width:100%">
				<button id="btnModificar" class="editar" >Modificar</button>
				<button type="submit" id="btnActualizar" class="guardar" disabled="disabled">Actualizar</button>
			</div>		
		</div>
	</fieldset>
</form>

<form id="nuevoRegistro" data-rutaAplicacion="administracionCatalogos" data-opcion="agregarSubCatalogo">

	<input type="hidden" name="idCatalogoPadre" value="<?php echo $idCatalogo;?>"/>
	<input type="hidden" name="idItemPadre" value="<?php echo $idItem;?>"/>
	<input type="hidden" name="nivel" value="<?php echo $nivel;?>"/>
	<input type="hidden" name="idExclusionCatalogo" value="<?php echo $idExclusionCatalogo;?>"/>
	<input type="hidden" name="idSubitemCatalogoPadre" value="<?php echo $idSubitemCatalogoPadre;?>"/>
	<input type="hidden" name="etapaProceso" value=<?php echo serialize($etapaProceso);?>/>
	<input type="hidden" name="tipoProceso" value="carga"/>
	
	<fieldset>
		<legend>Nuevo catálogo de subitems:</legend>
		<div data-linea="1">
			<label for="idCatalogoHijo">Catalogo:</label>
    		<select id="idCatalogoHijo" name="idCatalogoHijo">
    			<option value= "">Seleccione...</option>
        		<?php 
                    while ($fila = pg_fetch_assoc($catalogos)){
                        echo '<option value= "'.$fila['id_catalogo_negocios'].'">'.$fila['nombre'].'</option>';
                    }
                ?>
    		</select>
		</div>
		<input type="hidden" name="nombreCatalogoHijo" id="nombreCatalogoHijo"/>
		<div>
			<button type="submit" class="mas">Añadir catálogo</button>
		</div>
	</fieldset>	
</form>

<fieldset>
	<legend>Catálogos asignados</legend>
	<table id="registros">
		<?php
            while ($subCatalogo = pg_fetch_assoc($catalogosAsignados)){
                echo $cac->imprimirLineaCatalogoHijo($subCatalogo['id_catalogo_padre'], $subCatalogo['id_catalogo_hijo'], $subCatalogo['id_item_padre'], $subCatalogo['nombre'], $nivel, $idExclusionCatalogo, $etapaProceso, $idSubitemCatalogoPadre);
			}
		?>
	</table>
</fieldset>
</body>


<script type="text/javascript">

$("document").ready(function(event){
	distribuirLineas();
	acciones();
});

$("#btnModificar").click(function(event){
	event.preventDefault();
	val = $("#txtNombreItem").val();
	$("#txtNombreItem").attr("disabled",false);
	$("#txtDescripcion").attr("disabled",false);
	$("#btnModificar").attr("disabled",true);
	$("#btnActualizar").attr("disabled",false);
	$("#txtNombreItem").focus().val("").val(val);
});

$("#frmItem").submit(function(event){
	event.preventDefault();
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($.trim($("#txtNombreItem").val())=="" ){
		error = true;
		$("#txtNombreItem").addClass("alertaCombo");
	}	

	if (!error){
		$("#frmItem").attr("data-opcion","actualizarItem");
		ejecutarJson($(this));		
		if($("#estado").html() == "Los datos han sido guardados satisfactoriamente"){
    		$("#txtNombreItem").attr("disabled",true);
    		$("#txtDescripcion").attr("disabled",true);
    		$("#btnActualizar").attr("disabled",true);
    		$("#btnModificar").attr("disabled",false);
		}
	}else{
		$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
	}	
});

$("#idCatalogoHijo").change(function(event){
	mostrarMensaje("","EXITO");
    if($("#idCatalogoHijo").val() != ''){
    	$("#nombreCatalogoHijo").val($("#idCatalogoHijo option:selected").text());
    }else{
    	mostrarMensaje("Por favor seleccione un valor","FALLO");
	}
});

</script>
