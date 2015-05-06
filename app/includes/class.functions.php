<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */

function remove_space($string){

    $space = str_replace('_', ' ', $string);

    return $space;

}
function add_space($string){

    $space = str_replace(' ', '_', $string);

    return $space;

}
function time_normal_full($string){

    $space = date('d/m/Y H.i.s', $string);

    return $space;

}
function time_normal_his($string){

    $space = date('H.i.s', $string);

    return $space;

}
function time_normal($string){

    $space = date('d/m/Y', $string);

    return $space;

}
function fix_text($string){

    $text = str_replace('_', ' ', $string);
    $text = str_replace('è', '&egrave;', $string);
    $text = str_replace('é', '&eacute;', $string);
    $text = str_replace('à', '&agrave;', $string);
    $text = str_replace('ù', '&ugrave;', $string);

    return $text;

}

?>