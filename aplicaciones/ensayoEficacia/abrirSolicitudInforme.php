<?php 
session_start();

	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorEnsayoEficacia.php';
	require_once '../../clases/ControladorRegistroOperador.php';
	require_once '../../clases/ControladorCatalogos.php';
	
	$idUsuario= $_SESSION['usuario'];
	$id_informe = $_POST['id'];
	$id_documento = $_POST['id_documento'];
	
	$id_tramite_flujo = $_POST['id_tramite_flujo'];
	$id_flujo = $_POST['id_flujo'];
	
	$identificador=$idUsuario;

	$conexion = new Conexion();
	$ce = new ControladorEnsayoEficacia();
	$cr = new ControladorRegistroOperador();
	$cc = new ControladorCatalogos();
	
	$datosGenerales=array();
	
	$protocolo=array();
	$dosis_unidad='';

	//verifica si viene para ser verifido
	if($id_informe!=null && $id_informe!='_nuevo' && $id_informe!='' && $id_tramite_flujo==null){
		$id_tramite_flujo = $_POST['nombreOpcion'];
	}

	if($id_informe==null || $id_informe=='_nuevo' || $id_informe==''){
		if($id_documento!=null && $id_documento!='_nuevo' && $id_documento!='')
			$id_informe=$id_documento;
	}

	
	if($id_informe!=null && $id_informe!='_nuevo' && $id_informe!=''){
		$datosGenerales=$ce->obtenerInformeFinalEnsayo($conexion,$id_informe);
		$protocolo=$ce->obtenerProtocoloDesdeInformes($conexion,$datosGenerales['id_protocolo_zona']);
		$idProtocolo=$protocolo['id_protocolo'];
		$identificador=$protocolo['identificador'];
		$dosis=$ce->obtenerTratamientosDosis($conexion,$protocolo['id_protocolo']);

		if($protocolo['tiene_unidad_dosis']=='t'){
			$respuesta=$cc->listarUnidadesMedidaXTipo($conexion,'composicion');      																			 
			while ($item = pg_fetch_assoc($respuesta)){
				if(strtoupper($item['id_unidad_medida']) == strtoupper($protocolo['unidad_dosis'])){
					$dosis_unidad=$item['codigo'];
					break;
				}
			}

						
		}
		else
			$dosis_unidad=$protocolo['unidad_dosis_otro'];
		

		$ias=$ce->obtenerIaDelProtocolo($conexion,$protocolo['id_protocolo']);
		$listaIa='';
		foreach($ias as $key=>$value){
			if($listaIa=='')
			$listaIa=$listaIa.$value['ingrediente_activo'].' '.$value['concentracion'].$value['codigo'];
			else
				$listaIa=' + '.$listaIa.$value['ingrediente_activo'].' '.$value['concentracion'].$value['codigo'];
		}
		$formulacion=$ce->obtenerFormulacion	($conexion,$protocolo['id_protocolo']);
		if($formulacion!=null && $listaIa!='')
			$listaIa=$listaIa.', '.$formulacion['sigla'];
		$resultadoPlagasDeclaradas='';
		$plagas=$ce->obtenerPlagasProtocolo($conexion,$protocolo['id_protocolo']);
		foreach($plagas as $key=>$value){
			if($resultadoPlagasDeclaradas=='')
				$resultadoPlagasDeclaradas=$value['nombre'];
			else
				$resultadoPlagasDeclaradas=$resultadoPlagasDeclaradas.', '.$value['nombre'];
		}
		

	}

	$items=$ce->obtenerSubTiposXcodigo($conexion,'RIA-%');
	$catalogoSubTipos=array();
	foreach ($items as $item){
		$subTipo=array();
		$subTipo['id_subtipo_producto']=$item['id_subtipo_producto'];
		$subTipo['codigo']=$item['codificacion_subtipo_producto'];
		$subTipo['nombre']=$item['nombre'];
		$catalogoSubTipos[]=$subTipo;
	}


	$tramite=$ce->obtenerTramiteDesdeFlujoTramiteEE($conexion,$id_tramite_flujo);

	
	$res = $cr->buscarOperador($conexion, $identificador);
	$operador = pg_fetch_assoc($res);
	$cultivosMenores=$ce->obtenerProductosMenores($conexion);
	$cultivosNombres = $ce->obtenerProductosXSubTipo($conexion,'CULTIVOS');

	
	

	$declaracionLegal=$ce->obtenerTitulo($conexion,'EP');

	//******************************** ANEXOS *************************************
	$paths=$ce->obtenerRutaAnexos($conexion,'ensayoEficacia');
	$pathAnexo=$paths['ruta'];		//Ruta para los documentos adjuntos

?>

<header>
	<h1>Solicitud de Informe de ensayo de eficacia</h1>
</header>

<div id="estado"></div>

