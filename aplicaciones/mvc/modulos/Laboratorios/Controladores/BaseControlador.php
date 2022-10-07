<?php

/**
 * Controlador Base
 *
 * Este archivo contiene métodos comunes para todos los controladores 
 *
 * @author DATASTAR
 * @uses     ControladorBase
 * @package Laboratorios
 * @subpackage Controladores
 */

namespace Agrodb\Laboratorios\Controladores;

session_start();

use Agrodb\Catalogos\Modelos\EntidadesbancariasLogicaNegocio;
use Agrodb\Catalogos\Modelos\CuentasbancariasLogicaNegocio;
use Agrodb\Laboratorios\Modelos\ServiciosLogicaNegocio;
use Agrodb\Laboratorios\Modelos\LaboratoriosProvinciaLogicaNegocio;
use Agrodb\Laboratorios\Modelos\ArchivoInformeAnalisisLogicaNegocio;
use Agrodb\Laboratorios\Modelos\UsuarioLaboratorioLogicaNegocio;
use Agrodb\Laboratorios\Modelos\InformesLogicaNegocio;
use Agrodb\Laboratorios\Modelos\RecepcionMuestrasLogicaNegocio;
use Agrodb\Catalogos\Modelos\CatalogosLaboratoriosLogicaNegocio as CatalogosLab;
use Agrodb\Core\Comun;
use Agrodb\Core\Mensajes;
use Agrodb\Core\Constantes;

class BaseControlador extends Comun
{

    public $itemsFiltrados = array();
    public $codigoJS = null;
    public $modeloRecepcionMuestras = null;

    public function __construct()
    {
        parent::usuarioActivo();
        //Si se requiere agregar código concatenar la nueva cadena con \n ejemplo $this->codigoJS.="\nalert('hola');";
        $this->codigoJS = \Agrodb\Core\Mensajes::limpiar();
        
       
    }

    /**
     * Retorna el id del laboratorio que le corresponde al usuario
     * @return type
     */
    public function laboratorioUsuario()
    {
        $lNusuarioLaboratorio = new UsuarioLaboratorioLogicaNegocio();
        $buscaUsuarioLaboratorio = $lNusuarioLaboratorio->buscarUsuarioLaboratorio(array('identificador' => $this->identificador));
        $fila = $buscaUsuarioLaboratorio->current();
        if ($fila)
        {
            return $fila->id_laboratorio;
        } else
        {
            echo Mensajes::fallo(Constantes::INF_USUARIO_LABORATORIO);
            exit();
        }
    }

    /**
     * Construye el combo que contiene los laboratorios en provinci del usuario o
     * construye un campo oculto que contiene el único laboratorio del usuario
     * @param type $idLaboratoriosProvincia     para identificar la opción seleccionada en caso de combo
     * @param type $atributo   enviar la palabra 'requerido' si por ejemplo se usa en un formulario en caso de combo
     * y se desea que sea requerido
     * @return string
     */
    public function laboratoriosProvinciaUsuario($idLaboratoriosProvincia = null, $atributo = null)
    {
        $lNusuarioLaboratorio = new UsuarioLaboratorioLogicaNegocio();
        $buscaUsuarioLaboratorio = $lNusuarioLaboratorio->buscarLaboratoriosProvinciaUsuario($this->identificador);
        $html = "";
        if (count($buscaUsuarioLaboratorio) > 1)
        {
            $html.= "<div data-linea='1'>";
            $html.= "<label for='id_laboratorios_provincia'>Laboratorio</label>";
            $html.= "<select id='id_laboratorios_provincia' name='id_laboratorios_provincia' $atributo>";
            $html.= "<option value=''>Seleccione...</option>";

            foreach ($buscaUsuarioLaboratorio as $fila)
            {
                $html.= '<option value="' . $fila->id_laboratorios_provincia . '" data-id="' . $fila->id_laboratorio . '"';
                if ($idLaboratoriosProvincia == $fila->id_laboratorios_provincia)
                {
                    $html.= " selected";
                }
                $html.='>' . "$fila->prov_laboratorio - $fila->laboratorio" . '</option>';
            }
            $html.="</select>";
            $html.="</div>";
        } else if (count($buscaUsuarioLaboratorio) == 1)
        {
            $fila = $buscaUsuarioLaboratorio->current();
            $html.= "<input type='hidden' id='id_laboratorios_provincia' name='id_laboratorios_provincia' value='$fila->id_laboratorios_provincia' data-id='$fila->id_laboratorio'/>";
        } else
        {
            $html.= "Error! No tiene asignado el Laboratorio";
        }
        return $html;
    }

