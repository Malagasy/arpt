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
	if( jQuery("#contact_form").length ){
		jQuery(document).on("submit","#contact_form",function(e){
			var _email = jQuery("#contact_email").val();
			var _sujet = jQuery("#contact_sujet").val();
			var _message = jQuery("#contact_message").val();

			if( !is_email( _email ) ){
				if( !jQuery("#contact_email").parent().parent().hasClass("has-warning") ){
					jQuery("#contact_email").parent().parent().addClass("has-warning");
				}

			}else{
				if( jQuery("#contact_email").parent().parent().hasClass("has-warning") ){
					jQuery("#contact_email").parent().parent().removeClass("has-warning");
				}
			}

			if( _sujet == "" ){
				if( !jQuery("#contact_sujet").parent().parent().hasClass("has-warning") ){
					jQuery("#contact_sujet").parent().parent().addClass("has-warning");
				}

			}else{
				if( jQuery("#contact_sujet").parent().parent().hasClass("has-warning") ){
					jQuery("#contact_sujet").parent().parent().removeClass("has-warning");
				}
			}

			if( _message == "" ){
				if( !jQuery("#contact_message").parent().parent().hasClass("has-warning") ){
					jQuery("#contact_message").parent().parent().addClass("has-warning");
				}

			}else{
				if( jQuery("#contact_message").parent().parent().hasClass("has-warning") ){
					jQuery("#contact_message").parent().parent().removeClass("has-warning");
				}
			}

			if( jQuery("#contact_form .has-warning").length ){
				e.preventDefault();
			}else{
				e.preventDefault();
				jQuery("#contact_form .btn-submit").parent().html('<span class="btn-submit">Envoi en cours..</span>');
				jQuery.ajax({
					url:"ajax.php", 
					type: "POST",
					data:{
						action: "contact_form_email",
						param1: _email,
						param2: _sujet,
						param3: _message
					},
					success: function(data){
						if( data == "1" ){
							jQuery("#contact_email").parent().parent().addClass("has-success");
							jQuery("#contact_sujet").parent().parent().addClass("has-success");
							jQuery("#contact_message").parent().parent().addClass("has-success");
							jQuery("#contact_form .btn-submit").parent().html('Le message a bien été envoyé ! :)');
						}else{
							jQuery("#contact_form .btn-submit").parent().html('<button type="cancel" class="btn btn-default btn-cancel">Annuler</button><button type="submit" class="btn btn-primary btn-submit">Réessayer</button>');
						}
					}
				});
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