<div class="pestania" id="P1" style="display: block;">
	<form id='frmNuevaSolicitud' data-rutaAplicacion='ensayoEficacia' data-opcion='guardarNuevaSolicitud'>
		<input type="hidden"  id="id_protocolo" name="id_protocolo" value="<?php echo $idProtocolo;?>"/>
      <input type="hidden" id="listaIa" name="listaIa" value="<?php echo $listaIa;?>" />
      <input type="hidden" id="resultadoPlagasDeclaradas" name="resultadoPlagasDeclaradas" value="<?php echo $resultadoPlagasDeclaradas;?>" />
      
		<fieldset>
			<legend>Informacion del solicitante</legend>
						
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
				<label for="parroquia" class="opcional">Dirección</label> 
				<input value="<?php echo $operador['parroquia'];?>" name="parroquia" type="text" id="parroquia" placeholder="Parroquia" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
			</div>				
			<div data-linea="8">
				<label for="dirReferencia" class="opcional">Dirección de referencia</label> 
				<input value="<?php echo $protocolo['direccion_referencia'];?>" name="dirReferencia" type="text" id="dirReferencia" placeholder="Dirección de referencia" class="cuadroTextoCompleto" maxlength="512" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" disabled="disabled"/>
			</div>
			<div data-linea="9">
				<label for="telefono" class="opcional">Telefono</label> 
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
				<input value="<?php echo $protocolo['ci_representante_legal'];?>" name="ciLegal" type="text" id="ciLegal" placeholder="Cédula"  maxlength="10" data-er="^[0-9]+$" disabled="disabled"/>
			</div>
			<div data-linea="13">
				<label for="nombreLegal">Representante legal</label> 
					<input value="<?php echo $operador['nombre_representante'];?>" name="nombreLegal" type="text" id="nombreLegal" placeholder="Nombres" maxlength="200" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" disabled="disabled"/>
			</div>
			<div data-linea="14"> 
					<input value="<?php echo $operador['apellido_representante'];?>" name="apellidoLegal" type="text" id="apellidoLegal" placeholder="Apellidos" maxlength="250" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" disabled="disabled"/>
			</div>
			<div data-linea="15">
				<label for="correoLegal" class="opcional">Correo del representante legal</label> 
				<input value="<?php echo $protocolo['email_representante_legal'];?>" name="correoLegal" type="text" id="correoLegal" placeholder="Correo" class="cuadroTextoCompleto"  maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" disabled="disabled"/>
			</div>
						
		</fieldset>
		
		<button type="submit" class="guardar" style="display: none;">Guardar solicitud</button> 
	</form>
</div>


<div class="pestania" id="P2" style="display: block;">
	<form id='frmNuevaSolicitud2' data-rutaAplicacion='ensayoEficacia' data-opcion='guardarNuevaSolicitud'>
		<input type="hidden"  id="id_protocolo" name="id_protocolo" value="<?php echo $idProtocolo;?>"/>
      <input type="hidden" id="listaIa" name="listaIa" value="<?php echo $listaIa;?>" />
      <input type="hidden" id="resultadoPlagasDeclaradas" name="resultadoPlagasDeclaradas" value="<?php echo $resultadoPlagasDeclaradas;?>" />
     
	
		<fieldset>
			<legend>Datos generales del ensayo</legend>
			<div data-linea="1">
				<label for="normativa">Normativa Aplicada</label> 
				<select name="normativa" id="normativa" disabled="disabled">
					<option value="">Seleccione....</option>
					<?php 
					$normativaLista = $ce->listarElementosCatalogo($conexion,'P1C30');
					foreach ($normativaLista as $key=>$item){
						if(strtoupper($item['codigo']) == strtoupper($protocolo['normativa'])){
							echo '<option value="' . $item['codigo'] . '" selected="selected">' . $item['nombre'] . '</option>';
						}else{
							echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
						}
					}
                    ?>
				</select>				
			</div>
					
				<label>Objetivo del ensayo : </label> 
				<ul  type="circle">
					<?php
					$items = $ce->listarElementosCatalogo($conexion,'P1C1');
					
					foreach ($items as $key=>$item){
						echo '<li>'.$sret=$item['nombre'].'</li>';
					}									
                    ?>
				</ul>						
			<div data-linea="2">		
			</div>
			<div data-linea="3">
				<input type="hidden"  id="varMotivo" name="varMotivo" value=""/>
				<label for="motivo">Motivo del Ensayo</label> 
				<select name="motivo" id="motivo" disabled="disabled">
					<option value="">Seleccione....</option>
					<?php 
					$items = $ce->listarElementosCatalogo($conexion,'P1C2');
					foreach ($items as $key=>$item){
						if(strtoupper($item['codigo']) == strtoupper($protocolo['motivo'])){
							echo '<option value="' . $item['codigo'] . '" selected="selected">' . $item['nombre'] . '</option>';
						}else{
							echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
						}
					}
                    ?>
				</select>
				<div id="cultivoMenor" style="display:none;">
					<label for="aplicaModalidad">Aplica modalidad de cultivos menores</label> 
						SI<input type="radio" id="boolSI" name="boolModalidad" disabled="disabled" value="SI" <?php if($protocolo['cultivo_menor']==true) echo "checked=true"?> />
						NO<input type="radio" id="boolNO" name="boolModalidad" disabled="disabled" value="NO" <?php if($protocolo['cultivo_menor']==false) echo "checked=true"?> />
							
				</div>				
			</div>
			<hr/>
			<div data-linea="4" disabled="disabled">
				<label for="ciTecnico">No de cédula del técnico reconocido por ANC</label> 
				<input value="<?php echo $protocolo['ci_tecnico_reconocido'];?>" name="ciTecnico" type="text" id="ciTecnico" placeholder="Cédula"  maxlength="10" data-er="^[0-9]+$" disabled="disabled"/>
											
			</div>
			<div data-linea="5" disabled="disabled">
				<label for="nombreTecnico">Nombres del Técnico reconocido por ANC</label> 
				<input value="" name="nombreTecnico" type="text" id="nombreTecnico" placeholder="Nombres" maxlength="256" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" disabled="disabled"/>
					
			</div>
			<hr/>
			<div data-linea="6" disabled="disabled">
				<label for="cultivoNomCien">Nombre Cientifico del Cultivo</label> 
				<select name="cultivoNomCien" id="cultivoNomCien" disabled="disabled">
					<option value="">Seleccione....</option>
					<?php 						
					foreach ($cultivosNombres as $key=>$item){
						if(strtoupper($item['id_producto']) == strtoupper($protocolo['cultivo'])){
							echo '<option value="' . $item['id_producto'] . '" selected="selected">' . $item['nombre_cientifico'] . '</option>';
						}else{
							echo '<option value="' . $item['id_producto'] . '">' . $item['nombre_cientifico'] . '</option>';
						}
					}
                    ?>
				</select>
			</div>
			<div data-linea="7" disabled="disabled">
				<label for="cultivoNomComun">Nombre comun del cultivo</label> 					
				<select name="cultivoNomComun" id="cultivoNomComun" disabled="disabled">
					<option value="">Seleccione....</option>
					<?php 						
					foreach ($cultivosNombres as $key=>$item){
						if(strtoupper($item['id_producto']) == strtoupper($protocolo['cultivo'])){
							echo '<option value="' . $item['id_producto'] . '" selected="selected">' . $item['nombre_comun'] . '</option>';
						}else{
							echo '<option value="' . $item['id_producto'] . '">' . $item['nombre_comun'] . '</option>';
						}
					}
                    ?>
				</select>
			</div>
			<div data-linea="8" disabled="disabled">
				<label for="subTipoProducto">Uso propuesto del producto</label> 
				<select name="subTipoProducto" id="subTipoProducto" disabled="disabled">
					<option value="">Seleccione....</option>
					<?php 
										
					foreach ($catalogoSubTipos as $key=>$item){
						if(strtoupper($item['codigo']) == strtoupper($protocolo['uso'])){
							echo '<option value="' . $item['codigo'] . '" selected="selected">' . $item['nombre'] . '</option>';
						}else{
							echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
						}
					}
                    ?>
				</select>
				<div id="evaluarFungico" style="display:none;" disabled="disabled">
					<label for="evaluarFungico">Evaluará complejo fungico? :</label> 
						SI<input type="radio" id="boolFungicoSI" name="boolFungico" disabled="disabled" value="SI" <?php if($protocolo['complejo_fungico']=='t') echo "checked=true"?> />
						NO<input type="radio" id="boolFungicoNO" name="boolFungico" disabled="disabled" value="NO" <?php if($protocolo['complejo_fungico']=='f') echo "checked=true"?> />
							
				</div>
			</div>
			<hr />
         <div data-linea="10">
            <label for="zona_provincia" class="opcional">Provincia:</label>
            <input value="<?php echo $datosGenerales['provincia'];?>" name="zona_provincia" type="text" id="zona_provincia"  maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" disabled="disabled" />
         </div>
         <div data-linea="11">
            <label for="zona_canton" class="opcional">Cantón:</label>
            <input value="<?php echo $datosGenerales['canton'];?>" name="zona_canton" type="text" id="zona_canton"  maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" disabled="disabled" />
         </div>				
		</fieldset>			
		
		<button type="submit" class="guardar" style="display: none;">Guardar solicitud</button> 
	</form>
