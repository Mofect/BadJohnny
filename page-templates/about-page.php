<?php
/**
 * Template Name: About Page Template
 *
 * Description: A page template to show the blogger personal introduction.
 *
 * @package WordPress
 * @subpackage BadJohnny
 * @since BadJohnny 1.0
 */

get_header(); ?>

		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>
				<div class="card">
					<?php if(has_post_thumbnail()):?>
						<div class="avatar"><?php the_post_thumbnail();?></div>
					<?php else:?>
						<div class="avatar"><?php echo get_avatar( get_the_author_meta( 'ID' ), 80 ); ?></div>
					<?php endif?>
					<div class="intro">
						<h1 class="name"><?php the_author_meta('nickname'); ?></h1>
						<div class="bio">
						<?php 
						if(get_the_content()<>''){
						    the_content();
						}else{
							the_author_meta('description'); 
						}
						?> 
						</div>
						
						<div class="social">
							<h2 class="separate_text"><span><?php esc_html_e('Follow Me','badjohnny');?></span></h2>
							<?php badjohnny_author_socials();?>
					    </div>
						<a href="<?php echo get_the_author_meta('url');?>" class="button"><?php esc_html_e('Blog','badjohnny');?></a>
					</div>
				</div>
			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->

<?php get_sidebar( 'front' ); ?>
<?php get_footer(); ?>