<?php
class nota
{
	private $pdo;
    
    public $id;
    public $fecha;
    public $nota;
    public $usuario_id;
    public $create_at;
   
    
	public function __CONSTRUCT()
	{
		try
		{
			$this->pdo = Database::StartUp();     
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function Registrar(Nota $data) {
        try {
            // Consulta SQL para insertar la nueva nota, sin el campo usuario_id
            $sql = "INSERT INTO notas (fecha, nota) VALUES (?, ?)";

            // Ejecutamos la consulta utilizando los datos de la nota
            $this->pdo->prepare($sql)->execute([
                $data->fecha,
                $data->nota
            ]);

            return "Agregado";  // Si la inserción fue exitosa
        } catch (Exception $e) {
            // Devolver un mensaje de error
            return "Error: " . $e->getMessage();
        }
    }
    public function mdllistarNotasCalendar() {
    try {
        // Consulta para obtener las notas
        $stmt = $this->pdo->prepare("SELECT 
                                    n.id, 
                                    n.nota,  
                                    n.fecha  
                                FROM 
                                    notas n
                                WHERE 
                                    n.fecha IS NOT NULL
                                ORDER BY 
                                    n.fecha");
        $stmt->execute();
        $notas = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        return $notas;
    } catch (Exception $e) {
        // Captura los errores y los muestra de forma amigable
        return ['error' => 'Error en la consulta: ' . $e->getMessage()];
    }
}








}
