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
$endpoint = 0; // 0 para todos los valores, 1 para el último valor solamente
$response = $inegi->index($indice, $lugar, $endpoint);
</pre>
<p> Las claves de de los estados y municipios las puedes obtener de aquí: http://inegifacil.com/dropdowns</p>
<p> Las claves para los indicadores, los puedes obtener dentro de inegifacil.com o en el  sitio oficial del INEGI: 
http://www.inegi.org.mx/inegi/contenidos/serviciosweb/infoestadistica.aspx</p>

<p>4. regresa la info en json para tu app</p>
<pre>
header('Content-Type: application/json');
echo json_encode($response);
</pre>

<p>En el archivo index.php se incluye un ejemplo más completo que sirve para implementar la librería.</p>

<h6>Caveat emptor</h6>
<p>La librería no incluye ningún tipo de limpieza (¿sanitización?) para las variables que recibe. Aguas. Y mucho ojo.</p>

