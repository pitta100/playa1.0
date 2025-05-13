<?php 

require_once "database.php";

class DashboardModelo {

    public function mdlGetDatosDashboard() {
        try {
            // Preparar la consulta
            $stmt = Database::StartUp()->prepare('CALL prc_ObtenerDatosDashboard()');

            // Ejecutar la consulta
            $stmt->execute();

            // Retornar los resultados
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            // Manejo de errores en caso de fallo en la consulta
            echo "Error: " . $e->getMessage();
            return false; // Retorna false en caso de error
        } finally {
            // Cierra la conexión si es necesario (esto depende de la implementación de Database::StartUp)
            // $stmt = null; // Opcional, dependiendo de la implementación
        }
    }
    public function mdlGetVentasMesActual() {
        try {
            // Preparar la consulta
            $stmt = Database::StartUp()->prepare('CALL prc_ObtenerVentasMesActual()');

            // Ejecutar la consulta
            $stmt->execute();

            // Retornar los resultados
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            // Manejo de errores en caso de fallo en la consulta
            echo "Error: " . $e->getMessage();
            return false; // Retorna false en caso de error
        } finally {
            // Cierra la conexión si es necesario (esto depende de la implementación de Database::StartUp)
            // $stmt = null; // Opcional, dependiendo de la implementación
        }
    }
     public function mdlProductosMasVendidos() {
        try {
            // Preparar la consulta
            $stmt = Database::StartUp()->prepare('CALL pitta100productosMasVendidos()');

            // Ejecutar la consulta
            $stmt->execute();

            // Retornar los resultados
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            // Manejo de errores en caso de fallo en la consulta
            echo "Error: " . $e->getMessage();
            return false; // Retorna false en caso de error
        } finally {
            // Cierra la conexión si es necesario (esto depende de la implementación de Database::StartUp)
            // $stmt = null; // Opcional, dependiendo de la implementación
        }
    }
     public function mdlProductosPocoStock() {
        try {
            // Preparar la consulta
            $stmt = Database::StartUp()->prepare('CALL pitta100productosPocoStock()');

            // Ejecutar la consulta
            $stmt->execute();

            // Retornar los resultados
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            // Manejo de errores en caso de fallo en la consulta
            echo "Error: " . $e->getMessage();
            return false; // Retorna false en caso de error
        } finally {
            // Cierra la conexión si es necesario (esto depende de la implementación de Database::StartUp)
            // $stmt = null; // Opcional, dependiendo de la implementación
        }
    }


}

?>
