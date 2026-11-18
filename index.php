<?php
// index.php - Compilador ChocóCode
require_once 'src/lexico.php';
require_once 'src/sintactico.php';
require_once 'src/semantico.php';
require_once 'src/generador.php';

// Detectar si se ejecuta desde la terminal o desde el navegador
if (php_sapi_name() === 'cli') {
    // Modo Terminal
    if ($argc < 2) {
        die("Uso: php index.php <archivo.chc>\n");
    }
    $archivo = $argv[1];
} else {
    // Modo Navegador (XAMPP)
    // Si no se pasa parámetro por la URL (?archivo=...), usamos uno por defecto
    $archivo = $_GET['archivo'] ?? 'pruebas/correcto1.chc';
    echo "<pre>"; // Para que el texto se vea bonito en el navegador
}

if (!file_exists($archivo)) {
    die("Error: El archivo '$archivo' no existe.\n");
}

$codigoFuente = file_get_contents($archivo);

echo "=== COMPILANDO: $archivo ===\n";

try {
    $tokens = analizarLexico($codigoFuente);
    echo "[✓] Fase Léxica completada.\n";

    $parser = new Parser($tokens);
    $parser->parsear();
    echo "[✓] Fase Sintáctica completada.\n";

    $semantico = new Semantico();
    $semantico->analizar($tokens);
    echo "[✓] Fase Semántica completada.\n";

    $generador = new GeneradorCodigo();
    $comandos = $generador->generar($tokens);

    echo "\n=== COMPILACIÓN EXITOSA ===\n";
    echo "Código generado:\n";
    foreach ($comandos as $comando) {
        echo "- $comando\n";
    }
} catch (Exception $e) {
    echo "\n[✗] ERROR DE COMPILACIÓN:\n";
    echo $e->getMessage() . "\n";
}

if (php_sapi_name() !== 'cli') {
    echo "</pre>";
}