    /**
     * Construye el combo que contiene los laboratorios por provincia solo principales
     * Ejm: Los laboratorios de Suelos, Aguas y Foliares, el principal es Suelos 
     * laboratorios_provincia.bodega_laboratorios contiene el id del principal
     */
    public function laboratoriosProvinciaPorDireccion($idDireccion)
    {
        $lNLaboratoriosProvincia = new LaboratoriosProvinciaLogicaNegocio();
        $buscaLaboratorios = $lNLaboratoriosProvincia->buscarLaboratoriosProvinciaPrincipal($this->identificador, array('idDireccion' => $idDireccion));
        $html = "<option value=''>Seleccione...</option>";
        foreach ($buscaLaboratorios as $fila)
        {
            $html.= '<option value="' . $fila->id_laboratorios_provincia . '" data-id="' . $fila->id_laboratorio . '">' . "$fila->nombre_provincia - $fila->nombre_laboratorio" . '</option>';
        }
        echo $html;
    }

    /**
     * Para crear botones extras
     * @return type
     */
    public function crearAccionBotones()
    {

        $botonesExtras = new \Agrodb\Laboratorios\Controladores\BotonesExtras();
        if ($botonesExtras->getVerMenuPrincipal())
        {
            $menu = parent::crearAccionBotones();
            return $menu . $botonesExtras->getHtml();
        } else
        {
            return $botonesExtras->getHtml();
        }
    }

    /**
     * Combo con los informes- indicando el nombre del cliente
     * @return string
     */
    public function comboClientesInforme()
    {
        $informe = new ArchivoInformeAnalisisLogicaNegocio();
        $opcionesHtml = "";
        $combo = $informe->buscarClientesInforme($this->laboratorioUsuario());

        foreach ($combo as $item)
        {
            $opcionesHtml .= '<option value="' . $item->id_archivo_informe_analisis . '">' . $item->nombre_informe . '</option>';
        }
        return $opcionesHtml;
    }

    /**
     * Construye combo html para el Cronograma Postregistro
     * @param type $idLaboratorio
     * @return string
     */
    public function comboCronogramaPostregistro($idLaboratorio)
    {
        $cronograma = new \Agrodb\Laboratorios\Modelos\CronogramaPostregistroLogicaNegocio;
        $opcionesHtml = "";
        $combo = $cronograma->buscarCronogramaUsuario($idLaboratorio);
        foreach ($combo as $fila)
        {
            $ingredientes = explode(";", $fila->ingrediente_activo);
            $opcionesHtml .='<option>Seleccione...</option>';
            foreach ($ingredientes as $item)
            {
                $opcionesHtml .= '<option value="' . $item . '">' . $item . '</option>';
            }
        }
        return $opcionesHtml;
    }

    /**
     * Combo con las provincias que esta un laboratorio
     * @param type $idLaboratorio
     */
    public function comboLaboratoriosProvincia($idLaboratorio)
    {
        $laboratorios = new LaboratoriosProvinciaLogicaNegocio();
        $opcionesHtml = "";
        $combo = $laboratorios->buscarLaboratoriosProvincia(array('idLaboratorio' => $idLaboratorio));
        $opcionesHtml .= '<option value="">Seleccionar....</option>';
        foreach ($combo as $item)
        {
            $opcionesHtml .= '<option value="' . $item->id_laboratorios_provincia . '">' . $item->nombre_provincia . ' (' . $item->tipo . ') </option>';
        }
        echo $opcionesHtml;
    }
    
    /**
     * Combo con las provincias que esta un laboratorio
     * @param type $idLaboratorio
     */
    public function comboLaboratoriosProvincia2($idLaboratorio)
    {
        $laboratorios = new LaboratoriosProvinciaLogicaNegocio();
        $opcionesHtml = "";
        $combo = $laboratorios->buscarLaboratoriosProvincia(array('idLaboratorio' => $idLaboratorio));
        $opcionesHtml .= '<option value="">TODOS</option>';
        foreach ($combo as $item)
        {
            $opcionesHtml .= '<option value="' . $item->id_laboratorios_provincia . '">' . $item->nombre_provincia . ' (' . $item->tipo . ') </option>';
        }
        echo $opcionesHtml;
    }

    /**
     * Construye el combo de servicios segun el laboratorio seleccionado
     * Este comobo es usado en la solicitud para que muestre además con el valor del tarifario
     *
     * @param Integer $idLaboratorio
     * @return string Código html para llenar el combo de servicios mediante ajax
     */
    public function comboServicios($idLaboratorio)
    {
        $servicios = new ServiciosLogicaNegocio();
        $opcionesHtml = "";
        $combo = $servicios->buscarServiciosJoinGuia($idLaboratorio);
        $opcionesHtml .= '<option value="">Seleccionar....</option>';
        foreach ($combo as $item)
        {
            // buscar por cada item si tiene analisis hijos
            $objAnalisis = new ServiciosLogicaNegocio();
            $analisis = $objAnalisis->buscarAnalisis($item['id_servicio']);
            if (count($analisis) > 0)
            { //tiene varias opciones
                $value = "id:{$item['id_servicio']}/hijos:varias/valor:{$item['valor']}"; //varias opciones
                $opcionesHtml .= '<option data-id="' . $value . '" value="' . $item['id_servicio'] . '">' . $item['nombre'] . '</option>';
            } else
            {
                $value = "id:{$item['id_servicio']}/hijos:ninguna/valor:{$item['valor']}";
                $opcionesHtml .= '<option data-id="' . $value . '" value="' . $item['id_servicio'] . '">' . $item['nombre'] . '</option>';
            }
        }
        echo $opcionesHtml;
        exit();
    }