</div>

<div class="pestania" id="P3" style="display: block;">
	<form id="frmInforme3" data-rutaAplicacion="ensayoEficacia" data-opcion="guardarSolicitudInforme" data-destino="detalleItem">
		<input type="hidden"  id="id_protocolo" name="id_protocolo" value="<?php echo $idProtocolo;?>"/>
		<input type="hidden"  id="plaga_eval_eficacia" name="plaga_eval_eficacia" value="<?php echo $protocolo['plaga_eval_eficacia'];?>"/>
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_informe;?>" /> 
      <input type="hidden" id="dosis_unidad" name="dosis_unidad" value="<?php echo $dosis_unidad;?>" />  
          
      <input type="hidden" id="paso_solicitud" name="paso_solicitud" value="P3">

		<fieldset>
			<legend>Datos del producto</legend>
         <div data-linea="1">
            <label for="plaguicida_nombre" class="opcional">Nombre del producto:</label>
            <input value="<?php echo $protocolo['plaguicida_nombre'];?>" name="plaguicida_nombre" type="text" id="plaguicida_nombre" placeholder="producto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" disabled="disabled" />
         </div>
         <div data-linea="2">
            <label for="caracteristica" class="opcional">Caracteristicas del producto:</label>
            <textarea class="justificado" data-distribuir="no" name="caracteristica" id="caracteristica" maxlength="2048">
					<?php echo htmlspecialchars($datosGenerales['caracteristica']); ?>
						
				</textarea>
         </div>
         <div data-linea="3">
            <label for="ambito" class="opcional">Ambito de aplicación:</label>
            <textarea class="justificado" data-distribuir="no" name="ambito" id="ambito" maxlength="2048">
					<?php echo htmlspecialchars($datosGenerales['ambito']); ?>
						
				</textarea>
         </div>
         <div data-linea="4">
            <label for="efecto_plagas" class="opcional">Efectos sobre plagas y cultivos :</label>
            <textarea class="justificado" data-distribuir="no" name="efecto_plagas" id="efecto_plagas" maxlength="2048">
					<?php echo htmlspecialchars($datosGenerales['efecto_plagas']); ?>
						
				</textarea>
         </div>
         <div data-linea="5">
            <label for="condiciones" class="opcional">Condiciones de uso:</label>
            <textarea class="justificado" data-distribuir="no" name="condiciones" id="condiciones" maxlength="2048">
					<?php echo htmlspecialchars($datosGenerales['condiciones']); ?>
						
				</textarea>
         </div>
         <div data-linea="6">
            <label for="metodo_aplicacion" class="opcional">Métodos de aplicación:</label>
            <textarea class="justificado" data-distribuir="no" name="metodo_aplicacion" id="metodo_aplicacion" maxlength="2048">
					<?php echo htmlspecialchars($datosGenerales['metodo_aplicacion']); ?>
						
				</textarea>
         </div>
         <div data-linea="7">
            <label for="instrucciones" class="opcional">Instrucciones de uso:</label>
            <textarea class="justificado" data-distribuir="no" name="instrucciones" id="instrucciones" maxlength="2048" >
					<?php echo htmlspecialchars($datosGenerales['instrucciones']); ?>
						
				</textarea>
         </div>
         <div data-linea="8">
            <label for="numero_aplicacion" class="opcional">Número y frecuencia de aplicaciones:</label>
            <textarea class="justificado" data-distribuir="no" name="numero_aplicacion" id="numero_aplicacion" maxlength="2048">
					<?php echo htmlspecialchars($datosGenerales['numero_aplicacion']); ?>
						
				</textarea>
         </div>


			<div data-linea="9" class="eficaciaOtra">
            <label for="eficacia" class="opcional">Datos del cálculo de la eficacia:</label>
            <textarea class="justificado obsEficacia" data-distribuir="no" name="eficacia" id="eficacia" maxlength="2048"><?php echo htmlspecialchars($datosGenerales['eficacia']); ?></textarea>
         </div>
			<?php 
			if($protocolo['plaga_eval_eficacia']!='VEE_OTRO'){
				$verInforme=null;
				if($id_informe!=null && $id_informe!='_nuevo' && $id_informe!='')
					$verInforme=$id_informe;
				echo $ce->obtenerMatrizEficacia($conexion,$protocolo['id_protocolo'],$verInforme);

			}
	
			?>
			<div data-linea="10" class="eficaciaStandar">
				<label>Eficacia segun <?php 
											 $items=$ce->obtenerItemDelCatalogo($conexion,'P1C28',$protocolo['plaga_eval_eficacia']);
											 echo strtoupper( $items['nombre']);
											 ?></label>
			</div>
			<table id="tblEficaciaStandar" class="eficaciaStandar">
				<?php 
					if($protocolo['plaga_eval_eficacia']!='VEE_OTRO'){
						$verInforme=null;
						if($id_informe!=null && $id_informe!='_nuevo' && $id_informe!='')
							$verInforme=$id_informe;
						echo $ce->obtenerMatrizEvaluacionEficacia($conexion,$protocolo['id_protocolo'],$protocolo['plaga_eval_eficacia'],$verInforme);
					}
	
					?>
			</table>
        
         <div data-linea="11">
            <label for="dosis" class="opcional">Dosis sugerida del producto:</label>
				<select name="dosis" id="dosis">
					<option value="">Seleccione....</option>
					<?php 
					
					foreach ($dosis as $key=>$item){
						$dosisItem=$item['dosis'].' '.$dosis_unidad;
						if($item['dosis']== $datosGenerales['dosis']){
						    echo '<option value="' . $item['dosis'] . '" selected="selected">' . $dosisItem. '</option>';
						}else{
						    echo '<option value="' . $item['dosis'] . '">' . $dosisItem. '</option>';
						}
					}
                    ?>
				</select>
            
         </div>
         <div data-linea="13">
            <label for="gasto_agua" class="opcional">Gasto de agua [l/ha]:</label>
            <input value="<?php echo $datosGenerales['gasto_agua'];?>" name="gasto_agua" type="number" id="gasto_agua" min="0" max="199999999"  step="0.01" data-er="^[0-9]+$" />

         </div>
         <div data-linea="15">
            <label for="fitotoxicidad" class="opcional">Fitotoxicidad:</label>
            <textarea class="justificado" data-distribuir="no" name="fitotoxicidad" id="fitotoxicidad" maxlength="2048">
					<?php echo htmlspecialchars($datosGenerales['fitotoxicidad']); ?>						
				</textarea>
         </div>
         <div data-linea="16">
            <label for="conclusiones" class="opcional">Conclusiones:</label>
            <textarea class="justificado" data-distribuir="no" name="conclusiones" id="conclusiones" maxlength="2048">
					<?php echo htmlspecialchars($datosGenerales['conclusiones']); ?>						
				</textarea>
         </div>
         <div data-linea="17">
            <label for="recomendaciones" class="opcional">Recomendaciones:</label>
            <textarea class="justificado" data-distribuir="no" name="recomendaciones" id="recomendaciones" maxlength="2048">
					<?php echo htmlspecialchars($datosGenerales['recomendaciones']); ?>						
				</textarea>
         </div>
         
		</fieldset>
		
		<button type="submit" class="guardar" >Guardar solicitud</button>
	</form>
