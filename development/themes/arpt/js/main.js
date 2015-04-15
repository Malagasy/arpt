var scrollWhenPostedComment = function(){
	var posted = GetURLParameter('posted');
	if( posted == 1 ){
		scrollToId('comments');
	}
}

var instancingPlugins = function(){
	hljs.initHighlightingOnLoad();
}

var doTableOfContent = function(){
	var selection = "";
	var key = 0;
	selection += '<div class="panel panel-primary tableOfContent"><div class="panel-heading">Table des matières</div>';
	selection += '<div class="list-group">';
	jQuery(".content h2").each( function(index) {
		key++;
		jQuery(this).attr("id",key);
		selection += '<a href="' + jQuery(location).attr("href") + '" class="tableOfContentLink scrolling list-group-item" data-id="' +  key +'">' + key + '. ' + jQuery(this).text() + "</a>";
	})
	selection += '</div></div>';
	jQuery(".content h1").after(selection);
	if( !jQuery(".tableOfContentLink").length ){
		jQuery(".tableOfContent").remove();
	}
}
var scrolling_action = function(){
	jQuery(document).on("click",".scrolling",function(e){
		e.preventDefault();
		scrollToId( jQuery(this).data("id") );
	});
}
var hightlight_menulink = function(){
	jQuery(".menu-right .last_articles li").on("mouseenter",function(){
 		jQuery(this,'a').animate({ color: "#F9F9F9" }, 1 );	
 	});	
 	jQuery(".menu-right .last_articles li").on("mouseleave",function(){
 		jQuery(this,'a').animate({ color: "#666666" }, 1 );	
 	});	
}

var jumbotronEffect = function(){
	jQuery(document).on("click",".jumbotron .more-info",function(e){
		e.preventDefault();
		jQuery(this).effect("fold",200);
		jQuery(".more-info-next").effect("pulsate",30);
		jQuery(".more-info-buttons").effect("slide",600);
	});

}
var transformToFunctionLink = function(){
	jQuery("p").each( function(e){
		var old = jQuery(this).html();
		var new_ = old.replace(/(\w+)\(\)/g,"<a href='http://arpt.fr/$1/'>$1()</a>");
		jQuery(this).html(new_);
	});

}

var formValidation = function(){
	if( jQuery("#form_contact").length ){
		jQuery("#form_contact").on("submit",function(e){
			var form_is_valid = true;

			if( !is_email( jQuery("#contact_email").val() ) ){
				if( !jQuery("#contact_email").parent().parent().hasClass("has-error") ){
					jQuery("#contact_email").parent().parent().addClass("has-error");
					form_is_valid = false;
				}

			}


			if( !form_is_valid ){
				e.preventDefault();
			}

		})
	}
}
jQuery(document).ready(function(){
	instancingPlugins();
	scrollWhenPostedComment();
	doTableOfContent();
	scrolling_action();
	hightlight_menulink();
	jumbotronEffect();
	transformToFunctionLink();
	formValidation();
});

