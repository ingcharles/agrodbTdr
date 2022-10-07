
<link rel='stylesheet'
	href='<?php echo URL_MVC_MODULO ?>InspeccionAntePostMortemCF/vistas/estilos/estiloModal.css'>

<link rel='stylesheet'
	href='<?php echo URL_RESOURCE ?>estilos/bootstrap.min.css'>
<script src="<?php echo URL_RESOURCE ?>js/bootstrap.min.js"
	type="text/javascript"></script>
<script
	src="<?php echo URL ?>modulos/InspeccionAntePostMortemCF/vistas/js/funcionCf.js"></script>

<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id='formulario'
	data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>InspeccionAntePostMortemCF'
	data-opcion='detalleanteaves/guardar' data-destino="detalleItem"
	data-accionEnExito="ACTUALIZAR" method="post">
	<div class="pestania">
		<input type="hidden" id="id_formulario_ante_mortem"
			name="id_formulario_ante_mortem"
			value="<?php echo $this->idFormularioAnteMortem;?>" /> <input
			type="hidden" id="id_formulario_post_mortem"
			name="id_formulario_post_mortem"
			value="<?php echo $this->modeloFormularioPostMortem->getIdFormularioPostMortem();?>" />

		<fieldset>
			<legend>Identificación del Centro de Faenamiento</legend>

			<div data-linea="1">
				<label for="provincia">Provincia: </label> <input type="text"
					id="provincia" name="provincia"
					value="<?php echo $this->provincia; ?>" disabled />
			</div>
			<div data-linea="1">
				<label for="canton">Cantón:</label> <input type="text" id="canton"
					name="canton" value="<?php echo $this->canton; ?>" disabled />
			</div>
			<div data-linea="1">
				<label for="parroquia">Parroquia: </label> <input type="text"
					id="parroquia" name="parroquia"
					value="<?php echo $this->parroquia; ?>" disabled />
			</div>
			<div data-linea="2">
				<label for="razonSocial">Nombre del Establecimiento: </label> <input
					type="text" id="razonSocial" name="razonSocial"
					value="<?php echo $this->razonSocial; ?>" disabled />
			</div>
			<div data-linea="3">
				<label for="nombreMedico">Nombre del Médico Veterinario Autorizado </label>
				<input type="text" id="nombreMedico" name="nombreMedico"
					value="<?php echo $this->nombreMedico; ?>" disabled />
			</div>
			<div data-linea="4">
				<label for="fecha_formulario">Fecha: </label> <input type="text"
					id="fecha_formulario" name="fecha_formulario" readonly
					placeholder="Fecha del formulario" />
			</div>
		</fieldset>

		<fieldset id="especieAnimal">
			<legend>Especies de animales de abasto</legend>

			<div data-linea="1">
				<label for="especie">Especie: </label> <input type="text"
					id="especie" name="especie"
					value="<?php echo $this->modeloDetalleAnteAnimales->getEspecie();?>"
					disabled maxlength="8" />
			</div>
			<div data-linea="1">
				<label for="num_total_animales">Nro. Total de animales </label> <input
					type="text" id="num_total_animales" name="num_total_animales"
					disabled
					value="<?php echo $this->modeloDetalleAnteAnimales->getNumTotalAnimales();?>"
					maxlength="8" />
			</div>
			<div data-linea="2">
				<label for="num_hembras">Nro. Hembras: </label> <input type="text"
					id="num_hembras" name="num_hembras"
					value="<?php echo $this->modeloDetalleAnteAnimales->getNumHembras();?>"
					disabled maxlength="8" />
			</div>
			<div data-linea="2">
				<label for="num_machos">Nro. Machos: </label> <input type="text"
					id="num_machos" name="num_machos" disabled
					value="<?php echo $this->modeloDetalleAnteAnimales->getNumMachos();?>"
					maxlength="64" />
			</div>

		</fieldset>

		<fieldset id="estadoGeneral">
			<legend>Hallazgos diagnosticados al examen post - mortem</legend>
			<div data-linea="1" id="hallazgosDiv1">
				<label for="enfermedad">Enfermedad: </label> <select id="enfermedad"
					name="enfermedad"> 
            		<?php
														echo $this->comboEnfermedad($this->modeloDetallePostAves->getDestinoDecomisos());
														?>
				</select>
			</div>
			<div data-linea="1" id="hallazgosDiv2">
				<label for="localizacion">Localizacion: </label> <select
					id="localizacion" name="localizacion"> 
            		<?php
														echo $this->comboLocalizacion($this->modeloDetallePostAves->getDestinoDecomisos());
														?>
				</select>
			</div>

			<div data-linea="2" id="hallazgosDiv3">
				<label for="num_animales_afectados">Nro. Animales afectados: </label>
				<input type="text" id="num_animales_afectados"
					name="num_animales_afectados"
					value="<?php echo $this->modeloDetallePostAves->getNumPododermatitis(); ?>"
					placeholder="Números de animales afectados" maxlength="8" />
			</div>
			<div data-linea="3">
				<button type="button" id="agregarHallazgos" class="mas">Agregar</button>
				<hr>
			</div>
			
			<div data-linea="4" id="hallazgosDiv11">
				<label for=""><h3>Endoparásitos</h3> </label>
			</div>
			<br>
			<div data-linea="5" id="hallazgosDiv4">
				<label for="endoparasitos_presencia">Presencia (describir cual)</label>
				<input type="text" id="endoparasitos_presencia"
					value="<?php echo $this->modeloDetallePostAves->getPorcentNumPododermatitis(); ?>"
					name="endoparasitos_presencia"
					placeholder="Descripción de endoparasitos" maxlength="64" />
			</div>

			<div data-linea="5" id="hallazgosDiv5">
				<label for="endoparasitos_localizacion">Localización: </label> <input
					type="text" id="endoparasitos_localizacion"
					name="endoparasitos_localizacion"
					value="<?php echo $this->modeloDetallePostAves->getNumLesionesPiel(); ?>"
					placeholder="Endoparasitos localización" maxlength="64" />
			</div>

			<div data-linea="6" id="hallazgosDiv6">
				<label for="endoparasitos_num_afectados">Nro. Animales afectados: </label>
				<input type="text" id="endoparasitos_num_afectados"
					value="<?php echo $this->modeloDetallePostAves->getPorcentNumLesionesPiel(); ?>"
					name="endoparasitos_num_afectados"
					placeholder="Endoparasitos números de afectados" maxlength="8" />
			</div>
			<div data-linea="7">
				<button type="button" id="agregarEndoparasitos" class="mas">Agregar</button>
			</div>
			<hr>
			<div data-linea="8" id="hallazgosDiv10">
				<label for=""><h3>Ectoparásitos</h3> </label>
			</div>
			<br>
			<div data-linea="9" id="hallazgosDiv7">
				<label for="ectoparasitos_presencia">Presencia (describir cual): </label>
				<input type="text" id="ectoparasitos_presencia"
					value="<?php echo $this->modeloDetallePostAves->getPorcentNumMalSangrado(); ?>"
					name="ectoparasitos_presencia"
					placeholder="Llave foránea de la tabla detalle_post_animales"
					maxlength="64" />
			</div>

			<div data-linea="9" id="hallazgosDiv8">
				<label for="ectoparasitos_localizacion">Localización: </label> <input
					type="text" id="ectoparasitos_localizacion"
					value="<?php echo $this->modeloDetallePostAves->getNumContusionPierna(); ?>"
					name="ectoparasitos_localizacion"
					placeholder="Ectoparasitos localización" maxlength="64" />
			</div>

			<div data-linea="10" id="hallazgosDiv9">
				<label for="ectoparasitos_num_afectados">Nro. Animales afectados: </label>
				<input type="text" id="ectoparasitos_num_afectados"
					value="<?php echo $this->modeloDetallePostAves->getPorcentNumContusionPierna(); ?>"
					name="ectoparasitos_num_afectados"
					placeholder="Ectoparasitos números de afectados" maxlength="8" />
			</div>
			<div data-linea="11">
				<button type="button" id="agregarEctoparasitos" class="mas">Agregar</button>
			</div>
			<hr>
			<table id="detalleHallazgos" style="width: 100%">
				<tbody>

					<tr>
						<th>Enfermedad</th>
						<th>Localización</th>
						<th>Nro. Animanimales afectados</th>
					</tr>
				
				
				<tbody id="bodyTbl">
                    <?php echo $this->hallazgosDiagnosticados;?>
				</tbody>
			</table>
			<hr>
			<div data-linea="12" >
				<label for="estado_nodulos_linfaticos">Estado de los nódulos
					linfáticos: </label> <input type="text"
					id="estado_nodulos_linfaticos" name="estado_nodulos_linfaticos"
					value="<?php echo $this->modeloDetallePostAnimales->getEstadoNodulosLinfaticos(); ?>"
					placeholder="Estado nódulos linfaticos" maxlength="32" />
			</div>

			<div data-linea="13">
				<label for="otro_diagnostico">Otros: </label> <input type="text"
					id="otro_diagnostico"
					value="<?php echo $this->modeloDetallePostAnimales->getOtroDiagnostico(); ?>"
					name="otro_diagnostico" placeholder="Registrar nuevo diagnostico"
					maxlength="512" />
			</div>



		</fieldset>

	</div>
	<div class="pestania">
		<fieldset id="resultadosDecomisos">
			<legend>Resultados y decomisos</legend>


			<div data-linea="1">
				<label for="num_colibacilosis"><h1>Órganos</h1></label>
			</div>
			<div data-linea="2">
				<label for="organo_decomisado">Órgano decomisado </label> <select
					id="organo_decomisado" name="organo_decomisado"> 
            		<?php
														echo $this->comboOrganoDecomisado($this->modeloDetallePostAves->getDestinoDecomisos());
														?>
				</select>
			</div>

			<div data-linea="2">
				<label for="razon_decomiso">Razón del decomiso: </label> <select
					id="razon_decomiso" name="razon_decomiso"> 
            		<?php
														echo $this->comboRazonDecomisado($this->modeloDetallePostAves->getDestinoDecomisos());
														?>
				</select>
			</div>

			<div data-linea="3">
				<label for="num_organos_decomisados">Nro. Órganos decomisados: </label>
				<input type="text" id="num_organos_decomisados"
					value="<?php echo $this->modeloDetallePostAves->getPorcentNumPododermatitis(); ?>"
					name="num_organos_decomisados"
					placeholder="Número de órganos decomisados" maxlength="8" />
			</div>

			<div data-linea="4">
				<button type="button" id="agregarOrganos" class="mas">Agregar</button>
			</div>
			<hr>
			<table id="detalleOrgano" style="width: 100%">
				<tbody>

					<tr>
						<th>Nro.</th>
						<th>Órgano</th>
						<th>Razón decomiso</th>
					</tr>
				
				
				<tbody id="bodyTblOrgano">
                  <?php echo $this->resultadoOrgano;?>
				</tbody>
			</table>
			<hr>
			<div data-linea="5">
				<label for="num_lesiones_piel"><h1>Canales</h1> </label>
			</div>
			<div data-linea="6">
				<label for="num_lesiones_piel">Decomiso parcial </label>
			</div>

			<div data-linea="7">
				<label for="razon_decomiso">Razón del decomiso: </label> <select
					id="razon_decomiso_parcial" name="razon_decomiso_parcial"> 
            		<?php
														echo $this->comboRazonDecomisado($this->modeloDetallePostAves->getDestinoDecomisos());
														?>
				</select>
			</div>

			<div data-linea="7">
				<label for="num_canales_decomisadas_parcial">Nro. Canales
					decomisadas: </label> <input type="text"
					id="num_canales_decomisadas_parcial"
					name="num_canales_decomisadas_parcial"
					value="<?php echo $this->modeloDetallePostAves->getNumMalSangrado(); ?>"
					placeholder="Números de canales decomisadas parcial" maxlength="8" />
			</div>

			<div data-linea="8">
				<label for="peso_carne_aprobada_parcial">Peso de carne aprobada: </label>
				<input type="text" id="peso_carne_aprobada_parcial"
					value="<?php echo $this->modeloDetallePostAves->getPorcentNumMalSangrado(); ?>"
					name="peso_carne_aprobada_parcial"
					placeholder="Peso de carne aprobada" maxlength="8" />
			</div>

			<div data-linea="8">
				<label for="peso_carne_decomisada_parcial">Peso de carne decomisada:
				</label> <input type="text" id="peso_carne_decomisada_parcial"
					value="<?php echo $this->modeloDetallePostAves->getNumContusionPierna(); ?>"
					name="peso_carne_decomisada_parcial"
					placeholder="Peso de carne decomisada parcial" maxlength="8" />
			</div>
			<div data-linea="9">
				<button type="button" id="agregarCanalesParcial" class="mas">Agregar</button>
			</div>
			<table id="detalleDecomisoParcial" style="width: 100%">
				<tbody>

					<tr>
						<th>Razón decomiso</th>
						<th>Nro. Canales decomisadas</th>
						<th>Peso carne aprobada</th>
						<th>Peso carne decomisada</th>
					</tr>
				
				<tbody id="bodyTblParcial">
                      <?php echo $this->resultadoDecomisoParcial;?>
				</tbody>
			</table>
			<br> <br>
			<div data-linea="10">
				<label for="">Decomiso total</label>
			</div>
			<div data-linea="11">
				<label for="razon_decomiso">Rázon del decomiso: </label> <select
					id="razon_decomiso_total" name="razon_decomiso_total"> 
            		<?php
														echo $this->comboRazonDecomisado($this->modeloDetallePostAves->getDestinoDecomisos());
														?>
				</select>
			</div>

			<div data-linea="11">
				<label for="num_canales_decomisadas_total">Nro. Canales decomisadas:
				</label> <input type="text" id="num_canales_decomisadas_total"
					value="<?php echo $this->modeloDetallePostAves->getPorcentNumContusionAla(); ?>"
					name="num_canales_decomisadas_total"
					placeholder="Números de canales decomisadas total" maxlength="8" />
			</div>
			<div data-linea="12">
				<label for="peso_carne_decomisada_total">Peso carne decomisada: </label>
				<input type="text" id="peso_carne_decomisada_total"
					value="<?php echo $this->modeloDetallePostAves->getNumContusionPechuga(); ?>"
					name="peso_carne_decomisada_total"
					placeholder="Peso de carne decomisada total" maxlength="8" />
			</div>

			<div data-linea="13">
				<button type="button" id="agregarCanalesTotal" class="mas">Agregar</button>
			</div>
			<table id="detalleDecomisoTotal" style="width: 100%">
				<tbody>

					<tr>
						<th>Razón decomiso</th>
						<th>Nro. Canales decomisadas</th>
						<th>Peso carne decomisada</th>
					</tr>
				
				
				<tbody id="bodyTblTotal">
				<?php echo $this->resultadoDecomisoTotal;?>
				</tbody>
			</table>
			<hr>


			<div data-linea="14">
				<label for=""><h1>Datos productivos</h1></label>
			</div>
			<div data-linea="15">
				<label for=""><h3>Decomiso parcial</h3></label>
			</div>
			<div data-linea="16">
				<label for="num_canales_decomiso_parcial">Nro. Total de canales con
					decomiso parcial: </label> <input type="text"
					id="num_canales_decomiso_parcial"
					name="num_canales_decomiso_parcial"
					value="<?php echo $this->modeloDetallePostAnimales->getNumCanalesDecomisoParcial(); ?>"
					placeholder="Número de  canales de decomiso parcial" maxlength="8" />
			</div>

			<div data-linea="16">
				<label for="peso_total_carne_aprobada">Peso total de carne aprobada
					(kg): </label> <input type="text" id="peso_total_carne_aprobada"
					name="peso_total_carne_aprobada"
					value="<?php echo $this->modeloDetallePostAnimales->getPesoTotalCarneAprobada(); ?>"
					placeholder="Peso total de carne aprobada" maxlength="8" />
			</div>
			<div data-linea="17">
				<label for="peso_total_carne_decomisada">Peso total de carne
					decomisada (kg): </label> <input type="text"
					id="peso_total_carne_decomisada" name="peso_total_carne_decomisada"
					value="<?php echo $this->modeloDetallePostAnimales->getPesoTotalCarneDecomisada(); ?>"
					placeholder="Peso total de carne decomisada" maxlength="8" />
			</div>
			<hr>
			<div data-linea="18">
				<label for=""><h3>Decomiso total</h3></label>
			</div>
			<div data-linea="19">
				<label for="num_canales_decomiso">Nro. Total de canales con decomiso
					total: </label> <input type="text" id="num_canales_decomiso"
					name="num_canales_decomiso"
					value="<?php echo $this->modeloDetallePostAnimales->getNumCanalesDecomiso(); ?>"
					placeholder="Número de canales de decomiso" maxlength="8" />
			</div>

			<div data-linea="19">
				<label for="peso_total_carne_decomisada_productivo">Peso total de
					carne decomisada (kg): </label> <input type="text"
					id="peso_total_carne_decomisada_productivo"
					value="<?php echo $this->modeloDetallePostAnimales->getPesoTotalCarneAprobadaProductivos(); ?>"
					name="peso_total_carne_decomisada_productivo"
					placeholder="Peso total carne decomisada productivo" maxlength="8" />
			</div>
			<hr>
			<div data-linea="20">
				<label for=""><h3>Datos generales</h3></label>
			</div>
			<div data-linea="21">
				<label for="num_canales_aprobadas_totalmente">Nro. de canales
					aprobados totalmente: </label> <input type="text"
					id="num_canales_aprobadas_totalmente"
					name="num_canales_aprobadas_totalmente"
					value="<?php echo $this->modeloDetallePostAnimales->getNumCanalesAprobadasTotalmente(); ?>"
					placeholder="Número de canales aprobadas totalmente" maxlength="8" />
			</div>

			<div data-linea="21">
				<label for="num_canales_aprobadas_parcialmente">Nro. de canales
					aprobadas parcialmente: </label> <input type="text"
					id="num_canales_aprobadas_parcialmente"
					value="<?php echo $this->modeloDetallePostAnimales->getNumCanalesAprobadasParcialmente(); ?>"
					name="num_canales_aprobadas_parcialmente"
					placeholder="Número de canales aprobadas parcialmente"
					maxlength="8" />
			</div>

			<div data-linea="22">
				<label for="peso_total_carne_aprobada_productivos">Peso total de
					carne aprobada (kg): </label> <input type="text"
					id="peso_total_carne_aprobada_productivos"
					name="peso_total_carne_aprobada_productivos"
					value="<?php echo $this->modeloDetallePostAnimales->getPesoTotalCarneAprobadaProductivos(); ?>"
					placeholder="Peso total de carne decomisada productivo"
					maxlength="8" />
			</div>

			<div data-linea="22">
				<label for="peso_promedio_canal">Peso promedio de la canal (kg): </label>
				<input type="text" id="peso_promedio_canal"
					value="<?php echo $this->modeloDetallePostAnimales->getPesoPromedioCanal(); ?>"
					name="peso_promedio_canal" placeholder="Peso promedio de canal"
					maxlength="8" />
			</div>

			<div data-linea="23">
				<label for="peso_total_visceras_decomisadas">Peso total de víseras
					decomisadas (kg): </label> <input type="text"
					id="peso_total_visceras_decomisadas"
					value="<?php echo $this->modeloDetallePostAnimales->getPesoTotalViscerasDecomisadas(); ?>"
					name="peso_total_visceras_decomisadas"
					placeholder="Peso total de visceras decomisadas" maxlength="8" />
			</div>
			<hr>
			<div data-linea="24">
				<label for=""><h3>Destino de los decomisos</h3></label>
			</div>

			<div data-linea="25">
				<label for="">kg destinados a incineración</label>
			</div>
			<div data-linea="26">
				<label for="peso_carne_incineracion">Kg de carne: </label> <input
					type="text" id="peso_carne_incineracion"
					value="<?php echo $this->modeloDetallePostAnimales->getPesoCarneIncineracion(); ?>"
					name="peso_carne_incineracion"
					placeholder="Peso de carne para incineración" maxlength="8" />
			</div>
			<div data-linea="26">
				<label for="peso_visceras_incineracion">Kg de vísceras: </label> <input
					type="text" id="peso_visceras_incineracion"
					name="peso_visceras_incineracion"
					value="<?php echo $this->modeloDetallePostAnimales->getPesoViscerasIncineracion(); ?>"
					placeholder="Peso de visceras para incineración" maxlength="8" />
			</div>
			<br>
			<div data-linea="28">
				<label for="">kg destinados a rendering</label>
			</div>
			<div data-linea="29">
				<label for="peso_carne_rendering">Kg de carne: </label> <input
					type="text" id="peso_carne_rendering"
					value="<?php echo $this->modeloDetallePostAnimales->getPesoCarneRendering(); ?>"
					name="peso_carne_rendering"
					placeholder="Peso de carne para rendering" maxlength="8" />
			</div>
			<div data-linea="29">
				<label for="peso_visceras_rendering">Kg de vísceras: </label> <input
					type="text" id="peso_visceras_rendering"
					name="peso_visceras_rendering"
					value="<?php echo $this->modeloDetallePostAnimales->getPesoViscerasRendering(); ?>"
					placeholder="Peso de visceras para rendering" maxlength="8" />
			</div>
			<br>
			<div data-linea="31">
				<label for="">kg destinados a descomposición controlada (abono)</label>
			</div>
			<div data-linea="32">
				<label for="peso_carne_abono">Kg de carne: </label> <input
					type="text" id="peso_carne_abono"
					value="<?php echo $this->modeloDetallePostAnimales->getPesoCarneAbono(); ?>"
					name="peso_carne_abono" placeholder="Peso de carne para abono"
					maxlength="8" />
			</div>
			<div data-linea="32">
				<label for="peso_visceras_abono">Kg de vísceras: </label> <input
					type="text" id="peso_visceras_abono" name="peso_visceras_abono"
					value="<?php echo $this->modeloDetallePostAnimales->getPesoViscerasAbono(); ?>"
					placeholder="Peso de visceras para abono" maxlength="8" />
			</div>
			<br>
			<div data-linea="34">
				<label for="">kg destinados a gestor ambiental autorizado</label>
			</div>
			<div data-linea="35">
				<label for="peso_carne_ambiental">Kg de carne: </label> <input
					type="text" id="peso_carne_ambiental"
					value="<?php echo $this->modeloDetallePostAnimales->getPesoCarneAmbiental(); ?>"
					name="peso_carne_ambiental"
					placeholder="Peso de carne ambiental autorizado" maxlength="8" />
			</div>
			<div data-linea="35">
				<label for="peso_visceras_ambiental">Kg de vísceras: </label> <input
					type="text" id="peso_visceras_ambiental"
					name="peso_visceras_ambiental"
					value="<?php echo $this->modeloDetallePostAnimales->getPesoViscerasAmbiental(); ?>"
					placeholder="Peso de visceras ambiental autorizado" maxlength="8" />
			</div>

			<hr>
			<br>
			<div data-linea="36">
				<label for=""><h3>Lugar de disposición final</h3></label>
			</div>
			<div data-linea="37">
				<label for="lugar_incineracion">Lugar de incineración: </label> <input
					type="text" id="lugar_incineracion"
					value="<?php echo $this->modeloDetallePostAnimales->getLugarIncineracion(); ?>"
					name="lugar_incineracion" placeholder="Lugar de incineración"
					maxlength="512" />
			</div>
			<div data-linea="37">
				<label for="lugar_renderizacion">Lugar de renderización: </label> <input
					type="text" id="lugar_renderizacion"
					value="<?php echo $this->modeloDetallePostAnimales->getLugarRenderizacion(); ?>"
					name="lugar_renderizacion" placeholder="Lugar de renderización"
					maxlength="512" />
			</div>

			<div data-linea="38">
				<label for="lugar_desconposicion">Lugar de descomposición
					controlada: </label> <input type="text" id="lugar_desconposicion"
					value="<?php echo $this->modeloDetallePostAnimales->getLugarDesconposicion(); ?>"
					name="lugar_desconposicion" placeholder="Lugar de desconposición"
					maxlength="512" />
			</div>
			<div data-linea="38">
				<label for="nombre_gestor_ambiental">Nombre del gestor ambiental
					autorizado: </label> <input type="text"
					id="nombre_gestor_ambiental" name="nombre_gestor_ambiental"
					value="<?php echo $this->modeloDetallePostAnimales->getNombreGestorAmbiental(); ?>"
					placeholder="Nombre de gestor ambiental" maxlength="512" />
			</div>


		</fieldset>
		<fieldset id="actividades">
			<legend>Actividades realizadas por el médico veterinario oficial u
				autorizado durante la inspección post-mortem para determinar la
				información que consta en este formulario</legend>
			<div data-linea="1">
				<input type="checkbox" id="examenVisual" name="examenVisual"
					placeholder="Examen visual" maxlength="512" <?php echo ($this->modeloDetallePostAnimales->getExamenVisual() == false) ? '':'checked'; ?>/> <label
					id="examenVisualTxt">Examen visual </label>
			</div>
			<div data-linea="1">
				<input type="checkbox" id="palpacion" name="palpacion"
					placeholder="Palpación" maxlength="512" <?php echo ($this->modeloDetallePostAnimales->getPalpacion() == false) ? '':'checked'; ?>/> <label id="palpacionTxt">Palpación
				</label>
			</div>
			<div data-linea="2">
				<input type="checkbox" id="insicion" name="insicion"
					placeholder="Insición" maxlength="512" <?php echo ($this->modeloDetallePostAnimales->getInsicion() == false) ? '':'checked'; ?>/> <label id="insicionTxt">Insición
				</label>
			</div>
			<div data-linea="2">

				<input type="checkbox" id="tomaMuestra" name="tomaMuestra"
					placeholder="tomaMuestra" maxlength="512" <?php echo ($this->modeloDetallePostAnimales->getTomaMuestra() == false) ? '':'checked'; ?>/> <label
					id="tomaMuestraTxt">Toma de muestra</label>
			</div>
			<div data-linea="3">
				<input type="checkbox" id="organoTejido" name="organoTejido"
					placeholder="Órgano o tejido" <?php echo ($this->modeloDetallePostAnimales->getOrganoTejido() == false) ? '':'checked'; ?>/> <label id="organoTejidoTxt">Órgano
					o tejido que se tomó la muestra </label> <input type="text"
					id="descripcion_actividad" name="descripcion_actividad"
					value="<?php echo $this->modeloDetallePostAnimales->getDescripcionActividad(); ?>"
					placeholder="Órgano o tejido que se tomó la muestra"
					maxlength="512" size="40" />
			</div>
			<br>
			<div data-linea="4">
				<label for="descripcion_actividad_general">Descripción del proceso :
				</label> <input type="text" id="descripcion_actividad_general"
					name="descripcion_actividad_general"
					value="<?php echo $this->modeloDetallePostAnimales->getDescripcionActividadGeneral(); ?>"
					placeholder="Descripcion de la actividad" maxlength="1024" />
			</div>

		</fieldset>
		<fieldset id="observaciones">
			<legend>Observaciones</legend>

			<div data-linea="1">
				<input type="text" id="observacion" name="observacion"
					value="<?php echo $this->modeloDetallePostAnimales->getObservacion(); ?>"
					placeholder="Observaciones del formulario" maxlength="1024" />
			</div>

		</fieldset>
		<div data-linea="1">
			<button id="agregarFormulario" type="button" class="guardar"></button>
		</div>
		<div data-linea="1">

			<button type="button" id="enviarRevision" class="">Enviar a revisión</button>
			<button type="button" id="aprobar" class="">Aprobar</button>
			<button type="button" id="generar" class="">Generar</button>
		</div>
	</div>
