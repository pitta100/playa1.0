<?php 
require_once 'model/nota.php';
require_once 'model/venta.php';
require_once 'model/venta_tmp.php';
require_once 'model/producto.php';
require_once 'model/ingreso.php';
require_once 'model/deuda.php';
require_once 'model/egreso.php';
require_once 'model/cliente.php';
require_once 'model/cierre.php';


class notaController{
    
    private $model;
    
    public function __CONSTRUCT(){
        $this->model = new nota();
        $this->venta_tmp = new venta_tmp();
        $this->cierre = new cierre();
        $this->producto = new producto();
        $this->ingreso = new ingreso();
        $this->venta = new venta();
        $this->egreso = new egreso();
        $this->cliente = new cliente();
        $this->deuda = new deuda();
    }
      
   
    public function guardarNota() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtener los datos del formulario
            $fecha = $_POST['fecha'];
            $nota = $_POST['nota'];

            // Crear el objeto Nota
            $notaObj = new Nota();
            $notaObj->fecha = $fecha;
            $notaObj->nota = $nota;

            // Registrar la nota
            $resultado = $this->model->Registrar($notaObj);

            // Verificar si la inserción fue exitosa
            if ($resultado === "Agregado") {
                echo json_encode(['success' => true, 'message' => 'Nota guardada correctamente']);
            } else {
                echo json_encode(['success' => false, 'message' => $resultado]);  // Aquí puedes devolver el mensaje de error
            }
        }
       header('Location:' . getenv('HTTP_REFERER'));
    }
    public function ctrlistarNotasCalendar() {
    try {
        $notas = $this->model->mdllistarNotasCalendar();  // Llamamos al modelo para obtener las notas
        if ($notas) {
            // Si hay notas, las devolvemos como JSON
            echo json_encode($notas, JSON_UNESCAPED_UNICODE);  
        } else {
            // Si no hay notas, devolvemos un arreglo vacío
            echo json_encode([]);
        }
    } catch (Exception $e) {
        // Si ocurre un error, capturamos la excepción y la mostramos como JSON
        echo json_encode(["error" => "Hubo un problema al cargar las notas"]);
    }
}





     

}






 ?>