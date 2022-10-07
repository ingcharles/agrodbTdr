<?php

class ControladorResoluciones{
	
	public function listarResoluciones ($conexion){
		$res = $conexion->ejecutarConsulta("select 
												*
											from	
												g_biblioteca.resoluciones
											order by
												numero_resolucion;");
		return $res;
	}
	
	public function guardarResolucion ($conexion,$numeroResolucion, $nombre, $fecha, $observacion, $rutaArchivo, $rutaAnexo, $estado){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_biblioteca.resoluciones
												(numero_resolucion, nombre, fecha, observacion, ruta_archivo, ruta_anexo, estado)
											VALUES 
												('$numeroResolucion', '$nombre', '$fecha', '$observacion', '$rutaArchivo', '$rutaAnexo', '$estado') returning id_resolucion;");
		return $res;
	}
	
	
	public function abrirResolucion ($conexion,$numeroResolucion){
		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM 
												g_biblioteca.resoluciones
											WHERE
												id_resolucion = '$numeroResolucion';");
				return $res;
	}
	
	public function ingresarNuevaPalabraClave ($conexion,$numeroResolucion, $palabra ){
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_biblioteca.palabras_claves(palabra, id_resolucion)
											VALUES ('$palabra','$numeroResolucion')
											returning id_palabra");
				return $res;
	}
	
	public function actualizarDatosResolucion ($conexion, $numero ,$nombre, $fecha, $observacion, $estado){
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_biblioteca.resoluciones
  											SET 
												nombre='$nombre', 
												fecha='$fecha',
												estado = '$estado',
												observacion = '$observacion'
 											WHERE 
												id_resolucion = '$numero';");
		return $res;
	}
	
	public function cargarPalabrasClave($conexion, $numero_resolucion){
		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM 
												g_biblioteca.palabras_claves
											WHERE
												id_resolucion = '$numero_resolucion'
											order by
												palabra;");
				return $res;
	}
	
	public function abrirEstructura($conexion, $estructura){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_biblioteca.estructuras
											WHERE
												id_estructura = $estructura;");
		return $res;
	}
	
	public function cargarEstructuras($conexion, $numero_resolucion, $nivelPadre = 'null'){
		$nivelPadre = ($nivelPadre == 'null')? (' is ' . $nivelPadre): (' = ' . $nivelPadre);
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_biblioteca.estructuras
											WHERE
												id_resolucion = '$numero_resolucion' 
												and id_estructura_padre $nivelPadre
												
											order by
												orden;");
		return $res;
	}
	
	public function ingresarNuevaEstructura ($conexion,$numeroResolucion, $nivel, $numero, $contenido, $padre){
		if ($padre == 'null')
			$res = $conexion->ejecutarConsulta("INSERT INTO
												g_biblioteca.estructuras(nivel,numero, contenido, id_resolucion,id_estructura_padre)
											VALUES ('$nivel','$numero','$contenido','$numeroResolucion',null)
												returning id_estructura");
		else
			$res = $conexion->ejecutarConsulta("INSERT INTO
					g_biblioteca.estructuras(nivel,numero, contenido, id_estructura_padre, id_resolucion)
					VALUES ('$nivel','$numero','$contenido',$padre,'$numeroResolucion')
					returning id_estructura");
		return $res;
	}
	
	public function actualizarEstructura ($conexion, $idEstructura, $nivel, $numero, $contenido){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_biblioteca.estructuras
											SET
												nivel = '$nivel',
												numero = '$numero',
												contenido = '$contenido'
											WHERE
												id_estructura = $idEstructura;");
		return $res;
	}
	
	public function imprimirLineaPalabrasClave($idPalabra,$palabra,$idResolucion){
		return '<tr id="R' . $idPalabra . '">' .
				'<td width="100%">' .
				$palabra .
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="resoluciones" data-opcion="eliminarPalabraClave">' .
				'<input type="hidden" name="palabraClave" value="' . $idPalabra . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaEstructura($idEstructura,$nombreEstructura,$idResolucion,$tipo){
		return '<tr id="R' . $idEstructura . '">' .
				'<td width="100%">' .
				$nombreEstructura .
				'</td>' .
				'<td>' .
				'<form class="bajar" data-rutaAplicacion="general" data-opcion="modificarOrdenamiento" >' .
				'<input type="hidden" name="idRegistro" value="' . $idEstructura . '" >' .
				'<input type="hidden" name="accion" value="BAJAR" >' .
				'<input type="hidden" name="tabla" value="'.$tipo.'" >' .
				'<button class="icono"></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="subir" data-rutaAplicacion="general" data-opcion="modificarOrdenamiento" >' .
				'<input type="hidden" name="idRegistro" value="' . $idEstructura . '" >' .
				'<input type="hidden" name="accion" value="SUBIR" >' .
				'<input type="hidden" name="tabla" value="'.$tipo.'" >' .
				'<button class="icono"></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="abrir" data-rutaAplicacion="resoluciones" data-opcion="abrirEstructura" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="resolucion" value="' . $idResolucion . '" >' .
				'<input type="hidden" name="estructura" value="' . $idEstructura . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="resoluciones" data-opcion="eliminarEstructura">' .
				'<input type="hidden" name="estructura" value="' . $idEstructura . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
public function buscarResoluciones($conexion, $numeroResolucion, $nombreResolucion, $fecha1, $fecha2, $palabrasClave){
		
		$patron = '[aeiouAEIOUáéíóúÁÉÍÓÚ]';
		$vocales = array('a','e','i','o','u','á','é','í','ó','ú','A','E','I','O','U','Á','É','Í','Ó','Ú');
		$sustitucion = '_';
		$nombreResolucion = str_replace($vocales, $sustitucion, $nombreResolucion);
		
		$consulta = '';
		$consulta .= ($numeroResolucion != null)? " and r.numero_resolucion = '$numeroResolucion'" : '';
		$consulta .= ($nombreResolucion != null)? " and upper(r.nombre) like upper('%$nombreResolucion%')" : '';
		$consulta .= (fecha1 != null and $fecha2 != null)? " and r.fecha between '$fecha1' and '$fecha2'" : '';
		if($palabrasClave != null && count($palabrasClave)>0 && $palabrasClave[0]!=null){
			$consulta .= " and upper(regexp_replace(pc.palabra,'$patron', '$sustitucion','g')) in (";
			foreach ($palabrasClave as $palabra ){
				$palabra = str_replace($vocales, $sustitucion, $palabra);
				$consulta .= "upper('$palabra'),";
			}
			$consulta = substr($consulta, 0, -1) . ')';
		}
		
		$res = $conexion->ejecutarConsulta("select 
				distinct r.numero_resolucion,
				r.id_resolucion,
				r.nombre,
				r.fecha,
				r.estado
			from 
				g_biblioteca.resoluciones r,
				g_biblioteca.palabras_claves pc
			where
				r.id_resolucion = pc.id_resolucion $consulta
			order by
				r.fecha desc");
		return $res;
	}
	
	public function eliminarPalabraClave($conexion, $palabra){
		$res = $conexion->ejecutarConsulta("delete from
				g_biblioteca.palabras_claves
				where
				id_palabra = $palabra;");
		return $res;
	
	}
	
	public function eliminarEstructura($conexion, $estructura){
		$res = $conexion->ejecutarConsulta("delete from
				g_biblioteca.estructuras
				where
				id_estructura = $estructura;");
		return $res;
	
	}
	
	public function cargarResolucion($conexion, $resolucion){
		$res = $conexion->ejecutarConsulta("
				select
				*
				from
				g_biblioteca.estructuras e
				where
				e.id_resolucion = $resolucion
				order by
				id_estructura_padre desc,orden");
				return $res;
	}
	
	
	
}
