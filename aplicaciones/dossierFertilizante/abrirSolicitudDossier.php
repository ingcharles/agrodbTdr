<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';


require_once '../../clases/ControladorEnsayoEficacia.php';
require_once '../../clases/ControladorDossierFertilizante.php';


$idUsuario= $_SESSION['usuario'];			//Es el usuario logeado en la solicitud
$id_solicitud = $_POST['id'];
$id_flujo = $_POST['idFlujo'];

$identificador=$idUsuario;						//Es el duenio del documento, puede variar si ya hay un protocolo y el usuario es alguien de revision, aprobacion, etc..
$conexion = new Conexion();
$ce = new ControladorEnsayoEficacia();
$cr = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();
$cf = new ControladorDossierFertilizante();




$datosGenerales=array();
$operador = array();
$tipoProductos=array();
$clasificaciones=array();
$operadoresFabricantes=array();

$fabricantesDossier=array();
$composicionIA=array();

$es_fabricante=true;
if($id_solicitud!=null && $id_solicitud!='_nuevo'){
	$datosGenerales=$cf->obtenerSolicitud($conexion, $id_solicitud);
	$identificador=$datosGenerales['identificador'];						//El duenio del documento
	$arrOperadoresFabricantes=$ce->obtenerOperadoresConOperacionesEnEstado($conexion,'IAF',"in ('FRA','FOR')","in ('registrado')");
	foreach ($arrOperadoresFabricantes as $key=>$item){
		$a=array();
		$a['value']=$item['identificador'];
		$a['label']='('.$item['identificador'].')'.$item['razon_social'];
		$operadoresFabricantes[]=$a;
	}
	$fabricantesDossier=$cf->obtenerFabricantesDossier($conexion,$id_solicitud);
	$composicionIA=$cf->obtenerComposicionProducto($conexion,$id_solicitud);
	$clasificacionesDossier=$cf->obtenerClasificaciones($conexion,$id_solicitud);
	$cultivosDossier=$cf->obtenerCultivos($conexion,$id_solicitud);
	$archivosAnexos=$cf->listarArchivosAnexos($conexion,$id_solicitud);
}
else{
	
}
//busca los datos del operador
$res = $cr->buscarOperador($conexion, $identificador);
$operador = pg_fetch_assoc($res);
$tipoProducto=$ce->listarElementosCatalogo($conexion,'P3C15');
$items=$cc->listarLocalizacion($conexion,'PAIS');
$paises=array();
$paisEcuador=0;
while ($fila = pg_fetch_assoc($items)){
	$paises[] = array('codigo'=>$fila['id_localizacion'],'nombre'=>$fila['nombre']);
	if($fila['codigo']=='EC')
		$paisEcuador=$fila['id_localizacion'];
}
//recupera los tipos de producto
$tipoProductos=$cf->obtenerTipoProducto ($conexion, 'IAF');
$subTiposProducto=$cf->obtenerSubTiposProducto ($conexion, 'IAF');

$subTiposSeleccionado=array_filter($subTiposProducto, function ($elemento) use ($datosGenerales) { return trim((strtolower( $elemento['id_tipo_producto'])) == $datosGenerales['id_tipo_producto']); } );

$sitiosAreas=$cf->obtenerSitiosAreas($conexion, $identificador);
$clasificaciones=$ce->listarElementosCatalogoEx($conexion,'P3C12');
$cultivosNombres = $ce->obtenerProductosXSubTipo($conexion,'CULTIVOS');

$unidadesMedida=$ce->obtenerUnidadesMedida($conexion,'DF_COMP');
$unidadesMedidaPresentacion=$ce->obtenerUnidadesMedida($conexion,'DF_PRES');
$unidadesMedidaDensidad=$ce->obtenerUnidadesMedida($conexion,'DF_DENS');
$unidadesMedidaDosis=$ce->obtenerUnidadesMedida($conexion,'DF_DOSIS');

$catalogoUsos=array();
$res=$cc->listarUsosPorArea($conexion,'IAV');
while ($fila = pg_fetch_assoc($res)){
$catalogoUsos[] = $fila;
}


$usos=$ce->obtenerPlagas($conexion,'IAF');


$catalogoUnidadesTiempo=$ce->listarElementosCatalogo($conexion,'P_TIEMPO');


$tipoOperador=$ce->obtenerOperacionesDelOperador($conexion,$identificador,'IAF');
$registroProductosMatriz=$ce->obtenerProductosMatrizRegistrados($conexion,$identificador);
$nombreProductos=$ce->listarElementosCatalogo($conexion,'P3C18');
$liNombreProductos=array();
foreach ($nombreProductos as $key=>$item){
	$a=array();
	$a['value']=$item['nombre'];
	$a['label']=$item['nombre'];
	$liNombreProductos[]=$a;
}


$elementosConcentracion=$ce->obtenerIA($conexion,'IAF');

$formulaciones=$cf->obtenerFormulacionesPorArea($conexion,'IAF','DF_%');



$declaracionLegal=$ce->obtenerTitulo($conexion,'EP');

//******************************  ANEXOS **************************
$tipoAnexos=$ce->listarElementosCatalogoEx($conexion,'ANEXOF');
$paths=$ce->obtenerRutaAnexos($conexion,'dossierFertilizante');
$pathAnexo=$paths['ruta'];

?>

<header>
   <h1>Solicitud de dossier fertilizante</h1>
</header>

<div id="estado"></div>


