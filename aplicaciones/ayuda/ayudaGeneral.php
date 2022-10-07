<?php 
require_once '../../clases/Conexion.php';

$conexion = new Conexion();

$conexion->verificarSesion();
?>

<h2>SISTEMA GUIA</h2>
<p>El Sistema Gestor Unificado de Información para Agrocalidad - GUIA es un SII (Sistema de Información Integrado).</p>

<?php 
	 
	if($_SESSION['nombreLocalizacion'] != ''){
	    
		echo'<h3>LINK DE INTERES</h3>

                <table>
    				<thead>
    					<tr>
    						<th></th>
    						<th>PDF</th>
    					</tr>
    				</thead>

                    <tr>
				        <td>REGLAMENTO INTERNO DE RECURSOS HUMANOS AGROCALIDAD</td>
					   <td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/normativa/Reglamento_Interno_Agrocalidad_mayo_2018_vigente.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>						
				    </tr>

                </table>    


            <h3>TUTORIALES DE MÓDULOS DE SERVICIOS</h3>
                
            <table>
				<thead>
					<tr>
						<th></th>
						<th>TUTORIAL</th>
						<th>YOUTUBE</th>
						<th>PDF</th>
					</tr>
				</thead>
				  
				  <tr>
				    <td>SISTEMA DE TRANSPORTE</td>
				    <td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/transportes/index.htm" target="_blank"><img src="aplicaciones/ayuda/img/video.png" width="31" height="31"  alt=""/></a></td>
				  </tr>
				  
				 
				  <tr>
				    <td>REGISTRO DE OPERADOR</td>
				    <td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/vue/regguia.html" target="_blank"><img src="aplicaciones/ayuda/img/video.png" width="31" height="31"  alt=""/></a></td>
					<td align="center"><a href="https://www.youtube.com/watch?v=L5hg_RqfeNE"target="_blank"><img src="aplicaciones/ayuda/img/youtube.png" width="31" height="31"  alt=""/></a></td>
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/Registro_Sistema_Guia.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>					
				  </tr>
			
		
				 <tr>
				    <td>PROGRAMACIÓN ANUAL PRESUPUESTARIA - PLAN ANUAL DE CONTRATACIÓN</td>
					<!--td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/PAPP/index.html" target="_blank"><img src="aplicaciones/ayuda/img/video.png" width="31" height="31"  alt=""/></a></td-->
					<td align="center"><a></a></td>
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/Manual_PAP-PAC.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>					
				 </tr>
		
		
				<tr>
				    <td>IMPOSICIÓN DE TASAS</td>
					<td align="center"><a></a></td>
					<td align="center"><a></a></td>	
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/Manual_Tasas_optimizado.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>						
				 </tr>
		
				<tr>
				    <td>INGRESO DE RESULTADOS DE REVISIÓN REGISTRO OPERADOR</td>
					<td align="center"><a></a></td>
					<td align="center"><a></a></td>	
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/Resultados_de_revision.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>						
				 </tr>
		
				<tr>
				    <td>INGRESO DE RESULTADOS DE REVISIÓN FORMULARIOS DE COMERCIO EXTERIOR(TÉCNICOS)</td>
					<td align="center"><a></a></td>
					<td align="center"><a></a></td>	
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/RevisionFormulariosComercioExterior.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>						
				 </tr>
		
				<tr>
				    <td>NOTIFICAR CONSUMO DE FACTURAS</td>
					<td align="center"><a></a></td>
					<td align="center"><a></a></td>	
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/NotificarConsumoFactura.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>						
				 </tr>
				 
				 <tr>
				    <td>ESTADO DE OPERACIONES EN INSCRIPCIÓN DE OPERADOR (EXPEDIENTE DIGITAL - GESTIÓN OPERADORES)</td>
					<td align="center"><a></a></td>
					<td align="center"><a></a></td>	
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/Estados_inscripcion_operador.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>						
				 </tr>
		
				<tr>
					 <td>CATASTRO DE PRODUCTOS AGROPECUARIOS</td>
				    <td align="center"><a></a></td>
					<td align="center"><a></a></td>
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/Catastro_Productos_Agropecuarios.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>					
				 </tr>
							
				<tr>
					 <td>VACUNACIÓN ANIMAL</td>
				    <td align="center"><a></a></td>
					<td align="center"><a></a></td>
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/Vacunación_Animal.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>					
				 </tr>
							
							
				<tr>
					 <td>MOVILIZACIÓN DE PRODUCTOS AGROPECUARIOS</td>
				    <td align="center"><a></a></td>
					<td align="center"><a></a></td>
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/Movilizacion_Productos_Agropecuarios.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>					
				 </tr>

					
				 <tr>
				    <td>SISTEMA DE PERMISOS Y VACACIONES</td>
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/vacaciones/index.html" target="_blank"><img src="aplicaciones/ayuda/img/video.png" width="31" height="31"  alt=""/></a></td>
					<td align="center"><a></a></td>
					<td align="center"><a></a></td>					
				 </tr>
		
				<tr>
					<td>INSPECCIONES POST REGISTRO TABLETS</td>
				    <td align="center"><a></a></td>
					<td align="center"><a></a></td>
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/Tablet-PostRegistro.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>					
				 </tr>

				<tr>
					<td>S.S.O INVESTIGACIÓN ACCIDENTES E INCIDENTES</td>
				    <td align="center"><a></a></td>
					<td align="center"><a></a></td>
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/S.S.O_Investigacion_Accidentes_e_Incidentes.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>					
				 </tr>

				<tr>
					<td>SERVICIOS DE CONSULTA DE INFORMACIÓN TÉCNICA</td>
				    <td align="center"><a></a></td>
					<td align="center"><a></a></td>
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/servicioConsultaTecnica.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>					
				 </tr>
		
				<tr>
					<td>SERVICIOS EN LÍNEA</td>
				    <td align="center"><a></a></td>
					<td align="center"><a></a></td>
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/serviciosLinea.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>					
				 </tr>
		
				<tr>
					<td>EVALUACIÓN DE DESEMPEÑO</td>
				    <td align="center"><a></a></td>
					<td align="center"><a></a></td>
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/evaluacionDesempenio.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>					
				 </tr>
				 
				 <tr>
					<td>SERVICIOS DE CONSULTA DE INFORMACIÓN TÉCNICA SANIDAD VEGETAL</td>
				    <td align="center"><a></a></td>
					<td align="center"><a></a></td>
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/servicioInformacionTecnicaSanidadVegetal.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>					
				 </tr>
				 
				 <tr>
					<td>SEGUIMIENTO CUARENTENARÍO SANIDAD ANIMAL</td>
				    <td align="center"><a></a></td>
					<td align="center"><a></a></td>
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/seguimiento_cuarentenario_sa.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>					
				 </tr>
				 
				  <tr>
					<td>VIGENCIA DE DOCUMENTOS</td>
				    <td align="center"><a></a></td>
					<td align="center"><a></a></td>
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/vigencia_documentos.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>					
				 </tr>
				 
				  <tr>
					<td>REGISTRO DE OPERADOR DE LABORATORIO DE LECHES Y VETERINARIOS</td>
				    <td align="center"><a></a></td>
					<td align="center"><a></a></td>
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/registro_operador_laboratorios_leches.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>					
				 </tr>
		
				</table>';
	}else{
		
		echo'<table>
				<thead>
					<tr>
						<th></th>
						<th>WEB</th>
						<th>TUTORIAL</th>
						<th>YOUTUBE</th>
						<th>PDF</th>
					</tr>
				</thead>
							
				 <tr>
					 <td>Registro Operador del sistema GUIA Agrocalidad</td>
				    <td align="center"><a href="https://guia.agrocalidad.gob.ec" target="_blank"><img src="aplicaciones/ayuda/img/web.png" width="31" height="31"  alt=""/></a></td>
				    <td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/vue/regguia.html" target="_blank"><img src="aplicaciones/ayuda/img/video.png" width="31" height="31"  alt=""/></a></td>
					<td align="center"><a href="https://www.youtube.com/watch?v=L5hg_RqfeNE"target="_blank"><img src="aplicaciones/ayuda/img/youtube.png" width="31" height="31"  alt=""/></a></td>
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/Registro_Sistema_Guia.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>					
				  </tr>
				
				<tr>
					 <td>Tipo de operaciones</td>
				    <td align="center"><a></a></td>
				    <td align="center"><a></a></td>
					<td align="center"><a></a></td>
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/Tipo_de_Operaciones.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>					
				 </tr>
							
			  	<tr>
				    <td>Registro de Operador de Comercio Exterior (Vue)</td>
				    <td align="center"><a href="https://portal.aduana.gob.ec/" target="_blank"><img src="aplicaciones/ayuda/img/web.png" width="31" height="31"  alt=""/></a></td>
				    <td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/vue/regvue.html" target="_blank"><img src="aplicaciones/ayuda/img/video.png" width="31" height="31"  alt=""/></a></td>
					<td align="center"><a href="https://www.youtube.com/watch?v=_VVU3fyVY1Q" target="_blank"><img src="aplicaciones/ayuda/img/youtube.png" width="31" height="31"  alt=""/></a></td>
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/Registro_Operador_Vue.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>					
				  </tr>
							
				<tr>
				    <td>Solicitud de Certificado de Libre Venta (Veterinario)</td>
				    <td align="center"><a href="https://portal.aduana.gob.ec/" target="_blank"><img src="aplicaciones/ayuda/img/web.png" width="31" height="31"  alt=""/></a></td>
				    <td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/vue/clvvet.html" target="_blank"><img src="aplicaciones/ayuda/img/video.png" width="31" height="31"  alt=""/></a></td>
					<td align="center"><a href="https://www.youtube.com/watch?v=1Q5ORMVHjrQ" target="_blank"><img src="aplicaciones/ayuda/img/youtube.png" width="31" height="31"  alt=""/></a></td>
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/CLV_Veterinario.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>					
				  </tr>
							
				<tr>
				    <td>Solicitud de Certificado de Libre Venta (Plaguicida)</td>
				    <td align="center"><a href="https://portal.aduana.gob.ec/" target="_blank"><img src="aplicaciones/ayuda/img/web.png" width="31" height="31"  alt=""/></a></td>
				    <td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/vue/clvpla.html" target="_blank"><img src="aplicaciones/ayuda/img/video.png" width="31" height="31"  alt=""/></a></td>
					<td align="center"><a href="https://www.youtube.com/watch?v=f6_V3EyY_EY" target="_blank"><img src="aplicaciones/ayuda/img/youtube.png" width="31" height="31"  alt=""/></a></td>
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/CLV_Plaguicida.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>					
				  </tr>
							
				<tr>
				    <td>Solicitud de Certificado Zoosanitario de Exportación</td>
				    <td align="center"><a href="https://portal.aduana.gob.ec/" target="_blank"><img src="aplicaciones/ayuda/img/web.png" width="31" height="31"  alt=""/></a></td>
				    <td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/vue/zooexpor.html" target="_blank"><img src="aplicaciones/ayuda/img/video.png" width="31" height="31"  alt=""/></a></td>
					<td align="center"><a href="https://www.youtube.com/watch?v=nl72VEHr5kw" target="_blank"><img src="aplicaciones/ayuda/img/youtube.png" width="31" height="31"  alt=""/></a></td>
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/Exportacion_Zoosanitaria.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>					
				  </tr>
							
				<tr>
				    <td>Solicitud de Certificado Fitosanitario de Exportación</td>
				    <td align="center"><a href="https://portal.aduana.gob.ec/" target="_blank"><img src="aplicaciones/ayuda/img/web.png" width="31" height="31"  alt=""/></a></td>
				    <td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/vue/fitoexpor.html" target="_blank"><img src="aplicaciones/ayuda/img/video.png" width="31" height="31"  alt=""/></a></td>
					<td align="center"><a href="https://www.youtube.com/watch?v=1Tqrc911LQ8" target="_blank"><img src="aplicaciones/ayuda/img/youtube.png" width="31" height="31"  alt=""/></a></td>
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/Exportacion_Fitosanitaria.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>					
				  </tr>
							
				<tr>
				    <td>Solicitud de Importación de Productos Agropecuarios</td>
				    <td align="center"><a href="https://portal.aduana.gob.ec/" target="_blank"><img src="aplicaciones/ayuda/img/web.png" width="31" height="31"  alt=""/></a></td>
				    <td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/vue/importacion.html" target="_blank"><img src="aplicaciones/ayuda/img/video.png" width="31" height="31"  alt=""/></a></td>
					<td align="center"><a href="https://www.youtube.com/watch?v=hACMo20HI5c" target="_blank"><img src="aplicaciones/ayuda/img/youtube.png" width="31" height="31"  alt=""/></a></td>
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/Importaciones.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>					
				  </tr>
							
				<tr>
				    <td>Solicitud de Documento de Destinación Aduanera</td>
				    <td align="center"><a href="https://portal.aduana.gob.ec/" target="_blank"><img src="aplicaciones/ayuda/img/web.png" width="31" height="31"  alt=""/></a></td>
				    <td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/vue/dda.html" target="_blank"><img src="aplicaciones/ayuda/img/video.png" width="31" height="31"  alt=""/></a></td>
					<td align="center"><a href="https://www.youtube.com/watch?v=ZOkshbNfY5c" target="_blank"><img src="aplicaciones/ayuda/img/youtube.png" width="31" height="31"  alt=""/></a></td>
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/DDA.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>					
				 </tr>
							
				<tr>
					 <td>Catastro de productos Agropecuarios</td>
				    <td align="center"><a></a></td>
				    <td align="center"><a></a></td>
					<td align="center"><a></a></td>
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/Catastro_Productos_Agropecuarios.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>					
				 </tr>
							
				<tr>
					 <td>Vacunación Animal</td>
				    <td align="center"><a></a></td>
				    <td align="center"><a></a></td>
					<td align="center"><a></a></td>
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/Vacunación_Animal.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>					
				 </tr>
							
							
				<tr>
					 <td>Movilización de productos Agropecuarios</td>
				    <td align="center"><a></a></td>
				    <td align="center"><a></a></td>
					<td align="center"><a></a></td>
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/Movilizacion_Productos_Agropecuarios.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>					
				 </tr>
							
				<tr>
					 <td>Emisión de etiquetas</td>
				    <td align="center"><a></a></td>
				    <td align="center"><a></a></td>
					<td align="center"><a></a></td>
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/Emision_de_etiquetas.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>					
				 </tr>
				 
				 <tr>
					 <td>Conformación de lotes</td>
				    <td align="center"><a></a></td>
				    <td align="center"><a></a></td>
					<td align="center"><a></a></td>
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/ConformacionDeLotes.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>					
				 </tr>
							
				<tr>
					 <td>Exportación e Importación de mercancías sin valor comercial</td>
				    <td align="center"><a></a></td>
				    <td align="center"><a></a></td>
					<td align="center"><a></a></td>
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/exp_imp_mercancias.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>					
				 </tr>
				 
				 <tr>
					<td>Registro de operador de laboratorio de leches y veterinarios</td>
				    <td align="center"><a></a></td>
					<td align="center"><a></a></td>
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/registro_operador_laboratorios_leches.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>					
				 </tr>
							
			</table>';

	}

