function GetURLParameter(sParam){

	var sPageURL = window.location.search.substring(1);
	var sURLVariables = sPageURL.split('&');

	for (var i = 0; i < sURLVariables.length; i++)
	{
		var sParameterName = sURLVariables[i].split('=');
		if (sParameterName[0] == sParam)
		{
			return sParameterName[1];
		}
	}
}

function scrollToId(theId){
	jQuery('html,body').animate({scrollTop: jQuery('#' + theId).offset().top - 80} , 1200);
	jQuery('#' + theId).delay(1200).effect("pulsate",300);
}
