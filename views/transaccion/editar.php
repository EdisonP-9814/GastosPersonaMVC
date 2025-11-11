<div class='Contvcc Frm1' style='margin-top: 20px;'>
  
  <form action="<?=base_url?>Transaccion/update&id=<?=$tx['id_transaccion']?>" class='Contvcc Frm2 Brd15' method="post">
    
    <h4>Editar <?=$tipo_tx == 'GASTO' ? 'Gasto' : 'Ingreso'?></h4>

    <input type="hidden" name="tipo" value="<?=$tipo_tx?>" />

    <label>Monto:</label>
    <input type="number" name="monto" value="<?=$tx['monto_transaccion']?>" step="0.01" required>

    <label>Descripción:</label>
    <input type="text" name="descripcion" value="<?=htmlspecialchars($tx['descripcion_transaccion'])?>" required>

    <label>Fecha:</label>
    <input type="date" name="fecha" value="<?=htmlspecialchars($tx['fecha_transaccion'])?>" required>

    <label>Cuenta:</label>
    <select name="cuenta" required>
        <option value="" disabled>-- Selecciona una cuenta --</option>
        <?php foreach($cuentas as $cuenta): ?>
            <option value="<?=$cuenta['id_cuenta']?>" <?=($cuenta['id_cuenta'] == $tx['id_cuenta_transaccion']) ? 'selected' : ''?>>
                <?=htmlspecialchars($cuenta['nombre_cuenta'])?> ($<?=number_format($cuenta['saldo_actual'], 2)?>)
            </option>
        <?php endforeach; ?>
    </select>

    <label>Categoría:</label>
    <select name="categoria" required>
        <option value="" disabled>-- Selecciona una categoría --</option>
        <?php foreach($categorias as $cat): ?>
            <option value="<?=$cat['id_categoria']?>" <?=($cat['id_categoria'] == $tx['id_categoria_principal']) ? 'selected' : ''?>>
                <?=htmlspecialchars($cat['nombre_categoria'])?>
            </option>
        <?php endforeach; ?>
    </select>

    <?php if($tipo_tx == 'GASTO'): ?>
        <label>Método de Pago:</label>
        <select name="metodo" required>
            <option value="" disabled>-- Selecciona un método --</option>
            <?php foreach($metodos as $metodo): ?>
                <option value="<?=$metodo['id_metodo']?>" <?=($metodo['id_metodo'] == $tx['id_metodo_transaccion']) ? 'selected' : ''?>>
                    <?=htmlspecialchars($metodo['nombre_metodo'])?>
                </option>
            <?php endforeach; ?>
        </select>
    <?php endif; ?>


    <div class='Conthcj' style='width: 75%; height: 60px;'>
      <input type="submit" value="Actualizar" class="Bt4b">
      <input type="button" value="Cancelar" class="Bt4r"
        onclick="location.href='<?=base_url?>Transaccion/index'">
    </div>
  </form>
</div>