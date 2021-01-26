var currentDrop, pluginHuntTheme_Global = {
    fbLoadTryCount: 0,
    twLoadTryCount: 0,
    keyset: 0,
    page: 1,
    page_cat: 2,
    epicload: 0,
    imgArray: [],
    ytHeight: 400,
    ytWidth: 520
};

function bindmedia() {
    jQuery(".postmedia, .v-add").unbind("click").bind("click", function(e) {
        return 0 == HuntAjax.logged ? (jQuery("#ph-log-social-new").click(), !1) : (jQuery("#ph_collections_list").html(""), jQuery("html").addClass("noscroll"), pid = jQuery(this).data("pid"), jQuery(".popover--simple-form--actions").attr("data-pid", pid), jQuery(".collections-popover--form--submit").attr("data-pid", pid), e.stopPropagation(), e.preventDefault(), void jQuery(".ph_popover_media").show())
    }), jQuery(".single .v-add").unbind("click").bind("click", function(e) {
        return 0 == HuntAjax.logged ? (jQuery("#ph-log-social-new").click(), !1) : (jQuery("#ph_collections_list").html(""), pid = jQuery(this).data("pid"), jQuery(".popover--simple-form--actions").attr("data-pid", pid), jQuery(".collections-popover--form--submit").attr("data-pid", pid), e.stopPropagation(), e.preventDefault(), void jQuery(".ph_popover_media").show())
    })
}

function phvalidateEmail(e) {
    var t = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return t.test(e) ? !0 : !1
}

function phflash(e) {
        jQuery(".modal-container").load(e + " #phsf", function(e) {
            jQuery(".modal-loading").hide();
            pluginhuntbind();
            var t = 1;
            initialiseslick(t)
        });
}

function phcomments(e) {
        jQuery("#video-ph-modal .video-comments").load(e + " .section-discussion", function(e) {
            jQuery('#modal-fullscreen').modal('show');
        });
}


function phslickbind() {
    jQuery(".slick-slide").unbind("click").bind("click", function(e) {
        if (e.preventDefault(), jQuery("body").addClass("noscroll"), img = jQuery("img", this).attr("src"), youtube = jQuery(".phlb", this).data("yturl")) {
            var t = pluginHuntTheme_Global.ytHeight,
                i = pluginHuntTheme_Global.ytWidth;
            vid = jQuery(".phlb", this).data("ytid"), embed = '<iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0"width="' + i + '" height="' + t + '" type="text/html" src="https://www.youtube.com/embed/' + vid + '?autoplay=0"></iframe>', me = "<div class='lightbox-image'>" + embed + "</div>"
        } else me = "<div class='lightbox-image'><img src='" + img + "'/></div>";
        ph_index = jQuery(this).data("slick-index"), num = ph_index + 1, ph_tot = jQuery(".phlb").length, 0 == ph_index ? jQuery(".v-left-lb").addClass("deactivate") : jQuery(".v-left-lb").removeClass("deactivate"), num == ph_tot ? jQuery(".v-right-lb").addClass("deactivate") : jQuery(".v-right-lb").removeClass("deactivate"), jQuery(".modal-overlay-lightbox").toggle(), jQuery("#lb-num").html(num), jQuery("#lb-tot").html(ph_tot), jQuery(".ph-lb-post-image").html(me)
    })
}

function bindFires() {}

function updateTwitterValues(e, t) {
    if ("undefined" != typeof twttr && "undefined" != typeof twttr.widgets) jQuery("#twitter-share-section").html("&nbsp;"), jQuery("#twitter-share-section").html('<a href="https://twitter.com/share" class="twitter-share-button" data-url="' + e + '" data-size="medium" data-text="' + t + '" data-count="none" height:"20px" width:"57px">Tweet</a>'), twttr.widgets.load();
    else {
        var i = 5;
        window.pluginHuntTheme_Global.twLoadTryCount <= i && (window.pluginHuntTheme_Global.twLoadTryCount++, setTimeout(function() {
            updateTwitterValues(e, t)
        }, 300))
    }
}

function updateFacebookValues(e) {
    if ("undefined" != typeof FB && "undefined" != typeof FB.XFBML) jQuery("#facebook-share-section").html("&nbsp;"), jQuery("#facebook-share-section").html('<fb:like href="' + e + '" layout="button" action="like" show_faces="true" share="false"></fb:like>'), FB.XFBML.parse();
    else {
        var t = 5;
        window.pluginHuntTheme_Global.fbLoadTryCount <= t && (window.pluginHuntTheme_Global.fbLoadTryCount++, setTimeout(function() {
            updateFacebookValues(e)
        }, 300))
    }
}

function phhtmlEncode(e) {
    return jQuery("<div/>").text(e).html()
}

function epic_infinite_scroll() {
    if (jQuery("#epic_page_end_2").length > 0) return pluginHuntTheme_Global.epicload = 1, !1;
    if (pluginHuntTheme_Global.epicload = 1, msg = window.HuntAjax.epic_more, jQuery("#results").append("<div id='epic_page_end'>" + msg + "</div>"), 0 == pluginHuntTheme_Global.keyset && (key = jQuery("#epic-key").html()), 1 == pluginHuntTheme_Global.keyset, 1 == pluginHuntTheme_Global.keyset) var e = jQuery(".next-posts-link a").attr("href") + "&page=" + pluginHuntTheme_Global.page + "&key=" + key;
    else var e = jQuery(".next-posts-link a").attr("href") + "&page=" + pluginHuntTheme_Global.page;
    console.log('e is ' + e);
    jQuery.get(e, function(e) {
        jQuery(e).find(".maincontent").appendTo("#results"), 0 == pluginHuntTheme_Global.keyset && (key = jQuery("#epic-key").html(), pluginHuntTheme_Global.keyset = 1), pluginHuntTheme_Global.page++, key++, pluginHuntTheme_Global.epicload = 0, jQuery("#epic_page_end").remove(), pluginhuntbind()
    })
}

function epic_infinite_scroll_cat() {
    var e = jQuery(".ph-next-cats a").attr("href") + "?paged=" + pluginHuntTheme_Global.page_cat;
    console.log('getting data from URL ' + e);
    jQuery.get(e, function(e) {
        jQuery(e).find(".maincontent").appendTo("#results-cat")
        pluginHuntTheme_Global.page_cat++;
        console.log('next page is page ' + pluginHuntTheme_Global.page_cat);
        pluginhuntbind();
    });
}

function testMedia(e) {
    jQuery("#media_url").val()
}

function phisUrlValid(e) {
    return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(e)
}

function getYouTubeVideoImagePM(e, t) {
    if (null === e) return "";
    t = null === t ? "big" : t;
    var i = e.match(/(?:https?:\/{2})?(?:w{3}\.)?youtu(?:be)?\.(?:com|be)(?:\/watch\?v=|\/)([^\s&]+)/);
    return null != i ? (item = {}, item.url = e, item.source = "yt", item.image = "http://img.youtube.com/vi/" + i[1] + "/0.jpg", item.id = i[1], pluginHuntTheme_Global.imgArray[0] = item, jQuery(".popover--simple-form--actions").attr("data-vid", item.id), jQuery(".popover--simple-form--actions").attr("data-source", item.source), item.image) : !1
}

