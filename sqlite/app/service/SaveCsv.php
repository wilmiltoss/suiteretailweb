<?php 
namespace App\Service;

use App\Util\Anexgrid,
    PDOException,
    PDO;



class SaveCsv {

    public static function RutaCsv($tienda) {

    	//$paginas = "?page=1";
		$url = "http://192.168.236.132:8080/VierciApi/service?actividad=listar_maestro_articulos&tienda=".$tienda."&caja=0";
		$arrDataUrl = file_get_contents($url);//consultamos el webservice
		$arrData = json_decode($arrDataUrl,true);//decodifiamos el json, extrayendo el array con true
		
		echo count($arrData['listaArticulos']).' items';
		$maestro = "../files/sucursales/".$tienda."/maestro.csv";

      //desde el csv, carga los datos
     if (($gestor = fopen($maestro, "w")) !== FALSE) {
      chmod ($maestro, 0777);//Damos todos los permisos a las carpetas de lectura y escritura.	

          fputs($gestor, "id;codigo_producto;codigo;descripcion;codigo_unidad_medida;precio_venta;contiene"."\n"); // en la tx
					for ($i=0; $i < count($arrData['listaArticulos']); $i++) {

				 		   /*echo $arrData['listaArticulos'][$i]['EAN']."|";
						   echo $arrData['listaArticulos'][$i]['descripcion']."|";
						   echo $arrData['listaArticulos'][$i]['presentacion']."|";	
						   echo $arrData['listaArticulos'][$i]['precioLista']."\n";*/

						   $id           = $arrData['listaArticulos'][$i]['EAN'];
						   $codigo       = $arrData['listaArticulos'][$i]['EAN'];
				           $ean          = substr($arrData['listaArticulos'][$i]['EAN'],0,-1);
				           $descripcion  = $arrData['listaArticulos'][$i]['descripcion'];
				           $presentacion = $arrData['listaArticulos'][$i]['presentacion'];
				           $precioLista  = $arrData['listaArticulos'][$i]['precioLista']; 
				           $contiene     = 1; 
	

						   fputs($gestor, $id.";"); // en la tx
						   fputs($gestor, $codigo.";");
						   fputs($gestor, $ean.";"); 
						   fputs($gestor, $descripcion.";");
						   fputs($gestor, $presentacion.";");
						   fputs($gestor, $precioLista.";");
						   fputs($gestor, $contiene."\n");

	
		 			}

            fclose($gestor);

        }
        
        }


        public static function EliminaUltimaLinea ($file){
         // leemos el contenido del archivo y lo ponemos en un array
		$contenido = file($file);
		// obtenemos el numero de lineas y le restamos la ultima que es la linea a eliminar
		$last = count($contenido) - 1 ;
		if ($last>=0) {
		    // eliminamos la linea del array
		    unset($contenido[$last]);
		    // vamos a guardar en el archivo todo el contenido excepto la ultima linea

		    // abrimos el archivo para escritura y sin contenido

		    $fp = fopen($file, 'w');
		    // guardamos cada una de las lineas
		    fwrite($fp, implode('', $contenido));
		    // cerramos el archivo
		   fclose($fp);

		}


    }

      

    }

?>