</form>
<iframe id="formularioCreado" width="100%" height="100%"
	src="<?php echo $this->urlExcel; ?>" frameborder="0" allowfullscreen></iframe>
<script type="text/javascript">
	var hallazgos = [];
	var organos = [];
	var decomisoParcial = [];
	var decomisoTotal = [];
	var organoTejido =  <?php echo json_encode($this->modeloDetallePostAnimales->getOrganoTejido());?>;
    var fechaInical = <?php echo json_encode($this->fechaInicial);?>;
    var idCentroFaenamiento = <?php echo json_encode($this->idCentroFaenamiento);?>;
    var idFormularioDetalle = <?php echo json_encode($this->idFormularioEditar);?>;
    var idDetalleAnteAnimales = <?php echo json_encode($this->modeloDetalleAnteAnimales->getIdDetalleAnteAnimales());?>;  
    var idDetallePostAnimales = <?php echo json_encode($this->modeloDetallePostAnimales->getIdDetallePostAnimales());?>;    
    var hallazgos = <?php echo json_encode($this->arrayHallazgos);?>;   
    var organos = <?php echo json_encode($this->arrayResultadoOrgano);?>;   
    var decomisoParcial = <?php echo json_encode($this->arrayResultadoDecomisoParcial);?>;   
    var decomisoTotal = <?php echo json_encode($this->arrayResultadoDecomisoTotal);?>;    
    if(idDetallePostAnimales != '' && idDetallePostAnimales != null){
    	var fechaActual = <?php echo json_encode($this->modeloDetallePostAnimales->getFechaFormulario());?>;
    	var estadoRegistro = <?php echo json_encode($this->modeloFormularioPostMortem->getEstado());?>;
	 }else{
		var fechaActual = <?php echo json_encode(date("Y-m-d"));?>;
		var estadoRegistro = <?php echo json_encode($this->modeloFormularioAnteMortem->getEstado());?>;
	 }
	$(document).ready(function() {
		construirAnimacion($(".pestania"));	
		establecerFechas('fecha_formulario',fechaActual);
		setearVariablesIniciales();
		mostrarMensaje("", "FALLO");
		$("#avesMuertas").hide();
	    $("#caracteristicas").hide();
	    $("#problemasSistemicos").hide();
	    $("#caracteristicasExternas").hide();
	    $("#enviarRevision").hide();

	    
	    if(estadoRegistro == 'Aprobado_PM'){
	    	$("#agregarCanalesTotal").hide();
	    	$("#agregarCanalesParcial").hide();
        	$("#agregarOrganos").hide();
        	$("#agregarEctoparasitos").hide();
        	$("#agregarEndoparasitos").hide();
        	$("#agregarHallazgos").hide();
        	ocultarCampos();
        	
        	$("#agregarFormulario").hide();
        	$("#enviarRevision").hide();
        	$("#aprobar").hide();
        	//bloquearCampos();
           }else{
        	$("#generar").hide();
           }
		$("#areaTrabajo #listadoItems").append('<div id="estado"></div>');

		 if(idDetallePostAnimales != '' && idDetallePostAnimales != null){
				$("#agregarFormulario").html('Actualizar registro');
			 }else{
				$("#agregarFormulario").html('Guardar registro');
			 }
	    construirValidador();
		distribuirLineas();
		if(!organoTejido){
			$("#descripcion_actividad").hide();
		}
	 });

	//verificar campo organo
    $("#organoTejido").change(function () {
    	if( $('#organoTejido').prop('checked') ) {
    		$("#descripcion_actividad").show();
    	}else{
    		$("#descripcion_actividad").hide();
    		$("#descripcion_actividad").val('');
    	}
    	
    });
    //verificar que campo esta vacio
    $( "#num_total_animales" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearVariablesRegistro();
    	}
    });
