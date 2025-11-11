<?php
class Utils {

    /**
     * Muestra un mensaje de sesión (éxito o error) y luego lo borra.
     * $type: 'success' o 'error'
     */
    public static function showSessionMessage($session_name, $type) {
        
        // Define el color y la clase de texto (basado en style.css)
        $class_name = ($type == 'success') ? 'alt_green' : 'alt_red';
        $border_color = ($type == 'success') ? 'green' : 'darkred';
        
        $message = $_SESSION[$session_name] ?? null;

        if ($message) {
            // Imprime el bloque del mensaje
            echo "<div class='Contvcc' style='width: 100%; align-items: center; margin-top: 10px;'>";
            // Usamos $border_color en el estilo y $class_name para el texto
            echo "  <p class='Txwarning {$class_name}' style='padding: 10px; background-color: #f0f0f0; border: 1px solid {$border_color}; width: 80%; text-align: center;'>";
            echo "    <strong>" . htmlspecialchars($message) . "</strong>";
            echo "  </p>";
            echo "</div>";
            
            // Borra el mensaje para que no se muestre de nuevo
            unset($_SESSION[$session_name]);
        }
    }
}
?>