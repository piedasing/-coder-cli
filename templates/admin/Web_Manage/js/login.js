$(document).ready(function () {
    if (jQuery().validate) {
        var removeSuccessClass = function (e) {
            $(e).closest(".form-group").removeClass("has-success");
        };
        $("#myform").validate({
            errorElement: "span", //default input error message container
            errorClass: "help-block", // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                username: {
                    required: true,
                    minlength: 3,
                },
                password: {
                    required: true,
                    minlength: 6,
                },
                code: {
                    required: true,
                    minlength: 5,
                    maxlength: 5,
                },
            },
            messages: {
                username: {
                    required: "請輸入登入帳號",
                },
                password: {
                    required: "請輸入登入密碼",
                },
                code: {
                    required: "請輸入右圖數字",
                },
            },
            invalidHandler: function (event, validator) {
                //display error alert on form submit
            },

            highlight: function (element) {
                // hightlight error inputs
                $(element)
                    .closest(".form-group")
                    .removeClass("has-success")
                    .addClass("has-error"); // set error class to the control group
            },

            unhighlight: function (element) {
                // revert the change dony by hightlight
                $(element).closest(".form-group").removeClass("has-error"); // set error class to the control group
                setTimeout(function () {
                    removeSuccessClass(element);
                }, 3000);
            },

            success: function (label) {
                label
                    .closest(".form-group")
                    .removeClass("has-error")
                    .addClass("has-success"); // set success class to the control group
            },
        });
    }
    $("#formbtn").click(function () {
        var $this = $(this);
        var $form = $("#myform");
        var $alert = $form.find("#alertdiv");
        $alert
            .removeClass("alert-danger")
            .addClass("alert-info")
            .html("<strong>驗證登入資訊中...</strong>請稍候");
        //if ($form.valid()){
        $("#formcontent").hide();
        startFadeInOut($alert);
        $.ajax({
            url: "api/login",
            cache: false,
            type: "POST",
            data: {
                username: $("#username").val(),
                password: $("#password").val(),
                code: $("#code").val(),
                remember_me: $("#remember_me:checked").val(),
            },
            dataType: "json",
            success: function (data) {
                setTimeout(function () {
                    vaildResult($alert, data, "formcontent");
                }, 1000);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                var data = new Array();
                data["result"] = false;
                data["msg"] = "讀取資料時發生錯誤,請梢候再試" + thrownError;
                vaildResult($alert, data, "formcontent");
            },
        });

        //}
    });

    function vaildResult(obj, data, formid, targethref, targettext) {
        targethref = targethref || "home/index.php";
        targettext =
            targettext || "<strong> 驗證完成! </strong> 準備進入系統..";
        stopFadeInOut(obj);
        obj.removeClass("alert-info");
        if (data["result"] == true) {
            obj.addClass("alert-success");
            obj.html(targettext);
            setTimeout(function () {
                $("body").fadeOut(function () {
                    location.href = targethref;
                });
            }, 1000);
        } else {
            obj.addClass("alert-danger");
            obj.html("<strong> 驗證失敗! </strong>" + data["msg"]);
            $("#" + formid).show();
            $("#codeimg").click();
            $("#code").val("");
        }
    }
    function goToForm(form) {
        $(".login-wrapper > form:visible").fadeOut(500, function () {
            $("#" + form).fadeIn(500);
        });
    }
    $(function () {
        $(".goto-login").click(function () {
            goToForm("myform");
        });
    });
});
