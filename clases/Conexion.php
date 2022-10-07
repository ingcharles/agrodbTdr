<?php

/**
 *
 * @author Carlos Anchundia
 *        
 */
class Conexion{

	private $servidor;

	private $puerto;

	private $baseDatos;

	private $usuario;

	private $clave;

	private $conexion;

	private $resultado;

	private $connection;

	private $driver;

	private $connectionString;

	private $user;

	public $mensajeError;

	public function __construct($servidor = 'localhost', $puerto = '', $baseDatos = 'agrocalidadprueba', $usuario = 'postgres', $clave = 'A9r@07c@/i.'){
		$this->servidor = $servidor;
		$this->puerto = $puerto;
		$this->baseDatos = $baseDatos;
		$this->usuario = $usuario;
		$this->clave = $clave;
	}

	/**
	 *
	 * @return string
	 */
	public function getServidor(){
		return $this->servidor;
	}

	/**
	 *
	 * @return string
	 */
	public function getPuerto(){
		return $this->puerto;
	}

	/**
	 *
	 * @return string
	 */
	public function getBaseDatos(){
		return $this->baseDatos;
	}

	/**
	 *
	 * @return string
	 */
	public function getUsuario(){
		return $this->usuario;
	}

	/**
	 *
	 * @return string
	 */
	public function getClave(){
		return $this->clave;
	}

	private function conectar(){
		$cadenaConexion = 'host=' . $this->servidor . ' port=' . $this->puerto . ' dbname=' . $this->baseDatos . ' user=' . $this->usuario . ' password=' . $this->clave;
		$this->conexion = pg_connect($cadenaConexion);
	}

	public function generarSqlName($longitudLetras, $longitudNumeros){
		$key = '';
		$keyNumeros = '';
		$keyLetras = '';
		$patternNumeros = '1234567890';
		$patternLetras = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

		$maxNumeros = strlen($patternNumeros) - 1;
		$maxLetras = strlen($patternLetras) - 1;
		for ($i = 0; $i < $longitudNumeros; $i ++)
			$keyNumeros .= $patternNumeros{mt_rand(0, $maxNumeros)};
		for ($i = 0; $i < $longitudLetras; $i ++)
			$keyLetras .= $patternLetras{mt_rand(0, $maxLetras)};

		$key = $keyLetras . $keyNumeros;
		return $key;
	}

	public function ejecutarConsulta($sql, $parametros = null){
		if (! $this->estaConectada()){
			$this->conectar();
			if (! $this->estaConectada()){
				$this->mensajeError = pg_last_error($this->conexion);
				throw new Exception('Error: No se pudo establecer conexion a la base de datos!');
				// die('Error: No se pudo establecer conexion a la base de datos: ' . pg_last_error());
			}
		}
		try{

			// $this->resultado = pg_query($this->conexion,$sql);

			if ($parametros == null || is_null($parametros)){
				$this->resultado = pg_query($this->conexion, $sql);
			}else{
				$sqlName = $this->generarSqlName(10, 10);
				$this->resultado = pg_prepare($this->conexion, $sqlName, $sql);
				if ($this->resultado){
					$this->resultado = pg_execute($this->conexion, $sqlName, $parametros);
					if (! $this->resultado){
						throw new Exception();
					}
				}else{
					throw new Exception();
				}
			}
		}catch (Exception $e){
			$this->mensajeError = pg_last_error($this->conexion);
			throw new Exception('Error DESCONOCIDO: ' . pg_last_error($this->conexion));
		}

		if (! $this->resultado){
			// die('No hay resultados para la consulta');
			$this->mensajeError = pg_last_error($this->conexion);
			throw new Exception('Error: SQL contiene errores');
		}
		return $this->resultado;
	}

	private function estaConectada(){
		return (! $this->conexion) ? false : true;
	}

	public function desconectar(){
		if ($this->estaConectada()){
			pg_free_result($this->resultado);
			pg_close($this->conexion);
		}
	}

	public function verificarSesion(){
		session_start();
		if (! isset($_SESSION['usuario'])){
			// header('Location: http://'.$_SERVER['HTTP_HOST']. '/agrodb/ingreso.php?sesion=E');
			header('Location: ingreso.php');
		}
	}