    /**
     * Construye un combo segun el servicio padre selecionado
     * Usado en la solicitud para análisis/procedimiento/muestra
     * @param type $fkIdServicio
     */
    public function comboServicio($fkIdServicio)
    {
        $servicios = new ServiciosLogicaNegocio();
        $opcionesHtml = "";
        $combo = $servicios->buscarServiciosHijos($fkIdServicio);
        $opcionesHtml .= '<option value="">Seleccionar....</option>';
        foreach ($combo as $item)
        {
            // buscar por cada item si tiene mas de un registro
            $obj = new ServiciosLogicaNegocio();
            $servicios = $obj->buscarServiciosHijos($item->id_servicio);
            if (count($servicios) > 0)
            { //tiene varias opciones
                $value = "id:$item->id_servicio/hijos:varias"; //varias opciones
                $opcionesHtml .= '<option data-id="' . $value . '" value="' . $item->id_servicio . '">' . $item->nombre . '</option>';
            } else
            {
                $value = "id:$item->id_servicio/hijos:ninguna";
                $opcionesHtml .= '<option data-id="' . $value . '" value="' . $item->id_servicio . '">' . $item->nombre . '</option>';
            }
        }
        echo $opcionesHtml;
        exit();
    }

    /**
     * Busca los servicios directamente en la base de laboratorios
     * @param type $idLaboratorio
     */
    public function comboServiciosSinJoinGuia($idLaboratorio)
    {
        $servicios = new ServiciosLogicaNegocio();
        $combo = $servicios->buscarServicios($idLaboratorio);
        $opcionesHtml = "";
        $opcionesHtml .= '<option value="">Seleccionar....</option>';
        foreach ($combo as $item)
        {
            $opcionesHtml .= '<option value="' . $item->id_servicio . '">' . $item->nombre . '</option>';
        }
        echo $opcionesHtml;
    }

    /**
     * Consulta los laboratorios de una dirección seleccionada y construye el combo
     *
     * @param Integer $idLaboratorio
     * @return string Código html para llenar el combo de servicios mediante ajax
     */
    public function comboServiciosSimple($idLaboratorio)
    {
        $servicios = new ServiciosLogicaNegocio();
        $opcionesHtml = "";
        $combo = $servicios->buscarServiciosJoinGuia($idLaboratorio);
        $opcionesHtml .= '<option value="">Seleccionar....</option>';
        foreach ($combo as $item)
        {
            $opcionesHtml .= '<option value="' . $item['id_servicio'] . '">' . $item['nombre'] . '</option>';
        }
        echo $opcionesHtml;
        exit();
    }

    /**
     * Consulta los Bancos para pagar y construye el combo
     *
     * @param 
     * @return string
     */
    public function comboEntidadesBancarias($idBanco = null)
    {
        $entidadBancaria = new EntidadesbancariasLogicaNegocio();
        $bancos = "";
        $combo = $entidadBancaria->buscarEntidadesBancarias();

        foreach ($combo as $item)
        {
            if ($idBanco == $item['id_banco'])
            {
                $bancos .= '<option value="' . $item->id_banco . '" selected>' . $item->nombre . '</option>';
            } else
            {
                $bancos .= '<option value="' . $item->id_banco . '">' . $item->nombre . '</option>';
            }
        }
        return $bancos;
    }

    /**
     * Configura si un campo del formulario es visible para  usuarios internos/externos
     * 0->Todos 1->Todos los usuarios internos.
     * @param type $idRol
     * @return string
     */
    public function comboNivelAcceso($respuesta = null)
    {
        $combo = "";
        $opt = array('0' => 'Todos', '1' => 'Únicamente usuarios internos', '2' => 'Únicamente usuarios externos');
        foreach ($opt as $key => $value)
        {
            $combo.= "<option value=\"$key\"";
            if ($respuesta == $key)
            {
                $combo.= " selected";
            }
            $combo.= ">$value</option>";
        }
        return $combo;
    }

    /**
     * Consulta las cuentas bancarias para pagar y construye el combo
     *
     * @param 
     * @return string
     */
    public function comboCuentasBancarias($idBanco, $idCuentaBancaria = null)
    {
        $cuentaBancaria = new CuentasbancariasLogicaNegocio();
        $opcionesHtml = "";
        $combo = $cuentaBancaria->buscarCuentasBancarias($idBanco);
        $opcionesHtml .= '<option value="">Seleccionar....</option>';
        foreach ($combo as $item)
        {
            $opcionesHtml .= '<option value="' . $item->id_cuenta_bancaria . '">' . $item->numero_cuenta . '</option>';
        }
        echo $opcionesHtml;
        exit();
    }

