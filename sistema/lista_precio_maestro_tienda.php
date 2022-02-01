<?php 

	 //Filtro de productos que viene de la grilla
        if (!empty($_REQUEST['tienda'])) {
			$tienda_url  = $_REQUEST['tienda'];			
			//echo $tienda_url;
		}	
		//Mostrar Tienda seleccionada que viene de la url
		$url_t = "http://192.168.236.132:8080/VierciApi/service?actividad=listar_tiendas&tienda=".$tienda_url;
		$selectUrl = file_get_contents($url_t);
		$tienda_json = json_decode($selectUrl,true);
		$count = count($tienda_json['tiendas']);

		$select_id = $tienda_json['tiendas'][0]['local'];//el primer registro del json
		$select_desc = $tienda_json['tiendas'][0]['descripcion'];
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php";?>
	<title>Lista de Productos</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		<form action="" method="post">
 				<label for="nombre">Panel de Control</label>

 			<table>
 			<tr>
                 <th><a href="../sqlite/import2.php?tienda=<?php echo $select_id; ?>" class="btn_new"> <i class="fas fa-cash-register"></i> Actualizar Precios</a></th>
 				 <th>

 				 	<select>
 				 <!-- LISTA DESPLEGABLE colectores-->
 				<?php 
				$url_col = "http://192.168.236.132:8080/VierciApi/service?actividad=listar_colector&tienda=".$tienda_url;
				$colDataUrl = file_get_contents($url_col);
				$arrCol = json_decode($colDataUrl,true);
				$total_colector = count($arrCol['colectores']);
				$select_ip = $arrCol['colectores'][0]['ip'];
		        ?>

	 			  <option value="" selected>Lista Colectores</option>
 					<?php 
 						if ($total_colector > 0) {
 							for ($i=0; $i < count($arrCol['colectores']); $i++) {
 								 $_ip = $arrCol['colectores'][$i]['ip'];	
 					?>
 					<option value="<?php echo $_ip; ?>"><?php echo $_ip; ?></option>
 					<?php 
 						}
 					}
 					?>
 				    </select>
				</th>
					 <th><a href="transmitir_datos.php?ip=<?php echo $_ip; ?>&tienda=<?php echo $tienda_url; ?>" class="btn_new"><i class="fas fa-mobile-alt"></i> Transmitir Archivo al Colector</a>
 				 	 </th>

                <th><a href="genera_txt_balanza_maestro.php?tienda=<?php echo $select_id; ?>" class="btn_new" > <i class="fas fa-balance-scale-right"></i> Generar Archivo p/ Balanzas</a></th>

               </tr>
            </table>	
 		</form>	
 		<h2><i class="fas fa-cube"></i> Lista de Cambios</h2>
			<table>
				<tr>
				<td>   
					 <!-- LISTA DESPLEGABLE de la tienda-->
		 <?php 

				/*$url = "http://192.168.236.132:8080/VierciApi/service?actividad=listar_tiendas";
				$arrDataUrl = file_get_contents($url);
				$arrData = json_decode($arrDataUrl,true);
				$total_registro = count($arrData['tiendas']);*/
		  ?>
			<select name="tienda" id="search_tienda">
 					<option value="" selected><?php echo $select_id.' - '.$select_desc; ?></option>
 					<?php /*
 						if ($total_registro > 0) {
 							for ($i=0; $i < count($arrData['tiendas']); $i++) {
 								 $_id = $arrData['tiendas'][$i]['local'];
 								 $_tienda = $arrData['tiendas'][$i]['descripcion'];		*/
 			
 						//}
 					//}
 					?>	
 				</select>

		<div>
			<?php
			//Listar el maestro completo de los Articulos de la tienda seleccionada
			$url = "http://192.168.236.132:8080/VierciApi/service?actividad=listar_maestro_articulos&tienda=".$tienda_url."&caja=0";
			$arrDataUrl = file_get_contents($url);
			$arrData = json_decode($arrDataUrl,true);
			$total_registro = count($arrData['listaArticulos']);
			?>   
				</td>
				<td>Total Registro <?php echo $total_registro; ?></td>
				</tr>

			</table>
		</div>

	<table>
			<tr>
				<th>Código</th>
				<th>Descripción</th>
				<th>Precio</th>
				<th>Presentación</th>
				<th>Fecha Actualizacion</th>

			</tr>

	 <?php 		
		for ($i=0; $i < count($arrData['listaArticulos']); $i++) {

 		   $ean          = $arrData['listaArticulos'][$i]['EAN'];
		   $descripcion  = $arrData['listaArticulos'][$i]['descripcion'];
		   $precioLista  = $arrData['listaArticulos'][$i]['precioLista'];
		   $presentacion = $arrData['listaArticulos'][$i]['presentacion'];
		   $fecha        = $arrData['listaArticulos'][$i]['fechaActualizacion'];
			
				?>	
				<!-- ACTUALIZAMOS LA PLANILLA INMEDIATAMENTE DESPUES DE REALIZAR UN CAMBIO -->
				<!-- row = Clase q identifica a c/u de los productos -->
					<tr class="row<?php  ?>">
						<td><?php echo $ean; ?></td>
						<td><?php echo $descripcion; ?></td>
						<td><?php echo number_format($precioLista,0, ",", "."); ?></td>
						<td><?php echo $presentacion ; ?></td>
						<td><?php echo $fecha; ?></td>
					
					</tr>
				<?php 					
					}
				
			 ?>		

		</table>


	</section>

	<?php include "includes/footer.php"; ?>

</body>
</html>