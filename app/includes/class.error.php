<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */

/**
 * Class My_Error
 * @package Index/Loader/App/Includes/
 */
class My_Error {

    function __construct() {

    }

    /**
     * @param $code
     * @param $message
     */
    public static function error_die($code, $message){
        if(empty($code))
            return;

        $variable = <<<END
                        <html>
    <head>
        <title>MyCMS Error</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <style type="text/css">
            body {
            background-color: #fff;
                color: #000;
                font-size: 0.9em;
                font-family: sans-serif,helvetica;
                margin: 0;
                padding: 0;
            }
            h1 {
            text-align: center;
                margin: 0;
                padding: 0.6em 2em 0.4em;
                background-color: #294172;
                color: #fff;
                font-weight: normal;
                font-size: 1.75em;
                border-bottom: 2px solid #000;
            }
            h1 strong {
            font-weight: bold;
                font-size: 1.5em;
            }
            h3 {
            text-align: center;
                background-color: #ff0000;
                padding: 0.5em;
                color: #fff;
            }
            .content {
            padding: 1em 5em;
            }
        </style>
    </head>

    <body>
        <h1><strong>MyCMS error!</strong></h1>

        <div class="content">
            <h3>Code $code - $message</h3>
        </div>
    </body>
</html>
END;

        die($variable);

    }

    public static function error($code, $message){
        if(empty($code))
            return;

        echo '<div style="background-color: #FF7400;border-radius: 3px;color: white;font-size: 18px;"><b>Error MYCMS - CODE '. $code .': </b><br /> <center>'. $message .'</center></div><br>';

    }

}