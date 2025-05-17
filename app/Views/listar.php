<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Titulo ALF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> 

</head>
<body>
    Lista de libros mios!


?>

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

                <?php foreach($libros as $libro): ?>
                <tr>
                    <td><?= $libro['id'];?></td>
                    <td><?= $libro['imagen'];?></td>
                    <td><?= $libro['nombre'];?></td>
                    <td>editar/borrar</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>