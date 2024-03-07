<?php
class coderSelectHelp {
    public $sql_type = "mysql";
    public $select = "";
    public $table = "";
    public $orderby = "";
    public $orderdesc = "desc";
    public $where = "";
    public $whereary = [];
    public $groupby = "";
    public $having = "";
    public $page = "1";
    public $page_size = 10; //-1為不分頁
    public $count = 0;
    public $db;
    public $page_info = null;
    private $sql;
    public $orderbyadd = ""; //可新增其他排序條件
    public function __construct($db) {
        $this->db = $db;
    }
    public function getList() {
        return $this->get_List();

        // if($this->page_size==0 || empty($this->page_size)){
        // 	$this->page_size=10;
        // }
        // $sql=$this->getPageSQL();
        // // echo $sql;exit;
        // $row=$this->db->fetch_all_array($sql);

        // return $row;

    }

    function get_List() { // mysql可用
        if($this->page_size == 0 || empty($this->page_size)) {
            $this->page_size = 10;
        }
        $sql = $this->get_PageSQL();
        // echo $sql;exit;
        $row = $this->db->fetch_all_array($sql, $this->whereary);
        return $row;
    }

    private function getPageSQL(){
        $sql='SELECT '.$this->select.' FROM '.$this->table.$this->getWhere();
        $this->count=$this->db->queryCount($sql);
        //$this->count=0;
        $mypage=$this->getPageInfo($this->page, $this->count, $this->page_size);
        $this->page_info=$mypage;
        if($this->page_size > 0){
            switch($this->sql_type){
                case "mssql" :
                    $sql='SELECT * FROM ( SELECT  ROW_NUMBER() OVER ('.$this->getOrder().') AS RowNum,
                    '.$this->select.' FROM '.$this->table.' '.$this->getWhere().' '.'
                    ) as temp1 WHERE RowNum > '.$mypage['begin'].' AND RowNum<='.($mypage['begin']+$this->page_size).'
                    ';
                break;
                case "mysql" :
                    $sql.=$this->getOrder().' limit '.$mypage['begin'].','.$this->page_size;
                break;
            }
        }
        else{
            $sql.=$this->getOrder();
        }

        return $sql;
    }

    private function get_PageSQL() {
        $sql = 'SELECT ' . $this->select . ' FROM ' . $this->table . $this->getWhere();
        $this->count = $this->db->queryCount($sql, $this->whereary);
        //$this->count=0;
        $mypage = $this->getPageInfo($this->page, $this->count, $this->page_size);

        // $this->whereary[':pagebegin'] = $mypage['begin'];
        // $this->whereary[':pagesize'] = $this->page_size;
        $this->page_info = $mypage;

        if ($this->page_size > 0) {
            switch($this->sql_type) {
                case "mssql" :
                    $sql = 'SELECT * FROM ( SELECT  ROW_NUMBER() OVER (' . $this->getOrder() . ') AS RowNum,
                    '.$this->select.' FROM '.$this->table . ' ' . $this->getWhere() . ' ' . '
                    ) as temp1 WHERE RowNum > ' . $mypage['begin'].' AND RowNum<=' . ($mypage['begin'] + $this->page_size) . '
                    ';
                break;
                case "mysql" :
                    // $sql .= $this->getOrder() . ' limit :pagebegin, :pagesize';
                    $sql.=$this->getOrder().' limit ' . intval($mypage['begin']) . ',' . intval($this->page_size);
                break;
            }
        } else {
            $sql .= $this->getOrder();
        }

        return $sql;
    }

    private function getWhere() {
        return (($this->where != "") ? ' WHERE ' . $this->where : '') . ' 
            ' . (($this->groupby != "") ? ' GROUP BY ' . $this->groupby : '') . ' 
            ' . (($this->having != "") ? ' HAVING ' . $this->having : '');
    }
    private function getOrder() {
        if ($this->orderby != "" && $this->orderbyadd != "") {
            $orderby = $this->orderby;
            $orderdesc = strtolower($this->orderdesc) === 'desc' ? 'desc' : 'asc';
            return ' order by ' . $orderby . ' ' . $orderdesc . ' ' . $this->orderbyadd;
        } else if ($this->orderby != "" && $this->orderbyadd == "") {
            $orderby = $this->orderby;
            $orderdesc = strtolower($this->orderdesc) === 'desc' ? 'desc' : 'asc';
            return ' order by ' . $orderby . ' ' . $orderdesc . ' ';
        }
    }
    private function getPageInfo($page, $totalrows, $show_num = 10, $num_page = 10){
        $page = (int)$page;
        $show_num = (int)$show_num;
        $totalrows = (int)$totalrows;
        if ($show_num < 10) {
            $show_num = 10;
        }
        if ($totalrows > 0) {
            $pagecount = ceil($totalrows / $show_num);
        } else {
            $pagecount = 1;
            $totalrows = 0;
        }
        if ($page < 1) {
            $page = 1;
        } else if ($page > $pagecount) {
            $page = $pagecount;
        }
    
        $sno = (int)($num_page / 2) - 1;
        $eno = $sno * 2 + 1;
        $s_start = $page - $sno;
        if ($s_start < 1) {
            $s_start = 1;
        }
    
        $s_end = $s_start + $eno;
        if ($s_end > $pagecount) {
            $s_end = $pagecount;
        }
    
        $mypage = array();
        $mypage["count"] = $totalrows;       //資料筆數
        $mypage["page"] = $page;            //目前頁數
        $mypage["pagecount"] = $pagecount;  //總頁數
        $mypage["s_start"] = $s_start;      //分頁起點
        $mypage["s_end"] = $s_end;          //分頁終點
        $mypage["begin"] = ($page - 1) * $show_num; //limit 分頁起點
        $mypage["show_num"] = $show_num;   //每頁筆數

        return $mypage;
    }
}

?>
