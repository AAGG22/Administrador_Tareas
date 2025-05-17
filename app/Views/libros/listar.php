<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Titulo ALF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> 

</head>
<body>
    Lista de libros mios, dentro de la carpeta LIBROS!
<?php print_r($libros);?>
    <div class="container">
        <table class="table table-light">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>ID</td>
                    <td>imagen</td>
                    <td>nombre</td>
                    <td>editar/borrar</td>

                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>