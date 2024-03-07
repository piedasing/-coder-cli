$(document).ready(function(){
    $("input.checkall[type=checkbox]").change(function(){
        var _this = $(this);
        var _checked=_this.prop("checked");
        _this.closest('tr').find('td input:checkbox:not(.checkall)').prop('checked', _checked).trigger("change");

        if(_this.closest('tr').hasClass('maintr')){
            _this.closest('tr').nextUntil('.maintr').find('.checkall').prop('checked', _checked).trigger("change");
        }
    })
    $("input:checkbox[name='main_auth[]']").change(function(){
        var action=$(this).attr('data_action');
        var data_main=$(this).attr('data_main');
        var _checked=$(this).prop("checked");
        $("input:checkbox[name='fun_auth[]'][data_main="+data_main+"][data_action="+action+"]").each(function(){
            $(this).prop("checked",_checked);
            checkallrow($(this).closest('tr'));
        })

        checkallrow($(this).closest('tr'));
    })
    $("input:checkbox[name='fun_auth[]']").change(function(){
        var action=$(this).attr('data_action');
        var data_main=$(this).attr('data_main');
        var _checked=true;
        $("input:checkbox[name='fun_auth[]'][data_main="+data_main+"][data_action="+action+"]").each(function(){
            var _thischecked = $(this).is(':checked')?1:0;
            if(_thischecked!==1){
                _checked = false;
            }
            if(!_checked){
                return false;
            }
        })
        $("input:checkbox[name='main_auth[]'][data_main="+data_main+"][data_action="+action+"]").each(function(){
            if($(this).prop("checked") != _checked){
                $(this).prop("checked",_checked);
                checkallrow($(this).closest('tr'));
            }
        });

        checkallrow($(this).closest('tr'));
    })
    $("a.tab").click(function(event) {
        event.preventDefault();
        var _this = $(this);
        if(_this.hasClass('tabclose')){
            _this.closest('tr').nextUntil('.maintr').css('display', 'table-row');
            _this.removeClass('tabclose').html();

            _this.addClass('icon-collapse-alt');
            _this.removeClass('icon-expand-alt');
        }else{
            _this.closest('tr').nextUntil('.maintr').css('display', 'none');
            _this.addClass('tabclose').html();

            _this.removeClass('icon-collapse-alt');
            _this.addClass('icon-expand-alt');
        }
    });

    $("tr.maintr,tr.funtr").each(function(){
        var _thisrow = $(this);
        checkallrow($(this));
    })

    $("input:checkbox[name='main_auth[]']:not(:checked)").each(function(){//如果子選單勾選不一樣就打開
        var _this = $(this);
        var _thisaction=_this.attr('data_action');
        var _thismain=_this.attr('data_main');
        var _prev_checkedstate = '';
        var _this_checkedstate = '';
        $("input:checkbox[name='fun_auth[]'][data_main="+_thismain+"][data_action="+_thisaction+"]").each(function(){
            _this_checkedstate = $(this).is(':checked')?1:0;
            if(_prev_checkedstate!=='' && _prev_checkedstate !== _this_checkedstate){
                _this.closest('tr').find('a.tab').removeClass('tabclose icon-expand-alt').addClass('icon-collapse-alt').closest('tr').nextUntil('.maintr').css('display', 'table-row');
            }
            _prev_checkedstate = _this_checkedstate;
        })
    })

    function checkallrow(_thisrow){
        var checkednum = _thisrow.find('td input[type=checkbox]:checked:not(.checkall)').length;
        var uncheckednum = _thisrow.find('td input[type=checkbox]:not(.checkall)').length;
        if(checkednum == uncheckednum){
            _thisrow.find('td input.checkall[type=checkbox]').prop('checked', true);
        }else{
            _thisrow.find('td input.checkall[type=checkbox]').prop('checked', false);
        }
    }
})
