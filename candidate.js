jQuery(document).ready(function(){ 
	var searchReults = {
	vars : {
		self : this,
		widgetDiv: jQuery('.candidate-module'),
	},
	init : function() {
		var self = this,
			vars = self.vars,
			widgetDiv = vars.widgetDiv,
			headers = widgetDiv.find('.header');
			headers.click(function() {
				jQuery(this).next().slideToggle();
				var currentSrc = jQuery(this).find('.arrowDown').attr("src");
				if (currentSrc == 'arrow_collapse.gif') {
					jQuery(this).find('.arrowDown').attr("src", 'http://ben.balter.com/sandbox/hacksandhackers/wp-content/plugins/campaign-viz/arrow_down.gif');
				} else {
					jQuery(this).find('.arrowDown').attr("src", 'http://ben.balter.com/sandbox/hacksandhackers/wp-content/plugins/campaign-viz/arrow_collapse.gif');
				}
			});

			//hiddenBullets.hide();
		}
}
    searchReults.init();
}); 