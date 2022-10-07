<?php
 /**
 * Controlador DatosIngreso
 *
 * Este archivo controla la lógica del negocio del modelo:  DatosIngresoModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-08-14
 * @uses    DatosIngresoControlador
 * @package FormularioBoleta
 * @subpackage Controladores
 */
 namespace Agrodb\FormularioBoleta\Controladores;
 use Agrodb\FormularioBoleta\Modelos\DatosIngresoLogicaNegocio;
 use Agrodb\FormularioBoleta\Modelos\DatosIngresoModelo;
 use Agrodb\FormularioBoleta\Modelos\PreguntasIngresoLogicaNegocio;
 use Agrodb\FormularioBoleta\Modelos\PreguntasIngresoModelo;
 use Agrodb\FormularioBoleta\Modelos\RespuestasIngresoLogicaNegocio;
 use Agrodb\FormularioBoleta\Modelos\RespuestasIngresoModelo;
 
 
class DatosIngresoControlador extends BaseControlador 
{

		 private $lNegocioDatosIngreso = null;
		 private $modeloDatosIngreso = null;
		 private $lNegocioPreguntasIngreso = null;
		 private $modeloPreguntasIngreso = null;
		 
		 private $lNegocioRespuestasIngreso = null;
		 private $modeloRespuestasIngreso = null;
		 
		 private $accion = null;
		 private $preguntas = null;
		 
		 private $numHombres = null;
		 private $numMujeres = null;
		 
		 private $paises = null;
		 private $puertos = null;
		 
		 
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioDatosIngreso = new DatosIngresoLogicaNegocio();
		 $this->modeloDatosIngreso = new DatosIngresoModelo();
		 $this->lNegocioPreguntasIngreso = new PreguntasIngresoLogicaNegocio();
		 $this->modeloPreguntasIngreso = new PreguntasIngresoModelo();
		 $this->lNegocioRespuestasIngreso = new RespuestasIngresoLogicaNegocio();
		 $this->modeloRespuestasIngreso = new RespuestasIngresoModelo();
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		    require APP . 'FormularioBoleta/vistas/listaDatosIngresoVista.php';
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $modeloPreguntasIngreso = $this->lNegocioPreguntasIngreso->buscarLista("estado='activo' order by orden");
		 $this->crearPreguntas($modeloPreguntasIngreso);
		 
		 $this->paises = $this->cargarPaises();
		 $this->puertos = $this->cargarPuertos(66);
		 
		 $this->accion = "Nuevo Datos Ingreso"; 
		 require APP . 'FormularioBoleta/vistas/formularioDatosIngresoVista.php';
		 
		}	
		/**
		 * Método para desplegar el formulario vacio
		 */
		public function listar()
		{        
		    $arrayParametros = '';
		    
		    if($_POST['apellidos'] != ''){
		        $arrayParametros = "apellidos ilike '%".$_POST['apellidos']."%' ";
		    }
		    if($_POST['nombres'] != ''){
		        if($_POST['apellidos'] != ''){
		            $arrayParametros .= " AND ";
		        }
		        $arrayParametros .= " nombres ilike '%".$_POST['nombres']."%' ";
		    }
		    if($_POST['identificador'] != ''){
		        if($_POST['apellidos'] != '' || $_POST['nombres'] != ''){
		            $arrayParametros .= " AND ";
		        }
		        $arrayParametros .= " identificador ilike '".$_POST['identificador']."%%' ";
		    }
		    if($_POST['fecha_dia'] != ''){
		        if($_POST['apellidos'] != '' || $_POST['nombres'] != '' || $_POST['identificador'] != ''){
		            $arrayParametros .= " AND ";
		        }
		        $arrayParametros .= " fecha_creacion::TIMESTAMP::DATE = '".$_POST['fecha_dia']."' ";
		    } 
		    
		    if($_POST['apellidos'] == '' && $_POST['nombres'] == '' && $_POST['identificador'] == '' && $_POST['fecha_dia'] == ''){
		        $arrayParametros = true;
		    }
		        $this->modeloDatosIngreso = $this->lNegocioDatosIngreso->buscarLista($arrayParametros);
		        $valores=''; $ban=0;
		        $valores .= '<h5>LISTADO</h5><div class="table-responsive" style="overflow:scroll; height:160px;"><table class="table table-sm table-hover table-responsible-lg">';
                $valores .= '<thead><tr>'.
                '<th></th>'.
                '<th>APELLIDOS</th>'.
                '<th>NOMBRES</th>'.
                '<th>IDENTIFICACIÓN</th>'.
                '</tr></thead><tbody>';
                    foreach ($this->modeloDatosIngreso as $fila) {
                        $ban=1;
                        $valores .=  '<tr>'.
                            '<td><input type="radio" name="item" id="'.$fila['id_datos_ingreso'].'" value="'.$fila['id_datos_ingreso'].'"></td>'.
                            '<td>'.$fila['apellidos'].'</td>'.
                            '<td>'.$fila['nombres'].'</td>'.
                            '<td>'.$fila['identificador'].'</td>'.
                            '</tr>';
                          }
                        $valores .= '</tbody></table></div>';
                        $valores .= '<div id="btnDiv"><button type="button" class="btnMostrar btn btn-secondary btn-sm mb-3" id="mostrar" onclick="mostrarFormulario(); return false;">Mostrar</button></div>';
                        $valores .= '<div id="mostrarFormulario"></div>';
                if($ban){
                    echo $valores;
                }else{
                    echo 'error';
                }
		}	/**
		/**
		* Método para registrar en la base de datos -DatosIngreso
		*/
		public function guardar()
		{
		    $estado = 'EXITO';
		    $mensaje = '';
		    $contenido = '';
		    $resultado=$this->lNegocioDatosIngreso->guardarFormulario($_POST);
		    if($resultado > 0){
		        $mensaje = 'Su declaración fue generada con el número: / <span class="text-muted">Your statement was generated with the number:</span> '.$resultado;
		    }else{
		        $estado = 'Error';
		        $mensaje = 'Error al guardar los datos..!!';
		    }
		    echo json_encode(array(
		        'estado' => $estado,
		        'mensaje' => $mensaje,
		        'contenido' => $contenido
		    ));
		}	
		    
		    /**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: DatosIngreso
		*/
		public function editarFormulario()
		{
		 $this->accion = "Editar DatosIngreso"; 
		 require APP . 'FormularioBoleta/vistas/listaRespuestasIngresoVista.php';
		}
	    public function listarFormulario() {
		     $this->modeloDatosIngreso = $this->lNegocioDatosIngreso->buscar($_POST['item']);
		     $modeloPreguntasIngreso = $this->lNegocioPreguntasIngreso->buscarLista("estado='activo' order by orden");
		     $this->crearPreguntas($modeloPreguntasIngreso, $this->modeloDatosIngreso->getIdDatosIngreso());
		     require APP . 'FormularioBoleta/vistas/listarDatosIngresoConsultaVista.php';
	    }
