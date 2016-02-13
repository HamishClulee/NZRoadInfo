<?php

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

####### XML LOAD ####### XML LOAD ####### XML LOAD ####### XML LOAD #####


$xml = simplexml_load_file("../XML/chchTrafficSolid.xml");

$result = $xml->xpath("//tns:id");
$count = sizeof($result);

$test = $xml->xpath("//tns:item[tns:id=26976]/tns:locations/tns:coordinates");


######## PROPS ######## PROPS ######## PROPS ######## PROPS ########

echo "\n";
echo "\n";
echo "\n";
echo "Size of (number of events) in chchch xml: " . $count . "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";