<?php

echo "CHXMLToJSON2 start - builds and encodes ChChEvents json \n";

include "XMLStructReader.php";
include "helperFunctions.php";


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

$json = json_encode($toBeEncoded1);

file_put_contents("../json/ChChEvents.json", $json);

echo "CHXMLToJSON2 end \n";