<?php

//Creamos la bd sqlite con las tablas
namespace App\Util;

use PDO, PDOException;

class DbContext2 {
    private static $db = null;

    public static function initialize($nro_tienda) {
        if(empty(self::$db)) {

    $files = '../files/sucursales/'.$nro_tienda.'/retaildata.db';
    //chmod ($files, 0777);//Damos todos los permisos a las carpetas de lectura y escritura.  
            try {
                self::$db = new PDO('sqlite:'.$files);
                self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        }
    }

    public static function getInstance() {
        return self::$db;
    }
    
    public static function generateSchema() {
        $_categorias = '
        CREATE TABLE IF NOT EXISTS categorias (
                id          INTEGER PRIMARY KEY NOT NULL,
                descripcion VARCHAR NOT NULL,
                codigo      VARCHAR NOT NULL UNIQUE
        )';

        $_etiquetas = '
        CREATE TABLE IF NOT EXISTS etiquetas (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            descripcion VARCHAR(100)  NULL,
            alto INTEGER,
            ancho INTEGER,
            margen_superior INTEGER,
            margen_izquierdo INTEGER,
            nombre_clase VARCHAR DEFAULT ``
        )';

        $_filtros_busquedas= '
        CREATE TABLE IF NOT EXISTS filtros_busquedas (
            id              INTEGER PRIMARY KEY NOT NULL,
            descripcion     VARCHAR NOT NULL,
            tabla           VARCHAR NOT NULL,
            campo           VARCHAR NOT NULL,
            operador        VARCHAR NOT NULL,
            formato_valor_comparado VARCHAR NOT NULL
        )';

        $_presentaciones= '
        CREATE TABLE IF NOT EXISTS presentaciones (
            id                    INTEGER PRIMARY KEY  NULL,
            codigo_producto       VARCHAR  NULL,
            codigo                VARCHAR  NULL,
            descripcion           VARCHAR  NULL,
            codigo_unidad_medida  VARCHAR  NULL,
            precio_venta          DOUBLE   NULL DEFAULT 0,
            contiene              DOUBLE   NULL DEFAULT 1
        )';

         $_productos='
         CREATE TABLE IF NOT EXISTS productos (
            id INTEGER  PRIMARY KEY NOT NULL UNIQUE,
            codigo_interno   VARCHAR  NOT NULL,
            descripcion      VARCHAR  NOT NULL,
            detalles         VARCHAR  NOT NULL,
            fecha_ins        DATETIME NOT NULL DEFAULT (CURRENT_TIMESTAMP),
            fecha_act        DATETIME NOT NULL DEFAULT (CURRENT_TIMESTAMP),
            codigo_categoria VARCHAR
        )';

         $_retiros='
         CREATE TABLE IF NOT EXISTS retiros (
            id                  INTEGER  PRIMARY KEY AUTOINCREMENT NOT NULL,
            codigo_presentacion VARCHAR  NOT NULL UNIQUE,
            vencimiento         DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            proximo_vencimiento DATETIME NOT NULL,
            cantidad            DOUBLE   NOT NULL DEFAULT 1,
            stock_id            NUMBER   NOT NULL
        )';

         $_top_n_ventas='
         CREATE TABLE IF NOT EXISTS top_n_ventas (
            id                  INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            codigo_presentacion VARCHAR NOT NULL UNIQUE,
            codigo_categoria    VARCHAR NOT NULL,
            ranking             INTEGER NOT NULL,
            marca               VARCHAR NOT NULL
        )';

         $_unidades='
         CREATE TABLE IF NOT EXISTS unidades (
            id          INTEGER PRIMARY KEY NOT NULL,
            descripcion VARCHAR NOT NULL,
            codigo      VARCHAR NOT NULL
        )';

         $_vencidos='
         CREATE TABLE IF NOT EXISTS vencidos (
            id                  INTEGER  PRIMARY KEY AUTOINCREMENT NOT NULL,
            codigo_presentacion VARCHAR  NOT NULL UNIQUE,
            vencimiento         DATETIME NOT NULL,
            retirado            BOOL     NOT NULL DEFAULT false
        )';



        try {
            self::$db->exec($_categorias);
            self::$db->exec($_etiquetas);
            self::$db->exec($_filtros_busquedas);
            self::$db->exec($_presentaciones);
            self::$db->exec($_productos);
            self::$db->exec($_retiros);
            self::$db->exec($_top_n_ventas);
            self::$db->exec($_unidades);
            self::$db->exec($_vencidos);

        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }


    public static function cargarTablasFijas(){
        $_delete_etiquetas= '
        DELETE from etiquetas
        ';
        $_insert_etiquetas= '
        INSERT INTO ETIQUETAS VALUES (1,"Fleje Estandar Super",278,750,0,30,"FlejeEstandarSuper");
        INSERT INTO ETIQUETAS VALUES (2,"Fleje Mayorista Super",27,100,7,30,"FlejeMayoristaSuper")
        ';

        $_delete_categorias= '
        DELETE from CATEGORIAS
        ';
        $_insert_categorias= '
        INSERT INTO CATEGORIAS VALUES (4249,"PERECEDEROS",100000000);
        INSERT INTO CATEGORIAS VALUES (4630,"P.G.C.",200000000);
        INSERT INTO CATEGORIAS VALUES (5773,"TEXTIL",400000000);
        INSERT INTO CATEGORIAS VALUES (5774,"BAZAR",300000000);
        INSERT INTO CATEGORIAS VALUES (7721,"ELECTRODOMESTICOS",500000000);
        INSERT INTO CATEGORIAS VALUES (8077,"INSUMOS",600000000);
        INSERT INTO CATEGORIAS VALUES (8614,"CATEGORIA PENDIENTE",1000000000);
        INSERT INTO CATEGORIAS VALUES (14964,"MEDICAMENTOS ",800000000);
        INSERT INTO CATEGORIAS VALUES (15675,"HOSPITALARIOS",900000000);
        INSERT INTO CATEGORIAS VALUES (16654,"PERECEDEROS (CARNES, F&V) ",700000000);
        ';

        $_delete_filtro_busqueda= '
        DELETE from FILTROS_BUSQUEDAS
        ';
        $_insert_filtro_busqueda= '
        INSERT INTO FILTROS_BUSQUEDAS VALUES (1,"Codigo Interno Igual","PRODUCTOS","CODIGO_INTERNO","=","{0}");
        INSERT INTO FILTROS_BUSQUEDAS VALUES (2,"Codigo Barras Igual","PRESENTACIONES","CODIGO","=","{0}");
        ';

        $_delete_unidades= '
        DELETE from UNIDADES
        ';
        $_insert_unidades= '
        INSERT INTO UNIDADES VALUES (1,"PIEZAS","PCS");
        INSERT INTO UNIDADES VALUES (2,"LITROS","LTS");
        INSERT INTO UNIDADES VALUES (3,"KILOGRAMOS","KGS");
        ';

        try {
            self::$db->exec($_delete_etiquetas);  
            self::$db->exec($_insert_etiquetas);
            self::$db->exec($_delete_categorias);  
            self::$db->exec($_insert_categorias);
            self::$db->exec($_delete_filtro_busqueda);  
            self::$db->exec($_insert_filtro_busqueda);
            self::$db->exec($_delete_unidades);  
            self::$db->exec($_insert_unidades);

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public static function migraApiLite($tienda){

        $db = new PDO('sqlite:../files/sucursales/'.$tienda.'/retaildata.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

       // $_delete_presentaciones= 'DELETE from presentaciones';

        $url = "http://192.168.236.132:8080/VierciApi/service?actividad=listar_maestro_articulos&tienda=".$tienda."&caja=0";
        $arrDataUrl = file_get_contents($url);//consultamos el webservice
        $arrData = json_decode($arrDataUrl,true);//decodifiamos el json, extrayendo el array con true

        echo count($arrData['listaArticulos'])." items";
   
     for ($i=0; $i < count($arrData['listaArticulos']); $i++) {

           $id           = $arrData['listaArticulos'][$i]['EAN'];
           $ean          = substr($arrData['listaArticulos'][$i]['EAN'],0,-1);
           $descripcion  = $arrData['listaArticulos'][$i]['descripcion'];
           $presentacion = $arrData['listaArticulos'][$i]['presentacion'];
           $precioLista  = $arrData['listaArticulos'][$i]['precioLista'];     
    

           $requete = $db->prepare("INSERT INTO presentaciones VALUES ('$id', '$id', '$ean', '$descripcion', '$presentacion', '$precioLista',1)");
            $requete->execute();
    }


    }




}