function getYouTubeVideoImage(e, t) {
    if (null === e) return "";
    t = null === t ? "big" : t;
    var i = e.match(/(?:https?:\/{2})?(?:w{3}\.)?youtu(?:be)?\.(?:com|be)(?:\/watch\?v=|\/)([^\s&]+)/);
    return null == i ? !1 : (item = {}, item.url = e, item.source = "yt", item.image = "http://img.youtube.com/vi/" + i[1] + "/0.jpg", item.id = i[1], pluginHuntTheme_Global.imgArray.push(item), pluginhuntbind(), iurl = "http://img.youtube.com/vi/" + i[1] + "/0.jpg", jQuery(".media-items").append('<div class="media-parent"><div class="media-item" style="background-image:url(' + iurl + ');" data-aid=""><a class="remove-media" href="' + e + '" data-aid="new" data-type="yt"><i class="fa fa-times"></i></a><a href="' + e + '" target="_blank" class="open-video"><span><svg width="35" height="35" viewBox="0 0 35 35" xmlns="http://www.w3.org/2000/svg"><path d="M17.5 35C27.165 35 35 27.165 35 17.5S27.165 0 17.5 0 0 7.835 0 17.5 7.835 35 17.5 35zm-3.71-24.57c-.152 0-.305.038-.444.116-.29.163-.47.472-.47.807l-.015 12.892c0 .336.18.645.472.808.138.077.29.116.445.116.167 0 .335-.047.483-.14l10.54-6.447c.27-.168.433-.465.433-.784 0-.32-.164-.617-.433-.786L14.274 10.57c-.147-.094-.315-.14-.483-.14z" fill="#FFF" fill-rule="evenodd"></path></svg></span></a></div></div>'), !0)
}

function editTitle(e) {
    if (13 == e.keyCode) {
        newtitle = jQuery(".edit-title-input").val(), cid = window.phcid, jQuery("#collection-title").show(), jQuery(".collection-title").html(newtitle), jQuery(".edit-title-input").addClass("hide"), jQuery("input[name=etitle]").val(newtitle);
        var t = {
                action: "ph_update_collection_title",
                title: newtitle,
                cid: cid
            },
            i = jQuery.ajax({
                url: HuntAjax.ajaxurl,
                type: "POST",
                data: t,
                dataType: "json"
            });
        i.done(function(e) {}), i.fail(function() {})
    }
}

function editDesc(e) {
    if (13 == e.keyCode) {
        newtitle = jQuery(".edit-content-input").val(), cid = window.phcid, jQuery(".collection-content").show(), jQuery(".collection-content").html(newtitle), jQuery(".edit-content-input").addClass("hide"), jQuery("input[name=econtent]").val(newtitle);
        var t = {
                action: "ph_update_collection_desc",
                title: newtitle,
                cid: cid
            },
            i = jQuery.ajax({
                url: HuntAjax.ajaxurl,
                type: "POST",
                data: t,
                dataType: "json"
            });
        i.done(function(e) {}), i.fail(function() {})
    }
}

function phremoveItem(e, t, i) {
    var o, a = !1;
    for (o in e)
        if (e[o][t] == i) {
            a = !0;
            break
        }
    a && delete e[o]
}

function bindreply() {
    jQuery(".reply-comment").unbind("click").bind("click", function(e) {
        window.phcr = jQuery(this).data("cid"), window.un = jQuery(this).data("un"), jQuery("#comment_parent").val(window.phcr), jQuery(".post-detail--footer--comments-form-toggle--link").val("@" + window.un + " "), jQuery(".post-detail--footer--comments-form-toggle--link").click().focus()
    })
}

function initialiseslick(e) {
    0 == e && jQuery(".ph-slider").length > 0 && (jQuery(".ph-slider").slick({
        infinite: !0,
        slidesToShow: 3,
        slidesToScroll: 1
    }), jQuery(".ph-slider").fadeIn(1e3), jQuery(".ph-slider").show()), 1 == e && jQuery(".ph-slick").length > 0 && jQuery(".ph-slick").slick({
        infinite: !1,
        slidesToShow: 3,
        slidesToScroll: 1,
        arrows: !1,
        variableWidth: !0
    }), jQuery(".v-right-lb").unbind("click").bind("click", function(e) {
        if (after = ph_index + 1, image_num = ph_index + 2, jQuery(".v-right-lb").hasClass("deactivate")) return !1;
        if (ph_index + 1 > 0 && jQuery(".v-left-lb").removeClass("deactivate"), ph_index += 1, af = jQuery("img", '*[data-slick-index="' + after + '"]').attr("src"), youtube = jQuery("img", '*[data-slick-index="' + after + '"]').data("yturl"), youtube) {
            var t = pluginHuntTheme_Global.ytHeight,
                i = pluginHuntTheme_Global.ytWidth;
            vid = jQuery("img", '*[data-slick-index="' + after + '"]').data("ytid"), embed = '<iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0"width="' + i + '" height="' + t + '" type="text/html" src="https://www.youtube.com/embed/' + vid + '?autoplay=0"></iframe>', me = "<div class='lightbox-image'>" + embed + "</div>"
        } else me = "<div class='lightbox-image'><img src='" + af + "'/></div>";
        return jQuery(".ph-lb-post-image").html(me), jQuery("#lb-num").html(image_num), image_num >= ph_tot ? (jQuery(".v-right-lb").addClass("deactivate"), jQuery(".v-left-lb").removeClass("deactivate"), !1) : void 0
    }), jQuery(".v-left-lb").unbind("click").bind("click", function(e) {
        if (before = ph_index - 1, bf = jQuery("img", '*[data-slick-index="' + before + '"]').attr("src"), image_num = ph_index, jQuery(".v-left-lb").hasClass("deactivate")) return !1;
        if (ph_index + 1 <= ph_tot && jQuery(".v-right-lb").removeClass("deactivate"), youtube = jQuery("img", '*[data-slick-index="' + before + '"]').data("yturl"), youtube) {
            var t = pluginHuntTheme_Global.ytHeight,
                i = pluginHuntTheme_Global.ytWidth;
            vid = jQuery("img", '*[data-slick-index="' + before + '"]').data("ytid"), embed = '<iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0"width="' + i + '" height="' + t + '" type="text/html" src="https://www.youtube.com/embed/' + vid + '?autoplay=0"></iframe>', me = "<div class='lightbox-image'>" + embed + "</div>"
        } else me = "<div class='lightbox-image'><img src='" + bf + "'/></div>";
        return jQuery(".ph-lb-post-image").html(me), jQuery("#lb-num").html(image_num), ph_index -= 1, image_num <= 1 ? (jQuery(".v-left-lb").addClass("deactivate"), jQuery(".v-right-lb").removeClass("deactivate"), !1) : void 0
    }), jQuery(".ph-slick .slick-slide").unbind("click").bind("click", function(e) {
        if (e.preventDefault(), jQuery("body").addClass("noscroll"), img = jQuery("img", this).attr("src"), youtube = jQuery(".phlb", this).data("yturl"), youtube) {
            var t = pluginHuntTheme_Global.ytHeight,
                i = pluginHuntTheme_Global.ytWidth;
            vid = jQuery(".phlb", this).data("ytid"), embed = '<iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0"width="' + i + '" height="' + t + '" type="text/html" src="https://www.youtube.com/embed/' + vid + '?autoplay=0"></iframe>', me = "<div class='lightbox-image'>" + embed + "</div>"
        } else me = "<div class='lightbox-image'><img src='" + img + "'/></div>";
        ph_index = jQuery(this).data("slick-index"), num = ph_index + 1, ph_tot = jQuery(".phlb").length, 0 == ph_index ? jQuery(".v-left-lb").addClass("deactivate") : jQuery(".v-left-lb").removeClass("deactivate"), num == ph_tot ? jQuery(".v-right-lb").addClass("deactivate") : jQuery(".v-right-lb").removeClass("deactivate"), jQuery(".modal-overlay-lightbox").toggle(), jQuery("#lb-num").html(num), jQuery("#lb-tot").html(ph_tot), jQuery(".ph-lb-post-image").html(me)
    }), jQuery(".v-next").unbind("click").bind("click", function(e) {
        jQuery(".ph-slick").slick("slickNext")
    }), jQuery(".v-prev").unbind("click").bind("click", function(e) {
        jQuery(".ph-slick").slick("slickPrev")
    })
}

