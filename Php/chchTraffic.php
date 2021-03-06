<?php

include "XMLStructReader.php";
include "helperFunctions.php";

echo "######## START ######## START ######## START ######## START ########\n";

####### XML LOAD ####### XML LOAD ####### XML LOAD ####### XML LOAD #####
//$curl_chch = curl_init('https://infoconnect1.highwayinfo.govt.nz/ic/jbi/TMP/REST/FeedService/');
//$startCH_Time = microtime(true);
//curl_setopt($curl_chch, CURLOPT_HTTPHEADER, array(
//    'username: mjwynyard', 'password: Copper2004'
//        )
//);
//curl_setopt($curl_chch, CURLOPT_RETURNTRANSFER, true);
//$responseCH = curl_exec($curl_chch);
//file_put_contents("../XML/chchTraffic.xml", $responseCH);
//
//$endCH_Time = microtime(true);
//
//echo "CHCH xml downloaded: " .($endCH_Time - $startCH_Time) ." seconds\n\n";

####### XPATH ID TAG COUNT ####### XPATH ID TAG COUNT ####### 
#
$xmlTest = simplexml_load_file("../XML/chchTraffic.xml");
$resultTest = $xmlTest->xpath("//tns:locations");
$countTest = sizeof($resultTest);

$xPathIds = $xmlTest->xpath("//tns:id");
$xPathIdsCount = sizeof($xPathIds);


####### REFACTOR ORIGINAL XML ####### REFACTOR ORIGINAL XML ####### 

$xml = new XMLReader();
$xml->open('../XML/chchTraffic.xml');

$xmlRoadClosureString = "<roadClose>";

while ($xml->read()) {

    if ($xml->localName === 'roadClosures' && $xml->nodeType == XMLREADER::ELEMENT) {

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


while ($x->read()) {

    if ($x->localName === 'id' && $x->nodeType == XMLREADER::ELEMENT) {

        $event = array();

        $event[id] = $x->readString();
    }

    if ($x->localName === 'address' && $x->nodeType == XMLREADER::ELEMENT) {

        $event[description] = $x->readString();
    }

    if ($x->localName === 'startDate' && $x->nodeType == XMLREADER::ELEMENT) {

        $event[startDate] = $x->readString();
    }

    if ($x->localName === 'endDate' && $x->nodeType == XMLREADER::ELEMENT) {

        $event[endDate] = $x->readString();
    }

    if ($x->localName === 'publicDescription' && $x->nodeType == XMLREADER::ELEMENT) {

        $event[description] .= ". " . $x->readString();
    }

    if ($x->localName === 'lastUpdated' && $x->nodeType == XMLREADER::ELEMENT) {

        $event[eventModified] = $x->readString();
    }

    if ($x->localName === 'jobType' && $x->nodeType == XMLREADER::ELEMENT) {

        $event[eventType] = $x->readString();
    }

    if ($x->localName === 'significance' && $x->nodeType == XMLREADER::ELEMENT) {

        $event[description] .= ". " . $x->readString();
    }

    if ($x->localName === 'timeOfDay' && $x->nodeType == XMLREADER::ELEMENT) {

        $event[description] .= ". Time(s) of day affected: " . $x->readString();
    }

    if ($x->localName === 'trafficImpacts' && $x->nodeType == XMLREADER::ELEMENT) {

        $event[trafficImpactsXML] = "<root>";
        $event[trafficImpactsXML] .= $x->readInnerXml();
        $event[trafficImpactsXML] .= "</root>";
    }

    if ($x->localName === 'locations' && $x->nodeType == XMLREADER::ELEMENT) {

        $event[geoXML] = "<root>";
        $event[geoXML] .= $x->readInnerXML();
        $event[geoXML] .= "</root>";

        $events[] = $event;
    }
}

$x->close();

######## CREATE GEOJSON ######## CREATE GEOJSON ######## CREATE GEOJSON ######

$eventsCount = sizeof($events);

$x2GeoStruct = array();
$x2ImpactStruct = array();

$toBeEncoded1 = array("type" => "FeatureCollection",
    "features" => []);


for ($i = 0; $i < $eventsCount; $i++) {

    $toBeEncoded1[features][$i] = array("type" => "Feature",
        "geometry" => [
            "type" => "null",
            "coordinates" => []
        ],
        "properties" => array(
            "id" => $events[$i][id],
            "location" => "null",
            "description" => $events[$i][description],
            "planned" => "null",
            "alternativeRoute" => "N/A",
            "eventType" => $events[$i][eventType],
            "expectedResolution" => "null",
            "impact" => "null",
            "locationAreas" => "null",
            "status" => "null",
            "eventCreated" => $events[$i][startDate],
            "eventModified" => $events[$i][lastModified]
        )
    );

    //function call to XMLStructReader.php script for conversion to struct
    $x2ImpactStruct[] = xml_to_object($events[$i][trafficImpactsXML]);
    $impactCount = sizeof($x2ImpactStruct[$i]->children);
    $tmpImpactString = "";

    for ($p = 0; $p < $impactCount; $p++) {
        $tmpImpactString .= $x2ImpactStruct[$i]->children[$p]->content . ". ";
    }


    $x2GeoStruct[] = xml_to_object($events[$i][geoXML]);
    $geoCount = sizeof($x2GeoStruct[$i]->children);
    
    
    
    for ($j = 0; $j < $geoCount; $j++) {

        $tmpGeoType = $x2GeoStruct[$i]->children[0]->children[1]->content;
        $tmpCoords = $x2GeoStruct[$i]->children[0]->children[2]->content;

        //function call to helperFunctions.php
        $coordsFinal = convertCoords($tmpCoords);
        
        $closePolygon = array((double)$coordsFinal[0][0], (double)$coordsFinal[0][1]);
        $tmpFinalCoordLen = sizeof($coordsFinal);
        $coordsFinal[$tmpFinalCoordLen] = $closePolygon;
        
    }
    
    

    $toBeEncoded1[features][$i][properties][impact] = $tmpImpactString;
    $toBeEncoded1[features][$i][geometry][type] = "Polygon";
    $toBeEncoded1[features][$i][geometry][coordinates] = [$coordsFinal];
}


//
//
$json = json_encode($toBeEncoded1);

file_put_contents("../json/test.json", $json);


######## PRINT BLOCK ######## PRINT BLOCK ######## PRINT BLOCK ######## 

echo "\n";
//print_r($tmpCoords);
//echo "$final \n";
//echo $str . "\n";
//print_r($events);
//print_r($toBeEncoded1);
//print_r($x2GeoStruct);
//print_r($newphrase);
//print_r($ns);
echo "\n";
echo "\n";
echo "######## END ######## END ######## END ######## END ########\n";

