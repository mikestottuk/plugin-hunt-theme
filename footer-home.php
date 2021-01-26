<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>



<div style="clear:both"></div>

</div>


<div class='home-footer'>
    <div class='container'>
        <div class='footer-1' style='height:75px'>
            <div class='col-md-6'>
                <a href='<?php echo esc_url( home_url( '/' ) ); ?>' title='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>' rel='home'>
                    <div class='pluginwrapper'>
                        <div class='phhomelogom'><img src='<?php echo of_get_option('white_logo'); ?>'/></div>
                      <div class='wrap-me'>
                            <div class='plugin'><?php echo get_bloginfo( 'name' ); ?></div>
                            <div class='hunttheme hide'><?php echo get_bloginfo( 'description' ); ?></div>
                      </div>
                    </div>
                </a>
            </div>
            <div class='col-md-6 cta'>
            <?php if(of_get_option('ph_footer_cta') != ''){ ?>
                <?php echo of_get_option('ph_footer_cta','Text to input before the CTA button.'); ?>
                <span class='buy'><a href="<?php echo of_get_option('ph_footer_cta_link','http://pluginhunt.com/pricing'); ?>"> <?php echo of_get_option('ph_footer_cta_but','Buy Today.'); ?></a></span>
                <?php } ?>
            </div>
            <div class='clear'></div>
        </div>
        <div class='clear'></div>
        <div class='footer-2'>
            <div class='col-md-3'>
                <?php wp_nav_menu( array( 'container' => '','container_class' => '','theme_location' => 'footer1','menu_class' => 'nav','walker' => new Bootstrap_Walker()) ); ?>
            </div>
            
            <div class='col-md-3'>
                 <?php 

                 $fmenu2 = wp_nav_menu( array( 'container' => '','container_class' => '','theme_location' => 'footer2','menu_class' => 'nav','walker' => new Bootstrap_Walker()) );

                 if ( $fmenu2 )
                 {   
                    echo $fmenu2;
                 }

                  ?>
            </div>
            
            <div class='col-md-3'>
                <?php 
                $fmenu3 = wp_nav_menu( array( 'container' => '','container_class' => '','theme_location' => 'footer3','menu_class' => 'nav', 'echo' => 'false' ,'walker' => new Bootstrap_Walker()) );

                   if($fmenu3){
                        echo $fmenu3;
                   }

                 ?>            
            </div>
            
            <div class='col-md-3'>
                <?php wp_nav_menu( array( 'container' => '','container_class' => '','theme_location' => 'footer4','menu_class' => 'nav','walker' => new Bootstrap_Walker()) ); ?>                
            </div>
        </div>

        <div class='clear'></div>
        <div class='footer-3'>
            <div class='col-md-6 copy'>
                <p><?php echo of_get_option('ph_footer_copyright','Copyright 2016 your site. All rights reserved.'); ?></p>
            </div>
            <div class='col-md-6 contact'>
                <?php echo of_get_option('ph_footer_talk',"<p>Want to talk to us? <a href='#'>Contact us here</a></p>"); ?>
            </div>
        </div>

    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>