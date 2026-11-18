# Bitácora del Proyecto ChocóCode

## Yossuan (Análisis Léxico y Pruebas)

**1. Decisiones de diseño (Análisis Léxico):**
*   Se implementó el analizador léxico en el archivo `src/lexico.php`.
*   **Decisión técnica:** Se optó por usar la función de PHP `preg_match` en un bucle para recorrer el texto con expresiones regulares. Esto es más eficiente y limpio que recorrer el string carácter por carácter con un `for` manual.
*   Se implementó un control estricto de las variables `$linea` y `$columna`. Cada vez que se consume un token que contiene un salto de línea (`\n`), el contador de línea aumenta y la columna se reinicia en 1. Esto es vital para que los errores se reporten con precisión.
*   Si el analizador se encuentra con un carácter que no está en ninguna expresión regular (como `@`, `$` o `&`), lanza una excepción con el mensaje exacto: *"Línea X, columna Y: carácter no reconocido"*.

**2. Programas de prueba:**
*   Se crearon los 6 archivos `.chc` iniciales en la carpeta `/pruebas` (3 correctos y 3 con errores intencionales: léxico, sintáctico y semántico) para ir probando el compilador a medida que avanzamos.

**3. Uso de Inteligencia Artificial:**
*   Se utilizó IA como apoyo para estructurar las expresiones regulares de los tokens y organizar la lógica del conteo de líneas y columnas. El código fue revisado y comprendido por el integrante.
