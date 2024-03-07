
//物件閃礫動畫開始
function startFadeInOut(obj){
	obj.addClass('animatefade');
	fadeInOut(obj);
}

function stopFadeInOut(obj){
	obj.removeClass('animatefade');
}

function fadeInOut(obj){
	if(obj.hasClass('animatefade')){	
		if(obj.css('display')=='none'){
			obj.fadeIn(500,function(){fadeInOut(obj)});
		}
		else{
			obj.fadeOut(500,function(){fadeInOut(obj)});
		}
	}
	else{
		obj.show();
	}
}
//物件閃礫動畫結束