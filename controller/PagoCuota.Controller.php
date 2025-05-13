<?php
require_once 'model/pago_cuota.php';  // Asegúrate de incluir el modelo

class PagoCuotaController{
    
    private $model;

    public function __CONSTRUCT(){
        $this->model = new PagoCuota();  // Inicia el modelo para trabajar con los pagos de las cuotas
    }
    
    // Mostrar todas las cuotas de una deuda específica
    public function ListarPorDeuda($id_deuda){
        $cuotas = $this->model->ListarPorDeuda($id_deuda); // Llama al método que obtiene las cuotas
        require_once 'view/header.php';
        require_once 'view/pago_cuota/listar.php';  // Aquí se mostrarían las cuotas
        require_once 'view/footer.php';
    }

    // Marcar una cuota como pagada
    public function PagarCuota($id_cuota){
        $this->model->PagarCuota($id_cuota);  // Llama al método que marca una cuota como pagada
        header('Location: index.php?c=PagoCuota&a=ListarPorDeuda&id_deuda=' . $_GET['id_deuda']);  // Redirige a la página con las cuotas actualizadas
    }
    public function MarcarPagada() {
        $id_deuda = $_POST['id_deuda'];
        $nro_cuota = $_POST['nro_cuota'];

        $this->model->PagarCuota($id_deuda, $nro_cuota);

        header("Location: index.php?c=PagoCuota&a=ListarPorDeuda&id=$id_deuda");
    }

    
}
?>
