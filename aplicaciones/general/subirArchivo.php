<?php
try {
    $return = Array(
        'ok' => TRUE
    );
    // $upload_folder ='';
    $nombre_archivo = $_FILES['archivo']['name'];
    $tipo_archivo = $_FILES['archivo']['type'];
    $tamano_archivo = $_FILES['archivo']['size'];
    $tmpArchivo = $_FILES['archivo']['tmp_name'];
    $identificador = $_GET['identificador'];
    $rutaCarpeta = $_GET['rutaCarpeta'];
    
     $extension = explode(".", $nombre_archivo);

    if(strtoupper(end($extension))=='PDF' && $tipo_archivo!='application/pdf'){        
         die('No se cargó archivo. Extención incorrecta');
    }
    
    if (!file_exists('../../' . $rutaCarpeta)) {
        mkdir('../../' .$rutaCarpeta, 0777, true);
    }
    
    // echo $tamano_archivo;
    
    if ($tamano_archivo != '0') {
        $nuevo_nombre = $identificador . '.' . end($extension);
        $ruta = $rutaCarpeta . '/' . $nuevo_nombre;
        move_uploaded_file($tmpArchivo, '../../' . $ruta);
        echo $ruta;
        return $ruta;
    } else {
        echo 'archivoNoSoportado';
        return 'archivoNoSoportado';
    }
} catch (Exception $ex) {
    echo 'No se cargó archivo';
}

?>