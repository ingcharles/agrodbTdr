<?php
 /**
 * Controlador RegistroProduccion
 *
 * Este archivo controla la lógica del negocio del modelo:  RegistroProduccionModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-09-18
 * @uses    RegistroProduccionControlador
 * @package EmisionCertificacionOrigen
 * @subpackage Controladores
 */
 namespace Agrodb\EmisionCertificacionOrigen\Controladores;
 use Agrodb\EmisionCertificacionOrigen\Modelos\RegistroProduccionLogicaNegocio;
 use Agrodb\EmisionCertificacionOrigen\Modelos\RegistroProduccionModelo;
 use Agrodb\EmisionCertificacionOrigen\Modelos\ProductosLogicaNegocio;
 use Agrodb\EmisionCertificacionOrigen\Modelos\ProductosModelo;
 use Agrodb\EmisionCertificacionOrigen\Modelos\ProductosTempLogicaNegocio;
 use Agrodb\EmisionCertificacionOrigen\Modelos\ProductosTempModelo;
 use Agrodb\EmisionCertificacionOrigen\Modelos\SubproductosTempLogicaNegocio;
 use Agrodb\EmisionCertificacionOrigen\Modelos\SubproductosTempModelo;
 use Agrodb\EmisionCertificacionOrigen\Modelos\SubproductosLogicaNegocio;
 use Agrodb\EmisionCertificacionOrigen\Modelos\SubproductosModelo;
 use Agrodb\EmisionCertificacionOrigen\Modelos\LocalizacionLogicaNegocio;
 use Agrodb\EmisionCertificacionOrigen\Modelos\LocalizacionModelo;
 
 
class RegistroProduccionControlador extends BaseControlador 
{

		 private $lNegocioRegistroProduccion = null;
		 private $modeloRegistroProduccion = null;
		 
		 private $lNegocioProductos = null;
		 private $modeloProductos = null;
		 
		 private $lNegocioProductosTemp = null;
		 private $modeloProductosTemp = null;
		 
		 private $lNegocioSubproductosTemp = null;
		 private $modeloSubproductosTemp = null;
		 
		 private $lNegocioSubproductos = null;
		 private $modeloSubproductos = null;
		 
		 private $productosAgregados = null;
		 private $subProductosAgregados = null;
		 
		 private $lNegocioLocalizacion = null;
		 private $modeloLocalizacion = null;
		 
		 
		 private $accion = null;
		 private $especie=null;
		 private $tipo=null;
		 private $visualizar=null;
		 private $idRegistro=null;
		 
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioRegistroProduccion = new RegistroProduccionLogicaNegocio();
		 $this->modeloRegistroProduccion = new RegistroProduccionModelo();
		 
		 $this->lNegocioProductos = new ProductosLogicaNegocio();
		 $this->modeloProductos = new ProductosModelo();
		 
		 $this->lNegocioProductosTemp = new ProductosTempLogicaNegocio();
		 $this->modeloProductosTemp = new ProductosTempModelo();
		 
		 $this->lNegocioSubProductosTemp = new SubproductosTempLogicaNegocio();
		 $this->modeloSubProductosTemp = new SubproductosTempModelo();
		 
		 $this->lNegocioSubproductos = new SubproductosLogicaNegocio();
		 $this->modeloSubproductos = new SubproductosModelo();
		 
