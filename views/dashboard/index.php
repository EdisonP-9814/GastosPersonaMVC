<div class='Contvcc Frm1' style='margin-top: 20px;'>
    
    <div style="width: 80%; margin-top: 20px;">
        <h4>Mi Dashboard</h4>
        <p>
            Este es tu panel principal. Aquí puedes ver tus movimientos recientes y accesos directos.
        </p>
        
        <div class='Conthcs' style='width: 100%; margin: 20px 0;'>
            <a href="<?=base_url?>Cuenta/index" class="Bt4b" style="width: 150px;">Ver mis Cuentas</a>
            <a href="<?=base_url?>Transaccion/crear" class="Bt4b" style="width: 150px; background-color: #41c16b;">Nuevo Gasto</a>
            <a href="<?=base_url?>Transaccion/crearIngreso" class="Bt4b" style="width: 150px; background-color: #25739d;">Nuevo Ingreso</a>
        </div>
        
        <h5 style="margin-top: 30px;">Movimientos Recientes</h5>
        
        <?php if (empty($transacciones)): ?>
            <p class="Txwarning" style="margin-top: 20px;">
                Aún no tienes transacciones registradas.
            </p>
        <?php else: ?>
            <table style='margin-top: 10px;'>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Descripción</th>
                        <th>Categoría</th>
                        <th>Cuenta</th>
                        <th>Monto</th>
                        <th>Acciones</th> </tr>
                </thead>
                <tbody>
                    <?php foreach ($transacciones as $tx): ?>
                        <tr>
                            <td><?=htmlspecialchars($tx['fecha_transaccion'])?></td>
                            <td><?=htmlspecialchars($tx['descripcion_transaccion'])?></td>
                            <td><?=htmlspecialchars($tx['nombre_categoria'])?></td>
                            <td><?=htmlspecialchars($tx['nombre_cuenta'])?></td>
                            <td>
                                <span class="<?=($tx['tipo_transaccion'] == 'GASTO') ? 'alt_red' : 'alt_green'?>">
                                    <?=($tx['tipo_transaccion'] == 'GASTO') ? '-' : '+'?>
                                    $<?=number_format($tx['monto_transaccion'], 2)?>
                                </span>
                            </td>
                            <td>
                                <a href="<?=base_url?>Transaccion/editar&id=<?=$tx['id_transaccion']?>" class="Bt2">Editar</a>
                                <a href="<?=base_url?>Transaccion/eliminar&id=<?=$tx['id_transaccion']?>" class="Bt2r">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
