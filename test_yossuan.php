<?php
require_once 'src/lexico.php';

echo "--- Probando error_lexico.chc ---\n";
$codigo = file_get_contents('pruebas/error_lexico.chc');

// Imprimimos el contenido exacto para ver si el @ está ahí
echo "Contenido del archivo:\n";
var_dump($codigo);

try {
    $tokens = analizarLexico($codigo);
    echo "Éxito (no debería pasar).\n";
    // Si llega aquí, es porque no lanzó error. Imprimimos los tokens para ver qué pasó.
    print_r($tokens);
} catch (Exception $e) {
    echo "Error capturado correctamente: " . $e->getMessage() . "\n";
}
