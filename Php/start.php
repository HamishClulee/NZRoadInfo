<?php

echo "START.php fired! \n";

$startCH_Time = microtime(true);

$curl_h = curl_init('https://infoconnect1.highwayinfo.govt.nz/ic/jbi/TREIS/REST/FeedService/');
curl_setopt($curl_h, CURLOPT_HTTPHEADER, array(
    'username: xxxx', 'password: xxxx'
        )
);

curl_setopt($curl_h, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($curl_h);

file_put_contents("../XML/SHClosures.xml", $response);


include "SHXMLToJSON.php";

echo "start.php end \n";



