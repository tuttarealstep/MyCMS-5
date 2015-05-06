<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */
define('PAGE_ID', 'maintenance');
define('PAGE_NAME', e('maintenance_page_name', '1'));
add_meta_tag('maintenance', '<meta http-equiv="refresh" content=60 url="{@siteURL@}">');
get_file('header'); //Puoi usare questa funzione anche così: get_file('header', 'nome'); lui caricherà il file header-nome.php
?>
<div class="section-colored text-center">

        <div class="container">

            <div class="row">
                <div class="col-lg-12">
                    <h2><?php e('maintenance_title');?></h2>
                    <p><?php e('maintenance_title_description');?></p>
                </div>
            </div>
            <!-- /.row -->

        </div>
        <!-- /.container -->

</div>
<?php get_file('footer'); ?>