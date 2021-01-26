<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>



<div style="clear:both"></div>

</div>


<div class='blog-footer'>
    <div class='container'>
        <div class='home-title'><span class='title'><?php echo get_bloginfo( 'name' ); ?></span><span class='tagline'> - <?php echo get_bloginfo( 'description' ); ?></span></div>
        <a class='ph-home' target='_blank' href="<?php echo home_url(); ?>"><?php echo of_get_option('ph_blog_cta','visit Plugin Hunt');?></a>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>