		 $this->lNegocioLocalizacion = new LocalizacionLogicaNegocio();
		 $this->modeloLocalizacion = new LocalizacionModelo();
		 
		 
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $this->filtroOperaciones();
		 $arrayParametros = array('identificador_operador'=> $_SESSION['usuario']);
		 $modeloRegistroProduccion = $this->lNegocioRegistroProduccion->listarRegistroProduccion($arrayParametros,'order by 1 desc');
		 $this->tablaHtmlRegistroProduccion($modeloRegistroProduccion);
		 require APP . 'EmisionCertificacionOrigen/vistas/listaRegistroProduccionVista.php';
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nueva Producción"; 
		 $this->lNegocioProductosTemp->borrarTablasTemporales();
		 //$arrayParametros = array('identificador_operador'=>$_SESSION['usuario']);
		// $verificar = $this->lNegocioRegistroProduccion->buscarSitioFaenamiento($arrayParametros);
		 //$this->tipo = $verificar['tipo'];
		 require APP . 'EmisionCertificacionOrigen/vistas/formularioRegistroProduccionVista.php';
// 		 $this->especie = $this->comboEspecie();
// 		 if($this->tipo =='Mayores'){
// 		      require APP . 'EmisionCertificacionOrigen/vistas/formularioRegistroProduccionMayoresVista.php';
// 		 }else{
// 		      require APP . 'EmisionCertificacionOrigen/vistas/formularioRegistroProduccionMenoresVista.php';
// 		 }
		}	/**
		* Método para registrar en la base de datos -RegistroProduccion
		*/
		public function guardar()
		{
		  $this->lNegocioRegistroProduccion->guardar($_POST);
		}
		/**
		 * Método para registrar en la base de datos -RegistroProduccion
		 */
		public function guardarProduccion()
		{
		    $this->lNegocioRegistroProduccion->guardarProduccion($_POST);
		}
		/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: RegistroProduccion
		*/
		public function editar()
		{
		 $this->accion = "Registro Producción"; 
		 $id = explode('-', $_POST['id']);
		 $this->modeloRegistroProduccion = $this->lNegocioRegistroProduccion->buscar($id[0]);
		 $this->visualizar = 'si';
		 $arrayParametros= array('identificador_operador' => $_SESSION['usuario'],'id_productos' => $id[1]);
		 $this->productosAgregados = $this->listarProductos($arrayParametros);
		 $arrayParametros = array('id_productos' => $id[1]);
		 $this->subProductosAgregados = $this->listarSubProductos($arrayParametros);
		 
		 $this->modeloProductos = $this->lNegocioProductos->buscar($id[1]);
		 if($this->modeloRegistroProduccion->getTipo() =='Mayores'){
		      require APP . 'EmisionCertificacionOrigen/vistas/formularioRegistroProduccionMayoresVista.php';
		 }else{
		      require APP . 'EmisionCertificacionOrigen/vistas/formularioRegistroProduccionMenoresVista.php';
		 }
		}	/**
		* Método para borrar un registro en la base de datos - RegistroProduccion
		*/
		public function eliminar()
		{
		    if($_POST['elementos'] !=''){
		    $id = explode('-', $_POST['elementos']);
		    $this->modeloRegistroProduccion = $this->lNegocioRegistroProduccion->buscar($id[0]);
		    $this->visualizar = 'eliminar';
		    $arrayParametros= array('identificador_operador' => $_SESSION['usuario'],'id_productos' => $id[1]);
		    $this->productosAgregados = $this->listarProductos($arrayParametros);
		    $arrayParametros = array('id_productos' => $id[1]);
		    $this->subProductosAgregados = $this->listarSubProductos($arrayParametros);
		    $this->idRegistro = $id[1];
		    if($this->modeloRegistroProduccion->getTipo() =='Mayores'){
		        require APP . 'EmisionCertificacionOrigen/vistas/formularioRegistroProduccionMayoresVista.php';
		    }else{
		        require APP . 'EmisionCertificacionOrigen/vistas/formularioRegistroProduccionMenoresVista.php';
		    }
		    }
		}	/**
		* Construye el código HTML para desplegar la lista de - RegistroProduccion
		*/
		 public function tablaHtmlRegistroProduccion($tabla) {
		{
		 $contador = 0;
		  foreach ($tabla as $fila) {
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['id_registro_produccion'] . '-'.$fila['id_productos'].'"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'EmisionCertificacionOrigen\registroProduccion"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['fecha_faenamiento'] . '</b></td>
            <td>' . $fila['tipo_especie'] . '</td>
            <td align="center">' . $fila['num_animales_recibidos'] . '</td>
            <td align="center">' . $fila['num_canales_obtenidos'] . '</td>
            <td>' . $fila['subproducto'] . '</td>
            </tr>');
		}
		}
	}
	//****************************filtra informacion segun parametros************
	public function filtrarInformacion(){
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    $modeloRegistroProduccion = array();
	    if(isset($_POST['fechaInicio']) && isset($_POST['fechaFin']) && $_POST['fechaInicio'] != '' && $_POST['fechaFin'] != '' ){
	    
	    $arrayParametros = array('identificador_operador'=> $_SESSION['usuario'],'fechaInicio' => $_POST['fechaInicio'],'fechaFin' => $_POST['fechaFin']);
	    $modeloRegistroProduccion = $this->lNegocioRegistroProduccion->listarRegistroProduccion($arrayParametros);
	    if($modeloRegistroProduccion->count()==0){
	        $estado = 'FALLO';
	        $mensaje = 'No existen registros para la busqueda..!!';
	    }
	    }else{
	        $estado = 'FALLO';
	        $mensaje = 'Debe ingresar la fecha inicio y fecha fin..!!';
	    }
	    $this->tablaHtmlRegistroProduccion($modeloRegistroProduccion);
	    $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido));
	}
	/**
	 *
	 */
	public function listarCanalObtenido(){
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    $contenido= $this->comboNumeros($_POST['numCanalesObtenidos']);
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido));
	    
	}
	/**
	 * 
	 */
	public function listarCanalSinRestrUso(){
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    $contenido= $this->comboNumeros($_POST['numCanalesObtenidos']);
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido));
	    
	}
	/**
	 *
	 */
	public function listarCanalIndustr(){
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    $contenido= $_POST['numCanalesObtenidos']-$_POST['numCanalesObtenidosUso'];
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido));
	}
	/**
	 * 
	 */
	public function agregarProduccion(){
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    $subContenido = '';
	    $banValidarIngreso = true;
	    
	    $validarProduccion = $this->lNegocioProductosTemp->buscarLista("fecha_faenamiento = '".$_POST['fecha_faenamiento']."' and identificador_operador='".$_SESSION['usuario']."' and tipo_especie= '".$_POST['tipo_especie']."'");
	    if($validarProduccion->count()){
	        $banValidarIngreso=false;
	    }
	    if($banValidarIngreso){
	        $arrayParametros = array('identificador_operador' => $_SESSION['usuario'], 'fecha_faenamiento' => $_POST['fecha_faenamiento'], 'tipo_especie' => $_POST['tipo_especie'] );
	        $validarProduccion = $this->lNegocioRegistroProduccion->validarRegistroProduccion($arrayParametros);
	    }
	    if($validarProduccion->count()){
	        $banValidarIngreso=false;
	    }
	    
	    
	    if($banValidarIngreso){
	    $resultado =  $this->lNegocioProductosTemp->buscarLista("fecha_creacion::date = '".date('Y-m-d')."' and identificador_operador='".$_SESSION['usuario']."' order by 1 DESC LIMIT 1");
	    $arrayParametros = array('identificador_operador' => $_SESSION['usuario'], 'fecha_creacion' => date('Y-m-d'));
	    $resultProductos =  $this->lNegocioRegistroProduccion->listarRegistroProduccion($arrayParametros,'order by 1,8 desc limit 1');
	    $codigoCanal=str_pad(1, 3, "0", STR_PAD_LEFT);
	    if($resultProductos->count()){
	        if($resultado->count()){
	            $num= $resultado->current()->codigo_canal + $resultProductos->current()->codigo_canal +1;
	            $codigoCanal = str_pad($num, 3, "0", STR_PAD_LEFT);
	        }else{
	            $num= $resultProductos->current()->codigo_canal +1;
	            $codigoCanal = str_pad($num, 3, "0", STR_PAD_LEFT);
	        }
	    }else if($resultado->count()){
	        $num= $resultado->current()->codigo_canal +1;
	        $codigoCanal = str_pad($num, 3, "0", STR_PAD_LEFT);
	    }
	    $_POST['codigo_canal']=$codigoCanal;
	    $_POST['identificador_operador']=$_SESSION['usuario'];
	   $result = $this->lNegocioProductosTemp->guardar($_POST);
	   if($result > 0){
	       if(isset($_POST['menores'])){
	           $contenido = $this->listarProductosTmpMenores();
	       }else{
	           $contenido = $this->listarProductosTmp();
	       }
	        $subContenido = $this->listarEspecie();
	    }else{
	        $estado = 'error';
	        $mensaje = "Error al guardar los datos";
	    }
	    
	    }else{
	        $estado = 'error';
	        $mensaje = "Producción con la misma especie ya registrada...!!";
	        
	    }
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido,
	        'subContenido' => $subContenido
	    ));
	}
	
	/**
	 *
	 */
	public function eliminarProduccion(){
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    $verificar=$this->lNegocioSubProductosTemp->buscarLista('id_productos_temp = '.$_POST['id']);
	    if($verificar->count() > 0){
	        $estado = 'error';
	        $mensaje = "Existen subproductos agregados al producto a eliminar..!!";
	    }else{
	        $this->lNegocioProductosTemp->borrar($_POST['id']);
	    }
	    $contenido = $this->listarProductosTmp();
	    $subContenido = $this->listarEspecie();
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido,
	        'subContenido' => $subContenido
	    ));
	}
	/**
	 * 
	 * @return string
	 */
	public function listarProductosTmp(){
	    $html=$datos='';
	    $resultado =  $this->lNegocioProductosTemp->buscarLista("identificador_operador='".$_SESSION['usuario']."' order by 1");
	    if($resultado->count()){
	        $contador=0;
	        foreach ($resultado as $item) {
	            
	            $datos .= '<tr>';
	            $datos .= '<td>'.++$contador.'</td>';
	            $datos .= '<td>'.$item->fecha_faenamiento.'</td>';
	            $datos .= '<td>'.$item->tipo_especie.'</td>';
	            $datos .= '<td align="center">'.$item->num_canales_obtenidos_uso.'</td>';
	            $datos .= '<td align="center">'.$item->num_canales_uso_industri.'</td>';
	            $datos .= '<td><button class="bEliminar icono" onclick="eliminarProducto('.$item->id_productos_temp.'); return false; "></button></td>';
	            $datos .= '</tr>';
	        }
	        $html = '
        				<table style="width:100%">
        					<thead><tr>
        						<th>#</th>
        						<th>Fecha faenamiento</th>
        						<th>Especie</th>
                                <th>N° canales obtenidas sin restricción de uso</th>
                                <th>N° canales obtenidas para uso industrial</th>
                                <th></th>
        						</tr></thead>
        					<tbody>'.$datos.'</tbody>
        				</table>';
	    }
	    return $html;
	}
	/**
	 *
	 * @return string
	 */
	public function listarProductosTmpMenores(){
	    $html=$datos='';
	    $resultado =  $this->lNegocioProductosTemp->buscarLista("identificador_operador='".$_SESSION['usuario']."' order by 1");
	    if($resultado->count()){
	        $contador=0;
	        foreach ($resultado as $item) {
	            $datos .= '<tr>';
	            $datos .= '<td>'.++$contador.'</td>';
	            $datos .= '<td>'.$item->fecha_faenamiento.'</td>';
	            $datos .= '<td>'.$item->tipo_especie.'</td>';
	            $datos .= '<td align="center">'.$item->num_canales_obtenidos_uso.'</td>';
	            $datos .= '<td align="center">'.$item->num_canales_uso_industri.'</td>';
	            $datos .= '<td><button class="bEliminar icono" onclick="eliminarProducto('.$item->id_productos_temp.'); return false; "></button></td>';
	            $datos .= '</tr>'; 
	        }
	        $html = '
        				<table style="width:100%">
        					<thead><tr>
        						<th>#</th>
        						<th>Fecha faenamiento</th>
        						<th>Especie</th>
                                <th>N° canales obtenidas sin restricción de uso</th>
                                <th>N° canales obtenidas para uso industrial</th>
                                <th></th>
        						</tr></thead>
        					<tbody>'.$datos.'</tbody>
        				</table>';
	    }
	    return $html;
	}
	
	/**
	 *
	 * @return string
	 */
	public function listarProductos($arrayParametros){
	    $html=$datos='';
	    $consulta= $this->lNegocioRegistroProduccion->listarRegistroProduccion($arrayParametros);
	    if($consulta->count()){
	        $contador=0;
	        foreach ($consulta as $item) {
	            $datos .= '<tr>';
	            $datos .= '<td>'.++$contador.'</td>';
	            $datos .= '<td>'.$item->fecha_faenamiento.'</td>';
	            $datos .= '<td>'.$item->tipo_especie.'</td>';
	            $datos .= '<td align="center">'.$item->num_canales_obtenidos_uso.'</td>';
	            $datos .= '<td align="center">'.$item->num_canales_uso_industri.'</td>';
	           // $datos .= '<td><button class="bEliminar icono" onclick="eliminarProducto('.$item->id_productos.'); return false; "></button></td>';
	            $datos .= '</tr>';
	        }
	        $html = '
        				<table style="width:100%">
        					<thead><tr>
        						<th>#</th>
        						<th>Fecha faenamiento</th>
        						<th>Especie</th>
                                <th>N° canales obtenidas sin restricción de uso</th>
                                <th>N° canales obtenidas para uso industrial</th>
                                <th></th>
        						</tr></thead>
        					<tbody>'.$datos.'</tbody>
        				</table>';
	    }
	    return $html;
	}
	
	public function listarEspecie(){
	    $arrayParametros = array('identificador_operador' => $_SESSION['usuario']);
	    $resultado =  $this->lNegocioProductosTemp->obtenerEspecie($arrayParametros);
	    $combo = '<option value="">Seleccionar...</option>';
	    if($resultado->count()){
	        foreach ($resultado as $item) {
	            $combo .= '<option value="' . $item['tipo_especie'] . '-'.$item['num_canales_obtenidos'].'-'.$item['id_productos_temp'].'" >' .$item['contador'].' - '. $item['tipo_especie']. '</option>';
	        }
	    }
	    return $combo;
	}
	public function agregarSubproducto(){
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    $nombreSubtipo = explode('-', $_POST['tipoEspecieSub']);
	    $arrayParametros = array('id_area' => 'AI', 'nombreTipoProducto'=>'Productos y subproductos cárnicos en estado primario', 'nombreSubtipo' =>$nombreSubtipo[0]);
	    $consulta = $this->lNegocioRegistroProduccion->obtenerSubproducto($arrayParametros);
	    $combo = '<option value="">Seleccionar...</option>';
	    if($consulta->count()){
	        foreach ($consulta as $item) {
	            $combo .= '<option value="' . $item['numero_piezas'] . '" >' . $item['nombre_comun']. '</option>';
	        }
	    }else{
	        $estado = 'error';
	        $mensaje = 'Actualizar el campo número de piezas, en el módulo de Administración de productos';
	    }
	    $contenido = $combo;
	    
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido));
	}
	
	public function numPiezaSubproducto(){
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    $utilizado = 0;
	    $num_canales = explode('-', $_POST['tipoEspecie']);
	    $numMax = $num_canales[1]*$_POST['numPiezas'];
	    
	    $result = $this->lNegocioSubProductosTemp->buscarLista("id_productos_temp=".$num_canales[2]." and subproducto='".$_POST['subproducto']."' and fecha_creacion::date='".date('Y-m-d')."'");
	    if($result->count()){
	        $utilizado = $result->current()->cantidad;
	    }
	    
	    $cantidadLibre   = $numMax - $utilizado;
	    
	    $contenido =   $contenido= $this->comboNumeros($cantidadLibre);
	    
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido));
	}
	
	public function agregarSubProduccion(){
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    $datos = explode('-', $_POST['tipo_especie_sub']);
	    $total=0;
	    
	    $arrayParametros = array('id_area' => 'AI', 'nombreTipoProducto'=>'Productos y subproductos cárnicos en estado primario', 'nombreSubtipo' =>$datos[0],'nombre_comun' => $_POST['subproducto'] );
	    $consulta = $this->lNegocioRegistroProduccion->obtenerSubproducto($arrayParametros);
	    if($consulta->count()>0){
	        foreach ($consulta as $value) {
	            $total = $value['numero_piezas'] * $datos[1];
	        }
	    }
	    $_POST['id_productos_temp'] =  $datos[2];
	    $arrayParametros = array('id_productos_temp' => $datos[2],'subproducto' => $_POST['subproducto']);
	    $cantidad = $this->lNegocioSubProductosTemp->sumarCantidadSubProductos($arrayParametros);
	    if($cantidad->count()>0){
	        if(($cantidad->current()->total+$_POST['cantidad'] )<= $total){
	            $_POST['id_subproductos_temp'] = $cantidad->current()->id_subproductos_temp;
	            $_POST['cantidad']=$cantidad->current()->total+$_POST['cantidad'];
	            $result = $this->lNegocioSubProductosTemp->guardar($_POST);
	            if($result > 0){
	                $contenido = $this->listarSubProductosTmp();
	            }else{
	                $estado = 'error';
	                $mensaje = "Error al guardar los datos";
	            }
	        }else{
	            $estado = 'error';
	            $mensaje = "Excede la cantidad de producción diaria";
	        }
	        
	    }else{
	        $result = $this->lNegocioSubProductosTemp->guardar($_POST);
	        if($result > 0){
	            $contenido = $this->listarSubProductosTmp();
	        }else{
	            $estado = 'error';
	            $mensaje = "Error al guardar los datos";
	        }
	    }
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	/**
	 *
	 */
	public function eliminarSubproduccion(){
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    $this->lNegocioSubProductosTemp->borrar($_POST['id']);
	    $contenido = $this->listarSubProductosTmp();
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	/**
	 *
	 * @return string
	 */
	public function listarSubProductosTmp(){
	    $html=$datos='';
	    $arrayParametros = array('identificador_operador' => $_SESSION['usuario']);
	    $resultado =  $this->lNegocioSubProductosTemp->buscarSubproductosXProductos($arrayParametros);
	    if($resultado->count()){
	        $contador=0; 
	        foreach ($resultado as $item) {
	            
	            $datos .= '<tr>';
	            $datos .= '<td>'.++$contador.'</td>';
	            $datos .= '<td>'.$item['fecha_faenamiento'].'</td>';
	            $datos .= '<td>'.$item['tipo_especie'].'</td>';
	            $datos .= '<td>'.$item['subproducto'].'</td>';
	            $datos .= '<td>'.$item['cantidad'].'</td>';
	            $datos .= '<td><button class="bEliminar icono" onclick="eliminarSubproducto('.$item['id_subproductos_temp'].'); return false; "></button></td>';
	            $datos .= '</tr>';
	        }
	        $html = '
        				<table style="width:100%">
        					<thead><tr>
        						<th>#</th>
        						<th>Fecha faenamiento</th>
        						<th>Especie</th>
                                <th>Subproducto</th>
                                <th>Cantidad</th>
                                <th></th>
        						</tr></thead>
        					<tbody>'.$datos.'</tbody>
        				</table>';
	    }
	    return $html;
	}
	
	/**
	 *
	 * @return string
	 */
	public function listarSubProductos($arrayParametros){
	    $html=$datos='';
	    $resultado =  $this->lNegocioRegistroProduccion->buscarSubproductosXProductos($arrayParametros);
	    if($resultado->count()){
	        $contador=0; 
	        foreach ($resultado as $item) {
	            
	            $datos .= '<tr>';
	            $datos .= '<td>'.++$contador.'</td>';
	            $datos .= '<td>'.$item['fecha_faenamiento'].'</td>';
	            $datos .= '<td>'.$item['tipo_especie'].'</td>';
	            $datos .= '<td>'.$item['subproducto'].'</td>';
	            $datos .= '<td>'.$item['cantidad'].'</td>';
	          //  $datos .= '<td><button class="bEliminar icono" onclick="eliminarSubproducto('.$item['id_subproductos_temp'].'); return false; "></button></td>';
	            $datos .= '</tr>';
	        }
	        $html = '
        				<table style="width:100%">
        					<thead><tr>
        						<th>#</th>
        						<th>Fecha faenamiento</th>
        						<th>Especie</th>
                                <th>Subproducto</th>
                                <th>Cantidad</th>
                                <th></th>
        						</tr></thead>
        					<tbody>'.$datos.'</tbody>
        				</table>';
	    }
	    return $html;
	}
	
	/**
	 *
	 */
	public function eliminarRegistro(){
	    $estado = 'EXITO';
	    $mensaje = 'Registro Borrado';
	    $contenido = '';
	    
	    $consultar = $this->lNegocioProductos->buscar($_POST['id']);
	    $verificar= $this->lNegocioProductos->buscarLista('id_registro_produccion ='.$consultar->getIdRegistroProduccion());
	    $this->lNegocioSubproductos->borrarPorParametro('id_productos',$_POST['id']);
	    if($verificar->count()==1){
	        $this->lNegocioProductos->borrar($_POST['id']);
	        $this->lNegocioRegistroProduccion->borrar($consultar->getIdRegistroProduccion());
	    }else{
	        $this->lNegocioProductos->borrar($_POST['id']);
	    }
	    
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	/**
	 * filtro buscar sitio
	 */
	public function buscarSitio(){
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    
	    if(isset($_POST['id'])){
	        $dato = explode('-', $_POST['id']);
	        $arrayParametros = array('identificador_operador' => $_SESSION['usuario'], 'provincia' => $dato[1], 'id_area_tipo_operacion' =>'AI', 'codigo' => 'FAE');
	        $contenido = $this->comboSitioCf($arrayParametros);
	    }else{
	        $estado = 'FALLO';
	        $mensaje ='Debe seleccionar la provincia...!!!';
	    }
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	/**
	 * filtro buscar area
	 */
	public function buscarArea(){
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    if(isset($_POST['id'])){
	        $dato = explode('-', $_POST['id']);
	        $arrayParametros = array('identificador_operador' => $_SESSION['usuario'], 'id_sitio' => $dato[0], 'id_area_tipo_operacion' =>'AI', 'codigo' => 'FAE');
	        $contenido = $this->comboAreaCf($arrayParametros);
	    }else{
	        $estado = 'FALLO';
	        $mensaje ='Debe seleccionar el sitio...!!!';
	    }
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	/**
	 * filtro buscar area
	 */
	public function buscarEspecie(){
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    $codigo = '';
	    $criterio = '';
	    $canton = '';
	    $parroquia = '';
	    $idProvincia='';
	    
	    
	    if(isset($_POST['id'])){
	        $dato = explode('-', $_POST['id']);
	        $arrayParametros = array('identificador_operador' => $_SESSION['usuario'], 'id_centro_faenamiento' => $dato[2]);
	        $contenido = $this->comboEspecieCf($arrayParametros);
	        $codigo=$dato[3];
	        $criterio=$dato[4];
	        if($criterio == 'Activo'){
	            $prov= $this->lNegocioLocalizacion->buscarLista("id_localizacion_padre = 66 and nombre='".$dato[1]."'");
	            $idProvincia = $prov->current()->id_localizacion;
	            $cant = $this->lNegocioLocalizacion->buscarLista("id_localizacion_padre =".$idProvincia." and nombre='".$dato[5]."'");
	            $canton = $this->comboCantonesECO($idProvincia, $cant->current()->id_localizacion);
	            $parroquia = $this->comboParroquiasECO($cant->current()->id_localizacion);
	            //$canton =  $cant->current()->id_localizacion;
	        }
	    }else{
	        $estado = 'FALLO';
	        $mensaje ='Debe seleccionar el área...!!!';
	    }
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido,
	        'codigo' => $codigo,
	        'criterio' => $criterio,
	        'canton' => $canton,
	        'provincia' => $idProvincia,
	        'parroquia' => $parroquia
	    ));
	}
	//******************************crear combo numeros
	public function comboAnimalesRecibidos(){
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    
	    if(isset($_POST['tipoEspecie'])){
	        if($_POST['tipoEspecie'] == 'AVICOLA'){
	            $contenido = $this->comboNumeros(5000);
	        }else{
	            $contenido = $this->comboNumeros(500);
	        }
	    }else{
	        $estado = 'FALLO';
	        $mensaje ='Refrescar la página e internar nuevamente...!!!';
	    }
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	

}