//*****************************************************************************************************
 //validar el ingreso de información en animales afectados en hallazgos
    $("#num_animales_afectados").change(function () {
    	validarIngresoInfoPost("num_animales_afectados","num_total_animales");
    });

 //validar el ingreso de información en animales afectados en hallazgos
    $("#endoparasitos_num_afectados").change(function () {
    	validarIngresoInfoPost("endoparasitos_num_afectados","num_total_animales");
    });
 //validar el ingreso de información en animales afectados en hallazgos
    $("#ectoparasitos_num_afectados").change(function () {
    	validarIngresoInfoPost("ectoparasitos_num_afectados","num_total_animales");
    });

//*****************************************************************************************************
	$("#agregarFormulario").click(function () {
        $(".alertaCombo").removeClass("alertaCombo");
      	var error = false;
      	error = verificarCamposObligatorios();

        if(!error){
        		$.post("<?php echo URL ?>InspeccionAntePostMortemCF/FormularioPostMortem/agregarFormularioPostAnimales", 
                        {
        			        //*****cabecera*******
        			        id_formulario_ante_mortem: $("#id_formulario_ante_mortem").val(),
        			        id_formulario_post_mortem: $("#id_formulario_post_mortem").val(),
        			        id_detalle_ante_animales : idDetalleAnteAnimales,
        			        id_detalle_post_animales: idDetallePostAnimales,
        			        idCentroFaenamiento: idCentroFaenamiento,
							//*****generalidades*****
							fecha_formulario: $("#fecha_formulario").val(),
							num_canales_decomiso_parcial: $("#num_canales_decomiso_parcial").val(),
							estado_nodulos_linfaticos: $("#estado_nodulos_linfaticos").val(),
							otro_diagnostico: $("#otro_diagnostico").val(),
							peso_total_carne_aprobada: $("#peso_total_carne_aprobada").val(),
							peso_total_carne_decomisada: $("#peso_total_carne_decomisada").val(),
							num_canales_decomiso: $("#num_canales_decomiso").val(),
							peso_total_carne_decomisada_productivo: $("#peso_total_carne_decomisada_productivo").val(),
							num_canales_aprobadas_totalmente: $("#num_canales_aprobadas_totalmente").val(),
							num_canales_aprobadas_parcialmente: $("#num_canales_aprobadas_parcialmente").val(),
							peso_total_carne_aprobada_productivos: $("#peso_total_carne_aprobada_productivos").val(),
							peso_promedio_canal: $("#peso_promedio_canal").val(),
							peso_total_visceras_decomisadas: $("#peso_total_visceras_decomisadas").val(),
							peso_carne_incineracion: $("#peso_carne_incineracion").val(),
							peso_visceras_incineracion: $("#peso_visceras_incineracion").val(),
							peso_carne_rendering: $("#peso_carne_rendering").val(),
							peso_visceras_rendering: $("#peso_visceras_rendering").val(),
							peso_carne_abono: $("#peso_carne_abono").val(),
							peso_visceras_abono: $("#peso_visceras_abono").val(),
							peso_carne_ambiental: $("#peso_carne_ambiental").val(),
							peso_visceras_ambiental: $("#peso_visceras_ambiental").val(),
							lugar_incineracion: $("#lugar_incineracion").val(),
							lugar_renderizacion: $("#lugar_renderizacion").val(),
							lugar_desconposicion: $("#lugar_desconposicion").val(),
							nombre_gestor_ambiental: $("#nombre_gestor_ambiental").val(),
							descripcion_actividad_general: $("#descripcion_actividad_general").val(),
							//****************enfermedad
							num_animales_afectados: $("#num_animales_afectados").val(),
							//****************endoparasitos
							endoparasitos_num_afectados: $("#endoparasitos_num_afectados").val(),
							//****************ectoparasitos
							ectoparasitos_num_afectados: $("#ectoparasitos_num_afectados").val(),
							//****************organos decomiso
							num_organos_decomisados: $("#num_organos_decomisados").val(),
							//****************decomiso parcial
							num_canales_decomisadas_parcial: $("#num_canales_decomisadas_parcial").val(),
							peso_carne_aprobada_parcial: $("#peso_carne_aprobada_parcial").val(),
							peso_carne_decomisada_parcial: $("#peso_carne_decomisada_parcial").val(),
							//****************decomiso total
							num_canales_decomisadas_total: $("#num_canales_decomisadas_total").val(),
							peso_carne_aprobada_total: $("#peso_carne_aprobada_total").val(),
							peso_carne_decomisada_total: $("#peso_carne_decomisada_total").val(),
        		            //********observacion*****
        		            observacion: $("#observacion").val(),
        		            examenVisual: $("#examenVisual").prop('checked'),
        		            palpacion: $("#palpacion").prop('checked'),
        		            insicion: $("#insicion").prop('checked'),
        		            tomaMuestra: $("#tomaMuestra").prop('checked'),
        		            organoTejido: $("#organoTejido").prop('checked'),
        		            descripcion_actividad: $("#descripcion_actividad").val(),
        		            hallazgos: hallazgos,
        		            organos:organos,
        					decomisoParcial:decomisoParcial,
        					decomisoTotal:decomisoTotal
                        	
     					},
     					function (data) {
     						  if(data.estado === 'EXITO'){
     							 $("#id_formulario_post_mortem").val(data.id);
     							 var idFormularioDetalleNew = actualizarIdTr(idFormularioDetalle, data.idDetalle); 
     							 mostrarMensaje(data.mensaje, "EXITO");
     							 $('#tablaItems #'+idFormularioDetalle+' td:eq(1)').html('<b>Registrado</b>');
     							 $('#tablaItems #'+idFormularioDetalle).attr("id",idFormularioDetalleNew);
     							 $("#detalleItem").html("<div id='cargando'>Cargando...</div>").wait(170).html('<div class="mensajeInicial">Arrastre aqui una operación para revisarla.</div>');
     						  }else{
     							  mostrarMensaje(data.mensaje, "FALLO");
     						  }
     		        	}, 'json');  
        	           
  		}else{
  			mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
  		}
	});
