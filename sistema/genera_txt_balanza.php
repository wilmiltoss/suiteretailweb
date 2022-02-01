<?php 
		
			//obtenemos la tienda
      if (!empty($_REQUEST['tienda'])) {
			$tienda_url  = $_REQUEST['tienda'];			
			//echo $tienda_url;
		  }	

      $url = "http://192.168.236.132:8080/VierciApi/service?actividad=listar_maestro_actualizado&tienda=".$tienda_url."&caja=0";
			$arrDataUrl = file_get_contents($url);
			$arrData = json_decode($arrDataUrl,true);
			$total_registro = count($arrData['listaArticulos']);
      $toledo = "../files/sucursales/".$tienda_url."/TOLEDO.txt";
      $toledo_pro = "../files/sucursales/".$tienda_url."/TOLEDO_B_PRO.txt";
      $digi = "../files/sucursales/".$tienda_url."/DIGI.txt";

      //si no existe el directorio, lo crea
      $path = "../files/sucursales/".$tienda_url;
			if (!file_exists($path)) {
		    	 mkdir($path, 0777, true);
			}


      //desde el csv, carga los datos
     if (($gestor = fopen($toledo, "w+")) !== FALSE) {
      chmod ($toledo, 0777);//Damos todos los permisos a las carpetas de lectura y escritura.	
      //$toledo.basename($path['TOLEDO_B_PRO.txt']['webdesa']);
					for ($i=0; $i < count($arrData['listaArticulos']); $i++) {
						if ($arrData['listaArticulos'][$i]['balanza'] == true) {


				 		   $ean          = $arrData['listaArticulos'][$i]['EAN'];
						   $descripcion  = $arrData['listaArticulos'][$i]['descripcion'];
						   $precioLista  = $arrData['listaArticulos'][$i]['precioLista'];
						   $presentacion = $arrData['listaArticulos'][$i]['presentacion'];
						   $balanza 	 = $arrData['listaArticulos'][$i]['balanza'];
						   $fecha        = $arrData['listaArticulos'][$i]['fechaActualizacion'];


						   fputs($gestor, "0".substr ($ean, 0, 5).","); // en la tx
						   fputs($gestor, "00000000".substr($ean,0, 6).",");
						   fputs($gestor, "00".",");
						   fputs($gestor, str_pad($precioLista, 7, 0, STR_PAD_LEFT).",");
						   fputs($gestor, "00".",");
						   fputs($gestor, "000".",");
						   fputs($gestor, "0".",");
						   fputs($gestor, "000".",");
						   fputs($gestor, "000".",");
						   fputs($gestor, "00000000".",");
						   //marca de unidad o kilos
					   	   if ($arrData['listaArticulos'][$i]['presentacion'] == 'UN') {
						   	fputs($gestor, "1".",");
						   }else{
							 	fputs($gestor, "0".",");
						   }
						   fputs($gestor, "0".",");
						   fputs($gestor, "0".",");
						   fputs($gestor, substr(str_pad($descripcion, 28),0,27).","."\n");

					   }

		 			}

            fclose($gestor);
            $mensaje = "Archivo generado para TOLEDO";
        }


      if (($gestor = fopen($toledo_pro, "w")) !== FALSE) {
      chmod ($toledo_pro, 0777);//Damos todos los permisos a las carpetas de lectura y escritura.	
					for ($i=0; $i < count($arrData['listaArticulos']); $i++) {

						if ($arrData['listaArticulos'][$i]['balanza'] == true) {


				 		   $ean          = $arrData['listaArticulos'][$i]['EAN'];
						   $descripcion  = $arrData['listaArticulos'][$i]['descripcion'];
						   $precioLista  = $arrData['listaArticulos'][$i]['precioLista'];
						   $presentacion = $arrData['listaArticulos'][$i]['presentacion'];
						   $balanza      = $arrData['listaArticulos'][$i]['balanza'];


						   fputs($gestor, "0".substr ($ean, 0, 5)."	"); // en la tx
						   fputs($gestor, "00000000".substr($ean,0, 6)."	");
						   fputs($gestor, substr(str_pad($descripcion, 28),0,27)."		");
						   fputs($gestor, "0"."	");
						   fputs($gestor, str_pad($precioLista, 7, 0, STR_PAD_LEFT)."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   //marca de unidad o kilos
					   		if ($arrData['listaArticulos'][$i]['presentacion'] == 'UN') {
					   			fputs($gestor, "1"."	");
					   		}else{
					   			fputs($gestor, "0"."	");
					   		}
						   
						   fputs($gestor, "1"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "000"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "1"."	");
						   fputs($gestor, "1"."	");
						   fputs($gestor, "1"."	");
						   fputs($gestor, "1"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "1"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	");
						   fputs($gestor, "0"."	"."\n");
					    }

		 			}
            fclose($gestor);
            $mensaje2 = "Archivo generado para TOLEDO_B_PRO";
        }

        if (($gestor = fopen($digi, "w")) !== FALSE) {
      chmod ($digi, 0777);//Damos todos los permisos a las carpetas de lectura y escritura.	
  

					for ($i=0; $i < count($arrData['listaArticulos']); $i++) {
						if ($arrData['listaArticulos'][$i]['balanza'] == true) {


				 		   $ean          = $arrData['listaArticulos'][$i]['EAN'];
						   $descripcion  = $arrData['listaArticulos'][$i]['descripcion'];
						   $precioLista  = $arrData['listaArticulos'][$i]['precioLista'];
						   $presentacion = $arrData['listaArticulos'][$i]['presentacion'];
						   $balanza 	 = $arrData['listaArticulos'][$i]['balanza'];
						   $fecha        = $arrData['listaArticulos'][$i]['fechaActualizacion'];

						   if ($arrData['listaArticulos'][$i]['presentacion'] == 'UN') {
						   	  fputs($gestor, "8".substr($ean,0, 5)."U".str_pad(substr($descripcion,0, 22),22).str_pad($precioLista, 6,0,STR_PAD_LEFT)."1101"."\n");
							}else{
							  fputs($gestor, "5".substr($ean,0, 5)."P".str_pad(substr($descripcion,0, 22),22).str_pad($precioLista, 6, 0,STR_PAD_LEFT)."1101"."\n");
							}
					   }

		 			}
            fclose($gestor);
            $mensaje3 = "Archivo generado para DIGI";
        } 
      

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

 		  <a href="javascript:history.back()"><img src="img/atras.png" height="100" width="100" alt="Botón" ></a><br>Volver<br><br>
 		  <p4><?php echo $mensaje; ?></p4><a href="download.php?file=TOLEDO.txt&tienda=<?php echo $tienda_url; ?>" class="btn_new"> Descargar</a><br>
 		  <p4><?php echo $mensaje2; ?></p4><a href="download.php?file=TOLEDO_B_PRO.txt&tienda=<?php echo $tienda_url; ?>" class="btn_new">  Descargar</a><br>
 		  <p4><?php echo $mensaje3; ?></p4><a href="download.php?file=DIGI.txt&tienda=<?php echo $tienda_url; ?>" class="btn_new">  Descargar</a>
    		
 	</section>
 	<?php include "includes/footer.php"; ?>
 </body>
 </html>



	