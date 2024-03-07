<?php
//2014.10.17 Jane 新增 剩餘時間計算功能(showrestseconds)
//2014.11.04 Jane 新增 進行中傳回true功能(getDateInfo_boolean)
//2014.11.06 Jane 新增 得獎率計算(winningrate)
//2014.11.14 Jane 新增 若為null則傳回(getifnull)(目標字串,傳回)
//2015.01.21 Jane 新增 array_diff_assoc_recursive(解決array_diff_assoc無法用於2維陣列的問題)
class coderHelp
{
    public static function getDateRangeLabel($date_s = '', $date_e = '') {
        if (!empty($date_s) && !empty($date_e)) {
            return "{$date_s} ~ {$date_e}";
        }
        if (!empty($date_s)) {
            return "{$date_s} ~ 最新";
        }
        if (!empty($date_e)) {
            return "最舊 ~ {$date_e}";
        }
        return "";
    }

	public static function getArrayPropertyVal($ary,$_key,$_val,$_return)
	{
		$item=self::getArrayByPropertyVal($ary,$_key,$_val);

		return ($item && self::getStr($item[$_return])!='' ) ? $item[$_return] : '';
	}
	public static function getArrayByPropertyVal($ary,$_key,$_val)
	{
		foreach($ary as $key=>$item)
		{
			if(isset($item[$_key]) && $item[$_key]==$_val)
			{
				return $item;
			}
		}
		return false;
	}

	//將2維陣列中的KEY值更改成指定的KEY值 ex $ary[0]['key']=>$ary[0]['value']
	public static function parseAryKeys($ary,$name_ary)
	{
		$new_ary=array();
		foreach($ary as $item)
		{
			foreach($name_ary as $key=>$new_key)
			{
				if(array_key_exists($key,$item))
				{
					$item[$new_key]=$item[$key];
					unset($item[$key]);
				}
			}
			$new_ary[]=$item;
		}
		return $new_ary;
	}
	//製作陣列中屬性與KEY值的對照,ex $ary['a']['key']=1,$ary['b']['key']=2 => array(1=>'a',2=>'b')
	public static function makeAryKeyValue($array,$_key)
	{
		$ary=array();
		foreach($array as $key=>$item)
		{
			$ary[$item[$_key]]=$key;
		}

		return $ary;
	}

    //二維陣列轉一維陣列
    public static function arraytwochangearrayone($array,$keyname,$name){
            $ary = array();
        foreach ($array as $val){
            $ary[$val[$keyname]] = $val[$name];
        }
        return $ary;
    }

	//將陣列的key值轉為2維陣列的某一屬性,ex $ary['a']=111,$ary['b']=2222 => array(0=>('key'=1,'name'=111),1=>('key'=2,'name'=222))
	public static function makeAryKeyToAryElement($array,$keyname,$elementname)
	{
		$ary=array();
		foreach($array as $key=>$item)
		{
			$ary[]=array($keyname=>$key,$elementname=>$item);
		}

		return $ary;
	}

	public static function getAryVal($ary,$key){
		if(isset($ary[$key])){
			return $ary[$key];
		}
		else{
			return '';
		}
	}

	//回傳位元運算後陣列KEY值包含在int裡面的陣列,ex:$int=3,$ary[1]='ary1';$ary[2]='ary2',$ary[4]='ary3'; 回傳array(array('key','name'))
	public static function getMatchKeyAry($ary,$int){
		$ary_result=array();
		foreach($ary as $key=>$item){
			if($int&$key){
				$ary_result[]=array('key'=>$key,'name'=>$item);
			}
		}
		return $ary_result;
	}
	//回傳位元運算後陣列KEY值包含在int裡面的名稱,ex:$int=3,$ary[1]='ary1';$ary[2]='ary2',$ary[4]='ary3'; 回傳ary1|ary2|ary3 ,$s可指定分隔字元
	public static function getMatchKeyName($ary,$int,$s='|',$power=false){ //190314 By yu add $power:是否將key變成2的key次方
		$str='';
		foreach($ary as $key=>$item){
			//190314 By yu ------(S)
			// if($int&$key){
			// 	$str.=$s.$item;
			// }
			if($int&($power==false? $key : 1<<$key)){
				$str.=$s.$item;
			}
			//190314 By yu ------(E)
		}
		return $str!='' ? substr($str, 1) : '';
	}
	public static function getStr(&$obj)
	{
		return isset($obj) ? $obj : '';
	}
	public static function getNum(&$obj)
	{
		return isset($obj) && is_numeric($obj)? (int)$obj : 0;
	}
	public static function getDate($date , $type="-"){
		global $null_date;
		return strtotime($date)==strtotime($null_date) ? '' : datetime("Y".$type."m".$type."d",$date);
	}

