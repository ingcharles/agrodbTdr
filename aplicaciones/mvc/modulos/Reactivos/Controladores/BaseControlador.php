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

namespace Agrodb\Reactivos\Controladores;

use Agrodb\Catalogos\Modelos\CatalogosLaboratoriosLogicaNegocio as Catalogos;
use Agrodb\Reactivos\Modelos\ReactivosBodegaLogicaNegocio;
use Agrodb\Laboratorios\Modelos\UsuarioLaboratorioLogicaNegocio;
use Agrodb\Core\Mensajes;


class BaseControlador extends \Agrodb\Laboratorios\Controladores\BaseControlador
{

    public $itemsFiltrados = array();
    public $identificador = null;

    /**
     * Para retornar las bodegas al que pertenece el usuario
     * @return type
     */
    public function usuarioBodegas()
    {
        $lNReactivosBodega = new ReactivosBodegaLogicaNegocio();
        $buscaUsuarioBodega = $lNReactivosBodega->buscarUsuarioBodegas($this->identificador);
        if (count($buscaUsuarioBodega) > 0)
        {
            $arraBodegas = array();
            foreach ($buscaUsuarioBodega as $fila)
            {
                $arraBodegas[] = $fila->id_bodega;
            }
            return $arraBodegas;
        } else
        {
            echo Mensajes::fallo("Verifique que el usuario esté asignado a una provincia y creado la bodega. ");
            exit();
        }
    }

    /**
     * Retorna la el array de los laboratorios provincia asignados al usuario
     * @param type $respuesta
     * @return type
     */
    public function laboratoriosProvincia()
    {
        $lNusuarioLaboratorio = new UsuarioLaboratorioLogicaNegocio();
        $buscaUsuarioLaboratorio = $lNusuarioLaboratorio->buscarUsuarioLaboratorio(array(
            'identificador' => $this->identificador,
            'estado' => 'ACTIVO',
            'perfil' => 'Responsable Técnico'));
        $arrayLaboratoriosProvincia = array();
        foreach ($buscaUsuarioLaboratorio as $fila)
        {
            $arrayLaboratoriosProvincia[] = $fila->id_laboratorios_provincia;
        }
        return $arrayLaboratoriosProvincia;
    }

    /**
     * Construye el combo de servicios segun el laboratorio seleccionado
     *
     * @param Integer $idLaboratorio
     * @return string Código html para llenar el combo de servicios mediante ajax
     */
    public function comboServicios($idLaboratorio)
    {
        
    }

    /**
     * Consulta los laboratorios de una dirección seleccionada y construye el combo
     *
     * @param Integer $idLaboratorio
     * @return string Código html para llenar el combo de servicios mediante ajax
     */
    public function comboServiciosSimple($idLaboratorio)
    {
        
    }

    /**
     * Construye un combo segun el servicio padre selecionado
     * @param type $fkIdServicio
     */
    public function comboServicio($fkIdServicio)
    {
        
    }

    /**
     * Crea un combo con las opciones SI/NO
     *
     * @return string - Vista el cÃ³digo html para desplegar los botones
     */
    public function crearComboSINO($respuesta = null)
    {
        
    }

    /**
     * Crea un combo con las opciones de VERTICAL / HORIZONTAL
     *
     * @return string - Vista el cÓdigo HTML 
     */
    public function comboOrientacion($respuesta)
    {
        
    }

    public function tipoCampoInforme($respuesta)
    {
        
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
        return parent::crearRadioEstadoAIS($estado);
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
        return parent::crearRadioEstadoAI($estado);
    }

    public function crearTabla()
    {
        
    }

    /**
     * Para cualquier tipo de combo de catalogos
     * @param type $respuesta
     * @return string
     */
    public function comboCatalogo($codigo, $respuesta = null)
    {
        $lNCatalogos = new Catalogos();
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
     * Combo General que se forma segun array de opciones enviado
     * @param type $arrayOpciones
     * @param type $respuesta
     * @return type
     */
    public function comboGeneral($arrayOpciones, $respuesta = null)
    {
        $combo = "";
        foreach ($arrayOpciones as $key => $value)
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
     * 
     * @param type $respuesta
     * @return string
     */
    public function tipoProcedimiento($respuesta = null)
    {
        $combo = "";
        $opt = array('MANUAL' => 'Manual', 'AUTOMATICO' => 'Automático');
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
     * 
     * @param type $respuesta
     * @return type
     */
    public function comboBodegasDelUsuario()
    {
        $lNReactivosBodega = new ReactivosBodegaLogicaNegocio();
        $buscaUsuarioBodega = $lNReactivosBodega->buscarUsuarioBodegas($this->identificador);
        $combo = "";
        foreach ($buscaUsuarioBodega as $fila)
        {
            $combo.= "<option value='$fila->id_bodega'>$fila->provincia => $fila->nombre_bodega</option>";
        }
        return $combo;
    }

    /**
     * Laboratorios del usuario
     * @param type $respuesta
     * @return type
     */
    public function comboUsuarioLaboratorios()
    {
        $lNusuarioLaboratorio = new UsuarioLaboratorioLogicaNegocio();
        $buscaUsuarioLaboratorio = $lNusuarioLaboratorio->buscarUsuarioLaboratorio(array(
            'identificador' => $this->identificador,
            'estado' => 'ACTIVO',
            'perfil' => 'Responsable Técnico'));
        $combo = "";
        foreach ($buscaUsuarioLaboratorio as $fila)
        {
            $combo.= "<option data-id='$fila->id_laboratorio' value='$fila->id_laboratorios_provincia'>$fila->prov_laboratorio => $fila->laboratorio</option>";
        }
        return $combo;
    }
    
    /**
     * Laboratorios del usuario
     * @param type $respuesta
     * @return type
     */
    public function comboUsuarioLaboratoriosPrincipal()
    {
        $lNusuarioLaboratorio = new UsuarioLaboratorioLogicaNegocio();
        $buscaUsuarioLaboratorio = $lNusuarioLaboratorio->buscarUsuarioLaboratorioPrincipal($this->identificador);
        $combo = "";
        foreach ($buscaUsuarioLaboratorio as $fila)
        {
            $combo.= "<option data-id='$fila->id_laboratorio' value='$fila->id_laboratorios_provincia'>$fila->prov_laboratorio => $fila->laboratorio</option>";
        }
        return $combo;
    }
    
    /**
     * Construye el combo que contiene los laboratorios en provinci del usuario o
     * construye un campo oculto que contiene el único laboratorio del usuario
     * @param type $idLaboratoriosProvincia     para identificar la opción seleccionada en caso de combo
     * @param type $atributo   enviar la palabra 'requerido' si por ejemplo se usa en un formulario en caso de combo
     * y se desea que sea requerido
     * @return string
     */
    public function laboratoriosProvinciaPrincipal($idLaboratoriosProvincia = null, $atributo = null)
    {
        $lNusuarioLaboratorio = new UsuarioLaboratorioLogicaNegocio();
        $buscaUsuarioLaboratorio = $lNusuarioLaboratorio->buscarUsuarioLaboratorioPrincipal($this->identificador);
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
     * Calcular el estock minimo
     * @param type $minimo
     * @param type $stock
     * @return string
     */
    public function calcularStockMinimo($minimo, $stock)
    {
        $estilo = "";
        if ($stock < $minimo)
        {
            $estilo = "danger";
        } else if ($stock == $minimo)
        {
            $estilo = "warning";
        }
        return $estilo;
    }

}