<div class="pestania" id="P1" style="display: block;">
   <form id='frmNuevaSolicitud' data-rutaAplicacion='dossierFertilizante' data-opcion='guardarNuevaSolicitud' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="paso_solicitud" name="paso_solicitud" value="P1" />

      <input type="hidden" id="opcion" name="opcion" />

      <fieldset>
         <legend>Informacion del solicitante</legend>
         <div data-linea="1">
            <label for="tipo_producto">Producto a registrar: </label>
            <select name="tipo_producto" id="tipo_producto" required>
               <option value="">Seleccione....</option><?php
            foreach ($tipoProducto as $key=>$item){
            if(strtoupper($item['codigo']) == strtoupper($datosGenerales['tipo_producto'])){
            echo '<option value="' . $item['codigo'] . '" selected="selected">' . $item['nombre'] . '</option>';
            }else{
            echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
            }
            }
            ?>
            </select>
         </div>
         <div data-linea="2">
            <label>Tipo Operador: </label>
            <ul type="circle"><?php
            foreach ($tipoOperador as $key=>$item){
            echo '<li>'.$sret=$item['operacion'].'</li>';
            }
            ?>
            </ul>
         </div>
         <div data-linea="3">
            <label for="tipoRazon" class="opcional">Tipo razón social</label>
            <input value="<?php echo $operador['tipo_operador'];?>" name="tipoRazon" type="text" id="tipoRazon" placeholder="Tipo de razon social" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

         <div data-linea="4">
            <label for="razon" class="opcional">Razón social</label>
            <input value="<?php echo $operador['razon_social'];?>" name="razon" type="text" id="razon" placeholder="Nombre de la empresa" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

         <div data-linea="5">
            <label for="ruc" class="opcional">CI/RUC/PASS</label>
            <input value="<?php echo $operador['identificador'];?>" name="ruc" type="text" id="ruc" placeholder="Nombre de la empresa" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="6">
            <label for="direccion" class="opcional">Dirección</label>
            <input value="<?php echo $operador['direccion'];?>" name="direccion" type="text" id="direccion" placeholder="Direccion" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="7">
            <label for="provincia" class="opcional">Provincia</label>
            <input value="<?php echo $operador['provincia'];?>" name="provincia" type="text" id="provincia" placeholder="Provincia" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="8">
            <label for="canton" class="opcional">Cantón</label>
            <input value="<?php echo $operador['canton'];?>" name="canton" type="text" id="canton" placeholder="Canton" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="9">
            <label for="parroquia" class="opcional">Parroquia</label>
            <input value="<?php echo $operador['parroquia'];?>" name="parroquia" type="text" id="parroquia" placeholder="Parroquia" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="10">
            <label for="dirReferencia" class="opcional">Dirección de referencia</label>
            <input value="<?php echo $datosGenerales['direccion_referencia'];?>" name="dirReferencia" type="text" id="dirReferencia" placeholder="Dirección de referencia" class="cuadroTextoCompleto" maxlength="512" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required />
         </div>
         <div data-linea="11">
            <label for="telefono" class="opcional">Telefono</label>
            <input value="<?php echo $operador['telefono_uno'];?>" name="telefono" type="text" id="telefono" placeholder="Telefono" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="12">
            <label for="celular" class="opcional">Celular</label>
            <input value="<?php echo $operador['celular_uno'];?>" name="celular" type="text" id="celular" placeholder="Celular" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="13">
            <label for="correo" class="opcional">Correo</label>
            <input value="<?php echo $operador['correo'];?>" name="correo" type="text" id="correo" placeholder="Correo" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <hr />
         <div data-linea="14">
            <label for="ciLegal" class="opcional">Cédula del representante legal</label>
            <input value="<?php echo $datosGenerales['ci_representante_legal'];?>" name="ciLegal" type="text" id="ciLegal" placeholder="Cédula" maxlength="10" data-er="^[0-9]+$" required />
         </div>
         
         <div data-linea="16">
            <label for="nombreLegal">Nombres representante legal</label>
            <input style="width:100%" value="<?php echo $operador['nombre_representante'];?>" name="nombreLegal" type="text" id="nombreLegal" placeholder="Nombres" maxlength="200" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" disabled="disabled" />
         </div>
         <div data-linea="17">
            <label for="apellidoLegal">Apellidos representante legal</label>
            <input value="<?php echo $operador['apellido_representante'];?>" name="apellidoLegal" type="text" id="apellidoLegal" placeholder="Apellidos" maxlength="250" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" disabled="disabled" />
         </div>
         <div data-linea="18">
            <label for="correoLegal" class="opcional">Correo del representante legal</label>
            <input value="<?php echo $datosGenerales['email_representante_legal'];?>" name="correoLegal" type="text" id="correoLegal" placeholder="Correo" class="cuadroTextoCompleto" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required />
         </div>
      </fieldset>
      
      <fieldset>
         <legend>Datos generales</legend>

         <div data-linea="1">
            <label for="id_sitio">Sitio</label>
            <select name="id_sitio" id="id_sitio" required>
               <option value="">Seleccione....</option><?php
         foreach ($sitiosAreas as $key=>$item){
         
         if(strtoupper($item['id_sitio']) == strtoupper($datosGenerales['id_sitio'])){
         echo '<option value="' . $item['id_sitio'] . '" selected="selected">' . $item['nombre_lugar'] . '</option>';
         }else{
         echo '<option value="' . $item['id_sitio'] . '">' . $item['nombre_lugar'] . '</option>';
         }
         }
         ?>
            </select>
         </div>
         <div data-linea="2">
            <label for="id_area">Area</label>
            <select name="id_area" id="id_area" required>
               <option value="">Seleccione....</option><?php
         $areas=array();
         foreach ($sitiosAreas as $key=>$item){
         if($datosGenerales['id_sitio'] == $item['id_sitio']){
         $areas=$item['areas'];
         break;
         }
         }
         foreach ($areas as $key=>$item){
         if(strtoupper($item['id_area']) == strtoupper($datosGenerales['id_area'])){
         echo '<option value="' . $item['id_area'] . '" selected="selected">' . $item['nombre_area'] . '</option>';
         }else{
         echo '<option value="' . $item['id_area'] . '">' . $item['nombre_area'] . '</option>';
         }
         }
         ?>
            </select>
         </div>

         <hr />
         <div data-linea="3" id="rep_tecnico">
            <label for="ci_representante_tecnico">Representante técnico</label>
            <select id="ci_representante_tecnico" name="ci_representante_tecnico">
               <option value="">Seleccione....</option><?php
         $repTecnicos=array();
         foreach ($sitiosAreas as $key=>$item){
         if($datosGenerales['id_sitio'] == $item['id_sitio']){
         $areas=$item['areas'];
         foreach ($areas as $k=>$v){
         if($datosGenerales['id_area'] == $v['id_area']){
         $repTecnicos=$v['representates_tecnicos'];
         break;
         }
         }
         break;
         }
         }
         foreach ($repTecnicos as $key=>$item){
         if(strtoupper($item['identificacion_representante']) == strtoupper($datosGenerales['ci_representante_tecnico'])){
         echo '<option value="' . $item['identificacion_representante'] . '" selected="selected">' . $item['nombre_representante'] . '</option>';
         }else{
         echo '<option value="' . $item['identificacion_representante'] . '">' . $item['nombre_representante'] . '</option>';
         }
         }
         ?>
            </select>
         </div>
         <div data-linea="4">
            <label for="tituloTecnico">Título del representante técnico</label>
            <input value="" name="tituloTecnico" type="text" id="tituloTecnico" placeholder="titulo" maxlength="256" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" disabled="disabled" />
         </div>
         <div data-linea="5">
            <label for="registroSenesyt">Registro del título en el SENESCYT</label>
            <input value="" name="registroSenesyt" type="text" id="registroSenesyt" placeholder="registro" maxlength="256" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" disabled="disabled" />
         </div>
         <hr />
         <div data-linea="6">
            <label for="objetivo">Objetivo:</label>
            <select name="objetivo" id="objetivo">
               <option value="">Seleccione....</option><?php
         $items = $ce->listarElementosCatalogo($conexion,'P3C13');
         foreach ($items as $key=>$item){
         if(strtoupper($item['codigo']) == strtoupper($datosGenerales['objetivo'])){
         echo '<option value="' . $item['codigo'] . '" selected="selected">' . $item['nombre'] . '</option>';
         }else{
         echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
         }
         }
         ?>
            </select>
         </div>



         <div data-linea="8" class="verRegistroClon">
            <input type="hidden" id="varObjetivo" name="varObjetivo" value="" />
            <label for="clon_registro_madre" class="opcional">No. de registro producto matriz:</label>
            <select name="clon_registro_madre" id="clon_registro_madre" required>
               <option value="">Seleccione....</option><?php
         foreach ($registroProductosMatriz as $key=>$item){
         if(strtoupper($item['numero_registro']) == strtoupper($datosGenerales['clon_registro_madre'])){
         echo '<option value="' . $item['numero_registro'] . '" selected="selected">' .'('.$item['numero_registro'].')'. $item['nombre_comun'] . '</option>';
         }else{
         echo '<option value="' . $item['numero_registro'] . '">' . '('.$item['numero_registro'].')'.$item['nombre_comun'] . '</option>';
         }
         }
         ?>
            </select>
         </div>

         <div data-linea="9" class="verRegistroClon">
            <label for="clon_nombre_madre">Nombre producto madre</label>
            <input value="" name="clon_nombre_madre" type="text" id="clon_nombre_madre" placeholder="nombre" maxlength="256" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" disabled="disabled" />
         </div>
         <div data-linea="10" class="verRegistroClon">
            <label for="clon_numero">Clones registrados</label>
            <input value="" name="clon_numero" type="text" id="clon_numero" maxlength="256" disabled="disabled" />
         </div>
         <div class="justificado verRegistroClon">
            <label for="declaracion_juramentada" class="opcional">Declaración juramentada:</label>
            <textarea name="declaracion_juramentada" id="declaracion_juramentada" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
                <?php echo  htmlspecialchars($datosGenerales['declaracion_juramentada']);?>

            </textarea>
         </div>
         
      </fieldset>

      <button id="btnGuardarPrimero" type="button" class="guardar">Guardar solicitud</button>

   </form>
</div>

<div class="pestania" id="P2" style="display: block;">
   <form id='frmFabricantes' data-rutaAplicacion='dossierFertilizante' data-opcion='guardarPasosSolicitud'>
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="paso_solicitud" name="paso_solicitud" value="P2" />

      <fieldset>
         <legend>Origen del producto</legend>

         <div class="siNacional">
            <label for="es_fabricante">El producto es fabricado por el solicitante ?</label>
            SI<input type="radio" id="es_fabricanteSI" name="es_fabricante" class="verElementoFabricante" value="SI" <?php if($es_fabricante==true) echo "checked=true"?> />
            NO<input type="radio" id="es_fabricanteNO" name="es_fabricante" class="verElementoFabricante" value="NO" <?php if($es_fabricante==false) echo "checked=true"?> />
         </div>

         <div data-linea="2" class="siNacional fabricanteNacional">
            <label for="fn_razon_social" class="opcional">Razón social</label>
            <input value="<?php echo $operador['razon_social'];?>" name="fn_razon_social" type="text" id="fn_razon_social" placeholder="Nombre de la empresa" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="3" class="siNacional fabricanteNacional">
            <label for="fn_ruc" class="opcional">CI/RUC/PASS</label>
            <input value="<?php echo $operador['identificador'];?>" name="fn_ruc" type="text" id="fn_ruc" placeholder="Nombre de la empresa" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="4" class="siNacional fabricanteNacional">
            <label for="fn_sitio_nombre">Sitio</label>
            <input value="" name="fn_sitio_nombre" type="text" id="fn_sitio_nombre" placeholder="nombre del sitio" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="5" class="siNacional fabricanteNacional">
            <label for="fn_sitio_direccion" class="opcional">Dirección del sitio</label>
            <input value="" name="fn_sitio_direccion" type="text" id="fn_sitio_direccion" placeholder="direccion del sitio" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="6" id="es_por_contratoVer" class="siNacional">
            <label>Ingrese los datos del elaborador por contrato</label>

         </div>

         <div data-linea="7" class="siNacional fabricanteContrato">
            <label for="fc_ruc" class="opcional">CI/RUC/PASS</label>
            <input value="" name="fc_ruc" type="text" id="fc_ruc" placeholder="Máximo 13 caracteres" class="verElementoFabricante" maxlength="13" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="8" class="siNacional fabricanteContrato">
            <label for="fc_razon_social" class="opcional">Razón social</label>
            <input value="" name="fc_razon_social" type="text" id="fc_razon_social" placeholder="Nombre de la empresa" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

         <div data-linea="9" class="siNacional fabricanteContrato">
            <label for="fc_id_sitio">Sitio</label>
            <select name="fc_id_sitio" id="fc_id_sitio" class="verElementoFabricante"></select>
         </div>
         <div data-linea="10" class="siNacional fabricanteContrato">
            <label for="fc_sitio_direccion" class="opcional">Dirección del sitio</label>
            <input value="" name="fc_sitio_direccion" type="text" id="fc_sitio_direccion" placeholder="direccion del sitio" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>


         <div data-linea="13" class="siImportado">
            <label for="id_extranjero">Identificación</label>
            <input value="" name="id_extranjero" type="text" id="id_extranjero" class="verElementoFabricante" placeholder="de la empresa extranjera" class="cuadroTextoCompleto" maxlength="13" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="14" class="siImportado">
            <label for="razon_extranjero" class="opcional">Razón social</label>
            <input value="" name="razon_extranjero" type="text" id="razon_extranjero" class="verElementoFabricante" placeholder="de la empresa extranjera" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="15" class="siImportado">
            <label for="ex_pais">País de origen</label>
            <select name="ex_pais" id="ex_pais" class="verElementoFabricante">
               <option value="">Seleccione....</option>               <?php
               foreach ($paises as $key=>$item){
               echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
               }
               ?>
            </select>
         </div>

         <div data-linea="16" class="siImportado">
            <label for="ex_direccion">Dirección</label>
            <input value="" name="ex_direccion" type="text" id="ex_direccion" class="verElementoFabricante" placeholder="de la empresa extranjera" maxlength="1024" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" />

         </div>

         <button id="btnAddExtranjero" class="mas verElementoFabricante" type="button">Agregar</button>

         <table id="tblExtranjeros" style="width:95%">
            <thead>
               <tr>
                  <th style="width:10%;">Identificación</th>
                  <th style="width:20%;">Razón social</th>
                  <th style="width:45%;">Dirección</th>
                  <th style="width:20%;">País de origen</th>
                  <th style="width:5%;"></th>
               </tr>
            </thead>
            <tbody></tbody>
         </table>

			<div data-linea="18">
            <label id="tblFabricantes"></label>
         </div>

      </fieldset>


      <button type="submit" class="guardar">Guardar solicitud</button>
   </form>
