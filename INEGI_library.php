<?php
/**
* INEGI_library
*
* @author elcoruco <el.coruco@gmail.com>
*/

class INEGI_library{
  const FROM       = 1800;
  const TO         = 2050;
  const ENDPOINT   = 'http://www2.inegi.org.mx/servicioindicadores/Indicadores.asmx/';
  const GET_LATEST = 'obtieneValoresOportunos';
  const GET_ALL    = 'obtieneValoresHistoricos';
  const GET_DEF    = 'obtieneDSD';
  const GET_DESC   = 'obtieneDescripcionCodeList';

  public $latest;
  public $collection;

  function __construct(){
    $this->latest = self::ENDPOINT . self::GET_LATEST;
    $this->collection = self::ENDPOINT . self::GET_ALL;
  }

  /*
  * returns the header and the content as an array from an XML call
  * 
  * @access public
  * @params int, int, bool
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
    // make the call to the web service
    $xml        = file_get_contents($inegi_url);
    // pass the XML response to a DOMDocument
    $dom        = new DOMDocument();
    $r          = @$dom->loadXML($xml); // check if the response it's OK
    $header     = $this->_clean_header($dom);
    $collection = $this->_clean_body($dom, $header);
    return $collection;
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
     $nodes = [];
 
     if($header->hasAttributes()){
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
   private function _clean_body($dom, $header){
     $collection = [];
     foreach ($dom->getElementsByTagNameNS('*', 'Obs') as $element) {
       $item = [];
       foreach($element->attributes as $attr){
         $item[$attr->nodeName] = $attr->nodeValue;
       }
       // each item  contains the same header, but because of
       // the Backbone method to retrieve information (the "fetch" function),
       // its simplier to make it this way
       // by the way, http://inegifacil.com uses Backbone!
       $item['header'] = $header;
       $collection[] = $item;
     }
     return $collection;
   }


}
// END of class INEGI_library
