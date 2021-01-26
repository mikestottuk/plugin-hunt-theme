
<form class = 'form-search search-box search-logged' method="get" id="searchform" action="<?php echo esc_url( home_url('/') );?>">
<div class="input search-form">
	<label class="hidden" for="s" style = "display:none"><?php _e('Search this site:','pluginhunt'); ?></label>
	<input type="text" class="input-xlarge" placeholder = "<?php _e('Search..','pluginhunt'); ?>" value="<?php the_search_query(); ?>" name="s" id="s" />
	<input type="hidden" name="post_type" value="post" />
</div>
</form>
