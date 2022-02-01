<?php 

    //obtenemos la ip del colector
     if (!empty($_REQUEST['ip'])||!empty($_REQUEST['tienda'])) {
            $ip_colector  = $_REQUEST['ip'];   
            $tienda_colector  = $_REQUEST['tienda'];       
            echo $tienda_colector;
    }  


  // variables
    $ftp_server = $ip_colector;
    $ftp_user_name = "retail";
    $ftp_user_pass = "ohaus";
    $destination_file = "/Honeywell/retaildata.db";
    $source_file = "../files/sucursales/".$tienda_colector."/retaildata.db"; 

    echo $ftp_server;

if (strpos($ip_colector, 'Warning') !== false) {
    ?>
    <script>alert('No se ha seleccionado ningun colector');javascript:history.back();</script>
    <?php
   echo "error";
}else {
    // conexión
    $conn_id = ftp_connect($ftp_server); 

    if ($conn_id == false) {

        ?>
        <script>alert('Fallo en la transferencia, el colector se encuentra fuera de linea');javascript:history.back();</script>
        <?php 
    }else{
    // logeo
    $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass); 

    if ($login_result == false) {
        echo "error";
    }   
    // conexión
    if ((!$conn_id) || (!$login_result)) { 
           //echo "Conexión al FTP con errores!";
           //echo "Intentando conectar a $ftp_server for user $ftp_user_name"; 
           $conexion =  "Conexión al FTP con errores!";
            $var = "Conexión al FTP con errores!";
           exit; 
       } else {
          // echo "Conectado a $ftp_server, for user $ftp_user_name";
           $conexion = "Conectado a $ftp_server, for user $ftp_user_name"; 
       } 
    // archivo a copiar/subir
    $upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);
     
    // estado de subida/copiado
    if (!$upload) { 
           //echo "Error al subir el archivo!";
           $var = "Error al subir el archivo!";
       } else {
           //echo "Archivo $source_file se ha subido exitosamente a $ftp_server en $destination_file";

           $var = "Archivo $source_file se ha subido exitosamente a $ftp_server en $destination_file";
       }    
    // cerramos
    ftp_close($conn_id);


 ?>

     <!DOCTYPE html>
     <html lang="en">
     <head>
     	<meta charset="utf-8">
     	<?php include "includes/scripts.php"; ?>
     	<title>Panel de Control</title>
     </head>
     <body>
     	<?php include "includes/header.php"; ?>
     	<section id="container">

     		<?php 

     		echo "<script> alert('".$conexion."'); </script>";
    		echo "<script> alert('".$var."'); </script>";
     		?>
     		  <a href="javascript:history.back()"><img src="img/atras.png" height="100" width="100" alt="Botón" ></a>Volver
        		
     	</section>
     	<?php include "includes/footer.php"; ?>
     </body>
     </html>

     <?php 
     }

 }

  ?>