function pluginhuntbind() {
    function e(e) {
        var t;
        jQuery("<img>", {
            src: e,
            error: function() {
                iurl = getYouTubeVideoImagePM(e), jQuery(".img-prev").html("<img src='" + iurl + "'/>")
            },
            load: function() {
                t = {}, t.url = e, t.source = "ei", pluginHuntTheme_Global.imgArray[0] = t, jQuery(".popover--simple-form--actions").attr("data-source", t.source), jQuery(".img-prev").html("<img src='" + e + "'/>")
            }
        })
    }

    function t(e) {
        var t;
        jQuery("<img>", {
            src: e,
            error: function() {
                getYouTubeVideoImage(e)
            },
            load: function() {
                jQuery(".media-items").append('<div class="media-parent"><div class="media-item" style="background-image:url(' + e + ');" data-aid=""><a class="remove-media" href="' + e + '" data-aid="0"><i class="fa fa-times"></i></a></div></div>'), t = {}, t.url = e, t.source = "ei", pluginHuntTheme_Global.imgArray.push(t)
            }
        })
    }

    jQuery(".ph-title-prevent-click").unbind("click").bind("click",function(e){
        e.preventDefault();
    });

    jQuery("#menu-fixed-mobile-bottom li").unbind("click").bind("click",function(e){
        window.location.replace(jQuery('a',this).attr('href'));
    });

    jQuery(".mob-three-dots").unbind("click").bind("click",function(e){
        jQuery("ul",this).toggle();
    });

    jQuery(".ph-s-em").unbind("click").bind("click", function(e) {
        var t = jQuery(this).data("subject"),
            i = jQuery(this).data("link");
        window.location.href = "mailto:user@example.com?subject=" + t + "&body=" + i
    }), jQuery(function() {
        jQuery("#comment").on("keyup paste", function() {
            var e = jQuery(this),
                t = e.innerHeight() - e.height();
            e.innerHeight < this.scrollHeight ? e.height(this.scrollHeight - t) : (e.height(1), e.height(this.scrollHeight - t))
        })
    }), countdownfire(), 

    jQuery(".add_to_cart_inline").bind("click", function(e) {
        e.stopPropagation();
    });



    jQuery(".navbar-toggle").bind("click", function(e) {
        jQuery(this).hasClass("collapsed")
    }), jQuery(".modal--close-lb").unbind("click").bind("click", function(e) {
        jQuery("body").removeClass("noscroll"), jQuery(".modal-overlay-lightbox").hide(), jQuery(".ph-lb-post-image").html("")
    }), jQuery(".ph-log-new").unbind("click").bind("click", function(e) {
        return e.preventDefault(), 0 == HuntAjax.logged ? (jQuery("#ph-log-social-new").click(), !1) : void 0
    }), jQuery(".ph-view-container").unbind("click").bind("click", function(e) {
        jQuery(this).hasClass("fa-th") ? (jQuery(this).removeClass("fa-th").addClass("fa-list-ul"), jQuery("body").addClass("grid_layout").removeClass("nogrid")) : (jQuery(this).removeClass("fa-list-ul").addClass("fa-th"), jQuery("body").removeClass("grid_layout").addClass("nogrid"))
    }), bindreply(), bindmedia(), jQuery(".comment-cancel").unbind("click").bind("click", function(e) {
        jQuery(".comment-actions").hide(), jQuery(".comment-post").val(""), jQuery(".post-detail--footer-2").removeClass("high")
    }), jQuery("body").unbind("click").bind("click", function(e) {
        jQuery("html").removeClass("noscroll")
    }), jQuery(".edit-title").unbind("click").bind("click", function(e) {
        window.phcid = jQuery(this).data("cid"), jQuery(".edit-title-input").removeClass("hide"), jQuery("#collection-title").hide()
    }), jQuery(".edit-content").unbind("click").bind("click", function(e) {
        window.phcid = jQuery(this).data("cid"), jQuery(".edit-content-input").removeClass("hide"), jQuery(".collection-content").hide()
    });
    var i = jQuery("#phcommentform");
    i.prepend('<div id="comment-status" ></div>');
    var o = jQuery("#comment-status"),
        n = !1;
    jQuery(".comment-submit").unbind("click").bind("click", function() {
        var e = i.serializeArray();
        if (window.phcr)
            for (index = 0; index < e.length; ++index)
                if ("comment_parent" == e[index].name) {
                    e[index].value = window.phcr, n = !0;
                    break
                }
        values = jQuery.param(e), jQuery(".comment-submit").addClass("disabled").val("Processing..");
        var t = i.attr("action");
        return jQuery.ajax({
            type: "post",
            url: t,
            data: e,
            error: function(e, t, i) {},
            success: function(e, t) {
                "success" == e || "success" == t ? (jQuery(".comment-submit").removeClass("disabled").val("Submit"), n ? (cparent = window.phcr, jQuery("#comment-" + cparent).has(".child-comments").length > 0 ? jQuery("#comment-" + cparent + " .child-comments").first().append(e) : jQuery("#comment-" + cparent).append(e)) : jQuery("#comments").has("ol.comment-list").length > 0 ? jQuery("ol.comment-list").append(e) : (jQuery("#comments").html('<ol class="comment-list"> </ol>'), jQuery("ol.comment-list").html(e)), jQuery(".comment-actions").hide(), jQuery(".comment-post").val(""), jQuery(".comment-actions").hide(), jQuery(".comment-post").val(""), jQuery(".post-detail--footer-2").removeClass("high"), jQuery("#comments").animate({
                    scrollTop: jQuery("#comments")[0].scrollHeight
                }, 1e3), bindreply()) : (o.html('<p class="ajax-error" >Please wait a while before posting your next comment</p>'), i.find("textarea[name=comment]").val(""))
            }
        }), !1
    }), jQuery(".ph-remove-from-collection").unbind("click").bind("click", function(e) {
        e.stopPropagation(), e.preventDefault(), cid = jQuery(this).data("cid"), pid = jQuery(this).data("pid");
        var t = {
                action: "ph_remove_from_collection",
                pid: pid,
                cid: cid
            },
            i = jQuery.ajax({
                url: HuntAjax.ajaxurl,
                type: "POST",
                data: t,
                dataType: "json"
            });
        i.done(function(e) {
            jQuery(".collections-loading").hide(), jQuery("#ph_collections_list").html(e.html), jQuery(".popover--footer").hide(), jQuery(".popover--header--title").html("Removed!"), jQuery(".popover--header--icon").hide(), pluginhuntbind()
        }), i.fail(function() {})
    }), jQuery(".collection-detail--header--delete-button").unbind("click").bind("click", function(e) {
        cid = jQuery(this).data("cid");
        var t = confirm("Are you sure you want to delete this collection? There is no way back. This cannot be undone");
        if (1 != t) return !1;
        var i = {
                action: "ph_delete_collection",
                cid: cid
            },
            o = jQuery.ajax({
                url: HuntAjax.ajaxurl,
                type: "POST",
                data: i,
                dataType: "json"
            });
        o.done(function(e) {
            window.location.replace(window.HuntAjax.epichome)
        }), o.fail(function() {})
    }), jQuery(".ph-add-to-collection").unbind("click").bind("click", function(e) {
        e.stopPropagation(), e.preventDefault(), cid = jQuery(this).data("cid"), pid = jQuery(this).data("pid");
        var t = {
                action: "ph_add_collection",
                pid: pid,
                cid: cid
            },
            i = jQuery.ajax({
                url: HuntAjax.ajaxurl,
                type: "POST",
                data: t,
                dataType: "json"
            });
        i.done(function(e) {
            jQuery(".collections-loading").hide(), jQuery("#ph_collections_list").html(e.html), jQuery(".popover--footer").hide(), jQuery(".popover--header--title").html("Nice work!"), jQuery(".popover--header--icon").hide(), pluginhuntbind()
        }), i.fail(function() {})
    }), jQuery(".ph-collect,.ph-collect-single,.collect-button--icon").unbind("click").bind("click", function(e) {
        if (e.stopPropagation(), e.preventDefault(), 0 == HuntAjax.logged) return jQuery("#ph-log-social-new").click(), !1;
        jQuery("body").addClass("collecting"), jQuery(".popover--footer").show();
        var t = window.HuntAjax.atc;
        jQuery(".popover--header--title").html(t), jQuery(".popover--header--icon").show(), jQuery(".collections-loading").show(), jQuery("#ph_collections_list").html(""), jQuery("html").addClass("noscroll"), pid = jQuery(this).data("pid"), jQuery(".collections-popover--form--submit").attr("data-pid", pid), e.stopPropagation(), e.preventDefault(), popover = jQuery(".ph_popover"), pw = popover.width() / 2;
        var i = jQuery(this),
            o = i.offset(),
            a = o.top,
            n = a + i.height(),
            r = o.left,
            l = r + i.width(),
            u = r + (l - r) / 2;
        u = Math.max(0, u - pw), popover.css({
            position: "absolute",
            top: n + 10,
            left: u,
            zIndex: 5e3
        }), jQuery(".ph_popover").show();
        var d = {
                action: "ph_list_collections",
                pid: pid
            },
            s = jQuery.ajax({
                url: HuntAjax.ajaxurl,
                type: "POST",
                data: d,
                dataType: "json"
            });
        s.done(function(e) {
            jQuery(".collections-loading").hide(), jQuery("#ph_collections_list").html(e.html), pluginhuntbind()
        }), s.fail(function(e) {})
    }), jQuery(".collections-popover--form--field,.collections-popover--form--submit").unbind("click").bind("click", function(e) {
        e.stopPropagation()
    }), jQuery(".collections-popover--form--submit").unbind("click").bind("click", function(e) {
        if (e.preventDefault(), e.stopPropagation(), name = jQuery(".collections-popover--form--field").val(), "" == name) return !1;
        var t = (HuntAjax.logged, jQuery(this).data("pid")),
            i = {
                action: "ph_create_collection",
                name: name,
                prod: t
            },
            o = jQuery.ajax({
                url: HuntAjax.ajaxurl,
                type: "POST",
                data: i,
                dataType: "json"
            });
        return o.done(function(e) {
            jQuery(".popover--header--title").html("Nice work!"), jQuery(".popover--header--icon").toggle(), jQuery(".cmsg").html(e.html), jQuery(".popover--footer").toggle()
        }), o.fail(function() {}), !1
    }), jQuery(".comment-post").bind("click", function(e) {
        jQuery(".comment-actions").show(), jQuery(".post-detail--footer-2").addClass("high")
    }), jQuery(".ph-collect").unbind("click").bind("click", function(e) {
        e.preventDefault()
    }), jQuery(".collections-popover--form-trigger").unbind("click").bind("click", function(e) {
        e.preventDefault(), e.stopPropagation(), jQuery(".collections-form, .collections-popover--form-trigger").toggle()
    }), jQuery(".ph-request-access").unbind("click").bind("click", function(e) {
        e.preventDefault();
        var t = jQuery(this).data("uid"),
            i = {
                action: "ph_access_request",
                uid: t
            },
            o = jQuery.ajax({
                url: HuntAjax.ajaxurl,
                type: "POST",
                data: i,
                dataType: "json"
            });
        o.done(function(e) {}), o.fail(function() {}), jQuery(".ph-user-message-text").html("You have been added to the waiting list."), jQuery(".ph-user-message").slideDown("slow", function() {}), jQuery(".ph-request-msg").html("You have been added to the waiting list. <span class='emo'>&#x1f483;</span>")
    }), jQuery(".ph-user-close").unbind("click").bind("click", function(e) {
        jQuery(".ph-user-message").slideUp("slow", function() {})
    }), jQuery(".page-header--navigation--tab").bind("click", function(e) {
        e.preventDefault(), jQuery(".page-header--navigation--tab").removeClass("m-active"), jQuery(this).addClass("m-active");
        var t = jQuery(this).attr("id");
        jQuery(".ph-tabbed").hide(), jQuery("#" + t + "-tab").show()
    }), jQuery(".new_post_submit").bind("click", function(e) {
        if (e.preventDefault(), "" == jQuery("#submission_url").val() || "" == jQuery("#submission_name").val() || "" == jQuery("#submission_tagline").val()) return alert("form invalid"), !1;
        nonce = jQuery("#_wpnonce").val(), url = jQuery("#submission_url").val(), title = jQuery("#submission_name").val(), desc = jQuery("#submission_tagline").val();
        var t = {
                action: "ph_newpost",
                security: nonce,
                url: url,
                title: title,
                desc: desc
            },
            i = jQuery.ajax({
                url: HuntAjax.ajaxurl,
                type: "POST",
                data: t,
                dataType: "json"
            });
        i.done(function() {
            shtml = '<div class="new-post-header">POST SUBMITTED</div><div class="new-post-subheader">Your post will be reviewed by our team and if suitable make it onto the homepage </div>', shtml = window.PHnew.success, jQuery(".new-post-wrapper").hide(), jQuery(".new-post-form").hide(), jQuery(".new-post-success").html(shtml).show(), jQuery("#submission_url").val(""), jQuery("#submission_name").val(""), jQuery("#submission_tagline").val(""), again = 30
        }), i.fail(function() {
            shtml = '<div class="new-post-header">POST SUBMITTED</div><div class="new-post-subheader">Your post will be reviewed by our team and if suitable make it onto the homepage </div>', shtml = window.PHnew.success, jQuery(".new-post-wrapper").hide(), jQuery(".new-post-form").hide(), jQuery(".new-post-success").html(shtml).show(), jQuery("#submission_url").val(""), jQuery("#submission_name").val(""), jQuery("#submission_tagline").val(""), again = 30
        })
    }), jQuery(".ph-follow").bind("click", function(e) {
        if (e.preventDefault(), 0 == HuntAjax.logged) return jQuery("#ph-log-social-new").click(), !1;
        var t = jQuery(this).data(),
            i = t.follow,
            o = t.follower,
            a = t.crud,
            n = {
                action: "ph_follows",
                followed: i,
                follower: o,
                crud: a
            },
            r = jQuery.ajax({
                url: EpicAjax.ajaxurl,
                type: "POST",
                data: n,
                dataType: "json"
            });
        return r.done(function(e) {
            0 == a ? (jQuery(".ph-follow").attr("data-crud", "1"), jQuery(".ph-follow").html("Follow"), jQuery(".ph-follow").removeClass("v-red").addClass("v-green"), t.crud = 1, a = t.crud) : (jQuery(".ph-follow").attr("data-crud", "0"), jQuery(".ph-follow").html("Unfollow"), jQuery(".ph-follow").removeClass("v-green").addClass("v-red"), t.crud = 0, a = t.crud)
        }), r.fail(function(e, t) {
            alert("Request failed: " + t)
        }), !0
    }), jQuery(document).ready(function(i) {
        i(".share", this).bind("click", function(e) {
            e.preventDefault(), e.stopPropagation();
            var t = i(this).attr("href");
            if (o = i(this).attr("data-action"), "twitter" == o) {
                var a = i(this).attr("title"),
                    n = HuntAjax.ph_tweet;
                window.open("http://twitter.com/share?url=" + t + "&text=" + a + " - via " + n, "twitterwindow", "height=255, width=550, top=" + (i(window).height() / 2 - 225) + ", left=" + (i(window).width() / 2 - 275) + ", toolbar=0, location=0, menubar=0, directories=0, scrollbars=0")
            } else if ("facebook" == o) {
                var r = document.title;
                window.open("http://www.facebook.com/sharer.php?u=" + encodeURIComponent(t) + "&t=" + encodeURIComponent(r), "sharer", "status=0,width=626,height=436, top=" + (i(window).height() / 2 - 225) + ", left=" + (i(window).width() / 2 - 313) + ", toolbar=0, location=0, menubar=0, directories=0, scrollbars=0")
            } else "google" == o && window.open("https://plus.google.com/share?url=" + encodeURIComponent(t), "Share", "status=0,width=626,height=436, top=" + (i(window).height() / 2 - 225) + ", left=" + (i(window).width() / 2 - 313) + ", toolbar=0, location=0, menubar=0, directories=0, scrollbars=0")
        }), jQuery('form#popover--simple-form--input :input[name="media_url"]').off("input").on("input", function(t) {
            var i = this.value;
            return phisUrlValid(i) && (iurl = e(i)), !0
        }), jQuery('form#post-submission :input[name="media_url"]').off("input").on("input", function(e) {
            var i = this.value,
                o = !1;
            return jQuery(".remove-media").each(function(e) {
                return jQuery(this).attr("href") == i ? (o = !0, !1) : void 0
            }), o ? !1 : (phisUrlValid(i) && t(i), !0)
        }), jQuery(document).on("click", ".remove-media", function(e) {
            e.preventDefault(), aid = jQuery(this).data("aid"), url = jQuery(this).attr("href"), jQuery(this).attr("href", "#"), jQuery(this).parents(".media-parent").html(""), phremoveItem(pluginHuntTheme_Global.imgArray, "url", url)
        }), i(".popover--simple-form--actions").unbind("click").bind("click", function(e) {
            if (e.preventDefault(), imgurl = i(".popover--simple-form--input").val(), pid = i(this).data("pid"), vid = i(this).data("vid"), src = i(this).data("source"), !phisUrlValid(imgurl)) return jQuery(".urlerror").html("invalid"), !1;
            jQuery(".urlerror").html('<i class="fa fa-spinner fa-spin"></i>'), "yt" == src ? phm = '<div><a href="' + imgurl + '" class="phlb" data-ytid="' + vid + '" data-yturl="https://www.youtube.com/watch?v=' + vid + '"><img src="http://img.youtube.com/vi/' + vid + '/0.jpg" height="210px"></a></div>' : phm = '<div><a href="' + src + '" class="phlb" data-tp=' + src + '><img src="' + imgurl + '" height="210px"></a></div>', i(".ph-slick").slick("slickAdd", phm), i(".img-prev").html(""), i(".ph_popover_media").hide(), i(".media-placeholder").addClass("hide"), i(".urlerror .fa-spinner").hide(), i("#media_url").attr("value", "");
            var t = {
                action: "ph_newmedia",
                pid: pid,
                vid: vid,
                src: src,
                img: imgurl
            };
            a = jQuery.ajax({
                url: EpicAjax.ajaxurl,
                type: "POST",
                data: t,
                dataType: "json"
            }), a.done(function(e) {
                phslickbind()
            }), a.fail(function() {})
        }), i(".new-post-button").unbind("click").bind("click", function(e) {
            if(window.phe.ph_post_sub == 'one'){
                return 0 == window.HuntAjax.logged ? (jQuery("#ph-log-social-new").click(), !1) : (i(".modal--close-new").toggle(), jQuery("html").css("overflow", "hidden"), jQuery(".modal-container").html(""), jQuery("body").addClass("showing-discussion"), jQuery(".modal-overlay-new").show(), jQuery(".new-post-modal").show(), jQuery(".new-modal-container").show(), jQuery(".new-modal-loading").show(), void jQuery(".new-post-modal").removeClass("hide"))
            }else{
               window.location.replace(window.phe.ph_post_sub_url); 
            }
        });

        i(".ph-newsubmit-button").unbind("click").bind("click", function(e) {

     

            if (e.preventDefault(), i(this).hasClass("disabled")) return !1;
            var t = PHnew.ph_hunting_string;
            if ("" == t && (t = "Hunting.."), jQuery(".ph-newsubmit-button").addClass("disabled").val(t), name = i("#name").val(), url = i("#url").val(), tag = i("#tagline").val(), media = pluginHuntTheme_Global.imgArray, prodtype = i("#producttype").val(), cat = i("#cat").val(), avail = i("#postavailability").val(), discat = i("#discussioncat").val(), prod = window.phproduct, "post" == prod) {
                null == tag && (tag = tinyMCE.get("tagline-full").getContent());
                var o = {
                    action: "ph_newpost",
                    name: name,
                    url: url,
                    tag: tag,
                    media: media,
                    prodtype: prodtype,
                    avail: avail,
                    cat: cat,
                    prod: prod
                }
            }
            if ("woo" == prod) {
                tag = tinyMCE.get("tagline-woo").getContent(), name = jQuery("#wooname").val(), type = jQuery("#woo-product-type").val(), condition = jQuery("#woo-product_condition").val(), price = jQuery("#price").val(), resprice = jQuery("#resprice").val();
                var o = {
                    action: "ph_newpost",
                    name: name,
                    url: url,
                    condition: condition,
                    tag: tag,
                    media: media,
                    prodtype: prodtype,
                    avail: avail,
                    cat: cat,
                    prod: prod,
                    discat: discat,
                    price: price,
                    resprice: resprice
                }
            }
            if ("discussion" == prod) {
                name = jQuery("#title").val(), tag = tinyMCE.get("tagline-dis").getContent();
                var o = {
                    action: "ph_newpost",
                    name: name,
                    url: url,
                    tag: tag,
                    media: media,
                    prodtype: prodtype,
                    cat: cat,
                    prod: prod
                }
            }

  
            a = jQuery.ajax({
                url: EpicAjax.ajaxurl,
                type: "POST",
                data: o,
                dataType: "json"
            }), a.done(function(e) {
                window.location.replace(e.perma)
            }), a.fail(function(e) {
                window.location.replace(window.HuntAjax.epichome + "/" + e.slug)
            })
        }), i(".ph-login-link").unbind("click").bind("click", function(e) {
            return 0 == window.HuntAjax.logged ? (jQuery("#ph-log-social-new").click(), !1) : void 0
        }), i(".modal--close").unbind("click").bind("click", function(e) {
            i(".modal-overlay").toggle(), i(".modal--close").toggle(), i("body").removeClass("showing-discussion"), i("html").removeClass("noscroll"), i(".ph_popover").hide()
        }), i(".phlb").unbind("click").bind("click", function(e) {
            e.preventDefault()
        }), i(".modal--close-new").unbind("click").bind("click", function(e) {
            i(".new-post-modal").toggle(), i(".modal-overlay-new").toggle(), i(".modal--close-new").toggle(), i("body").removeClass("showing-discussion"), i("html").removeClass("noscroll"), i(".ph_popover").hide(), jQuery("html").css("overflow", "visible")
        });
        i(".reddit-voting").unbind("click").bind("click", function(e) {
            console.log('clicked the vote...');
            if (e.stopPropagation(), 0 == window.HuntAjax.logged) return jQuery("#ph-log-social-new").click(), !1;
            id = i(".arrow", this).data("red-id"), i(this).hasClass("blue") ? vote = "d" : vote = "u", parent = i(this), elem = i(".arrow", this), score = i(".score-" + id), scoreval = Number(i(".score-" + id).html()), "u" == vote && (score.html(scoreval + 1), i(".reddit-voting-" + id).addClass("blue").removeClass("none")), "d" == vote && (score.html(scoreval - 1), i(".reddit-voting-" + id).addClass("none").removeClass("blue"));
            var t = {
                action: "hackers_vote",
                id: id,
                vote: vote,
                security: HuntAjax.ajax_nonce
            };
            a = jQuery.ajax({
                url: HuntAjax.ajaxurl,
                type: "POST",
                data: t,
                dataType: "json"
            });
            a.done(function(mess) {
                console.log("success in the AJAX vote..");
                console.log(mess);
                "u" == vote ? (newscore = scoreval + 1, score.html(newscore)) : "d" == vote && (newscore = scoreval - 1, score.html(newscore))
            }), a.fail(function() {
                console.log('failure in the AJAX vote..');
            })
        })
    }), jQuery(".showmore").bind("click", function() {
        jQuery(this).hide(), ud = jQuery(this).data("d"), um = jQuery(this).data("m"), uy = jQuery(this).data("y"), jQuery(".hidepost-" + ud + "-" + um + "-" + uy).show()
    }), 

    jQuery(".reddit-post").unbind("click").bind("click", function() {
        if(jQuery(this).hasClass('.negotiator-product-button-container') || jQuery(this).hasClass('.negotiator-product-button')){
            if(jQuery('.negotiator-product-button-container').length){
                return false;
            }
        }
        var e = jQuery(this).attr("data-ph-url");

        var pid = jQuery(this).attr("data-pid");

        if(window.phe.ph_post_fly == 'two'){
            ph_page_title = jQuery('.title',this).html();
            jQuery('#myModalLabeltitle').html(ph_page_title);
            ph_video_url = jQuery('.ph_video_url', this).data('video-url');
            ph_video_type = jQuery('.ph_video_url',this).data('video-type');
            pluginhunt_load_video(pid);
            phcomments(e);
        }else if(jQuery('.woocommerce').length > 0){
            var ph_url = jQuery(this).data('ph-url');
            window.location.replace(ph_url);
        }else if(window.phe.ph_post_fly == 'four'){
            var ph_url = jQuery(this).data('ph-url');
            window.location.replace(ph_url);
        }else{
            console.log('launching modal');
            jQuery(".drop").remove();
            jQuery("html").addClass("noscroll");
            jQuery(".modal-container").html("");
            jQuery("body").addClass("showing-discussion");
            jQuery(".modal-overlay").show();
            jQuery(".show-post-modal").show();
            jQuery(".modal-container").show();
            jQuery(".modal-loading").show();
            jQuery(".show-post-modal").removeClass("hide");
            jQuery(".modal--close").show();
            ph_page_title = jQuery('.title',this).html();
            phflash(e);
            jQuery(".ph_popover").hide();
        }
    });

    jQuery(".subscribeButton").unbind("click").bind("click", function(e) {
        e.preventDefault();
        var t = jQuery("#mce-EMAIL").val();
        jQuery(".subscribeButton").attr("value", "submitting....");
        var i = {
            action: "ph_mailchimpsubscribe",
            email: t,
            checksum: HuntAjax.ajax_nonce
        };
        a = jQuery.ajax({
            url: HuntAjax.ajaxurl,
            type: "POST",
            data: i,
            dataType: "json"
        }), a.done(function(e) {
            jQuery(".subscribeButton").attr("value", "Submit"), -1 == e && alert("Unable to subscribe at this time"), 0 == e.errors ? ph_mailchimpsuccess(phe.ph_mc_msg) : e.errors.toLowerCase().indexOf("already a list member") >= 0 ? ph_mailchimpsuccess("User already subscribed under this email") : alert("Unable to subscribe user at this time");
        }), a.fail(function() {
            alert("failed")
        })
    })
}


