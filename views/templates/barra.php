<div class="barra">
    <p>Hola: <?php echo $nombre ?? ''; ?></p>
    <a href="/logout" class="boton">Cerrar Sesión</a>
</div>

<?php if($_SESSION['admin'] === 1):?>
    <div class="barra-servicios">
        <a class="boton" href="/admin">Ver Citas</a>
        <a class="boton" href="/servicios">Ver Servicios</a>
        <a class="boton" href="/servicios/crear">Nuevo Servicio</a>
    </div>
<?php endif; ?>