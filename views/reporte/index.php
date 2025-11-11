<div class='Contvcc Frm1' style='margin-top: 20px;'>
    
    <div style="width: 80%; margin-top: 20px;">
        <h4>Reporte de Gastos por Categoría</h4>
        <p>
            Aquí puedes ver un resumen de tus gastos agrupados por categoría.
        </p>
        
        <?php if (empty($datos_reporte)): ?>
            <p class="Txwarning" style="margin-top: 20px;">
                Aún no tienes gastos registrados para mostrar en el reporte.
            </p>
        <?php else: ?>
            <table style='margin-top: 20px; width: 60%;'> <thead>
                    <tr>
                        <th>Categoría</th>
                        <th>Total Gastado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $total_general = 0;
                        foreach ($datos_reporte as $fila): 
                        $total_general += $fila['total_gastado'];
                    ?>
                        <tr>
                            <td><?=htmlspecialchars($fila['nombre_categoria'])?></td>
                            <td>
                                <span class_="alt_red">
                                    $<?=number_format($fila['total_gastado'], 2)?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr style="background-color: #f0f0f0; font-weight: bold;">
                        <td style="text-align: right; padding-right: 10px;">TOTAL GASTADO</td>
                        <td>
                            <span class="alt_red">
                                $<?=number_format($total_general, 2)?>
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        <?php endif; ?>
    </div>
</div>