</div>

<div class="pestania" id="P4">		
	<form id="frmAnexos" data-rutaAplicacion="ensayoEficacia" data-opcion="guardarSolicitudInforme" >
		<input type="hidden" id="idProtocolo" name="idProtocolo" value="<?php echo $idProtocolo;?>">
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_informe;?>" />  
      <input type="hidden" id="paso_solicitud" name="paso_solicitud" value="P4">
      
		<input type="hidden" name="opcion" value="0">
								   
	<fieldset>
		<legend>Carga de informe final completo</legend>
		<div data-linea="1">
			
			<a  href='<?php echo $datosGenerales['ruta'];?>' target="_blank" class="archivo_cargado" id="archivo_informe_final">Ver informe final</a>
         
			<input type="file" class="archivo obsInformesFinales" name="informe" id="boolAnexos" accept="application/pdf"/>
			<input type="hidden" class="rutaArchivo" name="archivo" value="<?php echo $datosGenerales['path'];?>"/>
			<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
			<button type="button" class="subirArchivo adjunto obsInformesFinales" id="btnInformeFinal" data-rutaCarga="<?php echo $pathAnexo;?>" >Subir archivo</button>
							
		</div>
		<div data-linea="6">
			<label id="obsInformeFinal"></label>
		</div>
				
	</fieldset>
	</form>
	