</div>

<div class="pestania" id="P3" style="display: block;">

   <form id='frmDatosProducto' data-rutaAplicacion='dossierFertilizante' data-opcion='guardarOrigenProducto'>
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="paso_solicitud" name="paso_solicitud" value="P2" />

      <fieldset>
         <legend>Datos del producto</legend>
         <div data-linea="1">
            <label for="nombreProducto">Nombre del producto:</label>
            <input value="<?php echo $datosGenerales['producto_nombre'];?>" name="nombreProducto" type="text" id="nombreProducto" placeholder="Nombre del Producto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required />

         </div>
         <p>Concentración del producto:</p>
         <div data-linea="7">
            <label>Elemento</label>
            <select name="elementoComposicion" id="elementoComposicion" class="verObsComposicion">
               <option value="">Seleccione....</option>               <?php
               foreach ($elementosConcentracion as $key=>$item){
               echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
               }
               ?>
            </select>
         </div>
         <div data-linea="8">
            <label>Cantidad</label>
            <input value="" name="composicionValor" type="text" id="composicionValor" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" class="verObsComposicion" />
         </div>
         <div data-linea="9">
            <label>Unidad</label>
            <select name="composicionUnidad" id="composicionUnidad" class="verObsComposicion">
               <option value="">Seleccione....</option>               <?php
               foreach ($unidadesMedida as $key=>$item){
               echo '<option value="' . $item['id_unidad_medida'] . '">' . $item['nombre'] . '</option>';
               }
               ?>
            </select>
         </div>
         <div class="detalles">

            <button id="agregarElementoComposicion" type="button" class="mas verObsComposicion">Agregar</button>
         </div>

         <table id="tblComposicion" style="width:95%">
            <thead>
               <tr>
                  <th>Elemento</th>
                  <th>Cantidad</th>
                  <th>Unidad</th>
                  <th></th>
               </tr>
            </thead>
            <tbody></tbody>
         </table>
			<div data-linea="10">
            <label id="tblComposicionObservar"></label>
         </div>
      </fieldset>

   </form>

   <form id='frmTipoClasificacion' data-rutaAplicacion='dossierFertilizante' data-opcion='atenderActualizaciones'>
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="opcionActualizar" name="opcionActualizar" value="guardarTipoClasificacion" />

      <fieldset>
         <legend>Tipo y clasificación</legend>
         <div data-linea="1">
            <label for="id_tipo_producto">Tipo de producto</label>
            <select name="id_tipo_producto" id="id_tipo_producto" required>
               <option value="">Seleccione....</option>               <?php
						foreach ($tipoProductos as $key=>$item){
							if(strtoupper($item['id_tipo_producto']) == strtoupper($datosGenerales['id_tipo_producto'])){
								echo '<option value="' . $item['id_tipo_producto'] . '" selected="selected">' . $item['nombre'] . '</option>';
							}else{
								echo '<option value="' . $item['id_tipo_producto'] . '">' . $item['nombre'] . '</option>';
							}
						}
               ?>
            </select>
         </div>
			<div data-linea="2" >
            <label>Sub tipo de producto: </label>
            <select name="id_subtipo_producto" id="id_subtipo_producto" class="verTipoClasificacion" required> 
					<option value="">Seleccione....</option>               <?php
						foreach ($subTiposSeleccionado as $key=>$item){
							if(strtoupper($item['id_subtipo_producto']) == strtoupper($datosGenerales['id_subtipo_producto'])){
								echo '<option value="' . $item['id_subtipo_producto'] . '" selected="selected">' . $item['nombre'] . '</option>';
							}else{
								echo '<option value="' . $item['id_subtipo_producto'] . '">' . $item['nombre'] . '</option>';
							}
						}
               ?>
            </select>

         </div>
         <div data-linea="3" class="justificado">
            <label>Clasificaciones: </label>
            <select name="clasificacion" id="clasificacion" class="verTipoClasificacion" required>
               <option value="">Seleccione....</option>               <?php
               foreach ($clasificaciones as $key=>$item){
               echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
               }
               ?>
            </select>

         </div>
			
         <button type="submit" class="mas verTipoClasificacion">Añadir</button>
      </fieldset>

   </form>

   <fieldset>
      <legend>Clasificaciones declaradas</legend>
      <table id="tblTipoClasificacion">         <?php
         foreach($clasificacionesDossier as $item){
         echo $cf->imprimirLineaTipoClasificacion($item['id_solicitud_clasificacion'],$item['nombre'],$item['codigo'],$item['sub_tipo_producto']);
         }
         ?>


      </table>
   </fieldset>

   <form id='frmNuevaSolicitud3' data-rutaAplicacion='dossierFertilizante' data-opcion='guardarPasosSolicitud'>
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="id_formulacion" name="id_formulacion" value="<?php echo $datosGenerales['id_formulacion'];?>" />

		<input type="hidden" id="paso_solicitud" name="paso_solicitud" value="P3" />

      <fieldset>
         <legend>Presentación</legend>
         <div data-linea="1">
            <label for="estado_fisico" class="opcional">Estado Físico: </label>
            <select name="estado_fisico" id="estado_fisico">
               <option value="">Seleccione....</option>               <?php
              
               foreach ($formulaciones as $key=>$item){
						if(strtoupper($item['codigo']) == strtoupper($datosGenerales['estado_fisico'])){
							echo '<option value="' . $item['codigo'] . '" data-formulacion="'.$item['id_formulacion'].'" selected="selected">' . $item['nombre'] . '</option>';
						}else{
							echo '<option value="' . $item['codigo'] . '" data-formulacion="'.$item['id_formulacion'].'">' . $item['nombre'] . '</option>';
						}
               }
               ?>
            </select>
         </div>
         <div data-linea="2">
            <label for="uso">Uso específico: </label>
            <select name="uso" id="uso">
               <option value="">Seleccione....</option>               <?php
               foreach ($usos as $key=>$item){
               if(strtoupper($item['codigo']) == strtoupper($datosGenerales['uso'])){
               echo '<option value="' . $item['codigo'] . '" selected="selected">' . $item['nombre'] . '</option>';
               }else{
               echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
               }
               }
               ?>
            </select>
         </div>
         <div data-linea="3">
            <label for="cantidad">Cantidad: </label>
            <input value="<?php echo $datosGenerales['cantidad'];?>" name="cantidad" type="text" id="cantidad" class="esNumerico" maxlength="10" data-er="^[0-9]+$" />
         </div>
         <div data-linea="4">
            <label for="unidad" class="opcional">Unidad de la presentación: </label>
            <select name="unidad" id="unidad">
               <option value="">Seleccione....</option>               <?php
               foreach ($unidadesMedidaPresentacion as $key=>$item){
               if(strtoupper($item['id_unidad_medida']) == strtoupper($datosGenerales['unidad'])){
               echo '<option value="' . $item['id_unidad_medida'] . '" selected="selected">' . $item['codigo'] . '</option>';
               }else{
               echo '<option value="' . $item['id_unidad_medida'] . '">' . $item['codigo'] . '</option>';
               }
               }
               ?>
            </select>
         </div>

      </fieldset>

      <button type="submit" class="guardar">Guardar solicitud</button>
   </form>

</div>

