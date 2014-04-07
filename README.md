inegifacil_4all
===============

Desarrolla tu propia versión de inegi fácil, aquí lo básico que necesitas

<h3>EJEMPLO DE CÓMO USAR LA LIBRERÍA</h3>

<p>1. impórtala<p>
<pre>include_once('INEGI_library.php');</pre>

<p>2. crea una nueva instancia</p>
<pre>$inegi = new INEGI_library();</pre>

<p>3. pasa el índice que quieras obtener y la clave de la localidad</p>
<pre>
$indice = 1002000001; // población total
$lugar = 21132; // san martín texmelucan (21 (puebla) + 132(san martín)
$response = $inegi->index($indice, $lugar);
</pre>
<p> Las claves de de los estados y municipios las puedes obtener de aquí: http://inegifacil.com/dropdowns</p>
<p> Próximamente subiré aquí una pequeña db con todos los índices disponibles del INEGI (nomás es cosa de terminar de capturarlos).</p>

<p>4. regresa la info en json para tu app</p>
<pre>
header('Content-Type: application/json');
echo json_encode($response);
</pre>

