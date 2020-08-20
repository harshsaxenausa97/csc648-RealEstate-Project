<?php

function getDistance($addressFrom, $addressTo){
    // Google API key
    $apiKey = 'AIzaSyAspxm79KDz8BOV8Dtyy5qOi_uRVjgE0qw';

    // Change address format
    $formattedAddrFrom    = str_replace(' ', '+', $addressFrom);
    $formattedAddrTo     = str_replace(' ', '+', $addressTo);

    // Geocoding API request with start address
    $geocodeFrom = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddrFrom.'&sensor=false&key='.$apiKey);
    $outputFrom = json_decode($geocodeFrom);
    if(!empty($outputFrom->error_message)){
        return $outputFrom->error_message;
    }

    // Geocoding API request with end address
    $geocodeTo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddrTo.'&sensor=false&key='.$apiKey);
    $outputTo = json_decode($geocodeTo);
    if(!empty($outputTo->error_message)){
        return $outputTo->error_message;
    }

    // Get latitude and longitude from the geodata
    $latitudeFrom    = $outputFrom->results[0]->geometry->location->lat;
    $longitudeFrom   = $outputFrom->results[0]->geometry->location->lng;
    $latitudeTo      = $outputTo->results[0]->geometry->location->lat;
    $longitudeTo     = $outputTo->results[0]->geometry->location->lng;

    // Calculate distance between latitude and longitude
    $theta    = $longitudeFrom - $longitudeTo;
    $dist    = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
    $dist    = acos($dist);
    $dist    = rad2deg($dist);
    $miles    = $dist * 60 * 1.1515;

    //Return value in miles
    return round($miles, 2).' miles';
}

//Start-Exaple of use: Two address
//$addressFrom = '1600 Holloway Ave, San Francisco, CA 94132';
//$addressTo = '100 Chevron Way, Richmond, CA 94801';

//Get distance in miles between the two
//$distance = getDistance($addressFrom, $addressTo);

//echo $distance;
//End-Example

?>