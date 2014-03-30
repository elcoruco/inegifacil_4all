<?php

if($_GET['index'] && $_GET['location']){
  // get the INEGI library
  include_once('INEGI_library.php');
  $inegi = new INEGI_library();
  $response = $inegi->index($_GET['index'], $_GET['location']);
  
  // return json
  header('Content-Type: application/json');
  echo json_encode($response);
  die();
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
</head>
<body>
  <form id="query" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <!-- SELECT THE LOCATION -->
    <p>
      <label for="location">Selecciona una ubicación:</label>
      <select name="location" id="location">
        <option value="00000">Todo México</option>
        <option value="09000">Distrito federal</option>
        <option value="15000">Estado de México</option>
        <option value="21000">Puebla</option>
        <option value="21132">San Martín Texmelucan</option>
      </select>
    </p>

    <!-- SELECT THE INEGI'S INDEX  -->
    <p>
      <label for="index">Selecciona un índice</label>
      <select name="index" id="index">
        <option value="1002000001">población total</option>
        <option value="1002000003">Población total mujeres</option>
        <option value="6300000269">Población en situación de pobreza</option>
      </select>
    </p>

    <p><input type="submit" value="obtén la información"></p>

  </form>

  <!-- RESPONSE -->
  <pre id="response">
  </pre>


  <!-- THE REQUEST -->
  <script>
    var form = document.getElementById("query"),
        response = document.getElementById("response");
    
    function getData(e){
      var loc   = document.getElementById('location').value,
          index = document.getElementById('index').value,
          url   = 'index.php?location=' + loc + '&index=' + index,
          r     = new XMLHttpRequest();

      r.open('GET', url, true);

      r.onload = function(){
        response.innerHTML = '';
        response.innerHTML = r.responseText;
      }

      r.send();
      e.preventDefault();
    }

    form.addEventListener("submit", getData, false);
  </script>
</body>
</html>

