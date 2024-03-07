(function($) {
$.fn.coderselectBoxManipulation = function(settings) {
    var _defaultSettings = {
    	lv:0,//層級
    	cid:null,//關聯子元素id ary
    	dataary:null,//數據 ary
    	valary:'',//預設值
		debug:true,
		required:false,
		preset:false,
		presettext:'請選擇',
    };
    var _settings = $.extend(_defaultSettings, settings);
	return this.each(function() {
		/*
		var $hidden=null;*/
		var _this=$(this),
		    data =_settings.dataary,
		    cid = _settings.cid,
		    valary = _settings.valary,
		    preset =_settings.preset,
		    presettext = _settings.presettext;
		function init(){
			//console.log(_settings.cid);
			//console.log(_settings.dataary);
			if(_settings.lv===0){
				//console.log(data);
				var thisdata = new Object();
				//if(preset!=false){thisdata[0]=presettext;}
				for(var key in data){
					//console.log(data[key]['pid']);
					if(data[key]['pid']==='0'){
						//thisdata[key][data[key]['value']]=data[key]['name'];
						thisdata[data[key]['value']]=data[key]['name'];
				    }
				}
				_this.removeOption(/./);
				if(preset!=false){
					_this.append("<option value=''>"+presettext+"</option>");
				}
				_this.addOption(thisdata,false);
			}

			_this.change(function(event) {
				var thisdata = new Object();
				for(var key in data){
					if(data[key]['pid']===_this.val() && data[key]['pid']!=='0'){
						//thisdata[key][data[key]['value']]=data[key]['name'];
						thisdata[data[key]['value']]=data[key]['name'];
				    }
				}
				if(cid.length>0){
					for(var i=0,num=cid.length;i<num;i++){
						if(i=='0'){
							$("#"+cid[i]).removeOption(/./);
							if(preset!=false){
							 	$("#"+cid[i]).html("<option value=''>"+presettext+"</option>");
							}
							$("#"+cid[i]).addOption(thisdata,false);
						}else{
							$("#"+cid[i]).removeOption(/./);
						}
				    }
				}
			}).trigger('change');
			if(valary!=''){setval(valary);}
		}

		function setval(valary){//設定預設值
			//console.log(valary);
			_this.val(valary).trigger('change');
		}
		init();
	});
}
})(jQuery);
