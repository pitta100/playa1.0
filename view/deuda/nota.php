<form id="crud-frm" method="post" action="?c=nota&a=guardarNota" enctype="multipart/form-data">
    <div class="form-group">
        <label for="fecha">Fecha</label>
        <input type="date" class="form-control" id="fecha" name="fecha" required>
    </div>
    <div class="form-group">
        <label for="nota">Nota</label>
        <textarea class="form-control" id="nota" name="nota" rows="3" required></textarea>
    </div>
    <div id="mensaje"></div>
    <button type="submit" class="btn btn-primary">Guardar Nota</button>
</form>


<script>
    $(document).ready(function() {
        // Cuando el formulario sea enviado
        $('#form-nota').submit(function(e) {
            e.preventDefault(); // Evita el comportamiento por defecto (recarga de la página)
            
            var fecha = $('#fecha').val();
            var nota = $('#nota').val();
            
            // Verificar que los campos no estén vacíos
            if (fecha == "" || nota == "") {
                $('#mensaje').html('<div class="alert alert-danger">Todos los campos son obligatorios.</div>');
                return;
            }

            // Hacer la solicitud AJAX
            $.ajax({
                url: '?c=nota&a=guardarNota',  // Aquí va la URL de tu controlador y acción para guardar la nota
                method: 'POST',
                data: {
                    fecha: fecha,
                    nota: nota
                },
                success: function(response) {
                    // Suponemos que el controlador devuelve un mensaje de éxito
                    if (response.success) {
                        $('#mensaje').html('<div class="alert alert-success">Nota guardada correctamente.</div>');
                        $('#form-nota')[0].reset(); // Limpiar el formulario
                    } else {
                        $('#mensaje').html('<div class="alert alert-danger">Hubo un error al guardar la nota.</div>');
                    }
                },
                error: function(xhr, status, error) {
                    // Manejar errores de AJAX
                    $('#mensaje').html('<div class="alert alert-danger">Ocurrió un error al procesar la solicitud.</div>');
                }
            });
        });
    });
</script>
