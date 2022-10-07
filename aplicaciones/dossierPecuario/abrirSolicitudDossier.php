<?php 
session_start();

	require_once '../../clases/Conexion.php';
	
	require_once '../../clases/ControladorRegistroOperador.php';
	require_once '../../clases/ControladorCatalogos.php';

	require_once '../../clases/ControladorEnsayoEficacia.php';
	require_once '../../clases/ControladorDossierPecuario.php';
	
	require_once '../../clases/ControladorFinanciero.php';

	
	$idUsuario= $_SESSION['usuario'];			//Es el usuario logeado en la solicitud
	$id_solicitud = $_POST['id'];
	$id_flujo = $_POST['idFlujo'];

	$identificador=$idUsuario;					//Es el duenio del documento, puede variar si ya hay un protocolo y el usuario es alguien de revision, aprobacion, etc..

	$conexion = new Conexion();
	$ce = new ControladorEnsayoEficacia();
	$cr = new ControladorRegistroOperador();
	$cc = new ControladorCatalogos();
	$cp=new ControladorDossierPecuario();
	
	
	$datosGenerales=array();	
	$operador = array();
	$subtipoProductos=array();
	$operadoresFabricantes=array();	
	$ingredientesActivos=array();
	$fabricantesDossier=array();
	$composicionIA=array();
	$dosisProducto=array();
	$efectosSolicitud=array();
	$especiesPecuarios=array();
	$periodosRetirosSolicitud=array();
	$presentacionesSolicitud=array();
	$anexoVector=array();
	$observacionesLaboratorioFarmacos=array();
	
	$datosTablaProcedencia='';

	$es_fabricante=true;
	$es_por_contrato=true;
	
	if($id_solicitud!=null && $id_solicitud!='_nuevo'){

		$datosGenerales=$cp->obtenerSolicitud($conexion, $id_solicitud);
		$identificador=$datosGenerales['identificador'];						//El duenio del documento
		if($datosGenerales['es_fabricante']==null || $datosGenerales['es_fabricante']==''){
			$es_fabricante=false;
			$es_por_contrato=false;
		}
		else if($datosGenerales['es_fabricante']=='N')
			$es_fabricante=true;
		else if($datosGenerales['es_fabricante']=='C'){
			$es_fabricante=false;
			$es_por_contrato=true;
		}
		else{
			$es_fabricante=false;
			$es_por_contrato=false;
		}

		$requiere_preparacion=false;
		if($datosGenerales['requiere_preparacion']=='t')
			$requiere_preparacion=true;
		
		$arrOperadoresFabricantes=$ce->obtenerOperadoresConOperacionesEnEstado($conexion,'IAV',"in ('FRA')","in ('registrado')");	//IAV=Area pecuarios; 37=Operacion fabricante
		
		foreach ($arrOperadoresFabricantes as $key=>$item){
			$a=array();
			$a['value']=$item['identificador'];
			$a['label']='('.$item['identificador'].')'.$item['razon_social'];
			$operadoresFabricantes[]=$a;
		}	
		$fabricantesDossier=$cp->obtenerFabricantesDossier($conexion,$id_solicitud);
		$tieneExtranjero=0;
		foreach($fabricantesDossier as $items){
			if($items['tipo']=='E'){
				$tieneExtranjero=1;
				break;
			}
		}
		
		$datosTablaProcedencia =$cp->imprimirFabricantesDosier($fabricantesDossier);
		$composicionIA=$cp->obtenerComposicionProducto($conexion,$id_solicitud);
		$dosisProducto=$cp->obtenerDosis($conexion,$id_solicitud);
		$efectosSolicitud=$cp->obtenerEfectosNoDeseados($conexion,$id_solicitud);
		$periodosRetirosSolicitud=$cp->obtenerPeriodosDeRetiro($conexion,$id_solicitud);
		$presentacionesSolicitud=$cp->obtenerPresentacion($conexion,$id_solicitud);

		$qCodigoAdicionales = $cp->listarCodigoComplementarioSuplementario($conexion, $id_solicitud);
		$anexos=$cp->listarArchivosAnexos($conexion,$id_solicitud);
		foreach($anexos as $key=>$value){
			$anexoVector[$value['tipo']]=$value;			
		}

		$observacionesLaboratorioFarmacos=$ce->obtenerObservacionesDelFlujo($conexion, 'DP', $id_solicitud,'PFL_DP_DDIACIA');
		
	}
	

	//busca los datos del operador
	$res = $cr->buscarOperador($conexion, $identificador);
	$operador = pg_fetch_assoc($res);
	
	//recupera los subtipos de producto
	$subtipoProductos=$ce->obtenerSubTiposProductos ($conexion, 'IAV','TIPO_VETERINARIO');		
	$sitiosAreas=$cp->obtenerSitiosAreas($conexion, $identificador,'IAV','DP_OPERA');

	$fabricantesExtranjeros=$cp->obtenerFabricantesExtranjeros($conexion,$identificador);

	$clasificaciones=$cp->obtenerClasificacionesDeSubtipos ($conexion);
	$formulaciones=$cp->obtenerFormulacionesPorArea ($conexion,'IAV');

	$unidadesMedida=$ce->obtenerUnidadesMedida($conexion,'DP_COMP');		
	$unidadesMedidaCepas=$ce->obtenerUnidadesMedida($conexion,'DP_COMP,DP_CEPA');		
	$iaGrupos = $ce->listarElementosCatalogo($conexion,'IA_GRUPO');
	$subtiposGrupos=$cp->obtenerSubtiposGrupos($conexion);
	$catalogoUsos=array();
	$res=$cc->listarUsosPorArea($conexion,'IAV');
	while ($fila = pg_fetch_assoc($res)){
		$catalogoUsos[] = $fila;
	}
	$codificacionUsos=$ce->listarElementosCatalogoEx($conexion,'P_USOS');
	$especiesTipo=$ce->listarElementosCatalogo($conexion,'P_ESP_FA');
	$especies=$ce->listarElementosCatalogoEx($conexion,'P_ESPECI');
	$viasAdmin=$ce->listarElementosCatalogoEx($conexion,'P_DOSIS');
	$catalogoViasAdmin=$ce->listarElementosCatalogo($conexion,'P_VIA');
	
	$catalogoUnidades1=$ce->obtenerUnidadesMedida($conexion,'DP_COMP');
	
	$catalogoAux=$ce->obtenerUnidadesMedida($conexion,'DP_VIVO');
	$catalogoUnidades2=array_merge($catalogoAux, $catalogoUnidades1);

	$catalogoUnidadesSustrato=$ce->obtenerUnidadesMedida($conexion,'DP_SUSTRATO');
		
	$catalogoUnidadesTiempo=$ce->listarElementosCatalogo($conexion,'P_TIEMPO');
	$categoriasToxicologicas=$cp->obtenerCatagoriasToxicologicas($conexion,'IAV');
	$efectosNoDeseados=$ce->listarElementosCatalogo($conexion,'P4C07');

	$especiesConsumibles=$cp->obtenerCatalogoEspeciesConsumibles($conexion);
	if(sizeof($especies)>0){
		//filtra solo las especies pecuarias y elimina las instalaciones pecuarias
		$especiesPecuarios=array_filter($especies, function($el) { return $el['nombre3']=='PEF_PEC' && $el['codigo']!='PEE_IP'; });	
	}

	$anexosCapacidad=array();
	$items=$ce->listarElementosCatalogoEx($conexion,'ANEXO_PC');
	foreach($items as $key=>$value){
		$anexosCapacidad[$value['codigo']]=$value;			
	}
		
	$declaracionLegal=$ce->obtenerTitulo($conexion,'DP');

	$items=$cc->obtenerIdLocalizacion($conexion,'Ecuador','PAIS');
	$paisEcuador=0;
	while ($fila = pg_fetch_assoc($items)){		
		if($fila['codigo']=='EC')
			$paisEcuador=$fila['id_localizacion'];
	}
	
	
	
	//****************** ANEXOS **************************************
	$paths=$ce->obtenerRutaAnexos($conexion,'dossierPecuario');
	$pathAnexo=$paths['ruta'];
	
	
?>

<header>
	<h1>Solicitud de dossier pecuario</h1>
</header>

<div id="estado"></div>

<div class="pestania" id="P1" style="display: block;">
	<form id='frmNuevaSolicitud' data-rutaAplicacion='dossierPecuario' data-opcion='guardarNuevaSolicitud' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
		<input type="hidden"  id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>"/>
      <input type="hidden" id="opcion" name="opcion" />

		<?php 
            $cf = new ControladorFinanciero();
            
            if($datosGenerales['estado']== 'verificacion'){
    		    $qOrdenPago = $cf->obtenerOrdenPagoPorIdentificadorSolicitud($conexion, $id_solicitud, 'dossierPecuario');
    		    $ordenPago = pg_fetch_assoc($qOrdenPago);
    		
    		echo '<fieldset>
                    <legend>Información de pago</legend>
                        <div data-linea="7">
						<label>Monto a pagar:</label> <span class="alerta">$ '.$ordenPago['total_pagar'].'</span>
                        </br><a href="'.$ordenPago['orden_pago'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Descargar orden de pago: </a>
					</div>
                </fieldset>';
    		
    		}
		?>
      <fieldset>
         <legend>Información del solicitante</legend>

         <div data-linea="1">
            <label for="tipoRazon" class="opcional">Tipo razón social</label>
            <input value="<?php echo $operador['tipo_operador'];?>" name="tipoRazon" type="text" id="tipoRazon" placeholder="Tipo de razon social" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

         <div data-linea="2">
            <label for="razon" class="opcional">Razón social</label>
            <input value="<?php echo $operador['razon_social'];?>" name="razon" type="text" id="razon" placeholder="Nombre de la empresa" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

         <div data-linea="3">
            <label for="ruc" class="opcional">CI/RUC/PASS</label>
            <input value="<?php echo $operador['identificador'];?>" name="ruc" type="text" id="ruc" placeholder="Nombre de la empresa" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="4">
            <label for="direccion" class="opcional">Dirección</label>
            <input value="<?php echo $operador['direccion'];?>" name="direccion" type="text" id="direccion" placeholder="Direccion" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="5">
            <label for="provincia" class="opcional">Provincia</label>
            <input value="<?php echo $operador['provincia'];?>" name="provincia" type="text" id="provincia" placeholder="Provincia" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="6">
            <label for="canton" class="opcional">Cantón</label>
            <input value="<?php echo $operador['canton'];?>" name="canton" type="text" id="canton" placeholder="Canton" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="7">
            <label for="parroquia" class="opcional">Parroquia</label>
            <input value="<?php echo $operador['parroquia'];?>" name="parroquia" type="text" id="parroquia" placeholder="Parroquia" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="8">
            <label for="dirReferencia" class="opcional">Dirección de referencia</label>
            <input value="<?php echo $datosGenerales['direccion_referencia'];?>" name="dirReferencia" type="text" id="dirReferencia" placeholder="Dirección de referencia" class="cuadroTextoCompleto" maxlength="512" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required />
         </div>
         <div data-linea="9">
            <label for="telefono" class="opcional">Teléfono</label>
            <input value="<?php echo $operador['telefono_uno'];?>" name="telefono" type="text" id="telefono" placeholder="Telefono" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="10">
            <label for="celular" class="opcional">Celular</label>
            <input value="<?php echo $operador['celular_uno'];?>" name="celular" type="text" id="celular" placeholder="Celular" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="11">
            <label for="correo" class="opcional">Correo</label>
            <input value="<?php echo $operador['correo'];?>" name="correo" type="text" id="correo" placeholder="Correo" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

         <div data-linea="12">
            <label for="ciLegal" class="opcional">Cédula del representante legal</label>
            <input value="<?php echo $datosGenerales['ci_representante_legal'];?>" name="ciLegal" type="text" id="ciLegal" placeholder="Cédula" maxlength="10" data-er="^[0-9]+$" required />
         </div>
         <div data-linea="13">
            <label for="registro_oficial" class="opcional">Número de registro oficial</label>
            <input value="<?php echo $datosGenerales['registro_oficial'];?>" name="registro_oficial" type="text" id="registro_oficial" placeholder="Registro oficial" maxlength="128" data-er="^[0-9]+$" required />
         </div>
        
         <div data-linea="15">
            <label for="nombreLegal">Nombres representante legal</label>
            <input style="width:100%" value="<?php echo $operador['nombre_representante'];?>" name="nombreLegal" type="text" id="nombreLegal" placeholder="Nombres" maxlength="200" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" disabled="disabled" />
         </div>
         <div data-linea="16">
            <label for="apellidoLegal">Apellidos representante legal</label>
            <input value="<?php echo $operador['apellido_representante'];?>" name="apellidoLegal" type="text" id="apellidoLegal" placeholder="Apellidos" maxlength="250" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" disabled="disabled" />
         </div>
         <div data-linea="17">
            <label for="correoLegal" class="opcional">Correo del representante legal</label>
            <input value="<?php echo $datosGenerales['email_representante_legal'];?>" name="correoLegal" type="text" id="correoLegal" placeholder="Correo" class="cuadroTextoCompleto" maxlength="64" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required />
         </div>

      </fieldset>
		
		<fieldset>
			<legend>Datos generales</legend>
         <div data-linea="1">
            <label for="nombreProducto">Nombre del producto:</label>
            <input value="<?php echo $datosGenerales['nombre'];?>" name="nombreProducto" type="text" id="nombreProducto" placeholder="nombre"  maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required />
         </div>
			<div data-linea="2">
				<label for="id_subtipo_producto">Tipo de producto</label>
				<select name="id_subtipo_producto" id="id_subtipo_producto" required>
					<option value="">Seleccione....</option>
					<?php
					foreach ($subtipoProductos as $key=>$item){
						if(strtoupper($item['id_subtipo_producto']) == strtoupper($datosGenerales['id_subtipo_producto'])){
							echo '<option value="' . $item['id_subtipo_producto'] . '" selected="selected" data-codigo="'.$item['codificacion_subtipo_producto'].'">' . $item['nombre'] . '</option>';
							}else{
								echo '<option value="' . $item['id_subtipo_producto'] . '" data-codigo="'.$item['codificacion_subtipo_producto'].'">' . $item['nombre'] . '</option>';
							}
						}
                        ?>
				</select>
			</div>
			<div data-linea="3">
				<label for="tipo_solicitud">Tipo de solicitud</label>
				<select name="tipo_solicitud" id="tipo_solicitud" required>
					<option value="">Seleccione....</option>
					<?php
						$items=$ce->listarElementosCatalogo($conexion,'P4C0');
						foreach ($items as $key=>$item){
							if(strtoupper($item['codigo']) == strtoupper($datosGenerales['tipo_solicitud'])){
								echo '<option value="' . $item['codigo'] . '" selected="selected">' . $item['nombre'] . '</option>';
							}else{
								echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
							}
						}
                    ?>
				</select>
			</div>
			<div data-linea="4">
				<label for="id_sitio">Sitio</label>
				<select name="id_sitio" id="id_sitio" required>
					<option value="">Seleccione....</option>
					<?php
					foreach ($sitiosAreas as $key=>$item){
						$itemCodigo=$item['direccion'].', Parroquia '.$item['parroquia'].', Cantón '.$item['canton'].', Provincia '.$item['provincia'];
						if(strtoupper($item['id_sitio']) == strtoupper($datosGenerales['id_sitio'])){
						    echo '<option value="' . $item['id_sitio'] . '" data-direccion="'.$itemCodigo.'" selected="selected">' . $item['nombre_lugar'] . ' - ' .$item['provincia'] . '</option>';
						}else{
						    echo '<option value="' . $item['id_sitio'] . '" data-direccion="'.$itemCodigo.'" >' . $item['nombre_lugar'] . ' - ' .$item['provincia'] . '</option>';
						}
					}
                        ?>
				</select>
			</div>
			<div data-linea="5">
				<label for="id_area">Área</label>
				<select name="id_area" id="id_area" required>
					<option value="">Seleccione....</option>
					<?php
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

			<hr/>
			<div data-linea="6" id="rep_tecnico">
				<label for="ci_representante_tecnico">Representante técnico</label>
				<select id="ci_representante_tecnico" name="ci_representante_tecnico" required>
					<option value="">Seleccione....</option>
					<?php
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
			<div data-linea="7">
				<label for="tituloTecnico">Título del representante técnico</label>
				<input value="" name="tituloTecnico" type="text" id="tituloTecnico" placeholder="titulo" maxlength="256" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" disabled="disabled"/>
			</div>
			<div data-linea="8">
				<label for="registroSenesyt">Registro del título en el SENESCYT</label>
				<input value="<?php echo $datosGenerales['tecnico_matricula'];?>" name="registroSenesyt" type="text" id="registroSenesyt" placeholder="registro" maxlength="256" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$"/>
			</div>

		</fieldset>
			
		<button id="btnGuardarPrimero" type="button" class="guardar">Guardar solicitud</button>
		
	</form>


</div>

<div class="pestania" id="P2" style="display: block;">
  
	<form id='frmProcedencia' data-rutaAplicacion='dossierPecuario' data-opcion='guardarProcedenciaProducto'>
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      
      <fieldset>
         <legend>Origen del producto</legend>

         <div class="noDinamicos">
            <label for="es_fabricante">El producto es fabricado por el solicitante ?</label>
				<br />
            <input type="radio" id="es_fabricanteSI" name="es_fabricante" class="es_fabricante obsFabricantes" value="SI" <?php if($es_fabricante==true) echo "checked=true"?> /><label for="es_fabricanteSI">SI</label>
            <input type="radio" id="es_fabricanteNO" name="es_fabricante" class="es_fabricante obsFabricantes" value="NO" <?php if($es_fabricante==false) echo "checked=true"?> /><label for="es_fabricanteNO">NO</label>
         </div>

         <div data-linea="1" class="fabricanteNacional">
            <label for="fn_razon_social" class="opcional">Razón social</label>
            <input value="<?php echo $operador['razon_social'];?>" name="fn_razon_social" type="text" id="fn_razon_social" placeholder="Nombre de la empresa" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="2" class="fabricanteNacional">
            <label for="fn_ruc" class="opcional">CI/RUC/PASS</label>
            <input value="<?php echo $operador['identificador'];?>" name="fn_ruc" type="text" id="fn_ruc" placeholder="Nombre de la empresa" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="3" class="fabricanteNacional">

            <label for="fn_sitio_nombre">Sitio</label>
            <input value="" name="fn_sitio_nombre" type="text" id="fn_sitio_nombre" placeholder="nombre del sitio" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="4" class="fabricanteNacional">
            <label for="fn_sitio_direccion" class="opcional">Dirección del sitio</label>
            <input value="" name="fn_sitio_direccion" type="text" id="fn_sitio_direccion" placeholder="direccion del sitio" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
			<hr />
         <div id="es_por_contratoVer">				
            <label for="es_por_contrato">El producto es fabricado por medio de un contrato de elaboración por contrato?</label> 
				<br />
            <input type="radio" id="es_por_contratoSI" name="es_por_contrato" class="es_por_contrato obsFabricantes" value="SI" <?php if($es_por_contrato==true) echo "checked=true"?> /><label for="es_por_contratoSI">SI</label>
            <input type="radio" id="es_por_contratoNO" name="es_por_contrato" class="es_por_contrato obsFabricantes" value="NO" <?php if($es_por_contrato==false) echo "checked=true"?> /><label for="es_por_contratoNO">NO</label>
         </div>

         <div data-linea="5" class="fabricanteContrato">
            <label for="fc_ruc" class="opcional">CI/RUC/PASS</label>
            <input value="" name="fc_ruc" type="text" id="fc_ruc" placeholder="Identificación de la empresa" class="fc_ruc obsFabricantes" maxlength="13" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="6" class="fabricanteContrato">
            <label for="fc_razon_social" class="opcional">Razón social</label>
            <input value="" name="fc_razon_social" type="text" id="fc_razon_social" placeholder="Nombre de la empresa" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

         <div data-linea="7" class="fabricanteContrato">
            <label for="fc_id_sitio">Sitio</label>
            <select name="fc_id_sitio" id="fc_id_sitio" class="obsFabricantes"></select>
         </div>
         <div data-linea="8" class="fabricanteContrato">
            <label for="fc_sitio_direccion" class="opcional">Dirección del sitio</label>
            <input value="" name="fc_sitio_direccion" type="text" id="fc_sitio_direccion" placeholder="direccion del sitio" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="9" class="fabricanteContrato">
            <label for="fc_id_area">Área</label>
            <select name="fc_id_area" id="fc_id_area" class="obsFabricantes"></select>
         </div>
         <div data-linea="10" class="fabricanteContrato">
            <label for="fc_tecnico_contrato">Representante técnico</label>
            <select name="fc_tecnico_contrato" id="fc_tecnico_contrato" class="obsFabricantes"></select>
         </div>

         <div data-linea="13" class="fabricanteExtranjero">
            <label for="id_extranjero">Seleccione el fabricante extranjero</label>
            <select name="id_extranjero" id="id_extranjero" class="obsFabricantes">
               <option value="">Seleccione....</option><?php
         foreach ($fabricantesExtranjeros as $key=>$item){
         echo '<option value="' . $item['id_fabricante_extranjero'] . '">' . $item['nombre'] . '</option>';
         }
                                                       ?>
            </select>
         </div>
         <div data-linea="14" class="fabricanteExtranjero">
            <input value="" name="ex_id_pais" type="hidden" id="ex_id_pais" />
            <label for="ex_pais" class="opcional">Pais de origen</label>
            <input value="" name="ex_pais" type="text" id="ex_pais" placeholder="pais" class="cuadroTextoCompleto" disabled="disabled" maxlength="30" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div class="justificado fabricanteExtranjero">
            <label for="ex_direccion">Dirección</label>
            <textarea  name="ex_direccion" id="ex_direccion" placeholder="Dirección" class="obsFabricantes" maxlength="1024" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$"></textarea>
         </div>

         <div data-linea="16" class="fabricanteExtranjero">
            <label for="ex_tecnico_contrato">Representante técnico</label>            
				<input value="" name="ex_tecnico_contrato" type="text" id="ex_tecnico_contrato" class="obsFabricantes" placeholder="Identificación y nombre del técnico responsable" maxlength="256" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" />
         </div>
         <div data-linea="17" class="fabricanteMix">
            <label for="fc_tituloTecnico">Título del representante técnico</label>
            <input value="" name="fc_tituloTecnico" type="text" id="fc_tituloTecnico" class="obsFabricantes" placeholder="titulo" maxlength="256" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" />

         </div>
         <div data-linea="18" class="fabricanteMix">
            <label id="lbl_fc_registroSenesyt" for="fc_registroSenesyt">Registro del título en el SENESCYT/MATRICULA</label>
            <input value="" name="fc_registroSenesyt" type="text" id="fc_registroSenesyt" class="obsFabricantes" placeholder="registro" maxlength="256" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" />
         </div>

         <div data-linea="20">
            <label for="f_registro_oficial" class="opcional">Número de registro oficial</label>
            <input value="" name="f_registro_oficial" type="text" id="f_registro_oficial" class="obsFabricantes" placeholder="Registro oficial" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>


         <button id="btnNuevoOrigen" type="button" class="mas obsFabricantes">Agregar fabricante</button>
      </fieldset>

      
   </form>
	<fieldset>
		<legend>Lista de fabricantes</legend>
	
	<table id="tblProcedencia" style="width:99%">
		<thead>
			<tr>				
				<th >Fabricado por</th>
				<th >Empresa</th>
				<th >Dirección</th>
				<th >Sitio</th>
				<th >Área</th>
				<th >País</th>
				<th >Registro</th>
				<th >Responsable técnico</th>
				<th >Profesión</th>
				<th >Matrícula</th>
				<th></th>
			</tr>
		</thead>
		<tbody ></tbody>
	</table>
		<div data-linea="1">
			<label id="tablaFabricantes"></label>
		</div>
	</fieldset>

   <form id='frmGuardarPaso' data-rutaAplicacion='dossierPecuario' data-opcion='guardarPasosSolicitud'>
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="paso_solicitud" name="paso_solicitud" value="P2" />
      <button type="submit" class="guardar">Guardar solicitud</button>
   </form>

