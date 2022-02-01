<?php
namespace App\Service;

use App\Util\DbContext2,
    App\Util\Anexgrid,
    App\Model\Presentacion,
    PDOException,
    PDO;


class ProductService2 {
   public static function importFromCsv($file) {
        $presentaciones = [];

        $isFirstLine = true;
        //desde el csv, carga los datos
        if (($gestor = fopen($file, "w+")) !== FALSE) {
            while (($datos = fgetcsv($gestor, 10000, ";")) !== FALSE) {
                if(!$isFirstLine) {
                    $presentacion = new Presentacion();
                    $presentacion->id                   = $datos[0];
                    $presentacion->codigo_producto      = $datos[1];
                    $presentacion->codigo               = $datos[2];
                    $presentacion->descripcion          = $datos[3];
                    $presentacion->codigo_unidad_medida = $datos[4];
                    $presentacion->precio_venta         = $datos[5];
                    $presentacion->contiene             = $datos[6];

                    $presentaciones[] = $presentacion;
                } else {
                    $isFirstLine = false;
                }
            }
            fclose($gestor);
        }

        foreach($presentaciones as $presentacion) {
            try {
                DbContext::initialize();
                $qry = DbContext::getInstance()->prepare(
                    'INSERT INTO presentaciones (id,codigo_producto, codigo, descripcion, codigo_unidad_medida, precio_venta, contiene) VALUES (?, ?, ?, ?, ?, ?, ?)'
                );

                $qry->execute([
                    $presentacion->id,
                    $presentacion->codigo_producto,
                    $presentacion->codigo,
                    $presentacion->descripcion,
                    $presentacion->codigo_unidad_medida,
                    $presentacion->precio_venta,
                    $presentacion->contiene
                ]);
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        }
    }

    public static function getAll() {
        try
        {
            DbContext2::initialize();
            /* AnexGRID */
            $anexgrid = new AnexGrid();
            /* Los registros */
            $sql = "
                SELECT * FROM presentaciones
                ORDER BY $anexgrid->columna $anexgrid->columna_orden
                LIMIT $anexgrid->pagina, $anexgrid->limite
            ";

            $pdo = DbContext2::getInstance();
            $stm = $pdo->prepare( $sql );
            $stm->execute();
            $result = $stm->fetchAll(PDO::FETCH_OBJ);

            /* El total de registros */
            $total = $pdo->query("
                SELECT COUNT(*) Total
                FROM presentaciones
            ")->fetchObject()->Total;

            return $anexgrid->responde($result, $total);
        }
        catch(Exception $e)
        {
            die($e->getMessage());
        }
    }

    public static function count($nro_tienda) {
        try {
            DbContext2::initialize($nro_tienda);
            $qry = DbContext2::getInstance()->prepare(
                'SELECT COUNT(*) total FROM presentaciones'
            );
            $qry->execute();
            return $qry->fetchColumn();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}