<div class="pestania" id="P4" style="display: block;">
   <form id='frmNuevaSolicitud4' data-rutaAplicacion='dossierFertilizante' data-opcion='guardarPasosSolicitud'>
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="paso_solicitud" name="paso_solicitud" value="P4" />

      <fieldset>
         <legend>Requisitos específicos</legend>

         <div data-linea="1">
            <label for="vida_util"> Vida útil:</label>
            <input value="<?php echo $datosGenerales['vida_util'];?>" name="vida_util" type="text" id="vida_util" class="esNumerico" maxlength="10" data-er="^[0-9]+$" required />
         </div>
         <div data-linea="1">

            <select name="unidad_vida_util" id="unidad_vida_util">
               <option value="">Seleccione....</option><?php
               foreach ($catalogoUnidadesTiempo as $key=>$item){
               if(strtoupper($item['codigo']) == strtoupper($datosGenerales['unidad_vida_util'])){
               echo '<option value="' . $item['codigo'] . '" selected="selected">' . $item['nombre'] . '</option>';
               }else{
               echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
               }
               }
               ?>
            </select>
         </div>
         <div data-linea="2">
            <label for="densidad">Densidad:</label>
            <input value="<?php echo $datosGenerales['densidad'];?>" name="densidad" type="text" id="densidad" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required />
         </div>
         <div data-linea="2">

            <select name="unidad_densidad" id="unidad_densidad">
               <option value="">Seleccione....</option><?php
               foreach ($unidadesMedidaDensidad as $key=>$item){
               if(strtoupper($item['id_unidad_medida']) == strtoupper($datosGenerales['unidad_densidad'])){
               echo '<option value="' . $item['id_unidad_medida'] . '" selected="selected">' . $item['nombre'] . '</option>';
               }else{
               echo '<option value="' . $item['id_unidad_medida'] . '">' . $item['nombre'] . '</option>';
               }
               }
               ?>
            </select>
         </div>
         <div data-linea="3">
            <label for="ph">pH:</label>
            <input value="<?php echo $datosGenerales['ph'];?>" name="ph" type="number" id="ph" class="esNumerico" min="0" max="14" step="0.1" data-er="^[0-9]+$" required />
         </div>
         <div data-linea="4">
            <label for="solubilidad">Solubilidad en agua [%]:</label>
            <input value="<?php echo $datosGenerales['solubilidad'];?>" name="solubilidad" type="number" id="solubilidad" class="esNumerico" min="0" max="100" step="0.1" data-er="^[0-9]+$" required />
         </div>
         <div data-linea="5" class="esSolido">
            <label for="granulometria">Granulometría:</label>
            <input value="<?php echo $datosGenerales['granulometria'];?>" name="granulometria" type="text" id="granulometria" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"  />
         </div>
         <div data-linea="6">
            <label for="corrosividad">Corrosividad:</label>
            <input value="<?php echo $datosGenerales['corrosividad'];?>" name="corrosividad" type="text" id="corrosividad" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required />
         </div>
         <div data-linea="8">
            <label for="materia_prima" class="opcional">Declaración de materias primas:</label>
            <textarea class="justificado" data-distribuir="no" name="materia_prima" id="materia_prima" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo htmlspecialchars($datosGenerales['materia_prima']);?></textarea>
         </div>
         <div data-linea="9">
            <label for="modo_preparacion" class="opcional">Modo de preparación:</label>
            <textarea class="justificado" data-distribuir="no" name="modo_preparacion" id="modo_preparacion" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo htmlspecialchars($datosGenerales['modo_preparacion']);?></textarea>
         </div>
         <div data-linea="10">
            <label for="ambito_aplicacion" class="opcional">Ambito de aplicación:</label>
            <textarea class="justificado" data-distribuir="no" name="ambito_aplicacion" id="ambito_aplicacion" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo htmlspecialchars($datosGenerales['ambito_aplicacion']);?></textarea>
         </div>
         <div data-linea="11">
            <label for="modo_aplicacion" class="opcional">Modo de aplicación:</label>
            <textarea class="justificado" data-distribuir="no" name="modo_aplicacion" id="modo_aplicacion" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo htmlspecialchars($datosGenerales['modo_aplicacion']);?></textarea>
         </div>
      </fieldset>

      <button type="submit" class="guardar">Guardar solicitud</button>
   </form>
</div>

<div class="pestania" id="P5" style="display: block;">

   <form id='frmCultivos' data-rutaAplicacion='dossierFertilizante' data-opcion='atenderActualizaciones' class="verClasificacionB verClasificacionG verClasificacionH verClasificacionI verClasificacionM verClasificacionN">
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="opcionActualizar" name="opcionActualizar" value="guardarCultivo" />
     
		<fieldset>
         <legend>Declaracion de cultivos</legend>
         <div data-linea="1">
            <label for="cultivoNomCien">Nombre Cientifico del Cultivo</label>
            <select name="cultivoNomCien" id="cultivoNomCien" class="verObsCultivos" required>
               <option value="">Seleccione....</option>               <?php
               foreach ($cultivosNombres as $key=>$item){
               echo '<option value="' . $item['id_producto'] . '" data-comun="' . $item['nombre_comun'] . '">' . $item['nombre_cientifico'] . '</option>';
               }
               ?>
            </select>
         </div>
         <div data-linea="2">
            <label for="cultivoNomComun">Nombre comun del cultivo</label>
            <input value="" name="cultivoNomComun" type="text" id="cultivoNomComun" disabled="disabled" />
         </div>
         <button type="submit" class="mas">Añadir</button>
      </fieldset>

   </form>

   <fieldset class="verClasificacionB verClasificacionG verClasificacionH verClasificacionI verClasificacionM verClasificacionN">
      <legend>Cultivos declarados</legend>
      <table id="tblCultivos">         <?php
         foreach($cultivosDossier as $item){
         $nombre=$item['nombre_comun'].' (<i>'.$item['nombre_cientifico'].'</i>)';
         echo $cf->imprimirLineaCultivo($item['id_solicitud_cultivo'],$nombre);
         }
         ?>

      </table>
   </fieldset>

   <form id='frmNuevaSolicitud5' data-rutaAplicacion='dossierFertilizante' data-opcion='guardarPasosSolicitud'>
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="paso_solicitud" name="paso_solicitud" value="P5" />

      <fieldset>
         <legend>Especificaciones para la aplicación</legend>

         <div data-linea="1">
            <label for="dosis"> Dosis:</label>
            <input value="<?php echo $datosGenerales['dosis'];?>" name="dosis" type="text" id="dosis" maxlength="10" data-er="^[0-9]+$" />
         </div>
         <div data-linea="1">

            <select name="unidadDosis" id="unidadDosis">
               <option value="">Seleccione....</option><?php
               foreach ($unidadesMedidaDosis as $key=>$item){
               if(strtoupper($item['id_unidad_medida']) == strtoupper($datosGenerales['unidad_dosis'])){
               echo '<option value="' . $item['id_unidad_medida'] . '" selected="selected">' . $item['nombre'] . '</option>';
               }else{
               echo '<option value="' . $item['id_unidad_medida'] . '">' . $item['nombre'] . '</option>';
               }
               }
               ?>
            </select>
         </div>
         <div data-linea="4" class="verClasificacionB verClasificacionG verClasificacionH verClasificacionI verClasificacionM verClasificacionN">
            <label for="epoca_aplicacion" class="opcional">Epoca de Aplicación:</label>
            <textarea class="justificado" data-distribuir="no" name="epoca_aplicacion" id="epoca_aplicacion" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo htmlspecialchars($datosGenerales['epoca_aplicacion']);?></textarea>
         </div>
         <div data-linea="5" class="verClasificacionB verClasificacionC verClasificacionH verClasificacionI verClasificacionJ verClasificacionN">
            <label for="frecuencia_aplicacion">Frecuencia de Aplicación:</label>
            <textarea class="justificado" data-distribuir="no" name="frecuencia_aplicacion" id="frecuencia_aplicacion" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo htmlspecialchars($datosGenerales['frecuencia_aplicacion']);?></textarea>
         </div>
         <div data-linea="6">
            <label for="metodo_aplicacion" class="opcional">Método de aplicación:</label>
            <textarea class="justificado" data-distribuir="no" name="metodo_aplicacion" id="metodo_aplicacion" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo htmlspecialchars($datosGenerales['metodo_aplicacion']);?></textarea>
         </div>
         <div data-linea="7">
            <label for="condiciones_aplicacion" class="opcional">Condiciones de Aplicación:</label>
            <textarea class="justificado" data-distribuir="no" name="condiciones_aplicacion" id="condiciones_aplicacion" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo htmlspecialchars($datosGenerales['condiciones_aplicacion']);?></textarea>
         </div>
         <div data-linea="8">
            <label for="compatibilidad" class="opcional">Compatibilidad :</label>
            <textarea class="justificado" data-distribuir="no" name="compatibilidad" id="compatibilidad" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo htmlspecialchars($datosGenerales['compatibilidad']);?></textarea>
         </div>
         <div data-linea="9">
            <label for="fitotoxicidad" class="opcional">Fitotoxicidad:</label>
            <textarea class="justificado" data-distribuir="no" name="fitotoxicidad" id="fitotoxicidad" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo htmlspecialchars($datosGenerales['fitotoxicidad']);?></textarea>
         </div>
      </fieldset>

      <button type="submit" class="guardar">Guardar solicitud</button>
   </form>

</div>

<div class="pestania" id="P6" style="display: block;">
   <form id='frmNuevaSolicitud6' data-rutaAplicacion='dossierFertilizante' data-opcion='guardarPasosSolicitud'>
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="paso_solicitud" name="paso_solicitud" value="P6" />
      <fieldset>
         <legend>Requisitos específicos</legend>
         <div class="justificado">
            <label for="metodos_analisis" class="opcional">Métodos de Análisis:</label>
            <textarea name="metodos_analisis" id="metodos_analisis" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo htmlspecialchars($datosGenerales['metodos_analisis']);?></textarea>
         </div>
         <div class="justificado">
            <label for="envase_producto" class="opcional">Envase del producto a comercializar :</label>
            <textarea name="envase_producto" id="envase_producto" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo htmlspecialchars($datosGenerales['envase_producto']);?></textarea>
         </div>
         <div class="justificado verClasificacionE verClasificacionF verClasificacionH verClasificacionI">
            <label for="materia_organica" class="opcional">Materia Orgánica Total  :</label>
            <textarea name="materia_organica" id="materia_organica" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo htmlspecialchars($datosGenerales['materia_organica']);?></textarea>
         </div>
         <div class="justificado verClasificacionE verClasificacionF verClasificacionH verClasificacionI">
            <label for="materia_prima_organica" class="opcional">Declaración de materias primas utilizadas en la materia orgánica  :</label>
            <textarea name="materia_prima_organica" id="materia_prima_organica" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo htmlspecialchars($datosGenerales['materia_prima_organica']);?></textarea>
         </div>

         <div data-linea="1" class="verClasificacionE verClasificacionF verClasificacionH verClasificacionI">
            <label for="carbono"> Carbono:</label>
            <input value="<?php echo $datosGenerales['carbono'];?>" name="carbono" type="text" id="carbono" class="esNumerico" maxlength="10" data-er="^[0-9]+$" />
         </div>
         <div data-linea="1" class="verClasificacionE verClasificacionF verClasificacionH verClasificacionI">
            <label>%</label>
         </div>
         <div data-linea="2" class="verClasificacionE verClasificacionF verClasificacionH verClasificacionI">
            <label for="nitrogeno"> Nitrógeno:</label>
            <input value="<?php echo $datosGenerales['nitrogeno'];?>" name="nitrogeno" type="text" id="nitrogeno" class="esNumerico" maxlength="10" data-er="^[0-9]+$" />
         </div>
         <div data-linea="2" class="verClasificacionE verClasificacionF verClasificacionH verClasificacionI">
            <label>%</label>
         </div>
         <div data-linea="3" class="verClasificacionE verClasificacionF verClasificacionH verClasificacionI">
            <label for="humedad_minima">Humedad mínima:</label>
            <input value="<?php echo $datosGenerales['humedad_minima'];?>" name="humedad_minima" type="text" id="humedad_minima" class="esNumerico" maxlength="10" data-er="^[0-9]+$" />
         </div>
         <div data-linea="3" class="verClasificacionE verClasificacionF verClasificacionH verClasificacionI">
            <label>%</label>
         </div>
         <div data-linea="4" class="verClasificacionE verClasificacionF verClasificacionH verClasificacionI">
            <label for="humedad_maxima">Humedad máxima:</label>
            <input value="<?php echo $datosGenerales['humedad_maxima'];?>" name="humedad_maxima" type="text" id="humedad_maxima" class="esNumerico" maxlength="10" data-er="^[0-9]+$" />
         </div>
         <div data-linea="4" class="verClasificacionE verClasificacionF verClasificacionH verClasificacionI">
            <label>%</label>
         </div>

      </fieldset>

      <button type="submit" class="guardar">Guardar solicitud</button>
   </form>