    /**
     * Crea un combo con las opciones SI/NO
     *
     * @return string - Vista el cÃ³digo html para desplegar los botones
     */
    public function crearComboSINO($respuesta = null)
    {
        $combo = "";
        if ($respuesta == "SI")
        {
            $combo .= '<option value="SI" selected>SI</option>';
            $combo .= '<option value="NO" >NO</option>';
        } else if ($respuesta == "NO")
        {
            $combo .= '<option value="SI" >SI</option>';
            $combo .= '<option value="NO" selected>NO</option>';
        } else
        {
            $combo .= '<option value="SI" >SI</option>';
            $combo .= '<option value="NO">NO</option>';
        }
        return $combo;
    }

    /**
     * Llena un combo para el tipo de desliegue de los campos de resultados del análisis
     * @param type $respuesta
     * @return string
     */
    public function comboDespliegue($respuesta = null)
    {
        $combo = "";
        if ($respuesta == "HORIZONTAL")
        {
            $combo .= '<option value="HORIZONTAL" selected>HORIZONTAL</option>';
            $combo .= '<option value="VERTICAL" >VERTICAL</option>';
        } else if ($respuesta == "VERTICAL")
        {
            $combo .= '<option value="HORIZONTAL" >HORIZONTAL</option>';
            $combo .= '<option value="VERTICAL" selected>VERTICAL</option>';
        } else
        {
            $combo .= '<option value="HORIZONTAL" >HORIZONTAL</option>';
            $combo .= '<option value="VERTICAL" >VERTICAL</option>';
        }
        return $combo;
    }

    /**
     * Construye combo de tipo de usuario
     * @param type $respuesta
     * @return string
     */
    public function tipoUsuario($respuesta = null)
    {
        $combo = "";
        if ($respuesta == "TODOS")
        {
            $combo .= '<option value="TODOS" selected>TODOS</option>';
            $combo .= '<option value="INTERNO" >INTERNO</option>';
            $combo .= '<option value="EXTERNO" >EXTERNO</option>';
        } else if ($respuesta == "EXTERNO")
        {
            $combo .= '<option value="EXTERNO" selected>EXTERNO</option>';
            $combo .= '<option value="INTERNO" >INTERNO</option>';
            $combo .= '<option value="TODOS" >TODOS</option>';
        } else if ($respuesta == "INTERNO")
        {
            $combo .= '<option value="EXTERNO" >EXTERNO</option>';
            $combo .= '<option value="INTERNO" selected>INTERNO</option>';
            $combo .= '<option value="TODOS" >TODOS</option>';
        } else
        {

            $combo .= '<option value="EXTERNO" >EXTERNO</option>';
            $combo .= '<option value="INTERNO" >INTERNO</option>';
            $combo .= '<option value="TODOS" selected >TODOS</option>';
        }
        return $combo;
    }

    /**
     * Construye combo para muestras
     * @param type $respuesta
     * @return string
     */
    public function operadorComparacion($respuesta = null)
    {
        $combo = "";
        if ($respuesta == "#=")
        {
            $combo .= '<option value="#>"># muestras mayor ></option>';
            $combo .= '<option value="#<" ># muestras menor< </option>';
            $combo .= '<option value="#>="># muestras mayor igual>=</option>';
            $combo .= '<option value="#<="># muestras menor igual <=</option>';
            $combo .= '<option value="#=" selected># muestras igual =</option>';
        } else if ($respuesta == "#<")
        {
            $combo .= '<option value="#>"> # muestras mayor></option>';
            $combo .= '<option value="#<" selected># muestras menor< </option>';
            $combo .= '<option value="#>="># muestras mayor igual >=</option>';
            $combo .= '<option value="#<=" ># muestras menor igual <=</option>';
            $combo .= '<option value="#="># muestras igual =</option>';
        } else if ($respuesta == "#>=")
        {
            $combo .= '<option value="#>"># muestras mayor ></option>';
            $combo .= '<option value="#<"># muestras menor <</option>';
            $combo .= '<option value="#>="># muestras mayor igual >=</option>';
            $combo .= '<option value="#<="># muestras menor igual <=</option>';
            $combo .= '<option value="#="># muestras igual =</option>';
        } else if ($respuesta == "#<=")
        {
            $combo .= '<option value="#>"># muestras mayor ></option>';
            $combo .= '<option value="#<"># muestras menor <</option>';
            $combo .= '<option value="#>="># muestras mayor igual >=</option>';
            $combo .= '<option value="#<="selected># muestras menor igual <=</option>';
            $combo .= '<option value="#="># muestras igual =</option>';
        } else
        {
            $combo .= '<option value="#>" selected># muestras mayor  > </option>';
            $combo .= '<option value="#<"># muestras menor  < </option>';
            $combo .= '<option value="#>="># muestras mayor igual >= </option>';
            $combo .= '<option value="#<="># muestras menor igual <= </option>';
            $combo .= '<option value="#="># muestras igual = </option>';
        }
        return $combo;
    }

    /**
     * Crea un combo con las opciones de VERTICAL / HORIZONTAL
     *
     * @return string - Vista el cÓdigo HTML 
     */
    public function comboOrientacion2($respuesta)
    {
        $combo = "";
        if ($respuesta == "H")
        {
            $combo .= '<option value="H" >HORIZONTAL</option>';
            $combo .= '<option value="V" selected>VERTICAL</option>';
        } else
        {
            $combo .= '<option value="V" selected>VERTICAL</option>';
            $combo .= '<option value="H" >HORIZONTAL</option>';
        }
        return $combo;
    }

