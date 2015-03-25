var configuration = function(){
	window.ajaxpage = "ajax.php";
};


var designWithJQuery = function(){
	/* It applies for all legend class="hoverable" */
	jQuery(".admin-body fieldset legend.hoverable").append( ' <span class="caret"></span>' );

};

var displayMoreFieldset = function(){
	jQuery(".admin-body fieldset legend.hoverable").on("click" , function(){
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


function phpajax( action , param1 , param2, param3 , param4 ){

	var result;
	jQuery.ajax({
		url:ajaxpage, 
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
});