</div>

<div class="pestania" id="P5" style="display: block;">
   <form id="frmFinalizarInforme" data-rutaAplicacion="ensayoEficacia" data-opcion="atenderFlujosInformes" data-accionEnExito='ACTUALIZAR'>
      <input type="hidden" id="id_protocolo" name="id_protocolo" value="<?php echo $idProtocolo;?>" />
      <input type="hidden" id="id_documento" name="id_documento" value="<?php echo $id_informe;?>" />

      <input type="hidden" id="id_tramite_flujo" name="id_tramite_flujo" value="<?php echo $id_tramite_flujo;?>" />
      <input type="hidden" id="id_flujo" name="id_flujo" value="<?php echo $id_flujo;?>" />

      <input type="hidden" id="paso_solicitud" name="paso_solicitud" value="P5">
      <input type="hidden" id="opcion_llamada" name="opcion_llamada" value="emitirInformeFinal">
      <input type='hidden' id='id_ruta' name='id_ruta' value='' />

      <fieldset>
         <legend>Finalizar informe</legend>
         <div class="justificado">
            <label for="titulo">Título del ensayo:</label>

            <textarea hidden="hidden" name="titulo" id="titulo" maxlength="516" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">             
				</textarea>
            <br />
            <label id="titulo1"></label>
         </div>
         <hr />

         <div class="justificado">
            <label for="observacion">Condiciones de la información:</label>
            <br />
            
            <label id="observacion">            <?php
            echo $declaracionLegal['pie'];
            ?>
            </label>
         </div>
         <div data-linea="2">
            <label>            <?php
            echo '<a href='.$declaracionLegal['encabezado'].' target="_blank">Lea información confidencial</a>';
                               ?>
            </label>
         </div>
         <hr />
			
         
         <div data-linea="5" class="ocultarOtros">
            <label for="boolFinalizo">Acepto las condiciones</label>
				<input type="checkbox" id="boolFinalizo" name="boolFinalizo" value="NO">
           
         </div>
      </fieldset>

		<fieldset>
			<legend>Documentos habilitantes</legend>
			<div data-linea="1">
				<label>Protocolo aprobado:</label>
                        <?php
								if($protocolo['ruta']!=null)
									echo '<a href="'.$protocolo['ruta'].'" target="_blank">Ver documento</a>';
                        ?>
            
				
         </div>
			
			<div data-linea="2">
				
                        <?php
								if(($identificador!=$idUsuario) &&( $datosGenerales['ruta_informe_inspeccion']!=null)){
									echo "<label>Informe de inspección:</label>";
									echo '<a href="'.$datosGenerales['ruta_informe_inspeccion'].'" target="_blank">Ver documento</a>';
								}
                        ?>
            
				
         </div>
			<div data-linea="3">
				<label>Informe final resumen:</label>
                    <?php
										 if($datosGenerales['ruta_resumen']!=null)
											 echo '<a href="'.$datosGenerales['ruta_resumen'].'" target="_blank">Ver documento</a>';
                               ?>
            
				
         </div>
		</fieldset>

      <button type="submit" id="btnFinalizar" class="guardar" hidden="hidden">Finalizar</button>
   </form>
	<form id='frmCrearPdf' data-rutaAplicacion='ensayoEficacia' data-opcion='crearSolicitudInforme'>
      <button type="button" id="btnCrearPdf" class="guardar">Finalizar</button>
	</form>
	<form id='frmVistaPrevia' data-rutaAplicacion='ensayoEficacia' data-opcion='crearSolicitudInforme'>
      <button id="btnVistaPrevia" type="button" class="adjunto btnVistaPrevia">Generar vista previa</button>
		<a id="verReporte" href="" target="_blank" style="display:none">Ver archivo</a>
	</form>
</div>

<script type="text/javascript" src="aplicaciones/ensayoEficacia/funciones/generales.js"></script>


