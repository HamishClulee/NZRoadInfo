<?php

echo "CHXMLToJSON start - places roadClosure tags into CHRdClosures xml \n";

$xmlTest = simplexml_load_file("../XML/chchTraffic.xml");
$resultTest = $xmlTest->xpath("//tns:locations");
$countTest = sizeof($resultTest);

$xPathIds = $xmlTest->xpath("//tns:id");
$xPathIdsCount = sizeof($xPathIds);

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

echo "CHXMLToJSON end\n";