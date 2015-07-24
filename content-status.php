<?php
/**
 * The template for displaying posts in the Status post format
 *
 * @package WordPress
 * @subpackage BadJohnny
 * @since BadJohnny 1.0
 */
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="entry-header">
			<header>
				<h3><?php the_author(); ?></h3>
				<h4><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'badjohnny' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php echo get_the_date(); ?></a></h4>
			</header>
			<?php
			/**
			 * Filter the status avatar size.
			 *
			 * @since BadJohnny 1.0
			 *
			 * @param int $size The height and width of the avatar in pixels.
			 */
			$status_avatar = apply_filters( 'badjohnny_status_avatar', 48 );
			echo get_avatar( get_the_author_meta( 'ID' ), $status_avatar );
			?>
		</div><!-- .entry-header -->

		<div class="entry-content">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'badjohnny' ) ); ?>
		</div><!-- .entry-content -->

		<footer class="entry-meta">
			<?php if ( comments_open() ) : ?>
			<div class="comments-link">
				<?php comments_popup_link( '<span class="leave-reply">' . __( 'Leave a reply', 'badjohnny' ) . '</span>', __( '1 Reply', 'badjohnny' ), __( '% Replies', 'badjohnny' ) ); ?>
			</div><!-- .comments-link -->
			<?php endif; // comments_open() ?>
			<?php edit_post_link( __( 'Edit', 'badjohnny' ), '<span class="edit-link">', '</span>' ); ?>
		</footer><!-- .entry-meta -->
	</article><!-- #post -->
