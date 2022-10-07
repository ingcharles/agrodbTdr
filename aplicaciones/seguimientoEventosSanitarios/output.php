<?php
require_once 'pdf/lib/dompdf/dompdf_config.inc.php';

echo 'hola';

$dompdf = new DOMPDF();
$dompdf->load_html( file_get_contents( 'pdf.php' ) );
$dompdf->render();
$dompdf->stream("mi_archivo.pdf");
?>