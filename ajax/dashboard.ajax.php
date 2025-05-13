<?php
require_once "../controller/dashboard.controller.php";
require_once "../model/dashboard.php";

// Clase para manejar las solicitudes AJAX
class AjaxDashboard {
    // Método para obtener las ventas del mes actual
    public function getVentasMesActual() {
        try {
            $controller = new DashboardController();
            $ventasMesActual = $controller->ctrgetVentasMesActual();

            // Verifica si se obtuvieron ventas
            if ($ventasMesActual) {
                // Retorna la respuesta en formato JSON
                echo json_encode(['success' => true, 'ventas' => $ventasMesActual]);
            } else {
                echo json_encode(['success' => false, 'message' => 'No se encontraron datos de ventas.']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    // Método para obtener datos del dashboard
    public function getDatosDashboard() {
        try {
            $controller = new DashboardController();
            $datos = $controller->ctrgetDatosDashboard();
            echo json_encode($datos); // Retorna los datos en formato JSON
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
    public function getproductosMasVendidos() {
        $controller = new DashboardController();
        $productosMasVendidos = $controller->ctrProductosMasVendidos();
          echo json_encode($productosMasVendidos); // Retorna los datos en formato JSON
        
    }
    public function getProductoPocoStock() {
        $controller = new DashboardController();
        $productoPocoStock = $controller->ctrProductosPocoStock();
          echo json_encode($productoPocoStock); // Retorna los datos en formato JSON
        
    }

}

// Determina la acción y ejecuta el método correspondiente
if (isset($_POST['accion']) && $_POST['accion'] == 1) { // parametros para obtener venta del mes (graficos de barra )
    $ventasMesActual = new AjaxDashboard();
    $ventasMesActual->getVentasMesActual();
}else if (isset($_POST['accion']) && $_POST['accion'] == 2) { //  listar los 10 productos mas vendidos 
    $productosMasVendidos = new AjaxDashboard();
    $productosMasVendidos->getProductosMasVendidos();
 }else if (isset($_POST['accion']) && $_POST['accion'] == 3) { //  listar productos con poco stock
    $productoPocoStock = new AjaxDashboard();
    $productoPocoStock->getProductoPocoStock();
} else {
    $datos = new AjaxDashboard();
    $datos->getDatosDashboard();
}
?>