function pluginhunt_load_video(pid){

    //change it to just get the actual post ID...
    iframe = jQuery('.embed-content-url-' + pid).html();

    jQuery('.ph-video-modal').html(iframe);
}

function ph_mailchimpsuccess(e) {
    jQuery(".newsletter-box").empty().html('<div class="message">' + e + "</div>").fadeOut(4e3), jQuery(".postlist").css("margin-top", "50px")
}

function phTheme_bindPopBar(e, t, o, a, n) {
    jQuery("body").addClass("showing-discussion"), jQuery(".show-post-modal").show(), jQuery(".modal-container").show(), jQuery(".post-url").html(e.title), jQuery("#ph_red_title").html(e.title), jQuery("#ph_red_title_flag").html(e.title), s = HuntAjax.epichome + "/posts/" + o, updateTwitterValues(s, e.title), updateFacebookValues(s), jQuery(".drop").remove(), jQuery(".email-drop").each(function() {
        "undefined" != typeof window.emailDrop && "function" == typeof window.emailDrop.destroy && window.emailDrop.destroy(), $example = jQuery(this), $target = $example.find(".drop-target"), window.emailDrop = new Drop({
            target: $target[0],
            content: $example.find(".ph-content").html(),
            position: "bottom center",
            openOn: "click",
            classes: "drop-theme-arrows-bounce"
        })
    }), jQuery(".flag-drop").each(function() {
        "undefined" != typeof window.flagDrop && "function" == typeof window.flagDrop.destroy && window.flagDrop.destroy(), $example = jQuery(this), $target = $example.find(".drop-target"), window.flagDrop = new Drop({
            target: $target[0],
            content: $example.find(".ph-content").html(),
            position: "bottom center",
            openOn: "click",
            classes: "drop-theme-arrows-bounce"
        })
    }), jQuery(".profile-drop").each(function() {
        "undefined" != typeof window.currentDrop && "function" == typeof window.currentDrop.destroy && window.currentDrop.destroy(), $example = jQuery(this), $target = $example.find(".drop-target"), window.currentDrop = new Drop({
            target: $target[0],
            content: $example.find(".ph-content").html(),
            position: "bottom center",
            openOn: "hover",
            classes: "drop-theme-arrows-bounce"
        })
    }), jQuery(".can-comment").html(e.commentshtml);
    var r = "";
    for (i = 0; i < e.comments.length; i++) r += "<hr class='comments-rule'><div class='comment' data-comment-id=" + e.comments[i].id + "><div class='comment-body'><h2 class='comment-user-name'><a href=''>" + e.comments[i].author + "</a></h2><div class='maker'></div><div class='user-image-container'><a class='user-image-container' href='#'><img src='" + e.comments[i].ava + "'/></a></div><div class='comment-user-info'></div><div class='actual-comment'>" + e.comments[i].content + "</div></div></div>";
    if (jQuery(".comments").html(r), jQuery(".comnum").html(e.comments.length), jQuery(".post-tagline").html(e.content), null == e.upvotes && jQuery(".upvotes-modal").hide(), jQuery(".scoremodal").html(a), jQuery(".arrow-modal").attr("class", function(e, t) {
            return t.replace(/\barrow-up\S+/g, "")
        }), jQuery(".scoremodal").attr("class", function(e, t) {
            return t.replace(/\bscore-\S+/g, "")
        }), jQuery(".arrow-modal").addClass("arrow-up-" + t), jQuery(".scoremodal").addClass("score-" + t), vp = jQuery(".vp").html(), null != e.upvotes) {
        var l = "";
        for (i = 0; i < e.upvotes.length; i++) l += '<div class="who-by-v votes-inner"><a class="drop-target drop-theme-arrows-bounce"><img class="img-rounded flash-ava" src="' + e.upvotes[i].ava + '"/></a><div class="ph-content pop-ava-v"><img id="modal-img" src="' + e.upvotes[i].ava + '"/><div class="user-info"><span class="user-name">' + e.upvotes[i].user + '</span><div class="view-profile"><div class="btn btn-success primary ph_vp"><a href="' + e.upvotes[i].hr + '">' + vp + "</a></div></div></div></div></div>"
    }
    jQuery(".user-votes").html(l), jQuery(".modal-loading").hide(), jQuery(".modal-container").show(), jQuery(".new-post-modal-close").show(), jQuery(".show-post-modal").removeClass("hide"), jQuery(".new-post-modal").removeClass("hide"), jQuery(".new-post-modal-close").removeClass("hide"), jQuery(".drop-content img#modal-img.poster-ava").attr("src", n), prof = jQuery(".profile-drop").html(), jQuery(".drop-content img#modal-img.poster-ava").html(prof)
}

