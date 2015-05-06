<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */

//LANGUAGE FILE
if(!defined(MY_LANGUAGE)){
    $site_language = get_settings_value('site_language');
    if(!empty($site_language)){
        define('MY_LANGUAGE', $site_language);
    }else{
        define('MY_LANGUAGE', 'it_IT');
    }
}


function get_language(){

    return MY_LANGUAGE;

}
//Translate use e('text'); use for template page NO ADMIN
function e($string, $display = '0'){

    $theme_path = get_theme_path();
    if(!file_exists ($theme_path)) {
        MY_Error::error_die("00STYLE", "Template not found!");
    }

    $file_language_name = ''.MY_LANGUAGE;
    $path = $theme_path . '/languages/';

    @include($path.$file_language_name.'.php');

    if(!empty($language[$string])){

        if($display == '1'){

            return $language[$string]; //Ritorno come dato

        } else {

            echo $language[$string]; //Ritorno per testo

        }

    } else {

        if($display == '1'){

            return $string;

        } else {

            echo $string;

        }
    }

}

function ea($string, $display = '0'){

    $file_language_name = 'admin_'.MY_LANGUAGE;
    $path = MY_ADMIN_PATH.'/languages/';

    @include($path.$file_language_name.'.php');

    if(!empty($language[$string])){

        if($display == '1'){

            return $language[$string]; //Ritorno come dato

        } else {

            echo $language[$string]; //Ritorno per testo

        }

    } else {

        if($display == '1'){

            return $string;

        } else {

            echo $string;

        }
    }

}