    /**
     * combo tipo de laboratorio en provincia
     * @param type $respuesta
     * @return string
     */
    public function tipoLaboratorio($respuesta)
    {
        $combo = "";
        $opt = array('LN' => 'Laboratorio Nacional', 'LDR' => 'Laboratorios de diagnóstico rápido', 'LR' => 'Laboratorios Regionales');
        foreach ($opt as $key => $value)
        {
            $combo.= "<option value=\"$key\"";
            if ($respuesta == $key)
            {
                $combo.= " selected";
            }
            $combo.= ">$value</option>";
        }
        return $combo;
    }

    /**
     * Tipo de solcitud: Registro  o Postregistro
     * @param type $respuesta
     * @return type
     */
    public function tipoSolicitudRP($respuesta)
    {
        $combo = "";
        if ($this->usuarioInterno)
        {
            $opt = array('POSTREGISTRO' => 'POST-REGISTRO', 'OTROS' => 'OTROS');
        } else
        {
            $opt = array('REGISTRO' => 'REGISTRO', 'OTROS' => 'OTROS');
        }
        foreach ($opt as $key => $value)
        {
            $combo.= "<option value=\"$key\"";
            if ($respuesta == $key)
            {
                $combo.= " selected";
            }
            $combo.= ">$value</option>";
        }
        return $combo;
    }

    /**
     * Construye combo de tipo de campo para el informe
     * @param type $respuesta
     * @return string
     */
    public function tipoCampoInforme($respuesta)
    {
        $combo = "";
        if ($respuesta == "INFORME")
        {
            $combo .= '<option value="INFORME" selected>INFORME</option>';
            $combo .= '<option value="SECCION">SECCIÓN</option>';
            $combo .= '<option value="CAMPO">CAMPO</option>';
            $combo .= '<option value="CAMPOOT">CAMPO DE LA ORDEN DE TRABAJO</option>';
            $combo .= '<option value="CAMPORE">CAMPO DEL RESULTADO</option>';
        } else if ($respuesta == "SECCION")
        {

            $combo .= '<option value="INFORME" >INFORME</option>';
            $combo .= '<option value="SECCION" selected>SECCIÓN</option>';
            $combo .= '<option value="CAMPOOT">CAMPO DE LA ORDEN DE TRABAJO</option>';
            $combo .= '<option value="CAMPORE">CAMPO DEL RESULTADO</option>';
        } else if ($respuesta == "CAMPOOT")
        {
            $combo .= '<option value="INFORME" >INFORME</option>';
            $combo .= '<option value="SECCION" >SECCIÓN</option>';
            $combo .= '<option value="CAMPOOT" selected>CAMPO DE LA ORDEN DE TRABAJO</option>';
            $combo .= '<option value="CAMPORE">CAMPO DEL RESULTADO</option>';
        } else
        {
            $combo .= '<option value="INFORME" >INFORME</option>';
            $combo .= '<option value="SECCION" >SECCIÓN</option>';
            $combo .= '<option value="CAMPOOT" >CAMPO DE LA ORDEN DE TRABAJO</option>';
            $combo .= '<option value="CAMPORE" selected>CAMPO DEL RESULTADO</option>';
        }
        return $combo;
    }

    /**
     * Crea las opciones de campos estado cuando tiene elementos
     * ACTIVO
     * INACTIVO
     * SUSPENDIDO
     *
     * @param
     *            String HTML de un campo Radio Button
     */
    public function crearRadioEstadoAIS($estado)
    {
        $activo = "";
        $inactivo = "";
        $supendido = "";

        if ($estado == "ACTIVO")
        {
            $activo = 'checked="checked"';
        } elseif ($estado == "INACTIVO")
        {
            $inactivo = 'checked="checked"';
        } elseif ($estado == "SUSPENDIDO")
        {
            $supendido = 'checked="checked"';
        }
        $radioButon = '<label for="activo">Estado: </label>
            <label for="activo">Activo</label>
            <input type="radio" name="estado" id="activo" value="INACTIVO" ' . $activo . '>
            <label for="suspendido">Suspendido</label>
            <input type="radio" name="estado" id="suspendido" value="SUSPENDIDO" ' . $inactivo . '>
            <label for="desactivado">Inactivo</label>
            <input type="radio" name="estado" id="desactivado" value="INACTIVO" ' . $supendido . '>
            ';

        return $radioButon;
    }

    public function comboEstadosServicios($respuesta)
    {
        $combo = "";
        $opt = array('ACTIVO' => 'ACTIVO', 'INACTIVO' => 'INACTIVO');
        foreach ($opt as $key => $value)
        {
            $combo.= "<option value=\"$key\"";
            if ($respuesta == $key)
            {
                $combo.= " selected";
            }
            $combo.= ">$value</option>";
        }
        return $combo;
    }