function validateURL(e) {
    var t = new RegExp("^(http|https|ftp)://([a-zA-Z0-9.-]+(:[a-zA-Z0-9.&%jQuery-]+)*@)*((25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]).(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0).(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0).(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])|([a-zA-Z0-9-]+.)*[a-zA-Z0-9-]+.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(:[0-9]+)*(/(jQuery|[a-zA-Z0-9.,?'\\+&%jQuery#=~_-]+))*jQuery");
    return t.test(e)
}

function phf() {
    jQuery("#phf").slideDown("slow")
}

function hasScrolled() {
    jQuery(this).scrollTop()
}

function start() {
}
jQuery(document).mouseup(function(e) {
    var t = jQuery(".ph_popover_media");
    t.is(e.target) || 0 !== t.has(e.target).length || (t.hide(), jQuery(".show-post-modal").removeClass("noscroll"))
}), window.phproduct = "post";

jQuery(document).mouseup(function(e) {
    var t = jQuery(".ph_popover");
    t.is(e.target) || 0 !== t.has(e.target).length || t.hide()
});
jQuery(document).ready(function(e) {
    jQuery(".image-embed .video").fitVids();
    function t() {
        var t = null,
            i = wp.media.controller.Library.extend({
                defaults: _.defaults({
                    id: "insert-image",
                    title: "Upload media",
                    allowLocalEdits: !1,
                    displaySettings: !1,
                    displayUserSettings: !1,
                    multiple: !1,
                    type: "image"
                }, wp.media.controller.Library.prototype.defaults)
            });
        cid = e(".collection-detail--header--background-uploader").data("pid");
        var t = wp.media({
            button: {
                text: "Select"
            },
            state: "insert-image",
            states: [new i]
        });
        wp.media.model.settings.post.id = cid, t.on("insert", function() {
            t.close()
        }), t.on("close", function() {
            var e = t.state("insert-image").get("selection");
            json = t.state().get("selection").first().toJSON();
            var i = {
                    action: "ph_update_collection_bgimg",
                    aid: json.id,
                    cid: cid
                },
                o = jQuery.ajax({
                    url: HuntAjax.ajaxurl,
                    type: "POST",
                    data: i,
                    dataType: "json"
                });
            o.done(function(e) {
                jQuery(".collection-detail--header").css("background-image", "url(" + json.url + ")")
            }), o.fail(function() {}), !e.length
        }), t.open()
    }

    function i() {
        var e = null,
            t = wp.media.controller.Library.extend({
                defaults: _.defaults({
                    id: "insert-image",
                    title: "Upload media",
                    allowLocalEdits: !1,
                    displaySettings: !1,
                    displayUserSettings: !1,
                    multiple: !1,
                    type: "image"
                }, wp.media.controller.Library.prototype.defaults)
            }),
            e = wp.media({
                button: {
                    text: "Select"
                },
                state: "insert-image",
                states: [new t]
            });
        e.on("insert", function() {
            e.close()
        }), e.on("close", function() {
            var t, i = e.state("insert-image").get("selection");
            json = e.state().get("selection").first().toJSON(), jQuery(".media-items").append('<div class="media-parent"><div class="media-item" style="background-image:url(' + json.url + ');" data-aid=""><a class="remove-media" href="#" data-aid="' + json.id + '"><i class="fa fa-times"></i></a></div></div>'), t = {}, t.url = json.url, t.source = "med", pluginHuntTheme_Global.imgArray.push(t), !i.length
        }), e.open()
    }
    jQuery(".collection-detail--header--background-uploader").unbind("click").bind("click", function(e) {
        t()
    }), jQuery(".trigger-upload").unbind("click").bind("click", function(e) {
        i()
    })
})

