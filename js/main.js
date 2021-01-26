jQuery(document).ready(function($){
	
    initialiseslick(0);
    initialiseslick(1);
    $("#ph-log-social-new").animatedModal({
        animatedIn:'zoomIn',
        animatedOut:'zoomOut',
        color:'#fff',
    });

    $(".dropdown-toggle").dropdown();


    $(function() {
      $('#comment').on('keyup paste', function() {
        var $el = $(this),
            offset = $el.innerHeight() - $el.height();

        if ($el.innerHeight < this.scrollHeight) {
          //Grow the field if scroll height is smaller
          $el.height(this.scrollHeight - offset);
        } else {
          //Shrink the field and then re-set it to the scroll height in case it needs to shrink
          $el.height(1);
          $el.height(this.scrollHeight - offset);
        }
      });
    });    


    $('.submit-topic-prompt').unbind('click').bind('click',function(e){

        $('.simple-submit-prompt').toggle();
        $('.simple-submit').toggle();


    });

    $('.submit-close').unbind('click').bind('click',function(e){

        $('.simple-submit-prompt').toggle();
        $('.simple-submit').toggle();

    });


    $('.ph-search-form #s').focusin(function(){
        console.log('expanding...');
        $('.ph-search-wrap').addClass('expanded');
      //  $('.ph-search-wrap').css("width", "70%");
      //  $(this).css("width", "100%");
      //  $(this).css("max-width", "100%");
    });

    $('.ph-search-form #s').focusout(function(){
        $('.ph-search-wrap').removeClass('expanded');   
    });

    $('.simple-submit .submit-discuss').unbind('click').bind('click',function(e){

        var title = $('#discuss-title').val();
        var content = $('#discuss-content').val();
        nonce = jQuery("#nonce").val();

        discat = $("#discussioncatslim").val()

        $('.simple-submit').toggle();
        $('.simple-loading').show();

        var t = {
                action: "ph_simple_discuss",
                nonce: nonce,
                title: title,
                content: content,
                discat: discat
            }

            i = jQuery.ajax({
                url: HuntAjax.ajaxurl,
                type: "POST",
                data: t,
                dataType: "json"
            });
        i.done(function() {
            console.log('all OK');
            $('.simple-submit-prompt').before('<div class="alert alert-success yay">Discussion Submitted Sucessefully. Refresh the page to see your topic</div>').fadeIn(3000);
            $('.ya').fadeOut(3000);
            $('.simple-submit-prompt').toggle();
            $('.simple-loading').hide();
            $('#discuss-content').val("");
            $('#discuss-content').val("");



        }), i.fail(function() {
            alert('something went wrong');
        })


    });


    $('.ph-s-em').unbind('click').bind('click', function(e){
        var ph_email_subject = $(this).data('subject');
        var ph_link = $(this).data('link');
        window.location.href = "mailto:user@example.com?subject="+ph_email_subject+"&body=" + ph_link;
    });

    $('.ph-slider .slick-track .slide').unbind('click').bind('click',function(e){
        var e = jQuery('.out', this).attr("data-ph-url");
        jQuery("html").addClass("noscroll");
        jQuery(".modal-container").html("");
        jQuery("body").addClass("showing-discussion");
        jQuery(".modal-overlay").show();
        jQuery(".show-post-modal").show();
        jQuery(".modal-container").show();
        jQuery(".modal-loading").show();
        jQuery(".show-post-modal").removeClass("hide");
        jQuery(".modal--close").show();
        phflash(e);
    });

    $('.ph-slider-nav .fa-chevron-left').bind('click',function(e){
        $('.ph-slider').slick('slickPrev');
    });

    $('.ph-slider-nav .fa-chevron-right').bind('click',function(e){
        $('.ph-slider').slick('slickNext');
    });

    $('.v-next').unbind('click').bind('click',function(e){
        $('.ph-slick').slick('slickNext');
    });

    $('.v-prev').unbind('click').bind('click',function(e){
        $('.ph-slick').slick('slickPrev');
    });


    $('.ph-slider .novote').unbind('click').bind('click', function(e){
        e.preventDefault();
    })



jQuery('form#popover--simple-form--input :input[name="media_url"]').off("input").on("input", function(t) {
            var o = this.value;
            return phisUrlValid(o) && (iurl = e(o)), !0
        })


    tinymce.init({
    selector: "#tagline-dis",
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
    });


    tinymce.init({
    selector: "#tagline-woo",
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
    });

    tinymce.init({
    selector: "#tagline-full",
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
    });

    $('.phlander').unbind('click').bind('click',function(ev){
            $(this).css('background-image','none');
            $('.phlander iframe').fadeIn(1000);
            $("#video")[0].src += "&autoplay=1";
            ev.preventDefault();
    });

     $('#woo-product-type').on('change',function(){
        if( $(this).val()==="2"){
                $('.woo-item-condition').show();
                $('.woo-reserve-product-price').show();
        }
        else{
                $('.woo-item-condition').hide();
                $('.woo-reserve-product-price').hide();
        }
    });


    $('.v-category-discussion').unbind('click').bind('click', function(e){
    		e.preventDefault();
            $('.new-post-info').addClass('hide');
            $('.new-discuss-info').removeClass('hide');
            $('.new-woo-info').addClass('hide');
    		$(this).addClass('m-active-g');
    		$('.v-category-product').removeClass('m-active');
            $('.v-category-woo').removeClass('m-active-w');
    		$('.post-category').addClass('hide');
    		$('.discussion-category').removeClass('hide');
    		$('.modal-post-submission--header').css("background",phe.ph_dis_hc);
    		$('#post-submission input[type=submit]').css("background",phe.ph_dis_hc);
            $('#post-submission input[type=submit]').css("border-color",phe.ph_dis_hc);
    		$('.post-submission-full').css("min-height",1300);
    		$('.post-submission').css("min-height",1300);
    		$('#discussion-content').removeClass('hide');
            $('.v-category-product').css('color','#bbb');
            $('.v-category-woo').css('color','#bbb');
            $('.v-category-discussion').css('color',phe.ph_dis_hc);
            $('.woo-category').addClass('hide');
            $('.ph-newsubmit-button').val($(this).html());
            $('.woo-reserve-product-price').hide();
            $('.woo-item-condition').hide();
    		window.phproduct = 'discussion';
    });

    $('.woo-auction .reddit-post').unbind('click').bind('click',function(e){
        e.stopPropagation();
        e.preventDefault();
        alocation = $(this).data('ph-url');
        window.location.replace(alocation);
    });

    $('.v-category-product').unbind('click').bind('click', function(e){
    		e.preventDefault();
            $('.new-discuss-info').addClass('hide');
            $('.new-post-info').removeClass('hide');
            $('.new-woo-info').addClass('hide');
    		$(this).addClass('m-active');
    		$('.v-category-discussion').removeClass('m-active-g');
            $('.v-category-woo').removeClass('m-active-w');
    		$('.modal-post-submission--header').css("background",phe.ph_post_hc);
			$('#post-submission input[type=submit]').css("background",phe.ph_post_hc);
            $('#post-submission input[type=submit]').css("border-color",phe.ph_post_hc);
			$('.post-category').removeClass('hide');
			$('.discussion-category').addClass('hide');
			$('.post-submission-full').css("min-height",1300);
			$('.post-submission').css("min-height",1300);
            $('.v-category-discussion').css('color','#bbb');
            $('.v-category-woo').css('color','#bbb');
            $('.v-category-product').css('color',phe.ph_post_hc);
            $('.woo-category').addClass('hide');
            $('.woo-reserve-product-price').hide();
            $('.woo-item-condition').hide();
            $('.ph-newsubmit-button').val($(this).html());

			window.phproduct = 'post';
    });

    $('.v-category-woo').unbind('click').bind('click',function(e){
        e.preventDefault();
        $('.new-post-info').addClass('hide');
        $('.new-discuss-info').addClass('hide');
        $('.new-woo-info').removeClass('hide');

        $('.woo-category').removeClass('hide');
        $('.discussion-category').addClass('hide');
        $('.post-category').addClass('hide');

        $('.modal-post-submission--header').css("background",phe.ph_woo_hc);
        $('#post-submission input[type=submit]').css("background",phe.ph_woo_hc);
        $('#post-submission input[type=submit]').css("border-color",phe.ph_woo_hc);
        $(this).addClass('m-active-w');
        $('.m-active-w').css("color", phe.ph_woo_hc);
        $('.v-category-discussion').removeClass('m-active-g');
        $('.v-category-product').removeClass('m-active');
        $('.v-category-product').css('color','#bbb');
        $('.v-category-discussion').css('color','#bbb');
        $('.woo-reserve-product-price').hide();
        $('.woo-item-condition').hide();
        $('.ph-newsubmit-button').val($(this).html());

        window.phproduct = 'woo';
    })

    //Mobile Menu
    $("#hamburger").on("click", function() {
        //alert('aaa');
        $(".ph_62_menu_wrapper").toggleClass('menu-hide');
    })


});


function ph_email_share(){

    $('.ph-s-em').unbind('click').bind('click', function(e){
        var ph_email_subject = $(this).data('subject');
        var ph_link = $(this).data('link');
        window.location.href = "mailto:user@example.com?subject="+ph_email_subject+"&body=" + ph_link;
    });
}


function countdownfire(){
        jQuery( ".auction-time-countdown" ).each(function( index ) {
        var time    = jQuery(this).data('time');
        var format  = jQuery(this).data('format');
        
        if(format == ''){
            format = 'yowdHMS';
        }
        var etext ='';
        if(jQuery(this).hasClass('future') ){
            var etext = '<div class="started">'+data.started+'</div>';  
        } else{
            var etext = '<div class="over">'+data.finished+'</div>';
            
        }
        
        
        
        
        
        jQuery(this).SAcountdown({
            until:   jQuery.SAcountdown.UTCDate(-(new Date().getTimezoneOffset()),new Date(time*1000)),
            format: format, 
            
            onExpiry: closeAuction,
            expiryText: etext
        });
             
    });
}