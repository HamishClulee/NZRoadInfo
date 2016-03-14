<?php

echo "CHXMLToJSON3 start - builds and encodes ChChRoadClosures json \n";

$x1 = file_get_contents("../XML/chchRoadClosures.xml");

$rdCloseStruct[] = xml_to_object($x1);

$rdCloseCount = sizeof($rdCloseStruct[0]->children);

$toBeEncoded2 = array("type" => "FeatureCollection",
    "features" => []);

for ($i = 0; $i < $rdCloseCount; $i++) {

    $toBeEncoded2[features][$i] = array("type" => "Feature",
        "geometry" => [],
        "properties" => array(
            "id" => "null",
            "location" => "null",
            "description" => $rdCloseStruct[0]->children[$i]->children[0]->children[0]->content,
            "planned" => "null",
            "alternativeRoute" => "N/A",
            "eventType" => "Road Closure",
            "expectedResolution" => $rdCloseStruct[0]->children[$i]->children[0]->children[2]->content,
            "impact" => "null",
            "locationAreas" => "null",
            "status" => "null",
            "eventCreated" => $rdCloseStruct[0]->children[$i]->children[0]->children[1]->content,
            "eventModified" => "null"
        )
    );

    $tmpType = $rdCloseStruct[0]->children[$i]->children[0]->children[3]->children[0]->children[1]->content;
    $typeCounter = sizeof($rdCloseStruct[0]->children[$i]->children[0]->children[3]->children);

    for ($j = 0; $j < $typeCounter; $j++) {
//        if($typeCounter > 2){
//            $toBeEncoded[features][$i][geometry][type] = "GeometryCollection";
//            $toBeEncoded[features][$i][geometry] = array(
//                "type" => "GeometryCollection",
//                "geometries" => []
//            );
//        }
        $tmpType = $rdCloseStruct[0]->children[$i]->children[0]->children[3]->children[$j]->children[1]->content;

        if ($tmpType === "polygon") {

            $tmpCoords = $rdCloseStruct[0]->children[$i]->children[0]->children[3]->children[$j]->children[2]->content;

            $CoordsFinal = convertCoords($tmpCoords);

            $closePolygon1 = array((double) $CoordsFinal[0][0], (double) $CoordsFinal[0][1]);
            $tmpFinalCoordLen1 = sizeof($CoordsFinal);
            $CoordsFinal[$tmpFinalCoordLen1] = $closePolygon1;

            $toBeEncoded2[features][$i][geometry][type] = "Polygon";
            $toBeEncoded2[features][$i][geometry][coordinates] = [$CoordsFinal];
           
        }


//TODO Rejig $tobeencoded to reflect geo collections
//        if ($tmpType === "polyline") {
//            $featureCollection = true;
//            $tmpCoords = $rdCloseStruct[0]->children[$i]->children[0]->children[3]->children[$j]->children[2]->content;
//            $toBeEncoded[features][$i][geometry][type] = "LineString";
//            
//        }
    }

}


$json1 = json_encode($toBeEncoded2);

file_put_contents("../json/chchRdClose.json", $json1);

echo "CHXMLToJSON3 end \n";