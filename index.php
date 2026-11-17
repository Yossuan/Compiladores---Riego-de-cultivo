<?php
// index.php - Archivo principal del compilador ChocóCode
require_once 'src/lexico.php';
require_once 'src/sintactico.php';
require_once 'src/semantico.php';
require_once 'src/generador.php';

// 1. Verificar que se pasó un archivo por parámetro
if ($argc < 2) {
    die("Uso: php index.php <archivo.chc>\n");
}

$archivo = $argv[1];

if (!file_exists($archivo)) {
    die("Error: El archivo '$archivo' no existe.\n");
}

$codigoFuente = file_get_contents($archivo);

echo "=== COMPILANDO: $archivo ===\n";

try {
    // FASE 1: LÉXICO (Yossuan)
    $tokens = analizarLexico($codigoFuente);
    echo "[✓] Fase Léxica completada.\n";

    /*

    // FASE 2: SINTÁCTICO (Winner)
    $parser = new Parser($tokens);
    $parser->parsear(); // Si falla, lanza Exception
    echo "[✓] Fase Sintáctica completada.\n";

    // FASE 3: SEMÁNTICO (Carlos)
    // (Se integrará cuando Carlos suba su código)
    $semantico = new Semantico();
    // $semantico->analizar($tokens); 
    echo "[✓] Fase Semántica completada.\n";

    // FASE 4: GENERACIÓN DE CÓDIGO (Carlos)
    // (Se integrará cuando Carlos suba su código)
    $generador = new GeneradorCodigo();
    // $comandos = $generador->generar($tokens);

    echo "\n=== COMPILACIÓN EXITOSA ===\n";
    echo "Código generado:\n";
    // print_r($comandos);
*/
} catch (Exception $e) {
    // Si cualquier fase falla, se captura el error aquí
    echo "\n[✗] ERROR DE COMPILACIÓN:\n";
    echo $e->getMessage() . "\n";
    
}