<script type="text/javascript">

	var protocolo=<?php echo json_encode($protocolo); ?>;
	var solicitud=<?php echo json_encode($datosGenerales); ?>;
	var tramite=<?php echo json_encode($tramite); ?>;

	$("document").ready(function(){

		construirAnimacion(".pestania");

		distribuirLineas();

		ponerTitulo();

		if(solicitud.ruta==null || solicitud.ruta==''){
			$('.archivo_cargado').hide();
		}
		else
			$('.archivo_cargado').show();

		if(tramite.status!='E'){
			//deshabilita casillas
			$('section#detalleItem').find('input:enabled').attr('disabled', 'disabled');
			$('section#detalleItem').find('textarea:enabled', 'select:enabled').attr('disabled', 'disabled');
			$('section#detalleItem').find('select:enabled').attr('disabled', 'disabled');
			
			$('section#detalleItem').find('button:not(.bsig,.bant)').hide();
		}
		$('#btnVistaPrevia').show();
		
		try{
			actualizarSubtipoProducto();
		}catch(e){}

	});

	$('button.btnVistaPrevia').click(function (event) {

		event.preventDefault();
		var form=$(this).parent();
		form.append("<input type='hidden' id='id_documento' name='id_documento' value='"+solicitud.id_informe+"' />"); // añade el nivel del formulario
		form.append("<input type='hidden' id='tituloPrevio' name='tituloPrevio' value='"+$('#titulo').html()+"' />");
		form.append("<input type='hidden' id='esDocumentoLegal' name='esDocumentoLegal' value='' />");
		
		form.attr('data-opcion', 'crearSolicitudInforme');

		mostrarMensaje("Generando archivo ... ",'FALLO');
		$('#verReporte').hide();
		ejecutarJson(form,new exitoVistaPrevia());

	});


	function exitoVistaPrevia(){
		this.ejecutar=function (msg){
			mostrarMensaje(msg.mensaje, "EXITO");
			$('#verReporte').show();
			$('#verReporte').attr('href',msg.datos);
		};
	}



	function ponerTitulo(){
	
		var param={opcion_llamada:'obtenerTitulo', id_protocolo:protocolo.id_protocolo,esInformeFinal:'SI'};
		llamarServidor('ensayoEficacia','atenderLlamadaServidor',param,verTitulo);

	}

	function verTitulo(msg){

		$('#titulo').html(msg);
		$('#titulo1').html(msg);
	}

	//campos numericos
	$('#gasto_agua').numeric();
	$('.valor-numerico').numeric();


	//*********************** SUBMIT*********
	$("#frmNuevaSolicitud").submit(function(event){

		event.preventDefault();


		var error = false;


		if (!error){
			ejecutarJson($(this));
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}


	});


	$("#frmInforme3").submit(function(event){

		event.preventDefault();

		error = false;
		verificarCamposVisiblesNulos(['#caracteristica','#ambito','#efecto_plagas','#condiciones','#metodo_aplicacion','#instrucciones','#numero_aplicacion']);
		verificarCamposVisiblesNulos(['#eficacia','#dosis','#gasto_agua','#fitotoxicidad','#conclusiones','#recomendaciones']);

		if (!error){
			mostrarMensaje('Generando documentación','');
			ejecutarJson($(this),new verResultadoEficacia());
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}


	});

	function verResultadoEficacia(){
		this.ejecutar=function(msg){

			mostrarMensaje(msg.mensaje,'EXITO');
			if(msg.eficacia!='undefined' && msg.eficacia!=null){
				$("#tblEficaciaStandar").empty();
				$("#tblEficaciaStandar").html(msg.eficacia);
			}
			
			
		};
	}

	
	$("#btnCrearPdf").click(function(event){

		event.preventDefault();

		$('#btnFinalizar').click();


	});


	$("#frmFinalizarInforme").submit(function(event){

		event.preventDefault();

		var error = false;

		if($('#archivo_informe_final').attr('href')==null){
			mostrarMensaje("Favor carge el informe final completo","FALLO");
			error = true;
			return;
		}

		if($("#boolFinalizo").is(':checked')!=true){
			mostrarMensaje("Favor aceptar las condiciones para continuar","FALLO");
			error = true;
			return;
		}

		if (!error){

			mostrarMensaje("Generando documentación...","");
			ejecutarJson($(this), new informeEnviado(),new informeFalloAlEnvio());
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}


	});

	function archivoFinalCreado(){
		this.ejecutar=function(msg){
			$("#id_ruta").val(msg.datos);
			$('#btnFinalizar').click();
			
		};
	}


	function informeEnviado() {
		this.ejecutar = function(msg) {
			if(msg.mensaje!=null && msg.mensaje.id_expediente!=null){
				$("#detalleItem").html('<div class="mensajeInicial">Solicitud de informe final ha sido enviada.</div>');
				abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), null, true);
				abrir($("input:hidden"), null, false);
			}else{
				mostrarMensaje("Revise sus datos y complete los campos requeridos", "FALLO");
			}

		};
	}

	function informeFalloAlEnvio() {
		this.ejecutar = function(msg) {
			mostrarMensaje("Revise sus datos y complete los campos requeridos", "FALLO");
		};
	}


	//Anexos
	$('button.subirArchivo').click(function (event) {
		event.preventDefault();

		var boton = $(this);
		var archivo = boton.parent().find(".archivo");
		var str=archivo.val().trim();

		str=str.replace(/[^a-zA-Z0-9.]+/g,'');
		str = str.replace('Cfakepath','');
		try{
			str=str.replace('.'+str.replace(/^.*\./, ''),'');
		}catch(e){}
		var nombre_archivo = protocolo.identificador+"_IF_"+solicitud.id_informe+"_"+str;
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

			$('#archivo_informe_final').attr('href',msg.datos);
			boton=$('#btnInformeFinal');
			if(msg.datos==''){
				boton.removeAttr("disabled");
				$('#archivo_informe_final').text('');
				$('.archivo_cargado').hide();
			}
			else{
				boton.attr("disabled", "disabled");
				$('#archivo_informe_final').text('Ver informe');
				$('.archivo_cargado').show();
			}
		};
	}

	//************************ FIN SUBMIT ***


	var vmotivo="MOT_REG";

	$("#motivo").change(function(){
		motivoConstruir();
	});

	function motivoConstruir(){
		vmotivo=$("#motivo").val();
		$("#varMotivo").val(vmotivo);

		if (vmotivo=="MOT_AMP"){
			$("#cultivoMenor").show();
		}
		else{
			$("#cultivoMenor").hide();
		}

		

		if(vmotivo=="MOT_REG")
		{
			$("#tipoPlaguicida").val("PLAGUICIDA EXPERIMENTAL");
			$("#formuladorNombreExp").show();
			$("#formuladorPaisExp").show();
			$("#formuladorLoteExp").show();
			$("#formuladorNombreCom").hide();
			$("#formuladorPaisCom").hide();

			$("#verPlaguicidaComercial").hide();
			$("#nombreProducto").prop( "disabled", false );
			
			$("[name='plaguicidaComercialView']").hide();
			$("[name='plaguicidaExperimentalView']").show();

		}
		else{
			$("#tipoPlaguicida").val("PLAGUICIDA COMERCIAL");
			$("#formuladorNombreExp").hide();
			$("#formuladorPaisExp").hide();
			$("#formuladorLoteExp").hide();
			$("#formuladorNombreCom").show();
			$("#formuladorPaisCom").show();

			$("#verPlaguicidaComercial").show();
			$("#nombreProducto").prop( "disabled", true );
			
			$("[name='plaguicidaComercialView']").show();
			$("[name='plaguicidaExperimentalView']").hide();
		}

		
		//pone catalogo de productos por defecto
		$("#boolNO").prop( "checked", true );
		$("#boolNO").change();
	}


	//seleccion de cultivos
	$("[name='boolModalidad']").change(function(){
		ponerComboCultivos($(this).val());

	});

	function ponerComboCultivos(esCultivoMenor){
		if(esCultivoMenor=="SI"){	//cultivos menores
			var items=<?php echo json_encode($cultivosMenores); ?>;
			var src='<option value="">Seleccione....</option>';
			var sr='<option value="">Seleccione....</option>';
			for(var i in items)
			{
				src=src+'<option value="' + items[i].id_producto + '">' + items[i].nombre_cientifico + '</option>';
				sr=sr+'<option value="' + items[i].id_producto + '">' + items[i].nombre_comun + '</option>';

			}
			$("#cultivoNomCien").html(src);
			$("#cultivoNomComun").html(sr);
		}
		else{
			var items=<?php echo json_encode($cultivosNombres); ?>;
			var src='<option value="">Seleccione....</option>';
			var sr='<option value="">Seleccione....</option>';
			for(var i in items)
			{
				src=src+'<option value="' + items[i].id_producto + '">' + items[i].nombre_cientifico + '</option>';
				sr=sr+'<option value="' + items[i].id_producto + '">' + items[i].nombre_comun + '</option>';

			}
			$("#cultivoNomCien").html(src);
			$("#cultivoNomComun").html(sr);
		}
	}


	$("#ciTecnico").change(function(){
		var items=<?php echo json_encode($tecnicosReconocidos); ?>;
		var iden=$(this).val();
		var nombres="";
		for(var i in items){
			if(items[i].identificador==iden){
				nombres=items[i].nombres;
				break;
			}

		}
		if(nombres==""){
			mostrarMensaje('El técnico no esta registrado en la ANT','FALLO');
			$(this).val('');
			$(this).focus();
		}
		else
			$('#nombreTecnico').val(nombres);
	});

	$("#cultivoNomCien").change(function(){
		$('#cultivoNomComun').val($("#cultivoNomCien").val());
		$('#cultivoNomComun').change();
	});

	function encerarPlagas(){
		$("#listaPlagas").val("");
		$("#resultadoPlagasDeclaradas").html("");
		$("#btnAddPlaga").show();
		$("#elegirPlagaDeclarada").val("");
		$("#elegirPlagaComun").val("");

		$("#jsonPlagas").val("");
		var tipoProd=$("#subTipoProducto").val();
		if(tipoProd=="RIA-F" && $("#boolFungicoSI").is(":checked"))
			llenarPlagasComunesFungico();
		else
			llenarPlagasComunes();
	}

	function llenarPlagasComunes(){
		$('#elegirPlagaComun').children('option').remove();
		$('#elegirPlagaComun').append($("<option></option>").attr("value","").text("Seleccione...."));
	
		$.each(selectValues, function(key, value) {
		$('#elegirPlagaComun')
			.append($("<option></option>")
			.attr("value",value.codigo)
			.text(value.nombre2));
		});
		$('#elegirPlagaComun').prop('disabled', 'disabled');
	}

	function llenarPlagasComunesFungico(){
		$('#elegirPlagaComun').children('option').remove();
		$('#elegirPlagaComun').append($("<option></option>").attr("value","").text("Seleccione...."));
	
		$.each(selectValues, function(key, value) {
			$('#elegirPlagaComun')
				.append($("<option></option>")
				.attr("value",value.codigo)
				.text(value.nombre));
		});
		$('#elegirPlagaComun').removeAttr("disabled");
	}

	$("#subTipoProducto").change(function(){
		var sprod=$("#subTipoProducto").val();
		if (sprod=="RIA-F"){
			$("#evaluarFungico").show();
			$("#disAplicarFungicida").show();	//para aplicacion del fungicida

		}
		else{
			$("#evaluarFungico").hide();
			$("#disAplicarFungicida").hide();	//para aplicacion de fungicida

		}
		if (sprod=="RIA-COAD" || sprod=="RIA-RP"){
			$("#titleCoadyuvante").html("Datos del PRODUCTO");
		}
		else{
			$("#titleCoadyuvante").html("Datos del COADYUVANTE");
		}

		//estadío de la plaga
		if(sprod=="RIA-I" || sprod=="RIA-A"){
			$("#disEstadioInsecto").show();
			$("#disEstadioInsectoOtro").hide();
		}
		else{
			$("#disEstadioInsecto").hide();
			$("#disEstadioInsectoOtro").hide();
		}

		//para aplicacion del herbicida
		if(sprod=="RIA-H"){
			$("#disAplicarHerbicida").show();
		}
		else{
			$("#disAplicarHerbicida").hide();
		}


		//Resetea las plagas
		encerarPlagas();

		

	});

	//ver funcion para reemplazar
	function actualizarSubtipoProducto(){
		var sprod=$("#subTipoProducto").val();
		if (sprod=="RIA-F"){
			$("#evaluarFungico").show();
			$("#disAplicarFungicida").show();	//para aplicacion del fungicida

		}
		else{
			$("#evaluarFungico").hide();
			$("#disAplicarFungicida").hide();	//para aplicacion de fungicida

		}
		if (sprod=="RIA-COAD" || sprod=="RIA-RP"){
			$("#titleCoadyuvante").html("Datos del PRODUCTO");
		}
		else{
			$("#titleCoadyuvante").html("Datos del COADYUVANTE");
		}

		//estadío de la plaga
		if(sprod=="RIA-I" || sprod=="RIA-A"){
			$("#disEstadioInsecto").show();
			$("#disEstadioInsectoOtro").hide();
		}
		else{
			$("#disEstadioInsecto").hide();
			$("#disEstadioInsectoOtro").hide();
		}

		//para aplicacion del herbicida
		if(sprod=="RIA-H"){
			$("#disAplicarHerbicida").show();
		}
		else{
			$("#disAplicarHerbicida").hide();
		}


		//Resetea las plagas
		
		if(sprod=="RIA-F" && $("#boolFungicoSI").is(":checked"))
			llenarPlagasComunesFungico();
		else
			llenarPlagasComunes();

	}

	

	$("#boolFungicoSI").change(function(){
		encerarPlagas();
		llenarPlagasComunesFungico();
	});

	$("#boolFungicoNO").change(function(){
		encerarPlagas();
		llenarPlagasComunes();
	});

	$("#elegirPlagaDeclarada").change(function(){
		$("#elegirPlagaComun").val($(this).val());
	});

	

	//plaguicida en prueba

	$("#nombreProducto").change(function(){
		if(vmotivo=="MOT_REG"){
			var noReg=$(this).val();
			if(noReg!=""){
				var param={nombreProducto:noReg.trim()};
				llamarServidor('ensayoEficacia','consultarNombreProducto',param,verificarNombrePlagicida);
			}
		}

	});

	function verificarNombrePlagicida (items){
		if(items==null){
			
		}
		else{
			mostrarMensaje("Nombre ya existe, intente otro nombre",'FALLO');
			$("#nombreProducto").val('');
			$("#nombreProducto").focus();
		}
	}

	$("#noRegistro").change(function(){
		var noReg=$(this).val();
		if(noReg!=""){
			var param={noRegistro:noReg};
			llamarServidor('ensayoEficacia','consultarNoRegistroProducto',param,llenarPlagicidaComercial);
		}
	});

	var fabricantesPrueba={};

	function llenarPlagicidaComercial (items){
		var formulacion='';
		if(items==null){
			//no hace nada link
			mostrarMensaje("El número de registro introducido no existe",'FALLO');
			$("#noRegistro").val('');
			$("#noRegistro").focus();
		}
		else{
			if(items.producto==null)
				mostrarMensaje("Producto nulo",'FALLO');
			else{
				
				$("#nombreProducto").val(items.producto[0].nombre_comun);
				formulacion=items.producto[0].formulacion;
			}

			
			if(items.composicion!=null){
				var sfor="";
				$.each(items.composicion, function(key, value) {
					if(sfor=="")
						sfor=value.ingrediente_activo+" "+value.concentracion;
					else
						sfor=sfor+" + "+value.ingrediente_activo+" "+value.concentracion;

				});
				if(sfor!="")
					sfor=sfor+", "+formulacion;
				$('#plagPruebaIa').val(sfor);
			}
			if(items.fabricantes!=null){
				fabricantesPrueba.length=0;
				var sr='<option value="">Seleccione....</option>';
				for(var i in items.fabricantes)
				{
					var map=items.fabricantes[i];
					sr=sr+'<option value="' + map["id_fabricante_formulador"] + '">' + map['nombre'] + '</option>';
					fabricantesPrueba[map["id_fabricante_formulador"]]=map["pais_origen"];
				}
				$("#formuladorNombreCom").html(sr);

			}
		}
	}


</script>

