<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */


class MY_Plugins {

    public function __construct ()
    {
    }

    function include_plugins($file = null){

        if(empty($file))
            return;

        switch ($file){
            case "header":



                break;

            case "footer";


                break;
            default:
                return;
        }

    }

    function control_plugin(){

    }

    function language_plugin(){

    }

}