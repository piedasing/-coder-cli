(function($) {
$.fn.coderlisthelp = function(settings) {

    var _defaultSettings = {
		callback:null,
		listComplete:null,
		ajaxsrc:"",
		delsrc:"",
		editlink:"",
		previewLink:"",
		ordersrc:"",
		ordersortable:"",
		excelLink:"",
        pdfLink: "",
		page:1,
		debug:false
    };
    var _settings = $.extend(_defaultSettings, settings);
	return this.each(function() {

		var parent=$(this);
		var content=parent.find('tbody:first');
		var pagecontent=parent.find('#listtable_page');
		var filterform=parent.find("form#filterform");
		var message="";
		var mutileselect=false;
		var manage=false;
		function init(){

			linkInit();

			parent.find('th').each(function(){
				var th=$(this);
				//如果有mutileselect功能
				if($(this).attr('attr')=='mutileselect'){
					mutileselect=true;
					var checked=$(this).prop('checked');
					$(this).find('input[type="checkbox"]').click(function(){
						parent.find('tbody tr td:eq(0)').prop('checked',checked);
					});
				}
				//操作功能
				else if($(this).attr('attr')=='manage'){
					manage=true;
				}
				//排序功能
				else if($(this).attr('attr')=='order'){
					var sorttype="";
					if($(this).attr('desc')=='desc'){
						sorttype="sort-desc";
					}
					else{
						sorttype="sort-asc";
					}
					var obj=$(this).find('a');
					obj.attr('class',sorttype+' sort-active');
					obj.click(function(event){
						parent.find('th a.sort-active').prop('class','sort-asc sort-desc');
						obj.attr('class',sorttype+' sort-active');
						showList();
					})
				}
				else{
					//一般排序樣式處理
					var obj=$(this).find('a');
					var css=obj.attr('class');
					if(css && css.indexOf('sort-')>-1){
						obj.click(function(event){
							var css=obj.attr('class');
							 if(css.indexOf('sort-active')<0){ //非作用中
								parent.find('th a.sort-active').prop('class','sort-asc sort-desc');
								obj.attr('class','sort-asc sort-active');
							 }
							 else{ //作用中
								obj.prop('class',(css.indexOf('sort-asc')<0) ? 'sort-asc sort-active' : 'sort-desc sort-active');
							 }
							 showList();
						});
					}
				}
			})

			//搜尋鈕處理
			parent.find('#submit,#refreshBtn').click(function(){
				showList();
			});
			//複數刪除鈕處理
			parent.find('#mutileDelBtn').click(function(){
				deleteChooseItem();
			});
            parent.find('#excelBtn').click(function () {
				savetoexcel();
			});
            parent.find('#pdfBtn').click(function () {
				savetopdf();
			});

			//拖曳排序 開放table1~table4 備用
			if(_settings.ordersortable!=''){
				$('#table1 table.table-advance tbody,#table2 table.table-advance tbody,#table3 table.table-advance tbody,#table4 table.table-advance tbody').sortable({
				    opacity: 0.8, //设置拖动时候的透明度 
				    helper: "clone",
				    cancel: "a",
				    cursor: 'move', //拖动的时候鼠标样式 
				    update: function(event, ui){ 
				        ordersortableList(ui.item.attr('order_id'),ui.item.attr('order_key'),ui.item.prev().attr('order_id'),'移動'+ui.item.attr('title'));
				    },
				}).disableSelection();
			}
		}

		function linkInit(){
			if(parent.attr('ajaxsrc')){
				_settings.ajaxsrc=parent.attr('ajaxsrc');
				parent.removeAttr('ajaxsrc');
			}
			if(parent.attr('delsrc')){
				_settings.delsrc=parent.attr('delsrc');
				parent.removeAttr('delsrc');
			}
			if(parent.attr('editlink')){
				_settings.editlink=parent.attr('editlink');
				parent.removeAttr('editlink');
			}
			if(parent.attr('previewLink')){
				_settings.previewLink=parent.attr('previewLink');
				parent.removeAttr('previewLink');
			}
			if(parent.attr('ordersrc')){
				_settings.ordersrc=parent.attr('ordersrc');
				parent.removeAttr('ordersrc');
			}
			if(parent.attr('ordersortable')){
				_settings.ordersortable=parent.attr('ordersortable');
				parent.removeAttr('ordersortable');
			}
			if(parent.attr('excelLink')){
				_settings.excelLink=parent.attr('excelLink');
				parent.removeAttr('excelLink');
			}
			if(parent.attr('pdfLink')){
				_settings.pdfLink=parent.attr('pdfLink');
				parent.removeAttr('pdfLink');
			}

            parent.find('#addBtn').click(function (evt) {
                evt.preventDefault();
                const href = $(this).attr('href');
                openBox2({ href: href, iframe: true, innerWidth: '95%', innerHeight: '95%', scrolling: !isnicescroll, transition: 'none', initialWidth: '95%', initialHeight: '95%', speed: 200, fixed: true, onClosed: function () { parent.find('#refreshBtn').click() } });
            });
			
            parent.find('#addexcelBtn').click(function (evt) {
                evt.preventDefault();
                const href = $(this).attr('href');
                openBox2({ href: href, iframe: true, innerWidth: '95%', innerHeight: '95%', scrolling: !isnicescroll, transition: 'none', initialWidth: '95%', initialHeight: '95%', speed: 200, fixed: true, onClosed: function () { parent.find('#refreshBtn').click() } });
            });
		}

		function deleteChooseItem(){
			var list=new Array();
			var listname="";
			var delstr="";
			if(typeof(parent.attr("delstr"))!="undefined"){
				var delstr=parent.attr("delstr");
			}
			parent.find('tbody tr').each(function(){
				var tr=$(this);
				if(tr.find('td:eq(0) input[type="checkbox"]').prop("checked")){
					list[list.length]=tr.attr('delkey');
					listname+='\r\n'+tr.attr('title');
				}
			})
			if(list.length>0){
				if(confirm('您確定要刪除這些項目嗎?'+listname+delstr)){
					deleteList(list);
				}
			}
			else{
				alert('請先選擇要被刪除的項目');
			}
		}

		function deleteItem(id,title){
			var delstr="";
			if(typeof(parent.attr("delstr"))!="undefined"){
				var delstr=parent.attr("delstr");
			}
			if(confirm('您確定要刪除'+title+'?'+delstr)){
				var list=new Array();
				list[0]=id;
				deleteList(list);
			}
		}


		function orderList(para,title){
			startload();
			hideorder();

			var parent=this;
			$.ajax({
			url:_settings.ordersrc+( formattoget(getSearchPara())!=''?(_settings.ordersrc.match(/[?]/)?'&':'?'):'')+formattoget(getSearchPara()),
			//url:_settings.ordersrc,
			cache: false,
			type:"POST",
			data:para ,
			dataType:"json",
			success:function(data){
				if(data.result==true){
					showList();
					showNotice('ok','排序作業完成','您己成功'+title);
				}
				else{
					showNotice('alert','排序作業失敗',data.msg);
				}
				stopload();
			}
			,error:function(xhr, ajaxOptions, thrownError){

				oops("讀取資料時發生錯誤,請梢候再試"+thrownError,xhr);
				stopload();
			}
			});
		}

		function ordersortableList(_thisid,_thiskey,_previd,title){
			startload();
			hideorder();
			var parent=this;
			$.ajax({
				url:_settings.ordersortable,
				cache: false,
				type:"POST",
				data:{
					'order_id':_thisid,
					'order_key':_thiskey,
					'prev_id':_previd,
					'method':'sortable',
				},
				dataType:"json",
				success:function(data){
					if(data.result==true){
						showList();
						showNotice('ok','排序作業完成','您己成功'+title);
					}
					else{
						showNotice('alert','排序作業失敗',data.msg);
					}
					stopload();
				}
				,error:function(xhr, ajaxOptions, thrownError){

					oops("讀取資料時發生錯誤,請梢候再試"+thrownError,xhr);
					stopload();
				}
			});
		}

		function deleteList(list){
			startload();
			var parent=this;
			$.ajax({
			url:_settings.delsrc,
			cache: false,
			type:"POST",
			data:{ id : list } ,
			dataType:"json",
			success:function(data){
				if(data.result==true){
					showList();
					if(data.check == 1){
                        showNotice('ok','刪除作業【待審核】',data.count+'筆資料');
					}
					else{
                        showNotice('ok','刪除作業完成','您己成功刪除'+data.count+'筆資料');
					}

				}
				else{
					showNotice('alert','刪除作業失敗',data.msg);
				}
				stopload();
			}
			,error:function(xhr, ajaxOptions, thrownError){
				oops("讀取資料時發生錯誤,請梢候再試"+thrownError,xhr);
				stopload();
			}
			});
		}

		function showList(){

			startload();
			var parent=this;
			var callback=_settings.callback;
			var listComplete=_settings.listComplete;
			$.ajax({
			url:_settings.ajaxsrc,
			cache: false,
			type:"GET",
			data: getPara(),
			dataType:"json",
			success:function(data){
				if(data){
					if(data['result']==true){
						if (callback && typeof(callback)=='function'){
							callback(content, data["data"], data["page"]);
						}
						showModifyContent();
						showPage(data["page"]);
						chkCkeditorScrollEvent();
						if(listComplete && typeof(listComplete)=='function'){
							listComplete();
						}
					}
					else{
						oops(data['data']);
					}
				}
				else{
					oops("回傳資料錯誤");
				}
				stopload();
			}
			,error:function(xhr, ajaxOptions, thrownError){
				oops("讀取資料時發生錯誤,請梢候再試"+thrownError,xhr);
				stopload();
			}
			});
		}
		function savetoexcel(){
            if (!confirm('確定要匯出Excel嗎?')) {
                return;
            }

			var list=new Array(),
			    list_id ='';
			parent.find('tbody tr').each(function(){
				var tr=$(this);
				if(tr.find('td:eq(0) input[type="checkbox"]').prop("checked")){
					list[list.length]=tr.attr('delkey');
				}
			});
			if(list.length>0){
				list_id=list.join(',');
			}
			location.href = _settings.excelLink+'?'+'list_id'+'='+list_id+'&'+formattoget(getPara());
        }
        function savetopdf(){
            if (!confirm('確定要匯出PDF嗎?')) {
                return;
            }

			var list=new Array(),
			    list_id ='';
			parent.find('tbody tr').each(function(){
				var tr=$(this);
				if(tr.find('td:eq(0) input[type="checkbox"]').prop("checked")){
					list[list.length]=tr.attr('delkey');
				}
			});
			if(list.length>0){
				list_id=list.join(',');
			}
			location.href = _settings.pdfLink+'?'+'list_id'+'='+list_id+'&'+formattoget(getPara());
		}
		function showModifyContent(){
			if(mutileselect){
				parent.find('tbody tr').each(function(){
					$(this).prepend('<td><input type="checkbox"></td>');
				})
			}
			if(manage){
				//是否排序作用中欄位判斷
				var orderclass='disabled';
				var ordertitle='必須要用排序欄位排序才可使用';
				var orderth=parent.find('th[attr="order"] a.sort-active');
				if(orderth.index()>-1){
					orderclass=' btn-info ';
					ordertitle='';
					if(_settings.ordersortable!=''){$('#table1 table.table-advance tbody,#table2 table.table-advance tbody,#table3 table.table-advance tbody,#table4 table.table-advance tbody').sortable( "enable" )};//拖曳排序啟用
				}else{
					if(_settings.ordersortable!=''){$('#table1 table.table-advance tbody,#table2 table.table-advance tbody,#table3 table.table-advance tbody,#table4 table.table-advance tbody').sortable( "disable" )};//拖曳排序禁止
				}
				//畫各個按鈕
				parent.find('tbody tr').each(function(){

					var tr=$(this);
					var title=tr.attr('title');
					//排序按鈕
					if(_settings.ordersrc!="" && tr.attr('orderlink')!=''){
						var td=$('<td class=" text-center"></td>');
						var up=$('<a class="btn btn-sm show-tooltip '+orderclass+'" title="上移'+ordertitle+'" href="#"><i class="icon-angle-up"></i></a>');
						up.click(function(){
							orderList(tr.attr('orderlink')+'&method=up','上移'+title);
						});
						td.append(up);
						var down=$('<a class="btn btn-sm show-tooltip '+orderclass+'" title="下移'+ordertitle+'" href="#"><i class="icon-angle-down"></i></a>');
						down.click(function(){
							orderList(tr.attr('orderlink')+'&method=down','下移'+title);
						});
						td.append(down);
						$(this).append(td);
					}

					//管理按鈕
                    var td = $('<td class="text-center" style="white-space: nowrap;"></td>');
                    let manageBtnWidth = 0;
                    if (_settings.editlink != "") { manageBtnWidth += 40; }
                    if (_settings.previewLink != "") { manageBtnWidth += 40; }
                    if (_settings.delsrc != "") { manageBtnWidth += 40; }
                    td.append(`<div class="btn-group" style="min-width: ${manageBtnWidth}px; display: flex; justify-content: center;"></div>`);
					if(_settings.editlink!=""){
						var edit=$('<a class="btn btn-sm show-tooltip btn-success" title="修改'+title+'" href="javascript:void(0)"><i class="icon-edit"></i></a>');
						edit.click(function(){
							openBox2({href:_settings.editlink+'?'+tr.attr('editlink'),iframe:true, innerWidth: '95%', innerHeight: '95%', scrolling: !isnicescroll,transition:'none',initialWidth:'95%',initialHeight:'95%',speed:200,fixed:true,onClosed:function(){parent.find('#refreshBtn').click()}});
						});
						td.find('.btn-group').append(edit);
					}
					if(_settings.previewLink!=""){
						var edit=$('<a class="btn btn-sm show-tooltip btn-primary" title="瀏覽'+title+'" href="javascript:void(0)"><i class="icon-search"></i></a>');
						edit.click(function(){
							openBox2({href:_settings.previewLink+'?'+tr.attr('previewLink'),iframe:true, innerWidth: '95%', innerHeight: '95%', scrolling: !isnicescroll,transition:'none',initialWidth:'95%',initialHeight:'95%',speed:200,fixed:true,onClosed:function(){parent.find('#refreshBtn').click()}});
						});
						td.find('.btn-group').append(edit);
					}
					if(_settings.delsrc!=""){
						var del=$('<a id="delbtn" class="btn btn-sm btn-danger show-tooltip" title="刪除'+title+'" href="javascript:void(0)"><i class="icon-trash"></i></a>');
						del.click(function(){
							deleteItem(tr.attr('delkey'),title);
						});
						td.find('.btn-group').append(del);
					}

					$(this).append(td);
				})
			}
		}
		function showPage(page){
			pagecontent.html('');
			if(page['count']>0){
				var $left=$('<div></div>');
				$left.append((page["begin"]+1)+'-'+Math.min(page["show_num"],page["count"])+' of '+page["count"]+' &nbsp; Page: '+page["page"]+"/"+page["pagecount"]+' ');
				$left.append("每頁");
                $select = $("<select id=\"PageNum\" name=\"PageNum\" style=\"margin: 0 5px;\" />");
				//$select.append('<option value="1" >1</option>');
                [10, 20, 30, 50, 100].forEach((_num) => {
                    $select.append('<option value="'+_num+'" '+((page["show_num"]==_num+'') ? 'selected' : '')+'>'+_num+'</option>');
                })
				$select.change(function(event){
				   showList();
			    });
				$left.append($select);
				$left.append('筆');
				pagecontent.append($left);

				var $right=$('<div></div>');
				$right_content=$('<ul class="pagination"></ul>');

				var _page=parseInt(page["page"]);
				var page_start=parseInt(page["s_start"]);
				var page_end=parseInt(page["s_end"]);


				if(_page>page_start){
					$btn=$('<li><a href="javascript:void(0)">← Prev</a></li>');
					bindPageClick($btn,_page-1);
					$right_content.append($btn);

				}
				for(var i=page_start;i<=page_end;i++){
					var $li=$('<li '+(_page==i ? 'class="active"' : '')+'><a href="javascript:void(0)">'+i+'</a></li>');
					bindPageClick($li,i);
					$right_content.append($li);
				}
				if(_page<page_end){
					$btn=$('<li><a href="javascript:void(0)">Next → </a></li>');
					bindPageClick($btn,_page+1);
					$right_content.append($btn);
				}
				$right.append($right_content);
				pagecontent.append($right);
			}
		}

		function bindPageClick($obj,ind){
			$obj.click({ind:ind}, function(event){
			  _settings.page=event.data.ind;
			  showList();
		    });
		}

		function getPara(){
			var orderobj=parent.find('th').find('a.sort-active');
			if(orderobj.index()<0){
				orderobj=parent.find('thead tr[sortkey]');
			}
			if(orderobj.index()<0){
				orderobj=parent.find('th').find('a.sort-desc:eq(0)');
			}
			var orderkey=orderobj.attr("sortkey");
			var orderdesc=(orderobj.prop("class").indexOf('sort-asc')<0) ? 'desc' : 'asc';

			var para = { page:_settings.page,pagenum: parent.find('#PageNum').val(),orderkey:orderkey,orderdesc:orderdesc}
			 $.extend(para, getSearchPara());
			// console.log(para);
			return para;

		}
		function getSearchPara(){
			message="";
			var para = {};
			if(filterform!=null && filterform!='undefined'){

				filterform.find(':input').each(function(){
					var obj=$(this);
					var otype = obj.getType();
					switch (otype){
						case 'text':
							para[obj.attr('id')] = checkFormat(obj) ? obj.val() : '';
						break;
						case 'hidden':
							para[obj.attr('id')] =  obj.val() ;
						break;
						case 'select':
							para[obj.attr('id')] =  obj.val() ;
						break;
						case 'checkbox':
							if(obj.prop('checked')){
								if(!para[obj.attr('id')]) {
									para[obj.attr('id')] = new Array();
								}
								para[obj.attr('id')][para[obj.attr('id')].length]=  obj.val() ;
							}
						break;
					}
				})
				if(message!=""){
					showNotice('alert','您的搜尋條件無法順利執行','有些欄位格式不正確導致搜尋無法完全顯示,請檢查輸入條件是否正確!<br>'+message);
				}

			}
			//console.log(para);
			return para;

		}
		function checkFormat(obj){
			var val=obj.val()
			//日期格式確認
            if (obj.hasClass("date-picker") && val != '') {
                const viewMode = obj.attr('data-date-viewmode') || 'dates';
                if (viewMode === 'dates' && !/^([0-9]{4})[.-]{1}([0-9]{2})[.-]{1}([0-9]{2})$/.test(val)) {
                    obj.addClass('myform-error');
                    message += val + '必須為yyyy-mm-dd格式<br>';
                } else if (viewMode === 'months' && !/^([0-9]{4})[.-]{1}([0-9]{2})$/.test(val)) {
                    obj.addClass('myform-error');
                    message += val + '必須為yyyy-mm格式<br>';
               } else if (viewMode === 'years' && !/^([0-9]{4})$/.test(val)) {
                    obj.addClass('myform-error');
                    message += val + '必須為yyyy格式<br>';
                } else {
                    obj.removeClass('myform-error');
                    return true;
                }
            }
			else if(obj.attr("format")=="numeric"){
				if(!isNumber(val) && val!=''){
					obj.addClass('myform-error');
					message+=val+'必須為數字格式<br>';
				}
				else{
					obj.removeClass('myform-error');
					return true;
				}
			}
			else{
				return true;
			}
		}
		function oops(msg,data){
			showNotice('alert','作業失敗',msg);
			if(_settings.debug == true){
				console.log(data);
			}
			stopload();
		}
		function startload(){
			parent.find('#filterloading').show();
		}
		function stopload(){
			parent.find('#filterloading').hide();
		}
		function hideorder(){
			parent.find('td a.btn-info').addClass('disabled').removeClass('btn-info');
		}
		function formattoget(obj){
			var str ='';
			for(var key in obj){
				str += ('&'+key+'='+obj[key]);
			}
			return str.slice(1);
		}
		_settings.page=1;
		init();
		showList();

	});
};
})(jQuery);