</div>

<div class="pestania" id="P7" style="display: block;">
   <form id='frmNuevaSolicitud7' data-rutaAplicacion='dossierFertilizante' data-opcion='guardarPasosSolicitud'>
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="paso_solicitud" name="paso_solicitud" value="P7" />

      <fieldset>
         <legend>Requisitos específicos</legend>
         <div class="justificado verClasificacionE verClasificacionF verClasificacionH verClasificacionI">
            <label for="proceso_fabricacion">Proceso de fabricación: </label>
            <textarea name="proceso_fabricacion" id="proceso_fabricacion" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo htmlspecialchars($datosGenerales['proceso_fabricacion']);?></textarea>
         </div>
         <div class="justificado verClasificacionE verClasificacionF">
            <label for="capacidad_neutralizadora">Capacidad neutralizadora: </label>
            <textarea name="capacidad_neutralizadora" id="capacidad_neutralizadora" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo htmlspecialchars($datosGenerales['capacidad_neutralizadora']);?></textarea>
         </div>
         <div class="justificado">
            <label for="restricciones_uso" class="opcional">Restricciones de uso  :</label>
            <textarea name="restricciones_uso" id="restricciones_uso" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo htmlspecialchars($datosGenerales['restricciones_uso']);?></textarea>
         </div>
         <div class="justificado">
            <label for="eliminacion_productos" class="opcional">Eliminación de productos: </label>
            <textarea name="eliminacion_productos" id="eliminacion_productos" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo htmlspecialchars($datosGenerales['eliminacion_productos']);?></textarea>
         </div>
         <div class="justificado">
            <label for="metodos_cultivo" class="opcional">Métodos y medios de cultivo: </label>
            <textarea name="metodos_cultivo" id="metodos_cultivo" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo htmlspecialchars($datosGenerales['metodos_cultivo']);?></textarea>
         </div>
      </fieldset>

      <button type="submit" class="guardar">Guardar solicitud</button>
   </form>
</div>

<div class="pestania" id="P8" style="display: block;">

   <form id="frmAnexos" data-rutaAplicacion="dossierFertilizante" data-opcion="guardarArchivoAnexo">
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>">
      <input type="hidden" id="fase" name="fase" value="solicitud">

      <fieldset>
         <legend>Carga de archivos anexos</legend>

         <div data-linea="1">
            <label for="tipoArchivo" class="opcional">Tipo de Archivo:</label>
            <select name="tipoArchivo" id="tipoArchivo" class="verElementoArchivo" required>
               <option value="">Seleccione....</option>               <?php
               foreach ($tipoAnexos as $key=>$item){
               echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
               }
               ?>
            </select>
         </div>
         <hr />
         <div data-linea="2">
         <label for="referencia" class="opcional">Referencia para el documento:</label>
         <input value="" type="text" id="referencia" class="verElementoArchivo" name="referencia" placeholder="ponga la referencia" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required />
         </div>
         <div data-linea="3">
            <input type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />

            <input type="file" class="archivo" accept="application/msword | application/pdf | image/*" disabled="disabled" />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
            <button type="button" class="subirArchivo adjunto verElementoArchivo" data-rutaCarga="<?php echo $pathAnexo;?>" disabled="disabled">Subir archivo</button>
            
         </div>

      </fieldset>

   </form>

   <fieldset>
      <legend>Archivos subidos</legend>
      <input type="hidden" id="tabla_archivos_codigos" name="tabla_archivos_codigos" />
      <table id="tabla_archivos" class="tabla" style="width:100%">
         <thead>
            <tr>
               <th style="display:none">Codigo</th>
               <th width="40%">Tipo Documento</th>
               <th width="40%">Referencia</th>
               <th width="18%"></th>
            </tr>
         </thead>
         <tbody></tbody>

      </table>


   </fieldset>

   <form id="frmFinAnexos" data-rutaAplicacion="dossierFertilizante" data-opcion="guardarPasosSolicitud">
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>">
      <input type="hidden" id="paso_solicitud" name="paso_solicitud" value="P8" />
      <button type="submit" class="guardar">Guardar solicitud</button>
   </form>
</div>

<div class="pestania" id="P9" style="display: block;">

	<form id="frmFinalizarSolicitud" data-rutaAplicacion="dossierFertilizante" data-opcion="finalizarSolicitud" data-accionEnExito='ACTUALIZAR'>
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="id_flujo" name="id_flujo" value="<?php echo $id_flujo;?>" />

      <fieldset>
         <legend>Finalizar solicitud</legend>

         <div class="justificado">
            <label >Condiciones de la información:</label>
            <br />
            <label >            <?php
																						 echo $declaracionLegal['pie'];
                                                                   ?>
            </label>
         </div>
         <div data-linea="2">
            <label>            
					<?php
										 echo '<a href='.$declaracionLegal['encabezado'].' target="_blank">Lea información confidencial</a>';
                    ?>
            </label>
         </div>
         <hr />
         <div data-linea="3" class="ocultarOtros">
            <label for="boolAcepto">Acepto las condiciones</label>
				<input type="checkbox" id="boolAcepto" name="boolAcepto" value="NO">
           
         </div>
      </fieldset>
      <button id="btnFinalizar" type="button" class="guardar">Finalizar</button>
   </form>
	
	<form id='frmVistaPreviaSolicitud' data-rutaAplicacion='dossierFertilizante' data-opcion='' >		
		<button id="btnVistaPreviaSolicitud" type="button" class="documento btnVistaPreviaSolicitud">Generar vista previa solicitud</button>
		<a id="verReporteSolicitud" href="" target="_blank" style="display:none">Ver archivo</a>
	</form>
	

</div>


<script type="text/javascript" src="aplicaciones/ensayoEficacia/funciones/generales.js"></script>


