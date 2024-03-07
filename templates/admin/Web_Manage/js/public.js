// JavaScript Document
var public_ckecitor_chk=true;

function openBox(links,width,height,transition,close_refresh,fixed){

	width = width || '95%';
	height = height || '95%';
	transition = transition || 'fade';//fade/none
	close_refresh = close_refresh || false;//false/function(是否關掉重整頁面?)
    fixed = fixed || true;//控制光箱是否隨滑鼠滾動(true為fixed在中間)

    if(typeof(close_refresh)=='function'){closefun = close_refresh;}
    else{closefun =function(){};}
	$.colorbox({
        href:links,
        iframe:true,
        innerWidth: width,
        innerHeight: height,
        scrolling: !isnicescroll,
        transition: transition,
        initialWidth:width,
        initialHeight: height,
        speed:200,
        fixed:fixed,
        onClosed: closefun,
        maxWidth: (window.innerWidth * 0.95 / window.ZOOM_IN + 'px'),
        maxHeight: (window.innerHeight * 0.95 / window.ZOOM_IN + 'px'),
        onComplete: function () {
            $('#colorbox').css({
                left: '50%',
                top: '50%',
                transform: 'translateX(-50%) translateY(-50%)',
            });
        }
    });

}

function openBox2(configs) {
    $.colorbox({
        ...configs,
        maxWidth: (self.innerWidth * 0.95 / window.ZOOM_IN + 'px'),
        maxHeight: (self.innerHeight * 0.95 / window.ZOOM_IN + 'px'),
        speed: 100,
        onComplete: function () {
            $('#colorbox').css({
                left: '50%',
                top: '50%',
                transform: 'translateX(-50%) translateY(-50%)',
            });
        }
    });
}

function openPicBox(configs) {
    $.colorbox({
        href: configs['href'] || '',
        transition: configs['transition'] || 'fade', // fade/none,
        initialWidth: configs['initialWidth'] || '0px',
        initialHeight: configs['initialHeight'] || '0px',
        maxWidth: '600px',
        maxHeight: '600px',
        speed: 100,
        onOpen: () => {
            setTimeout(() => {
                $('#colorbox').css({
                    left: '50%',
                    top: '50%',
                    transform: 'translateX(-50%) translateY(-50%)',
                });
            }, 150);
        }
    });
}

function closeBox(){
	$.colorbox.close();
}

//驗證統一編號
function CheckCompanyNo(idvalue) {
    return true;//先不驗證
    var tmp = new String("12121241");
    var sum = 0;
    re = /^\d{8}$/;
    if (!re.test(idvalue)) {
       return false;
    }
    for (i=0; i< 8; i++) {
        s1 = parseInt(idvalue.substr(i,1));
        s2 = parseInt(tmp.substr(i,1));
        sum += cal(s1*s2);
    }
    if (!valid(sum)) {
        if (idvalue.substr(6,1)=="7") return(valid(sum+1));
    }
    return(valid(sum));

    function valid(n) {
        return (n%10 == 0)?true:false;
    }
    function cal(n) {
        var sum=0;
        while (n!=0) {
            sum += (n % 10);
            n = (n - n%10) / 10;  // 取整數
        }
        return sum;
    }
}

function getTimeStamp(){
   var dt=new Date();
   return dt.getTime();
}

function resizeNicescroll()  {
    if (isnicescroll==true){
        setTimeout(function(){$("html").getNiceScroll().resize();},300);
    }
}

function ckeditorScrollFix(){

	CKEDITOR.on('instanceCreated', function(ev) {

    	ev.editor.on('resize',function(reEvent){
         	 resizeNicescroll();

     	});
 	});
	//
}
function pathToFile(str)
{
    var nOffset = Math.max(0, Math.max(str.lastIndexOf('\\'), str.lastIndexOf('/')));
    var eOffset = str.lastIndexOf('.');
    if(eOffset < 0)
    {
        eOffset = str.length;
    }
    return {isDirectory: eOffset == str.length,
            path: str.substring(0, nOffset),
            name: str.substring(nOffset > 0 ? nOffset + 1 : nOffset, eOffset),
            extension: str.substring(eOffset > 0 ? eOffset + 1 : eOffset, str.length)};
}
function chkCkeditorScrollEvent(){
	 if(public_ckecitor_chk==true){
		 resizeNicescroll();
		 public_ckecitor_chk=false;
		 setTimeout(function(){public_ckecitor_chk=true;},1000);
	 }
}

function selectAll(nametag,selected){
	$('body').find('input[name="'+nametag+'[]"]').prop('checked', selected);
}

function dateValidationCheck(str){
    var re = new RegExp("^([0-9]{4})[.-]{1}([0-9]{1,2})[.-]{1}([0-9]{1,2})$");
    var strDataValue;
    var infoValidation = true;

    if ((strDataValue = re.exec(str)) != null){
        var i;
        i = parseFloat(strDataValue[1]);
        if (i <= 0 || i > 9999){ // 年
            infoValidation = false;
        }
        i = parseFloat(strDataValue[2]);
        if (i <= 0 || i > 12){ // 月
            infoValidation = false;
        }
        i = parseFloat(strDataValue[3]);
        if (i <= 0 || i > 31){ // 日
            infoValidation = false;
        }
    }else{
        infoValidation = false;
    }
    return infoValidation;
}

function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}


function showNotice(type,title,text){
	var image="";
	switch (type){
		case "alert":
			image='../images/gitter/alert.png';
		break;
		case "ok" :
			image='../images/gitter/ok.png';
		break;
		default:
			image='../images/gitter/could.png';
		break;
	}
	$.gritter.add({
		title: title,
		text: text,
		image: image,
		sticky: false,
		time: ''
	});
}

var CODER = CODER || {};
CODER.namespace = function() {
    var a=arguments, o=null, i, j, d;
    for (i=0; i < a.length; i=i+1) {
        d=a[i].split(".");
        o=CODER;
        for (j=(d[0] == "CODER") ? 1 : 0; j < d.length; j=j+1) {
            o[d[j]]=o[d[j]] || {};
            o=o[d[j]];
        }
    }
    return o;
};
CODER.namespace('CODER.Util');

function WeightColumnSum(sum_class,toval_id){
    $(sum_class).blur(function () {
        var row_total = 0;
        $(sum_class).each(function (index, el) {
            var float_val = parseFloat($(this).val());
            if (!isNaN(float_val)) {
                row_total = accAdd(row_total, float_val);
            }
        });

        if (row_total != 0) {
            $(toval_id).val(row_total);
        }
    });
    $(sum_class).first().trigger('blur');
}

/* Jessica 20160520 動態欄位"上傳圖片"必填判斷  如果name重複 就抓id ★id 不可重複*/
$(function () {
    if ($.validator) {
        //fix: when several input elements shares the same name, but has different id-ies....
        $.validator.prototype.elements = function () {
            var validator = this,
                rulesCache = {};


            // select all valid inputs inside the form (no submit or reset buttons)
            // workaround $Query([]).add until http://dev.jquery.com/ticket/2114 is solved
            return $([]).add(this.currentForm.elements)
                .filter(":input")
                .not(":submit, :reset, :image, [disabled]")
                .not(this.settings.ignore)
                .filter(function () {
                    var elementIdentification = this.id || this.name;

                    !elementIdentification && validator.settings.debug && window.console && console.error("%o has no id nor name assigned", this);

                    // select only the first element for each name, and only those with rules specified
                    if (elementIdentification in rulesCache || !validator.objectLength($(this).rules()))
                        return false;


                    rulesCache[elementIdentification] = true;
                    return true;

                });

        };

    }

});
