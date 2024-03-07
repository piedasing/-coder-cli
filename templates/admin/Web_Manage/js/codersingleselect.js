

CODER.Util.SingleSelect=function(_id,_input_id,_defval,_defname) { 
	var id=_id;
	var input_id=_input_id;
	var defval=_defval;
	var defname=_defname;

	//private
	var title='';
	var src='';
	var viewlink='';
	var required=true;
	var parent=$('#'+id);
	var $input;
	var $view_btn;
	var $search_btn;
	var $hidden;
	return{

		init:function(){
			if(id=='' || input_id==''){
				console.log('id或input_id為必填!');
				return;
			}

			var obj=this;
			var $input_group=$('<div class="input-group"></div>');
			var $input_group_btn=$('<span class="input-group-btn"></span>');
			$view_btn=$('<button class="btn  btn-info" type="button"><i class="icon-info-sign"></i></button>');

			$input_group_btn.append($view_btn);
			$input=$('<input type="text" placeholder="請按右邊按鈕選擇關聯'+this.title+'" class="form-control col-sm-8" readonly>');
			var $input_group_btn2=$('<span class="input-group-btn"></span>');
			$search_btn=$('<button class="btn  btn-primary" type="button"><i class="icon-search"></i></button>');
			$input_group_btn2.append($search_btn);

			$hidden=$('<input type="hidden" id="'+input_id+'" name="'+input_id+'" value="" '+(this.required==true ? 'required' : '' )+'>');
			$input_group.append($input_group_btn).append($input).append($input_group_btn2);
			this.initBtn();
			parent.html('');
			parent.append($input_group).append($hidden);
			if(defval!='' && defname!=''){
				this.setItem(defval,defname);
			}
		}
		,
		initBtn:function(){
			var obj=this;
			$view_btn.click(function(){
				var v=$hidden.val();
				if(v!=''){
					openBox(obj.viewlink.replace('{:val}',v));
				}
			});	
			$search_btn.click(function(){
				openBox(obj.src);
			});					
		},
		setItem:function(val,title){
			$hidden.val(val);
			$input.attr('placeholder',title)
			closeBox();
		}
	}

}
                                                

                                                  

                                                