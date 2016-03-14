<?php

echo "CHXMLToJSON1 start - removes the roadClosures tags from chch xml \n";

$dom = new DOMDocument();
$dom->load("../XML/chchTraffic.xml");
$deleteList = $dom->getElementsByTagName('roadClosures');

$remove = array();


foreach ($deleteList as $domElement) { 
  $remove[] = $domElement; 
} 


foreach( $remove as $domElement ){ 
  $domElement->parentNode->removeChild($domElement); 
} 



$dom->save("../XML/chchTraffic.xml");


echo "CHXMLToJSON1 end \n";