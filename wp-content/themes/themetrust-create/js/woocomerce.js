///////////////////////////////
// Cart and Search Buttons
///////////////////////////////

function hidingDivs(){
    var search_dummy = jQuery("#hide-a-bar").val();

    jQuery("#hide-a-bar").keydown(function() {
        if (this.value == search_dummy) {
            this.value = '';
            mainSpace       = headerWidth - ( logoWidth + secondaryWidth );

            if( mainSpace < mainWidth ) {
                jQuery('.site-header').addClass("long-menu");
            } else if( mainSpace > mainWidth ) {
                jQuery('.site-header').removeClass("long-menu");
            }
        }
    }).blur(function() {

    });

    // Search Button
    jQuery("a#search-btn").on( "click", function( event ){
        event.preventDefault();
        jQuery("#search-bar").slideToggle();
        jQuery("#header-cart").slideUp();
        jQuery("#hide-a-bar").focus().get(0).setSelectionRange(0,0);
        jQuery(this).toggleClass('active');
        if( jQuery('a#cart-btn').hasClass('active') )
            jQuery('a#cart-btn').removeClass('active');
    });

    // Cart Button
    jQuery("a#cart-btn").on( "click", function( event ){
        event.preventDefault();
        jQuery("#search-bar").slideUp();
        jQuery("#header-cart").slideToggle();
        jQuery(this).toggleClass('active');
        if( jQuery('a#search-btn').hasClass('active') )
            jQuery('a#search-btn').removeClass('active');
    });

    // Cart Notices
    if( jQuery('#cart-notices').length > 0 ){
        var timeOut;

        timeOut = setTimeout(function () {
                jQuery('#cart-notices').slideUp();
            }, 5000);

        jQuery('#cart-notices').mouseover( function () {
            clearTimeout(timeOut);
            jQuery(this).on( 'mouseout', function(){
                setTimeout(function () {
                    jQuery('#cart-notices').slideUp();
                }, 1000);
            });
        });

    }
}