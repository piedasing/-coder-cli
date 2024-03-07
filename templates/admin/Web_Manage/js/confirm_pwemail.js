$( document ).ready(function() {
	if (jQuery().validate) {
		var removeSuccessClass = function(e) {
			$(e).closest('.form-group').removeClass('has-success');
		}
		$('#confirm_pwemail').validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			ignore: "",
			rules: {
				password: {
					required: true,
					minlength: 6
				},
				password_repeat:{
					required:true,
					minlength: 6,
					equalTo:"#password"
				}
			},
			messages: {
				 password: {
					required: "請輸入密碼"
				 },
				 password_repeat: {
					required: "請再輸入一次密碼",
					equalTo:"兩次密碼不相符"
				 },
			},
			invalidHandler: function (event, validator) { //display error alert on form submit

			},

			highlight: function (element) { // hightlight error inputs
				$(element).closest('.form-group').removeClass('has-success').addClass('has-error'); // set error class to the control group
			},

			unhighlight: function (element) { // revert the change dony by hightlight
				$(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
				setTimeout(function(){removeSuccessClass(element);}, 3000);
			},

			success: function (label) {
				label.closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
			}
		});
	}
	$('#pwchange_btn').click(function(){
		var $this=$(this);
		var $form=$('#confirm_pwemail');
		var $alert=$form.find('#alertdiv');
		$alert.removeClass('alert-danger').addClass('alert-info').html('<strong>驗證資訊中...</strong>請稍候');
		//if ($form.valid()){
			$('#formcontent').hide();
			startFadeInOut($alert);
			$.ajax({
			url:'forgetpw_email/confirm_pwemail_do.php',
			cache: false,
			type:"POST",
			data:{coder:$('#coder').val(),uid:$('#uid').val(),password:$('#password').val(),password_repeat:$('#password_repeat').val()},
			dataType:"json",
			success:function(data){
				setTimeout(function(){
				vaildResult($alert,data,'formcontent');
				},1000);
			}
			,error:function(xhr, ajaxOptions, thrownError){
				var data=new Array();
				data['result']=false;
				data['msg']="讀取資料時發生錯誤,請梢候再試"+thrownError;
				vaildResult($alert,data,'formcontent');
			}
			});


		//}

	});
	function vaildResult(obj,data,formid,targethref,targettext){
		targethref = targethref || 'login.php';
		targettext = targettext || '<strong> 修改完成! </strong> 請以新密碼進入進入系統..';
		stopFadeInOut(obj);
		obj.removeClass('alert-info');
		if(data['result']==true){
			obj.addClass('alert-success');
			obj.html(targettext);
			setTimeout(function(){$('body').fadeOut(function(){location.href=targethref;})},2500);
		}
		else{
			obj.addClass('alert-danger');
			obj.html('<strong> 驗證失敗! </strong>'+data['msg']);
			$('#'+formid).show();
		}
	}
});