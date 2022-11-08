<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/ControladorVacaciones.php';


$conexion = new Conexion();
$cu = new ControladorUsuarios();
$ca = new ControladorAreas();
$cv = new ControladorVacaciones();

$identificadorUsuario = $_SESSION['usuario'];
// $isAprovedAS = false;
// $isAprovedAPV = true;
$qPermiso=$cv->obtenerPermisosCreados($conexion, $_SESSION['idArea']);
$datoPermiso = pg_fetch_assoc($qPermiso);
$isAprovedAS =  $datoPermiso['existe'];


//$identificadorUsuario = $_REQUEST['identificadorSSO'];

$qDatoEmpleado = $cu->obtenerDatosEmpleado($conexion, $identificadorUsuario);
$datoEmpleado = pg_fetch_assoc($qDatoEmpleado);
$idLocalizacion = $datoEmpleado['id_localizacion'];
$idProvincia = $datoEmpleado['id_provincia'];
$nombreLocalizacion = $datoEmpleado['nombre_localizacion'];
$codigoLocalizacion = $datoEmpleado['codigo_localizacion'];
$nombreProvincia = $datoEmpleado['nombre_provincia'];

$areaUsuario = pg_fetch_assoc($ca->areaUsuario($conexion, $identificadorUsuario));

$_SESSION['idLocalizacion'] = $idLocalizacion ;
$_SESSION['nombreLocalizacion'] = $nombreLocalizacion;
$_SESSION['codigoLocalizacion'] = $codigoLocalizacion;
$_SESSION['idAplicacion'] = $_POST["idAplicacion"];
$_SESSION['idProvincia'] = $idProvincia;
$_SESSION['nombreProvincia'] = $nombreProvincia;
$_SESSION['idArea'] = $areaUsuario['id_area'];

?>

<nav id="opcionesAplicacion">
	<h1>
		<?php echo $_POST["nombre"];?>
	</h1>
	<div>
		<?php 
		$ca = new ControladorAplicaciones();
		$res = $ca->obtenerOpcionesAplicacion($conexion, $_SESSION['idAplicacion'],$identificadorUsuario);
		while($fila = pg_fetch_assoc($res)){
			echo '<a
				href="#" id="' . $fila['estilo'] . '"
				data-destino="areaTrabajo #listadoItems"
				data-rutaAplicacion="' .(isset($fila['ruta_mvc']) ?  $fila['ruta_mvc'] : $_POST['app']). '"
				data-idOpcion="' .$fila['id_opcion']. '"
				data-opcion="' . $fila['pagina'] . '"
				data-flujo = "'.$fila['id_flujo'].'"';
			if($fila['nivel']=='1'){
                echo'data-nivel = "'.$fila['nivel'].'"
                    data-padre = "'.$fila['id_padre'].'" style="padding-left: 50px !important;"';
			}
			if($fila['nivel']=='0'){
			    echo'data-nivel = "'.$fila['nivel'].'"
			    onmousedown="desplegarMenu(this)" style="background: #4f4f4f; padding-left: 35px !important;"';
			}
		   echo'data-nombre = "'.$fila['nombre_opcion'].'">' . $fila['nombre_opcion'] . '</a>';
		}
		?>
	</div>
</nav>
<section id="areaTrabajo">
	<section id="listadoItems"></section>
	<section id="detalleItem" ondragover="allowDrop(event)" ondrop="drop(event)"></section>
</section>

<script type="text/javascript">

var app = <?php echo json_encode($_POST['app']); ?> ;
var isAprovedAS = <?php echo json_encode($isAprovedAS); ?>;
var isAprovedAPV = <?php echo json_encode($isAprovedAPV); ?>;
	$("document").ready(function(event){	
		
		if( app != 'general'){

			var n = app.indexOf("mvc");
			if(n==0){
				$("head").append("<link rel='stylesheet' href='aplicaciones/<?php  echo str_replace("mvc","mvc/modulos", $_POST['app']); ?>/vistas/estilos/estiloapp.css'>");
			}else{
				$("head").append("<link rel='stylesheet' href='aplicaciones/<?php  echo $_POST['app']; ?>/estilos/estiloapp.css'>");
			}
		}
		
		var validarMenuDesplegable = false;
	 	$("#opcionesAplicacion div a").each(function(){
	
	    	if($(this).attr("data-nivel")){   
		    	     
	        	if($(this).attr("data-nivel")=="0"){
	        		validarMenuDesplegable=true;
	        		var elemento = "#"+$(this).attr("id");
	        		$(elemento).unbind('click');
		           	$(elemento).removeAttr("data-destino");
	                $(elemento).removeAttr("data-rutaaplicacion");
	                $(elemento).removeAttr("data-opcion");
	                $(elemento).removeAttr("data-flujo");
	                $(elemento).removeAttr("data-nombre");
	                
	                if(!$(elemento).attr("status")){
	                	cerrarMenu(elemento);
	                }
	            }	
	        }   

			var opcion = "#"+$(this).attr("id");

			if( app =="vacacionesPermisos" && opcion=="#__autorizacionSolicitudes" && isAprovedAS==1 )/* || (opcion=="#__autorizarPlanificacionVacaciones" && isAprovedAPV)))*/{
				colors = ['#ef3e56', '#c7c7c7' ];
				var i = 0;
				animate_loop = function() {      
				$(opcion).addClass('abiertoColor');
				$('.abiertoColor').animate({backgroundColor:colors[(i++)%colors.length]
					}, 700, function(){
						animate_loop();
					});
				};
				animate_loop();			
			}
	    });	
		
		if(validarMenuDesplegable){
		 	$("#listadoItems").html('<div class="mensajeInicial">Seleccione una opción para revisarla.</div>');
	    }else{
			abrir($("#opcionesAplicacion div a").first(),"",true);
			$("title").html($("#opcionesAplicacion div a").first().attr('data-nombre'));
		}
		
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un item para revisarlo.</div>');
		$("#listadoItems").removeClass("programas");
		crearBarraResize();	
	
	});
</script>
