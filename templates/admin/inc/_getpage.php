<?php
function getsql($sql, $page_size, $page_querystring) {
	global $page, $sql, $count, $page_count, $pre, $next, $querystring, $HS, $ID, $PW, $DB, $db;
	$querystring = clearPageStr($page_querystring);

	$page = get("page")!='' && get("page")>0 ? get("page") : 1;

	$count = $db -> queryCount($sql);
	$page_count = ceil($count / $page_size);
	if ($page > $page_count) {
		$page = $page_count;
	}
	if ($page <= 0) {
		$page = 1;
	}
	$start = number_format(($page-1)*$page_size,0,'.','');

	$pre = $page - 1;
	$next = $page + 1;
	$first = 1;
	$last = $page_count;
	$sql.= " limit $start,$page_size";
	return $count;
}

function pagesql() {
	global $sql;
	return $sql;
}
function showpage() {
	global $page, $page_count, $count, $pre, $next, $querystring;
	if ($querystring != "") {
		$querystring = $querystring . "&";
	}
	echo $page . ' / ' . $page_count . '&nbsp;&nbsp;共' . $count . '筆資料&nbsp;&nbsp;';
	if ($page != 1) {
		echo '<a href=?' . $querystring . 'page=1>首頁</a>&nbsp;&nbsp;
		   <a href=?' . $querystring . 'page=' . $pre . '>上一頁</a>&nbsp;&nbsp;';
	}
	$viewpage = 5;

	if ($page_count > $viewpage) {
		if ($page - $viewpage < 0) {
			$s = 1;
			$j = $viewpage;
		}else {
			$s = (int)($page-$viewpage+1);
			if($s===0){$s++;}
			$j = $s + 5;
			if ($j >= $page_count) {
				$j = $page_count;
			}
			//$j = $page_count;
		}
	}else {
		$s = 1;
		$j = $page_count;
	}
	for ($i = $s; $i <= $j; $i++) {
		$num = $i;
		if ($page == $num) {
			echo $num . "&nbsp;";
		}else {
			echo '<a href=?' . $querystring . 'page=' . $num . '>' . $num . '</a>&nbsp;&nbsp;';
		}
	}
	if ($page < $page_count) {
		echo '<a href=?' . $querystring . 'page=' . ($page + 1) . '>下一頁</a>&nbsp;&nbsp;';
		echo '<a href=?' . $querystring . 'page=' . $page_count . '>末頁</a>&nbsp;';
	}
}
function showwebpage($ismob = false){//前端用顯示頁數
    global $page, $page_count, $count, $pre, $next, $querystring;
    if($page_count == 0){return;}
	if($querystring != ""){
    	$querystring = $querystring."&";
	}

	if($ismob){
		$prevbtn = '<a href="'.($page === 1?'javascript:void(0)':'?'.$querystring.'page='.$pre).'" class="prevPage"></a>';
		$active = 'active';
		$next = '<a href="'.($page >= $page_count?'javascript:void(0)':'?'.$querystring.'page='.$next).'" class="nextPage"></a>';
	}else{
		$prevbtn = '<a href="'.($page === 1?'javascript:void(0)':'?'.$querystring.'page='.$pre).'"><div class="prev"></div></a>';
		$active = 'currentPage';
		$next = '<a href="'.($page >= $page_count?'javascript:void(0)':'?'.$querystring.'page='.$next).'"><div class="next"></div></a>';
	}

	echo $prevbtn;
	$viewpage = 5;//預計頁碼數
	if($page_count > $viewpage){//總頁數>預計頁數
		/*此規則固定active置中 ，若$viewpage為偶數，則為$viewpage/2-1*/
		if($viewpage%2 == 0){
			$halfpage = ($viewpage/2)-1;
		}else{
			$halfpage = floor($viewpage/2);
		}
		if($page>$halfpage){
			$s = $page-$halfpage;
			$j = $s+$viewpage-1;
			if($j >= $page_count){
				$j = $page_count;
				$s = $j-$viewpage+1;
			}
		}else{
			$s = 1;$j = $viewpage;
		}
	}else{
		$s = 1;
		$j = $page_count;
	}

	for($i = $s;$i <= $j;$i++){
		$num = $i;
		if($page == $num){
			echo '<a href="javascript:void(0)" class="'.$active.'">'.$num.'</a>';
		}else{
			echo '<a href="?'.$querystring.'page='.$num.'">'.$num.'</a>';
		}
	}
	if(!$ismob){
		echo '<span>';
		if($j<$page_count){
			echo '...';
		}
	}

	echo $next;
	if(!$ismob){echo '</span>';}
}


function clearPageStr($querystring) {

	$pageind = strpos($querystring, '&page=');
	if ($pageind !== false) {

		$pageind2 = strpos(substr($querystring, $pageind + 6), '&');
		$querystring_ = substr($querystring, 0, $pageind);

		if ($pageind2 !== false) {
			$querystring_.= substr($querystring, $pageind + 6 + $pageind2);
		}
	}
	else {
		$querystring_ = $querystring;
	}
	return $querystring_;
}
?>
