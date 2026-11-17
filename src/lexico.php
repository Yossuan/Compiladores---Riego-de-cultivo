<?php
// lexico.php - Analizador Léxico para ChocóCodo
// Desarrollado por: Yossuan

function analizarLexico($codigo)
{
    // Un solo patrón global que busca TODOS los tokens válidos en orden de prioridad
    $patronGlobal = '/
        (?<COMENTARIO>\#[^\n]*)               |  # Comentarios
        (?<PALABRA_RESERVADA>\b(regar|medir|fertilizar|cosechar|si|entonces|sino|fin|repetir|veces|verdadero|falso)\b) |
        (?<NUMERO>\b\d+(\.\d+)?\b)            |  # Números (enteros o decimales)
        (?<ID>\b[a-zA-Z_][a-zA-Z0-9_]*\b)     |  # Variables
        (?<ASIGNACION>=)                      |  # =
        (?<COMPARACION>==|>|<)                |  # Comparaciones
        (?<OPERADOR>\+|-|\*)                  |  # Aritméticos
        (?<ESPACIO>\s+)                       |  # Espacios y saltos de línea
        (?<ERROR>.)                              # Cualquier otro carácter (Error)
    /x';

    // Ejecutamos la búsqueda global
    preg_match_all($patronGlobal, $codigo, $matches, PREG_SET_ORDER);

    $tokens = [];
    $linea = 1;
    $columna = 1;

    foreach ($matches as $match) {
        // Obtenemos el tipo de token (la primera clave que no esté vacía)
        $tipo = null;
        foreach ($match as $key => $value) {
            if (!is_int($key) && $value !== '' && $value !== null) {
                $tipo = $key;
                break;
            }
        }

        $valor = $match[0];
        $longitudToken = strlen($valor);

        // Actualizamos línea y columna ANTES de procesar el token
        $lineaActual = $linea;
        $columnaActual = $columna;

        // Recorremos el valor para actualizar línea y columna
        for ($i = 0; $i < $longitudToken; $i++) {
            if ($valor[$i] === "\n") {
                $linea++;
                $columna = 1;
            } else {
                $columna++;
            }
        }

        // Si es un error, lanzamos la excepción con la posición exacta
        if ($tipo === 'ERROR') {
            throw new Exception("Línea $lineaActual, columna $columnaActual: carácter no reconocido '$valor'");
        }

        // Guardamos los tokens válidos (ignoramos espacios y comentarios)
        if ($tipo !== 'ESPACIO' && $tipo !== 'COMENTARIO') {
            $tokens[] = [
                'tipo' => $tipo,
                'valor' => $valor,
                'linea' => $lineaActual,
                'columna' => $columnaActual
            ];
        }
    }

    return $tokens;
}
