<?php
/* associative array(acts like a hashtable) with each index being an array for a 
given category, all initialized as empty */
$categories = array (
    'Charmander' => array(),
    'Squirtle' => array(),
    'Bulbasaur' => array(),
    'Torchic' => array(),
    'Mudkip' => array(),
    'Treecko' => array(),
    'Chimchar' => array(),
    'Piplup' => array(),
    'Turtwig' => array(),
    'Cyndaquil' => array(),
    'Tododile' => array(),
    'Chikorita' => array()
);

/*function to add someone to a certain category, everyone who is in the same subarray
will count as matches for each other */
/* parameters for the function will be taken from string fields stored in SQL database */
function matchMake($userName, $category){
    /*push newest user into category subarray, assumes $category is string */
    array_push($categories[$category], $userName);
}

?>
