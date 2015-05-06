<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */
define('PAGE_ID', '404');
define('PAGE_NAME', e('404_error_page_name', '1'));
get_file('header'); //Puoi usare questa funzione anche così: get_file('header', 'nome'); lui caricherà il file header-nome.php
?>
    <div class="section-colored text-center">

        <div class="container">

            <div class="row">
                <div class="col-lg-12" style="color: #ffffff">
                    <h2><?php e('404_title');?></h2>
                    <p><?php e('404_description');?></p>
                </div>
            </div>
            <!-- /.row -->

        </div>
        <!-- /.container -->

    </div>
<?php get_file('footer'); ?>