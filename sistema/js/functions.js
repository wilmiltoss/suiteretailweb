
//READY = cuando se carga todo el documento se cargan los scrips siguientes
 $(document).ready(function(){
    //scrip p/ crear el evento del select, search_proveedor LL.1, change= ejecuta una funcion
    $('#search_tienda').change(function(e) {
      e.preventDefault();

      var sistema = getUrl();
      //alert(sistema);
      //Direccionamos con location.href a la url q tiene la variable sistema y concatena con el archivo buscar_producto.php
      location.href = sistema+'lista_precio_cambios_tienda.php?tienda='+$(this).val();//this=este elemento hace el select
    });


}); // End Ready


  $(document).ready(function(){
    //scrip p/ crear el evento del select, search_proveedor LL.1, change= ejecuta una funcion
    $('#search_tienda_maestro').change(function(e) {
      e.preventDefault();

      var sistema = getUrl();
      //alert(sistema);
      //Direccionamos con location.href a la url q tiene la variable sistema y concatena con el archivo buscar_producto.php
      location.href = sistema+'lista_precio_maestro_tienda.php?tienda='+$(this).val();//this=este elemento hace el select
    });


}); // End Ready

$(document).ready(function() {   //$(document).ready se ejecuta cuando carga la pagina
     $(':input[type="submit"]').prop('disabled', false);   //desactiva el input al cargar
     $('input[type="number"]').keyup(function() {   //cuando presionas tecla 
        if($(this).val() != '') {
           $(':input[type="submit"]').prop('disabled', false);
        }
     });
 });




 //funcion url = retorna direccion url donde se encuentra nuestro proyecto
function getUrl(){
  var loc = window.location;
  var pathName = loc.pathname.substring(0,loc.pathname.lastIndexOf('/') + 1);
  return loc.href.substring(0, loc.href.length -((loc.pathname + loc.search + loc.hash).length - pathName.length));

}




function generarPDF(cliente, factura){//recibe dos parametros, cliente y nro de facutra
  //MOSTRAR LA VENTANA DE PDF AD.1  
        var ancho = 1000;//ancho de la ventana a mostrar
        var alto  = 800;//alto de la ventana
        //Calcular posicion x,y para centrar la ventana
        var x = parseInt((window.screen.width/2) - (ancho / 2));//calculamos en el centro
        var y = parseInt((window.screen.height/2) - (alto / 2));
        //enviamos datos por medio del metodo GET por medio de la url
        $url = 'factura/generaFactura.php?cl='+cliente+'&f='+factura;//la $url se dirige a la sgte carpeta y recibe dos varibles, cl cliente y f factura
       //window.open va abrir la url cargada arriba, indicando la posicion de la ventana //resizable indica si se va ser grande o pequeña la ventana
        window.open($url,"Factura","left"+x+",top"+y+",height="+alto+",width="+ancho+",scrollbar=si,location=no,resizable=si,menubar=no");
}







//boton cerrar formulario //ventanita, limpiamos la ventana al cerrar
function closeModal(){
    $('#txtCantidad').val('');//limpiamos los campos
     $('#txtPrecio').val('');
    $('.modal').fadeOut();

}



