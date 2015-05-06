<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */

hide_if_staff_not_logged();

global $my_user;


$my_user->logout_admin();
