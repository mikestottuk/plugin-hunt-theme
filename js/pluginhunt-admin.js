jQuery(document).ready(function(){
    pluginhuntbindclicks();
});

function pluginhuntbindclicks(){
    jQuery('.upgrade').unbind("click").bind("click",function(e){
        uid = jQuery(this).data('id');
        level = jQuery(this).data('level');
            var t = {
                action: "ph_upgrade_user",
                uid: uid,
                level: level,
            },
            a = jQuery.ajax({
                url: ajaxurl,
                type: "POST",
                data: t,
                dataType: "json"
            });
        a.done(function(msg) {
            console.log("ajax successful");
            location.reload();
            console.log(msg);
         }), a.fail(function(msg) {
            console.log("ajax failure");
            console.log(msg);
        })


    });
}
