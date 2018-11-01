<?php
/* categorization is based on the 2 personality traits with the most points, referenced by their index at 0,1,2,3 */
/* initialize array key-value */
$pokemon = array ();
$pokemon['01'] = 'Charmander';
$pokemon['02'] = 'Squirtle';
$pokemon['03'] = 'Bulbasaur';
$pokemon['10'] = 'Torchic';
$pokemon['12'] = 'Mudkip';
$pokemon['13'] = 'Treecko';
$pokemon['20'] = 'Chimchar';
$pokemon['21'] = 'Piplup';
$pokemon['23'] = 'Turtwig';
$pokemon['30'] = 'Cyndaquil';
$pokemon['31'] = 'Tododile';
$pokemon['32'] = 'Chikorita';

/* function to output categorization*/
function categorizeUser($traits){
    /* not sure if initializing variables is needed in php */
    $firstMax = 0;
    $secondMax = 0;
    for($x = 0; $x < 4; $x++){
         /*check for index of 2 largest numbers*/
        if ($traits[$x] >= $traits[$firstMax]){
            $secondMax = $firstMax;
            $firstMax = $x;
        }
        else if($traits[$x] >= $traits[$secondMax] ){
            $secondMax = $x;
        }
    }
    /* concatenate the two maxes */
    $keyString = $firstMax . $secondMax;
    
    /* output the key lookup value */
    echo $pokemon[$keyString];

}

?>
