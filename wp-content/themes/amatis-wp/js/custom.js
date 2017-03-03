jQuery(document).ready(function( $ ) {
	
	$("img.size-shop_catalog").each(function(){
		var $this = $(this);
		if ($this.height() > 280) {
        	$this.addClass("tall");
    	}
	});
	
});

