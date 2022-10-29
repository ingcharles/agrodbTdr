<?php
 /**
 * Controlador CronogramaVacaciones
 *
 * Este archivo controla la lógica del negocio del modelo:  CronogramaVacacionesModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-10-22
 * @uses    CronogramaVacacionesControlador
 * @package VacacionesPermisos
 * @subpackage Controladores
 */
 namespace Agrodb\VacacionesPermisos\Controladores;

use Agrodb\GUath\Modelos\DatosContratoLogicaNegocio;
use Agrodb\VacacionesPermisos\Modelos\CronogramaVacacionesLogicaNegocio;
 use Agrodb\VacacionesPermisos\Modelos\CronogramaVacacionesModelo;
 
class CronogramaVacacionesControlador extends BaseControlador 
{

		 private $lNegocioCronogramaVacaciones = null;
		 private $modeloCronogramaVacaciones = null;
		 private $accion = null;
		 private $datosGenerales = null;
		 private $datosFuncionarioBackup = null;
		 private $lNegocioDatosContrato = null;
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioCronogramaVacaciones = new CronogramaVacacionesLogicaNegocio();
		 $this->modeloCronogramaVacaciones = new CronogramaVacacionesModelo();
		 $this->lNegocioDatosContrato = new DatosContratoLogicaNegocio();
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $modeloCronogramaVacaciones = $this->lNegocioCronogramaVacaciones->buscarCronogramaVacaciones();
		 $this->tablaHtmlCronogramaVacaciones($modeloCronogramaVacaciones);
		 require APP . 'VacacionesPermisos/vistas/listaCronogramaVacacionesVista.php';
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
			$this->accion = "Nueva solicitud de planificación año " . date('Y'); 
			$this->datosGenerales = $this->construirDatosGeneralesCronogramaVacaciones();
			$this->datosFuncionarioBackup = $this->obtenerDatosFuncionarioBackup($this->identificador);
			require APP . 'VacacionesPermisos/vistas/formularioCronogramaVacacionesVista.php';
		}	/**
		* Método para registrar en la base de datos -CronogramaVacaciones
		*/
		public function guardar()
		{
		  $this->lNegocioCronogramaVacaciones->guardar($_POST);
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: CronogramaVacaciones
		*/
		public function editar()
		{
		 $this->accion = "Editar CronogramaVacaciones"; 
		 $this->modeloCronogramaVacaciones = $this->lNegocioCronogramaVacaciones->buscar($_POST["id"]);
		 require APP . 'VacacionesPermisos/vistas/formularioCronogramaVacacionesVista.php';
		}	/**
		* Método para borrar un registro en la base de datos - CronogramaVacaciones
		*/
		public function borrar()
		{
		  $this->lNegocioCronogramaVacaciones->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - CronogramaVacaciones
		*/
		 public function tablaHtmlCronogramaVacaciones($tabla) {
		{
		 $contador = 0;
		  foreach ($tabla as $fila) {
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['id_cronograma_vacacion'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'VacacionesPermisos\cronogramavacaciones"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_cronograma_vacacion'] . '</b></td>
<td>'
		  . $fila['identificador'] . '</td>
<td>' . $fila['fecha_ingreso_institucion']
		  . '</td>
<td>' . $fila['id_puesto'] . '</td>
</tr>');
		}
		}
	}

	public function obtenerDatosFuncionarioBackup($identificadorFuncionario)
    {

        $comboFuncionarioBackup = '<option value="">Seleccionar....</option>';

		$funcionarioBackup = $this->lNegocioDatosContrato->obtenerDatosFuncionarioBackup($identificadorFuncionario);

        foreach ($funcionarioBackup as $item) {
            $comboFuncionarioBackup .= '<option value="' . $item->identificador . '">' . $item->nombre . '</option>';
        }

		return $comboFuncionarioBackup;
    }

	public function construirPlanificarPeriodos()
    {

		$numeroPeriodosPlanificar = $_POST['numero_periodos_planificar'];

		$datosPlanificarPeriodos = '<fieldset>
									<legend>Ingresar periodo</legend>';

		switch ($numeroPeriodosPlanificar){

			case '1':
				
				$datosPlanificarPeriodos .= '<table id="tPeriodosPlanificar" style="width: 100%;">
												<thead>
													<tr>
														<th>Periodo</th>
														<th>Fecha inicio</th>
														<th>Numero días</th>
														<th>Fecha fin</th>
													</tr>
												</thead>
												<tbody>
													<tr>	
														<td>Primer periodo<input type="hidden" name="hPeriodo"></td>
														<td><input type="date" name="hFechaIncio"></td>
														<td><input type="number" name="hnumeroDias" min="15" max="30" value ="15" id="diaPrimerPeriodo" onkeyup="calculo(this)"></td>
														<td><input type="date" name="hFechaFin"></td>
													</tr>
												</tbody>
											</table>';

			break;

			case '2':
				$datosPlanificarPeriodos .= '<table id="tPeriodosPlanificar" style="width: 100%;">
												<thead>
													<tr>
														<th>Periodo</th>
														<th>Fecha inicio</th>
														<th>Numero días</th>
														<th>Fecha fin</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>Primer periodo<input type="hidden" name="hPeriodo"></td>
														<td><input type="date" name="hFechaIncio"></td>
														<td><input type="number" name="hnumeroDias" min="15" max="30" value ="15" onkeyup="calculo(this)"></td>
														<td><input type="date" name="hFechaFin"></td>
														<td><input type="text" name="hTotalDias"></td>
													</tr>
													<tr>
														<td>Segundo periodo<input type="hidden" name="hPeriodo"></td>
														<td><input type="date" name="hFechaIncio"></td>
														<td><input type="number" name="hnumeroDias" min="15" max="30" value ="15" onkeyup="calculo(this)"></td>
														<td><input type="text" name="hFechaFin" id="hFechaFin"></td>
														<td><input type="text" name="hTotalDias"></td>
													</tr>
												</tbody>
											</table>';
			break;

		}

		$datosPlanificarPeriodos .= '</fieldset>';
  		
		echo json_encode(array(
            'estado' => 'EXITO',
            'datosPlanificarPeriodos' => $datosPlanificarPeriodos
        ));
    }

}
