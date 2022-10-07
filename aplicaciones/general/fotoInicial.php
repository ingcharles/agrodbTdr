<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="Expires" content="0">
	<meta http-equiv="Last-Modified" content="0">
	<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
	<meta http-equiv="Pragma" content="no-cache">
	<link rel='stylesheet' href='../general/estilos/agrodb_papel.css'>
	<link rel='stylesheet' href='../general/estilos/agrodb.css'>
	<script src="funciones/jquery-1.9.1.js" type="text/javascript"></script>
	<script src="funciones/agrdbfunc.js" type="text/javascript"></script>
</head>
<body>

	<figure class="<?php echo $_GET["clase"];?>">
		<img id="foto_img" src="<?php echo $_GET["img"];?>" />
		<figcaption>
			<?php echo $_GET["titulo"];?>
		</figcaption>
	</figure>
</body>
</html>


