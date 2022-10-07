<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAdministrarCatalogos.php';

$conexion = new Conexion();
$cac = new ControladorAdministrarCatalogos();

$idCatalogoPadre= $_POST['idCatalogoPadre'];
$idCatalogoHijo = $_POST['idCatalogoHijo'];
$idItemPadre= $_POST['idItemPadre'];
$nivel = $_POST['nivel'];
$idExclusionCatalogo = $_POST['idExclusionCatalogo'].','.$_POST['idCatalogoHijo'];
$idSubitemCatalogoPadre = ($_POST['idSubitemCatalogoPadre']=='' ? 'null': $_POST['idSubitemCatalogoPadre']);
$etapaProceso = unserialize($_POST['etapaProceso']);
$tipoProceso = $_POST['tipoProceso'];

if($tipoProceso == 'carga'){
    $etapaProceso[] = array('idCatalogoPadre'=>$idCatalogoPadre, 'idCatalogoHijo'=>$idCatalogoHijo, 'pagina'=>'abrirSubItem', 'idItemPadre'=>$idItemPadre, 'nivel'=>($nivel = '1'? $nivel : --$nivel), 'idExclusionCatalogo'=>$idExclusionCatalogo, 'idSubitemCatalogoPadre'=>$idSubitemCatalogoPadre);
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

$itemHijo = $cac->listarItems($conexion, $idCatalogoHijo, '1');
$itemsAsignados = $cac->listarItemAsignadosPorCatalogo($conexion, $idCatalogoPadre, $idCatalogoHijo, $idItemPadre, $nivel, $idSubitemCatalogoPadre);

?>

<header>
	<h1>Modificar Registro Subitems</h1>
</header>
<body>

<form id="regresar" data-rutaAplicacion="administracionCatalogos" data-opcion="<?php echo $ultimoProceso['pagina'];?>" data-destino="detalleItem">
	<?php echo $datosRegreso;?>
	<input type="hidden" name="tipoProceso" value="descarga"/>
	<input type="hidden" name="etapaProceso" value=<?php echo serialize($etapaProceso);?>/>
	<button class="regresar">Regresar nivel anterior</button>
</form>

<form id="nuevoRegistro" data-rutaAplicacion="administracionCatalogos" data-opcion="agregarSubItem">

	<input type="hidden" name="idCatalogoPadre" value="<?php echo $idCatalogoPadre;?>"/>
	<input type="hidden" name="idCatalogoHijo" value="<?php echo $idCatalogoHijo;?>"/>
	<input type="hidden" name="idItemPadre" value="<?php echo $idItemPadre;?>"/>
	<input type="hidden" name="nivel" value="<?php echo $nivel;?>"/>
	<input type="hidden" name="idExclusionCatalogo" value="<?php echo $idExclusionCatalogo;?>"/>
	<input type="hidden" name="idSubitemCatalogoPadre" value="<?php echo $idSubitemCatalogoPadre;?>"/>
	<input type="hidden" name="etapaProceso" value=<?php echo serialize($etapaProceso);?>/>
	
	<fieldset>
		<legend>Nuevo catálogo de subitems:</legend>
		<div data-linea="1">
			<label for="idItemHijo">Catalogo:</label>
    		<select id="idItemHijo" name="idItemHijo">
    			<option value= "">Seleccione...</option>
        		<?php 
        		while ($fila = pg_fetch_assoc($itemHijo)){
                        echo '<option value= "'.$fila['id_item'].'">'.$fila['nombre'].'</option>';
                    }
                ?>
    		</select>
		</div>
		<input type="hidden" name="nombreItemHijo" id="nombreItemHijo"/>
		<div>
			<button type="submit" class="mas">Añadir catálogo</button>
		</div>
	</fieldset>	
</form>

<fieldset>
	<legend>Catálogos asignados</legend>
	<table id="registros">
		<?php
		while ($subItem = pg_fetch_assoc($itemsAsignados)){
		    echo $cac->imprimirLineaItemoHijo($subItem['id_catalogo_padre'], $subItem['id_catalogo_hijo'], $subItem['id_item_padre'], $subItem['id_item_hijo'], $subItem['nombre'], $nivel, $idExclusionCatalogo, $subItem['id_subitem_catalogo'], $etapaProceso);
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

	$("#idItemHijo").change(function(event){
		mostrarMensaje("","EXITO");
	    if($("#idItemHijo").val() != ''){
	    	$("#nombreItemHijo").val($("#idItemHijo option:selected").text());
	    }else{
	    	mostrarMensaje("Por favor seleccione un valor","FALLO");
		}
	});

</script>