	public static function getDateTime($date){
		global $null_date;
		return strtotime($date)==strtotime($null_date) ? '' : datetime("Y-m-d H:i:s",$date);
	}

	public static function getifnull($str , $get=""){
		return $str==null || $str=="" || $str==="0" ? $get : $str;
	}


	public static function getDateInfo($startTime,$endTime){
		global $null_date;
		$str='';
		$nulldate=strtotime($null_date);
		$today=time();
		$start1=strtotime($startTime);
		$end1=strtotime($endTime);
		$d1=($today-$start1);
		$d2=($today-$end1);
		if($start1==$nulldate && $end1==$nulldate){
			return "未開始";
		}
		if($start1!=$nulldate && $end1!=$nulldate){

			if($d1>=0){ $str.= '進行中 ';}else{ $str.= "開始還有".round(($start1-$today)/3600/24)."天" ;};
			if($d2>=0){ $str= "已結束";}else{ $str.= "結束還有".round(($end1-$today)/3600/24)."天";};
		}else{
			if($start1!=$nulldate){
				if($d1>=0){ $str.= '進行中 ';}else{ $str.= "開始還有".round(($start1-$today)/3600/24).'天 ';};
			}else{
				if($d2>=0){ $str.= "已結束";}else{ $str.= "進行中,結束還有".round(($end1-$today)/3600/24)."天";};
			};
		};

		return $str;
	}
	public static function showrestseconds($startday,$endday,$show_Wday,$stime,$etime){//(開始日期,結束日期,獎勵星期,開始時間,結束時間)
	//計算獎勵距當天結束的剩下時間。未開始或已結束都傳回0,進行中則傳回剩餘秒數
	    $today=date("Y-m-d");
		$today_w=date("w");
		$today_h=date("H");
		$today_w2 = pow(2,($today_w-1));//轉次方(星期日開始為1,星期一2,三4......)
		$startday = ($endday!=0 && $startday==0)?$today:$startday;
		$endday = ($startday!=0 && $endday==0)?$today:$endday;
		if($today<$startday or $today>$endday){ //非活動期間
			return "0";
		}
		if(!($show_Wday & $today_w2) && !($show_Wday>$today_w2)){//活動期間但並非獎勵星期
			return "0";
		}
		if($today_h<$stime or $today_h>= $etime){//時間已過
			return "0";
		}
		return  mktime($etime,0,0)- strtotime("now");
	}
	public static function getDateInfo_boolean($startTime,$endTime){//若為進行中則傳回true，否則傳false
		global $null_date;
		$nulldate=strtotime($null_date);
		$today=time();
		$start1=strtotime($startTime);
		$end1=strtotime($endTime);
		$d1=($today-$start1);
		$d2=($today-$end1);
		if($start1==$nulldate && $end1==$nulldate){
			return false;
		}
		if($start1!=$nulldate && $end1!=$nulldate){
			if($d1>=0 && $d2<0){ return true;};
		}else{
			if($start1!=$nulldate){
				if($d1>=0){ return true;};
			}else{
				if($d2<0){ return true;};
			};
		};
		return false;
	}
	public static function array_diff_assoc_recursive($array1, $array2){
	    foreach($array1 as $key => $value){
	        if(is_array($value)){
	              if(!isset($array2[$key])){
	                  $difference[$key] = $value;
	              }elseif(!is_array($array2[$key])){
	                  $difference[$key] = $value;
	              }else{
	                  $new_diff = self::array_diff_assoc_recursive($value, $array2[$key]);
	                  if($new_diff != FALSE){
	                        $difference[$key] = $new_diff;
	                  }
	              }
	          }
	          elseif(!isset($array2[$key]) || $array2[$key] != $value){
	              $difference[$key] = $value;
	          }
	    }
	    return !isset($difference) ? 0 : $difference;
    }

    public static function canViewUser(){
        global $adminuser;
        return coderAdmin::isInAuth($adminuser['auth'],coderAdmin::$Auth['auth']['key'],coderAdmin::$Auth['auth']['list']['admin']['key'],coderAdminLog::$action['view']['key']);
    }
}
