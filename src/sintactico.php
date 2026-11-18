<?php
// sintactico.php - Analizador Sintáctico para ChocóCode
// Desarrollado por: Winner

class Parser
{
    private $tokens;
    private $pos = 0;
    private $tokenActual;

    public function __construct($tokens)
    {
        $this->tokens = $tokens;
        $this->tokenActual = $this->tokens[0] ?? null;
    }

    private function avanzar()
    {
        $this->pos++;
        $this->tokenActual = $this->tokens[$this->pos] ?? null;
    }

    private function error($mensaje)
    {
        if ($this->tokenActual) {
            $linea = $this->tokenActual['linea'];
            $columna = $this->tokenActual['columna'];
        } else {
            // Si llegó al final del archivo, usamos la posición del último token
            $ultimoToken = end($this->tokens);
            $linea = $ultimoToken ? $ultimoToken['linea'] : 1;
            $columna = $ultimoToken ? $ultimoToken['columna'] + strlen($ultimoToken['valor']) : 1;
        }
        throw new Exception("Línea $linea, columna $columna: $mensaje");
    }

    private function match($tipo)
    {
        if ($this->tokenActual && $this->tokenActual['tipo'] === $tipo) {
            $this->avanzar();
            return true;
        }
        return false;
    }

    private function matchValor($valor)
    {
        if ($this->tokenActual && $this->tokenActual['valor'] === $valor) {
            $this->avanzar();
            return true;
        }
        return false;
    }

    public function parsear()
    {
        while ($this->tokenActual) {
            $this->instruccion();
        }
    }

    private function instruccion()
    {
        if (!$this->tokenActual) return;

        $val = $this->tokenActual['valor'];

        if ($val === 'regar') {
            $this->avanzar();
            $this->expresion();
        } elseif ($val === 'medir') {
            $this->avanzar();
            if (!$this->match('ID')) $this->error("Se esperaba un identificador después de 'medir'");
        } elseif ($val === 'fertilizar') {
            $this->avanzar();
            $this->expresion();
        } elseif ($val === 'cosechar') {
            $this->avanzar();
        } elseif ($val === 'si') {
            $this->condicional();
        } elseif ($val === 'repetir') {
            $this->repeticion();
        } elseif ($this->tokenActual['tipo'] === 'ID') {
            $this->avanzar();
            if (!$this->matchValor('=')) $this->error("Se esperaba '=' en la asignación");
            $this->expresion();
        } else {
            $this->error("Instrucción no reconocida: '{$val}'");
        }
    }

    private function condicional()
    {
        $this->avanzar(); // consume 'si'
        $this->expresion();
        if (!$this->matchValor('>') && !$this->matchValor('<') && !$this->matchValor('==')) {
            $this->error("Se esperaba un operador de comparación (>, < o ==)");
        }
        $this->expresion();
        if (!$this->matchValor('entonces')) $this->error("Se esperaba 'entonces' después de la condición");

        while ($this->tokenActual && $this->tokenActual['valor'] !== 'sino' && $this->tokenActual['valor'] !== 'fin') {
            $this->instruccion();
        }

        if ($this->matchValor('sino')) {
            while ($this->tokenActual && $this->tokenActual['valor'] !== 'fin') {
                $this->instruccion();
            }
        }

        if (!$this->matchValor('fin')) $this->error("Se esperaba 'fin' para cerrar el bloque 'si'");
    }

    private function repeticion()
    {
        $this->avanzar(); // consume 'repetir'
        $this->expresion();
        if (!$this->matchValor('veces')) $this->error("Se esperaba 'veces' después de la cantidad");

        while ($this->tokenActual && $this->tokenActual['valor'] !== 'fin') {
            $this->instruccion();
        }

        if (!$this->matchValor('fin')) $this->error("Se esperaba 'fin' para cerrar el bloque 'repetir'");
    }

    private function expresion()
    {
        $this->termino();
        while ($this->tokenActual && in_array($this->tokenActual['valor'], ['+', '-', '*'])) {
            $this->avanzar();
            $this->termino();
        }
    }

    private function termino()
    {
        if ($this->match('NUMERO') || $this->match('ID')) {
            return;
        }
        $this->error("Se esperaba un número o una variable");
    }
}
