<?php
/**
* INEGI_library
*
* @author elcoruco <el.coruco@gmail.com>
*/

class INEGI_library{
  // define the time periods
  const FROM       = 1800;
  const TO         = 2050;
  // define the endpoints base
  const ENDPOINT   = 'http://www2.inegi.org.mx/servicioindicadores/Indicadores.asmx/';
  const GET_LATEST = 'obtieneValoresOportunos';
  const GET_ALL    = 'obtieneValoresHistoricos';
  const GET_DEF    = 'obtieneDSD';
  const GET_DESC   = 'obtieneDescripcionCodeList';
  // define the max wait time for the INEGI webservice
  const WAIT_TIME = 10;
  
  // prepare the distinct endpoints
  public $latest;
  public $collection;
  public $description;

  function __construct(){
    // define the endpoints
    $this->latest = self::ENDPOINT . self::GET_LATEST;
    $this->collection = self::ENDPOINT . self::GET_ALL;
    $this->description = self::ENDPOINT . self::GET_DESC;
  }

  /*
  * returns the header and the content as an array from an XML response
  * 
  * @access public
  * @param $key: the index to retrieve
  * @param $location: the state or city for the index
  * @param $single: define the endpoint option
  * @return array
  */
  public function index($key, $location, $single = FALSE){
    // the items array contains the elements for the query
    $items = [];
    $items['Indicador'] = $key;
    $items['ubicacionGeografica'] = $location;
    if(! $single){
      $items['fechaInicial'] = self::FROM;
      $items['fechaFinal']   = self::TO;
    }
    $query      = @http_build_query($items);
    $inegi_url  = ($single ? $this->latest : $this->collection) . '?' . $query;
    
    $xml = $this->the_call($inegi_url);
    
    // pass the XML response to a DOMDocument
    if($xml){
      $dom        = new DOMDocument();
      $r          = @$dom->loadXML($xml); // check if the response is OK
      $header     = $this->_clean_header($dom);
      $collection = $this->_clean_body($dom);
      return ["header" => $header, "indices" => $collection];
    }
    else{
      return FALSE;
    }
  }
  
  /*
  * returns the name of the code requested
  * 
  * @access public
  * @param  $catalog: the name of the code
  * @param  $key: the value of the code
  * @param  $type: the type of the index (a/b)
  * @return string
  */
  public function code($catalog, $key, $type){
    // set the url data
    $items = [];
    $items["catalogo"] = $catalog;
    $items["clave"] = $key;
    $items["tipo"] = $type;
    $query      = @http_build_query($items);
    $inegi_url  = $this->description . '?' . $query;

    $response = $this->the_call($inegi_url);
    if($response){
      // decode the XML value
      $xml      = new SimpleXMLElement($response);
      $name     = (string)$xml->children()[1]->children()[0]->children()[1]->children()[0];
    }

    return $response ? $name : FALSE;
  }

 
   /*
   * retrieves the header from the XML and returns it as an array
   *
   * @access private
   * @param  DOMDocument
   * @return array
   */
   private function _clean_header($dom){
     $header = $dom->getElementsByTagNameNs('*', 'Series')->item(0);
     if(!empty($header)){
      $nodes = [];
      foreach($header->attributes as $attr){
        $nodes[$attr->nodeName] = $attr->nodeValue;
      }
      return $nodes;
    }
    else{ 
      return FALSE;
    }
   }
 
   /*
   * retrieves the content from  the XML and returns it as an array
   *
   * @access private
   * @param  DOMDocument
   * @return array
   */
   private function _clean_body($dom){
     $collection = [];
    foreach ($dom->getElementsByTagNameNS('*', 'Obs') as $element) {
      $item = [];
      foreach($element->attributes as $attr){
        $item[$attr->nodeName] = $attr->nodeValue;
      }
      $collection[] = $item;
    }
    return $collection;
   }
   
   /*
  * make the request to the web service and return the response
  * 
  * @access private
  * @param  string: the endpoint
  * @return xml/false
  */
   private function the_call($inegi_url){
     $curl = curl_init();
     curl_setopt($curl, CURLOPT_URL, $inegi_url);
     curl_setopt($curl, CURLOPT_TIMEOUT, self::WAIT_TIME);
     curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
     
     return curl_exec($curl);
   }


}
// END of class INEGI_library