jQuery(document).keyup(function(e) {
    27 == e.keyCode && (jQuery(".modal-overlay-lightbox").hide(), jQuery(".show-post-modal").hide(), jQuery("body").removeClass("noscroll"), jQuery(".new-post-modal").hide(), jQuery(".modal-overlay").hide(), jQuery(".modal--close").hide(), jQuery("body").removeClass("showing-discussion"), jQuery(".new-post-modal-close").hide(), jQuery(".modal-container").hide(), jQuery(".drop").remove())
});

jQuery(window).scroll(function() {
    if(jQuery('body').hasClass('home') || jQuery('body').hasClass('blog')){
        jQuery(window).scrollTop() + jQuery(window).height() >= jQuery(document).height() && 0 == pluginHuntTheme_Global.epicload && 0 == jQuery("#epic_page_end_2").length && (epic_infinite_scroll(), pluginhuntbind(), console.log('loaded in more on home..'));
    }
    if(jQuery('body').hasClass('category')){
        console.log('scrolling on category');
        jQuery(window).scrollTop() + jQuery(window).height() >= jQuery(document).height() && 0 == pluginHuntTheme_Global.epicload && 0 == jQuery("#epic_page_end_2").length && (epic_infinite_scroll_cat(), pluginhuntbind(), console.log('loaded in more on category..'));     
    }
});

