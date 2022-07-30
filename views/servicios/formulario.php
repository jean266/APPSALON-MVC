<div class="campo">
    <label for="nombre">Nombre:</label>
    <input 
        type="text" 
        name="nombre" 
        id="nombre" 
        placeholder="Nombre del Servicio"
        value="<?php echo s($servicio->nombre); ?>"
    />
</div>

<div class="campo">
    <label for="precio">Precio:</label>
    <input 
        type="number" 
        name="precio" 
        id="precio" 
        placeholder="Precio del Servicio"
        value="<?php echo s($servicio->precio); ?>"
    />
</div>