//*****************************	
	    public function crearPreguntas ($array, $idDatosIngreso=null){
    	    $this->numHombres = null;
    	    $this->numMujeres = null;
    	    foreach ($array as $fila) {
    	        $radio1=$radio2="";
    	        if($idDatosIngreso != null){
    	            $consulta = $this->lNegocioDatosIngreso->obtenerRespuestas($idDatosIngreso,$fila['id_preguntas_ingreso']);
        	        if($consulta->current()->count()){
        	            if($consulta->current()->respuesta == 'Si'){
        	                $radio1 = 'checked';
        	            }else{
        	                $radio2 = 'checked';
        	            }
        	            $this->numHombres = $consulta->current()->num_hombres;
        	            $this->numMujeres = $consulta->current()->num_mujeres;
        	        }
    	        }
    	        
    	       $this->preguntas .= '
                            <div class="col-12  mb-3 textoPreguntas">
    							<label class="text-justify">'.$fila['pregunta_espanol'].' / <span class="text-muted">'.$fila['pregunta_ingles'].'</span></label>
    						<div class="col-12 ">
    							<div class="form-check form-check-inline">
    								<label class="form-check-label">
    									<input type="radio" '.$radio1.' name="'.$fila['id_preguntas_ingreso'].'" id="si" value="Si" class="form-check-input mr-2 ">Si / <span class="text-muted">Yes</span>
    								</label>
    							</div>
    							<div class="form-check form-check-inline">
    								<label class="form-check-label">
    									<input type="radio" '.$radio2.' name="'.$fila['id_preguntas_ingreso'].'" id="no" value="No" required class="form-check-input mr-2 ">No
    								</label>
    							</div>
    						</div>
    						</div>';
    	           }
        }
        
        /**
         * Consulta los paises
         *          *
         * @param Integer $idLocalizacion
         * @return string
         */
        public function cargarPaises($idLocalizacion = null)
        {
            $paises = "";
            $combo = $this->lNegocioDatosIngreso->obtenerPaises();
            $paises = '<option value="">Seleccione/to select</option>';
            foreach ($combo as $item)
            {
                if ($idLocalizacion == $item['id_localizacion'])
                {
                    $paises .= '<option value="' . $item->id_localizacion . '" selected>' . $item->nombre . '</option>';
                } else
                {
                    $paises .= '<option value="' . $item->id_localizacion . '">' . $item->nombre . '</option>';
                }
            }
            return $paises;
        }
        /**
         * Cargar los puertos
         *          *
         * @param Integer $idPuerto
         * @return string
         */
        public function cargarPuertos($idPais=null, $idPuerto=null)
        {
            $puertos = "";
            $combo = $this->lNegocioDatosIngreso->obtenerPuertos($idPais);
            $puertos = '<option value="">Seleccione/to select</option>';
            foreach ($combo as $item)
            {
                if ($idPuerto == $item['id_puerto'])
                {
                    $puertos .= '<option value="' . $item->id_puerto . '" selected>' . $item->nombre_puerto . '</option>';
                } else
                {
                    $puertos .= '<option value="' . $item->id_puerto . '">' . $item->nombre_puerto . '</option>';
                }
            }
            return $puertos;
        }
        /**
         * funcion para buscar informacion de puertos 
         */
        public function buscarPuertos(){
            $estado = 'EXITO';
            $mensaje = '';
            $contenido = '';
            
            $contenido = $this->cargarPuertos($_POST['idPais']);
            
            echo json_encode(array(
                'estado' => $estado,
                'mensaje' => $mensaje,
                'contenido' => $contenido
            ));
        }
}
