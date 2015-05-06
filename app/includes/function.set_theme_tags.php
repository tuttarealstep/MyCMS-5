<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */


function set_theme_tags(){
    global $my_theme;
    add_tag('getSTYLE=css', set_TAG(get_style_script("css", true)));
    add_tag('getSTYLE=script', set_TAG(get_style_script("script", true)));
}