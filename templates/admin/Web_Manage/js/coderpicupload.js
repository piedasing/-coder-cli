//20160104 Jane新增 圖片自訂裁切功能
(function ($) {
    $.fn.coderpicupload = function (settings) {
        var _defaultSettings = {
            id: 'pic',
            id_click_num: null,
            croptag: 'changepics_', //自訂切圖的tag
            croptagary: {},
            callback: null,
            pics: null,
            org_pic: null,
            filepath: '../../upload/temp/',
            nophotopic: '../images/nophoto2.gif',
            ajaxsrc: '../comm/upload.php',
            ajaxsrc_crop: '../comm/cropimage.php',
            org_width: '130px',
            org_height: '130px',
            s_width: '100px',
            s_height: '100px',
            width: '100px',
            height: '100px',
            debug: true,
            required: false,
            jcrop: true,
            notice: '', // 提示訊息
            upload_callback: function () {},
        };
        var _settings = $.extend(_defaultSettings, settings);
        return this.each(function () {
            var parent = $(this);
            var $content = null;
            var $origin = null;
            var $file = null;
            var $del = null;
            var $process = null;
            var $hidden = null;
            function init() {
                //圖片區
                var $table = $('<table cellpadding="5" ></table>');
                $content = $('<tr></tr>');
                $table.append($content);
                var sizetxt = '';
                if (_settings.width != '' && +settings.height != '') {
                    sizetxt = '建議圖片大小為:' + _settings.width + 'x' + _settings.height;
                }
                $origin = $(
                    '<td valign="bottom" align="left"><img src="' +
                        _settings.nophotopic +
                        '" style="max-width:' +
                        _settings.org_width +
                        ';max-height:' +
                        _settings.org_height +
                        '" class="myform_pic show-popover"  data-placement="top" data-trigger="hover"  data-content="此圖片為原圖的預覽,點擊圖片可看原始圖檔。' +
                        sizetxt +
                        '" data-original-title="原始圖片"><p><span class="label label-gray label-font">原始圖片</span></p></td>',
                );
                $content.append($origin);
                if (_settings.pics != null) {
                    for (var i = 0; i < _settings.pics.length; i++) {
                        $content.append(
                            '<td valign="bottom" align="left"><img src="' +
                                _settings.nophotopic +
                                '" id="spic' +
                                i +
                                '" style="max-width:' +
                                (Object.prototype.toString.call(_settings.s_height) ===
                                '[object Array]'
                                    ? _settings.s_width[i]
                                    : _settings.s_width) +
                                ';max-height:' +
                                (Object.prototype.toString.call(_settings.s_height) ===
                                '[object Array]'
                                    ? _settings.s_height[i]
                                    : _settings.s_height) +
                                ';" data-sno="' +
                                i +
                                '" class="myform_pic"><p><span class="label label-gray label-font">' +
                                _settings.pics[i].name +
                                _settings.pics[i].tag +
                                ':' +
                                _settings.pics[i].width +
                                'x' +
                                _settings.pics[i].height +
                                '</span></p>',
                        );
                    }
                }
                parent.append($table);

                //進度條
                $process = $(
                    '<div class="progress progress-striped active"><div style="width: 0%;" class="progress-bar"></div> </div>',
                );
                parent.append($process);
                $process.hide();

                //按鈕區
                var $buttoncontent = $('<div style="width:275px;"></div>');

                $file = $('<input type="file" style="float:left;line-height:30px;width:240px">');
                $del = $(
                    '<button type="button" class="btn btn-danger" style="float:left"><i class="icon-remove"></i></button>',
                );

                $del.click(function () {
                    clearPics();
                });

                $buttoncontent.append($file);
                $buttoncontent.append($del);
                $buttoncontent.append('<div style="clear:both"></div>');

                var click_num = '';
                if (_settings.click_num != null) {
                    var click_num = _settings.click_num;
                }
                $hidden = $(
                    '<input type="hidden" thisid="' +
                        _settings.id +
                        '" name="' +
                        _settings.id +
                        '" id="' +
                        _settings.id +
                        click_num +
                        '" class="hasvalidate" value="" ' +
                        (_settings.required == true ? ' required="yes" ' : ' ') +
                        '>',
                );
                $buttoncontent.append($hidden);

                if (_settings.id_click_num != null) {
                    $hidden_click_num = $(
                        '<input type="hidden" thisid="' +
                            _settings.id +
                            '" name="' +
                            _settings.id_click_num +
                            '" id="' +
                            _settings.id_click_num +
                            '" class="hasvalidate" value="" ' +
                            (_settings.required == true ? ' required="yes" ' : ' ') +
                            '>',
                    );
                    $buttoncontent.append($hidden_click_num);
                }

                if (_settings.notice) {
                    $buttoncontent.append(
                        `<p class="alert alert-danger" style="margin: 1rem 0 0 0; padding: 0.75rem 1rem; border-radius: 4px;">${_settings.notice}</p>`,
                    );
                }

                parent.append($buttoncontent);
                parent.find('img').click(function () {
                    if ($(this).attr('data-filepath') && $(this).attr('data-picname')) {
                        if (_settings.jcrop) {
                            $.colorbox({
                                html:
                                    '<div><img src="' +
                                    $(this).attr('src') +
                                    '"><div class="jcrop_picbox_edit" data-sno="' +
                                    $(this).attr('data-sno') +
                                    '" data-filepath="' +
                                    $(this).attr('data-filepath') +
                                    '" data-picname="' +
                                    $(this).attr('data-picname') +
                                    '"><span class="glyphicon glyphicon-pencil"></span></div></div>',
                                maxWidth: '95%',
                                initialWidth: '50px',
                                initialHeight: '50px',
                            }); //href:$(this).attr('src')
                            $('.jcrop_picbox_edit').click(function () {
                                jcrop_picbox_edit($(this));
                            });
                        } else {
                            openPicBox({
                                href: $(this).attr('src'),
                                initialWidth: '50px',
                                initialHeight: '50px',
                            });
                        }
                    } else {
                        openPicBox({
                            href: $(this).attr('src'),
                            maxWidth: '95%',
                            initialWidth: '50px',
                            initialHeight: '50px',
                        });
                    }
                });
                initButton();
                if (_settings.org_pic != null) {
                    showPics(_settings.org_pic);
                }
            }

            function jcrop_picbox_edit(_this) {
                var _sno = _this.attr('data-sno'),
                    _thispicname = _this.attr('data-picname'),
                    _thisfilepath = _this.attr('data-filepath'),
                    _thisoripic = _this.attr('data-filepath') + _this.attr('data-picname');
                $.colorbox({
                    href: _thisoripic,
                    className: 'jcrop_pic',
                    maxWidth: '95%',
                    maxHeight: '95%',
                    initialWidth: '50px',
                    initialHeight: '50px',
                    onComplete: function () {
                        var _thisokbtn = $(
                            '<div class="jcrop_ok"><span class="glyphicon glyphicon-ok"></span>\
						<input type="hidden" id="jcrop_x">\
						<input type="hidden" id="jcrop_y">\
						<input type="hidden" id="jcrop_w">\
						<input type="hidden" id="jcrop_h">\
						<input type="hidden" id="jcrop_thispicname" value="' +
                                _thispicname +
                                '">\
						<input type="hidden" id="jcrop_thispictag" value="' +
                                _settings.pics[_sno].tag +
                                '">\
						</div>',
                        );
                        var _thisinfobox = $(
                            '<div class="jcrop_infobox tosmall">建議尺寸為:' +
                                _settings.pics[_sno].width +
                                'px X' +
                                _settings.pics[_sno].height +
                                'px&nbsp;&nbsp;&nbsp;<span class="jcrop_infobox_wh">( <em class="w">0</em> px X <em class="h">0</em> px)</span></div>',
                        );
                        var _thisimgwidth = $('.jcrop_pic img').width(),
                            _thisimgheight = $('.jcrop_pic img').height(),
                            _thisimgnaturalwidth = $('.jcrop_pic img')[0].naturalWidth,
                            _thisimgnaturalheight = $('.jcrop_pic img')[0].naturalHeight;
                        $('.jcrop_pic #cboxClose').css('z-index', '9999');
                        $('.jcrop_pic #cboxLoadedContent')
                            .css('overflow', 'hidden')
                            .append(_thisokbtn)
                            .append(_thisinfobox);
                        $('.jcrop_pic img').Jcrop({
                            bgOpacity: 0.5,
                            bgColor: 'white',
                            addClass: 'jcrop-light',
                            //minSize:[_settings.pics[_sno].width,_settings.pics[_sno].height],
                            //boxWidth: _thisimgwidth,
                            //boxHeight: _thisimgheight,
                            trueSize: [_thisimgnaturalwidth, _thisimgnaturalheight],
                            aspectRatio: _settings.pics[_sno].width / _settings.pics[_sno].height,
                            onSelect: function (c) {
                                $('#jcrop_x').val(c.x);
                                $('#jcrop_y').val(c.y);
                                $('#jcrop_w').val(c.w);
                                $('#jcrop_h').val(c.h);
                            },
                            onChange: function (c) {
                                $('.jcrop_infobox .jcrop_infobox_wh .w').text(Math.floor(c.w));
                                $('.jcrop_infobox .jcrop_infobox_wh .h').text(Math.floor(c.h));
                                if (
                                    Math.floor(c.w) < _settings.pics[_sno].width ||
                                    Math.floor(c.h) < _settings.pics[_sno].height
                                ) {
                                    $('.jcrop_infobox').addClass('tosmall');
                                } else {
                                    $('.jcrop_infobox').removeClass('tosmall');
                                }
                            },
                            onRelease: function () {
                                $('#jcrop_x').val('');
                                $('#jcrop_y').val('');
                                $('#jcrop_w').val('');
                                $('#jcrop_h').val('');
                                $('.jcrop_infobox .jcrop_infobox_wh .w').text('0');
                                $('.jcrop_infobox .jcrop_infobox_wh .h').text('0');
                            },
                        });
                        _thisokbtn.click(function (event) {
                            if (
                                Math.floor($('#jcrop_w').val()) ||
                                Math.floor($('#jcrop_h').val())
                            ) {
                                if (
                                    Math.floor($('#jcrop_w').val()) < _settings.pics[_sno].width ||
                                    Math.floor($('#jcrop_h').val()) < _settings.pics[_sno].height
                                ) {
                                    //alert("圈選範圍過小 (需大於"+_settings.pics[_sno].width+"X"+_settings.pics[_sno].height+')');
                                    //return false;
                                    if (
                                        !confirm(
                                            '建議圈選範圍需大於 ' +
                                                _settings.pics[_sno].width +
                                                'X' +
                                                _settings.pics[_sno].height +
                                                '\n是否確定裁切? (尺寸不足部分將自動補白底)',
                                        )
                                    ) {
                                        return false;
                                    }
                                }
                                $.ajax({
                                    url: _settings.ajaxsrc_crop,
                                    data: {
                                        sx: $('#jcrop_x').val(),
                                        sy: $('#jcrop_y').val(),
                                        sw: $('#jcrop_w').val(),
                                        sh: $('#jcrop_h').val(),
                                        tw: _settings.pics[_sno].width,
                                        th: _settings.pics[_sno].height,
                                        name: $('#jcrop_thispicname').val(),
                                        tag: $('#jcrop_thispictag').val(),
                                        croptag: _settings.croptag,
                                        orgfilepath: _thisfilepath,
                                    },
                                    type: 'POST',
                                    dataType: 'json',
                                    success: function (r) {
                                        if (r.result) {
                                            $.fn.colorbox.close();
                                            showPics(r, _settings.croptag);
                                        } else {
                                            oops('上傳失敗:' + r['msg'], r);
                                        }
                                    },
                                });
                            } else {
                                alert('請圈選裁圖範圍');
                                return false;
                            }
                        });
                    },
                });
            }

            function initButton() {
                $file.change(function () {
                    var pic = this.files[0];
                    var pics = _settings.pics;
                    if (pic) {
                        _settings.croptagary = {};
                        var $processbar = $process.find('.progress-bar');
                        $process.show();
                        $processbar.css('width', '0px');
                        var fd = new FormData();
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', _settings.ajaxsrc);
                        xhr.onload = function () {
                            var r = $.parseJSON(this.responseText);
                            $processbar.css('width', '100%');
                            $process.fadeOut(1500, _settings.upload_callback);
                            if (r['result'] == true) {
                                showPics(r);
                                showNotice('ok', '上傳作業完成', '您己成功上傳圖片。');
                            } else {
                                oops('上傳失敗:' + r['msg'], r);
                            }
                        };
                        xhr.upload.onprogress = function (evt) {
                            //上傳進度
                            if (evt.lengthComputable) {
                                var complete = ((evt.loaded / evt.total) * 100) | 0;
                                if (100 == complete) {
                                    complete = 99.9;
                                }
                                $processbar.css('width', complete + '%');
                            }
                        };
                        fd.append('pic', pic);
                        fd.append('path', _settings.filepath);

                        if (pics != null) {
                            for (var i = 0; i < pics.length; i++) {
                                fd.append(
                                    'arys[]',
                                    pics[i].tag +
                                        ',' +
                                        pics[i].type +
                                        ',' +
                                        pics[i].width +
                                        ',' +
                                        pics[i].height +
                                        ',' +
                                        (pics[i].smallnomake ? pics[i].smallnomake : false),
                                );
                            }
                        }
                        xhr.send(fd); //開始上傳
                    }
                });
            }

            async function showPics(pic, croptag) {
                var _pic = $origin.find('img');
                var _croptag = croptag || '';
                _pic.attr({ src: pic['filepath'] + pic['filename'] });
                pic['width'] = pic['width'] || _pic?.get(0)?.naturalWidth || '';
                pic['height'] = pic['height'] || _pic?.get(0)?.naturalHeight || '';
                if (!pic['width'] && !pic['height']) {
                    const res = await loadImage(pic['filepath'] + pic['filename']);
                    if (res) {
                        pic['width'] = res.width || '';
                        pic['height'] = res.height || '';
                    }
                }
                if (pic['width'] && pic['height']) {
                    $origin.find('span').html('原始圖片' + pic['width'] + 'x' + pic['height']);
                } else {
                    $origin.find('span').html('原始圖片');
                }
                $hidden.val(pic['filename']);
                if (_settings.id_click_num != null) {
                    $('#' + _settings.id_click_num).val(pic['filename']);
                }
                var pics = _settings.pics;
                if (pics != null) {
                    var _pics;
                    for (var i = 0; i < pics.length; i++) {
                        var _thistag = pics[i].tag;
                        if (_settings.croptagary && _settings.croptagary[_thistag]) {
                            var _pics = _settings.croptagary[_thistag];
                        } else if (pic['filepath_pics'] && pic['filepath_pics'][_thistag]) {
                            var _pics = pic['filepath_pics'][_thistag] + _croptag;
                            _settings.croptagary[_thistag] =
                                pic['filepath_pics'][_thistag] + _croptag;
                        } else {
                            var _pics = pic['filepath'];
                        }
                        _pics += _thistag + pic['filename'] + '?time=' + new Date().getTime();

                        parent.find('#spic' + i).attr({
                            src: _pics,
                            'data-filepath': pic['filepath'],
                            'data-picname': pic['filename'],
                        });
                    }
                }
            }

            function loadImage(src) {
                return new Promise((resolve, reject) => {
                    const image = new Image();
                    image.onload = () => {
                        resolve(image);
                    };
                    image.onerror = () => {
                        resolve(null);
                    };
                    image.src = src;
                });
            }

            function clearPics() {
                var _pic = $origin.find('img');
                _pic.attr('src', _settings.nophotopic);
                $origin.find('span').html('您尚未選擇圖片');
                $hidden.val('');
                if (_settings.id_click_num != null) {
                    $('#' + _settings.id_click_num).val('');
                }
                _settings.croptagary = {};
                var pics = _settings.pics;
                if (pics != null) {
                    for (var i = 0; i < pics.length; i++) {
                        parent.find('#spic' + i).attr('src', _settings.nophotopic);
                    }
                }
            }
            function oops(msg, data) {
                showNotice('alert', '作業失敗', msg);
                if (_settings.debug == true) {
                    console.log(data);
                }
            }
            init();
        });
    };
})(jQuery);
