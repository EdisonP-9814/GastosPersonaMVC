<div class='Contvcc Frm1' style='margin-top: 20px;'>
  
  <form action="<?=base_url?>Transaccion/save" class='Contvcc Frm2 Brd15' method="post">
    
    <?php if($tipo_tx == 'GASTO'): ?>
        <h4>Registrar Nuevo Gasto</h4>
    <?php else: ?>
        <h4>Registrar Nuevo Ingreso</h4>
    <?php endif; ?>

    <input type="hidden" name="tipo" value="<?=$tipo_tx?>" />

    <label>Monto:</label>
    <input type="number" name="monto" placeholder="0.00" step="0.01" required>

    <label>Descripción:</label>
    <input type="text" name="descripcion" placeholder="Ej: Compra supermercado" required>

    <label>Fecha:</label>
    <input type="date" name="fecha" value="<?=date('Y-m-d')?>" required>

    <label>Cuenta:</label>
    <select name="cuenta" required>
        <option value="" disabled selected>-- Selecciona una cuenta --</option>
        <?php foreach($cuentas as $cuenta): ?>
            <option value="<?=$cuenta['id_cuenta']?>">
                <?=htmlspecialchars($cuenta['nombre_cuenta'])?> ($<?=number_format($cuenta['saldo_actual'], 2)?>)
            </option>
        <?php endforeach; ?>
    </select>

    <label>Categoría:</label>
    <select name="categoria" required>
        <option value="" disabled selected>-- Selecciona una categoría --</option>
        <?php foreach($categorias as $cat): ?>
            <option value="<?=$cat['id_categoria']?>">
                <?=htmlspecialchars($cat['nombre_categoria'])?>
            </option>
        <?php endforeach; ?>
    </select>

    <?php if($tipo_tx == 'GASTO'): ?>
        <label>Método de Pago:</label>
        <select name="metodo" required>
            <option value="" disabled selected>-- Selecciona un método --</option>
            <?php foreach($metodos as $metodo): ?>
                <option value="<?=$metodo['id_metodo']?>">
                    <?=htmlspecialchars($metodo['nombre_metodo'])?>
                </option>
            <?php endforeach; ?>
        </select>
    <?php endif; ?>


    <div class='Conthcj' style='width: 75%; height: 60px;'>
      <input type="submit" value="Guardar" class="Bt4b">
      <input type="button" value="Cancelar" class="Bt4r"
        onclick="location.href='<?=base_url?>'">
    </div>
  </form>
</div>