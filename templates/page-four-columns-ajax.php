<?php
/**
 * SmugMug Template Name: Page Four Columns (Ajax)
 *
 */

get_header(); 

wp_reset_query();

$galleries = SMW::getFrontend('galleries');

?>
    <div id="primary">
	<div id="content" role="main">
						
        <h1 class="page-title"><?php the_title(); ?></h1>

        <?php the_content(); ?>
        <div id="smw-images" class="four-columns">
            <?php $galleries->render(get_the_ID()); ?>
        </div>
   </div><!-- #content -->
</div><!-- #primary -->
<?php get_footer(); ?>