    /**
     * Combo con tres estados ACTIVO/INACTIVO/SUSPENDIDO
     * @param type $respuesta
     * @return string
     */
    public function combo2Estados($respuesta)
    {
        $combo = "";
        if ($respuesta == "ACTIVO")
        {
            $combo .= '<option value="ACTIVO" selected>ACTIVO</option>';
            $combo .= '<option value="INACTIVO">INACTIVO</option>';
        } else if ($respuesta == "INACTIVO")
        {
            $combo .= '<option value="ACTIVO" >ACTIVO</option>';
            $combo .= '<option value="INACTIVO" selected>INACTIVO</option>';
        } else
        {
            $combo .= '<option value="" >Seleccionar</option>';
            $combo .= '<option value="ACTIVO" selected>ACTIVO</option>';
            $combo .= '<option value="INACTIVO">INACTIVO</option>';
        }
        return $combo;
    }

    /**
     * Construye combo para estado
     * @param type $respuesta
     * @return string
     */
    public function combo3Estados($respuesta)
    {
        $combo = "";
        if ($respuesta == "ACTIVO")
        {
            $combo .= '<option value="ACTIVO" selected>ACTIVO</option>';
            $combo .= '<option value="INACTIVO">INACTIVO</option>';
            $combo .= '<option value="SUSPENDIDO">SUSPENDIDO</option>';
        } else if ($respuesta == "INACTIVO")
        {

            $combo .= '<option value="ACTIVO" selected>ACTIVO</option>';
            $combo .= '<option value="INACTIVO" selected>INACTIVO</option>';
            $combo .= '<option value="SUSPENDIDO">SUSPENDIDO</option>';
        } else if ($respuesta == "SUSPENDIDO")
        {
            $combo .= '<option value="ACTIVO" selected>ACTIVO</option>';
            $combo .= '<option value="INACTIVO">INACTIVO</option>';
            $combo .= '<option value="SUSPENDIDO" selected>SUSPENDIDO</option>';
        } else
        {
            $combo .= '<option value="" selected>Seleccionar</option>';
            $combo .= '<option value="ACTIVO" >ACTIVO</option>';
            $combo .= '<option value="INACTIVO">INACTIVO</option>';
            $combo .= '<option value="SUSPENDIDO">SUSPENDIDO</option>';
        }
        return $combo;
    }

    /**
     * Construye radio para estado
     * @param type $estado
     * @return string
     */
    public function crearRadioEstadoLaboratorio($estado)
    {
        $activo = "";
        $inactivo = "";
        $supendido = "";

        if ($estado == "ACTIVO")
        {
            $activo = 'checked="checked"';
        } elseif ($estado == "INACTIVO")
        {
            $inactivo = 'checked="checked"';
        } elseif ($estado == "SUSPENDIDO")
        {
            $supendido = 'checked="checked"';
        }
        $radioButon = '<label for="activo">Estado: </label>
            <label for="activo">Activo</label>
            <input type="radio" name="estado_registro" id="activo" value="INACTIVO" ' . $activo . '>
            <label for="suspendido">Suspendido</label>
            <input type="radio" name="estado_registro" id="suspendido" value="SUSPENDIDO" ' . $inactivo . '>
            <label for="desactivado">Inactivo</label>
            <input type="radio" name="estado_registro" id="desactivado" value="INACTIVO" ' . $supendido . '>
            ';

        return $radioButon;
    }

    /**
     * Crea las opciones de campos estado cuando tiene elementos
     * ACTIVO
     * INACTIVO
     * SUSPENDIDO
     *
     * @param
     *            String HTML de un campo Radio Button
     */
    public function crearRadioEstadoAI($estado)
    {
        $activo = "";
        $inactivo = "";

        if ($estado == "ACTIVO")
        {
            $activo = 'checked="checked"';
        } elseif ($estado == "INACTIVO")
        {
            $inactivo = 'checked="checked"';
        }
        $radioButon = '  <label for="activo" class="lblEstado">Estado: </label>
            <label for="activo">Activo</label>
            <input type="radio" name="estado" required id="activo" value="ACTIVO" ' . $activo . '>
            <label for="desactivado" >Inactivo</label>
            <input type="radio" name="estado" required id="desactivado" value="INACTIVO" ' . $inactivo . '>
            ';
        return $radioButon;
    }

    /**
     * Esta combo es utilizado en los formularios que guardan con ajax; Para diefereciar el campo (estado) donde van los mensajes al píe de página
     */
    public function crearRadioEstadoAjaxAI($estado)
    {
        $activo = "";
        $inactivo = "";

        if ($estado == "ACTIVO")
        {
            $activo = 'checked="checked"';
        } elseif ($estado == "INACTIVO")
        {
            $inactivo = 'checked="checked"';
        }
        $radioButon = '  <label for="activo" class="lblEstado">Estado: </label>
            <label for="activo">Activo</label>
            <input type="radio" name="estadoAjax" required id="activo" value="ACTIVO" ' . $activo . '>
            <label for="desactivado" >Inactivo</label>
            <input type="radio" name="estadoAjax" required id="desactivado" value="INACTIVO" ' . $inactivo . '>
            ';
        return $radioButon;
    }