</div>

<div class="pestania" id="P3" style="display: block;">
   	
   <form id='frmDatosProducto' data-rutaAplicacion='dossierPecuario' data-opcion='guardarOrigenProducto'>
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="id_flujo" name="id_flujo" value="<?php echo $id_flujo;?>" />

      <input type="hidden" id="fase" name="fase" value="solicitud" />

      <fieldset class="noFarmacologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noKid noDesinfectantes noDinamicos">
         <legend>Registro de CEPAS</legend>
         <div>
            <label for="es_nueva_cepa">Ud va ha registrar una nueva CEPA ?</label>
            SI<input type="radio" id="es_nueva_cepaSI" name="es_nueva_cepa" value="SI" <?php if($datosGenerales['es_nueva_cepa']=='t') echo "checked=true"?> />
            NO<input type="radio" id="es_nueva_cepaNO" name="es_nueva_cepa" value="NO" <?php if($datosGenerales['es_nueva_cepa']=='f') echo "checked=true"?> />
         </div>
         <div data-linea="3" class="verNuevaCepa">
            <label for="nueva_cepa">Nombre de la CEPA:</label>
            <input value="<?php echo $datosGenerales['nueva_cepa'];?>" name="nueva_cepa" type="text" id="nueva_cepa" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
      </fieldset>

      <fieldset id="verCertificadoBiologico">
         <legend>Certificado de análisis del producto</legend>
         <input type="hidden" id="tipoArchivo" name="tipoArchivo" value="AP_CAP" />
         <div class=" justificado">
            <label for="referencia" class="opcional">Referencia para el documento:</label>
            <input value="<?php echo $anexoVector['AP_CAP']['referencia']; ?>" type="text" id="referencia" name="referencia" placeholder="Incluir la referencia en el documento" class="referencia subsanarFarmacologico" maxlength="64" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"  />
         </div>
         <div data-linea="2">
            <label>Archivo adjunto</label>         <?php
         $anexo=$anexoVector['AP_CAP']['path'];
         if($anexo=='0' || $anexo==''){
         echo '<span class="alerta" id="noHayArchivo">No hay ningún archivo adjunto</span>';
         echo '<a href="" target="_blank" class="archivo_cargado" id="archivo_cargado" style="display: none;">Archivo Cargado</a>';
         }
         else{
         echo '<span class="alerta" id="noHayArchivo" style="display: none;">No hay ningún archivo adjunto</span>';
         echo '<a href='.$anexo.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';
         }
         ?>
         </div>
         <div data-linea="3">
            <input type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
            <input type="hidden" class="maxCapacidad" value="<?php echo intval($anexosCapacidad['AP_CAP']['nombre2'])*1024*1024; ?>" />
            <input type="file" class="archivo" accept="application/msword | application/pdf | image/*" disabled="disabled" />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo $anexosCapacidad['AP_CAP']['nombre2'].'M'; ?>B)</div>
            <button type="button" class="subirArchivo adjunto subsanarFarmacologico" data-rutaCarga="<?php echo $pathAnexo;?>" disabled="disabled">Subir archivo</button>
         </div>
      </fieldset>

      <fieldset class="verCepas">
         <legend>Datos del producto</legend>
         <div data-linea="1">
            <label for="id_clasificacion_subtipo">Clasificación:</label>
            <select name="id_clasificacion_subtipo" id="id_clasificacion_subtipo">
               <option value="">Seleccione....</option>

            </select>
         </div>

         <div data-linea="3" class="noDinamicos">
            <label for="partida_arancelaria" class="opcional">Partida arancelaria:</label>
            <input value="<?php echo $datosGenerales['partida_arancelaria'];?>" name="partida_arancelaria" type="text" id="partida_arancelaria" maxlength="10" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="4">
            <label for="id_formulacion">Forma farmaceútica:</label>
            <select name="id_formulacion" id="id_formulacion">
               <option value="">Seleccione....</option><?php
            foreach ($formulaciones as $key=>$item){
            if(strtoupper($item['id_formulacion']) == strtoupper($datosGenerales['id_formulacion'])){
            echo '<option value="' . $item['id_formulacion'] . '" selected="selected">' . $item['formulacion'] . '</option>';
            }else{
            echo '<option value="' . $item['id_formulacion'] . '">' . $item['formulacion'] . '</option>';
            }
            }
            ?>

            </select>
         </div>



         <p><b>Composición del producto:</b></p>
         <hr />
         <div data-linea="6">
            <label>Tipo de elemento:</label>
            <select name="tipoIa" id="tipoIa" style="width:auto">
               <option value="">Seleccione....</option><?php
            foreach ($iaGrupos as $key=>$item){
            echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
            }
            ?>
            </select>
         </div>
         <div data-linea="7">
            <label>Elemento</label>
            <select name="elementoComposicion" id="elementoComposicion" class="col-1">
               <option value="">Seleccione....</option>
            </select>
         </div>
         <div data-linea="8">
            <label>Cantidad</label>
            <input value="" name="composicionValor" type="text" id="composicionValor" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" class="col-2" />
         </div>
         <div data-linea="9">
            <label>Unidad</label>
            <select name="composicionUnidad" id="composicionUnidad" class="col-3">
               <option value="">Seleccione....</option><?php
            foreach ($unidadesMedida as $key=>$item){
            echo '<option value="' . $item['id_unidad_medida'] . '">' . $item['nombre'] . '</option>';
            }
            ?>
            </select>
         </div>
         <div class="detalles">

            <button id="agregarElementoComposicion" type="button" class="mas">Agregar</button>
         </div>

         <hr />

			<div data-linea="12">
            <label for="producto_cantidad">Cada:</label>
               <input value="<?php echo $datosGenerales['producto_cantidad'];?>" name="producto_cantidad" type="number" id="producto_cantidad" min="0" max="199999999" step="0.1" />
         </div>
			<div data-linea="12">
            <select name="producto_unidad" id="producto_unidad">
                  <option value="">Seleccione....</option>
						<?php
               foreach ($unidadesMedida as $key=>$item){
               if(strtoupper($item['id_unidad_medida']) == strtoupper($datosGenerales['producto_unidad'])){
               echo '<option value="' . $item['id_unidad_medida'] . '" selected="selected">' . $item['nombre'] . '</option>';
               }else{
               echo '<option value="' . $item['id_unidad_medida'] . '">' . $item['nombre'] . '</option>';
               }
               }
                   ?>
               </select>
         </div>
			<div data-linea="12">
            <label>.    Contiene...</label>
         </div>
         

         <table id="tblComposicion" style="width:95%">
            <thead>
               <tr>
                  <th>Tipo</th>
                  <th>Elemento</th>
                  <th>Cantidad</th>
                  <th>Unidad</th>
                  <th></th>
               </tr>
            </thead>
            <tbody></tbody>
         </table>
         <div data-linea="10">
            <label id="tblComposicionProducto"></label>
         </div>
      </fieldset>

      <button type="submit" class="guardar">Guardar solicitud</button>
   </form>

</div>

<div class="pestania" id="P4" style="display: block;">
   <form id='frmNuevaSolicitud3' data-rutaAplicacion='dossierPecuario' data-opcion='guardarInformacionSolicitud'>
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />

         <fieldset>
            <legend>Información del producto</legend>
            <div data-linea="1" class="noMedicados noCompletos noAditivos noSales noSnack noKid noDinamicos">
               <label for="ph">pH:</label>
               <input value="<?php echo $datosGenerales['ph'];?>" name="ph" type="text" id="ph" maxlength="4" data-er="^[0-9]+$"/>
            </div>
            <div data-linea="1" class="noMedicados noCompletos noAditivos noSales noSnack noKid noDinamicos">
               <label for="viscosidad">Viscosidad:</label>
               <input value="<?php echo $datosGenerales['viscosidad'];?>" name="viscosidad" type="text" id="viscosidad" maxlength="16" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"  />
            </div>
            <div data-linea="1" class="noMedicados noCompletos noAditivos noSales noSnack noKid noDinamicos">
               <label for="densidad">Densidad:</label>
               <input value="<?php echo $datosGenerales['densidad'];?>" name="densidad" type="text" id="densidad" maxlength="16" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"  />
            </div>
            <div  data-linea="2">
               <label for="modo_fabricacion" class="opcional">Modo de fabricación del producto:</label>
               <textarea name="modo_fabricacion" id="modo_fabricacion" maxlength="5120" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo trim(htmlspecialchars($datosGenerales['modo_fabricacion'])); ?></textarea>
            </div>
            <div data-linea="3" class="noDinamicos">
               <label for="especificacion" class="opcional">Especificaciones del producto:</label>
               <textarea name="especificacion" id="especificacion" maxlength="5120" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" ><?php echo trim(htmlspecialchars($datosGenerales['especificacion'])); ?></textarea>
            </div>
            <div data-linea="4" class="noMedicados noCompletos noAditivos noSales noSnack noKid noDinamicos noCosmeticos noDesinfectantes">
               <label for="prueba_biologica" class="opcional">Pruebas biológicas:</label>
               <textarea name="prueba_biologica" id="prueba_biologica" maxlength="5120" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" ><?php echo trim(htmlspecialchars($datosGenerales['prueba_biologica'])); ?></textarea>               
				</div>
				<div data-linea="5" class="noMedicados noCompletos noAditivos noSales noSnack noKid noDinamicos noCosmeticos noDesinfectantes">               
               <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['prueba_biologica_ref'];?>" name="prueba_biologica_ref" type="text" id="prueba_biologica_ref"  maxlength="128" placeholder="Ingrese la referencia bibliográfica" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
            <div data-linea="6" class="noFarmacologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noKid noDesinfectantes noDinamicos">
               <label for="identidad" class="opcional">Identidad:</label>
               <textarea name="identidad" id="identidad" maxlength="5120" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" ><?php echo trim(htmlspecialchars($datosGenerales['identidad'])); ?></textarea>               
            </div> 
				<div data-linea="7" class="noFarmacologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noKid noDesinfectantes noDinamicos">              
               <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['identidad_referencia'];?>" name="identidad_referencia" type="text" id="identidad_referencia" maxlength="128" placeholder="Ingrese la referencia bibliográfica" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
            </div>
            <div data-linea="8" class="noFarmacologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noKid noDesinfectantes noDinamicos">
               <label for="esterilidad" class="opcional">Esterilidad:</label>
               <textarea name="esterilidad" id="esterilidad" maxlength="5120" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" ><?php echo trim(htmlspecialchars($datosGenerales['esterilidad'])); ?></textarea>               
            </div>
				<div data-linea="9" class="noFarmacologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noKid noDesinfectantes noDinamicos">               
               <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['esterilidad_referencia'];?>" name="esterilidad_referencia" type="text" id="esterilidad_referencia" maxlength="128" placeholder="Ingrese la referencia bibliográfica" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
            </div>
            <div data-linea="10" class="noFarmacologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noKid noDesinfectantes noDinamicos">
               <label for="agentes_extra" class="opcional">Ausencia de agentes extraños:</label>
               <textarea name="agentes_extra" id="agentes_extra" maxlength="5120" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" ><?php echo trim(htmlspecialchars($datosGenerales['agentes_extra'])); ?></textarea>
            </div>
            <div data-linea="12" class="noFarmacologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noKid noDesinfectantes noDinamicos">
               <label for="inocuidad" class="opcional">Control de inocuidad:</label>
               <textarea name="inocuidad" id="inocuidad" maxlength="5120" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" ><?php echo trim(htmlspecialchars($datosGenerales['inocuidad'])); ?></textarea>               
            </div>
				<div data-linea="13" class="noFarmacologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noKid noDesinfectantes noDinamicos">              
               <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['inocuidad_referencia'];?>" name="inocuidad_referencia" type="text" id="inocuidad_referencia" maxlength="128" placeholder="Ingrese la referencia bibliográfica" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
            </div>

         </fieldset> 

      <button type="submit" class="guardar">Guardar solicitud</button>
   </form>
</div>

<div class="pestania" id="P5" style="display: block;">
   <form id='frmNuevaSolicitud4' data-rutaAplicacion='dossierPecuario' data-opcion='guardarUsosSolicitud'>
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />

   
      <fieldset>
         <legend>Indicaciones de uso</legend>
         <input type="hidden"  id="usos" name="usos" value="<?php echo $datosGenerales['usos'];?>"/>
			<div data-linea="1">
				
            <label>Uso para declarar:</label>
            <select name="id_usos" id="id_usos">

				</select>
            <button id="btnUsos" type="button" class="mas">Agregar</button>
            <button id="btnUsosElinar" type="button" class="menos">Quitar</button>
			</div>
         <div>
            <label>Usos declarados:</label>
            <p id="usos_lista"></p>
			</div>
         <div data-linea="3">
            <label id="puntosUsos"></label>
         </div>

         <div data-linea="4" id="usos_diagnostico_ver" hidden="hidden">
            <label for="usos_diagnostico">Indique diagnóstico:</label>
            <input value="<?php echo $datosGenerales['usos_diagnostico'];?>" name="usos_diagnostico" type="text" id="usos_diagnostico" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
		</fieldset>
           
		<fieldset id="tipoEspecieVer">
         <legend>Registro de dosis</legend>
            
            <div data-linea="1">
               <label for="especie_tipo">Tipo:</label>
               <select name="especie_tipo" id="especie_tipo">
                  <option value="">Seleccione....</option><?php
						foreach ($especiesTipo as $key=>$item){
							echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
						}
                                                          ?>
               </select>
            </div>
            <div data-linea="2" class="especieVer continuacion">
               <label for="especie">Especie:</label>
               <select name="especie" id="especie"></select>
            </div>
           
            <hr />
            <div data-linea="3" id="viaVer" class="especieVer">
               <label  for="via">Vía de administración:</label>
               <select name="via" id="via">
                  <option value="">Seleccione....</option>

               </select>
            </div>
					<div data-linea="4" id="verModoAplicacion">
                  <label for="cantidad">Modo de aplicación:</label>
                  <input value="" name="modo_aplicacion" type="text" id="modo_aplicacion" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />

               </div>

           

               <div data-linea="5" id="cantidadVer">
                  <label for="cantidad">Cantidad:</label>
                  <input value="" name="cantidad" type="text" id="cantidad" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" class="col-25" />

               </div>
               <div data-linea="5" id="unidad1Ver">
                  <label for="unidad1">Unidad:</label>
                  <select name="unidad1" id="unidad1" class="col-3">
                     <option value="">Seleccione....</option>
                  </select>
               </div>
               <div data-linea="6" id="porVer">
                  <label>Por:</label>
               </div>
               <div data-linea="7" id="sustratoPesoVer">
                  <label id="sustratoPesoLabel">Sustrato:</label>
                  <input value="" name="sustratoPeso" type="text" id="sustratoPeso" maxlength="10" data-er="^[0-9]+$" />
               </div>
               <div data-linea="7" id="unidad2Ver">
                  <label for="unidad2">Unidad:</label>
                  <select name="unidad2" id="unidad2">
                     <option value="">Seleccione....</option>
                  </select>
               </div>
               <div data-linea="8" id="cadaVer">
                  <label>Cada:</label>
               </div>

               <div data-linea="9" id="duracionVer">
                  <label for="duracion">Duración:</label>
                  <input value="" name="duracion" type="text" id="duracion" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
               </div>
               <div data-linea="9" id="unidad3Ver">
                  <label for="unidad3">Unidad:</label>
                  <select name="unidad3" id="unidad3">
                     <option value="">Seleccione....</option>
                  </select>
               </div>
               <div data-linea="10" class="justificado">
                  <label for="dosis_detalle">Detalle de la dosis:</label>
                  <input value="" name="dosis_detalle" type="text" id="dosis_detalle" maxlength="128" data-distribuir='no' data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
               </div>
               <div data-linea="11" class="justificado">
                  <label for="dosis_referencia">Referencia bibliográfica:</label>
                  <input value="" name="dosis_referencia" type="text" id="dosis_referencia" maxlength="128" data-distribuir='no' data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
               </div>


            
            <div data-linea="12">
               <button id="btnAddDosis" type="button" class="mas">Agregar</button>
            </div>

            

         </fieldset>
  
      <fieldset>
         <legend>Dosis registradas</legend>	
			
			<table id="tblViaAdmin" style="width:98%">
				<thead>
					<tr>
						<th>Vía de administración y dosis</th>									
						<th></th>
					</tr>
				</thead>
				<tbody>
								
				</tbody>
			</table>
			<div data-linea="3">
				<label id="tblDosisProducto"></label>
			</div>
      </fieldset>

      <button type="submit" class="guardar">Guardar solicitud</button>
   </form>
</div>