?>



<?php 
	 



	if($_SESSION['nombreLocalizacion'] != ''){
		echo'
		<br> </br>
		<h3>SERVICIOS</h3>
			<table>
				<thead>
					<tr>
						<th></th>
						<th>WEB</th>
						<th>TUTORIAL</th>
						<!--th>YOUTUBE</th-->
						<th>PDF</th>
					</tr>
				</thead>
				  <tr>
				    <td width="277">AGROBOX</td>
				    <td align="center"><a href="http://agrobox.agrocalidad.gob.ec" target="_blank"><img src="aplicaciones/ayuda/img/web.png" width="31" height="31"  alt=""/></td>
				    <td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/agrobox/index.html" target="_blank"><img src="aplicaciones/ayuda/img/video.png" width="31" height="31"  alt=""/></a></td>
					<!--td align="center"><a href="https://www.youtube.com" target="_blank"><img src="aplicaciones/ayuda/img/youtube.png" width="31" height="31"  alt=""/></a></td-->
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/agrobox.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>
					
				  </tr>
		
				  <tr>
				    <td>GLPI</td>
				    <td align="center"><a href="http://soporte.agrocalidad.gob.ec" target="_blank"><img src="aplicaciones/ayuda/img/web.png" width="31" height="31"  alt=""/></a></td>
				    <td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/glpi/index.html" target="_blank"><img src="aplicaciones/ayuda/img/video.png" width="31" height="31"  alt=""/></td>
					<!--td align="center"><a href="https://www.youtube.com" target="_blank"><img src="aplicaciones/ayuda/img/youtube.png" width="31" height="31"  alt=""/></a></td-->
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/Manual_GLPI.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>
					
				  </tr>
		
				  <tr>
				    <td>JURÍDICO (PROCESO ADMINISTRATIVO)</td>
				    <td align="center"><a href="http://guia.agrocalidad.gob.ec" target="_blank"><img src="aplicaciones/ayuda/img/web.png" width="31" height="31"  alt=""/></a></td>
				    <td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/juridico/index.htm" target="_blank"><img src="aplicaciones/ayuda/img/video.png" width="31" height="31"  alt=""/></td>
					<!--td align="center"><a href="https://www.youtube.com" target="_blank"><img src="aplicaciones/ayuda/img/youtube.png" width="31" height="31"  alt=""/></a></td>
					<td align="center"><a href="https://www.adobe.com" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td-->
					
				  </tr>
		
				  <tr>
				    <td>RADVISION (VIDEO-CONFERENCIA)</td>
					<td align="center"><a href="http://vc.agrocalidad.gob.ec" target="_blank"><img src="aplicaciones/ayuda/img/web.png" width="31" height="31"  alt=""/></a></td>
					<td align="center"><a></a></td>
					<td align="center"><a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/Radvision.pdf" target="_blank"><img src="aplicaciones/ayuda/img/pdf.png" width="31" height="31"  alt=""/></a></td>											
				 </tr>
				  
				</table>';
	}

?>



