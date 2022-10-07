<?php
/**
 * Controlador Operaciones
 *
 * Este archivo controla la lógica del negocio del modelo: OperacionesModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2020-09-18
 * @uses OperacionesControlador
 * @package AdministrarOperaciones
 * @subpackage Controladores
 */
namespace Agrodb\AdministrarOperaciones\Controladores;

use Agrodb\RegistroOperador\Modelos\OperacionesLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\OperacionesModelo;
use Agrodb\RegistroOperador\Modelos\SitiosLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\SitiosModelo;
use Agrodb\RegistroOperador\Modelos\AreasLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\AreasModelo;
/*use Agrodb\RegistroOperador\Modelos\DatosVehiculoTransporteAnimalesLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\DatosVehiculoTransporteAnimalesModelo;
use Agrodb\RegistroOperador\Modelos\VehiculoTransporteAnimalesExpiradoLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\VehiculoTransporteAnimalesExpiradoModelo;*/
use Agrodb\Catalogos\Modelos\TiposOperacionLogicaNegocio;
use Agrodb\Catalogos\Modelos\TiposOperacionModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class AdministrarOperacionesControlador extends BaseControlador
{

    private $lNegocioOperaciones = null;

    private $modeloOperaciones = null;

    private $lNegocioSitios = null;

    private $modeloSitios = null;

    private $lNegocioAreas = null;

    private $modeloAreas = null;

    private $lNegocioTiposOperacion = null;

    private $modeloTiposOperacion = null;

    /*private $modeloDatosVehiculoTransporteAnimales = null;

    private $lNegocioDatosVehiculoTransporteAnimales = null;

    private $modeloVehiculoTransporteAnimalesExprirado = null;

    private $lNegocioVehiculoTransporteAnimalesExpirado = null;*/

    private $accion = null;

    private $operador = null;

    private $datosSitios = null;
    private $datosAreas = null;
    private $sitiosAreas = null;
    
    private $area = null;
    private $transporte = null;

    private $idOperacion = null;
    
    private $datosOperacion = null;
    private $estadoActualOperacion = null;
    private $estadoAnteriorOperacion = null;
    private $productosDeclarados = null;
    private $operacionCuarentena = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->lNegocioOperaciones = new OperacionesLogicaNegocio();
        $this->modeloOperaciones = new OperacionesModelo();

        $this->lNegocioSitios = new SitiosLogicaNegocio();
        $this->modeloSitios = new SitiosModelo();

        $this->lNegocioAreas = new AreasLogicaNegocio();
        $this->modeloAreas = new AreasModelo();

        $this->lNegocioTiposOperacion = new TiposOperacionLogicaNegocio();
        $this->modeloTiposOperacion = new TiposOperacionModelo();

        /*$this->lNegocioDatosVehiculoTransporteAnimales = new DatosVehiculoTransporteAnimalesLogicaNegocio();
        $this->modeloVehiculoTransporteAnimales = new DatosVehiculoTransporteAnimalesModelo();

        $this->modeloVehiculoTransporteAnimalesExprirado = new VehiculoTransporteAnimalesExpiradoModelo();
        $this->lNegocioVehiculoTransporteAnimalesExpirado = new VehiculoTransporteAnimalesExpiradoLogicaNegocio();*/

        set_exception_handler(array(
            $this,
            'manejadorExcepciones'
        ));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        
    }

    /**
     * Método de inicio del controlador
     */
    public function inocuidad()
    {
        $this->perfilUsuario();
        $this->area = 'AI';
        $this->filtroOperaciones();

        require APP . 'AdministrarOperaciones/vistas/listaAdministrarOperacionesVista.php';
    }

    /**
     * Método de inicio del controlador
     */
    public function sanidadAnimal()
    {
        $this->perfilUsuario();
        $this->area = 'SA';
        $this->filtroOperaciones();

        require APP . 'AdministrarOperaciones/vistas/listaAdministrarOperacionesVista.php';
    }

    /**
     * Método de inicio del controlador
     */
    public function vegetal()
    {
        $this->perfilUsuario();
        $this->area = 'SV';
        $this->filtroOperaciones();
        
        require APP . 'AdministrarOperaciones/vistas/listaAdministrarOperacionesVista.php';
    }
    
    /**
     * Método de inicio del controlador
     */
    public function laboratorios()
    {
        $this->perfilUsuario();
        $this->area = 'LT';
        $this->filtroOperaciones();
        
        require APP . 'AdministrarOperaciones/vistas/listaAdministrarOperacionesVista.php';
    }
    
    /**
     * Método de inicio del controlador
     */
    public function registros()
    {
        $this->perfilUsuario();
        $this->area = 'registros';
        $this->filtroOperaciones();
        
        require APP . 'AdministrarOperaciones/vistas/listaAdministrarOperacionesVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Operaciones";
        require APP . 'AdministrarOperacionesGuia/vistas/formularioOperacionesVista.php';
    }

    /**
     * Método para registrar en la base de datos -Operaciones
     */
    public function guardar()
    {
        $resultado = $this->lNegocioOperaciones->guardarActualizaciónEstadoOperaciones($_POST);
    
        if($resultado['bandera']){
            echo json_encode(array(
                'estado' => $resultado['estado'],
                'mensaje' => $resultado['mensaje'],
                'contenido' => $resultado['contenido']
            ));
        }else{
            Mensajes::fallo($resultado['mensaje']);
        }       
    }
    
    /**
     * Método para registrar en la base de datos -Operaciones
     */
    public function guardarSitioArea()
    {
        $bandera = true;

        $_POST["id_sitio"] = $this->lNegocioSitios->guardar($_POST);

        // Guardar registro de detalles
        if (count($_POST['iArea']) > 0) {

            for ($i = 0; $i < count($_POST['iArea']); $i ++) {
                if ($_POST["iArea"][$i] != '' && $_POST["sArea"][$i]) {
                    $arrayParametros = array(
                        'id_area' => $_POST["iArea"][$i],
                        'nombre_area' => $_POST['nArea'][$i],
                        'superficie_utilizada' => $_POST['sArea'][$i]
                    );

                    $this->lNegocioAreas->guardar($arrayParametros);
                    
                    //Buscar operación en el área modificada y cambiar estado para actualizar certificados
                    $this->lNegocioOperaciones->actualizarCertificadoOperacion($_POST["iArea"][$i]);
                } else {
                    $bandera = false;
                    break;
                }
            }
        }

        if (! $bandera) {
            Mensajes::fallo(Constantes::AREA_VACIA);
        } else {
            Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
        }
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Operaciones
     */
    public function editarAI()
    {
        $this->accion = "Editar Operaciones Inocuidad de Alimentos";

        $this->idOperacion = $_POST['id'];
        $this->modeloOperaciones = $this->lNegocioOperaciones->buscar($_POST['id']);
        $identificadorOperador = $this->modeloOperaciones->getIdentificadorOperador();

        $arrayParametros = array(
            'identificadorOperador' => $identificadorOperador,
            'idOperacion' => $this->idOperacion
        );

        $this->datosSitios = $this->lNegocioSitios->buscarSitioXOperacion($arrayParametros);
        $this->datosAreas = $this->buscarAreasXOperacion($arrayParametros);
        $this->transporte = $this->buscarMedioTransporteOperacion($arrayParametros);
        
        //Revisa si la operación tiene productos declarados en la fase actual
        $this->datosOperacion = $this->datosOperacion($this->modeloOperaciones->getIdentificadorOperador(), $_POST['id']);

        if($this->datosOperacion != null){
            $this->productosDeclarados = 'Si';
        }else{
            $this->productosDeclarados = 'No';
        }
        
        //Revisa si dentro del sitio de esta operación hay un registro de operación de cuarentena animal o vegetal        
        $idSitio = $this->datosSitios->current()->id_sitio;
        $codigoOperacion = "'CUA'";
        $estadoOperacion = "'registrado'";
            
        $this->datosOperacionCuarentena = $this->datosOperacionEspecifica($identificadorOperador, $idSitio, $codigoOperacion, $estadoOperacion);
        
        if($this->datosOperacionCuarentena->current() != null){
            $this->operacionCuarentena = 'Si';
        }else{
            $this->operacionCuarentena = 'No';
        }
        
        require APP . 'AdministrarOperaciones/vistas/formularioAdministrarOperacionesAIVista.php';
    }
    
    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Operaciones
     */
    public function editarSA()
    {
        $this->accion = "Editar Operaciones Sanidad Animal";
        
        $this->idOperacion = $_POST['id'];
        $this->modeloOperaciones = $this->lNegocioOperaciones->buscar($_POST['id']);
        $identificadorOperador = $this->modeloOperaciones->getIdentificadorOperador();
        
        $arrayParametros = array(
            'identificadorOperador' => $identificadorOperador,
            'idOperacion' => $this->idOperacion
        );
        
        $this->datosSitios = $this->lNegocioSitios->buscarSitioXOperacion($arrayParametros);
        $this->datosAreas = $this->buscarAreasXOperacion($arrayParametros);
        //$this->transporte = $this->buscarMedioTransporteOperacion($arrayParametros);
        
        //Revisa si la operación tiene productos declarados en la fase actual
        $this->datosOperacion = $this->datosOperacion($this->modeloOperaciones->getIdentificadorOperador(), $_POST['id']);
        
        if($this->datosOperacion != null){
            $this->productosDeclarados = 'Si';
        }else{
            $this->productosDeclarados = 'No';
        }
        
        //Revisa si dentro del sitio de esta operación hay un registro de operación de cuarentena animal o vegetal
        $idSitio = $this->datosSitios->current()->id_sitio;
        $codigoOperacion = "'CUA'";
        $estadoOperacion = "'registrado'";
        
        $this->datosOperacionCuarentena = $this->datosOperacionEspecifica($identificadorOperador, $idSitio, $codigoOperacion, $estadoOperacion);
        
        if($this->datosOperacionCuarentena->current() != null){
            $this->operacionCuarentena = 'Si';
        }else{
            $this->operacionCuarentena = 'No';
        }
        
        require APP . 'AdministrarOperaciones/vistas/formularioAdministrarOperacionesSAVista.php';
    }
    
    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Operaciones
     */
    public function editarSV()
    {
        $this->accion = "Editar Operaciones Sanidad Vegetal";
        
        $this->idOperacion = $_POST['id'];
        $this->modeloOperaciones = $this->lNegocioOperaciones->buscar($_POST['id']);
        $identificadorOperador = $this->modeloOperaciones->getIdentificadorOperador();
        
        $arrayParametros = array(
            'identificadorOperador' => $identificadorOperador,
            'idOperacion' => $this->idOperacion
        );
        
        $this->datosSitios = $this->lNegocioSitios->buscarSitioXOperacion($arrayParametros);
        $this->datosAreas = $this->buscarAreasXOperacion($arrayParametros);
        //$this->transporte = $this->buscarMedioTransporteOperacion($arrayParametros);
        
        //Revisa si la operación tiene productos declarados en la fase actual
        $this->datosOperacion = $this->datosOperacion($this->modeloOperaciones->getIdentificadorOperador(), $_POST['id']);
        
        if($this->datosOperacion != null){
            $this->productosDeclarados = 'Si';
        }else{
            $this->productosDeclarados = 'No';
        }
        
        //Revisa si dentro del sitio de esta operación hay un registro de operación de cuarentena animal o vegetal
        $idSitio = $this->datosSitios->current()->id_sitio;
        $codigoOperacion = "'CUA'";
        $estadoOperacion = "'registrado'";
        
        $this->datosOperacionCuarentena = $this->datosOperacionEspecifica($identificadorOperador, $idSitio, $codigoOperacion, $estadoOperacion);
        
        if($this->datosOperacionCuarentena->current() != null){
            $this->operacionCuarentena = 'Si';
        }else{
            $this->operacionCuarentena = 'No';
        }
        
        require APP . 'AdministrarOperaciones/vistas/formularioAdministrarOperacionesSVVista.php';
    }
    
    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Operaciones
     */
    public function editarLT()
    {
        $this->accion = "Editar Operaciones Laboratorios";
        
        $this->idOperacion = $_POST['id'];
        $this->modeloOperaciones = $this->lNegocioOperaciones->buscar($_POST['id']);
        $identificadorOperador = $this->modeloOperaciones->getIdentificadorOperador();
        
        $arrayParametros = array(
            'identificadorOperador' => $identificadorOperador,
            'idOperacion' => $this->idOperacion
        );
        
        $this->datosSitios = $this->lNegocioSitios->buscarSitioXOperacion($arrayParametros);
        $this->datosAreas = $this->buscarAreasXOperacion($arrayParametros);
        //$this->transporte = $this->buscarMedioTransporteOperacion($arrayParametros);
        
        //Revisa si la operación tiene productos declarados en la fase actual
        $this->datosOperacion = $this->datosOperacion($this->modeloOperaciones->getIdentificadorOperador(), $_POST['id']);
        
        if($this->datosOperacion != null){
            $this->productosDeclarados = 'Si';
        }else{
            $this->productosDeclarados = 'No';
        }
        
        //Revisa si dentro del sitio de esta operación hay un registro de operación de cuarentena animal o vegetal
        $idSitio = $this->datosSitios->current()->id_sitio;
        $codigoOperacion = "'CUA'";
        $estadoOperacion = "'registrado'";
        
        $this->datosOperacionCuarentena = $this->datosOperacionEspecifica($identificadorOperador, $idSitio, $codigoOperacion, $estadoOperacion);
        
        if($this->datosOperacionCuarentena->current() != null){
            $this->operacionCuarentena = 'Si';
        }else{
            $this->operacionCuarentena = 'No';
        }
        
        require APP . 'AdministrarOperaciones/vistas/formularioAdministrarOperacionesLTVista.php';
    }
    
    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Operaciones
     */
    public function editarRegistros()
    {
        $this->accion = "Editar Operaciones Registro de Insumos Agropecuarios";
        
        $this->idOperacion = $_POST['id'];
        $this->modeloOperaciones = $this->lNegocioOperaciones->buscar($_POST['id']);
        $identificadorOperador = $this->modeloOperaciones->getIdentificadorOperador();
        
        $arrayParametros = array(
            'identificadorOperador' => $identificadorOperador,
            'idOperacion' => $this->idOperacion
        );
        
        $this->datosSitios = $this->lNegocioSitios->buscarSitioXOperacion($arrayParametros);
        $this->datosAreas = $this->buscarAreasXOperacion($arrayParametros);
        //$this->transporte = $this->buscarMedioTransporteOperacion($arrayParametros);
        
        //Revisa si la operación tiene productos declarados en la fase actual
        $this->datosOperacion = $this->datosOperacion($this->modeloOperaciones->getIdentificadorOperador(), $_POST['id']);
        
        if($this->datosOperacion != null){
            $this->productosDeclarados = 'Si';
        }else{
            $this->productosDeclarados = 'No';
        }
        
        //Revisa si dentro del sitio de esta operación hay un registro de operación de cuarentena animal o vegetal
        $idSitio = $this->datosSitios->current()->id_sitio;
        $codigoOperacion = "'CUA'";
        $estadoOperacion = "'registrado'";
        
        $this->datosOperacionCuarentena = $this->datosOperacionEspecifica($identificadorOperador, $idSitio, $codigoOperacion, $estadoOperacion);
        
        if($this->datosOperacionCuarentena->current() != null){
            $this->operacionCuarentena = 'Si';
        }else{
            $this->operacionCuarentena = 'No';
        }
        
        require APP . 'AdministrarOperaciones/vistas/formularioAdministrarOperacionesRegistrosVista.php';
    }

    public function buscarAreasXOperacion($arrayParametros)
    {
        $areas = $this->lNegocioAreas->buscarAreasXOperadorOperacion($arrayParametros);
        $html = '';
        $i = 1;

        foreach ($areas as $item) {
            $html .= '
        	<fieldset>
        	   <legend>Datos del área ' . $i ++ . '</legend>

                <input type="hidden" id="iArea" name="iArea[]" value="' . $item['id_area'] . '">

                <div data-linea=11>
					<label>Código del área: </label>' . $item['codificacion_area'] . '
                </div>

				<div data-linea=12>
					<label>Nombre del área: </label>  
                    <input type="text" id="nArea" name="nArea[]" value="' . $item['area'] . '" required="required" maxlength="256" >
                </div>

				<div data-linea=13>
					<label>Tipo de área: </label>' . $item['tipo_area'] . '
                </div>
					    
				<div data-linea=14>
					<label>Superficie utilizada: </label>
                    <input type="number" id="sArea" name="sArea[]" value="' . $item['superficie_utilizada'] . '" required="required" >
            </fieldset>';
        }

        return $html;
    }

    /**
     */
    public function buscarMedioTransporteOperacion($arrayParametros)
    {
        $operador = $this->lNegocioAreas->buscarAreasXOperadorOperacion($arrayParametros);
        $html = '';

        foreach ($operador as $item) {
            $arrayParametros = array(
                'id_area' => $item['id_area'],
                'id_tipo_operacion' => $item['id_tipo_operacion'],
                'id_operador_tipo_operacion' => $item['id_operador_tipo_operacion'],
                'estado' => 'activo'
            );

            $transporte = $this->lNegocioOperaciones->listarDatosVehiculoXIdAreaXidTipoOperacion($arrayParametros);

            foreach ($transporte as $item) {
                $html .= '
            	   <fieldset>
            	       <legend>Datos del medio de transporte</legend>
            	            <div data-linea="5">
            	            <label>*Marca: </label> ' . $item['marca'] . '</div>
            				<div data-linea="5">
            					<label>*Modelo: </label> ' . $item['modelo'] . '</div>
            				<div data-linea="6">
            					<label>*Clase: </label> ' . $item['clase'] . '</div>
            				<div data-linea="6">
            					<label>*Color: </label> ' . $item['colorvehiculo'] . '</div>
            				<div data-linea="7">
            					<label>*Tipo: </label> ' . $item['tipovehiculo'] . '</div>
            				<div data-linea="7">
            					<label>*Placa: </label> ' . $item['placa_vehiculo'] . '</div>
            				<div data-linea="8">
            					<label>*Año: </label> ' . $item['anio_vehiculo'] . '</div>
            				<div data-linea="8">
            					<label>*Capacidad instalada: </label>' . $item['capacidad_vehiculo'] . '</div>
            				<div data-linea="9">
            					<label>*Unidad: </label>' . $item['codigo_unidad_medida'] . '</div>';

                $html .= '</fieldset>';
            }
        }

        return $html;
    }

    public function datosOperacion($identificadorOperador, $idOperacion)
    {
        $lNegocioOperaciones = new OperacionesLogicaNegocio();

        $arrayParametros = array(
            'identificadorOperador' => $identificadorOperador,
            'idOperacion' => $idOperacion
        );

        $operador = $lNegocioOperaciones->abrirDatosOperacionSitioArea($arrayParametros);

        $html = '';

        foreach ($operador as $item) {

            $arrayParametros = array(
                'id_operador_tipo_operacion' => $item['id_operador_tipo_operacion'],
                'id_historial_operacion' => $item['id_historial_operacion'],
                'estado' => $item['estado']
            );

            $productos = $lNegocioOperaciones->obtenerProductosPorIdOperadorTipoOperacionHistorico($arrayParametros);

            $this->estadoActualOperacion = $item['estado'];
            $this->estadoAnteriorOperacion = $item['estado_anterior'];

            $html = ' <fieldset>
                		<div id="listaOperaciones" style="width: 100%">
                    	<legend>Datos de la operación </legend>';

            if ($productos->count() != 0) {
                $html .= '
        			<table style="width: 100%">
        			<thead>
        				<tr>
        					<th>#</th>
        					<th>Tipo producto</th>
        					<th>Subtipo producto</th>
        					<th>Producto</th>
        					<th>Código</th>
                            <th>Estado actual</th>
                            <th>Estado anterior</th>
                            <th></th>
        				</tr>
        			</thead>
        			<tbody>
        			<?php
                    ';
                $contadorProducto = 0;
                foreach ($productos as $fila) {
                    $html .= '<tr><td>' . ++ $contadorProducto . '</td>
                              <td>' . $fila['nombre_tipo'] . '</td>
                              <td>' . $fila['nombre_subtipo'] . '</td>
                              <td>' . $fila['nombre_comun'] . '</td>
    						  <td>' . $fila['id_operacion'] . '</td>
                              <td>' . $fila['estado'] . '</td>
                              <td>' . $fila['estado_anterior'] . '</td>
                              ';
                    // if($fila['estado'] == 'registrado'){
                    // $html .= '<td><input type="checkbox" checked id="'.$fila['id_operacion'].'" value="'.$fila['id_operacion'].'" name="check[]" onclick="limpiarResultado(id);"/> </td></tr>';
                    // }else{
                    $html .= '<td><input type="checkbox"   id="' . $fila['id_operacion'] . '" value="' . $fila['id_operacion'] . '" name="check[]" onclick="limpiarResultado(id);"/> </td></tr>';

                    // }
                }

                $html .= '</tbody>
    		</table><hr>';
                $html .= '
                   <div data-linea="2">
                    <span>Seleccionar:  </span>
        		    <input  name="resultado[]" type="radio"  id="total"   value="total" onclick="verificarOpcion(id);"><span> Todo</span>&nbsp;&nbsp;&nbsp;&nbsp;
        			<input  name="resultado[]" type="radio"  id="parcial" value="parcial" onclick="verificarOpcion(id);"><span> Parcial</span>&nbsp;&nbsp;&nbsp;&nbsp;
		          </div>
            </div>
    	</fieldset>
                ';
            }else{
                $html = '';
            }
        }
        return $html;
    }
    
    public function datosOperacionEspecifica($identificadorOperador, $idSitio, $codigoOperacion, $estadoOperacion)
    {
        $lNegocioOperaciones = new OperacionesLogicaNegocio();
        
        $arrayParametros = array(
            'identificadorOperador' => $identificadorOperador,
            'idSitio' => $idSitio,
            'codigoOperacion' => $codigoOperacion,
            'estado' => $estadoOperacion
        );
        
        $operador = $lNegocioOperaciones->buscarOperacionEspecificaEnSitio($arrayParametros);
        
        return $operador;
    }

    public function comboTipoOperacion($arrayParametros)
    {
        if($arrayParametros['id_area'] == 'registros'){
            $arrayParametros['id_area'] = "CGRIA', 'IAV', 'IAP', 'IAF";
        }
        
        $query = "estado = 1 and permite_desplegar_administracion_operacion is true and id_area in ('".$arrayParametros['id_area']."')";
        
        $combo = $this->lNegocioTiposOperacion->buscarLista($query);

        $opcionesHtml = '<option value="">Seleccione...</option>';

        foreach ($combo as $item) {
            $opcionesHtml .= '<option value="' . $item->codigo . '">' . $item->nombre . '</option>';
        }

        return $opcionesHtml;
    }
    
    /*public function comboTipoOperacion($arrayParametros)
    {
        if($arrayParametros['id_area'] == 'registros'){
            $arrayParametros['id_area'] = "CGRIA', 'IAV', 'IAP', 'IAF";
        }
        
        $combo = $this->lNegocioOperaciones->obtenerTipoOperacionesOperador($arrayParametros);
        
        $opcionesHtml = '<option value="">Seleccione...</option>';
        
        foreach ($combo as $item) {
            $opcionesHtml .= '<option value="' . $item->codigo . '">' . $item->operaciones_registradas . '</option>';
        }
        
        return $opcionesHtml;
    }*/
    
    public function comboNuevoEstadoOperacion($estadoActual, $estadoAnterior)
    {
        $opcionesHtml = '';//'<option value>Seleccione...</option>';
        
        if ($estadoActual == 'registrado') {//Solamente se permite inactivar. Debe actualizar el certificado, operacion en true
            $opcionesHtml .= '<option value="noHabilitado">Inactivar operación</option>';
            
        }else if($estadoActual == 'noHabilitado'){//Se cambia al estado anterior. Si el anterior era registrado o registradoObservacion actualizar certificado, operacion true
            $opcionesHtml .= '<option value="estadoAnterior">Regresar operación al estado anterior</option>';
            
        }else{ //Con cualquier otro estado solamente se inactiva
            $opcionesHtml .= '<option value="noHabilitadoOtro">Inactivar operación en proceso</option>';
        }

        return $opcionesHtml;
    }

    /**
     * Método para borrar un registro en la base de datos - Operaciones
     */
    public function borrar()
    {
        $this->lNegocioOperaciones->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Operaciones
     */
    public function tablaHtmlOperaciones($tabla, $idArea)
    {
        $contador = 0;
        foreach ($tabla as $fila) {

            $arrayParametros = array(
                'idTipoOperacion' => $fila['id_tipo_operacion'],
                'idSitio' => $fila['id_sitio']
            );

            $nombreArea = $this->lNegocioOperaciones->buscarNombreAreaPorSitioPorTipoOperacion($arrayParametros);

            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_operacion'] . '"
                	  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'AdministrarOperaciones/AdministrarOperaciones"
                	  data-opcion="editar' . $idArea . '" ondragstart="drag(event)" draggable="true"
                	  data-destino="detalleItem">
            	    <td>' . ++ $contador . '</td>
            	    <td style="white - space:nowrap; "><b>' . $fila['id_tipo_operacion'] . '-' . $fila['id_sitio'] . '</b></td>
                    <td>' . $fila['provincia'] . '</td>
                    <td>' . $fila['nombre_tipo_operacion'] . '</td>
                    <td>' . $nombreArea->current()->nombre_area . '</td>
                    <td>' . $fila['estado'] . '</td>
                </tr>'
            );
        }
    }

    public function filtroOperaciones()
    {
        $tipoOperacion = '<option value>Seleccione...</option>';

        $arrayParametros = array(
            'identificador_operador' => $_SESSION['usuario'],
            'estado' => "in ('registrado','noHabilitado')",
            'provincia' => $_SESSION['nombreProvincia'],
            'id_area' => $this->area
        );

        $tipoOperacion = $this->comboTipoOperacion($arrayParametros);

        $this->panelBusqueda = '<table class="filtro" style="width: 100%;">
                                            	<tbody>
	                                                <tr>
	                                                    <th colspan="4">Buscar por:</th>
	                                                </tr>
	                                                <tr  style="width: 100%;">
	                            						<td >RUC / CI:</td>
	                            						<td colspan="3">
	                            							<input id="identificadorFiltro" type="text" name="identificadorFiltro" value="" >
	                            						</td>
            
	                            					</tr>
	                                                <tr  style="width: 100%;">
	                            						<td >Razón Social: </td>
	                            						<td colspan="3">
	                            							<input id="razonSocialFiltro" type="text" name="razonSocialFiltro" value="" >
	                            						</td>
	                            					</tr>
	                                                <tr  style="width: 100%;">
                                						<td >Provincia: </td>
                                						<td colspan="3" >
                                							<select id="provinciaFiltro" name= "provinciaFiltro" style="width:185px;" >
                                                            <option value>Seleccione...</option>
                                                				' . $this->comboProvinciasEc() . '
                                                			</select>
                                						</td>
                                					</tr>
	                                                <tr  style="width: 100%;">
	                            						<td >Tipo operación: </td>
	                            						<td colspan="3">
                                                            <select id="tipoOperacionFiltro" name= "tipoOperacionFiltro" style="width:185px;" >
                                                                     ' . $tipoOperacion . '
                    			                             </select>
	                            						</td>
                                                                         
	                            					</tr>

                                                    <!--tr  style="width: 100%;">
	                            						<td >Estado: </td>
	                            						<td colspan="3">
                                                            <select id="estadoFiltro" name= "estadoFiltro" style="width:185px;" >
                                                                     ' . $tipoOperacion . '
                    			                             </select>
	                            						</td>
                                                                         
	                            					</tr-->

                            						<td colspan="4" style="text-align: end;">
                            							<button id="btnFiltrar">Filtrar lista</button>
                            						</td>
                            					</tr>
                            				</tbody>
                            			</table>';
    }

    /**
     * filtrar información
     */
    public function filtrarOperacionesOperador()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';

        $identificadorOperador = $_POST['identificadorOperador'];
        $razonSocial = $_POST["razonSocial"];
        $provincia = $_POST['provincia'];
        $area = $_POST['area'];
        $tipoOperacion = $_POST['tipoOperacion'];

        $arrayParametros = array(
            'identificador_operador' => $identificadorOperador,
            'razon_social' => $razonSocial,
            //'estado' => "in ('registrado','noHabilitado')",
            'provincia' => $provincia,
            'id_area' => $area,
            'codigo' => $tipoOperacion
        );

        $resultado = $this->lNegocioOperaciones->listarOperacionesXOperador($arrayParametros);

        $this->tablaHtmlOperaciones($resultado, $area);

        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);

        echo json_encode(array(
            'estado' => $estado,
            'mensaje' => $mensaje,
            'contenido' => $contenido
        ));
    }

    /**
     * Método de para cargar los tipos de operacion
     */
    public function cargarTipoOperacion()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';
        if (isset($_POST['identificador'])) {
            $arrayParametros = array(
                'identificador_operador' => $_POST['identificador'],
                'estado' => "in ('registrado','noHabilitado')",
                'provincia' => $_POST['provincia'],
                'id_area' => $_POST['area']
            );
        } else if (isset($_POST['razonSocial'])) {
            $arrayParametros = array(
                'razon_social' => $_POST['razonSocial'],
                'estado' => "in ('registrado','noHabilitado')",
                'provincia' => $_POST['provincia'],
                'id_area' => $_POST['area']
            );
        }
        $contenido = $this->comboTipoOperacion($arrayParametros);
        echo json_encode(array(
            'estado' => $estado,
            'mensaje' => $mensaje,
            'contenido' => $contenido
        ));
    }
}