<div class="pestania" id="P6" style="display: block;">
	<form id='frmNuevaSolicitud5' data-rutaAplicacion='dossierPecuario' data-opcion='guardarSeguridadSolicitud'>
		<input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />

      <fieldset>
         <legend>Seguridad</legend>
         <div data-linea="1" class="noBiologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noKid noDesinfectantes noDinamicos">
            <label for="margen_seguridad">Margen de seguridad:</label>
				<textarea name="margen_seguridad" id="margen_seguridad" maxlength="1024" data-distribuir='no' data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo trim(htmlspecialchars($datosGenerales['margen_seguridad'])); ?></textarea>             
         </div>
			<div data-linea="2" class="noBiologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noKid noDesinfectantes noDinamicos">            
            <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['margen_seguridad_referencia'];?>" name="margen_seguridad_referencia" type="text" id="margen_seguridad_referencia" maxlength="128" placeholder="Ingrese la referencia bibliográfica" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="3" class="noMedicados noCompletos noAditivos noSales noSnack noKid noDinamicos">
            <label for="requiere_preparacion">Requiere preparación para su uso ?</label>
        
               SI<input type="radio" id="requiere_preparacionSI" name="requiere_preparacion" value="SI" <?php if($requiere_preparacion==true) echo "checked=true"?> />
               NO<input type="radio" id="requiere_preparacionNO" name="requiere_preparacion" value="NO" <?php if($requiere_preparacion==false) echo "checked=true"?> />

         </div>
         <div data-linea="4" class="noMedicados noCompletos noAditivos noSales noSnack noKid noDinamicos">
				<label id="obs_requiere_preparacion"></label>
			</div>
         <div data-linea="5" class="requiere_preparacionView noMedicados noCompletos noAditivos noSales noSnack noKid noDinamicos" >
            <label for="preparacion_duracion">Duración máxima:</label>
            <input value="<?php echo $datosGenerales['preparacion_duracion'];?>" name="preparacion_duracion" type="number" id="preparacion_duracion" max="1999999999" data-er="^[0-9]+$" />

         </div>
			 <div data-linea="5" class="requiere_preparacionView noMedicados noCompletos noAditivos noSales noSnack noKid noDinamicos" >
            <label for="preparacion_unidad">Unidad:</label>
            <select name="preparacion_unidad" id="preparacion_unidad">
               <option value="">Seleccione....</option>
					<?php
					foreach ($catalogoUnidadesTiempo as $key=>$item){
						if(strtoupper($item['codigo']) == strtoupper($datosGenerales['preparacion_unidad'])){
							echo '<option value="' . $item['codigo'] . '" selected="selected">' . $item['nombre'] . '</option>';
						}else{
							echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
						}
					}
                    ?>

            </select>
         </div>

			<div data-linea="8" class="requiere_preparacionView noMedicados noCompletos noAditivos noSales noSnack noKid noDinamicos">
				<label for="preparacion_descripcion">Preparación para su uso correcto:</label>
				<textarea  name="preparacion_descripcion"  id="preparacion_descripcion" maxlength="5120" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" ><?php echo trim(htmlspecialchars($datosGenerales['preparacion_descripcion'])); ?></textarea>
			</div>
			
			<div data-linea="10" class="noBiologicos noCompletos noAditivos noSales noCosmeticos noSnack noKid noDesinfectantes noDinamicos">
				<label for="farmacocinetica">Farmacocinética:</label>
				<textarea  name="farmacocinetica" id="farmacocinetica" maxlength="1024" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" ><?php echo trim(htmlspecialchars($datosGenerales['farmacocinetica'])); ?></textarea>            
			</div>
			<div data-linea="11" class="noBiologicos noCompletos noAditivos noSales noCosmeticos noSnack noKid noDesinfectantes noDinamicos">				
            <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['farmacocinetica_referencia'];?>" name="farmacocinetica_referencia" type="text" id="farmacocinetica_referencia" maxlength="128" placeholder="Ingrese la referencia bibliográfica" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
			</div>
			<div data-linea="12" class="noBiologicos noCompletos noAditivos noSales noCosmeticos noSnack noKid noDesinfectantes noDinamicos">
				<label for="farmacodinamica">farmacodinámica:</label>
				<textarea name="farmacodinamica"  id="farmacodinamica" maxlength="1024" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" ><?php echo trim(htmlspecialchars($datosGenerales['farmacodinamica'])); ?></textarea>            
			</div>
			<div data-linea="13" class="noBiologicos noCompletos noAditivos noSales noCosmeticos noSnack noKid noDesinfectantes noDinamicos">				
            <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['farmacodinamica_referencia'];?>" name="farmacodinamica_referencia" type="text" id="farmacodinamica_referencia" maxlength="128" placeholder="Ingrese la referencia bibliográfica" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
			</div>
			<div data-linea="14" class="noSnack noKid noDinamicos">
				<label for="efectos_colaterales">Efectos colaterales posibles locales o generales incompatibilidades y antagonismos:</label>
					<textarea  name="efectos_colaterales"  id="efectos_colaterales" maxlength="5120" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" ><?php echo trim(htmlspecialchars($datosGenerales['efectos_colaterales'])); ?></textarea>            
			</div>
			<div data-linea="15" class="noSnack noKid noDinamicos">				
            <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['efectos_colaterales_referencia'];?>" name="efectos_colaterales_referencia" type="text" id="efectos_colaterales_referencia" maxlength="128" placeholder="Ingrese la referencia bibliográfica" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
			</div>
			<div data-linea="16" class="noMedicados noCompletos noSnack noKid noDesinfectantes noDinamicos">
				<label for="sobredosis">Intoxicación y sobredosis en animales:</label>
				<textarea  name="sobredosis"  id="sobredosis" maxlength="1024"  data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"  ><?php echo trim(htmlspecialchars($datosGenerales['sobredosis'])); ?></textarea>            
			</div>
			<div data-linea="17" class="noMedicados noCompletos noSnack noKid noDesinfectantes noDinamicos">				
            <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['sobredosis_referencia'];?>" name="sobredosis_referencia" type="text" id="sobredosis_referencia" maxlength="128" placeholder="Ingrese la referencia bibliográfica" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
			</div>
			<div data-linea="18" class="noBiologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noKid noDinamicos">
				<label for="toxicidad">Toxicidad en el hombre:</label>
				<textarea  name="toxicidad"  id="toxicidad" maxlength="1024" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"  ><?php echo trim(htmlspecialchars($datosGenerales['toxicidad'])); ?></textarea>            
			</div>
			<div data-linea="19" class="noBiologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noKid noDinamicos">				
            <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['toxicidad_referencia'];?>" name="toxicidad_referencia" type="text" id="toxicidad_referencia" maxlength="128" placeholder="Ingrese la referencia bibliográfica" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
			</div>

         <div data-linea="20" class=" noBiologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noKid noDinamicos">
            <label for="tiene_categoria_toxicologica">El producto tiene categoría toxicológica ?</label>
          
               SI<input type="radio" id="tiene_categoria_toxicologicaSI" name="tiene_categoria_toxicologica" value="SI" <?php if($datosGenerales['tiene_categoria_toxicologica']=='t') echo "checked=true"?> />
               NO<input type="radio" id="tiene_categoria_toxicologicaNO" name="tiene_categoria_toxicologica" value="NO" <?php if($datosGenerales['tiene_categoria_toxicologica']=='f') echo "checked=true"?> />
        
         </div>
         <div data-linea="21" class=" noBiologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noKid noDinamicos">
            <label id="obs_tiene_categoria_toxicologica"></label>
         </div>

         <div data-linea="22" class="categoria_toxicologicaVer">
            <label for="categoria_toxicologica">Categoría toxicológica:</label>
            <select name="categoria_toxicologica" id="categoria_toxicologica">
               <option value="">Seleccione....</option>
					<?php
					foreach ($categoriasToxicologicas as $key=>$item){
						if(strtoupper($item['id_categoria_toxicologica']) == strtoupper($datosGenerales['categoria_toxicologica'])){
							echo '<option value="' . $item['id_categoria_toxicologica'] . '" selected="selected">' . $item['categoria_toxicologica'] . '</option>';
						}else{
							echo '<option value="' . $item['id_categoria_toxicologica'] . '">' . $item['categoria_toxicologica'] . '</option>';
						}
					}
                    ?>
            </select>
         </div>
			<div class="noFarmacologicos noBiologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noKid noDesinfectantes">
				<label>Prohibida su comercialización en establecimientos de expendio de productos agropecuarios y/o veterinarios</label>
			</div>

      </fieldset>

		<fieldset class="noBiologicos noCosmeticos noSnack noKid noDesinfectantes noDinamicos">
            <legend>Efectos biológicos no deseados</legend>
            <div data-linea="1">
               <label for="id_efecto">Seleccione el efecto:</label>
               <select name="id_efecto" id="id_efecto">
                  <option value="">Seleccione....</option>
							
						<?php
						foreach ($efectosNoDeseados as $key=>$item){
							echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
						}
                        ?>

               </select>
            </div>
			<div data-linea="2" class="justificado">
            <label for="descripcionEfecto">Describa el efecto:</label>
            <textarea name="descripcionEfecto"  id="descripcionEfecto" maxlength="1024" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"></textarea>				
         </div>
			<div data-linea="3" class="justificado">            
				<input class="justificado" data-distribuir="no" value="" name="descripcionEfecto_referencia" type="text" id="descripcionEfecto_referencia" maxlength="128" placeholder="Ingrese la referencia bibliográfica" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
				<button id="btnAddEfectos" type="button" class="mas">Añadir</button>
				<hr/>
            <table id="tblEfectos"  style="width:100%">
               <thead>
                  <tr>
                     <th>Efecto</th>
                     <th>Descripción</th>
							<th>Referencia</th>
                     <th></th>
                  </tr>
               </thead>
               <tbody>
                     
               </tbody>
            </table>
			<div data-linea="4">
				<label id="tblEfectosNoDeseados"></label>
			</div>

         </fieldset>
           
		<button type="submit" class="guardar">Guardar solicitud</button>
	</form>

</div>

<div class="pestania" id="P7" style="display: block;">
   <form id='frmNuevaSolicitud6' data-rutaAplicacion='dossierPecuario' data-opcion='guardarPrecaucionesSolicitud'>
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />

      <fieldset class="noCompletos noAditivos noSales noCosmeticos noSnack noKid noDesinfectantes">
         <legend>Periodos de retiro</legend>
         <div data-linea="1">
            <label for="especie_tipo_retiro">Especie:</label>
            <select name="especie_tipo_retiro" id="especie_tipo_retiro" >                    
                  <option value="">Seleccione....</option>
						
            </select>
         </div>
         <div data-linea="2">
            <label for="producto_consumo">Producto de consumo:</label>
            <select name="producto_consumo" id="producto_consumo">
                     
            </select>
         </div>
         <div data-linea="3">
            <label for="cantidadTiempo">Tiempo de retiro:</label>
            <input value="" name="cantidadTiempo" type="text" id="cantidadTiempo" maxlength="10" data-er="^[0-9]+$" />
         </div>
         <div data-linea="3">
            <label for="unidadTiempo">Unidad:</label>
            <select name="unidadTiempo" id="unidadTiempo">
               <option value="">Seleccione....</option>						
							<?php
							foreach ($catalogoUnidadesTiempo as $key=>$item){
								echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
							}
                            ?>
            </select>
         </div>
			<button id="btnAddRetiro" type="button" class="mas">Agregar</button>
                
         <table id="tblRetiro" style="width:100%">
            <thead>
               <tr>
                  <th>Especie</th>
                  <th>Producto de consumo</th>
                  <th>Tiempo</th>
                  <th>Unidad</th>
						<th></th>
               </tr>
            </thead>
            <tbody>
                       
            </tbody>
         </table>
				
         
			<div data-linea="6">
				<label id="tblPeriodoRetiro"></label>
			</div>
      </fieldset>

      <fieldset>
         <legend>Control y precauciones</legend>
         <div data-linea="1" class="noBiologicos noCompletos noSales noCosmeticos noSnack noKid noDesinfectantes noDinamicos">
            <label for="residuos">Control sobre residuos de medicamentos:</label>
            <textarea name="residuos" id="residuos" maxlength="2048"  data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo trim(htmlspecialchars($datosGenerales['residuos'])); ?></textarea>
         </div>
         <div data-linea="2" class="noCosmeticos">
            <label for="precauciones">Precauciones generales:</label>
            <textarea name="precauciones" id="precauciones" maxlength="1024" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo trim(htmlspecialchars($datosGenerales['precauciones'])); ?></textarea>            
         </div>
			 <div data-linea="3" class="noCosmeticos">            
            <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['precauciones_ref'];?>" name="precauciones_ref" type="text" id="precauciones_ref" maxlength="128" placeholder="Ingrese la referencia bibliográfica" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="4" class="noBiologicos noKid noDesinfectantes noDinamicos">
            <label for="calidad">Causas que pueden hacer variar la calidad:</label>
            <textarea name="calidad" id="calidad" maxlength="1024" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo htmlspecialchars($datosGenerales['calidad']); ?></textarea>
         </div>
         <div data-linea="5" class="noFarmacologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noKid noDesinfectantes noDinamicos">
            <label for="inactivacion">Control de la inactivación o modificación antígena:</label>
            <textarea name="inactivacion" id="inactivacion" maxlength="2048" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo htmlspecialchars($datosGenerales['inactivacion']); ?></textarea>
																	  
	 
			   
         </div>

         <div data-linea="6" class="noFarmacologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noKid noDesinfectantes noDinamicos">
            <label for="linea_biologica">Definición de la línea biológica:</label>
            <input value="<?php echo $datosGenerales['linea_biologica'];?>" name="linea_biologica" type="text" id="linea_biologica" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

         <div data-linea="7">
            <label for="validez">Periodo de validez:</label>
            <input value="<?php echo $datosGenerales['validez'];?>" name="validez" type="number" id="validez" min="0" max="1999999999" data-er="^[0-9]+$" />
         </div>
         <div data-linea="7">
            <label for="validez_unidad">Unidad:</label>

            <select name="validez_unidad" id="validez_unidad">
               <option value="">Seleccione....</option><?php
         foreach ($catalogoUnidadesTiempo as $key=>$item){
         if(strtoupper($item['codigo']) == strtoupper($datosGenerales['validez_unidad'])){
         echo '<option value="' . $item['codigo'] . '" selected="selected">' . $item['nombre'] . '</option>';
         }else{
         echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
         }
         }
         ?>
            </select>
         </div>

         <div data-linea="9" class="segidos noFarmacologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noKid noDesinfectantes noDinamicos">
            <label for="humedad">Humedad residual:</label>
            <input data-distribuir='no' value="<?php echo $datosGenerales['humedad'];?>" name="humedad" type="number" id="humedad" min="0" max="100" step="0.0001" data-er="^[0-9]+$" />
            <label data-distribuir='no'>%</label>
         </div>
         <div data-linea="10" class="noFarmacologicos noMedicados noAditivos noSales noCosmeticos noSnack noKid noDesinfectantes noDinamicos">
            <label for="estabilidad">Estabilidad de la emulsión:</label>
            <input value="<?php echo $datosGenerales['estabilidad'];?>" name="estabilidad" type="number" id="estabilidad" min="0" max="1999999999" data-er="^[0-9]+$" />

         </div>
         <div data-linea="10" class="noFarmacologicos noMedicados noAditivos noSales noCosmeticos noSnack noKid noDesinfectantes noDinamicos">
            <label for="estabilidad_unidad">Unidad:</label>
            <select name="estabilidad_unidad" id="estabilidad_unidad">
               <option value="">Seleccione....</option><?php
         foreach ($catalogoUnidadesTiempo as $key=>$item){
         if(strtoupper($item['codigo']) == strtoupper($datosGenerales['estabilidad_unidad'])){
         echo '<option value="' . $item['codigo'] . '" selected="selected">' . $item['nombre'] . '</option>';
         }else{
         echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
         }
         }
         ?>
            </select>
         </div>


         <div data-linea="12" class="noFarmacologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noKid noDesinfectantes noDinamicos">
            <label for="inmunidad">Tiempo para conferir inmunidad:</label>
            <input value="<?php echo $datosGenerales['inmunidad'];?>" name="inmunidad" type="number" id="inmunidad" min="0" max="1999999999" data-er="^[0-9]+$" />

         </div>
         <div data-linea="12" class="noFarmacologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noKid noDesinfectantes noDinamicos">
            <label for="inmunidad_unidad">Unidad:</label>
            <select name="inmunidad_unidad" id="inmunidad_unidad">
               <option value="">Seleccione....</option><?php
         foreach ($catalogoUnidadesTiempo as $key=>$item){
         if(strtoupper($item['codigo']) == strtoupper($datosGenerales['inmunidad_unidad'])){
         echo '<option value="' . $item['codigo'] . '" selected="selected">' . $item['nombre'] . '</option>';
         }else{
         echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
         }
         }
         ?>
            </select>
         </div>
         <div data-linea="14" class="noFarmacologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noKid noDesinfectantes noDinamicos">
            <label for="inmunidad_min">Duración mínima de la inmunidad:</label>
            <input value="<?php echo $datosGenerales['inmunidad_min'];?>" name="inmunidad_min" type="number" id="inmunidad_min" min="0" max="1999999999" data-er="^[0-9]+$" />

         </div>
         <div data-linea="14" class="noFarmacologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noKid noDesinfectantes noDinamicos">
            <label for="inmunidad_min_unidad">Unidad:</label>
            <select name="inmunidad_min_unidad" id="inmunidad_min_unidad">
               <option value="">Seleccione....</option><?php
         foreach ($catalogoUnidadesTiempo as $key=>$item){
         if(strtoupper($item['codigo']) == strtoupper($datosGenerales['inmunidad_min_unidad'])){
         echo '<option value="' . $item['codigo'] . '" selected="selected">' . $item['nombre'] . '</option>';
         }else{
         echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
         }
         }
         ?>
            </select>
         </div>
         <div class="justificado noFarmacologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noKid noDesinfectantes noDinamicos">
            <label for="inmunidad_ref">Referencia bibliográfica para la inmunidad:</label>
            <input value="<?php echo $datosGenerales['inmunidad_ref'];?>" name="inmunidad_ref" type="text" id="inmunidad_ref" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

      </fieldset>
  
      <button type="submit" class="guardar">Guardar solicitud</button>
   </form>
</div>

