<?php

function convertCoords($x) {
    
    $e = explode(",", $x);

        for ($s = 0; $s < sizeof($e); $s+=2) {

            $temp = $e[$s];
            $e[$s] = $e[$s + 1];
            $e[$s + 1] = $temp;
        }

        $str = "";

        for ($s = 0; $s < sizeof($e); $s++) {
            $str .= $e[$s] . ",";
        }

        $search = array('"', "x", "y", "{", "}", ":", "[[", "]]", "[", "]");
        $replace = array("", "", "", "", "", "", "", "", "", "");

        $newphrase = str_replace($search, $replace, $str);

        $ns = explode(",", $newphrase);
        
        $CoordsFinal = array();
        
        for ($s = 0; $s < sizeof($ns) - 1; $s+=2) {
            
            $coordSet = array();
            $coordSet[] = (double)$ns[$s];           
            $coordSet[] = (double)$ns[$s+1];

            $CoordsFinal[] = $coordSet;
            
        }

    return $CoordsFinal;
}