	// ----------------------------------------------------------------------------------------------------------------------------------
	public function ejecutarConsultaLOGS($sql){
		if (! $this->estaConectada()){
			$this->conectar();
			if (! $this->estaConectada()){
				$this->mensajeError = pg_last_error($this->conexion);
				throw new Exception('Error: No se pudo establecer conexion a la base de datos!');
				// die('Error: No se pudo establecer conexion a la base de datos: ' . pg_last_error());
			}
		}
		$xtransaccion_old = $sql;
		$s = microtime(true);
		$s1 = microtime(true);
		$t = $s1 - $s;
		$xcadenota = date("d/m/Y") . ", " . date("H:i:s");
		$xcadenota .= "; " . @$_SESSION['usuario'];
		$xcadenota .= "; " . @$_SESSION['idAplicacion'];
		$xcadenota .= "; " . @$_SESSION['nombreProvincia'];
		$xcadenota .= "; " . @$_SESSION['idArea'];
		$xcadenota .= "; " . $_SERVER['REMOTE_ADDR'];
		$xcadenota .= "; " . $_SERVER['HTTP_REFERER'];

		try{
			$this->resultado = pg_query($this->conexion, $sql);
		}catch (Exception $e){
			$this->mensajeError = pg_last_error($this->conexion);
			$xcadenota .= "; " . $this->mensajeError;
			throw new Exception('Error DESCONOCIDO: ' . pg_last_error($this->conexion));
		}
		$xlast_error = pg_last_error($this->conexion);
		$xtransaccion_old = preg_replace('/\s\s+/', ' ', $xtransaccion_old);
		$xcadenota .= "; " . $xtransaccion_old . "; " . $t . " seg\n";

		if (@$_SESSION['idAplicacion'] == ''){
			$arch = fopen("../../aplicaciones/uath/lib_logs/logs/catastro_logs_trigger_" . date("d-m-Y") . ".txt", "a+");
		}else{
			switch (@$_SESSION['idAplicacion']) {
				case 8:
					$arch = fopen("../../aplicaciones/uath/lib_logs/logs/catastro_logs_" . date("d-m-Y") . ".txt", "a+");
				break;
				case 31:
					$arch = fopen("../../aplicaciones/uath/lib_logs/logs/vacaciones_logs_" . date("d-m-Y") . ".txt", "a+");
				break;
				default:
					$arch = fopen("../../aplicaciones/uath/lib_logs/logs/general_logs_" . date("d-m-Y") . ".txt", "a+");
			}
		}

		fwrite($arch, $xcadenota);
		fclose($arch);

		if ($xlast_error){
			if (@$_SESSION['idAplicacion'] == ''){
				$arch_error = fopen("../../aplicaciones/uath/lib_logs/errors/catastro_errors_trigger" . date("d-m-Y") . ".txt", "a+");
			}else{
				switch (@$_SESSION['idAplicacion']) {
					case 8:
						$arch = $arch_error = fopen("../../aplicaciones/uath/lib_logs/errors/catastro_errors_" . date("d-m-Y") . ".txt", "a+");
					break;
					case 31:
						$arch = $arch_error = fopen("../../aplicaciones/uath/lib_logs/errors/vacaciones_errors_" . date("d-m-Y") . ".txt", "a+");
					break;
					default:
						$arch = $arch_error = fopen("../../aplicaciones/uath/lib_logs/errors/general_errors_" . date("d-m-Y") . ".txt", "a+");
				}
			}
			fwrite($arch_error, $xcadenota);
			fclose($arch_error);
		}

		if (! $this->resultado){
			// die('No hay resultados para la consulta');
			$this->mensajeError = pg_last_error($this->conexion);
			throw new Exception('Error: SQL contiene errores');
		}
		return $this->resultado;
	}

	public function ejecutarLogsTryCatch($error){
		$xtransaccion_old = $error;
		$s = microtime(true);
		$s1 = microtime(true);
		$t = $s1 - $s;
		$xcadenota = date("d/m/Y") . ", " . date("H:i:s");
		$xcadenota .= "; " . @$_SESSION['usuario'];
		$xcadenota .= "; " . @$_SESSION['idAplicacion'];
		$xcadenota .= "; " . @$_SESSION['nombreProvincia'];
		$xcadenota .= "; " . @$_SESSION['idArea'];
		$xcadenota .= "; " . $_SERVER['REMOTE_ADDR'];
		$xcadenota .= "; " . $_SERVER['HTTP_REFERER'];

		$xcadenota .= "; " . $xtransaccion_old . "; " . $t . " seg\n";

		if (@$_SESSION['idAplicacion'] == ''){
			$arch = fopen("../../aplicaciones/uath/lib_logs/trycatch/logs_try_catch_" . date("d-m-Y") . ".txt", "a+");
		}else{
			switch (@$_SESSION['idAplicacion']) {
				case 8:
					$arch = fopen("../../aplicaciones/logs/trycatch/catastro/catastro_logs_try_catch_" . date("d-m-Y") . ".txt", "a+");
				break;
				case 27:
					$arch = fopen("../../aplicaciones/logs/trycatch/financiero/financiero_logs_try_catch_" . date("d-m-Y") . ".txt", "a+");
				break;
				case 31:
					$arch = fopen("../../aplicaciones/logs/trycatch/vacaciones/vacaciones_logs_try_catch__" . date("d-m-Y") . ".txt", "a+");
				break;
				case 38:
					$arch = fopen("../../aplicaciones/logs/trycatch/movilizacion/movilizacion_logs_try_catch__" . date("d-m-Y") . ".txt", "a+");
				break;
				default:
					$arch = fopen("../../aplicaciones/logs/trycatch/general/general_logs_try_catch__" . date("d-m-Y") . ".txt", "a+");
			}
		}

		fwrite($arch, $xcadenota);
		fclose($arch);
	}
	// ----------------------------------------------------------------------------------------------------------------------------------------
}