<div class="pestania" id="P8" style="display: block;">
     
	<fieldset>
		<legend>Presentaciones comerciales y tipos de envases</legend>
		<div data-linea="1">
			<label for="presentacion">Presentación:</label>
			<input value="" name="presentacion" type="text" id="presentacion" placeholder="Presentación"  maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
		</div>
		<div data-linea="2">
			<label for="cantidad_pres">Cantidad:</label>
			<input value="" name="cantidad_pres" type="number" id="cantidad_pres" min="0" max="9999999" step="0.01" data-er="^[0-9]+$"  />
			
		</div>
		<div data-linea="2">
			<label for="unidad_pres">Unidad:</label>
			
			<select name="unidad_pres" id="unidad_pres" >
				<option value="">Seleccione....</option>
							
				<?php
				foreach ($unidadesMedida as $key=>$item){
					echo '<option value="' . $item['id_unidad_medida'] . '">' . $item['nombre'] . '</option>';
				}
                ?>
			</select>
		</div>
		<div class="justificado">
			<label for="descripcion_pres">Descripción del envase:</label>
			<textarea  name="descripcion_pres"  id="descripcion_pres" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"></textarea>
		</div>
		<button id="btnAddPresentacion" type="button" class="mas">Añadir</button>
		<hr/>
		<table id="tblPresentacion" style="width:98%">
			<thead>
				<tr>
					<th>Sub Código</th>
					<th>Presentación</th>
					<th>Cantidad</th>
					<th>Unidad</th>
					<th>Descripción</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
                     
			</tbody>
		</table>
		<div data-linea="5">
			<label id="tblPresentacionProducto"></label>
		</div>
	</fieldset>

	<form id="nuevoCodigoSC" data-rutaAplicacion="dossierPecuario" data-opcion="guardarNuevoCodigoSC" >
		<input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>">
							
		<fieldset>
			<legend>Código complementario y suplementario</legend>
				
			<div data-linea="1">
				<label>Código complementario</label>
				<select id="codigoComplementario" name="codigoComplementario">
					<option value="0000">0000</option>
					<option value="0001">0001</option>
					<option value="0002">0002</option>
					<option value="0003">0003</option>
					<option value="0004">0004</option>
					<option value="0005">0005</option>
					<option value="0006">0006</option>
					<option value="0007">0007</option>
					<option value="0008">0008</option>
					<option value="0009">0009</option>
					<option value="0010">0010</option>
				</select>
			</div>
			<div data-linea="1">
				<label>Código suplementario</label>
				<select id="codigoSuplementario" name="codigoSuplementario">
					<option value="0000">0000</option>
					<option value="0001">0001</option>
					<option value="0002">0002</option>
					<option value="0003">0003</option>
					<option value="0004">0004</option>
					<option value="0005">0005</option>
					<option value="0006">0006</option>
					<option value="0007">0007</option>
					<option value="0008">0008</option>
					<option value="0009">0009</option>
					<option value="0010">0010</option>					
					<option value="0011">0011</option>
					<option value="0012">0012</option>
					<option value="0013">0013</option>
					<option value="0014">0014</option>
					<option value="0015">0015</option>
					<option value="0016">0016</option>
					<option value="0017">0017</option>
					<option value="0018">0018</option>
					<option value="0019">0019</option>
					<option value="0020">0020</option>
				</select>
			</div>
			<div data-linea="3">
				<button type="submit" class="mas">Añadir código</button>
			</div>

			
		</fieldset>				
	
	
	</form>

   <fieldset>
      <legend>Códigos ingresados</legend>
      <table id="codigoSC" style="width:100%">
         <thead>
            <tr>
               <th>Complementario</th>
               <th>Suplementario</th>
               <th></th>
            </tr>
         </thead>
         <tbody id="codigoSCss">         <?php
         while ($codigoAdicionales = pg_fetch_assoc($qCodigoAdicionales)){
         echo $cp->imprimirCodigoComplementarioSuplementario($codigoAdicionales['id_solicitud'], $codigoAdicionales['codigo_complementario'], $codigoAdicionales['codigo_suplementario']);
         }
         ?>
         </tbody>
      </table>
	</fieldset>
	
	<form id='frmNuevaSolicitud7' data-rutaAplicacion='dossierPecuario' data-opcion='guardarManejoSolicitud'>
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
   
		<fieldset>
         <legend>Manejo del producto</legend>
         <div data-linea="1">
            <label for="conservacion">Conservación del producto:</label>
            <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['conservacion'];?>" name="conservacion" type="text" id="conservacion" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="2" class=" noCosmeticos noSnack noDesinfectantes">
            <label for="almacenar_minimo">Temperatura mínima de almacenamiento [ºC]:</label>
               <input value="<?php echo $datosGenerales['almacenar_minimo'];?>" name="almacenar_minimo" type="number" id="almacenar_minimo" min="-273" max="999999" step="0.1" data-er="^[0-9]+$" />
           
         </div>
         <div data-linea="3" >
            <label for="almacenar_maximo">Temperatura máxima de almacenamiento [ºC]:</label>
            <input  value="<?php echo $datosGenerales['almacenar_maximo'];?>" name="almacenar_maximo" type="number" id="almacenar_maximo" min="-273" max="999999" step="0.1" data-er="^[0-9]+$"  />
         </div>
         <div data-linea="4">
            <label for="humedad_minima">Humedad mínima de almacenamiento:</label>
            <input value="<?php echo $datosGenerales['humedad_minima'];?>" name="humedad_minima" type="text" id="humedad_minima" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="5">
            <label for="humedad_maxima">Humedad máxima de almacenamiento:</label>
            <input value="<?php echo $datosGenerales['humedad_maxima'];?>" name="humedad_maxima" type="text" id="humedad_maxima" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
                 
         <div data-linea="6" class=" noFarmacologicos noBiologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noDesinfectantes noDinamicos">
            <label for="control_producto">Controles sobre el producto de diagnóstico de uso veterinario:</label>
            <textarea name="control_producto"  id="control_producto" maxlength="2048" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" ><?php echo trim(htmlspecialchars($datosGenerales['control_producto'])); ?></textarea>
         </div>
         <div data-linea="7" class="segidos noFarmacologicos noBiologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noDesinfectantes noDinamicos">
            <input type="hidden" id="tipos_anticuerpos" name="tipos_anticuerpos" value="<?php echo $datosGenerales['tipos_anticuerpos'];?>" />
            <label for="tiposAnticuerpos">Detección de anticuerpos de vacunación o infección:</label>
            <label>
               <input type="checkbox" class="radio" value="V" name="tiposAnticuerpos[]" />Vacunación
            </label>
            <label>
               <input type="checkbox" class="radio" value="I" name="tiposAnticuerpos[]" />Infección
            </label>  

         </div>
         <div data-linea="8" class="noFarmacologicos noBiologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noDesinfectantes noDinamicos">
            <label for="deteccion_anticuerpos"></label>
            <textarea name="deteccion_anticuerpos"  id="deteccion_anticuerpos" maxlength="2048" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" ><?php echo trim(htmlspecialchars($datosGenerales['deteccion_anticuerpos'])); ?></textarea>
         </div>
                  
         <div data-linea="9" class=" noFarmacologicos noBiologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noDesinfectantes noDinamicos">
            <label for="interpretacion">Resultados e interpretaciones:</label>
            <textarea name="interpretacion"  id="interpretacion" maxlength="2048" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" ><?php echo trim(htmlspecialchars($datosGenerales['interpretacion'])); ?></textarea>
         </div>
         <div data-linea="10" class=" noFarmacologicos noBiologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noDesinfectantes noDinamicos">
            <label for="eliminacion_envases">Forma y métodos de eliminación de envases:</label>
            <textarea name="eliminacion_envases"  id="eliminacion_envases" maxlength="2048" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" ><?php echo trim(htmlspecialchars($datosGenerales['eliminacion_envases'])); ?></textarea>
         </div>
         <div data-linea="11" class=" noFarmacologicos noBiologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noDinamicos">
            <label for="riesgo">Riesgo para la salud pública y el ambiente:</label>
            <textarea name="riesgo"  id="riesgo" maxlength="2048" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" ><?php echo htmlspecialchars($datosGenerales['riesgo']); ?></textarea>
         </div>
         <div data-linea="12" class=" noFarmacologicos noBiologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noKid noDinamicos">
            <label for="mecanismo_accion">Sitio y mecanismo de acción:</label>
            <textarea name="mecanismo_accion"  id="mecanismo_accion" maxlength="2048" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" ><?php echo trim(htmlspecialchars($datosGenerales['mecanismo_accion'])); ?></textarea>
            <input value="<?php echo $datosGenerales['mecanismo_accion_referencia'];?>" name="mecanismo_accion_referencia" type="text" id="mecanismo_accion_referencia" maxlength="128" placeholder="Ingrese la referencia bibliográfica" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="13" class=" noFarmacologicos noBiologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noDesinfectantes noDinamicos">
            <label for="microorganismos">Determinación de microorganismo (virus, bacterias, hongos), antígenos de campo o vacunal, recomendaciones para determinar serotipos:</label>
            <textarea name="microorganismos"  id="microorganismos" maxlength="2048" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" ><?php echo htmlspecialchars($datosGenerales['microorganismos']); ?></textarea>
         </div>
         <div data-linea="14" class="noFarmacologicos noBiologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack">
            <label for="modo_uso">Modo de uso del producto:</label>
            <textarea name="modo_uso"  id="modo_uso" maxlength="2048" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" ><?php echo trim(htmlspecialchars($datosGenerales['modo_uso'])); ?></textarea>
         </div>
         
         

      </fieldset> 
  
		<fieldset>
         <legend>Observaciones generales</legend>
         <div data-linea="1">
            <label for="observaciones">Observacion:</label>
            <textarea name="observaciones" id="observaciones" maxlength="2048" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo htmlspecialchars($datosGenerales['observaciones']); ?></textarea>
         </div>

      </fieldset>

    <button type="submit" class="guardar">Guardar solicitud</button>
   </form>
	
</div>

<div class="pestania" id="P9" style="display: block;">
	
	<form id="frmAnexos" data-rutaAplicacion="dossierPecuario" data-opcion="guardarArchivoAnexo" >
		<input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="fase" name="fase" value="solicitud">
      

      <fieldset class="noDesinfectantes noDinamicos">
         <legend>Anexo diagrama de flujo de elaboración</legend>
         <input type="hidden" id="tipoArchivo" name="tipoArchivo" value="AP_DF" />
         <div data-linea="1">
            <label for="referencia" class="opcional">Referencia para el documento:</label>
            <input value="<?php echo $anexoVector['AP_DF']['referencia']; ?>" type="text" id="referencia" name="referencia" placeholder="Incluir la referencia en el documento" class="referencia" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required />
         </div>
         <div data-linea="2">
            <label>Archivo adjunto</label>      <?php
      $anexo=$anexoVector['AP_DF']['path'];
      if($anexo=='0' || $anexo==''){
      echo '<span class="alerta" id="noHayArchivo">No hay ningún archivo adjunto</span>';
      echo '<a href="" target="_blank" class="archivo_cargado" id="archivo_cargado" style="display: none;">Archivo Cargado</a>';
      }
      else{
      echo '<span class="alerta" id="noHayArchivo" style="display: none;">No hay ningún archivo adjunto</span>';
      echo '<a href='.$anexo.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';
      }
      ?>
         </div>
         <div data-linea="3">
            <input type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
            <input type="hidden" class="maxCapacidad" value="<?php echo intval($anexosCapacidad['AP_DF']['nombre2'])*1024*1024; ?>" />
            <input type="file" class="archivo" accept="application/msword | application/pdf | image/*" disabled="disabled" />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo $anexosCapacidad['AP_DF']['nombre2'].'M'; ?>B)</div>
            <button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo $pathAnexo;?>" disabled="disabled">Subir archivo</button>
         </div>
         <div data-linea="5">
            <label id="archivoAP_DF"></label>
         </div>
      </fieldset>
      
		<fieldset id="verAnexoClv" >
         <legend>CLV / Certificado de exportabilidad con vigencia de 1 año / Carta de autorización legalizada / Presentaciones</legend>
         <input type="hidden" id="tipoArchivo" name="tipoArchivo" value="AP_CLV" />
         <div data-linea="1">
            <label for="referencia" class="opcional">Referencia para el documento:</label>
            <input value="<?php echo $anexoVector['AP_CLV']['referencia']; ?>" type="text" id="referencia" name="referencia" placeholder="Incluir la referencia en el documento" class="referencia" maxlength="64" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required />
         </div>
			<div data-linea="2">
            <label>Archivo adjunto</label> 
				<?php
				$anexo=$anexoVector['AP_CLV']['path'];
				if($anexo=='0' || $anexo==''){
					echo '<span class="alerta" id="noHayArchivo">No hay ningún archivo adjunto</span>';
					echo '<a href="" target="_blank" class="archivo_cargado" id="archivo_cargado" style="display: none;">Archivo Cargado</a>';
				}
				else{
					echo '<span class="alerta" id="noHayArchivo" style="display: none;">No hay ningún archivo adjunto</span>';
					echo '<a href='.$anexo.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';
				}
                ?>
         </div>
         <div data-linea="3">
            <input type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
				<input type="hidden" class="maxCapacidad" value="<?php echo intval($anexosCapacidad['AP_CLV']['nombre2'])*1024*1024; ?>" />
            <input type="file" class="archivo" accept="application/msword | application/pdf | image/*" disabled="disabled" />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo $anexosCapacidad['AP_CLV']['nombre2'].'M'; ?>B)</div>
            <button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo $pathAnexo;?>" disabled="disabled">Subir archivo</button>
         </div>
         <div data-linea="5">
            <label id="archivoAP_CLV"></label>
         </div>
      </fieldset>
     
		<fieldset class="noDinamicos">
         <legend>Métodos de control</legend>
         <input type="hidden" id="tipoArchivo" name="tipoArchivo" value="AP_MC" />
         <div class=" justificado">
            <label for="referencia" class="opcional">Referencia para el documento:</label>
            <input value="<?php echo $anexoVector['AP_MC']['referencia']; ?>" type="text" id="referencia" name="referencia" placeholder="Incluir la referencia en el documento" class="referencia" maxlength="64" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required />
         </div>
         <div data-linea="2">
            <label>Archivo adjunto</label>      <?php
      $anexo=$anexoVector['AP_MC']['path'];
      if($anexo=='0' || $anexo==''){
      echo '<span class="alerta" id="noHayArchivo">No hay ningún archivo adjunto</span>';
      echo '<a href="" target="_blank" class="archivo_cargado" id="archivo_cargado" style="display: none;">Archivo Cargado</a>';
      }
      else{
      echo '<span class="alerta" id="noHayArchivo" style="display: none;">No hay ningún archivo adjunto</span>';
      echo '<a href='.$anexo.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';
      }
      ?>
         </div>
         <div data-linea="3">
            <input type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
            <input type="hidden" class="maxCapacidad" value="<?php echo intval($anexosCapacidad['AP_MC']['nombre2'])*1024*1024; ?>" />
            <input type="file" class="archivo" accept="application/msword | application/pdf | image/*" disabled="disabled" />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo $anexosCapacidad['AP_MC']['nombre2'].'M'; ?>B)</div>
            <button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo $pathAnexo;?>" disabled="disabled">Subir archivo</button>
         </div>
         <div data-linea="5">
            <label id="archivoAP_MC"></label>
         </div>
      </fieldset>
      
		<fieldset class="noBiologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noKid noDesinfectantes noDinamicos">
         <legend>Ficha toxicológica de los componentes</legend>
         <input type="hidden" id="tipoArchivo" name="tipoArchivo" value="AP_FT" />
         <div class=" justificado">
            <label for="referencia" class="opcional">Referencia para el documento:</label>
            <input value="<?php echo $anexoVector['AP_FT']['referencia']; ?>" type="text" id="referencia" name="referencia" placeholder="Incluir la referencia en el documento" class="referencia" maxlength="64" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required />
         </div>
         <div data-linea="2">
            <label>Archivo adjunto</label>      <?php
      $anexo=$anexoVector['AP_FT']['path'];
      if($anexo=='0' || $anexo==''){
      echo '<span class="alerta" id="noHayArchivo">No hay ningún archivo adjunto</span>';
      echo '<a href="" target="_blank" class="archivo_cargado" id="archivo_cargado" style="display: none;">Archivo Cargado</a>';
      }
      else{
      echo '<span class="alerta" id="noHayArchivo" style="display: none;">No hay ningún archivo adjunto</span>';
      echo '<a href='.$anexo.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';
      }
      ?>
         </div>
         <div data-linea="3">
            <input type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
            <input type="hidden" class="maxCapacidad" value="<?php echo intval($anexosCapacidad['AP_FT']['nombre2'])*1024*1024; ?>" />
            <input type="file" class="archivo" accept="application/msword | application/pdf | image/*" disabled="disabled" />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo $anexosCapacidad['AP_FT']['nombre2'].'M'; ?>B)</div>
            <button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo $pathAnexo;?>" disabled="disabled">Subir archivo</button>
         </div>
         <div data-linea="5">
            <label id="archivoAP_FT"></label>
         </div>
      </fieldset>
      
		<fieldset>
         <legend>Rotulado / Artes finales / Insertos adjuntos</legend>
         <input type="hidden" id="tipoArchivo" name="tipoArchivo" value="AP_RAI" />
         <div class="justificado">
            <label for="referencia" class="opcional">Referencia para el documento:</label>
            <input value="<?php echo $anexoVector['AP_RAI']['referencia']; ?>" type="text" id="referencia" name="referencia" placeholder="Incluir la referencia en el documento" class="referencia" maxlength="64" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required />
         </div>
         <div data-linea="2">
            <label>Archivo adjunto</label>      <?php
      $anexo=$anexoVector['AP_RAI']['path'];
      if($anexo=='0' || $anexo==''){
      echo '<span class="alerta" id="noHayArchivo">No hay ningún archivo adjunto</span>';
      echo '<a href="" target="_blank" class="archivo_cargado" id="archivo_cargado" style="display: none;">Archivo Cargado</a>';
      }
      else{
      echo '<span class="alerta" id="noHayArchivo" style="display: none;">No hay ningún archivo adjunto</span>';
      echo '<a href='.$anexo.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';
      }
      ?>

         </div>
         <div data-linea="3">
            <input type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
            <input type="hidden" class="maxCapacidad" value="<?php echo intval($anexosCapacidad['AP_RAI']['nombre2'])*1024*1024; ?>" />
            <input type="file" class="archivo" accept="application/msword | application/pdf | image/*" disabled="disabled" />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo $anexosCapacidad['AP_RAI']['nombre2'].'M'; ?>B)</div>
            <button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo $pathAnexo;?>" disabled="disabled">Subir archivo</button>
         </div>
         <div data-linea="5">
            <label id="archivoAP_RAI"></label>
         </div>
      </fieldset>
      
		<fieldset class="noSnack noDinamicos">
         <legend>Prueba de eficacia / Trabajos científicos / Monografías</legend>
         <input type="hidden" id="tipoArchivo" name="tipoArchivo" value="AP_PETC" />
         <div class=" justificado">
            <label for="referencia" class="opcional">Referencia para el documento:</label>
            <input value="<?php echo $anexoVector['AP_PETC']['referencia']; ?>" type="text" id="referencia" name="referencia" placeholder="Incluir la referencia en el documento" class="referencia" maxlength="64" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required />
         </div>
         <div data-linea="2">
            <label>Archivo adjunto</label>      <?php
      $anexo=$anexoVector['AP_PETC']['path'];
      if($anexo=='0' || $anexo==''){
      echo '<span class="alerta" id="noHayArchivo">No hay ningún archivo adjunto</span>';
      echo '<a href="" target="_blank" class="archivo_cargado" id="archivo_cargado" style="display: none;">Archivo Cargado</a>';
      }
      else{
      echo '<span class="alerta" id="noHayArchivo" style="display: none;">No hay ningún archivo adjunto</span>';
      echo '<a href='.$anexo.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';
      }
      ?>
         </div>
         <div data-linea="3">
            <input type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
            <input type="hidden" class="maxCapacidad" value="<?php echo intval($anexosCapacidad['AP_PETC']['nombre2'])*1024*1024; ?>" />
            <input type="file" class="archivo" accept="application/msword | application/pdf | image/*" disabled="disabled" />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo $anexosCapacidad['AP_PETC']['nombre2'].'M'; ?>B)</div>
            <button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo $pathAnexo;?>" disabled="disabled">Subir archivo</button>
         </div>
         <div data-linea="5">
            <label id="archivoAP_PETC"></label>
         </div>
      </fieldset>

      <fieldset class="noFarmacologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noKid noDesinfectantes noDinamicos">
         <legend>Control de calidad y pureza</legend>
         <input type="hidden" id="tipoArchivo" name="tipoArchivo" value="AP_CCP" />
         <div class=" justificado">
            <label for="referencia" class="opcional">Referencia para el documento:</label>
            <input value="<?php echo $anexoVector['AP_CCP']['referencia']; ?>" type="text" id="referencia" name="referencia" placeholder="Incluir la referencia en el documento" class="referencia" maxlength="64" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required />
         </div>
         <div data-linea="2">
            <label>Archivo adjunto</label>      <?php
      $anexo=$anexoVector['AP_CCP']['path'];
      if($anexo=='0' || $anexo==''){
      echo '<span class="alerta" id="noHayArchivo">No hay ningún archivo adjunto</span>';
      echo '<a href="" target="_blank" class="archivo_cargado" id="archivo_cargado" style="display: none;">Archivo Cargado</a>';
      }
      else{
      echo '<span class="alerta" id="noHayArchivo" style="display: none;">No hay ningún archivo adjunto</span>';
      echo '<a href='.$anexo.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';
      }
      ?>
         </div>
         <div data-linea="3">
            <input type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
            <input type="hidden" class="maxCapacidad" value="<?php echo intval($anexosCapacidad['AP_CCP']['nombre2'])*1024*1024; ?>" />
            <input type="file" class="archivo" accept="application/msword | application/pdf | image/*" disabled="disabled" />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo $anexosCapacidad['AP_CCP']['nombre2'].'M'; ?>B)</div>
            <button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo $pathAnexo;?>" disabled="disabled">Subir archivo</button>
         </div>
         <div data-linea="5">
            <label id="archivoAP_CCP"></label>
         </div>
      </fieldset>
      
		<fieldset class="noFarmacologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noKid noDesinfectantes noDinamicos">
         <legend>Control de eficacia inmunológica y potencia</legend>
         <input type="hidden" id="tipoArchivo" name="tipoArchivo" value="AP_CEIP" />
         <div class=" justificado">
            <label for="referencia" class="opcional">Referencia para el documento:</label>
            <input value="<?php echo $anexoVector['AP_CEIP']['referencia']; ?>" type="text" id="referencia" name="referencia" placeholder="Incluir la referencia en el documento" class="referencia" maxlength="64" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required />
         </div>
         <div data-linea="2">
            <label>Archivo adjunto</label>      <?php
      $anexo=$anexoVector['AP_CEIP']['path'];
      if($anexo=='0' || $anexo==''){
      echo '<span class="alerta" id="noHayArchivo">No hay ningún archivo adjunto</span>';
      echo '<a href="" target="_blank" class="archivo_cargado" id="archivo_cargado" style="display: none;">Archivo Cargado</a>';
      }
      else{
      echo '<span class="alerta" id="noHayArchivo" style="display: none;">No hay ningún archivo adjunto</span>';
      echo '<a href='.$anexo.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';
      }
      ?>
         </div>
         <div data-linea="3">
            <input type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
            <input type="hidden" class="maxCapacidad" value="<?php echo intval($anexosCapacidad['AP_CEIP']['nombre2'])*1024*1024; ?>" />
            <input type="file" class="archivo" accept="application/msword | application/pdf | image/*" disabled="disabled" />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo $anexosCapacidad['AP_CEIP']['nombre2'].'M'; ?>B)</div>
            <button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo $pathAnexo;?>" disabled="disabled">Subir archivo</button>
         </div>
         <div data-linea="5">
            <label id="archivoAP_CEIP"></label>
         </div>
      </fieldset>
      
		<fieldset class="noDinamicos">
         <legend>Estudio de estabilidad / junto con la ficha técnica de los envases</legend>
         <input type="hidden" id="tipoArchivo" name="tipoArchivo" value="AP_EEFT" />
         <div class=" justificado">
            <label for="referencia" class="opcional">Referencia para el documento:</label>
            <input value="<?php echo $anexoVector['AP_EEFT']['referencia']; ?>" type="text" id="referencia" name="referencia" placeholder="Incluir la referencia en el documento" class="referencia" maxlength="64" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required />
         </div>
         <div data-linea="2">
            <label>Archivo adjunto</label>      <?php
      $anexo=$anexoVector['AP_EEFT']['path'];
      if($anexo=='0' || $anexo==''){
      echo '<span class="alerta" id="noHayArchivo">No hay ningún archivo adjunto</span>';
      echo '<a href="" target="_blank" class="archivo_cargado" id="archivo_cargado" style="display: none;">Archivo Cargado</a>';
      }
      else{
      echo '<span class="alerta" id="noHayArchivo" style="display: none;">No hay ningún archivo adjunto</span>';
      echo '<a href='.$anexo.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';
      }
      ?>
         </div>
         <div data-linea="3">
            <input type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
            <input type="hidden" class="maxCapacidad" value="<?php echo intval($anexosCapacidad['AP_EEFT']['nombre2'])*1024*1024; ?>" />
            <input type="file" class="archivo" accept="application/msword | application/pdf | image/*" disabled="disabled" />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo $anexosCapacidad['AP_EEFT']['nombre2'].'M'; ?>B)</div>
            <button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo $pathAnexo;?>" disabled="disabled">Subir archivo</button>
         </div>
         <div data-linea="5">
            <label id="archivoAP_EEFT"></label>
         </div>
      </fieldset>
      
		<fieldset>
         <legend>Certificado de análisis del producto</legend>
         <input type="hidden" id="tipoArchivo" name="tipoArchivo" value="AP_CAP" />
         <div class=" justificado">
            <label for="referencia" class="opcional">Referencia para el documento:</label>
            <input value="<?php echo $anexoVector['AP_CAP']['referencia']; ?>" type="text" id="referencia" name="referencia" placeholder="Incluir la referencia en el documento" class="referencia subsanarFarmacologico" maxlength="64" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required />
         </div>
         <div data-linea="2">
            <label>Archivo adjunto</label>      <?php
      $anexo=$anexoVector['AP_CAP']['path'];
      if($anexo=='0' || $anexo==''){
      echo '<span class="alerta" id="noHayArchivo">No hay ningún archivo adjunto</span>';
      echo '<a href="" target="_blank" class="archivo_cargado" id="archivo_cargado" style="display: none;">Archivo Cargado</a>';
      }
      else{
      echo '<span class="alerta" id="noHayArchivo" style="display: none;">No hay ningún archivo adjunto</span>';
      echo '<a href='.$anexo.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';
      }
      ?>
         </div>
         <div data-linea="3">
            <input type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
            <input type="hidden" class="maxCapacidad" value="<?php echo intval($anexosCapacidad['AP_CAP']['nombre2'])*1024*1024; ?>" />
            <input type="file" class="archivo" accept="application/msword | application/pdf | image/*" disabled="disabled" />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo $anexosCapacidad['AP_CAP']['nombre2'].'M'; ?>B)</div>
            <button type="button" class="subirArchivo adjunto subsanarFarmacologico" data-rutaCarga="<?php echo $pathAnexo;?>" disabled="disabled">Subir archivo</button>
         </div>
         <div data-linea="5">
            <label id="archivoAP_CAP"></label>
         </div>
      </fieldset>
      
		<fieldset class="noBiologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noKid noDesinfectantes noDinamicos">
         <legend>Método analítico de acuerdo al anexo 4 o 5(Resoluc. 003)</legend>
         <input type="hidden" id="tipoArchivo" name="tipoArchivo" value="AP_MAAA" />
         <div class=" justificado">
            <label for="referencia" class="opcional">Referencia para el documento:</label>
            <input value="<?php echo $anexoVector['AP_MAAA']['referencia']; ?>" type="text" id="referencia" name="referencia" placeholder="Incluir la referencia en el documento" class="referencia subsanarFarmacologico" maxlength="64" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required />
         </div>
         <div data-linea="2">
            <label>Archivo adjunto</label>      <?php
      $anexo=$anexoVector['AP_MAAA']['path'];
      if($anexo=='0' || $anexo==''){
      echo '<span class="alerta" id="noHayArchivo">No hay ningún archivo adjunto</span>';
      echo '<a href="" target="_blank" class="archivo_cargado" id="archivo_cargado" style="display: none;">Archivo Cargado</a>';
      }
      else{
      echo '<span class="alerta" id="noHayArchivo" style="display: none;">No hay ningún archivo adjunto</span>';
      echo '<a href='.$anexo.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';
      }
      ?>
         </div>
         <div data-linea="3">
            <input type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
            <input type="hidden" class="maxCapacidad" value="<?php echo intval($anexosCapacidad['AP_MAAA']['nombre2'])*1024*1024; ?>" />
            <input type="file" class="archivo" accept="application/msword | application/pdf | image/*" disabled="disabled" />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo $anexosCapacidad['AP_MAAA']['nombre2'].'M'; ?>B)</div>
            <button type="button" class="subirArchivo adjunto subsanarFarmacologico" data-rutaCarga="<?php echo $pathAnexo;?>" disabled="disabled">Subir archivo</button>
         </div>
         <div data-linea="5">
            <label id="archivoAP_MAAA"></label>
         </div>
      </fieldset>
      
		<fieldset class="noFarmacologicos noBiologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noDesinfectantes noDinamicos">
         <legend>Prueba de validación de los kits o certificados de análisis de cada componente utilizado en la prueba</legend>
         <input type="hidden" id="tipoArchivo" name="tipoArchivo" value="AP_PRVA" />
         <div class=" justificado">
            <label for="referencia" class="opcional">Referencia para el documento:</label>
            <input value="<?php echo $anexoVector['AP_PRVA']['referencia']; ?>" type="text" id="referencia" name="referencia" placeholder="Incluir la referencia en el documento" class="referencia" maxlength="64" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required />
         </div>
         <div data-linea="2">
            <label>Archivo adjunto</label>      <?php
      $anexo=$anexoVector['AP_PRVA']['path'];
      if($anexo=='0' || $anexo==''){
      echo '<span class="alerta" id="noHayArchivo">No hay ningún archivo adjunto</span>';
      echo '<a href="" target="_blank" class="archivo_cargado" id="archivo_cargado" style="display: none;">Archivo Cargado</a>';
      }
      else{
      echo '<span class="alerta" id="noHayArchivo" style="display: none;">No hay ningún archivo adjunto</span>';
      echo '<a href='.$anexo.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';
      }
      ?>
         </div>
         <div data-linea="3">
            <input type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
            <input type="hidden" class="maxCapacidad" value="<?php echo intval($anexosCapacidad['AP_PRVA']['nombre2'])*1024*1024; ?>" />
            <input type="file" class="archivo" accept="application/msword | application/pdf | image/*" disabled="disabled" />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo $anexosCapacidad['AP_PRVA']['nombre2'].'M'; ?>B)</div>
            <button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo $pathAnexo;?>" disabled="disabled">Subir archivo</button>
         </div>
         <div data-linea="5">
            <label id="archivoAP_PRVA"></label>
         </div>
      </fieldset>

      <fieldset id="verAnexoJustificacion" class="noBiologicos noFarmacologicos noMedicados noCompletos noAditivos noSales noCosmeticos noSnack noKid noDesinfectantes">
         <legend>Justificación de medicamentos utilizados</legend>
         <input type="hidden" id="tipoArchivo" name="tipoArchivo" value="AP_JMU" />
         <div class=" justificado">
            <label for="referencia" class="opcional">Referencia para el documento:</label>
            <input value="<?php echo $anexoVector['AP_JMU']['referencia']; ?>" type="text" id="referencia" name="referencia" placeholder="Incluir la referencia en el documento" class="referencia" maxlength="64" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required />
         </div>
         <div data-linea="2">
            <label>Archivo adjunto</label>      <?php
      $anexo=$anexoVector['AP_JMU']['path'];
      if($anexo=='0' || $anexo==''){
      echo '<span class="alerta" id="noHayArchivo">No hay ningún archivo adjunto</span>';
      echo '<a href="" target="_blank" class="archivo_cargado" id="archivo_cargado" style="display: none;">Archivo Cargado</a>';
      }
      else{
      echo '<span class="alerta" id="noHayArchivo" style="display: none;">No hay ningún archivo adjunto</span>';
      echo '<a href='.$anexo.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';
      }
      ?>
         </div>
         <div data-linea="3">
            <input type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
            <input type="hidden" class="maxCapacidad" value="<?php echo intval($anexosCapacidad['AP_JMU']['nombre2'])*1024*1024; ?>" />
            <input type="file" class="archivo" accept="application/msword | application/pdf | image/*" disabled="disabled" />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo $anexosCapacidad['AP_JMU']['nombre2'].'M'; ?>B)</div>
            <button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo $pathAnexo;?>" disabled="disabled">Subir archivo</button>
         </div>
         <div data-linea="5">
            <label id="archivoAP_JMU"></label>
         </div>
      </fieldset>

	<fieldset class="verInformeTecnico">
        <legend>Informe técnico de laboratorio</legend>
         <input type="hidden" id="tipoArchivo" name="tipoArchivo" value="AP_ITL" />
         <div class=" justificado">
            <label for="referencia" class="opcional">Referencia para el documento:</label>
            <input value="<?php echo $anexoVector['AP_ITL']['referencia']; ?>" type="text" id="referencia" name="referencia" placeholder="Incluir la referencia en el documento" class="referencia" maxlength="64" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required />
         </div>
         <div data-linea="2">
            <label>Archivo adjunto</label>      
            <?php
              
              $anexo=$anexoVector['AP_ITL']['path'];
              if($anexo=='0' || $anexo==''){
              echo '<span class="alerta" id="noHayArchivo">No hay ningún archivo adjunto</span>';
              echo '<a href="" target="_blank" class="archivo_cargado" id="archivo_cargado" style="display: none;">Archivo Cargado</a>';
              }
              else{
              echo '<span class="alerta" id="noHayArchivo" style="display: none;">No hay ningún archivo adjunto</span>';
              echo '<a href='.$anexo.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';
              }
              ?>
         </div>
         <div data-linea="3">
         	<?php 
         	  $observacionesTecnico="";
         	  foreach ($observacionesLaboratorioFarmacos as $observacion){
         	      $observacionesTecnico=$observacionesTecnico.$observacion['observacion']."\n";
         	  }
         	?>
         	<label>Observaciones</label>
         	<textarea data-distribuir="no"><?php echo trim($observacionesTecnico);?></textarea>
         </div>
         
      </fieldset>

	</form>
	
   <form id="frmNuevaSolicitud8" data-rutaAplicacion="dossierPecuario" data-opcion="guardarFaseArchivosAnexos">
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <button type="submit" class="guardar">Guardar solicitud</button>
   </form>
