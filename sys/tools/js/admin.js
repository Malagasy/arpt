var configuration = function(){
	window.ajaxpage = "ajax.php";
};


var designWithJQuery = function(){
	/* It applies for all legend class="hoverable" */
	jQuery(".admin-body fieldset legend.hoverable").append( ' <span class="caret"></span>' );

};

var displayMoreFieldset = function(){
	jQuery(".admin-body").on("click" , "fieldset legend.hoverable" , function(){
		if( (jQuery(this).next()).hasClass("hidden") ){
			(jQuery(this).next()).removeClass("hidden").clearQueue();
			(jQuery(this).next()).delay(150).queue(function(){jQuery(this).addClass("in")});
		}else{
			(jQuery(this).next()).removeClass("in").clearQueue();
			(jQuery(this).next()).delay(250).queue(function(){jQuery(this).addClass("hidden")});
		}
	});

};

var nextPreviousMeda = function(){
	jQuery(".previous-media").on("click",function(){
		var previous = jQuery(this).data("previous");
		jQuery(".img-" + previous).click();
	});
	jQuery(".next-media").on("click",function(){
		var next = jQuery(this).data("next");
		jQuery(".img-" + next).click();
	});
};

var activeMediaAction = function(){
	var old_title;
	jQuery("h3.modal-title").on("dblclick", function(e){
		var id = jQuery(this).data("id");
		var title = jQuery(this).html();
		jQuery(this).html('<input style="width:90%"  class="inline-' + id + '" type="text" value="' + title + '" autofocus>');
		old_title = title;

	});

	jQuery(".rename-file").on("click", function(e){
		event.preventDefault();
		var id = jQuery(this).data("title");
		console.log(id);
		var title = jQuery("h3.title-" + id).html();
		jQuery("h3.title-" + id).html('<input style="width:90%" class="inline-' + id + '" type="text" value="' + title + '" autofocus>');
		old_title = title;
	});/* http://stackoverflow.com/questions/1403615/use-jquery-to-hide-a-div-when-the-user-clicks-outside-of-it */
	jQuery("div.modal").mouseup(function (e)
	{
		var id = jQuery(this).data("box");
	    var container = jQuery("div.modal input.inline-"+id);
	    var title = container.val();
	    var parent = jQuery(this).data("parent");

	    if( typeof title != 'undefined' ){
		    if (!container.is(e.target) // if the target of the click isn't the container...
		        && container.has(e.target).length === 0) // ... nor a descendant of the container
		    {
		        jQuery("h3.title-"+id).html( title );
		        phpajax( "js_rename_media" , parent , title , old_title );
		    }
		}
	});

	jQuery(".delete-file").on("click",function(e){
		event.preventDefault();
		var id = jQuery(this).data("title");
		var parent = jQuery(this).data("parent");

		var title = jQuery("h3.title-" + id).html();

		phpajax( "js_delete_media" , parent + title );
		location.reload();
	});

}

simpleConfirmBox = function(){
	jQuery(document).on("click",".confirmbox",function(e){
		e.preventDefault();
		if( confirm('Etes-vous sûr de votre choix ?') ){
			window.location.replace( jQuery(this).attr('href' ) );
		}
	});
}

dragNdroppWidget = function(){

	if( !jQuery(".admin-widgetmenu").length ) return;

	jQuery(".active-widgets .panel-body").sortable({});


	jQuery(".active-widgets .panel-body fieldset").draggable({

		connectToSortable: ".active-widgets .panel-body",
		cursor: "all-scroll",
		opacity: 0.7,		
		stop: function(){
			var idsIn = [];
			jQuery(".active-widgets legend").each( function( i ){
				idsIn[i] = jQuery(this).data("widget-id");
			});
			
			jQuery.ajax({
				url:"ajax.php", 
				type: "POST",
				data:{
					action: "reorganise_widgetmenu",
					param1: JSON.stringify( idsIn ) },
				success: function(data){
					jQuery(".active-widgets .panel-body fieldset").attr("style","");

					if( jQuery(".admin-message.alert-success").is(":visible") )
						jQuery(".admin-message.alert-success").effect("shake" , 300);
					else
						jQuery(".admin-message.alert-success").show("bounce");
				}
			});

		}

	});
}

dragNdroppMenu = function(){

	if( !jQuery(".admin-navmenu").length ) return;

	jQuery(".list-group").sortable({});

	jQuery(".list-group-item").draggable({
		connectToSortable: ".list-group",
		cursor: "all-scroll",
		opacity: 0.7,
		stop: function(){
			var route = jQuery(".list-group").data("currentnavmenu");
			var idsIn = [];
			jQuery(".list-group-item").each( function( i ){
				idsIn[i] = jQuery(this).data("content-id");
			});
			
			jQuery.ajax({
				url:"ajax.php", 
				type: "POST",
				data:{
					action: "reorganise_navmenu",
					param1: route,
					param2: JSON.stringify( idsIn ) },
				success: function(data){
					jQuery(".list-group .list-group-item").attr("style","");

					if( jQuery(".admin-message.alert-success").is(":visible") )
						jQuery(".admin-message.alert-success").effect("shake" , 300);
					else
						jQuery(".admin-message.alert-success").show("bounce");
				}
			});

		}
	});
}


function phpajax( action , param1 , param2, param3 , param4 ){

	var result;
	jQuery.ajax({
		url:"ajax.php", 
		type: "POST",
		async: false,
		data:{
			action: action,
			param1: param1,
			param2: param2,
			param3: param3,
			param4: param4 },
		success: function(data){
			result = data;
		}
	});
	return result;
}

jQuery(document).ready(function(){
	configuration();
	designWithJQuery();
	nextPreviousMeda();
	displayMoreFieldset();
	activeMediaAction();
	simpleConfirmBox();
	dragNdroppWidget();
	dragNdroppMenu();
});
