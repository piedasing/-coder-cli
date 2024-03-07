<?php 
class coderWeblog{
	public $linkpage,$srcweb,$srcdomain,$ip,$createtime;
	
	public function _construct(){
	}
	
	function getIP(){
		if (!empty($_SERVER['HTTP_CLIENT_IP']))
    	$ip=$_SERVER['HTTP_CLIENT_IP'];
		else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
    	$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		else
   	 $ip=$_SERVER['REMOTE_ADDR'];
		
		return $ip;
	}
	
	function getSrcweb(){
		if(!empty($_SERVER['HTTP_REFERER'])){
			$srcweb=$_SERVER['HTTP_REFERER'];
		}else{
			$srcweb="";
		}
		return $srcweb;
	}
	
	function getSrcdomain(){
		$srcweb=$this->getSrcweb();
		if(!empty($srcweb)){
			if(strpos($srcweb,"https://")!==false){
				$i= (str_replace('https://','',$srcweb));
				$j= strpos($i,"/");
				return substr($i,0,$j);
			}else if(strpos($srcweb,"http://")!==false){
				$i= (str_replace('http://','',$srcweb));
				$j= strpos($i,"/");
				return substr($i,0,$j);
			}
		}else{
			return "";
		}
	}
	
	public function getWeblog(){
		global $db,$table_weblog;
		
		if(empty($_SESSION["log"])){
			$_SESSION["log"]=$this->getIP();
			
			$data["linkpage"]="index.php";
			$data["srcweb"]=$this->getSrcweb();
			$data["srcdomain"]=$this->getSrcdomain();
			$data["ip"]=$this->getIP();
			$data["createtime"]=request_cd();
			
			$db->query_insert($table_weblog,$data);
		}
	}
	
}
?>