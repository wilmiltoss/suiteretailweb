<?php
namespace App\Service;

use App\Util\DbContext,
    App\Util\Anexgrid,
    App\Model\Presentacion,
    PDOException,
    PDO;


class ProductService {
    public static function importFromCsv($file) {
        $presentaciones = [];

        $isFirstLine = true;
        //desde el csv, carga los datos
        if (($gestor = fopen($file, "r")) !== FALSE) {
            while (($datos = fgetcsv($gestor, 3000, "|")) !== FALSE) {
                if(!$isFirstLine) {
                    $presentacion = new Presentacion();
                    $presentacion->codigo_producto      = $datos[0];
                    $presentacion->codigo               = $datos[1];
                    $presentacion->descripcion          = $datos[2];
                    $presentacion->codigo_unidad_medida = $datos[3];
                    $presentacion->precio_venta         = $datos[4];

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
                    'INSERT INTO presentaciones (codigo_producto, codigo, descripcion, codigo_unidad_medida, precio_venta, contiene) VALUES (?, ?, ?, ?, ?, 1)'
                );

                $qry->execute([
                    $presentacion->codigo_producto,
                    $presentacion->codigo,
                    $presentacion->descripcion,
                    $presentacion->codigo_unidad_medida,
                    $presentacion->precio_venta
                ]);
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        }
    }

    public static function getAll() {
        try
        {
            DbContext::initialize();
            /* AnexGRID */
            $anexgrid = new AnexGrid();
            /* Los registros */
            $sql = "
                SELECT * FROM presentaciones
                ORDER BY $anexgrid->columna $anexgrid->columna_orden
                LIMIT $anexgrid->pagina, $anexgrid->limite
            ";

            $pdo = DbContext::getInstance();
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
            DbContext::initialize($nro_tienda);
            $qry = DbContext::getInstance()->prepare(
                'SELECT COUNT(*) total FROM presentaciones'
            );
            $qry->execute();
            return $qry->fetchColumn();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}
