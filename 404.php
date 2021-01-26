<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 */
get_header('404');
?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
		<?php
		$loc = PLUGINHUNT_URL .'/images/404.png';
		$home = home_url();
		echo "<a href='".$home."'><img src='".$loc."' style='margin-top:80px'/>";
		?>
		</a>
		<div class='error-search'>
			<?php get_search_form(); ?>
		</div>
		</div><!-- #content -->
	</div><!-- #primary -->


<?php
get_footer();
?>