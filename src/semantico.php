<?php
// generador.php - Generación de Código para ChocóCode
// Desarrollado por: Carlos

class GeneradorCodigo
{
    private $comandos = [];
    private $variables = []; // Para guardar el valor de las variables (ej. dosis = 2)

    public function generar($tokens)
    {
        $this->comandos = [];
        $this->variables = []; // Limpiar variables

        for ($i = 0; $i < count($tokens); $i++) {
            $token = $tokens[$i];

            // Si encontramos una asignación (ej. dosis = 2), guardamos su valor
            if ($token['tipo'] === 'ID' && isset($tokens[$i + 1]) && $tokens[$i + 1]['valor'] === '=') {
                $nombreVar = $token['valor'];
                $valorVar = $tokens[$i + 2]['valor'] ?? '0';
                $this->variables[$nombreVar] = $valorVar;
            }

            // Traducir instrucciones propias
            if ($token['tipo'] === 'PALABRA_RESERVADA') {
                $valor = strtoupper($token['valor']);

                if ($valor === 'REGAR' || $valor === 'FERTILIZAR') {
                    $siguiente = $tokens[$i + 1]['valor'] ?? '0';
                    // Si el siguiente es una variable, buscamos su valor real
                    if (isset($this->variables[$siguiente])) {
                        $siguiente = $this->variables[$siguiente];
                    }
                    $this->comandos[] = "$valor $siguiente";
                } elseif ($valor === 'MEDIR') {
                    $siguiente = $tokens[$i + 1]['valor'] ?? '';
                    $this->comandos[] = "ABRIR_SENSOR " . strtoupper($siguiente);
                } elseif ($valor === 'COSECHAR') {
                    $this->comandos[] = "ACTIVAR_COSECHA";
                } elseif ($valor === 'REPETIR') {
                    $this->comandos[] = "// BUCLE INICIADO";
                } elseif ($valor === 'SI') {
                    $this->comandos[] = "// CONDICIONAL INICIADO";
                } elseif ($valor === 'FIN') {
                    // Solo imprimimos "FIN BLOQUE" si es el fin de un repetir o si
                    // (Para simplificar, solo lo mostramos si no es el fin de un si)
                    $this->comandos[] = "// FIN DE BLOQUE";
                }
            }
        }

        return $this->comandos;
    }
}
