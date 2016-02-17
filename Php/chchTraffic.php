<?php

echo "######## START ######## START ######## START ######## START ########\n";

####### XML LOAD ####### XML LOAD ####### XML LOAD ####### XML LOAD #####

$curl_chch = curl_init('https://infoconnect1.highwayinfo.govt.nz/ic/jbi/TMP/REST/FeedService/');
$startCH_Time = microtime(true);
curl_setopt($curl_chch, CURLOPT_HTTPHEADER, array(
    'username: mjwynyard', 'password: Copper2004'
        )
);
curl_setopt($curl_chch, CURLOPT_RETURNTRANSFER, true);
$responseCH = curl_exec($curl_chch);
file_put_contents("../XML/chchTraffic.xml", $responseCH);

$endCH_Time = microtime(true);

echo "CHCH xml downloaded: " .($endCH_Time - $startCH_Time) ." seconds\n\n";

####### XPATH ID TAG COUNT ####### XPATH ID TAG COUNT ####### 

$xmlTest = simplexml_load_file("../XML/chchTraffic.xml");
$resultTest = $xmlTest->xpath("//tns:id");
$countTest = sizeof($resultTest);

####### REFACTOR ORIGINAL XML ####### REFACTOR ORIGINAL XML ####### 

$xml = new XMLReader();
$xml->open('../XML/chchTraffic.xml');

$xmlRoadClosureString = "<roadClose>";

while($xml->read()){
    
        if($xml->localName === 'roadClosures' && $xml->nodeType == XMLREADER::ELEMENT){
            
            $xmlRoadClosureString .= "<roadClosure>";
            $xmlRoadClosureString .= $xml->readInnerXml();
            $xmlRoadClosureString .= "</roadClosure>";

        }

}

$xmlRoadClosureString .= "</roadClose>";

file_put_contents("../XML/chchRoadClosures.xml", $xmlRoadClosureString);

$xml->close();

############## REMOVE CLOSURE NODES FROM CHCHTRAFFIC XML ##################

$dom = new DOMDocument();
$dom->load("../XML/chchTraffic.xml");
$delete = $dom->getElementsByTagName('roadClosures');

foreach ($delete as $node) {
    $node->parentNode->removeChild($node);
}

$dom->save("../XML/chchTraffic.xml");


####### READ CHCHTRAFFIC XML ####### READ CHCHTRAFFIC XML ####### 

$x = new XMLReader();
$x->open('../XML/chchTraffic.xml');

$events = array();

while($x->read()){
    
        if($x->localName === 'id' && $x->nodeType == XMLREADER::ELEMENT){
            
            $event = array();
            
            $event['id'] = $x->readString();

        }
        
        if($x->localName === 'locations' && $x->nodeType == XMLREADER::ELEMENT){
            
            $event[geo] = $x->readInnerXml();
            
            $events[] = $event;
            
            unset($event);
        }
}

$x->close();

######## PRINT BLOCK ######## PRINT BLOCK ######## PRINT BLOCK ######## 

echo "\n";
echo "Number of id tags (from xpath): " . $countTest . "\n";
echo "\n";
echo "\n";
print_r($events);
echo "\n";
echo "\n";
echo "######## END ######## END ######## END ######## END ########\n";