<p>Productos</p>
<?php
require_once 'clases/Conexion.php';

$c= new Conexion();

$res = $c->ejecutarConsulta("select * 
		from g_catalogos.tipo_productos tp,
		g_catalogos.subtipo_productos sp,
		g_catalogos.productos p
		where
		tp.id_tipo_producto = sp.id_tipo_producto and
		sp.id_subtipo_producto = p.id_subtipo_producto
		order by
		tp.id_area,tp.nombre,sp.nombre,p.nombre_comun;");

while ($fila = pg_fetch_assoc($res)){
	print_r ($fila);
	echo 'a<br/>';
}