</div>

<div class="pestania" id="P10" style="display: block;">
	<form id="frmFinalizarSolicitud9" data-rutaAplicacion="dossierPecuario" data-opcion="finalizarSolicitud" data-accionEnExito = 'ACTUALIZAR'>
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="id_flujo" name="id_flujo" value="<?php echo $id_flujo;?>" />
      

		<fieldset>
			<legend>Finalizar solicitud</legend>
		
			<div class="justificado">
				<label for="observacion" >Condiciones de la información:</label>
				<br/>
				
            <label  id="observacion" >
					<?php
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
			
			<div data-linea="3" class="noRevision">
				<label for="boolAcepto">Acepto las condiciones</label>
				<input type="checkbox" id="boolAcepto" name="boolAcepto" value="NO">
					
			</div>
		</fieldset>
		<button id="btnFinalizar" type="button" class="guardar">Finalizar</button>
	</form>
	<form id='frmVistaPreviaDossier' data-rutaAplicacion='dossierPecuario' data-opcion=''>		
		<button id="btnVistaPreviaDossier" type="button" class="documento btnVistaPreviaDossier">Generar vista previa</button>
		<a id="verReporteDossier" href="" target="_blank" style="display:none">Ver archivo</a>
	</form>
	
</div>

<script type="text/javascript" src="aplicaciones/ensayoEficacia/funciones/generales.js"></script>


