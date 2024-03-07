(function () {
    //Section 1 : 按鈕功能
    var action = {
        exec: function (editor) {
            CKEditorImageGroup.setName(editor.name);
            $.colorbox({
                inline: true, width: "80%", open: true, href: "#CKEditorImageGroup", scrolling: false,transition:'fade',
                onClosed: function () {
                    $('#CKEditorImageGroup').hide();
                },
                onOpen: function () {
                    $('#CKEditorImageGroup').show();
                }
            });

        }
    },
    //Section 2 : 建立按鈕
    name = 'ImageGroup';
    CKEDITOR.plugins.add(name, {
        init: function (editor) {
            editor.addCommand(name, action);
            editor.ui.addButton('ImageGroup', {
                label: '輪播圖片',
                command: name,
                icon: this.path + 'icon.png'
            });
        }
    });


    function CKEditorImageGroup() {
        this.name = "";
        this.mobile = false;
        var parent = this;
        var _defaultSettings = {
            rootpath: '',
            root:'',
            mobile:false
        };
        var _settings = arguments[0];

        var settings = $.extend(_defaultSettings, _settings);
        this.mobile = settings.mobile;
        init = function () {
            var option_txt = '';
            var str='<div class="box" id="CKEditorImageGroup" style="display:none;margin-bottom: auto;">';
            str+='    <div class="box-title">';
            str+='    <h3><i class="icon-file"></i>輪播圖片</h3>';
            str+='     <div class="box-tool">';
            str+='        <a data-action="collapse" href="#"><i class="icon-chevron-up"></i></a>';
            str+='        <a data-action="close" href="#"><i class="icon-remove"></i></a>';
            str+='     </div>';
            str+='</div>';
            str+='<div class="box-content" style="height:auto;">';
            str+='  <div class="row">';
            str+='      <form class="form-horizontal">';
            str+='          <div class="form-group photogroup">\
                                <div class="col-sm-10 col-sm-offset-1 controls">\
                                    <div class="alert alert-warning">\
                                        <h4>新增 / 編輯 輪播圖片</h4>\
                                        <p>\
                                            <div id="textgroup_photogroup"></div>\
                                        </p>\
                                    </div>\
                                </div>\
                            </div>';
            str+='          <div class="form-group">';
            str+='              <div class="text-center" id="ckeditor_style_button_group">';
            str+='              </div>';
            str+='          </div>';
            str+='      </form>';
            str+='  </div>';
            str+='</div>';
            str+='</div>';
            str+='';

            $('body').append(str);

            $okbtn = $('<button type="button" class="btn btn-primary" ><i class="icon-ok"></i>編輯完成</button>');

            $okbtn.click(function () {
                saveToCkeditor();
            });


            chkCheckedStyle();

            $cancelbtn = $('<button type="button" class="btn" onclick="if(confirm(\'確定要取消編輯樣式?\')){clear();$.colorbox.close();}"><i class="icon-remove"></i>編輯取消</button>');
            $('#ckeditor_style_button_group').append($okbtn);
            $('#ckeditor_style_button_group').append($cancelbtn);
        }
        chkCheckedStyle = function(content, pic, save){
            var content = content || '';
            var pic = pic || '';

            var placeholder = '';
            var result = true;

            placeholder = '請上傳圖片';
            $('.photogroup').show();
            if(save){
                if(content.length<1 || content === undefined){result = false; alert(placeholder);}
                for(var i = 0 , num = content.length ; i<num ; i++){
                    if(!content[i].pic || content[i].pic==""){result = false; alert(placeholder);}
                }
            }
            
            $.colorbox.resize();
            return result;
        }
        saveToCkeditor = function () {
            var content = '';
            content = JSON.parse(ckeditortextgroup1.getValue());

            if(!chkCheckedStyle(content, pic, true)) return;

            var editor = CKEDITOR.instances[parent.name];
            var range = editor.createRange();
            range.moveToPosition(range.root, CKEDITOR.POSITION_BEFORE_END);
            editor.getSelection().selectRanges([range]);
            editor.insertHtml(getDocStyle(pic,content));

            clear();
            $.colorbox.close();
        }
        getDocStyle = function (pic, content) {
            if(typeof content !=='object'){
                content = content.replace(/(?:\r\n|\r|\n)/g, '<br />');
            }
            var str = "";
            var h1 = "";
            str += '<div class="slidergroup"><div class="casio_slider">\
                        <div class="casio_slider_b">\
                            <ul class="slider_img_b">';
            for(var i = 0 ,num = content.length ; i<num ; i++){
                str += '        <li><img src="'+settings.rootpath +'l'+content[i].pic+'">';
                str +=         (content[i].title && content[i].title!=''?'<p style="'+(content[i].bgcolor?'background-color: '+content[i].bgcolor+';':'')+(content[i].fontcolor?'color: '+content[i].fontcolor+';':'')+'text-align: center;">'+content[i].title+'</p>':'');
                str += '        </li>';
            }
            str += '        </ul>\
                            <div class="slider_pre"><img src="'+site_root+'Web_Manage/assets/ckeditor/plugins/imagegroup/prevbtn.png" /></div>\
                            <div class="slider_next"><img src="'+site_root+'Web_Manage/assets/ckeditor/plugins/imagegroup/nextbtn.png" /></div>\
                        </div>\
                        <div class="casio_slider_s">\
                            <ul class="slider_img_s">';
            for(var i = 0 ,num = content.length ; i<num ; i++){
                str += '        <li><img src="'+settings.rootpath +'s'+content[i].pic+'"></li>';
            }
            str += '        </ul>\
                        </div>\
                    </div></div><p></p>';
            return str;
        }

        clear = function () {
            $('#CKEditorImageGroup input[type=text]').val('');
            $('#CKEditorImageGroup .photogroup .textgroup_delbtn').click();
            chkCheckedStyle();
        }
        init();
    }

    CKEditorImageGroup.prototype.setName = function (name) {
        if (this.mobile==true && name.substring(0, 2) == "m_") {
            this.name = name.substring(2);
        }
        else {
            this.name = name;
        }
    }

    var CKEditorImageGroup;
    var ckeditortextgroup1;

    CKEditorImageGroup = new CKEditorImageGroup({rootpath: site_root + 'upload/editor/',root:site_root, mobile:true });

    ckeditortextgroup1 = new CODER.Util.TextGroup(
        "textgroup_photogroup", 
        "輪播圖",
        'id',
        [{'key': 'pic',
            'name': '圖片',
            'width': '30%',
            'type': 'pic',
            'file_path':'../../upload/editor/',
            'pics': [{name:'輪播主圖',type:5,tag:'l',width:679,height:453,backgroundcolor:'0_0_0'},{name:'小圖',type:8,tag:'s',width:0,height:46}],
            'upload_callback':function(){$.colorbox.resize();}
        },
        { 'key': 'title', 'name': '文字', 'width': '70%', 'type': 'text','placeholder':'請輸入文字'},
        ],false
    );
    ckeditortextgroup1.setMax(4);//最多幾列
    ckeditortextgroup1.setinsertevent(function(){
        setTimeout(function(){$.colorbox.resize()}, 200);
    });
    ckeditortextgroup1.init();

    $("#CKEditorImageGroup #insertBtn").click();
})();
