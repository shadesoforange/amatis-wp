
(function($){
	var CreateSidebar = function(){

		this.widget_wrap = $('.widget-liquid-right');
		this.widget_area = $('#widgets-right');
		this.widget_add  = $('#tt-add-widget');

		this.create_form();
		this.add_del_button();
		this.bind_events();

	};
	
	CreateSidebar.prototype = {

		create_form: function(){
			this.widget_wrap.append(this.widget_add.html());
			this.widget_name = this.widget_wrap.find('input[name="tt-sidebar-widgets"]');
			this.nonce = this.widget_wrap.find('input[name="tt-delete-sidebar"]').val();   
		},

		add_del_button: function(){
			this.widget_area.find('.sidebar-tt-custom').append('<i class="fa fa-times tt-delete-button"></i>');
		},

		bind_events: function(){
			this.widget_wrap.on('click', '.tt-delete-button', $.proxy( this.delete_sidebar, this));
		},

		delete_sidebar: function(e){
			var widget = $(e.currentTarget).parents('.widgets-holder-wrap:eq(0)'),
			title = widget.find('.sidebar-name h3'),
			spinner = title.find('.spinner'),
			widget_name = $.trim(title.text()),
			obj = this;

			$.ajax({
				type: "POST",
				url: window.ajaxurl,
				data: {
					action: 'tt_ajax_delete_custom_sidebar',
					name: widget_name,
					_wpnonce: obj.nonce
				},

				beforeSend: function(){
					spinner.addClass('activate_spinner');
				},
				success: function(response){     
					if(response == 'sidebar-deleted'){
						widget.slideUp(200, function(){

						$('.widget-control-remove', widget).trigger('click'); //delete all widgets inside
						widget.remove();

						// obj.widget_area.find('.widgets-holder-wrap .widgets-sortables').each(function(i) //re calculate widget ids
						// {
								// $(this).attr('id','sidebar-' + (i + 1));
						// });

						wpWidgets.saveOrder();

						});
					}
				}
			});
		}

	};
	
	$(function()
	{
		new CreateSidebar();
 	});
	
})(jQuery);