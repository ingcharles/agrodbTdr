<?php
/**
 * Controlador EntregaProductos
 *
 * Este archivo controla la lógica del negocio del modelo:  EntregaProductosModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-01-03
 * @uses    EntregaProductosControlador
 * @package RegistroEntregaProductos
 * @subpackage Controladores
 */
namespace Agrodb\RegistroEntregaProductos\Controladores;

use Agrodb\RegistroEntregaProductos\Modelos\EntregaProductosLogicaNegocio;
use Agrodb\RegistroEntregaProductos\Modelos\EntregaProductosModelo;

use Agrodb\RegistroEntregaProductos\Modelos\DistribucionProductosLogicaNegocio;
use Agrodb\RegistroEntregaProductos\Modelos\DistribucionProductosModelo;

use Agrodb\RegistroEntregaProductos\Modelos\BeneficiariosLogicaNegocio;
use Agrodb\RegistroEntregaProductos\Modelos\BeneficiariosModelo;

use Agrodb\Usuarios\Modelos\UsuariosExternosLogicaNegocio;
use Agrodb\Usuarios\Modelos\UsuariosExternosModelo;

use Agrodb\GUath\Modelos\FichaEmpleadoLogicaNegocio;
use Agrodb\GUath\Modelos\FichaEmpleadoModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class EntregaProductosControlador extends BaseControlador
{

    private $lNegocioEntregaProductos = null;
    private $modeloEntregaProductos = null;

    private $lNegocioDistribucionProductos = null;
    private $modeloDistribucionProductos = null;
    
    private $lNegocioBeneficiarios = null;
    private $modeloBeneficiarios = null;
    
    private $lNegocioUsuariosExternos = null;
    private $modeloUsuariosExternos = null;
    
    private $lNegocioFichaEmpleado = null;
    private $modeloFichaEmpleado = null;

    private $accion = null;
    private $formulario = null;
    private $rutaFecha = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioEntregaProductos = new EntregaProductosLogicaNegocio();
        $this->modeloEntregaProductos = new EntregaProductosModelo();

        $this->lNegocioDistribucionProductos = new DistribucionProductosLogicaNegocio();
        $this->modeloDistribucionProductos = new DistribucionProductosModelo();
        
        $this->lNegocioBeneficiarios = new BeneficiariosLogicaNegocio();
        $this->modeloBeneficiarios = new BeneficiariosModelo();
        
        $this->lNegocioUsuariosExternos = new UsuariosExternosLogicaNegocio();
        $this->modeloUsuariosExternos = new UsuariosExternosModelo();
        
        $this->lNegocioFichaEmpleado = new FichaEmpleadoLogicaNegocio();
        $this->modeloFichaEmpleado = new FichaEmpleadoModelo();
        
        $this->rutaFecha = date('Y').'/'.date('m').'/'.date('d');

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
        if($_SESSION['idLocalizacion'] == null){
            $usuarioExterno = $this->consultarUsuarioExterno($_SESSION['usuario']);
            
            $_SESSION['idProvincia'] = $usuarioExterno['id_provincia'];
            $_SESSION['nombreProvincia'] = $usuarioExterno['provincia'];
            $_SESSION['entidad'] = $usuarioExterno['entidad'];
        }else{
            $_SESSION['entidad'] = 'Agrocalidad';
        }

        $this->cargarPanelEntrega();
        
        $estado = "Activo";
        
        $modeloInventarioProductos = $this->lNegocioEntregaProductos->listarEntregasConDatos($estado, $_SESSION['idProvincia']);
        $this->tablaHtmlEntregaProductos($modeloInventarioProductos);
        
        require APP . 'RegistroEntregaProductos/vistas/listaEntregaProductosVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nueva Entrega de Productos";
        $this->formulario = "Nuevo";
        
        require APP . 'RegistroEntregaProductos/vistas/formularioEntregaProductosVista.php';
    }

    /**
     * Método para registrar en la base de datos -EntregaProductos
     */
    public function guardar()
    {
        $this->lNegocioEntregaProductos->guardar($_POST);
        
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: EntregaProductos
     */
    public function editar()
    {
        $this->accion = "Entrega de Productos";
        $this->formulario = "Editar";
        
        $this->modeloEntregaProductos = $this->lNegocioEntregaProductos->buscar($_POST["id"]);
        
        $beneficiario = $this->consultarBeneficiario($this->modeloEntregaProductos->getIdentificadorBeneficiario());
        require APP . 'RegistroEntregaProductos/vistas/formularioEntregaProductosVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - EntregaProductos
     */
    public function borrar()
    {
        $this->lNegocioEntregaProductos->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - EntregaProductos
     */
    public function tablaHtmlEntregaProductos($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_entrega'] . '"
                		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'RegistroEntregaProductos/entregaProductos"
                		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
                		  data-destino="detalleItem">
                		  <td>' . ++ $contador . '</td>
                		  <td style="white - space:nowrap; "><b>' . $fila['apellido'] .' '. $fila['nombre'] . '</b></td>
                            <td>' . $fila['producto'] . '</td>
                            <td>' . $fila['provincia'] . '</td>
                            <td>' . $fila['provincia_uso'] . '</td>
                            <td>' . $fila['lugar_uso'] . '</td>
                            <td>' . $fila['cantidad_entrega'] . $fila['unidad'] . '</td>
                     </tr>'
                );
            }
        }
    }
    
    /**
     * Construye el código HTML para desplegar panel de busqueda para Distribuciones
     */
    public function cargarPanelEntrega()
    {
        
        $this->panelBusquedaEntrega = '<table class="filtro" style="width: 450px;">
                                                <tbody  style="width: 100%;">
                                                    <tr>
                                                        <th >Consultar productos:</th>
                                                    </tr>
            
                                					<tr  style="width: 100%;">
                                						<td >*Producto: </td>
                                						<td colspan=3 >
                                							<select id="idProductoEntrega" name="idProductoEntrega" style="width: 97%;" required>
                                                                <option>Seleccione....</option>' 
                                							    . $this->comboProductosDistribucion($_SESSION['nombreProvincia'], $_SESSION['entidad']). 
                                							'</select>
                                						</td>
                                					</tr>

                                                    <tr  style="width: 100%;">
                                						<td >Provincia entrega: </td>
                                						<td colspan=3 >
                                							<select id="idProvinciaEntrega" name="idProvinciaEntrega" style="width: 97%;" >
                                                                <option>Seleccione....</option>
                                                                ' . $this->comboProvinciasEc($_SESSION['idProvincia']) . 
                                                            '</select>
                                						</td>
                                					</tr>
                                                    
                                                    <tr  style="width: 100%;">
                                						<td >Entidad: </td>
                                						<td colspan=3 >
                                                            <input type="text" id="entidadEntrega" name="entidadEntrega" value="'.$_SESSION['entidad'].'" readonly="readonly" style="width: 97%" maxlength="13">
                                						</td>
                                					</tr>
                                                                    
                                                    <tr  style="width: 100%;">
                                						<td >Beneficiario: </td>
                                						<td colspan=3 >
                                							<input id="idBeneficiarioEntrega" type="text" name="idBeneficiarioEntrega" style="width: 97%" maxlength="13">
                                						</td>
                                					</tr>
                                                                
                                                    <tr>
                                						<td colspan="2" style="text-align: end;">
                                							<button id="btnFiltrar">Consultar</button>
                                						</td>
                                					</tr>
                                				</tbody>
                                			</table>';
    }
    
    /**
     * Combo de tipos de uso (individual/asociación)
     *
     * @param
     * $respuesta
     * @return string
     */
    public function comboTiposUsoEntrega($opcion = null)
    {
        $combo = "";
        if ($opcion == "Individual") {
            $combo .= '<option value="Individual" selected="selected">Individual</option>';
            $combo .= '<option value="Asociación">Asociación</option>';
        } else if ($opcion == "Asociación") {
            $combo .= '<option value="Individual" >Individual</option>';
            $combo .= '<option value="Asociación" selected="selected">Asociación</option>';
        } else {
            $combo .= '<option value="Individual">Individual</option>';
            $combo .= '<option value="Asociación">Asociación</option>';
        }
        
        return $combo;
    }
    
    /**
     * Combo de entidades
     *
     * @param
     * $respuesta
     * @return string
     */
    public function comboEntidades($opcion = null)
    {
        $combo = "";
        if ($opcion == "Agrocalidad") {
            $combo .= '<option value="Agrocalidad" selected="selected">Agrocalidad</option>';
            $combo .= '<option value="MAG">Ministerio de Agricultura y Ganadería</option>';
        } else if ($opcion == "MAG") {
            $combo .= '<option value="Agrocalidad" >Agrocalidad</option>';
            $combo .= '<option value="MAG" selected="selected">Ministerio de Agricultura y Ganadería</option>';
        } else {
            $combo .= '<option value="Agrocalidad">Agrocalidad</option>';
            $combo .= '<option value="MAG">Ministerio de Agricultura y Ganadería</option>';
        }
        
        return $combo;
    }
    
    /**
     * Consulta los productos de distribución del inventario y construye el combo
     *
     * @param Integer $idProductoDistribucion
     * @return string
     */
    public function comboProductosDistribucion($provincia, $entidad, $idProducto = null)
    {
        $productos = "";
        
        $combo = $this->lNegocioDistribucionProductos->buscarDistribucionDisponibleXProvinciaEntidad($provincia, $entidad);
        
        foreach ($combo as $item)
        {
            if ($idProducto == $item['id_producto'])
            {
                $productos .= '<option value="' . $item->id_producto . '" data-cantidad="' . $item->cantidad . '" selected>' . $item->producto . '</option>';
            } else
            {
                $productos .= '<option value="' . $item->id_producto . '" data-cantidad="' . $item->cantidad . '">' . $item->producto . '</option>';
            }
        }
        return $productos;
    }
    
    /**
     * Consulta los productos de distribución y construye el combo
     *
     * @param Integer $idProductoDistribucion
     * @return string
     */
    public function comboProductosDistribucionActualizado()
    {
        $provincia = $_SESSION['nombreProvincia'];
        $entidad = $_SESSION['entidad'];
        
        $productos = "<option value=''>Seleccionar....</option>";
        
        $combo = $this->lNegocioDistribucionProductos->buscarDistribucionDisponibleXProvinciaEntidad($provincia, $entidad);
        
        foreach ($combo as $item)
        {
            $productos .= '<option value="' . $item->id_producto . '" data-cantidad="' . $item->cantidad . '">' . $item->producto . '</option>';
        }
        
        echo $productos;
        exit();
    } 
    
    /**
     * Método para listar las entregas registradas
     */
    public function construirEntregas($identificador)
    {
        $query = "identificador_beneficiario='$identificador' and estado='Activo' ORDER BY fecha_creacion, provincia_uso ASC"; 
        
        $entregas = $this->lNegocioEntregaProductos->buscarLista($query);
        
        $this->listaEntregas = '<table style="width: 100%;">
                                        <thead>
                                            <tr style="width: 100%;">
                                                <th>Fecha</th>
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Provincia/Cantón/Parroquia</th>
                                                <th>Lugar</th>
                                            </tr>
                                        </thead>';
        
        foreach ($entregas as $fila) {
            
            $this->listaEntregas .=
            '<tr>
                        <td style="width: 15%;">' . date('Y-m-d',strtotime($fila['fecha_creacion'])). '</td>
                        <td style="width: 20%;">' . ($fila['producto'] != '' ? $fila['producto'] : '') . '</td>
                        <td style="width: 10%;">' . ($fila['cantidad_entrega'] != '' ? $fila['cantidad_entrega'] : '') . '</td>
                        <td style="width: 35%;">' . ($fila['provincia_uso'] != '' ? $fila['provincia_uso'] : 'NA') .' / '.($fila['canton_uso'] != 'NA' ? $fila['canton_uso'] : 'NA') .' / '.($fila['parroquia_uso'] != '' ? $fila['parroquia_uso'] : 'NA') . '</td>
                        <td style="width: 20%;">' . ($fila['lugar_uso'] != '' ? $fila['lugar_uso'] : '') . '</td>
                    </tr>';
        }
        
        $this->listaEntregas .= '</table>';
        
        echo $this->listaEntregas;
    }
    
    /**
     * Método para agregar un registro y una fila en el grid de entregas
     */
    public function agregarEntrega()
    {
        $estado = 'exito';
        $mensaje = 'Registro agregado con éxito';
        $contenido = '';
        
        //Revisar el beneficiario
        $beneficiario = $this->consultarBeneficiario($_POST['identificador_beneficiario']);
        
        if($beneficiario==null){
            $arrayBeneficiario = array(
                'identificador' => $_POST['identificador_beneficiario'],
                'nombre' => $_POST['nombre_beneficiario'],
                'apellido' => $_POST['apellido_beneficiario'],
                'direccion' => $_POST['direccion_beneficiario'],
                'telefono' => $_POST['telefono_beneficiario'],
                'correo_electronico' => $_POST['correo_electronico_beneficiario']
            );
            
            $this->lNegocioBeneficiarios->guardar($arrayBeneficiario);
        }
        
        //Buscar cantidad disponible del producto en las distribuciones por provincia
        $distribucionProducto = $this->lNegocioDistribucionProductos->buscarCantidadDisponibleXProductoProvinciaEntidad($_POST['id_producto'], $_SESSION['nombreProvincia'], $_SESSION['entidad']);
        
        if ($_POST['cantidad_entrega'] <= $distribucionProducto->current()->cantidad ){
            
            $idRegistro = $this->lNegocioEntregaProductos->guardar($_POST);
            
            //Una vez guardado el registro de asignación se reduce el valor de la distribución del producto en la provincia y entidad
            $arrayParametros = array(
                'id_producto' => $_POST['id_producto'],
                'provincia' => $_SESSION['nombreProvincia'],
                'entidad' => $_SESSION['entidad'],
                'cantidad' => $_POST['cantidad_entrega']
            );
            
            $this->lNegocioDistribucionProductos->disminuirCantidadProductosDistribucion($arrayParametros);
            
            //Impresión en el grid del registro creado
            $contenido .= "<tr id=" . $idRegistro . ">
                                <td>" . $_POST['provincia_uso'] . "/" . $_POST['canton_uso'] . "/" . $_POST['parroquia_uso'] . "</td>
                                <td>" . $_POST['producto'] . "</td>
                                <td>" . $_POST['cantidad_entrega'] . "
                                    <input id='iEntrega' name='iEntrega[]' value='".$idRegistro."' type='hidden'>
                                </td>
                                <td class='borrar'>
                                    <button type='button' class='icono' name='eliminar' id='eliminar' onclick='quitarProductos(".$idRegistro."); return false;'/>
                                </td>
                           </tr>";
        }else{
            $estado = 'error';
            $mensaje = 'No se dispone de la cantidad requerida del producto para la entrega';
            $contenido = '';
        }
        
        echo json_encode(array('estado' => $estado, 'mensaje' => $mensaje, 'contenido' => $contenido));
    }
    
    /**
     * Método para eliminar un registro en el grid de entregas y devolver el producto a la distribución
     */
    public function eliminarEntrega()
    {
        //Buscar registro de entrega con ID
        $entrega = $this->lNegocioEntregaProductos->buscar($_POST['idEntrega']);
        
        //Crear nuevo registro de distribución del Producto
        $arrayParametros = array(
            'id_producto' => $entrega->idProducto,
            'producto' => $entrega->producto,
            'entidad' => $entrega->institucion,
            'id_provincia' => $entrega->idProvincia,
            'provincia' => $entrega->provincia,
            'cantidad_asignada' => $entrega->cantidadEntrega,
            'cantidad_disponible' => $entrega->cantidadEntrega,
            'tipo_registro' => 'devolucion'
        );
        
        $this->lNegocioDistribucionProductos->guardar($arrayParametros);
        
        //Eliminar el registro actual
        $this->lNegocioEntregaProductos->borrar($_POST['idEntrega']);
    }
    
    /**
     * Método para listar la información del usuario logueado
     */
    public function consultarUsuarioExterno($identificador)
    {
        $consulta = "identificador='".$identificador."' and estado='activo'";
        
        $usuario = $this->lNegocioUsuariosExternos->buscarLista($consulta);
        
        $fila = $usuario->current();
        
        $usuario = array('identificador' => $fila->identificador,
                         'institucion' => $fila->institucion,
                         'entidad' => $fila->entidad,
                         'id_provincia' => $fila->id_provincia,
                         'provincia' => $fila->provincia,
                         'nombre' => $fila->nombre,
                         'apellido' => $fila->apellido
        );
        
        return $usuario;
    }
    
    /**
     * Método para listar la información del usuario logueado
     */
    public function consultarBeneficiario($identificador)
    {
        $consulta = "identificador='".$identificador."'";
        
        $usu = $this->lNegocioBeneficiarios->buscarLista($consulta);
        
        if(count($usu) > 0 && $usu != ''){
            $fila = $usu->current();
            
            $usuario = array(   'identificador' => $fila->identificador,
                                'nombre' => $fila->nombre,
                                'apellido' => $fila->apellido,
                                'direccion' => $fila->direccion,
                                'telefono' => $fila->telefono,
                                'correo' => $fila->correo_electronico
            );
        }else{
            $usuario = null;
        }
        
        return $usuario;
    }
    
    /**
     * Método para generar la numeración de los certificados
     */
    public function generarCodigoCertificadoEntrega($idProvincia)
    {        
        return $this->lNegocioEntregaProductos->buscarNumeroCertificado($idProvincia);
    }
    
    /**
     * Método para desplegar el certificado PDF
     */
    public function mostrarReporte()
    {
        $this->urlPdf = $_POST['id'];
        require APP . 'RegistroEntregaProductos/vistas/visorPDF.php';
    }
    
    /**
     * Función para generar el certificado
     */
    public function generarCertificadoEntrega()
    {
        $estado = 'exito';
        $mensaje = 'Certificado generado con éxito';
        $contenido = '';
        
        $anio = date('Y');
        $mes = date('m');
        $dia = date('d');
        
        if (count($_POST['iEntrega'])>0) {//cambiar variable a la de validacion de existencia
            
            //crear array de parametros y enviar para el reporte
            $codigoCertificado = $this->generarCodigoCertificadoEntrega($_SESSION['idProvincia']);
            
            //buscar los datos del usuario MAG o AGRO
            if($_SESSION['idLocalizacion'] == null){
                $tecnico = $this->consultarUsuarioExterno($_SESSION['usuario']);
            }else{
                //Información de ficha de empleado
                $query = "identificador='".$_SESSION['usuario']."'";
                $usuarioInterno = $this->lNegocioFichaEmpleado->buscarLista($query);
                $fila = $usuarioInterno->current();
                
                $tecnico = array(   'identificador' => $_SESSION['usuario'],
                                    'id_provincia' => $_SESSION['idProvincia'],
                                    'provincia' => $_SESSION['nombreProvincia'],
                                    'nombre' => $fila->nombre,
                                    'apellido' => $fila->apellido
                                );
            }
            //print_r($tecnico);
            $this->lNegocioEntregaProductos->generarCertificado($_POST['identificador_benef'], $codigoCertificado, $tecnico);
            
            $contenido = ENT_PROD_CERT_URL . "certificado/" . $anio . "/" . $mes . "/" . $dia . "/" . $_POST['identificador_benef']."-".$codigoCertificado . ".pdf";
            
            //Cambiar el estado de los registros de entrega
            foreach ($_POST['iEntrega'] as $fila) {
                $arrayParametros = array(
                    'id_entrega' => $fila,
                    'certificado' => 'SI',
                    'ruta_archivo' => $contenido,
                    'tipo_uso' =>  $_POST['tipo_uso'],
                    'numero_certificado' =>  substr($codigoCertificado, -5),
                    'ruta_certificado_firmado' => $_POST['certificado']
                );
                
                $this->lNegocioEntregaProductos->guardar($arrayParametros);
            }
            
        } else {
            $mensaje = 'No se pudo generar el certificado';
            $estado = 'FALLO';
        }
        
        echo json_encode(array('estado' => $estado, 'mensaje' => $mensaje, 'contenido' => $contenido));
    }
    
    /**
     * Método para listar los inventarios registrados
     */
    public function listarEntregasFiltradas()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';
        
        $idProductoEntrega = $_POST["idProductoEntrega"];
        $idProvinciaEntrega = $_POST["idProvinciaEntrega"];
        $entidadEntrega = $_POST["entidadEntrega"];
        $idBeneficiarioEntrega = $_POST["idBeneficiarioEntrega"];
        
        $arrayParametros = array(
            'id_producto' => $idProductoEntrega,
            'id_provincia' => $idProvinciaEntrega,
            'institucion' => $entidadEntrega,
            'identificador_beneficiario' => $idBeneficiarioEntrega
        );
        
        $entregas = $this->lNegocioEntregaProductos->buscarEntregasXFiltro($arrayParametros);
        
        $this->tablaHtmlEntregaProductos($entregas);
        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
        
        echo json_encode(array(
            'estado' => $estado,
            'mensaje' => $mensaje,
            'contenido' => $contenido
        ));
    }
    
    /**
     * Método para eliminar un registro en el grid de entregas y devolver el producto a la distribución
     */
    public function agregarCertificado()
    {
        $estado = 'exito';
        $mensaje = 'Certificado guardado con éxito';
        $contenido = '';
        
        if ($_POST['ruta_certificado_firmado']!='0') {
            $this->lNegocioEntregaProductos->guardar($_POST);
        } else {
            $mensaje = 'No se pudo guardar el certificado';
            $estado = 'FALLO';
        }
        
        echo json_encode(array('estado' => $estado, 'mensaje' => $mensaje, 'contenido' => $contenido));
    }
}