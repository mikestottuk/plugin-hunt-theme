jQuery(document).ready(function () {

    var navListItems = jQuery('div.setup-panel div a'),
            allWells = jQuery('.setup-content'),
            allNextBtn = jQuery('.nextBtn');
            alBackBtn = jQuery('.backBtn');

    var phOptions = {
        layout:1,
    };

    allWells.hide();

    navListItems.click(function (e) {
        e.preventDefault();
        var jQuerytarget = jQuery(jQuery(this).attr('href')),
                jQueryitem = jQuery(this);

        if (!jQueryitem.hasClass('disabled')) {
            navListItems.removeClass('btn-primary').addClass('btn-default');
            jQueryitem.addClass('btn-primary');
            allWells.hide();
            jQuerytarget.show();
            jQuerytarget.find('input:eq(0)').focus();
        }
    });

    allNextBtn.click(function(){
        var curStep = jQuery(this).closest(".setup-content"),
            curStepBtn = curStep.attr("id"),
            nextStepWizard = jQuery('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
            curInputs = curStep.find("input[type='text'],input[type='url']"),
            isValid = true;

        jQuery(".form-group").removeClass("has-error");
        for(var i=0; i<curInputs.length; i++){
            if (!curInputs[i].validity.valid){
                isValid = false;
                jQuery(curInputs[i]).closest(".form-group").addClass("has-error");
            }
        }

        if (isValid)
            nextStepWizard.removeAttr('disabled').trigger('click');
    });



    jQuery('div.setup-panel div a.btn-primary').trigger('click');


    jQuery('.l-o').unbind('click').bind('click', function(e){
        if(!jQuery(this).hasClass('selected')){
            jQuery('.l-o').removeClass('selected');
            phOptions.layout = jQuery(this).data('lo');
            jQuery(this).addClass('selected');
            console.log('layout ' + phOptions.layout);
        }
    });

    jQuery('.ph-finito').unbind('click').bind('click',function(e){
        if(jQuery(this).hasClass('disabled')){
            return false;
        }
        jQuery(this).addClass('disabled');
            var t = {
                action: "ph_layout_init",
                layout: phOptions.layout,
                security: jQuery( '#phos-ajax-nonce' ).val()
            };
            console.log(t);
            i = jQuery.ajax({
                url: window.ajaxurl,
                type: "POST",
                data: t,
                dataType: "json"
            });
        i.done(function(msg) {
            window.location.replace(jQuery( '#phf-finish' ).val());
        });
        i.fail(function(msg) {
            alert('something went wrong.. are you connected to the internet?');
        });

    });

    jQuery('.demoGo').unbind('click').bind('click',function(e){
        if(jQuery(this).hasClass('disabled')){
            return false;
        }
        jQuery(this).addClass('disabled');
        jQuery('.demo-content').html('Installing demo content...');
        jQuery('.setup-content .progress').fadeIn(1000);
            var t = {
                action: "ph_democontent",
                security: jQuery( '#phdc-ajax-nonce' ).val()
            };
            console.log(t);
            i = jQuery.ajax({
                url: window.ajaxurl,
                type: "POST",
                data: t,
                dataType: "json"
            });
        i.done(function(msg) {
            jQuery('.setup-content .progress-bar').css("width", "100%");
            jQuery('.demo-content').html('Install complete!');
            jQuery('.demoNext').html('Next');
            jQuery('.demoGo').hide();
        });
        i.fail(function(msg) {
            alert('something went wrong.. are you connected to the internet?');
        });

    });



});