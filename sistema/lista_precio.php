
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php";?>
	<title>Listado de Precios</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">

		<h2><i class="fas fa-cube"></i> Lista de Cambios</h2>
		<!--<form action="buscar_producto.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
			<button type="submit" class="btn_search"><i class="fas fa-search"></i></button>
		</form>-->
			<table>
				<tr>
				<td>
			<?php 
				$url = "http://192.168.236.132:8080/VierciApi/service?actividad=listar_tiendas&tienda=0";
				$arrDataUrl = file_get_contents($url);
				$arrData = json_decode($arrDataUrl,true);
				$total_registro = count($arrData['tiendas']);
			?>
 				 <!-- LISTA DESPLEGABLE de la tienda-->
			<select name="tienda" id="search_tienda"><!--LL.1-->
 					<option value="" selected>LOCAL</option><!--O .1-->
 					<?php 
 						if ($total_registro > 0) {

 							for ($i=0; $i < count($arrData['tiendas']); $i++) {
 								 $_id = $arrData['tiendas'][$i]['local'];
 								 $_tienda = $arrData['tiendas'][$i]['descripcion'];
								
 						?>
 							<option value="<?php echo $_id; ?>"><?php echo $_id.' - '.$_tienda; ?></option>
 						<?php 

 							}
 						}
 					?>	
 				</select>

		<div>
			<?php 
			/*$tienda = 123;
			$url = "http://192.168.236.132:8080/VierciApi/service?actividad=listar_maestro_actualizado&tienda=".$tienda."&caja=0";
			$arrDataUrl = file_get_contents($url);
			$arrData = json_decode($arrDataUrl,true);
			$total_registro = count($arrData['listaArticulos']);*/
			?>
				</td>
				<td>Total Registro <?php echo "0"; ?></td>
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