<?php 
/*                     *\
|	MYCMS - TProgram    |
\*                     */
require_page(true, 'blog');
global $my_db;
?>
<br />
<div class="col-lg-4" style="color: #000000 !important;">
                <div class="well">
                    <h4><?php e('blog-bar_search');?></h4>
                     <form role="searchblog" method="post">
                        <div class="input-group">
                            <input type="text" class="form-control" name="searchform" id="srch-term">
                            <div class="input-group-btn">
                                <button class="btn btn-default" name="search" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="well">
                    <h4><?php e('blog-bar_popular_categories');?></h4>
                    <div class="row">
                            <ul class="list-unstyled">
                            	<?php
									$cat = $my_db->query("SELECT *, count(*) AS count
    FROM my_blog INNER JOIN my_blog_category
    ON (my_blog.postCATEGORY = my_blog_category.catNAME)
    GROUP BY my_blog_category.catNAME
    ORDER BY count(*) DESC LIMIT 10");
									$i = 0; foreach($cat as $category){ $i++; 
								?>
                            	<li><a href="{@siteURL@}/blog/category/<?php echo $category['catNAME']; ?>"><?php echo $category['catNAME']; ?></a>
                                </li>
								<?php
                                }
                                ?>   
                            </ul>
                    </div>
                </div>
            </div>