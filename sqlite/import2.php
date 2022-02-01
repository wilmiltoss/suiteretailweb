<!DOCTYPE html>
 <html lang="en">
 <head>
  <meta charset="utf-8">
  <title>Panel de Control</title>
 </head>
 <body>

  <section id="container">

    <div class="form_register">
      <h1><i class="fas fa-user-plus"></i> Respuesta Log</h1>
      
<?php
//Filtro de productos que viene de la grilla
        if (!empty($_REQUEST['tienda'])) {
      $nro_tienda  = $_REQUEST['tienda'];     
      echo "- Nro. de Tienda:". $nro_tienda;echo "<br>";
    } 

require_once __DIR__ . '/app/util/DbContext2.php';
require_once __DIR__ . '/app/service/ProductService2.php';
require_once __DIR__ . '/app/service/SaveCsv.php';
require_once __DIR__ . '/app/model/Presentacion.php';

use App\Util\DbContext2,
    App\Service\SaveCsv,
    App\Model\Presentacion,
    App\Service\ProductService2;


//si no existe el directorio, lo crea
$path = "../files/sucursales/".$nro_tienda;

if (!file_exists($path)) {
    mkdir($path, 0777, true);
}


//If (unlink('C:/BALANZAS/retaildata.sqlite')) {
If (unlink('../files/sucursales/'.$nro_tienda.'/retaildata.db')) {
  echo "- BD eliminadada";echo "<br>";
} else {
  echo "no eliminado";echo "<br>";
}


DbContext2::initialize($nro_tienda);
print '- Instanciando la base de datos';echo "<br>";

//crea las tablas sqlite
DbContext2::generateSchema();
print PHP_EOL . '- Generado esquema';echo "<br>";

DbContext2::cargarTablasFijas();
print PHP_EOL . '- Cargando tablas fijas ';echo "<br>";

//DbContext2::migraApiLite($nro_tienda);//cargamos el nro de tienda obtenido
//print PHP_EOL . '- Insertando Tablas desde Api';echo "<br>";

//escribe en el archivo los datos, sino lo encuentra lo crea
SaveCsv::RutaCsv($nro_tienda);
print PHP_EOL . '- Escritura CSV y Guardado';echo "<br>";

//ERROR EN LA IMPORTACION DEL CSV A SQLITE
ProductService2::importFromCsv('../files/sucursales/'.$nro_tienda.'/maestro.csv');
print PHP_EOL . '- Importando CSV';


print PHP_EOL . sprintf('- Se han importado %s productos', ProductService2::count($nro_tienda));echo "<br>";
print PHP_EOL . '- Proceso finalizado';
?>


      <hr>
      <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

        <a href="javascript:history.back()"><img src="../sistema/img/atras.png" height="100" width="100" alt="Botón" ></a>Volver
    
    </div>    
  </section>


 </body>
 </html>















