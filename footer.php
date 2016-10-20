<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage BadJohnny
 * @since BadJohnny 1.0
 */
?>
	</div><!-- #main .wrapper -->
	<footer id="colophon" role="contentinfo">
		<div class="site-info">
		    &copy;2015 <a href="<?php echo home_url();?>" title="<?php bloginfo('description');?>"><?php bloginfo('name');?></a>. All Right Reserved.</a> 
			<?php if ( !current_user_can( 'administrator' ) ):?>
			<!--If you'd like to support us, hope you can keep the following text at the bottom of the page, thank you! -->
			<p>Theme Designed By <a href="<?php echo esc_url( __( 'http://www.themevan.com/', 'badjohnny' ) ); ?>" title="<?php esc_attr_e( 'Free and Premium Elegant WordPress Themes', 'badjohnny' ); ?>">ThemeVan</a> | Powered by <a href="http://wordpress.org">WordPress</a></p>
			<?php endif;?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>