    /**
     * Html si no se encuentra registros
     * @return string
     */
    public function crearTabla()
    {
        $tabla = "//No existen datos para mostrar...";
        if (count($this->itemsFiltrados) > 0)
        {
            $tabla = '$(document).ready(function () {
            construirPaginacion($("#paginacion"),' . json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE) . ');
			 $("#listadoItems").removeClass("comunes");
			  });
             ';
        }
        return $tabla;
    }

    /**
     * Para cualquier tipo de combo de catalogos
     * @param type $respuesta
     * @return string
     */
    public function comboCatalogoLab($codigo, $respuesta = null)
    {
        $lNCatalogos = new CatalogosLab();
        $result = $lNCatalogos->buscarHijosDeCodigo($codigo);
        $combo = "";
        foreach ($result as $row)
        {
            $combo.= "<option value=\"$row->id_catalogos\"";
            if ($respuesta == $row->id_catalogos)
            {
                $combo.= " selected";
            }
            $combo.= ">$row->nombre</option>";
        }
        return $combo;
    }

    /**
     * Combo con los formatos de informe de acuerdo al laboratorio 
     * @param type $idFormato
     * @return string
     */
    public function comboFormatoInforme($idFormato = "")
    {
        $informe = new InformesLogicaNegocio();
        $opcionesHtml = "";
        $where = array("nivel" => 0, "fk_id_laboratorio" => $this->laboratorioUsuario(), "estado_registro" => "ACTIVO");
        $combo = $informe->buscarLista($where);

        foreach ($combo as $item)
        {
            if ($idFormato == $item->id_informe)
            {
                $opcionesHtml .= '<option value="' . $item->id_informe . '" selected>' . $item->nombre_informe . "(" . $item->codigo . ')</option>';
            } else
            {
                $opcionesHtml .= '<option value="' . $item->id_informe . '">' . $item->nombre_informe . "(" . $item->codigo . ')</option>';
            }
        }
        return $opcionesHtml;
    }

    /**
     * Combo con la secciones del formato del informe seleccionado
     * @param type $idFormato
     * @return string
     */
    public function comboSeccionesInforme($idPadre)
    {
        $informe = new InformesLogicaNegocio();
        $opcionesHtml = "";
        $opcionesHtml .= '<option value="">Seleccionar....</option>';
        $where = "nivel=1 AND fk_id_informe=" . $idPadre . " AND estado_registro='ACTIVO' AND codigo<>'RESULTADO'";

        $combo = $informe->buscarLista($where);

        foreach ($combo as $item)
        {
            $opcionesHtml .= '<option value="' . $item->id_informe . '">' . $item->nombre_informe . " (" . $item->codigo . ')</option>';
        }
        echo $opcionesHtml;
    }
    
    public function comboCodigoInforme($idPadre)
    {
        $informe = new InformesLogicaNegocio();
        $opcionesHtml = "";
        $opcionesHtml .= '<option value="">Seleccionar....</option>';
        $where = "nivel=1 AND fk_id_informe=" . $idPadre . " AND estado_registro='ACTIVO' AND codigo<>'RESULTADO'";

        $combo = $informe->buscarLista($where);

        foreach ($combo as $item)
        {
            $opcionesHtml .= '<option value="' . $item->codigo . '">' . $item->nombre_informe . " (" . $item->codigo . ')</option>';
        }
        echo $opcionesHtml;
    }

    /**
     * Combo con los campos de resultado del formato del informe seleccionado
     * @param type $idFormato
     * @return string
     */
    public function comboResultadoInforme($idPadre)
    {
        $informe = new InformesLogicaNegocio();
        $opcionesHtml = "";
        $opcionesHtml .= '<option value="">Seleccionar....</option>';

        $combo = $informe->buscarListaResultado($idPadre);

        foreach ($combo as $item)
        {
            $opcionesHtml .= '<option value="' . $item->id_informe . '">' . $item->nombre_informe . " (" . $item->codigo . ')</option>';
        }
        echo $opcionesHtml;
    }

    /**
     * Para mostrar un modal común sobre las observaciones de recepción, verificación, análisis, aprobación
     * @param type $idRecepcionMestras
     */
    public function botonDatosMuestra($idRecepcionMestras)
    {
        $url = URL . "Laboratorios/Laboratorios/verDatosMuestraModal";
        $boton = "<button type='button' title='Ver datos de la muestra' onclick='fn_verDatosMuestraModal($idRecepcionMestras, " . "\"$url\"" . ")' class='far fa-window-restore'> </button>";
        return $boton;
    }

    /**
     * Para mostrar un modal común como las observaciones de recepción, verificación, análisis, aprobación
     */
    public function verDatosMuestra()
    {
        $idRecepcionMuestras = $_POST['id'];
        $this->modeloRecepcionMuestras = new \Agrodb\Laboratorios\Modelos\RecepcionMuestrasModelo();
        $lNRecepcionMuestras = new RecepcionMuestrasLogicaNegocio();
        $this->modeloRecepcionMuestras = $lNRecepcionMuestras->buscar($idRecepcionMuestras);
        require APP . 'Laboratorios/vistas/datosMuestraVista.php';
    }

    /**
     * Para mostrar un modal común como las observaciones de recepción, verificación, análisis, aprobación
     */
    public function verDatosMuestraModal()
    {
        $this->modeloRecepcionMuestras = new \Agrodb\Laboratorios\Modelos\RecepcionMuestrasModelo();
        $lNRecepcionMuestras = new RecepcionMuestrasLogicaNegocio();
        $this->modeloRecepcionMuestras = $lNRecepcionMuestras->buscar($_POST['idRecepcionMuestras']);
        require APP . 'Laboratorios/vistas/modalDatosMuestra.php';
    }

    /**
     * Boton que se puede agregar en una grilla para ver los informes ya sea solo por solicitud o por solicitud y orden de trabajo
     * Ejemplo solo solicitud: $this->botonInformes($fila->id_solicitud)
     * Ejemplo solicitud y orden de trabajo: $this->botonInformes($fila->id_solicitud, $fila->id_orden_trabajo)
     * @param type $idSolicitud
     */
    public function botonInformes($idSolicitud, $idOrdenTrabajo = null)
    {
        $url = URL . "Laboratorios/Laboratorios/verInformes";
        $boton = "<button type='button' title='Ver informes' onclick='fn_abrirVistaInformes(\"$idSolicitud\", \"$idOrdenTrabajo\", " . "\"$url\"" . ")' class='fas fa-search'> </button>";
        return $boton;
    }

    /**
     * Funcion comun para ver los informes ya sea solo por solicitud o por solicitud y orden de trabajo
     * Una solicitud puede contener varios informes segun como se haya configurado en Consolidad informes
     */
    public function verInformes()
    {
        $idSolicitud = $_POST['idSolicitud'];
        $idOrdenTrabajo = $_POST['idOrdenTrabajo'];
        //buscar los informes de la solicitud
        $lNinformes = new ArchivoInformeAnalisisLogicaNegocio();
        $arrayParametros['idSolicitud'] = $idSolicitud;
        if (!empty($_POST['idOrdenTrabajo']))
        {
            $arrayParametros['idOrdenTrabajo'] = $idOrdenTrabajo;
        }
        $buscaInformes = $lNinformes->buscarInformesSolicitud($arrayParametros);
        if (count($buscaInformes) > 0)
        {
            $html = "";
            foreach ($buscaInformes as $fila)
            {
                $urlArchivo = URL_MVC_MODULO . "Laboratorios/archivos/informes/firmados/" . $fila->ruta_archivo . "_firmado.pdf";
                $linkInf = "<a "
                        . "href='" . $urlArchivo . "' target='_blank'><i class='fas fa-file-pdf fa-2x'></i> $fila->nombre_informe</a></br>";
                $html.= "<tr>";
                $html.= "<td style='text-align: center'>$fila->codigo_ot</td>";
                $html.= "<td style='text-align: left'>$linkInf</td>";
                $html.= "<td style='text-align: left'>$fila->observacion_general</td>";
                $html.= "<td style='text-align: left'>{$this->listarAdjuntos($fila->id_archivo_informe_analisis)}</td>";
                $html.= "</tr>";
            }
            $this->tablaInformesSolicitud = $html;
        } else
        {
            $this->tablaInformesSolicitud = "<tr><td colspan='3'>No existen datos para mostrar</td></tr>";
        }
        require APP . 'Laboratorios/vistas/informesSolicitudVista.php';
    }

    /**
     * Listar documentos adjutnos a los informes
     * @param type $idArchivoInformeAnalisis
     * @return string
     */
    public function listarAdjuntos($idArchivoInformeAnalisis)
    {
        $lNinformes = new ArchivoInformeAnalisisLogicaNegocio();
        $buscaAdjuntos = $lNinformes->buscarAdjuntosInformesSolicitud($idArchivoInformeAnalisis);
        $html = "";
        if (count($buscaAdjuntos) > 0)
        {
            $cont = 0;
            foreach ($buscaAdjuntos as $fila)
            {
                $html.= ++$cont . ". <a "
                        . "href='" . URL_DIR_LAB_AD . $fila->ruta_archivo . ".pdf' target='_blank'><i class='fas fa-file-pdf fa-2x'></i> $fila->nombre_informe</a></br>";
            }
        }
        return $html;
    }

    /**
     * Retorna las opciones del combo para las unidades de medidas
     * @param type $respuesta
     * @return type
     */
    public function comboCatalogosUnidadesMedidas($respuesta = null)
    {
        $lNCatalogos = new CatalogosLab();
        $result = $lNCatalogos->buscarCatalogosUnidadesMedidas(Constantes::FILTRO_UNIDADES_MEDIDAS);
        $combo = "";
        foreach ($result as $row)
        {
            $combo.= "<option value=\"$row->id_unidad_medida\"";
            if ($respuesta == $row->id_unidad_medida)
            {
                $combo.= " selected";
            }
            $combo.= ">$row->nombre</option>";
        }
        return $combo;
    }
}