//*****************************************************************************************************
	function actualizarIdTr(idFormularioDetalle, idDetalle){
		var res = idFormularioDetalle.split("-");
		res[4] = idDetalle;
		var unificar = res[0]+"-"+res[1]+"-"+res[2]+"-"+res[3]+"-"+res[4];
		return unificar;
	 }
    //************setear los campos**********
	function setearVariablesIniciales(){
		//*****estado general*****
		$("#num_canales_decomiso_parcial").numeric();
		$("#peso_total_carne_aprobada").numeric();
		$("#peso_total_carne_decomisada").numeric();
		$("#num_canales_decomiso").numeric();
		$("#peso_total_carne_decomisada_productivo").numeric();
		$("#num_canales_aprobadas_totalmente").numeric();
		$("#num_canales_aprobadas_parcialmente").numeric();
		$("#peso_total_carne_aprobada_productivos").numeric();
		$("#peso_promedio_canal").numeric();
		$("#peso_total_visceras_decomisadas").numeric();
		$("#peso_carne_incineracion").numeric();
		$("#peso_visceras_incineracion").numeric();
		$("#peso_carne_rendering").numeric();
		$("#peso_visceras_rendering").numeric();
		$("#peso_carne_abono").numeric();
		$("#peso_visceras_abono").numeric();
		$("#peso_carne_ambiental").numeric();
		$("#peso_visceras_ambiental").numeric();
		//****************enfermedad
		$("#num_animales_afectados").numeric();
		//****************endoparasitos
		$("#endoparasitos_num_afectados").numeric();
		//****************ectoparasitos
		$("#ectoparasitos_num_afectados").numeric();
		//****************organos decomiso
		$("#num_organos_decomisados").numeric();
		//****************decomiso parcial
		$("#num_canales_decomisadas_parcial").numeric();
		$("#peso_carne_aprobada_parcial").numeric();
		$("#peso_carne_decomisada_parcial").numeric();
		
		//****************decomiso total
		$("#num_canales_decomisadas_total").numeric();
		$("#peso_carne_aprobada_total").numeric();
		$("#peso_carne_decomisada_total").numeric();
	}
	 
	 //************setear los campos cuando se guarde un registro**********
	function setearVariablesRegistro(){
		 $("#fecha_formulario").val(fechaActual);
			//*****estado general*****
				$("#num_descarte").val('');
				$("#porcent_num_descarte").val('');
				//*****manejo faenamiento*****
				$("#num_colibacilosis").val('');
				$("#porcent_num_colibacilosis").val('');
				$("#num_pododermatitis").val('');
				$("#porcent_num_pododermatitis").val('');
				$("#num_lesiones_piel").val('');
				$("#porcent_num_lesiones_piel").val('');
				$("#num_mal_sangrado").val('');
				$("#porcent_num_mal_sangrado").val('');
				$("#num_contusion_pierna").val('');
				$("#porcent_num_contusion_pierna").val('');
				$("#num_contusion_ala").val('');
				$("#porcent_num_contusion_ala").val('');
				$("#num_contusion_pechuga").val('');
				$("#porcent_num_contusion_pechuga").val('');
				$("#num_alas_rotas").val('');
				$("#porcent_num_alas_rotas").val('');
				$("#num_piernas_rotas").val('');
				$("#porcent_num_piernas_rotas").val('');
				$("#total_canales_aprobados").val('');
				$("#peso_total_canales_aprobados_totalmente").val('');
				$("#total_canales_aprobados_parcialmente").val('');
				$("#peso_total_canales_aprobados_parcialmente").val('');
				$("#canales_decomiso_parcial").val('');
				$("#canales_decomiso_total").val('');
				$("#peso_promedio_canales").val('');
				$("#total_carne_decomisada").val('');
				$("#destino_decomisos").val('');
				$("#lugar_disposicion_final").val('');
				$("#observacion").val('');
		
	}
