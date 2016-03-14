<?php

echo "SHXMLToJSON start - encodes and builds SHClosures json \n";

######## PROPS ######## PROPS ######## PROPS ######## PROPS ########

ini_set('allow_url_fopen ','ON');


$xml = simplexml_load_file("../XML/SHClosures.xml");
$result = $xml->xpath("//tns:roadEvent");
$count = sizeof($result);

$ids = $xml->xpath("//tns:eventId");
$locations = $xml->xpath("//tns:locationArea");
$descriptions = $xml->xpath("//tns:eventComments");
$planned = $xml->xpath("//tns:planned");
$alternativeRoute = $xml->xpath("//tns:alternativeRoute");
$eventType = $xml->xpath("//tns:eventType");
$expectedResoultion = $xml->xpath("//tns:expectedResolution");
$impact = $xml->xpath("//tns:impact");
$locationAreas = $xml->xpath("//tns:locationArea");
$restrictions = $xml->xpath("//tns:restrictions");
$startDates = $xml->xpath("//tns:startDate");
$status = $xml->xpath("//tns:status");
$eventCreated = $xml->xpath("//tns:eventCreated");
$eventModified = $xml->xpath("//tns:eventModified");
//$endDates = $xml->xpath("//tns:endDate");

###### C++ BLOCK ###### C++ BLOCK ###### C++ BLOCK ###### C++ BLOCK ###### 


$testPoints = $xml->xpath("//tns:wktGeometry");
$testPointsSize = sizeof($testPoints);

$exploded = array();
$finalPoints = array();

for ($i = 0; $i < $testPointsSize; $i++) {
    $exploded[$i] = explode(";", $testPoints[$i]);
    $points[$i] = $exploded[$i][1];

    if (substr($points[$i], 0, 1) === "P") {
        $tempP = explode("(", $points[$i]);
        $tempP2 = substr($tempP[1], 0, -1);
        $finalPoints[$i] = $tempP2;
    } else {
        $tempM = explode("((", $points[$i]);
        $tempM2 = substr($tempM[1], 0, -2);
        $tempM3 = str_replace(",", "", $tempM2);
        $finalPoints[$i] = $tempM3;
    }
}

$sizeT = sizeof($finalPoints);

$des = array(
    0 => array("pipe", "r"),
    1 => array("pipe", "w"),
    2 => array("file", "error-output.txt", "a")
);

echo file_exists('../C++/NZMGtransform');

for ($i = 0; $i < $sizeT; $i++) {

    $process = proc_open('/var/www/nzroadinfo.xyz/public_html/C++/NZMGtransform', $des, $pipes);

    if (is_resource($process)) {

        fwrite($pipes[0], $finalPoints[$i]);
        fclose($pipes[0]);

        $coords[$i] = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        $return_value = proc_close($process);
    }
}

$c = sizeof($coords);
for ($i = 0; $i < $c; $i++) {
    $splitCoOrds[$i] = explode(" ", $coords[$i]);
}

####### JSON ENCODE ####### JSON ENCODE ####### JSON ENCODE #######

$toBeEncoded1 = array("type" => "FeatureCollection",
    "features" => []);

for ($i = 0; $i < $count; $i++) {

    $toBeEncoded1['features'][$i] = array("type" => "Feature",
        "geometry" => [],
        "properties" => array(
            "id" => "$ids[$i]",
            "location" => "$locations[$i]",
            "description" => "$descriptions[$i]",
            "planned" => "$planned[$i]",
            "alternativeRoute" => "$alternativeRoute[$i]",
            "eventType" => "$eventType[$i]",
            "expectedResolution" => "$expectedResoultion[$i]",
            "impact" => "$impact[$i]",
            "locationAreas" => "$locationAreas[$i]",
            "status" => "$status[$i]",
            "eventCreated" => "$eventCreated[$i]",
            "eventModified" => "$eventModified[$i]"
        )
    );

    $tempCoordLen = sizeof($splitCoOrds[$i]);

    if ($tempCoordLen === 2) {
        $toBeEncoded1['features'][$i]['geometry'] = array(
            "type" => "Point",
            "coordinates" => [(double)$splitCoOrds[$i][0], (double)$splitCoOrds[$i][1]]
        );
 
    } else {
        $toBeEncoded1['features'][$i]['geometry'] = array(
            "type" => "LineString",
            "coordinates" => []
        );
        
        for($j = 0; $j < $tempCoordLen - 1; $j+=2){
        
            array_push($toBeEncoded1['features'][$i]['geometry']['coordinates'], [(double)$splitCoOrds[$i][$j], (double)$splitCoOrds[$i][$j+1]]);
        
        }
        unset($j);
        unset($tempCoordLen);
    }
}

$json = json_encode($toBeEncoded1);

file_put_contents("../json/SHClosures.json", $json);

echo "SHXMLToJSON end \n";