<script type="text/javascript">

	var solicitud=<?php echo json_encode($datosGenerales); ?>;
	var sitiosAreas=<?php echo json_encode($sitiosAreas); ?>;
	var operadoresFabricantes=<?php echo json_encode($operadoresFabricantes); ?>;
	var clasificaciones=<?php echo json_encode($clasificaciones); ?>;
	var fabricantesExtranjeros=<?php echo json_encode($fabricantesExtranjeros); ?>;
	var fabricantesDossier =<?php echo json_encode($fabricantesDossier); ?>;
	var subtiposGrupos =<?php echo json_encode($subtiposGrupos); ?>;

	var composicionIA=<?php echo json_encode($composicionIA); ?>;

	var catalogoUsos=<?php echo json_encode($catalogoUsos); ?>;
	var codificacionUsos=<?php echo json_encode($codificacionUsos); ?>;
	var especiesTipo=<?php echo json_encode($especiesTipo); ?>;
	var especies=<?php echo json_encode($especies); ?>;
	var viasAdmin=<?php echo json_encode($viasAdmin); ?>;
	var catalogoViasAdmin=<?php echo json_encode($catalogoViasAdmin); ?>;
	var catalogoUnidades1=<?php echo json_encode($catalogoUnidades1); ?>;
	var catalogoUnidades2=<?php echo json_encode($catalogoUnidades2); ?>;

	var catalogoUnidadesTiempo=<?php echo json_encode($catalogoUnidadesTiempo); ?>;
	var dosisProducto=<?php echo json_encode($dosisProducto); ?>;
	var efectosNoDeseados=<?php echo json_encode($efectosNoDeseados); ?>;
	var efectosSolicitud=<?php echo json_encode($efectosSolicitud); ?>;
	var especiesConsumibles=<?php echo json_encode($especiesConsumibles); ?>;
	var especiesPecuarios=<?php echo json_encode($especiesPecuarios); ?>;
	var periodosRetirosSolicitud=<?php echo json_encode($periodosRetirosSolicitud); ?>;
	var presentacionesSolicitud=<?php echo json_encode($presentacionesSolicitud); ?>;

	var unidadesMedidaCepas=<?php echo json_encode($unidadesMedidaCepas); ?>;
	var unidadesMedida=<?php echo json_encode($unidadesMedida); ?>;

	var datosTablaProcedencia=<?php echo json_encode($datosTablaProcedencia); ?>;
	var tieneExtranjero=<?php echo json_encode($tieneExtranjero); ?>;

	var paisEcuador=<?php echo json_encode($paisEcuador); ?>;



	var fabricantesReferencia={};
	var especiesElegidas=[];

	acciones("#nuevoCodigoSC","#codigoSC");

	//***************************** VISTA PREVIA DOSSIER***************************************
	$('button.btnVistaPreviaDossier').click(function (event) {

		event.preventDefault();

		var form=$(this).parent();
		form.append("<input type='hidden' id='id_solicitud' name='id_solicitud' value='"+solicitud.id_solicitud+"' />"); // añade el nivel del formulario
		form.append("<input type='hidden' id='id_protocolo' name='id_protocolo' value='"+$('#protocolo').val()+"' />"); // añade el nivel del formulario
		form.append("<input type='hidden' id='producto_nombre' name='producto_nombre' value='"+$('#producto_nombre').val()+"' />");
		form.append("<input type='hidden' id='normativa' name='normativa' value='"+$('#normativa').val()+"' />");
		form.append("<input type='hidden' id='ingrediente_activo' name='ingrediente_activo' value='"+$('#producto_ia').html()+"' />");
		form.append("<input type='hidden' id='ingredientes_paises' name='ingredientes_paises' value='"+$('#producto_pais').val()+"' />");
		form.append("<input type='hidden' id='usos' name='usos' value='"+$('#producto_uso').val()+"' />");
		form.append("<input type='hidden' id='formulacion' name='formulacion' value='"+$('#producto_formulacion').val()+"' />");
		form.append("<input type='hidden' id='formuladores_paises' name='formuladores_paises' value='"+$('#producto_pais_producto').val()+"' />");

		form.attr('data-opcion', 'crearDossierPecuario');

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
	//********************************************************************

	$("[name='tiposAnticuerpos[]']").change(function(){
		atenderPulsarCheckbox("[name='tiposAnticuerpos[]']",$(this), "#tipos_anticuerpos");

	});

	function atenderPulsarCheckbox(elementos,elementoPulsado,campoActualizar){
		var vc = $(elementos+":checked").map(function () {
			return this.value;
		}).get();
		vc=vc.join(',');
		$(campoActualizar).val(vc);
	}

	function mostrarCheckboxRecuperados(cadenaComas, elemento,valorValidar,campoMostrarSiValido){
		if(cadenaComas!=null && cadenaComas.trim()!="" && cadenaComas.trim()!='0'){
			var arr=cadenaComas.split(',');
			var boolValidar=false;
			$(elemento).each(function(){
				var ck=$(this);
				$(this).prop("checked", false );

				$.each(arr, function(index, value) {
					if(value==ck.val()){
						ck.prop("checked", true );

						if(valorValidar!=null && value==valorValidar)
							boolValidar=true;
					}
				});
			});

			if(campoMostrarSiValido!=null)
			{
				if(boolValidar==true)
					$(campoMostrarSiValido).show();
				else
					$(campoMostrarSiValido).hide();
			}
		}
	}


	//********************* CONFIGURACION *******************************


	$("document").ready(function(){

		construirAnimacion(".pestania");

		distribuirLineas();

		

		//deshabilita el boton siguiente de desplamiento para obligar a guardar el documento
		$('.bsig').attr("disabled", "disabled");

		//habilita los botones según estado del documento

		try{
			try{
				reconocerNivel(solicitud.nivel);
			}catch(e){}

			try{
				verDatosDatosRepresentanteTecnico();
			}catch(e){}

			try{

				verAreaTipoFabricante(solicitud.es_fabricante);
			}catch(e){}

			try{
				llenarFabricantes(fabricantesDossier);
			}catch(e){}

			try{
				verNuevaCepa(solicitud.es_nueva_cepa=='t');
			}catch(e){}

			try{
				actualizarClasificacion();
			}catch(e){}

			try{
				actualizaGrupos();
			}catch(e){}

			try{

				verEspecieTipos();
			}catch(e){}


			try{

				if(solicitud.tiene_categoria_toxicologica!=null && solicitud.tiene_categoria_toxicologica=="t")
					$('.categoria_toxicologicaVer').show();
				else
					$('.categoria_toxicologicaVer').hide();
			}catch(e){}
			try{
				actualizaUsos();
			}catch(e){}

			try{
				llenarComposicion(composicionIA);
			}catch(e){}

			try{
				actualizarVias();
			}catch(e){}

			try{
				verDosis(dosisProducto);
			}catch(e){}

			try{
				verEfectosNoDeseados(efectosSolicitud);
			}catch(e){}
			try{
				verTiemposRetiro(periodosRetirosSolicitud);
			}catch(e){}

			try{
				verPresentacion(presentacionesSolicitud);
			}catch(e){}

			try{
				valoresRecuperados();
			}catch(e){}

			try{
				verSegunTipos(solicitud.id_subtipo_producto);
			}catch(e){}

			try{
				verFabricantesProcedencia(datosTablaProcedencia);
				verAnexoCLV(tieneExtranjero);
				var subTipo=$("#id_subtipo_producto option[value="+solicitud.id_subtipo_producto+"]").data('codigo');
				if(solicitud.estado==null || solicitud.estado=="" || solicitud.estado=='solicitud' || subTipo !='RIP-FAR'){
					$('.verInformeTecnico').hide();
				}
				else{
					$('.verInformeTecnico').show();
				}
			}catch(e){}



		}catch(e){}

	});

	$('.bsig').click(function () {
		$("#estado").html('');
		$("#estado").removeClass();

	});

	$('.bant').click(function () {
		$("#estado").html('');
		$("#estado").removeClass();

	});

	function valoresRecuperados(){
		try{
			$("#id_clasificacion_subtipo option[value="+ solicitud.id_clasificacion_subtipo +"]").attr("selected",true);
		}catch(e){}


		$(".requiere_preparacionView").hide();

		verUsos();

		try{
			mostrarCheckboxRecuperados(solicitud.tipos_anticuerpos,"[name='tiposAnticuerpos[]']",null,null);
		}catch(e){}
	}

	function verRequierePreparacion(){
		try{
			if(solicitud.requiere_preparacion!=null && solicitud.requiere_preparacion=='t')
				$(".requiere_preparacionView").show();
			else{
				$(".requiere_preparacionView").hide();
			}
		}catch(e){}
	}

	function verSegunTipos(idSubTipo){
		var subTipo=$("#id_subtipo_producto option[value="+idSubTipo+"]").data('codigo');
		$('.noFarmacologicos, .noBiologicos, .noMedicados, .noCompletos, .noAditivos, .noSales, .noCosmeticos, .noSnack, .noKid, .noDesinfectantes, .noDinamicos').show();
		$('#usos_diagnostico_ver').hide();

		switch(subTipo){
			case "RIP-FAR":								//farmacológicos
				$('.noFarmacologicos').hide();
				verRequierePreparacion();
				break;
			case "RIP-BIO":
				$('.noBiologicos').hide();
				verRequierePreparacion();
				break;
			case "RIP-AM":								//
				$('.noMedicados').hide();
				break;
			case "RIP-AC":								//
				$('.noCompletos').hide();
				break;
			case "RIP-AD":								//
				$('.noAditivos').hide();
				break;
			case "RIP-SP":								//
				$('.noSales').hide();
				break;
			case "RIP-COS":								//
				$('.noCosmeticos').hide();
				verRequierePreparacion();
				break;
			case "RIP-SNK":								//
				$('.noSnack').hide();
				break;
			case "RIP-KD":								//
				$('.noKid').hide();
				$('#usos_diagnostico_ver').show();
				break;
			case "RIP-DAS":								//
				$('.noDesinfectantes').hide();
				verRequierePreparacion();
				break;
			case "RIP-DIN":								//
				$('.noDinamicos').hide();
				//bloquea los fabricantes
				verAreaTipoFabricante('N');


				break;
		}

	}

	//******************************************* ANEXOS **********************************************************



	$(".referencia").keyup(function(){
		var el=$(this);
		var fld=el.parent().parent();
		if(el.val().trim()!=""){
			fld.find(".archivo").removeAttr("disabled");
			fld.find("button.subirArchivo").removeAttr("disabled");
		}
		else{
			fld.find(".archivo").attr("disabled", "disabled");
			fld.find("button.subirArchivo").attr("disabled", "disabled");
		}
	});


	$('button.subirArchivo').click(function (event) {
		event.preventDefault();

		var boton = $(this);
		var str=boton.parent().parent().find("#referencia").val();

		error=false;
		if(str==null || str=='')
			error=true;
		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();


		$('#a_referencia').val(str);
		str=str.replace(/[^a-zA-Z0-9.]+/g,'');
		var tipoArchivo=boton.parent().parent().find("#tipoArchivo").val();
		tipoArchivo=tipoArchivo==null?"":tipoArchivo;
		$('#a_tipoArchivo').val(tipoArchivo);
		var nombre_archivo = solicitud.identificador+"_DP_"+solicitud.id_solicitud+"_"+tipoArchivo+"_"+str;
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        var maximaCapacidad = boton.parent().find(".maxCapacidad").val();

        if (extension[extension.length - 1].toUpperCase() == 'PDF') {

            subirArchivo(
                archivo
                , nombre_archivo
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new cargaDossier(estado, archivo, boton,rutaArchivo)
            );
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("");
        }
	});
	function cargaDossier(estado, archivo, boton,rutaArchivo) {
		this.esperar = function (msg) {
			estado.addClass("rojo");
			estado.html("Cargando el archivo...");
			archivo.addClass("amarillo");
		};

		this.exito = function (msg) {
			estado.removeClass("rojo");
			estado.html("El archivo ha sido cargado.");
			archivo.removeClass("amarillo");
			archivo.addClass("verde");
			boton.attr("disabled", "disabled");

			guardarArchivo(boton);

		};

		this.error = function (msg) {
			estado.html(msg);
			archivo.removeClass("amarillo");

			archivo.val("");
		};
	}

	function guardarArchivo(boton){
		var rutaArchivo = boton.parent().find(".rutaArchivo");

		$('#a_rutaArchivo').val(rutaArchivo.val());
		var form=boton.parent().parent().parent();

		form.attr('data-opcion', 'guardarArchivoAnexo');

		form.append('<input type="hidden" id="a_referencia" name="a_referencia" value="'+boton.parent().parent().find("#referencia").val()+'">');
		form.append('<input type="hidden" id="a_rutaArchivo" name="a_rutaArchivo" value="'+boton.parent().find(".rutaArchivo").val()+'">');
		form.append('<input type="hidden" id="a_tipoArchivo" name="a_tipoArchivo" value="'+boton.parent().parent().find("#tipoArchivo").val()+'">');

		ejecutarJson(form);
		var noHayArchivo = boton.parent().parent().find("#noHayArchivo");
		var archivo_cargado = boton.parent().parent().find("#archivo_cargado");
		noHayArchivo.hide();
		archivo_cargado.attr("href",rutaArchivo.val());
		archivo_cargado.show();

	}


	//**************************************** campos numericos **********************************
	$('#partida_arancelaria').numeric();
	$('#ph').numeric();

	$('#cantidadTiempo').numeric();


	$('#validez').numeric();
	$('#estabilidad').numeric();
	$('#inmunidad').numeric();
	$('#inmunidad_min').numeric();

	$('#humedad').numeric();
	$('#preparacion_duracion').numeric();

	$('#almacenar_minimo').numeric();
	$('#almacenar_maximo').numeric();

	$('#sustratoPeso').numeric();


	$('#btnGuardarPrimero').click(function(event){
		event.preventDefault();
		$("#estado").html("");

		var error = false;
		if(!esNoNuloEsteCampo("#dirReferencia"))
			error = true;
		if(!esNoNuloEsteCampo("#ciLegal"))
			error = true;
		if(!esNoNuloEsteCampo("#correoLegal"))
			error = true;
		if(!esNoNuloEsteCampo("#nombreProducto"))
			error = true;
		if(!esNoNuloEsteCampo("#id_subtipo_producto"))
			error = true;
		if(!esNoNuloEsteCampo("#tipo_solicitud"))
			error = true;
		if(!esNoNuloEsteCampo("#id_sitio"))
			error = true;

		if(!esNoNuloEsteCampo("#id_area"))
			error = true;
		if(!esNoNuloEsteCampo("#ci_representante_tecnico"))
			error = true;
		if(!esNoNuloEsteCampo("#registro_oficial"))
			error = true;


		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		var form=$(this).parent();
		if(!error){
			if(jQuery.isEmptyObject(solicitud)){
				//nuevo registro
				form.attr('data-opcion', 'guardarNuevaSolicitud');
				form.attr('data-destino', 'detalleItem');
				form.attr('data-accionEnExito', 'ACTUALIZAR');
				form.append("<input type='hidden' id='nivel' name='nivel' value='1' />"); // añade el nivel del formulario
				abrir(form, event, true); //Se ejecuta ajax, busqueda de sitios
			}
			else{
				//es actualización
				incrementarNivel(form,solicitud.nivel);
				form.attr('data-opcion', 'guardarSolicitudDossier');
				form.attr('data-destino', 'detalleItem');
				form.attr('data-accionEnExito', '');
				form.append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario
				ejecutarJson(form);
				actualizaBotonSiguiente(form, nivelActual,solicitud.nivel);
			}
		}

	});



	//************************************* GUARDADO DE LOS PASOS ***************************************

	$("#frmGuardarPaso").submit(function(event){
		incrementarNivel($(this),solicitud.nivel);
		event.preventDefault();

		var error = false;

		if($('#tblProcedencia >tbody >tr').length == 0)
			error = true;

		if(error){
			mostrarMensaje("Debe declarar al menos un fabricante","FALLO");
			return;
		}
		borrarMensaje();

		$(this).append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario

		if (!error){
			ejecutarJson($(this));
			actualizaBotonSiguiente($(this), nivelActual,solicitud.nivel);
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	//Pestaña 3
	$("#frmDatosProducto").submit(function(event){

		incrementarNivel($(this),solicitud.nivel);
		event.preventDefault();

		error = false;

		if($('#id_subtipo_producto :selected').data('codigo')=='RIP-BIO' &&  $('#es_nueva_cepaSI').is(':checked')){
			$(this).append("<input type='hidden' id='id_subtipo_producto' name='id_subtipo_producto' value='"+$('#id_subtipo_producto :selected').data('codigo')+"' />");
			if(!esNoNuloEsteCampo("#nueva_cepa"))
				error = true;
			var archivo_cargado = $('#verCertificadoBiologico').find("#archivo_cargado");
			if(archivo_cargado.attr("href")=="")
				error = true;
		}
		else{
			verificarCamposVisiblesNulos(['#id_clasificacion_subtipo','#id_formulacion','#producto_cantidad','#producto_unidad','#partida_arancelaria']);
		}
		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		var form=$(this);
		form.attr('data-opcion', 'guardarOrigenProducto');
		form.append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario

		if (!error){
			ejecutarJson(form,new exito2());
			actualizaBotonSiguiente($(this), nivelActual,solicitud.nivel);
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	function exito2(){
		this.ejecutar=function(msg){
			if(msg.evaluarIngreso=='1'){
				$('#detalleItem').html('Solicitud de aprobación de nueva CEPA, ha sido enviada');
				mostrarMensaje('La solicitud ha sido enviada','EXITO');
				abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), null, true);
				abrir($("input:hidden"), null, false);
			}
			else
				mostrarMensaje(msg.mensaje,'EXITO');
		};
	}

	var error = false;
	function verificarCamposVisiblesNulos(campos){
		for(var i in campos){
			var campo=campos[i];
			if($(campo).is(":visible")){
				if(!esNoNuloEsteCampo(campo))
					error = true;
			}
		}
	}


	//Pestaña 4
	$("#frmNuevaSolicitud3").submit(function(event){

		incrementarNivel($(this),solicitud.nivel);
		event.preventDefault();

		error = false;

		verificarCamposVisiblesNulos(['#ph','#viscosidad','#densidad','#modo_fabricacion','#especificacion','#prueba_biologica','#prueba_biologica_ref','#identidad']);
		verificarCamposVisiblesNulos(['#identidad_referencia','#esterilidad','#esterilidad_referencia','#agentes_extra','#inocuidad','#inocuidad_referencia']);

		//verifica el ph
		var ph=parseFloat($("#ph").val());

		if(ph<0 || ph>14){
			mostrarMensaje('El valor del ph debe estar entre 0 y 14','FALLO');
			$("#ph").val('');
			$("#ph").focus();
			$("#ph").addClass("alertaCombo");
			return;
		}
		else{
			$("#ph").removeClass("alertaCombo");
		}



		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		$(this).append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario

		if (!error){
			ejecutarJson($(this));
			actualizaBotonSiguiente($(this), nivelActual,solicitud.nivel);
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	//Pestaña 5 -> Cambio del combo de subtipo de producto
	$("#frmNuevaSolicitud4").submit(function(event){
		incrementarNivel($(this),solicitud.nivel);
		event.preventDefault();

		var error = false;


		if(!esNoNuloEsteCampo("#usos"))
			error = true;


		if($('#id_subtipo_producto :selected').data('codigo')!='RIP-DAS' ){
			if($('#tblViaAdmin >tbody >tr').length == 0)
				error = true;
		}
		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		$(this).append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario

		if (!error){
			ejecutarJson($(this));
			actualizaBotonSiguiente($(this), nivelActual,solicitud.nivel);
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	//Pestaña 6
	$("#frmNuevaSolicitud5").submit(function(event){
		var elInferior=$(this);
		incrementarNivel(elInferior,solicitud.nivel);
		event.preventDefault();

		error = false;

		verificarCamposVisiblesNulos(['#margen_seguridad','#margen_seguridad_referencia','#preparacion_duracion','#preparacion_unidad','#preparacion_descripcion','#farmacocinetica']);
		verificarCamposVisiblesNulos(['#farmacocinetica_referencia','#farmacodinamica','#farmacodinamica_referencia','#efectos_colaterales','#efectos_colaterales_referencia']);
		verificarCamposVisiblesNulos(['#sobredosis','#sobredosis_referencia','#toxicidad','#toxicidad_referencia','#categoria_toxicologica']);
		if($('input[name="tiene_categoria_toxicologica"]:checked').is(":visible")){
			if($('input[name="tiene_categoria_toxicologica"]:checked').val()===undefined)
				error = true;
		}


		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		$(this).append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario

		if (!error){

			ejecutarJson($(this));
			actualizaBotonSiguiente(elInferior, nivelActual,solicitud.nivel);
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$("#frmNuevaSolicitud6").submit(function(event){

		var elInferior=$(this);
		incrementarNivel(elInferior,solicitud.nivel);
		event.preventDefault();

		error = false;

		verificarCamposVisiblesNulos(['#residuos','#precauciones','#precauciones_ref','#calidad','#validez','#validez_unidad']);
		verificarCamposVisiblesNulos(['#linea_biologica','#humedad','#estabilidad','#estabilidad_unidad','#inactivacion','#inmunidad']);
		verificarCamposVisiblesNulos(['#inmunidad_unidad','#inmunidad_min','#inmunidad_min_unidad','#inmunidad_ref']);

		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		$(this).append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario

		if (!error){

			ejecutarJson($(this));
			actualizaBotonSiguiente(elInferior, nivelActual,solicitud.nivel);
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$("#frmNuevaSolicitud7").submit(function(event){
		var elInferior=$(this);
		incrementarNivel(elInferior,solicitud.nivel);
		event.preventDefault();

		error = false;

		verificarCamposVisiblesNulos(['#conservacion','#almacenar_minimo','#almacenar_maximo','#humedad_minima','#humedad_maxima','#control_producto','#tipos_anticuerpos']);
		verificarCamposVisiblesNulos(['#deteccion_anticuerpos','#interpretacion','#eliminacion_envases','#riesgo','#mecanismo_accion','#mecanismo_accion_referencia']);
		verificarCamposVisiblesNulos(['#modo_uso','#observaciones']);


		if($('#tblPresentacion >tbody >tr').length == 0)
			error = true;

		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		$(this).append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario

		if (!error){

			ejecutarJson($(this));
			actualizaBotonSiguiente(elInferior, nivelActual,solicitud.nivel);
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});



	$("#frmNuevaSolicitud8").submit(function(event){

		var elInferior=$(this);
		incrementarNivel(elInferior,solicitud.nivel);
		event.preventDefault();

		var error = false;

		$('#frmAnexos').find('fieldset:visible').each(function() {
			var el=$( this ).find('a.archivo_cargado');
			var href = el.attr('href');
			if(href==''){
				error=true;
			}
		});

		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

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

			form.append("<input type='hidden' id='id_subtipo_producto' name='id_subtipo_producto' value='"+$('#id_subtipo_producto :selected').data('codigo')+"' />");
			form.append("<input type='hidden' id='id_sitio' name='id_sitio' value='"+$('#id_sitio').val()+"' />");


			form.attr('data-destino', 'detalleItem');

			abrir(form, event, true); //Se ejecuta ajax, busqueda de sitios
		}
		else
			mostrarMensaje('Para finalizar acepte las condiciones','FALLO');
	});




	//********************* Subtipo de productos ************************************************************


	$('#id_subtipo_producto').change(function(){
		$('#es_nueva_cepaNO').attr('checked', true);
		verNuevaCepa(false);
		actualizarClasificacion();
		actualizaGrupos();
		actualizaUsos();
		actualizarVias();
		verSegunTipos($(this).val());
		verEspecieTipos();

	});

	function actualizarClasificacion(){
		$('#id_clasificacion_subtipo').children('option').remove();
		$('#id_clasificacion_subtipo').append($("<option></option>").attr("value","").text("Seleccione...."));
		if(clasificaciones!=null && clasificaciones.length>0)
		{
			$.each(clasificaciones, function(key, value) {

				if(value.codificacion_subtipo_producto==$('#id_subtipo_producto :selected').data('codigo')){
					$('#id_clasificacion_subtipo')
					.append($("<option></option>")
					.attr("value",value.id_clasificacion_subtipo)
					.text(value.nombre));
				}
			});

		}
	}

	function actualizaGrupos(){
		var el=$('#tipoIa');
		el.children('option').remove();
		el.append($("<option></option>").attr("value","").text("Seleccione...."));
		for(var i in subtiposGrupos){

			if(subtiposGrupos[i].codificacion_subtipo_producto==$('#id_subtipo_producto :selected').data('codigo'))
			{
				el.append($("<option></option>")
					.attr("value",subtiposGrupos[i].grupo)
					.text(subtiposGrupos[i].nombre));
			}
		}

	}

	//Pestaña 5
	function actualizaUsos(){
		var subtipo=$('#id_subtipo_producto :selected').data('codigo');
		//miro el catalogo de codificacion de usos que tipo de informacion desplegar
		$('#usos_diagnostico_ver').hide();
		var el=$('#id_usos');
		el.children('option').remove();
		el.append($("<option></option>").attr("value","").text("Seleccione...."));

		for(var i in catalogoUsos){
			el.append($("<option></option>")
				.attr("value",catalogoUsos[i].id_uso)
				.text(catalogoUsos[i].nombre_uso));
		}
	}
	
	/*function actualizaUsos(){

		var subtipo=$('#id_subtipo_producto :selected').data('codigo');
		//miro el catalogo de codificacion de usos que tipo de informacion desplegar
		$('#usos_diagnostico_ver').hide();
		var el=$('#id_usos');
		el.children('option').remove();
		el.append($("<option></option>").attr("value","").text("Seleccione...."));
		for(var i in codificacionUsos){
			if(codificacionUsos[i].nombre3.trim()==subtipo){
				switch(codificacionUsos[i].nombre.trim()){
					case 'C':
						for(var i in catalogoUsos){
							el.append($("<option></option>")
								.attr("value",catalogoUsos[i].id_uso)
								.text(catalogoUsos[i].nombre_uso));

						}
						break;
					case 'F':
						el.append($("<option></option>")
							.attr("value","F")
							.text(codificacionUsos[i].nombre2.trim()));
						break;
					case 'O':
						el.append($("<option></option>")
							.attr("value","O")
							.text(codificacionUsos[i].nombre2.trim()));
						$('#usos_diagnostico_ver').show();
						break;
				}
			}
		}

	}*/

	function actualizarVias(){
		$('#unidad3Ver').hide();
		$('#viaVer').hide();
		$('#cantidadVer').hide();
		$('#unidad1Ver').hide();
		$('#porVer').hide();
		$('#sustratoPesoVer').hide();
		$('#unidad2Ver').hide();
		$('#cadaVer').hide();
		$('#duracionVer').hide();
		$('#unidad3Ver').hide();
		$('#tipoEspecieVer').hide();

		//otengo las posibilidades según el subtipo

		var subtipo=$('#id_subtipo_producto :selected').data('codigo');
		var ops='';
		for(var i in viasAdmin){
			if(viasAdmin[i].nombre3.trim()==subtipo){
				ops=viasAdmin[i].nombre;
				break;
			}
		}
		if(ops!=null && ops.length>0){
			//array de posibilidades
			$('#tipoEspecieVer').show();
			var arr=ops.split(",");

			for(var i in arr){
				var op=arr[i].trim();
				switch(op.substr(0,1)){
					case 'E':
						//todos muestran
						break;
					case 'V':
						//vias de administracion
						$('#viaVer').show();

						llenarComboSelector($('#via'),op,catalogoViasAdmin,'codigo','nombre');
						break;
					case 'C':
						$('#cantidadVer').show();
						break;
					case 'U':
						$('#unidad1Ver').show();
						llenarComboSelector($('#unidad1'),op,catalogoUnidades1,'id_unidad_medida','nombre');

						break;
					case 'X':
						$('#porVer').show();

							break;
					case 'S':
						$('#sustratoPesoVer').show();
						$('#sustratoPesoLabel').html("Sustrato");
						break;
					case 'P':
						$('#sustratoPesoVer').show();
						$('#sustratoPesoLabel').html("Peso/Superficie");
						break;
					case 'M':
						$('#unidad2Ver').show();
						llenarComboSelector($('#unidad2'),op,catalogoUnidades2,'id_unidad_medida','nombre');

						break;
					case 'A':
						$('#cadaVer').show();
						break;
					case 'D':
						$('#duracionVer').show();

						break;
					case 'T':
						$('#unidad3Ver').show();
						llenarComboSelector($('#unidad3'),op,catalogoUnidadesTiempo,'codigo','nombre');
						break;
				}
			}
		}
	}

	function llenarComboSelector(elemento,op,catalogo,codigo,nombre){

		elemento.children('option').remove();
		elemento.append($("<option></option>").attr("value","").text("Seleccione...."));
		var arrcat=op.split('_');
		if(arrcat!=null && arrcat.length>1 ){
			var catTipo= arrcat[1].trim().substr(0,1);

			if(catTipo=="C"){
				if(!jQuery.isEmptyObject(catalogo)){
					for(var i in catalogo){
						var item=catalogo[i];
						elemento.append($("<option></option>").attr("value",item[codigo]).text(item[nombre]));
					}

				}
			}
			else if(catTipo=="F"){
				var codigoFijo="";
				var arrCodigo=op.split("*");
				if(arrCodigo!=null && arrCodigo.length>1)
					codigoFijo=arrCodigo[1].trim();

				if(!jQuery.isEmptyObject(catalogo) && codigoFijo!=""){
					for(var i in catalogo){
						var item=catalogo[i];
						if(item[codigo]==codigoFijo){
							elemento.append($("<option></option>").attr("value",item[codigo]).text(item[nombre]));
						}
					}

				}
			}
		}
	}

	function verElementosDependientes(){

	}

	//*********************  Sitios y areas *****************************************************************
	$('#id_sitio').change(function(){

		llenarComboAreas($(this).val());

		$('#fn_sitio_nombre').val($("#id_sitio option:selected").html());

	});

	function llenarComboAreas(sitio){
		$('#id_area').prop('disabled', false);
		$('#id_area').children('option').remove();
		$('#ci_representante_tecnico').children('option').remove();
		borrarDatosRepresentateTecnico();

		$('#id_area').append($("<option></option>").attr("value","").text("Seleccione...."));

		//busco el sitio
		var arrSitio = {};
		for(var i in sitiosAreas){
			if(sitiosAreas[i].id_sitio==sitio){
				arrSitio=sitiosAreas[i]['areas'];
				//pongo la direccion del sitio en fabricante por si mismo
				$("#fn_sitio_direccion").val(sitiosAreas[i].direccion);
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

	$('#id_area').change(function(event){
		if($('#id_area').val()=="")
		{
			$('#ci_representante_tecnico').children('option').remove();

		}
		else{
			var param={opcion_llamada:'representantesTecnicosPorArea',id_area:$('#id_area').val()};
			llamarServidor('dossierPecuario','atenderLlamadaServidor',param,llenarComboRepresentanteTecnico);
		}
	});

	function llenarComboRepresentanteTecnico(items){
		$('#ci_representante_tecnico').prop('disabled', false);
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
			llamarServidor('dossierPecuario','atenderLlamadaServidor',param,llenarDatosRepresentanteTecnico);
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

	$('#btnNuevoOrigen').click(function (event) {
		var form=$(this).parent().parent();
		if($("#es_fabricanteSI").prop('checked')){			//Mismo fabricante nacional

			form.append("<input type='hidden' id='tipo_fabricante' name='tipo_fabricante' value='N' />");
			form.append("<input type='hidden' id='ruc' name='ruc' value='"+$('#fn_ruc').val()+"' />");
			form.append("<input type='hidden' id='id_sitio' name='id_sitio' value='"+$('#id_sitio').val()+"' />");
			form.append("<input type='hidden' id='id_area' name='id_area' value='"+$('#id_area').val()+"' />");
			form.append("<input type='hidden' id='empresa' name='empresa' value='"+$('#fn_razon_social').val()+"' />");
			form.append("<input type='hidden' id='direccion' name='direccion' value='"+$('#fn_sitio_direccion').val()+"' />");
			form.append("<input type='hidden' id='id_pais' name='id_pais' value='"+paisEcuador+"' />");

			form.append("<input type='hidden' id='tecnico_nombre' name='tecnico_nombre' value='"+$('#ci_representante_tecnico option:selected').text()+"' />");
			form.append("<input type='hidden' id='tecnico_contrato' name='tecnico_contrato' value='"+$('#ci_representante_tecnico').val()+"' />");
			form.append("<input type='hidden' id='tecnico_titulo' name='tecnico_titulo' value='"+$('#tituloTecnico').val()+"' />");
			form.append("<input type='hidden' id='registroSenesyt' name='registroSenesyt' value='"+$('#registroSenesyt').val()+"' />");

		}
		else if($("#es_por_contratoSI").prop('checked')){		//Fabricante por contrator
			form.append("<input type='hidden' id='tipo_fabricante' name='tipo_fabricante' value='C' />");
			form.append("<input type='hidden' id='ruc' name='ruc' value='"+$('#fc_ruc').val()+"' />");
			form.append("<input type='hidden' id='id_sitio' name='id_sitio' value='"+$('#fc_id_sitio').val()+"' />");
			form.append("<input type='hidden' id='id_area' name='id_area' value='"+$('#fc_id_area').val()+"' />");
			form.append("<input type='hidden' id='empresa' name='empresa' value='"+$('#fc_razon_social').val()+"' />");
			form.append("<input type='hidden' id='direccion' name='direccion' value='"+$('#fc_sitio_direccion').val()+"' />");
			form.append("<input type='hidden' id='id_pais' name='id_pais' value='"+paisEcuador+"' />");

			form.append("<input type='hidden' id='tecnico_nombre' name='tecnico_nombre' value='"+$('#fc_tecnico_contrato option:selected').text()+"' />");
			form.append("<input type='hidden' id='tecnico_contrato' name='tecnico_contrato' value='"+$('#fc_tecnico_contrato').val()+"' />");
			form.append("<input type='hidden' id='tecnico_titulo' name='tecnico_titulo' value='"+$('#fc_tituloTecnico').val()+"' />");
			form.append("<input type='hidden' id='registroSenesyt' name='registroSenesyt' value='"+$('#fc_registroSenesyt').val()+"' />");
		}
		else{																//Fabricante extranjero
			form.append("<input type='hidden' id='tipo_fabricante' name='tipo_fabricante' value='E' />");
			form.append("<input type='hidden' id='ruc' name='ruc' value='"+$('#id_extranjero').val()+"' />");

			form.append("<input type='hidden' id='empresa' name='empresa' value='"+$( "#id_extranjero option:selected" ).text()+"' />");
			form.append("<input type='hidden' id='direccion' name='direccion' value='"+$('#ex_direccion').val()+"' />");
			form.append("<input type='hidden' id='id_pais' name='id_pais' value='"+$('#ex_id_pais').val()+"' />");

			form.append("<input type='hidden' id='id_sitio' name='id_sitio' value='0' />");		// Corregido guarda un sitio inexistente
			form.append("<input type='hidden' id='id_area' name='id_area' value='0' />");			// Corregido guarda un sitio inexistente

			form.append("<input type='hidden' id='tecnico_nombre' name='tecnico_nombre' value='"+$('#ex_tecnico_contrato').val()+"' />");
			form.append("<input type='hidden' id='tecnico_contrato' name='tecnico_contrato' value='' />");
			form.append("<input type='hidden' id='tecnico_titulo' name='tecnico_titulo' value='"+$('#fc_tituloTecnico').val()+"' />");
			form.append("<input type='hidden' id='registroSenesyt' name='registroSenesyt' value='"+$('#fc_registroSenesyt').val()+"' />");
		}
		form.append("<input type='hidden' id='registro_oficial' name='registro_oficial' value='"+$('#f_registro_oficial').val()+"' />");
		ejecutarJson(form,new exitoProcedencia());

	});

	function exitoProcedencia(){
		this.ejecutar=function (msg){
			mostrarMensaje(msg.mensaje, "EXITO");
			verFabricantesProcedencia(msg.datos);

			verAreaTipoFabricante('N');
			encerarAreaPorContrato();
			encerarAreaExtranjeros();
			verAnexoCLV(msg.tieneExtranjero);
		};
	}

	function verFabricantesProcedencia(datos){
		$('#tblProcedencia tbody tr').remove();
		$("#tblProcedencia").append(datos);

	}

	function verAnexoCLV(tieneExtranjero){
		if(tieneExtranjero==1)
			$('#verAnexoClv').show();
		else
			$('#verAnexoClv').hide();
	}



	$("#tblProcedencia").off("click",".btnBorraFilaFabricante").on("click",".btnBorraFilaFabricante",function(event){
		event.preventDefault();
		var form=$(this).parent();
		var param={opcion_llamada:'borrarFabricanteDossier',id_solicitud:solicitud.id_solicitud,id_solicitud_fabricante:form.find("#id_solicitud_fabricante").val()};
		llamarServidor('dossierPecuario','atenderLlamadaServidor',param,exitoBorraFabricanteProcedencia);
	});

	function exitoBorraFabricanteProcedencia(msg){
		verFabricantesProcedencia(msg.datos);
		verAnexoCLV(msg.tieneExtranjero);

	}

	//**************************************************************************************************************

	$("[name='es_fabricante']").change(function(){
		if($(this).val()=="SI"){
			verAreaTipoFabricante('N');
			encerarAreaPorContrato();
			encerarAreaExtranjeros();
			$('#f_registro_oficial').val($('#registro_oficial').val());
			$('#lbl_fc_registroSenesyt').html('Registro del título en el SENESCYT');
		}
		else{
			$('#f_registro_oficial').val('');
			verAreaTipoFabricante('C');
		}
	});



	function encerarAreaExtranjeros(){
		cargarValorDefecto("id_extranjero","");
		$("#ex_pais, #ex_direccion, #tblExtranjeros, #f_registro_oficial, #ex_tecnico_contrato").val('');

	}

	function encerarAreaPorContrato(){
		cargarValorDefecto("fc_id_sitio","");
		cargarValorDefecto("fc_id_area","");
		cargarValorDefecto("fc_tecnico_contrato","");
		$("#fc_ruc, #fc_razon_social, #fc_sitio_direccion, #fc_tituloTecnico, #fc_registroSenesyt, #f_registro_oficial").val('');
	}

	$("[name='es_por_contrato']").change(function(){
		if($(this).val()=="SI"){
			verAreaTipoFabricante('C');
			encerarAreaExtranjeros();
			$('#lbl_fc_registroSenesyt').html('Registro del título en el SENESCYT');
		}
		else{
			verAreaTipoFabricante('E');
			encerarAreaPorContrato();
			$('#lbl_fc_registroSenesyt').html('Número de Matricula');
		}
	});



	function verAreaTipoFabricante(tipo){


		if(tipo==null || tipo=='' || tipo=='N')
		{
			$("#es_fabricanteSI").prop('checked', true);
			$("#es_por_contratoSI").prop('checked', false);
			$("#es_por_contratoVer").hide();
			$(".fabricanteNacional").show();
			$('#f_registro_oficial').val($('#registro_oficial').val());

			var miSitio =$('#id_sitio option:selected');
			$('#fn_sitio_nombre').val(miSitio.text());
			$('#fn_sitio_direccion').val(miSitio.data('direccion'));

			$(".fabricanteContrato").hide();
			$(".fabricanteExtranjero").hide();
			$(".fabricanteMix").hide();
		}
		else if (tipo=='C'){
			$("#es_fabricanteSI").prop('checked', false);
			$("#es_por_contratoSI").prop('checked', true);
			$("#es_por_contratoVer").show();
			$(".fabricanteContrato").show();
			$(".fabricanteNacional").hide();
			$(".fabricanteExtranjero").hide();
			$(".fabricanteMix").show();
		}
		else {
			$(".fabricanteNacional").hide();
			$(".fabricanteContrato").hide();
			$(".fabricanteExtranjero").show();
			$(".fabricanteMix").show();
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
		llamarServidor('dossierPecuario','atenderLlamadaServidor',param,llenarDatosFabricanteContrato);

	});



	var datosOperadorContrato={};

	function llenarDatosFabricanteContrato(item){
		$('#fc_id_sitio').children('option').remove();
		$('#fc_id_area').children('option').remove();
		$('#fc_tecnico_contrato').children('option').remove();
		borrarDatosRepresentateTecnicoContrato();

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
		$('#fc_id_area').children('option').remove();
		$('#fc_tecnico_contrato').children('option').remove();
		borrarDatosRepresentateTecnicoContrato();
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
			$('#fc_id_area').append($("<option></option>").attr("value","").text("Seleccione...."));
			if(sitioContrato!=null){
				//lena las area y la direccion del sitio
				$('#fc_sitio_direccion').val(sitioContrato.nombre_lugar);
				if(sitioContrato.areas!=null && sitioContrato.areas.length>0){
					$.each(sitioContrato.areas, function(key, value) {
						$('#fc_id_area')
						.append($("<option></option>")
						.attr("value",value.id_area)
						.text(value.nombre_area));
					});
				}
			}
		}
	});

	var areaContrato={};
	$('#fc_id_area').change(function(event){
		$('#fc_tecnico_contrato').children('option').remove();
		borrarDatosRepresentateTecnicoContrato();
		if($('#fc_id_area').val()!=""){

			//busca el area
			for(var i in sitioContrato.areas){
				if(sitioContrato.areas[i].id_area==$('#fc_id_area').val()){
					areaContrato=sitioContrato.areas[i];
					break;
				}
			}
			$('#fc_tecnico_contrato').append($("<option></option>").attr("value","").text("Seleccione...."));
			if(areaContrato!=null && areaContrato.representates_tecnicos.length>0){
				$.each(areaContrato.representates_tecnicos, function(key, value) {
					$('#fc_tecnico_contrato')
					.append($("<option></option>")
					.attr("value",value.identificacion_representante)
					.text(value.nombre_representante));
				});
			}
		}
	});

	$("#fc_tecnico_contrato").change(function (event) {
		borrarDatosRepresentateTecnicoContrato();
		if($('#fc_tecnico_contrato').val()!=""){

			//busca el tecnico
			var rt={};
			for(var i in areaContrato.representates_tecnicos){
				if(areaContrato.representates_tecnicos[i].identificacion_representante==$('#fc_tecnico_contrato').val()){
					rt=areaContrato.representates_tecnicos[i];
					break;
				}
			}
			if(rt==null){
				borrarDatosRepresentateTecnicoContrato();
			}
			else{
				$('#fc_tituloTecnico').val(rt.titulo_academico);
				$('#fc_registroSenesyt').val(rt.identificacion_representante);
			}
		}
	});



	function borrarDatosRepresentateTecnicoContrato(){
		$('#fc_tituloTecnico').val("");
		$('#fc_registroSenesyt').val("");
	}

	//******************** FABRICANTE EXTRANJERO *********************************************************


	$("#id_extranjero").click(function (event) {
		for(var i in fabricantesExtranjeros){
			if(fabricantesExtranjeros[i].id_fabricante_extranjero==$("#id_extranjero").val()){
				$("#ex_pais").val(fabricantesExtranjeros[i].pais);
				$("#ex_id_pais").val(fabricantesExtranjeros[i].id_pais);
				break;
			}
		}
	});



	//*************************************** VALIDA NOMBRE DEL PRODUCTO *******************************************

	$("#nombreProducto").change(function (event) {
		event.preventDefault();
		var param={opcion_llamada:'validarNombre',areaTematica:'IAV', nombre:$("#nombreProducto").val().trim()};
		llamarServidor('dossierPecuario','atenderLlamadaServidor',param,resultadoValidacion,$(this));
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

	//************************************** REGISTRO DE CEPAS *******************************************
	$("[name='es_nueva_cepa']").change(function(){
		verNuevaCepa($(this).val()=="SI");
	});

	function verNuevaCepa(esNueva){
		if(esNueva){
			$('.verNuevaCepa').show();
			$('#verCertificadoBiologico').show();
			$('.verCepas').hide();

			distribuirLineas();
		}
		else{
			$('.verNuevaCepa').hide();
			$('#verCertificadoBiologico').hide();
			$('.verCepas').show();
		}
	}

	//************************************** COMPOSICION DEL PRODUCTO ***********************************************


	$("#tipoIa").click(function (event) {
		var param={opcion_llamada:'obtenerIA',grupo: $(this).val()};
		llamarServidor('dossierPecuario','atenderLlamadaServidor',param,llenarIA);

		//actualiza las unidades
		llenarComboUnidadesComposicion($(this).val());

	});

	function llenarIA(items){
		$('#elementoComposicion').children('option').remove();
		$('#elementoComposicion').append($("<option></option>").attr("value","").text("Seleccione...."));
		if(!jQuery.isEmptyObject(items)){
			for(var i in items){

				$('#elementoComposicion')
					.append($("<option></option>")
					.attr("value",items[i].id_ingrediente_activo)
					.text(items[i].ingrediente_activo));
			}

		}
	}

	function llenarComboUnidadesComposicion(tipoIa){
		var items=[];
		if(tipoIa=='IA_CEPA'){
			items=unidadesMedidaCepas;
		}
		else{
			items=unidadesMedida;
	}

		$('#composicionUnidad').children('option').remove();
		$('#composicionUnidad').append($("<option></option>").attr("value","").text("Seleccione...."));
		if(!jQuery.isEmptyObject(items)){
			for(var i in items){

				$('#composicionUnidad')
					.append($("<option></option>")
					.attr("value",items[i].id_unidad_medida)
					.text(items[i].nombre));
			}

		}

	}

	$("#agregarElementoComposicion").click(function (event) {

		error = false;
		verificarCamposVisiblesNulos(['#tipoIa','#elementoComposicion','#composicionValor','#composicionUnidad']);

		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		var param={opcion_llamada:'agregarComposicion',id_solicitud:solicitud.id_solicitud,grupo:$('#tipoIa').val(),id_elemento:$('#elementoComposicion').val(),valor:$("#composicionValor").val(),unidad:$("#composicionUnidad").val()};
		llamarServidor('dossierPecuario','atenderLlamadaServidor',param,llenarComposicion);
	});

	function llenarComposicion(items){
		$('#tblComposicion tbody tr').remove();
		var verAnexoJustificacion=false;
		if(!jQuery.isEmptyObject(items)){
			for(var i in items){
				var item=items[i];
				var nuevaFila='<td>'+item.nombre+'</td>';
				nuevaFila+='<td>'+item.ingrediente_activo+'</td>';
				nuevaFila+='<td>'+item.cantidad+'</td>';
				nuevaFila+='<td>'+item.nombre_unidad+'</td>';
				var tdEliminar='<form id="borrarFila" class="borrar" data-rutaAplicacion="dossierPecuario" data-opcion="eliminarComposicionProducto"  >' +
								'<input type="hidden" id="id_solicitud_composicion" name="id_solicitud_composicion" value="' + item.id_solicitud_composicion + '" />' +
								'<button type="button" class="icono btnBorraFilaComposicion"></button>' +
							'</form>';
				nuevaFila+='<td>'+tdEliminar+'</td>';
				$("#tblComposicion").append('<tr>'+nuevaFila+'</tr>');
				if(item.grupo=='IA_PRAC')
					verAnexoJustificacion=true;
			}
		}
		var subtipo=$('#id_subtipo_producto :selected').data('codigo');
		if(verAnexoJustificacion && (subtipo=='RIP-DIN'))
			$('#verAnexoJustificacion').show();
		else
			$('#verAnexoJustificacion').hide();

	}



	$(".btnBorraFilaComposicion").click(function(event){

		event.preventDefault();
		var form=$(this).parent();
		var param={opcion_llamada:'borrarComposicion',id_solicitud:solicitud.id_solicitud,id_solicitud_composicion:form.find("#id_solicitud_composicion").val()};
		llamarServidor('dossierPecuario','atenderLlamadaServidor',param,llenarComposicion);

	});


	$("#tblComposicion").on("click",".btnBorraFilaComposicion",function(event){
		event.preventDefault();

		var form=$(this).parent();

		var param={opcion_llamada:'borrarComposicion',id_solicitud:solicitud.id_solicitud,id_solicitud_composicion:form.find("#id_solicitud_composicion").val()};
		llamarServidor('dossierPecuario','atenderLlamadaServidor',param,llenarComposicion);
	});


	$("#ph").change(function(){

		var ph=parseFloat($("#ph").val());

		if(ph<0 || ph>14){
			mostrarMensaje('El valor debe estar entre 0 y 14','FALLO');
			$("#ph").focus();
			$("#ph").addClass("alertaCombo");
		}
		else{
			$("#ph").removeClass("alertaCombo");
			}

	});

	//************************ CATEGORIA TOXICOLOGICA ************************


	$("[name='tiene_categoria_toxicologica']").change(function(){
		if($(this).val()=="SI"){
			$('.categoria_toxicologicaVer').show();
		}
		else{
			$('.categoria_toxicologicaVer').hide();
		}
	});

	$('#btnUsos').click(function(){
		var usos=$('#usos').val().trim();
		//verifico si el uso ya esta en la lista
		var arr=usos.split(",");
		if(arr.indexOf($('#id_usos').val())<0)
		{
			if(usos.length==0)
				$('#usos').val($('#id_usos').val());
			else
				$('#usos').val(usos+","+$('#id_usos').val());
		}
		verUsos();
	});

	$('#btnUsosElinar').click(function(){
		$('#usos').val('');

		verUsos();
	});


	//Usos declarados
	function verUsos(){
		var usos=$('#usos').val().trim();
		var arr=usos.split(",");
		var sver="";

		for(var i in arr){
			for(var k in catalogoUsos){
				if(catalogoUsos[k].id_uso==arr[i])
					sver=sver+", "+catalogoUsos[k].nombre_uso;
			}
		}
		
		if(sver.length>1)
			sver=sver.substring(2);

		$('#usos_lista').html(sver);
	}

	/*function verUsos(){
		var usos=$('#usos').val().trim();
		var arr=usos.split(",");
		var sver="";
		//verifico los casos especiales
		if(arr.indexOf("F")>=0){
			for(var i in codificacionUsos){

				if(codificacionUsos[i].nombre3.trim()==$('#id_subtipo_producto :selected').data('codigo')){
					sver=codificacionUsos[i].nombre2;
				}
			}
		}
		else if(arr.indexOf("O")>=0){
			$('#usos_diagnostico_ver').show();
			for(var i in codificacionUsos){

				if(codificacionUsos[i].nombre3.trim()==$('#id_subtipo_producto :selected').data('codigo')){
					sver=codificacionUsos[i].nombre2;
				}
			}
		}
		else{
			for(var i in arr){
				for(var k in catalogoUsos){
					if(catalogoUsos[k].id_uso==arr[i])
						sver=sver+", "+catalogoUsos[k].nombre_uso;
				}
			}
			if(sver.length>1)
				sver=sver.substring(2);
		}

		$('#usos_lista').html(sver);

	}*/

	//*********************** ESPECIES ******************************************************

	function verEspecieTipos(){
		var subTipo=$("#id_subtipo_producto :selected").data('codigo');
		$("#especie_tipo option[value='PEF_IP']").show();
		$("#especie_tipo option[value='PEF_MAS']").show();
		$("#especie_tipo option[value='PEF_PEC']").show();
		$("#especie_tipo option[value='PEF_OT']").show();
		if(subTipo=='RIP-DAS'){
			
			$("#especie_tipo option[value='PEF_MAS']").hide();
			$("#especie_tipo option[value='PEF_PEC']").hide();
		}
		else{
			$("#especie_tipo option[value='PEF_IP']").hide();
		}
		cargarValorDefecto("especie_tipo","");
		cargarValorDefecto("especie","");
	}


	$("#especie_tipo").change(function(){
		var especieTipo=$("#especie_tipo").val();
		$('#especie').children('option').remove();
		$('#especie').append($("<option></option>").attr("value","").text("Seleccione...."));
		if(jQuery.isEmptyObject(especies) || especieTipo=='PEF_IP' || especieTipo=='PEF_OT'){
			$('.especieVer').hide();
			$('#verModoAplicacion').show();
			$("#via option[value='']").prop('selected', true);


		}
		else{
			$('#verModoAplicacion').hide();
			var subTipo=$("#id_subtipo_producto :selected").data('codigo');
			if(subTipo!="RIP-KD"){
				$('.especieVer').show();
			}
			//$('.especieVer').show();
			//$('#verModoAplicacion').hide();

			for(var i in especies){
				var item=especies[i];
				if(item.nombre3==especieTipo){
					$('#especie')
						.append($("<option></option>")
						.attr("value",item.codigo)
						.text(item.nombre));
				}
			}

		}

	});

	//**************************************** ADMINISTRACION Y DOSIS ************************************

	$("#btnAddDosis").click(function (event) {

		error = false;
		verificarCamposVisiblesNulos(['#especie','#via','#cantidad','#unidad1','#sustratoPeso','#unidad2','#duracion']);
		verificarCamposVisiblesNulos(['#unidad3','#dosis_detalle','#modo_aplicacion']);

		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		var param={opcion_llamada:'agregarDosis',id_solicitud:solicitud.id_solicitud,id_especie:$('#especie').val(),id_via:$('#via').val(),
			cantidad:$("#cantidad").val(),id_unidad1:$("#unidad1").val(),peso:$("#sustratoPeso").val(),id_unidad2:$("#unidad2").val(),duracion:$("#duracion").val(),id_unidad3:$("#unidad3").val(),
			id_subtipo_producto:$("#id_subtipo_producto").val(),detalle:$("#dosis_detalle").val(),referencia:$("#dosis_referencia").val(),modo_aplicacion:$('#modo_aplicacion').val()};
		llamarServidor('dossierPecuario','atenderLlamadaServidor',param,verDosis);
	});

	function verDosis(items){
		llenarComboRetiros(items);
		$('#tblViaAdmin tbody tr').remove();
		if(!jQuery.isEmptyObject(items)){
			for(var i in items){
				var item=items[i];
				var st=verTextoVias(item);
				var nuevaFila='<td>'+st+'</td>';
				var tdEliminar='<form id="frmBorrarFilaDosis" class="borrar" data-rutaAplicacion="dossierPecuario" data-opcion="eliminarDosis"  >' +
								'<input type="hidden" id="id_solicitud" name="id_solicitud" value="' + solicitud.id_solicitud + '" />' +
								'<input type="hidden" id="id_solicitud_dosis" name="id_solicitud_dosis" value="' + item.id_solicitud_dosis + '" />' +
								'<button type="button" class="icono btnBorraFilaDosis"></button>' +
							'</form>';
				nuevaFila+='<td>'+tdEliminar+'</td>';
				$("#tblViaAdmin").append('<tr>'+nuevaFila+'</tr>');
			}
		}

	}


	$(".btnBorraFilaDosis").click(function(event){

		event.preventDefault();
		var form=$(this).parent();
		var param={opcion_llamada:'borrarDosis',id_solicitud:solicitud.id_solicitud,id_solicitud_efecto:form.find("#id_solicitud_dosis").val()};
		llamarServidor('dossierPecuario','atenderLlamadaServidor',param,verDosis);

	});


	$("#tblViaAdmin").on("click",".btnBorraFilaDosis",function(event){
		event.preventDefault();
		var form=$(this).parent();
		var param={opcion_llamada:'borrarDosis',id_solicitud:solicitud.id_solicitud,id_solicitud_dosis:form.find("#id_solicitud_dosis").val()};
		llamarServidor('dossierPecuario','atenderLlamadaServidor',param,verDosis);
	});

	function verTextoVias(item){
		var st="";
		var ops='';
		var subtipo=$("#id_subtipo_producto option[value="+item.id_subtipo_producto+"]").data('codigo');

		for(var i in viasAdmin){
			if(viasAdmin[i].nombre3.trim()==subtipo){
				ops=viasAdmin[i].nombre;
				break;
			}
		}
		if(ops!=null && ops.length>0){
			//array de posibilidades
			var arr=ops.split(",");
			for(var i in arr){
				var op=arr[i].trim();
				switch(op.substr(0,1)){
					case 'E':
						var subTipo=$("#id_subtipo_producto :selected").data('codigo');
						if((subTipo=='RIP-DAS') &&(item.id_especie=='')&&(item.id_via=='')){
							st=st+"Instalaciones pecuarias: ";
						}
						else{
							if(item.especie!=null)
								st=st+item.especie+": ";
						}
						break;
					case 'V':
						//vias de administracion
						var subTipo=$("#id_subtipo_producto :selected").data('codigo');
						if((subTipo=='RIP-DAS') &&(item.id_especie=='')&&(item.id_via=='')){
							st=st+item.modo_aplicacion+" ";
						}
						else{
							if(item.via!=null)
								st=st+item.via+" ";
						}
						break;
					case 'C':
						st=st+item.cantidad+" ";
						break;
					case 'U':
						st=st+item.unidad1+" ";
						break;
					case 'X':
						st=st+"por ";
						break;
					case 'S':
						st=st+item.peso+" ";
						break;
					case 'P':
						st=st+item.peso+" ";
						break;
					case 'M':
						st=st+item.unidad2+" ";
						break;
					case 'A':
						st=st+"cada ";
						break;
					case 'D':
						st=st+item.duracion+" ";
						break;
					case 'T':
						st=st+item.unidad3+" ";
						break;
				}
			}
		}
		st=st+" [Detalle de la dosis: "+item.detalle+"] [Referencias: "+item.referencia+"]";
		return st;
	}



	//********************************* REQUIERE PREPARACION ***************


	$("[name='requiere_preparacion']").change(function(){
		if($(this).val()=="SI")
			$('.requiere_preparacionView').show();
		else
			$('.requiere_preparacionView').hide();

	});

	//********************************** EFECTOS NO DESEADOS ************

	$("#btnAddEfectos").click(function (event) {
		event.preventDefault();
		var param={opcion_llamada:'agregarEfectos',id_solicitud:solicitud.id_solicitud,codigo:$('#id_efecto').val(),descripcion:$('#descripcionEfecto').val(),referencia:$('#descripcionEfecto_referencia').val()};
		llamarServidor('dossierPecuario','atenderLlamadaServidor',param,verEfectosNoDeseados);
	});

	function verEfectosNoDeseados(items){
		$('#tblEfectos tbody tr').remove();
		if(!jQuery.isEmptyObject(items)){

			for(var i in items){
				var item=items[i];

				var nuevaFila='<td>'+item.nombre+'</td>';
				nuevaFila+='<td>'+item.descripcion+'</td>';
				nuevaFila+='<td>'+item.referencia+'</td>';
				var tdEliminar='<form id="borrarFilaEfectos" class="borrar borrarFilaEfectos" data-rutaAplicacion="dossierPecuario" data-opcion="eliminarEfectosNoDeseados"  >' +
								'<input type="hidden" id="id_solicitud" name="id_solicitud" value="' + solicitud.id_solicitud + '" />' +
								'<input type="hidden" id="id_solicitud_efecto" name="id_solicitud_efecto" value="' + item.id_solicitud_efecto + '" />' +
								'<button type="button" class="icono btnBorraFilaEfectos"></button>' +
							'</form>';
				nuevaFila+='<td>'+tdEliminar+'</td>';

				$("#tblEfectos").append('<tr>'+nuevaFila+'</tr>');
			}
		}

	}

	$(".btnBorraFilaEfectos").click(function(event){

		event.preventDefault();

		var form=$(this).parent();

		var param={opcion_llamada:'borrarEfecto',id_solicitud:solicitud.id_solicitud,id_solicitud_efecto:form.find("#id_solicitud_efecto").val()};
		llamarServidor('dossierPecuario','atenderLlamadaServidor',param,verEfectosNoDeseados);

	});


	$("#tblEfectos").on("click",".btnBorraFilaEfectos",function(event){
		event.preventDefault();
		var form=$(this).parent();
		var param={opcion_llamada:'borrarEfecto',id_solicitud:solicitud.id_solicitud,id_solicitud_efecto:form.find("#id_solicitud_efecto").val()};
		llamarServidor('dossierPecuario','atenderLlamadaServidor',param,verEfectosNoDeseados);
	});

	$('#especie_tipo_retiro').change(function (){
		var el=$('#producto_consumo');
		el.children('option').remove();
		el.append($("<option></option>").attr("value","").text("Seleccione...."));
		for(var i in especiesConsumibles){
			if(especiesConsumibles[i].id_especie==$(this).val()){
				el.append($("<option></option>")
							.attr("value",especiesConsumibles[i].id_consumible)
							.text(especiesConsumibles[i].consumible));
			}
		}

	});


	function llenarComboRetiros(items){
		especiesElegidas.splice(0,especiesElegidas.length);
		var combo=$('#especie_tipo_retiro');
		combo.children('option').remove();
		combo.append($("<option></option>").attr("value","").text("Seleccione...."));
		var especie="";
		for(var i in items){
			var item=items[i];
			var encontro=false;
			for(var k in especiesElegidas){
				var e=especiesElegidas[k];
				if(e.id_especie==item.id_especie){
					encontro=true;
					break;
					}
			}
			if(encontro==false)
			{
				//excluyo las mascotas
				for(var p in especiesPecuarios)
				{
					if(especiesPecuarios[p].codigo==item.id_especie){
						especiesElegidas.push({id_especie:item.id_especie,especie:item.especie});
						combo.append($("<option></option>")
									.attr("value",item.id_especie)
									.text(item.especie));
					}
				}

			}
		}

	}

	//************************************* PERIODOS DE RETIRO **********************************************************

	$("#btnAddRetiro").click(function (event) {
		event.preventDefault();
		var param={opcion_llamada:'agregarTiemposRetiro',id_solicitud:solicitud.id_solicitud,id_especie:$('#especie_tipo_retiro').val(),id_consumible:$('#producto_consumo').val(),tiempo:$('#cantidadTiempo').val(),id_unidad:$('#unidadTiempo').val()};
		llamarServidor('dossierPecuario','atenderLlamadaServidor',param,verTiemposRetiro);
	});

	function verTiemposRetiro(items){
		$('#tblRetiro tbody tr').remove();
		if(!jQuery.isEmptyObject(items)){

			for(var i in items){
				var item=items[i];

				var nuevaFila='<td>'+item.especie+'</td>';
				nuevaFila+='<td>'+item.consumible+'</td>';
				nuevaFila+='<td>'+item.tiempo+'</td>';
				nuevaFila+='<td>'+item.unidad+'</td>';
				var tdEliminar='<form id="borrarFilaRetiros" class="borrar borrarFilaEfectos" data-rutaAplicacion="dossierPecuario" data-opcion="eliminarEfectosNoDeseados"  >' +
								'<input type="hidden" id="id_solicitud" name="id_solicitud" value="' + solicitud.id_solicitud + '" />' +
								'<input type="hidden" id="id_solicitud_retiro" name="id_solicitud_retiro" value="' + item.id_solicitud_retiro + '" />' +
								'<button type="button" class="icono btnBorraFilaRetiro"></button>' +
							'</form>';
				nuevaFila+='<td>'+tdEliminar+'</td>';

				$("#tblRetiro").append('<tr>'+nuevaFila+'</tr>');
			}
		}

	}

	$(".btnBorraFilaRetiro").click(function(event){

		event.preventDefault();

		var form=$(this).parent();
		var param={opcion_llamada:'borrarTiemposRetiro',id_solicitud:solicitud.id_solicitud,id_solicitud_retiro:form.find("#id_solicitud_retiro").val()};
		llamarServidor('dossierPecuario','atenderLlamadaServidor',param,verTiemposRetiro);

	});


	$("#tblRetiro").on("click",".btnBorraFilaRetiro",function(event){
		event.preventDefault();
		var form=$(this).parent();
		var param={opcion_llamada:'borrarTiemposRetiro',id_solicitud:solicitud.id_solicitud,id_solicitud_retiro:form.find("#id_solicitud_retiro").val()};
		llamarServidor('dossierPecuario','atenderLlamadaServidor',param,verTiemposRetiro);
	});

	
	//************************************* PRESENTACION DE ENVASES ********************************

	$("#btnAddPresentacion").click(function (event) {
		event.preventDefault();

		error = false;
		verificarCamposVisiblesNulos(['#presentacion','#cantidad_pres','#unidad_pres','#descripcion_pres']);

		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		var param={opcion_llamada:'agregarPresentacion',id_solicitud:solicitud.id_solicitud,presentacion:$('#presentacion').val(),cantidad:$('#cantidad_pres').val(),id_unidad_medida:$('#unidad_pres').val(),descripcion:$('#descripcion_pres').val()};
		llamarServidor('dossierPecuario','atenderLlamadaServidor',param,verPresentacion);
	});

	function verPresentacion(items){
		$('#tblPresentacion tbody tr').remove();
		if(!jQuery.isEmptyObject(items)){

			for(var i in items){
				var item=items[i];

				var nuevaFila='<td>'+('000' + item.subcodigo).slice(-4)+'</td>';
				nuevaFila+='<td>'+item.presentacion+'</td>';
				nuevaFila+='<td>'+item.cantidad+'</td>';

				nuevaFila+='<td>'+item.unidad+'</td>';
				nuevaFila+='<td>'+item.descripcion+'</td>';
				var tdEliminar='<form id="borrarFilaRetiros" class="borrar borrarFilaEfectos" data-rutaAplicacion="dossierPecuario" data-opcion="eliminarEfectosNoDeseados"  >' +
								'<input type="hidden" id="id_solicitud" name="id_solicitud" value="' + solicitud.id_solicitud + '" />' +
								'<input type="hidden" id="id_solicitud_presentacion" name="id_solicitud_presentacion" value="' + item.id_solicitud_presentacion + '" />' +
								'<button type="button" class="icono btnBorraFilaPresentacion"></button>' +
							'</form>';
				nuevaFila+='<td>'+tdEliminar+'</td>';

				$("#tblPresentacion").append('<tr>'+nuevaFila+'</tr>');
			}
		}

	}

	$(".btnBorraFilaPresentacion").click(function(event){

		event.preventDefault();

		var form=$(this).parent();
		var param={opcion_llamada:'borrarPresentacion',id_solicitud:solicitud.id_solicitud,id_solicitud_presentacion:form.find("#id_solicitud_presentacion").val()};
		llamarServidor('dossierPecuario','atenderLlamadaServidor',param,verPresentacion);

	});


	$("#tblPresentacion").on("click",".btnBorraFilaPresentacion",function(event){
		event.preventDefault();
		var form=$(this).parent();
		var param={opcion_llamada:'borrarPresentacion',id_solicitud:solicitud.id_solicitud,id_solicitud_presentacion:form.find("#id_solicitud_presentacion").val()};
		llamarServidor('dossierPecuario','atenderLlamadaServidor',param,verPresentacion);
	});




	//***************************** VISTA PREVIA CERTIFICADO***************************************
	$('button.btnVistaPreviaCertificado').click(function (event) {

		event.preventDefault();

		var form=$(this).parent();
		form.append("<input type='hidden' id='id_solicitud' name='id_solicitud' value='"+solicitud.id_solicitud+"' />"); // añade el nivel del formulario
		form.append("<input type='hidden' id='id_protocolo' name='id_protocolo' value='"+$('#protocolo').val()+"' />"); // añade el nivel del formulario
		form.append("<input type='hidden' id='producto_nombre' name='producto_nombre' value='"+$('#producto_nombre').val()+"' />");
		form.append("<input type='hidden' id='normativa' name='normativa' value='"+$('#normativa').val()+"' />");
		form.append("<input type='hidden' id='ingrediente_activo' name='ingrediente_activo' value='"+$('#producto_ia').html()+"' />");
		form.append("<input type='hidden' id='ingredientes_paises' name='ingredientes_paises' value='"+$('#producto_pais').val()+"' />");
		form.append("<input type='hidden' id='usos' name='usos' value='"+$('#producto_uso').val()+"' />");
		form.append("<input type='hidden' id='formulacion' name='formulacion' value='"+$('#producto_formulacion').val()+"' />");
		form.append("<input type='hidden' id='formuladores_paises' name='formuladores_paises' value='"+$('#producto_pais_producto').val()+"' />");

		form.attr('data-opcion', 'crearCertificadoPecuario');


		mostrarMensaje("Generando archivo ... ",'FALLO');
		$('#verReporteCertificado').hide();
		ejecutarJson(form,new exitoVistaPreviaCertificado());

	});


	function exitoVistaPreviaCertificado(){
		this.ejecutar=function (msg){
			mostrarMensaje(msg.mensaje, "EXITO");
			$('#verReporteCertificado').show();
			$('#verReporteCertificado').attr('href',msg.datos);
		};
	}

	//***************************** VISTA PREVIA ETIQUETA ***************************************
	$('button.btnVistaPreviaEtiqueta').click(function (event) {

		event.preventDefault();

		var form=$(this).parent();
		form.append("<input type='hidden' id='id_solicitud' name='id_solicitud' value='"+solicitud.id_solicitud+"' />"); // añade el nivel del formulario
		form.append("<input type='hidden' id='id_protocolo' name='id_protocolo' value='"+$('#protocolo').val()+"' />"); // añade el nivel del formulario
		form.append("<input type='hidden' id='producto_nombre' name='producto_nombre' value='"+$('#producto_nombre').val()+"' />");
		form.append("<input type='hidden' id='normativa' name='normativa' value='"+$('#normativa').val()+"' />");
		form.append("<input type='hidden' id='ingrediente_activo' name='ingrediente_activo' value='"+$('#producto_ia').html()+"' />");
		form.append("<input type='hidden' id='ingredientes_paises' name='ingredientes_paises' value='"+$('#producto_pais').val()+"' />");
		form.append("<input type='hidden' id='usos' name='usos' value='"+$('#producto_uso').val()+"' />");
		form.append("<input type='hidden' id='formulacion' name='formulacion' value='"+$('#producto_formulacion').val()+"' />");
		form.append("<input type='hidden' id='formuladores_paises' name='formuladores_paises' value='"+$('#producto_pais_producto').val()+"' />");


		form.attr('data-opcion', 'crearPuntosEtiqueta');

		mostrarMensaje("Generando archivo ... ",'FALLO');
		$('#verReporteEtiqueta').hide();
		ejecutarJson(form,new exitoVistaPreviaEtiqueta());

	});


	function exitoVistaPreviaEtiqueta(){
		this.ejecutar=function (msg){
			mostrarMensaje(msg.mensaje, "EXITO");
			$('#verReporteEtiqueta').show();
			$('#verReporteEtiqueta').attr('href',msg.datos);
		};
	}

</script>