//***********************************************************************************
 
//************verificar campos obligatorios******************************************
	function verificarCamposObligatorios(){
		error = false;
			//*****generalidades*****
			  if(!$.trim($("#fecha_formulario").val())){
	  			   $("#fecha_formulario").addClass("alertaCombo");
	  			   error = true;
	  		  }
	          if (!$.trim($("#estado_nodulos_linfaticos").val())) {
	  			   $("#estado_nodulos_linfaticos").addClass("alertaCombo");
	  			   error = true;
	          }
	          if(!$.trim($("#otro_diagnostico").val())){
		  			$("#otro_diagnostico").addClass("alertaCombo");
		  			error =  true;
		  		  }
		      if (!$.trim($("#num_canales_decomiso_parcial").val())) {
		  			$("#num_canales_decomiso_parcial").addClass("alertaCombo");
		  			error =  true;
		      }
		      if(!$.trim($("#peso_total_carne_aprobada").val())){
		  			$("#peso_total_carne_aprobada").addClass("alertaCombo");
		  			error =  true;
		  		  }
		    
		      if (!$.trim($("#peso_total_carne_decomisada").val())) {
		  			$("#peso_total_carne_decomisada").addClass("alertaCombo");
		  			error =  true;
		      }

			  if (!$.trim($("#num_canales_decomiso").val())) {
		  			$("#num_canales_decomiso").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#peso_total_carne_decomisada_productivo").val())) {
		  			$("#peso_total_carne_decomisada_productivo").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#num_canales_aprobadas_totalmente").val())) {
		  			$("#num_canales_aprobadas_totalmente").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#num_canales_aprobadas_parcialmente").val())) {
		  			$("#num_canales_aprobadas_parcialmente").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#peso_total_carne_aprobada_productivos").val())) {
		  			$("#peso_total_carne_aprobada_productivos").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#peso_promedio_canal").val())) {
		  			$("#peso_promedio_canal").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#peso_total_visceras_decomisadas").val())) {
		  			$("#peso_total_visceras_decomisadas").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#peso_carne_incineracion").val())) {
		  			$("#peso_carne_incineracion").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#peso_visceras_incineracion").val())) {
		  			$("#peso_visceras_incineracion").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#peso_carne_rendering").val())) {
		  			$("#peso_carne_rendering").addClass("alertaCombo");
		  			error =  true;
		      }

		      if (!$.trim($("#peso_visceras_rendering").val())) {
		  			$("#peso_visceras_rendering").addClass("alertaCombo");
		  			error =  true;
		      } 
		      if (!$.trim($("#peso_carne_abono").val())) {
		  			$("#peso_carne_abono").addClass("alertaCombo");
		  			error =  true;
		      } 
		      if (!$.trim($("#peso_visceras_abono").val())) {
		  			$("#peso_visceras_abono").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#peso_carne_ambiental").val())) {
		  			$("#peso_carne_ambiental").addClass("alertaCombo");
		  			error =  true;
		      } 
		      if (!$.trim($("#peso_visceras_ambiental").val())) {
		  			$("#peso_visceras_ambiental").addClass("alertaCombo");
		  			error =  true;
		      }
		       if (!$.trim($("#lugar_incineracion").val())) {
		  			$("#lugar_incineracion").addClass("alertaCombo");
		  			error =  true;
		      } 
			   if (!$.trim($("#lugar_renderizacion").val())) {
		  			$("#lugar_renderizacion").addClass("alertaCombo");
		  			error =  true;
		      } 
			   if (!$.trim($("#lugar_desconposicion").val())) {
		  			$("#lugar_desconposicion").addClass("alertaCombo");
		  			error =  true;
		      }
			   if (!$.trim($("#nombre_gestor_ambiental").val())) {
		  			$("#nombre_gestor_ambiental").addClass("alertaCombo");
		  			error =  true;
		      } 
		      if (!$.trim($("#descripcion_actividad_general").val())) {
		  			$("#descripcion_actividad_general").addClass("alertaCombo");
		  			error =  true;
		      }
		      if( !$('#organoTejido').prop('checked') && !$('#examenVisual').prop('checked') && !$('#palpacion').prop('checked') && !$('#insicion').prop('checked') && !$('#tomaMuestra').prop('checked')) {
		    		$("#organoTejidoTxt").addClass("alertaCombo");
		    		$("#examenVisualTxt").addClass("alertaCombo");
		    		$("#insicionTxt").addClass("alertaCombo");
		    		$("#tomaMuestraTxt").addClass("alertaCombo");
		    		$("#palpacionTxt").addClass("alertaCombo");
		    		error =  true;
		    	}
		      if( $('#organoTejido').prop('checked') ) {
		    	  if (!$.trim($("#descripcion_actividad").val())) {
			  			$("#descripcion_actividad").addClass("alertaCombo");
			  			error =  true;
			      }
		    	}
		    //*****observacion***
		    if (!$.trim($("#observacion").val())) {
		  			$("#observacion").addClass("alertaCombo");
		  			error =  true;
		      }
		
		return error;
	    }

	//************previsualizar detalle formulario***********************
    function btnPrevisualizar(id){
    	$.post("<?php echo URL ?>InspeccionAntePostMortemCF/FormularioAnteMortem/detalleFormularioAvesPrevisualizar",{
    		    id_detalle_ante_aves : id,
    		    estadoRegistro : estadoRegistro
	      		},
	      		function (data) {
                    $('#modalDetalle').modal('show');
                    $("#divDetalle").html(data);
                });
    	
     }
    
    //*********enviar a revision el formulario
    $("#enviarRevision").click(function() { 
    	if($("#id_formulario_post_mortem").val() != '' && $("#id_formulario_post_mortem").val() != null){
    	$.post("<?php echo URL ?>InspeccionAntePostMortemCF/FormularioPostMortem/enviarRevisionAves", 
				{
    		        id_formulario_post_mortem: $("#id_formulario_post_mortem").val(),
    		        estado: 'Por revisar'
				},
				function (data) {
					 if(data.estado == 'EXITO'){
						       if(idFormularioDetalle != '' && idFormularioDetalle != null){
							    	$('#tablaItems #'+idFormularioDetalle+' td:eq(1)').html('<b>Por revisar</b>'); 
							    	$("#detalleItem").html("<div id='cargando'>Cargando...</div>").wait(170).html('<div class="mensajeInicial">Arrastre aqui una operación para revisarla.</div>');
							    }else{ 
							    	abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#detalleItem",false);
								}
							    mostrarMensaje(data.mensaje, "EXITO")
							    $("#estado").html(data.mensaje).wait(170).html('');
						  }else{
							    mostrarMensaje(data.mensaje, "FALLO");
						  }
	        	}, 'json'); 
    	}else{
    		mostrarMensaje("Debe guardar primero el formulario...!!", "FALLO");
        	}
	});

    //*********Aprobar el formulario
    $("#aprobar").click(function() {
    	if($("#id_formulario_post_mortem").val() != '' && $("#id_formulario_post_mortem").val() != null){
    	$.post("<?php echo URL ?>InspeccionAntePostMortemCF/FormularioPostMortem/aprobarFormularioAves", 
				{
    		        id_formulario_post_mortem: $("#id_formulario_post_mortem").val(),
    		        estado: 'Aprobado_PM'
				},
				function (data) {
					 if(data.estado == 'EXITO'){
						        if(idFormularioDetalle != '' && idFormularioDetalle != null){
							    	$('#tablaItems #'+idFormularioDetalle+' td:eq(1)').html('<b>Aprobado_PM</b>'); 
							    	$("#detalleItem").html("<div id='cargando'>Cargando...</div>").wait(170).html('<div class="mensajeInicial">Arrastre aqui una operación para revisarla.</div>');
							    }else{ 
							    	abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#detalleItem",false);
								}
							    mostrarMensaje(data.mensaje, "EXITO")
							    $("#estado").html(data.mensaje).wait(170).html('');
						  }else{
							 mostrarMensaje(data.mensaje, "FALLO");
						  }
	        	}, 'json'); 
    	}else{
    		mostrarMensaje("Debe guardar primero el formulario...!!", "FALLO");
        	}
	});
    //*********Aprobar el formulario
    $("#generar").click(function() {
    	if($("#id_formulario_post_mortem").val() != '' && $("#id_formulario_post_mortem").val() != null){
    	$.post("<?php echo URL ?>InspeccionAntePostMortemCF/FormularioPostMortem/generarFormularioAnimales", 
				{
    		        id_formulario_ante_mortem: $("#id_formulario_ante_mortem").val(),
    		        id_formulario_post_mortem: $("#id_formulario_post_mortem").val(),
    		        estado: 'Aprobado_AM',
    		        idFormularioDetalle: idFormularioDetalle
				},
				function (data) {
					 if(data.estado == 'EXITO'){
							 mostrarMensaje(data.mensaje, "EXITO");
							 $("#formularioCreado").attr("src", data.ruta);
						  }else{
							 mostrarMensaje(data.mensaje, "FALLO");
						  }
	        	}, 'json'); 
    	}else{
    		mostrarMensaje("Debe guardar primero el formulario...!!", "FALLO");
        	}
	});

  //*********Agregar hallazgos del examen de post - mortem
    $("#agregarHallazgos").click(function() {
    	  error = false;
    	  $("#enfermedad").removeClass("alertaCombo");
  		  $("#localizacion").removeClass("alertaCombo");
  		  $("#num_animales_afectados").removeClass("alertaCombo");
        var datos='';
      	if(!$.trim($("#enfermedad").val())){
			error = true;
			$("#enfermedad").addClass("alertaCombo");
		}
      	if(!$.trim($("#localizacion").val())){
			error = true;
			$("#localizacion").addClass("alertaCombo");
		}
      	if(!$.trim($("#num_animales_afectados").val()) || $("#num_animales_afectados").val() == 0 ){
			error = true;
			$("#num_animales_afectados").addClass("alertaCombo");
		}
    	if(!error){
			datos = {"enfermedad":$("#enfermedad option:selected").text(),"localizacion":$("#localizacion  option:selected").text(),"numAnimalAfec":$("#num_animales_afectados").val(),"tipo":"hallazgos","presencia":""};
      	    hallazgos.push(datos);
    		
    		$.post("<?php echo URL ?>InspeccionAntePostMortemCF/FormularioPostMortem/agregarHallazgos",
					{
    			      hallazgos: hallazgos,
    			      totalAnimales: $("#num_total_animales").val()
					},
					function (data) {
						  if(data.estado == 'EXITO'){
							 $("#bodyTbl").html(data.contenido);
							 $("#listDetalleHallazgos").val(JSON.stringify(hallazgos));
							 mostrarMensaje(data.mensaje, "EXITO");
							 $("#enfermedad").focus();
							 $("#enfermedad").val('');
					  		 $("#localizacion").val('');
					  		 $("#num_animales_afectados").val('');
						  }else{
							  hallazgos.pop();
							  $("#listDetalleHallazgos").val(JSON.stringify(hallazgos));
							  mostrarMensaje(data.mensaje, "FALLO");
						  }
					}, 'json');
    	}else{
    		mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
        	}
	});
  //*********Agregar endoparásitos del examen de post - mortem
    $("#agregarEndoparasitos").click(function() {
    	 error = false;
   	      $("#endoparasitos_presencia").removeClass("alertaCombo");
 		  $("#endoparasitos_localizacion").removeClass("alertaCombo");
 		  $("#endoparasitos_num_afectados").removeClass("alertaCombo");
        var datos='';
     	if(!$.trim($("#endoparasitos_presencia").val())){
			error = true;
			$("#endoparasitos_presencia").addClass("alertaCombo");
		}
     	if(!$.trim($("#endoparasitos_localizacion").val())){
			error = true;
			$("#endoparasitos_localizacion").addClass("alertaCombo");
		}
     	if(!$.trim($("#endoparasitos_num_afectados").val()) || $("#endoparasitos_num_afectados").val() == 0 ){
			error = true;
			$("#endoparasitos_num_afectados").addClass("alertaCombo");
		}
   	if(!error){
			datos = {"enfermedad":"Endoparásitos","localizacion":$("#endoparasitos_localizacion").val(),"numAnimalAfec":$("#endoparasitos_num_afectados").val(),"tipo":"endoparasitos","presencia": $("#endoparasitos_presencia").val()};
			hallazgos.push(datos);
   		
   		$.post("<?php echo URL ?>InspeccionAntePostMortemCF/FormularioPostMortem/agregarHallazgos",
					{
   			      hallazgos: hallazgos,
   			      totalAnimales: $("#num_total_animales").val()
					},
					function (data) {
						  if(data.estado == 'EXITO'){
							 $("#bodyTbl").html(data.contenido);
							 $("#listDetalleHallazgos").val(JSON.stringify(hallazgos));
							 mostrarMensaje(data.mensaje, "EXITO");
							 $("#endoparasitos_presencia").val('');
					 		 $("#endoparasitos_localizacion").val('');
					 		 $("#endoparasitos_num_afectados").val('');
						  }else{
							  hallazgos.pop();
							  $("#listDetalleHallazgos").val(JSON.stringify(hallazgos));
							  mostrarMensaje(data.mensaje, "FALLO");
						  }
					}, 'json');
   	}else{
   		mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
       	}
	});
  //*********Agregar ectoparásitos del examen de post - mortem
    $("#agregarEctoparasitos").click(function() {
    	 error = false;
  	      $("#ectoparasitos_presencia").removeClass("alertaCombo");
		  $("#ectoparasitos_localizacion").removeClass("alertaCombo");
		  $("#ectoparasitos_num_afectados").removeClass("alertaCombo");
       var datos='';
    	if(!$.trim($("#ectoparasitos_presencia").val())){
			error = true;
			$("#ectoparasitos_presencia").addClass("alertaCombo");
		}
    	if(!$.trim($("#ectoparasitos_localizacion").val())){
			error = true;
			$("#ectoparasitos_localizacion").addClass("alertaCombo");
		}
    	if(!$.trim($("#ectoparasitos_num_afectados").val()) || $("#ectoparasitos_num_afectados").val() == 0 ){
			error = true;
			$("#ectoparasitos_num_afectados").addClass("alertaCombo");
		}
  	if(!error){
			datos = {"enfermedad":"Ectoparásitos","localizacion":$("#ectoparasitos_localizacion").val(),"numAnimalAfec":$("#ectoparasitos_num_afectados").val(),"tipo":"ectoparasitos", "presencia": $("#ectoparasitos_presencia").val()};
			hallazgos.push(datos);
  		$.post("<?php echo URL ?>InspeccionAntePostMortemCF/FormularioPostMortem/agregarHallazgos",
					{
  			      hallazgos: hallazgos,
  			      totalAnimales: $("#num_total_animales").val()
					},
					function (data) {
						  if(data.estado == 'EXITO'){
							 $("#bodyTbl").html(data.contenido);
							 $("#listDetalleHallazgos").val(JSON.stringify(hallazgos));
							 mostrarMensaje(data.mensaje, "EXITO");
							 $("#ectoparasitos_presencia").val('');
							 $("#ectoparasitos_localizacion").val('');
							 $("#ectoparasitos_num_afectados").val('');
						  }else{
							  hallazgos.pop();
							  $("#listDetalleHallazgos").val(JSON.stringify(hallazgos));
							  mostrarMensaje(data.mensaje, "FALLO");
						  }
					}, 'json');
  	}else{
  		mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
      	}
	});
  //*********Agregar organos en resultados de post - mortem
  
    $("#agregarOrganos").click(function() {
    	 error = false;
 	      $("#organo_decomisado ").removeClass("alertaCombo");
		  $("#razon_decomiso").removeClass("alertaCombo");
		  $("#num_organos_decomisados").removeClass("alertaCombo");
      var datos='';
   	if(!$.trim($("#organo_decomisado").val())){
			error = true;
			$("#organo_decomisado").addClass("alertaCombo");
		}
   	if(!$.trim($("#razon_decomiso").val())){
			error = true;
			$("#razon_decomiso").addClass("alertaCombo");
		}
   	if(!$.trim($("#num_organos_decomisados").val()) || $("#num_organos_decomisados").val() == 0 ){
			error = true;
			$("#num_organos_decomisados").addClass("alertaCombo");
		}
 	if(!error){
			datos = {"numOrganoDecomiso":$("#num_organos_decomisados").val(),"organo":$("#organo_decomisado option:selected").text(),"razonDecomiso":$("#razon_decomiso option:selected").text()};
			organos.push(datos);
 		$.post("<?php echo URL ?>InspeccionAntePostMortemCF/FormularioPostMortem/agregarOrganos",
					{
 			      organos: organos,
 			      totalAnimales: $("#num_total_animales").val()
					},
					function (data) {
						  if(data.estado == 'EXITO'){
							 $("#bodyTblOrgano").html(data.contenido);
							 $("#listDetalleOrganos").val(JSON.stringify(organos));
							 mostrarMensaje(data.mensaje, "EXITO");
							 $("#organo_decomisado ").val('');
							 $("#razon_decomiso").val('');
							 $("#num_organos_decomisados").val('');
						  }else{
							  hallazgos.pop();
							  $("#listDetalleOrganos").val(JSON.stringify(organos));
							  mostrarMensaje(data.mensaje, "FALLO");
						  }
					}, 'json');
 	}else{
 		mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
     	}
	});
  //*********Agregar canales parcial en resultados de post - mortem
    $("#agregarCanalesParcial").click(function() {
    	 error = false;
 	      $("#razon_decomiso_parcial").removeClass("alertaCombo");
		  $("#num_canales_decomisadas_parcial").removeClass("alertaCombo");
		  $("#peso_carne_aprobada_parcial").removeClass("alertaCombo");
		  $("#peso_carne_decomisada_parcial").removeClass("alertaCombo");
		    
      var datos='';
   	if(!$.trim($("#razon_decomiso_parcial").val())){
			error = true;
			$("#razon_decomiso_parcial").addClass("alertaCombo");
		}
   	if(!$.trim($("#num_canales_decomisadas_parcial").val()) || $("#num_canales_decomisadas_parcial").val() == 0 ){
		error = true;
		$("#num_canales_decomisadas_parcial").addClass("alertaCombo");
	}
   	if(!$.trim($("#peso_carne_aprobada_parcial").val()) || $("#peso_carne_aprobada_parcial").val() == 0 ){
		error = true;
		$("#peso_carne_aprobada_parcial").addClass("alertaCombo");
	}
   	if(!$.trim($("#peso_carne_decomisada_parcial").val()) || $("#peso_carne_decomisada_parcial").val() == 0 ){
		error = true;
		$("#peso_carne_decomisada_parcial").addClass("alertaCombo");
	}
	if(!error){
			datos = {"razonDecomiso":$("#razon_decomiso_parcial option:selected").text(),"numCanalesDecomisadas":$("#num_canales_decomisadas_parcial").val(),"pesoCarneAprobada":$("#peso_carne_aprobada_parcial").val(),"pesoCarneDecomisada":$("#peso_carne_decomisada_parcial").val()};
			decomisoParcial.push(datos);
 		$.post("<?php echo URL ?>InspeccionAntePostMortemCF/FormularioPostMortem/agregarCanalesParcial",
					{
 			      decomisoParcial: decomisoParcial,
 			      totalAnimales: $("#num_total_animales").val()
					},
					function (data) {
						  if(data.estado == 'EXITO'){
							 $("#bodyTblParcial").html(data.contenido);
							 $("#listDetalleParcial").val(JSON.stringify(decomisoParcial));
							 mostrarMensaje(data.mensaje, "EXITO");
							  $("#razon_decomiso_parcial").val('');
							  $("#num_canales_decomisadas_parcial").val('');
							  $("#peso_carne_aprobada_parcial").val('');
							  $("#peso_carne_decomisada_parcial").val('');
						  }else{
							  decomisoParcial.pop();
							  $("#listDetalleParcial").val(JSON.stringify(decomisoParcial));
							  mostrarMensaje(data.mensaje, "FALLO");
						  }
					}, 'json');
 	}else{
 		mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
     	}
	});
  //*********Agregar canales total en resultados de post - mortem
    $("#agregarCanalesTotal").click(function() {
    	 error = false;
 	      $("#razon_decomiso_total").removeClass("alertaCombo");
		  $("#num_canales_decomisadas_total").removeClass("alertaCombo");
		  $("#peso_carne_decomisada_total").removeClass("alertaCombo");
      var datos='';
   	if(!$.trim($("#razon_decomiso_total").val())){
			error = true;
			$("#razon_decomiso_total").addClass("alertaCombo");
		}
	if(!$.trim($("#num_canales_decomisadas_total").val()) || $("#num_canales_decomisadas_total").val() == 0 ){
		error = true;
		$("#num_canales_decomisadas_total").addClass("alertaCombo");
	}
	if(!$.trim($("#peso_carne_decomisada_total").val()) || $("#peso_carne_decomisada_total").val() == 0 ){
		error = true;
		$("#peso_carne_decomisada_total").addClass("alertaCombo");
	}
	if(!error){
			datos = {"razonDecomiso":$("#razon_decomiso_total option:selected").text(),"numCanalesDecomisadas":$("#num_canales_decomisadas_total").val(),"pesoCarneDecomisada":$("#peso_carne_decomisada_total").val()};
			decomisoTotal.push(datos);
 		$.post("<?php echo URL ?>InspeccionAntePostMortemCF/FormularioPostMortem/agregarCanalesTotal",
					{
 				  decomisoTotal: decomisoTotal,
 			      totalAnimales: $("#num_total_animales").val()
					},
					function (data) {
						  if(data.estado == 'EXITO'){
							 $("#bodyTblTotal").html(data.contenido);
							// $("#listDetalleTotal").val(JSON.stringify(decomisoTotal));
							 mostrarMensaje(data.mensaje, "EXITO");
							  $("#razon_decomiso_total").val('');
							  $("#num_canales_decomisadas_total").val('');
							  $("#peso_carne_decomisada_total").val('');
						  }else{
							  decomisoTotal.pop();
							 // $("#listDetalleTotal").val(JSON.stringify(decomisoTotal));
							  mostrarMensaje(data.mensaje, "FALLO");
						  }
					}, 'json');
 	}else{
 		mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
     	}
	});

    //************eliminar elementos*************************
    function eliminarTr(i,opt){
    	switch (opt) { 
    	case 1: 
    		var r = i.parentNode.parentNode.rowIndex;
            var id = r-1;
    	    $("#detalleHallazgos tbody").find("#"+id).remove();
    	    hallazgos.splice(id,1);
        	for (var i = id, j=id+1; i < hallazgos.length; i++,j++) {
    			 $("#detalleHallazgos tbody").find("#"+j).attr("id",i);
            }
    		break;
    	}
    	
    }
    //**************
    function ocultarCampos(){
		$("#enfermedad").attr('disabled','disabled');
		$("#localizacion").attr('disabled','disabled');
		$("#num_animales_afectados").attr('disabled','disabled');
		$("#endoparasitos_presencia").attr('disabled','disabled');
		$("#endoparasitos_localizacion").attr('disabled','disabled');
		$("#endoparasitos_num_afectados").attr('disabled','disabled');
		$("#ectoparasitos_presencia").attr('disabled','disabled');
		$("#ectoparasitos_localizacion").attr('disabled','disabled');
		$("#ectoparasitos_num_afectados").attr('disabled','disabled');

		$("#organo_decomisado").attr('disabled','disabled');
		$("#razon_decomiso").attr('disabled','disabled');
		$("#num_organos_decomisados").attr('disabled','disabled');
		$("#razon_decomiso_parcial").attr('disabled','disabled');
		$("#num_canales_decomisadas_parcial").attr('disabled','disabled');
		$("#peso_carne_aprobada_parcial").attr('disabled','disabled');
		$("#peso_carne_decomisada_parcial").attr('disabled','disabled');
		$("#agregarCanalesParcial").attr('disabled','disabled');
		$("#razon_decomiso_total").attr('disabled','disabled');
		$("#num_canales_decomisadas_total").attr('disabled','disabled');
		$("#peso_carne_decomisada_total").attr('disabled','disabled');
		$("#num_canales_decomiso_parcial").attr('disabled','disabled');
		$("#peso_total_carne_aprobada").attr('disabled','disabled');
		$("#peso_total_carne_decomisada").attr('disabled','disabled');
		$("#num_canales_decomiso").attr('disabled','disabled');
		$("#peso_total_carne_decomisada_productivo").attr('disabled','disabled');
		$("#num_canales_aprobadas_totalmente").attr('disabled','disabled');
		$("#num_canales_aprobadas_parcialmente").attr('disabled','disabled');
		$("#peso_total_carne_aprobada_productivos").attr('disabled','disabled');
		$("#peso_promedio_canal").attr('disabled','disabled');
		$("#peso_total_visceras_decomisadas").attr('disabled','disabled');
		$("#peso_carne_incineracion").attr('disabled','disabled');
		$("#peso_visceras_incineracion").attr('disabled','disabled');
		$("#peso_carne_rendering").attr('disabled','disabled');
		$("#peso_visceras_rendering").attr('disabled','disabled');
		$("#peso_carne_abono").attr('disabled','disabled');
		$("#peso_visceras_abono").attr('disabled','disabled');
		$("#peso_carne_ambiental").attr('disabled','disabled');
		$("#peso_visceras_ambiental").attr('disabled','disabled');
		$("#lugar_incineracion").attr('disabled','disabled');
		$("#lugar_renderizacion").attr('disabled','disabled');
		$("#lugar_desconposicion").attr('disabled','disabled');
		$("#nombre_gestor_ambiental").attr('disabled','disabled');
		$("#examenVisual").attr('disabled','disabled');
		$("#palpacion").attr('disabled','disabled');
		$("#insicion").attr('disabled','disabled');
		$("#tomaMuestra").attr('disabled','disabled');
		$("#organoTejido").attr('disabled','disabled');
		$("#descripcion_actividad").attr('disabled','disabled');
		$("#descripcion_actividad_general").attr('disabled','disabled');
		$("#observacion").attr('disabled','disabled');
		$("#estado_nodulos_linfaticos").attr('disabled','disabled');
		$("#otro_diagnostico").attr('disabled','disabled');
		$("#fecha_formulario").attr('disabled','disabled');
		
		

        }
</script>
