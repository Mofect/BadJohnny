<?php
/**
 * Template Name: Full-width Blog Template, no sidebar
 * @package WordPress
 * @subpackage BadJohnny
 * @since BadJohnny 1.0
 */
get_header(); ?>

	<div id="primary" class="site-content single-column">
		<div id="content" role="main">
		
		<?php
		 $limit = get_option('posts_per_page');
	     $paged = (get_query_var('page')) ? get_query_var('page') : 1;
	     query_posts('post_type=post&posts_per_page='.$limit.'&paged='.$paged);
		?>
		
		<?php if (have_posts()) : ?>

			
			<?php while (have_posts() ) : the_post();  ?>
				<?php get_template_part( 'content', get_post_format() ); ?>
			<?php endwhile; ?>

			<?php badjohnny_content_nav( 'nav-below' ); ?>
			

		<?php else : ?>

			<article id="post-0" class="post no-results not-found">

			<?php if ( current_user_can( 'edit_posts' ) ) :
				// Show a different message to a logged-in user who can add posts.
			?>
				<header class="entry-header">
					<h2 class="entry-title"><?php _e( 'No posts to display', 'badjohnny' ); ?></h2>
				</header>

				<div class="entry-content">
					<p><?php printf( __( 'Ready to publish your first post? <a href="%s">Get started here</a>.', 'badjohnny' ), admin_url( 'post-new.php' ) ); ?></p>
				</div><!-- .entry-content -->

			<?php else :
				// Show the default message to everyone else.
			?>
				<header class="entry-header">
					<h2 class="entry-title"><?php _e( 'Nothing Found', 'badjohnny' ); ?></h2>
				</header>

				<div class="entry-content">
					<p><?php _e( 'Apologies, but no results were found. Perhaps searching will help find a related post.', 'badjohnny' ); ?></p>
					<?php get_search_form(); ?>
				</div><!-- .entry-content -->
			<?php endif; // end current_user_can() check ?>

			</article><!-- #post-0 -->

		<?php endif; // end have_posts() check ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>