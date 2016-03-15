<?php

echo "START.php fired! \n";

$startCH_Time = microtime(true);

$curl_h = curl_init('https://infoconnect1.highwayinfo.govt.nz/ic/jbi/TREIS/REST/FeedService/');
curl_setopt($curl_h, CURLOPT_HTTPHEADER, array(
    'username: REPLACE', 'password: REPLACE'
        )
);
curl_setopt($curl_h, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl_h);

file_put_contents("../XML/SHClosures.xml", $response);


$curl_chch = curl_init('https://infoconnect1.highwayinfo.govt.nz/ic/jbi/TMP/REST/FeedService/');

curl_setopt($curl_chch, CURLOPT_HTTPHEADER, array(
    'username: REPLACE', 'password: REPLACE'
        )
);
curl_setopt($curl_chch, CURLOPT_RETURNTRANSFER, true);
$responseCH = curl_exec($curl_chch);
file_put_contents("../XML/chchTraffic.xml", $responseCH);

$endCH_Time = microtime(true);

echo "xml downloaded: " .($endCH_Time - $startCH_Time) ." seconds\n\n";


include "SHXMLToJSON.php";


include "CHXMLToJSON.php";


include "CHXMLToJSON1.php";


include "CHXMLToJSON2.php";


include "CHXMLToJSON3.php";


echo "start.php end \n";