jQuery(window).scroll(function() {
    if(jQuery('body').hasClass('ph_mobile')){
        if(jQuery('body').hasClass('home') || jQuery('body').hasClass('blog')){
            jQuery(window).scrollTop() == jQuery(document).height() - jQuery(window).height() && 0 == pluginHuntTheme_Global.epicload && 0 == jQuery("#epic_page_end_2").length && (epic_infinite_scroll(), pluginhuntbind())
        }
        if(jQuery('body').hasClass('category')){
            jQuery(window).scrollTop() == jQuery(document).height() - jQuery(window).height() && 0 == pluginHuntTheme_Global.epicload && 0 == jQuery("#epic_page_end_2").length && (epic_infinite_scroll_cat(), pluginhuntbind(), console.log('I am firing...'))
        }
    }
});

 "loading" != document.readyState ? (start(), jQuery("[id]").each(function() {
    var e = jQuery('[id="' + this.id + '"]');
    e.length > 1 && e[0] == this
})) : document.addEventListener("DOMContentLoaded", start), jQuery(document).ready(function() {
    jQuery(document).on("click", ".new-post-modal-close", function() {
        jQuery(".show-post-modal").hide(), jQuery(".modal-container").hide(), jQuery(".show-post-modal, .new-post-modal-close").addClass("hide"), jQuery(".new-post-modal").hide(), jQuery("body").removeClass("showing-discussion"), jQuery(".new-post-modal-close").hide()
    }), jQuery(document).on("click", ".email-post-ph .ph_cancel", function() {
        window.emailDrop.close()
    }), jQuery(document).on("click", ".show-post-modal", function(e) {
        e.stopPropagation()
    }), jQuery(document).on("click", ".flag-post-ph .ph_cancel", function() {
        window.flagDrop.close()
    }), jQuery(document).on("click", ".ph_vp_email", function() {
        if (0 == HuntAjax.logged) return jQuery("#ph-log-social-new").click(), !1;
        var e = jQuery(".drop-content input#ph_email").val(),
            t = jQuery(this).data("id"),
            i = jQuery(this).data("perma");
        if (!phvalidateEmail(e)) return jQuery(".alert-ph").show(), !1;
        if (jQuery(".alert-ph").hide(), "undefined" == typeof o) var t = jQuery(this).data("id"),
            o = t;
        else var t = o;
        if ("undefined" == typeof a) var i = jQuery(this).data("perma"),
            a = i;
        else var i = a;
        var n = {
                action: "epicred_ajax_mail",
                mail: e,
                post: t,
                perma: i
            },
            r = jQuery.ajax({
                url: EpicAjax.ajaxurl,
                type: "POST",
                data: n,
                dataType: "json"
            });
        r.done(function(e) {
            jQuery(".ph-email").addClass("sent"), jQuery(".ph-email").html(jQuery(".email-success").html())
        }), r.fail(function(e, t) {
            alert("Request failed: " + t)
        })
    }), jQuery(document).on("click", ".email-drop", function() {
        emsg = jQuery(".email-success").html(), jQuery(".ph-email").is(".sent") ? (jQuery(".ph-email").html(econtent), jQuery(".ph-email").removeClass("sent")) : econtent = jQuery(".ph-email").html()
    }), jQuery(document).on("click", ".ph_vp_flag", function() {
        if (0 == HuntAjax.logged) return jQuery("#ph-log-social-new").click(), !1;
        var e = jQuery(".drop-content textarea#body-flag-ph").val(),
            e = phhtmlEncode(e);
        if ("undefined" == typeof i) var t = jQuery(this).data("id"),
            i = t;
        else var t = i;
        if ("undefined" == typeof a) var o = jQuery(this).data("perma"),
            a = o;
        else var o = a;
        var n = {
                action: "epicred_ajax_flag",
                mail: e,
                post: t,
                perma: o
            },
            r = jQuery.ajax({
                url: EpicAjax.ajaxurl,
                type: "POST",
                data: n,
                dataType: "json"
            });
        r.done(function(e) {
            msg = jQuery(".ph-flag-done").html(), jQuery(".ph-flag").html(msg)
        }), r.fail(function(e, t) {
            alert("Request failed: " + t)
        })
    }), jQuery(".post-meta-flash").show(), jQuery(".modal-loading").hide(), jQuery(".modal-container").show(), again = 30 - window.ehacklast, window.setInterval(function() {
        again = 1, jQuery("#again").html(again), 1 == again && jQuery(".toosoon2").fadeOut(1e3)
    }, 1e3), 



    setTimeout(function() {
        phf()
    }, 2e3), jQuery(".icon-x").click(function() {
        jQuery("#phf").slideUp("slow")
    }), showing = !1, pluginhuntbind()
});
var didScroll, lastScrollTop = 0,
    delta = 5,
    navbarHeight = jQuery(".site--header-d").outerHeight();
jQuery(window).scroll(function() {
    didScroll = !0
}), setInterval(function() {
    didScroll && (hasScrolled(), didScroll = !1)
}, 250);