<script type="text/javascript">

	var solicitud=<?php echo json_encode($datosGenerales); ?>;
	var sitiosAreas=<?php echo json_encode($sitiosAreas); ?>;
	var operadoresFabricantes=<?php echo json_encode($operadoresFabricantes); ?>;


	var fabricantesDossier =<?php echo json_encode($fabricantesDossier); ?>;

   var registroProductosMatriz=<?php echo json_encode($registroProductosMatriz); ?>;
	var composicionIA=<?php echo json_encode($composicionIA); ?>;
   var nombreProductos=<?php echo json_encode($liNombreProductos); ?>;

	var catalogoUsos=<?php echo json_encode($catalogoUsos); ?>;
	

	var archivosAnexos=<?php echo json_encode($archivosAnexos); ?>;

	var paisEcuador=<?php echo json_encode($paisEcuador); ?>;

	var subTiposProducto=<?php echo json_encode($subTiposProducto); ?>;



	$("document").ready(function(){
		construirAnimacion(".pestania");
		distribuirLineas();

		acciones("#frmTipoClasificacion", "#tblTipoClasificacion");
		acciones("#frmCultivos", "#tblCultivos");

		//deshabilita el boton siguiente de desplamiento para obligar a guardar el documento
		$('.bsig').attr("disabled", "disabled");

		//habilita los botones según estado del documento

		try{

			reconocerNivel(solicitud.nivel);

			objetivoConstruir();

			

			verSitioPropioFabricante(solicitud.id_sitio);


			verDatosDatosRepresentanteTecnico();


			verAreaTipoFabricante(true);

			llenarFabricantes(fabricantesDossier);


			llenarComposicion(composicionIA);

			verEstadoFisico(solicitud.estado_fisico);

			verOpcionesSegunClasificacion();

			verArchivosAnexos(archivosAnexos);

			verSegunProducto();

		}catch(e){}

	});



	//******************************************* ANEXOS **********************************************************
	$("#objetivo").change(function(){
		objetivoConstruir();
	});

	function objetivoConstruir(){
		vobjetivo=$("#objetivo").val();
		$("#varObjetivo").val(vobjetivo);

		if (vobjetivo=="DF_PCL"){
			$(".verRegistroClon").show();
		}
		else{
			$(".verRegistroClon").hide();
		}

	}

	$("#referencia").keyup(function(){
		if($(this).val().trim()!=""){
			$(".archivo").removeAttr("disabled");
			$("button.subirArchivo").removeAttr("disabled");
		}
		else{
			$(".archivo").attr("disabled", "disabled");
			$("button.subirArchivo").attr("disabled", "disabled");
		}
	});



	$('button.subirArchivo').click(function (event) {
		event.preventDefault();
		if($('#tipoArchivo').val()==''){
			mostrarMensaje("Favor especifique el tipo de archivo", "FALLO");
			return;
		}
		var str=$("#referencia").val().trim();

		str=str.replace(/[^a-zA-Z0-9.]+/g,'');

		var nombre_archivo = solicitud.identificador+"_DF_"+$('#tipoArchivo').val()+"_"+solicitud.id_solicitud+"_"+str;

		var boton = $(this);
		var archivo = boton.parent().find(".archivo");
		var rutaArchivo = boton.parent().find(".rutaArchivo");
		var extension = archivo.val().split('.');
		var estado = boton.parent().find(".estadoCarga");

		if (extension[extension.length - 1].toUpperCase() == 'PDF') {

			subirArchivo(
				 archivo
				 , nombre_archivo
				 , boton.attr("data-rutaCarga")
				 , rutaArchivo
				 , new carga(estado, archivo, boton,rutaArchivo)

			);
		} else {
			estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
			archivo.val("");
		}


	});
	function carga(estado, archivo, boton,rutaArchivo) {
		this.esperar = function (msg) {
			estado.html("Cargando el archivo...");
			archivo.addClass("amarillo");
		};

		this.exito = function (msg) {
			estado.html("El archivo ha sido cargado.");
			archivo.removeClass("amarillo");
			archivo.addClass("verde");
			boton.attr("disabled", "disabled");

			ejecutarJson($('#frmAnexos'), new exitoAnexo());
		};

		this.error = function (msg) {
			estado.html(msg);
			archivo.removeClass("amarillo");
			archivo.addClass("rojo");
		};
	}

  function exitoAnexo(){
		this.ejecutar=function (msg){
			mostrarMensaje(msg.mensaje, "EXITO");
			$("#referencia").val('');
			$(".archivo").val('');
			$(".rutaArchivo").val('');
			$(".archivo").attr("disabled", "disabled");
			$(".subirArchivo").attr("disabled", "disabled");
			verArchivosAnexos(msg.datos);

		};
	}


	function verArchivosAnexos(items){
		$('#tabla_archivos tbody > tr').remove();
		var strCodigos='';
		for(var i in items){
				var item=items[i];
				var fila='<tr class="modo2">'+
				'<td style="display:none">'+item.codigo+'</td>'+
				'<td>'+item.nombre+'</td>'+
				'<td><a href="'+ item.path+'" target="_blank">'+item.referencia+'</a></td>'+
				'<td>' +
					'<form id="borrarAnexoEE" class="borrar" data-rutaAplicacion="dossierFertilizante" data-opcion=""  >' +
						'<input type="hidden" id="id_solicitud" name="id_solicitud" value="' + item.id_solicitud + '" >' +
						'<input type="hidden" id="id_solicitud_anexos" name="id_solicitud_anexos" value="' + item.id_solicitud_anexos + '" >' +
						'<button type="button" class="icono btnBorraFilaArchivoAnexo verElementoArchivo"></button>' +
					'</form>' +
				'</td>'+
				'</tr>';

				$('#tabla_archivos tbody').append(fila);
				strCodigos=strCodigos+','+item.codigo;
		}
		$('#tabla_archivos_codigos').val(strCodigos);
	}

	$("#tabla_archivos").off("click",".btnBorraFilaArchivoAnexo").on("click",".btnBorraFilaArchivoAnexo",function(event){
		event.preventDefault();
		var form=$(this).parent();
		var param={opcion_llamada:'borraFilaArchivoAnexo',id_solicitud:form.find("#id_solicitud").val(),id_solicitud_anexos:form.find("#id_solicitud_anexos").val()};
		llamarServidor('dossierFertilizante','atenderLlamadaServidor',param,verArchivosAnexos);
	});


	$("#frmFinAnexos").submit(function(event){

		incrementarNivel($(this));
		event.preventDefault();

		var error = false;

		borrarMensaje();

		$(this).append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario


		if (!error){
			ejecutarJson($(this));
			actualizaBotonSiguiente($(this), nivelActual);

		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}

	});

	//**************************************** campos numericos **********************************
	$('.esNumerico').numeric();

	//**************************** FORMULARIOS **********************



	$('#btnGuardarPrimero').click(function(event){
		event.preventDefault();
		$("#estado").html("");

		var form=$(this).parent();

		error = false;
		verificarCamposVisiblesNulos(['#tipo_producto','#dirReferencia','#ciLegal','#correoLegal','#id_sitio','#id_area','#ci_representante_tecnico','#objetivo','#clon_registro_madre','#declaracion_juramentada']);

		if(error){
			$("#estado").html("Por favor revise sus datos, llene los obligatorios.").addClass("alerta");
		}
		else{
			if(jQuery.isEmptyObject(solicitud)){
				//nuevo registro
				form.attr('data-opcion', 'guardarNuevaSolicitud');
				form.attr('data-destino', 'detalleItem');
				form.attr('data-accionEnExito', 'ACTUALIZAR');
				form.append("<input type='hidden' id='nivel' name='nivel' value='1' />");
				abrir(form, event, true);
			}
			else{
				//es actualización

				incrementarNivel(form,solicitud.nivel);
				form.attr('data-opcion', 'guardarPasosSolicitud');
				form.attr('data-destino', 'detalleItem');
				form.attr('data-accionEnExito', '');
				form.append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario
				ejecutarJson(form);
				actualizaBotonSiguiente(form, nivelActual,solicitud.nivel);
			}
		}

	});

	$('button.eliminar_solicitud').click(function (event) {
		event.preventDefault();
		var form=$(this).parent();

		form.attr('data-opcion', 'notificarEliminarSolicitud');
		form.attr('data-destino', 'detalleItem');
		form.attr('data-accionEnExito', 'ACTUALIZAR');

		$("#estado").html("");

		abrir(form, event, true); //Se ejecuta ajax, busqueda de sitios

	});


	//************************************* GUARDADO DE LOS PASOS ***************************************

	$("#frmFabricantes").submit(function(event){
		incrementarNivel($(this),solicitud.nivel);
		event.preventDefault();

		var error = false;
		if($('#tblExtranjeros >tbody >tr').length == 0)
			error = true;

		$(this).append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario

		if (!error){
			ejecutarJson($(this));
			actualizaBotonSiguiente($(this), nivelActual,solicitud.nivel);
		}else{
			$("#estado").html("Por favor revise sus datos, debe declarar al menos un fabricante.").addClass("alerta");
		}
	});

	$("#frmNuevaSolicitud3").submit(function(event){

		incrementarNivel($(this),solicitud.nivel);
		event.preventDefault();

		error = false;
		verificarCamposVisiblesNulos(['#nombreProducto','#id_tipo_producto','#id_subtipo_producto','#estado_fisico','#uso','#cantidad','#unidad']);
		if($('#tblComposicion >tbody >tr').length == 0)
			error = true;
		if($('#tblTipoClasificacion >tbody >tr').length == 0)
			error = true;

		if(!error){
			$(this).append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />");
			$(this).append("<input type='hidden' id='nombreProducto' name='nombreProducto' value='"+$('#nombreProducto').val()+"' />");
			$(this).append("<input type='hidden' id='id_tipo_producto' name='id_tipo_producto' value='"+$('#id_tipo_producto').val()+"' />");
			$(this).append("<input type='hidden' id='id_subtipo_producto' name='id_subtipo_producto' value='"+$('#id_subtipo_producto').val()+"' />");
			
			ejecutarJson($(this));
			actualizaBotonSiguiente($(this), nivelActual,solicitud.nivel);
			verOpcionesSegunClasificacion();
		}else{
			$("#estado").html("Por favor revise los campos obligatorios, las tablas deben tener al menos un item.").addClass("alerta");
		}
	});

	$("#frmNuevaSolicitud4").submit(function(event){

		incrementarNivel($(this),solicitud.nivel);
		event.preventDefault();

		error = false;
		verificarCamposVisiblesNulos(['#vida_util','#unidad_vida_util','#densidad','#unidad_densidad','#ph','#solubilidad','#granulometria','#corrosividad','#materia_prima','#modo_preparacion','#ambito_aplicacion','#modo_aplicacion']);

		$(this).append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario

		if (!error){
			ejecutarJson($(this));
			actualizaBotonSiguiente($(this), nivelActual,solicitud.nivel);
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$("#frmNuevaSolicitud5").submit(function(event){
		incrementarNivel($(this),solicitud.nivel);
		event.preventDefault();

		error = false;
		verificarCamposVisiblesNulos(['#dosis','#unidadDosis','#epoca_aplicacion','#frecuencia_aplicacion','#metodo_aplicacion','#condiciones_aplicacion','#compatibilidad','#fitotoxicidad']);

		if ($('#tblCultivos').is(":visible")) {
			if($('#tblCultivos >tbody >tr').length == 0)
				error = true;
		}

		$(this).append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario

		if (!error){
			ejecutarJson($(this));
			actualizaBotonSiguiente($(this), nivelActual,solicitud.nivel);
		}else{
			$("#estado").html("Por favor revise los campos obligatorios").addClass("alerta");
		}
	});

	$("#frmNuevaSolicitud6").submit(function(event){
		incrementarNivel($(this),solicitud.nivel);
		event.preventDefault();

		error = false;
		verificarCamposVisiblesNulos(['#metodos_analisis','#envase_producto','#materia_organica','#materia_prima_organica','#carbono','#nitrogeno','#humedad_minima','#humedad_maxima']);

		$(this).append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario

		if (!error){
			ejecutarJson($(this));
			actualizaBotonSiguiente($(this), nivelActual,solicitud.nivel);
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$("#frmNuevaSolicitud7").submit(function(event){

		var elInferior=$(this);
		incrementarNivel(elInferior,solicitud.nivel);
		event.preventDefault();

		error = false;
		verificarCamposVisiblesNulos(['#proceso_fabricacion','#capacidad_neutralizadora','#restricciones_uso','#eliminacion_productos','#metodos_cultivo']);

		$(this).append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario

		if (!error){

			ejecutarJson($(this));
			actualizaBotonSiguiente(elInferior, nivelActual,solicitud.nivel);
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});


	$('#btnFinalizar').click(function (event) {
		event.preventDefault();

		if($("#boolAcepto").is(':checked')){
			borrarMensaje();
			var form=$(this).parent();
			var esClon='t';

			form.append("<input type='hidden' id='es_clon' name='es_clon' value='"+esClon+"' />");


			form.attr('data-destino', 'detalleItem');

			mostrarMensaje('Generando documentación','');

			abrir(form, event, true); //Se ejecuta ajax, busqueda de sitios
		}
		else
			mostrarMensaje('Para finalizar acepte las condiciones','FALLO');
	});


	$('.bsig').click(function () {
		$("#estado").html('');
		$("#estado").removeClass();

	});

	$('.bant').click(function () {
		$("#estado").html('');
		$("#estado").removeClass();

	});

	//************************************* VISTA SEGUN CLASIFICACIONES ***************************
	function verOpcionesSegunClasificacion(){
		//oculta los campos marcados con las clases
		$('.verClasificacionA, .verClasificacionB, .verClasificacionC, .verClasificacionD, .verClasificacionE, .verClasificacionF, .verClasificacionG, .verClasificacionH, .verClasificacionI, .verClasificacionJ, .verClasificacionK,.verClasificacionl, .verClasificacionM, .verClasificacionN').hide();
		$("#tblTipoClasificacion tr").find('td:eq(0)').each(function () {

			//obtenemos el codigo de la celda
			codigo = $(this).html();

			//comparamos código y habilitamos
			if(codigo=='A')
				$('.verClasificacionA').show();
			if(codigo=='B')
				$('.verClasificacionB').show();
			if(codigo=='C')
				$('.verClasificacionC').show();
			if(codigo=='D')
				$('.verClasificacionD').show();
			if(codigo=='E')
				$('.verClasificacionE').show();
			if(codigo=='F')
				$('.verClasificacionF').show();
			if(codigo=='G')
				$('.verClasificacionG').show();
			if(codigo=='H')
				$('.verClasificacionH').show();
			if(codigo=='I')
				$('.verClasificacionI').show();
			if(codigo=='J')
				$('.verClasificacionJ').show();
			if(codigo=='K')
				$('.verClasificacionK').show();
			if(codigo=='L')
				$('.verClasificacionL').show();
			if(codigo=='M')
				$('.verClasificacionM').show();
			if(codigo=='N')
				$('.verClasificacionN').show();

		});

	}
	//**********************************************************************************************

	function verSegunProducto(){
		vproducto=$("#tipo_producto").val();
		$('.siImportado, .siNacional').hide();
		if (vproducto=="DF_IMP"){
			$('.siImportado').show();
			$('.siNacional').hide();
			verAreaTipoFabricante('E');
		}
		else{
         $('.siImportado').hide();
         $('.siNacional').show();
         verAreaTipoFabricante('N');
		}
	}



	$('#tipo_producto').change(function(){
		verSegunProducto();
	});
	//********************* Subtipo de productos ************************************************************

	$('#id_tipo_producto').change(function(){
		llenarComboSubTipos($('#id_tipo_producto').val());
	});

	function llenarComboSubTipos(tipoProd){
		
		$('#id_subtipo_producto').children('option').remove();
		
		$('#id_subtipo_producto').append($("<option></option>").attr("value","").text("Seleccione...."));

		if(tipoProd==null)
			return;
	
		if((tipoProd!=='') && (subTiposProducto!=null) && (subTiposProducto.length>0))
		{
			$.each(subTiposProducto, function(key, value) {
				if(value.id_tipo_producto==tipoProd){
					$('#id_subtipo_producto')
					.append($("<option></option>")
					.attr("value",value.id_subtipo_producto)
					.text(value.nombre));
				}
			});

		}
	}
	
	//*********************  Sitios y areas *****************************************************************
	$('#id_sitio').change(function(){

		llenarComboAreas($(this).val());

	});

	function llenarComboAreas(sitio){

		$('#id_area').children('option').remove();
		$('#ci_representante_tecnico').children('option').remove();
		borrarDatosRepresentateTecnico();

		$('#id_area').append($("<option></option>").attr("value","").text("Seleccione...."));

		verSitioPropioFabricante(sitio);

		//busco el sitio
		var arrSitio = {};
		for(var i in sitiosAreas){
			if(sitiosAreas[i].id_sitio==sitio){
				arrSitio=sitiosAreas[i]['areas'];
			}
		}
		if(arrSitio!=null && arrSitio.length>0)
		{
			$.each(arrSitio, function(key, value) {
				$('#id_area')
				.append($("<option></option>")
				.attr("value",value.id_area)
				.text(value.nombre_area));
			});

		}

	}

	function verSitioPropioFabricante(sitio){
		for(var i in sitiosAreas){
			if(sitiosAreas[i].id_sitio==sitio){

				//pongo la direccion del sitio en fabricante por si mismo
				$('#fn_sitio_nombre').val(sitiosAreas[i].nombre_lugar);
				$("#fn_sitio_direccion").val(sitiosAreas[i].direccion);
			}
		}
	}

	$('#id_area').change(function(event){
		if($('#id_area').val()=="")
		{
			$('#ci_representante_tecnico').children('option').remove();

		}
		else{
			var param={opcion_llamada:'representantesTecnicosPorArea',id_area:$('#id_area').val()};
			llamarServidor('dossierFertilizante','atenderLlamadaServidor',param,llenarComboRepresentanteTecnico);
		}
	});

	function llenarComboRepresentanteTecnico(items){
		$('#ci_representante_tecnico').children('option').remove();
		borrarDatosRepresentateTecnico();
		$('#ci_representante_tecnico').append($("<option></option>").attr("value","").text("Seleccione...."));
		if(items!=null && items.length>0){
			$.each(items, function(key, value) {
				$('#ci_representante_tecnico')
				.append($("<option></option>")
				.attr("value",value.identificacion_representante)
				.text(value.nombre_representante));
			});
		}

	}

	$("#ci_representante_tecnico").change(function (event) {
		if($('#ci_representante_tecnico').val()=="")
		{
			borrarDatosRepresentateTecnico();
		}
		else{
			var param={opcion_llamada:'datosRepresentanteTecnico',id_area:$('#id_area').val(),ci_representante_tecnico:$("#ci_representante_tecnico").val()};
			llamarServidor('dossierFertilizante','atenderLlamadaServidor',param,llenarDatosRepresentanteTecnico);
		}
	});

	function borrarDatosRepresentateTecnico(){
		$('#tituloTecnico').val("");
		$('#registroSenesyt').val("");
	}

	function llenarDatosRepresentanteTecnico(item){
		if(item==null){
			$('#tituloTecnico').val("");
			$('#registroSenesyt').val("");
		}
		else{
			$('#tituloTecnico').val(item.titulo_academico);
			$('#registroSenesyt').val(item.identificacion_representante);
		}
	}

	function verDatosDatosRepresentanteTecnico(){
		//busco el sitio
		var arrSitio = {};
		for(var i in sitiosAreas){

			if(sitiosAreas[i].id_sitio==$('#id_sitio').val()){
				arrSitio=sitiosAreas[i]['areas'];
				//busco en area
				for(var j in arrSitio){
					if(arrSitio[j].id_area==$('#id_area').val()){
						var arrArea=arrSitio[j]['representates_tecnicos'];
						for(var k in arrArea){
							if(arrArea[k].identificacion_representante==$('#ci_representante_tecnico').val()){
								llenarDatosRepresentanteTecnico(arrArea[k]);
								break;
							}
						}
						break;
					}
				}
				break;
			}
		}
	}

	//************  TIPOS DE FABRICANTE *******

	$("[name='es_fabricante']").change(function(){
		if($(this).val()=="SI"){
			verAreaTipoFabricante('N');
		}
		else{
			verAreaTipoFabricante('C');
		}
	});


	function verAreaTipoFabricante(tipo){
		if(tipo==null || tipo=='' || tipo=='N')
		{
			$("#es_fabricanteSI").prop('checked', true);
			$("#es_por_contratoVer").hide();
			$(".fabricanteNacional").show();
			$(".fabricanteContrato").hide();
			$(".siImportado").hide();

		}
		else if (tipo=='C'){
			$("#es_por_contratoSI").prop('checked', true);
			$("#es_por_contratoVer").show();
			$(".fabricanteContrato").show();
			$(".fabricanteNacional").hide();
			$(".siImportado").hide();

		}
		else {
			$(".fabricanteNacional").hide();
			$(".fabricanteContrato").hide();
			$(".fabricanteExtranjero").show();

		}
		distribuirLineas();
	}


	//******************** Opciones para fabricante por contrato ************************************

	$("#fc_ruc").autocomplete({
		source: operadoresFabricantes,
		minLength: 2
	});

	$("#fc_ruc").change(function(){
		//busca los datos del operador
		var param={opcion_llamada:'datosOperadorSitiosAreas',identificador:$('#fc_ruc').val()};
		llamarServidor('dossierFertilizante','atenderLlamadaServidor',param,llenarDatosFabricanteContrato);
		
	});

	var datosOperadorContrato={};

	function llenarDatosFabricanteContrato(item){
		$('#fc_id_sitio').children('option').remove();

		
		
		if(item!=null){

			datosOperadorContrato=item;
			$('#fc_razon_social').val(item.razon_social);
			//llena los sitios

			$('#fc_id_sitio').append($("<option></option>").attr("value","").text("Seleccione...."));
			if(item.sitios!=null && item.sitios.length>0){
				$.each(item.sitios, function(key, value) {
					$('#fc_id_sitio')
					.append($("<option></option>")
					.attr("value",value.id_sitio)
					.text(value.nombre_lugar));
				});
			}
		}
	}

	var sitioContrato={};
	$("#fc_id_sitio").change(function(){



		if(datosOperadorContrato==null || datosOperadorContrato.sitios==null){
		}
		else{
			//Busca el sitio

			for(var i in datosOperadorContrato.sitios){

				if(datosOperadorContrato.sitios[i].id_sitio==$('#fc_id_sitio').val()){
					sitioContrato=datosOperadorContrato.sitios[i];
					break;
				}
			}

			if(sitioContrato!=null){
				//lena las area y la direccion del sitio
				$('#fc_sitio_direccion').val(sitioContrato.nombre_lugar);

			}
		}
	});




	//******************** FABRICANTE EXTRANJERO *********************************************************

	$("#btnAddExtranjero").click(function (event) {
		var param={};
		if($('#tipo_producto').val()=='DF_NAC'){
			if($("#es_fabricanteSI").is(':checked')){
				var param={opcion_llamada:'agregarFabricanteDossier',id_solicitud:solicitud.id_solicitud,ruc:solicitud.identificador,sitio:$("#id_sitio").val(),tipo:'N', direccion:$("#direccion").val(),
					razon:$("#fn_razon_social").val(),id_pais:paisEcuador};
			}
			else{

				var param={opcion_llamada:'agregarFabricanteDossier',id_solicitud:solicitud.id_solicitud,ruc:$("#fc_ruc").val(),sitio:$("#fc_id_sitio").val(),tipo:'C', direccion:$("#fc_sitio_direccion").val(),
					razon:$("#fc_razon_social").val(),id_pais:paisEcuador};
			}
		}
		else
			var param={opcion_llamada:'agregarFabricanteDossier',id_solicitud:solicitud.id_solicitud,ruc:$("#id_extranjero").val(),sitio:0,tipo:'E', direccion:$("#ex_direccion").val(),
				razon:$("#razon_extranjero").val(),id_pais:$("#ex_pais").val()};

		llamarServidor('dossierFertilizante','atenderLlamadaServidor',param,llenarFabricantes);
	});


	function llenarFabricantes(items){
		$('#tblExtranjeros tbody tr').remove();
		if(!jQuery.isEmptyObject(items)){
			for(var i in items){
				var fab=items[i];
				agregarExtranjero(fab);
			}
		}

	}

	function agregarExtranjero(item){
		//busca los nombres del fabricante

		var nuevaFila='<td>'+item.identificador+'</td>';
		nuevaFila+='<td>'+item.empresa+'</td>';
		nuevaFila+='<td>'+item.direccion+'</td>';
		nuevaFila+='<td>'+item.pais+'</td>';
		var tdEliminar='<form id="borrarFila" class="borrar" data-rutaAplicacion="dossierFertilizante" data-opcion="eliminarFabricante"  >' +
						'<input type="hidden" id="id_solicitud_fabricante" name="id_solicitud_fabricante" value="' + item.id_solicitud_fabricante + '" />' +
						'<button type="button" class="icono btnBorraFilaFabricante verElementoFabricante"></button>' +
					'</form>';
		nuevaFila+='<td>'+tdEliminar+'</td>';
		$("#tblExtranjeros").append('<tr>'+nuevaFila+'</tr>');
	}


	$("#tblExtranjeros").off("click",".btnBorraFilaFabricante").on("click",".btnBorraFilaFabricante",function(event){
		
		event.preventDefault();
		var form=$(this).parent();
		var param={opcion_llamada:'borrarFabricanteDossier',id_solicitud:solicitud.id_solicitud,id_solicitud_fabricante:form.find("#id_solicitud_fabricante").val()};
		llamarServidor('dossierFertilizante','atenderLlamadaServidor',param,llenarFabricantes);
	});

	//*************************************** VALIDA NOMBRE DEL PRODUCTO *******************************************



	$("#nombreProducto").autocomplete({
		source: nombreProductos,
		minLength: 2
	});

	function resultadoValidacion(items,elemento){
		borrarMensaje();
		if(!jQuery.isEmptyObject(items)){
			if(items.length>0){
				mostrarMensaje('El nombre '+elemento.val()+' ya esta registrado, intente otro','FALLO');
				elemento.val('');
				elemento.focus();
			}
		}
	}

	//************************************** COMPOSICION DEL PRODUCTO ***********************************************

	$("#agregarElementoComposicion").click(function (event) {
		var param={opcion_llamada:'agregarComposicion',id_solicitud:solicitud.id_solicitud,id_elemento:$('#elementoComposicion').val(),valor:$("#composicionValor").val(),unidad:$("#composicionUnidad").val()};
		llamarServidor('dossierFertilizante','atenderLlamadaServidor',param,llenarComposicion);
	});

	function llenarComposicion(items){
		$('#tblComposicion tbody tr').remove();
		if(!jQuery.isEmptyObject(items)){
			for(var i in items){
				var item=items[i];
				var nuevaFila='<td>'+item.nombre+'</td>';
				nuevaFila+='<td>'+item.cantidad+'</td>';
				nuevaFila+='<td>'+item.codigo+'</td>';
				var tdEliminar='<form id="borrarFila" class="borrar" data-rutaAplicacion="dossierFertilizante" data-opcion=""  >' +
								'<input type="hidden" id="id_solicitud_composicion" name="id_solicitud_composicion" value="' + item.id_solicitud_composicion + '" />' +
								'<button type="button" class="icono btnBorraFilaComposicion verObsComposicion"></button>' +
							'</form>';
				nuevaFila+='<td>'+tdEliminar+'</td>';
				$("#tblComposicion").append('<tr>'+nuevaFila+'</tr>');
			}
		}
		
	}



	$("#tblComposicion").off("click",".btnBorraFilaComposicion").on("click",".btnBorraFilaComposicion",function(event){
		event.preventDefault();

		var form=$(this).parent();

		var param={opcion_llamada:'borrarComposicion',id_solicitud:solicitud.id_solicitud,id_solicitud_composicion:form.find("#id_solicitud_composicion").val()};
		llamarServidor('dossierFertilizante','atenderLlamadaServidor',param,llenarComposicion);
	});

	$("#estado_fisico").change(function(){
		var formulacion=$('#estado_fisico :selected').data('formulacion');
		$('#id_formulacion').val(formulacion);
		
		verEstadoFisico($(this).val());

	});

	function verEstadoFisico(estado){
		if(estado=='DF_SOL'){
			$('.esSolido').show();
		}
		else{
			$('.esSolido').hide();
		}
	}

	$("#ph").change(function(){

		var ph=parseFloat($("#ph").val());
		borrarMensaje();
		if(ph<0 || ph>14){
			mostrarMensaje('El valor debe estar entre 0 y 14','FALLO');
			$("#ph").focus();
			$("#ph").addClass("alertaCombo");
		}
		else{
			$("#ph").removeClass("alertaCombo");
		}

	});





//***************** DATOS DEL CLON *********************
	$("#clon_registro_madre").change(function(){
		actualizarDatosDelClon($(this).val());

	});

	function actualizarDatosDelClon(numeroRegistro){
		if(registroProductosMatriz!=null ){
			for(var i in registroProductosMatriz){

				if(registroProductosMatriz[i].numero_registro==numeroRegistro){
					$('#clon_nombre_madre').val(registroProductosMatriz[i].nombre_comun);
					var numClones=parseInt(registroProductosMatriz[i].clones);
					$('#clon_numero').val(numClones);

					borrarMensaje();
					if(numClones>=3){
						mostrarMensaje('El número máximo permitido de clones para éste producto se ha alcanzado','FALLO');
						$("#clon_registro_madre").val('');
					}

					if(solicitud.producto_nombre==null || solicitud.producto_nombre.length==0)	{
						numClones++;

						$('#producto_nombre').val(registroProductosMatriz[i].nombre_comun+"-"+numClones);
						$('#producto_formulado').html(registroProductosMatriz[i].nombre_comun+"-"+numClones);
					}
					break;
				}
			}
		}
	}
	//seleccion de cultivos
	$("#cultivoNomCien").change(function(){
		$('#cultivoNomComun').val($("#cultivoNomCien :selected").data('comun'));

	});

	//***************************** VISTA PREVIA DOSSIER***************************************
	$('button.btnVistaPreviaDossier').click(function (event) {

		event.preventDefault();

		var form=$(this).parent();
		form.append("<input type='hidden' id='id_solicitud' name='id_solicitud' value='"+solicitud.id_solicitud+"' />"); // añade el nivel del formulario

		form.attr('data-opcion', 'crearCertificado');

		mostrarMensaje("Generando archivo ... ",'FALLO');
		$('#verReporteDossier').hide();
		ejecutarJson(form,new exitoVistaPreviaDossier());


	});


	function exitoVistaPreviaDossier(){
		this.ejecutar=function (msg){
			mostrarMensaje(msg.mensaje, "EXITO");
			$('#verReporteDossier').show();
			$('#verReporteDossier').attr('href',msg.datos);
		};
	}

	//***************************** VISTA PREVIA SOLICITUD***************************************
	$('button.btnVistaPreviaSolicitud').click(function (event) {

		event.preventDefault();

		var form=$(this).parent();
		form.append("<input type='hidden' id='id_solicitud' name='id_solicitud' value='"+solicitud.id_solicitud+"' />"); // añade el nivel del formulario

		form.attr('data-opcion', 'crearSolicitud');

		mostrarMensaje("Generando archivo ... ",'FALLO');
		$('#verReporteSolicitud').hide();
		ejecutarJson(form,new exitoVistaPreviaSolicitud());


	});


	function exitoVistaPreviaSolicitud(){
		this.ejecutar=function (msg){
			JSON.stringify(msg);
			mostrarMensaje(msg.mensaje, "EXITO");
			$('#verReporteSolicitud').show();
			$('#verReporteSolicitud').attr('href',msg.datos);
		};
	}


</script>
