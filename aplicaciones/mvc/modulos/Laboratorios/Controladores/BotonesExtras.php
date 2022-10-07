<?php

/**
 * Configura botones de acuerdo a los permisos otorgados internamente para el modulo de laboratorios
 */

namespace Agrodb\Laboratorios\Controladores;

/**
 * Description of BotonesExtras
 *
 */
use Agrodb\Laboratorios\Modelos\UsuarioLaboratorioLogicaNegocio;

class BotonesExtras
{

    private $html = null;
    private $verMenuPrincipal = null;

    function __construct()
    {
        $this->html = "";
        $this->verMenuPrincipal = true;
        $usuarioLaboratorio = new UsuarioLaboratorioLogicaNegocio();
        $permisos = $usuarioLaboratorio->buscarLista(array('identificador' => $_SESSION['usuario']));
        $fila = $permisos->current();
        if ($fila)
        {
            $objJson = \Zend\Json\Json::decode($fila->permisos);
            foreach ($objJson as $item)
            {

                if ($item->permiso == "true")
                {
                    switch ($item->id)
                    {
                        case "cronogramaPostregistro":

                            $this->html .= '<a href="#" id="_' . $item->id . '" data-destino="areaTrabajo #listadoItems" data-idopcion="' . $_POST["opcion"] . '"';
                            $this->html .= 'data-opcion="../mvc/Laboratorios/CronogramaPostregistro" "data-rutaAplicacion="mvc/Laboratorios">';
                            $this->html .= 'Cronograma</a>';
                            if ($_POST["id"] == "_cronogramaPostregistro")
                            {
                                $this->verMenuPrincipal = false;
                                $this->html .= '<a href="#" id="_nuevo" data-destino="detalleItem" data-idopcion="' . $_POST["opcion"] . '"';
                                $this->html .= 'data-opcion="../mvc/Laboratorios/CronogramaPostregistro/nuevo" "data-rutaAplicacion="mvc/Laboratorios">';
                                $this->html .= 'Nuevo</a>';
                            }

                            break;
                    }
                }
            }
        }
    }

    /**
     * Retorna html
     * @return type
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * Retorna html
     * @return type
     */
    public function getVerMenuPrincipal()
    {
        return $this->verMenuPrincipal;
    }

}
