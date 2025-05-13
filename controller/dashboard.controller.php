<?php 

class DashboardController {

    private $model;

    // Constructor para inicializar el modelo
    public function __construct() {
        $this->model = new DashboardModelo();
    }

    // Método para obtener los datos del dashboard
    public function ctrgetDatosDashboard() {
        try {
            // Llamar al modelo para obtener los datos
            $datos = $this->model->mdlGetDatosDashboard();

            // Retornar los datos obtenidos
            return $datos;
        } catch (Exception $e) {
            // En caso de que algo falle, mostrar el mensaje de error
            echo "Error: " . $e->getMessage();
            return false; // Retornar false en caso de error
        }
    }

    // Método para obtener las ventas del mes actual
    public function ctrgetVentasMesActual() {
        try {
            $ventasMesActual = $this->model->mdlGetVentasMesActual();
            return $ventasMesActual;  // Retorna el resultado como un array asociativo
        } catch (Exception $e) {
            // En caso de que algo falle, mostrar el mensaje de error
            echo "Error: " . $e->getMessage();
            return false; // Retorna false en caso de error
        }
    }
      public function ctrProductosMasVendidos() {
        try {
            $productosMasVendidos = $this->model->mdlProductosMasVendidos();
            return $productosMasVendidos;  // Retorna el resultado como un array asociativo
        } catch (Exception $e) {
            // En caso de que algo falle, mostrar el mensaje de error
            echo "Error: " . $e->getMessage();
            return false; // Retorna false en caso de error
        }
    }
    public function ctrProductosPocoStock() {
        try {
            $productosPocoStock = $this->model->mdlProductosPocoStock();
            return $productosPocoStock;  // Retorna el resultado como un array asociativo
        } catch (Exception $e) {
            // En caso de que algo falle, mostrar el mensaje de error
            echo "Error: " . $e->getMessage();
            return false; // Retorna false en caso de error
        }
    }
}

?>
