<h1 class="nombre-pagina">Nuevo Servicio</h1>
<p class="descripcion-pagina">LLena todos los campos para agregar un nuevo servicios</p>

<a href="/servicios" class="boton--block">Volver</a>

<?php 
    include_once __DIR__ . '/../templates/alertas.php'; 
?>

<form action="/servicios/crear" class="formulario" method="POST">

    <?php include_once __DIR__ . '/formulario.php'; ?>

    <input type="submit" value="Crear" class="boton">

</form>