<?php

class mysql
{

    private $db_host;       //数据库主机
    private $db_user;       //数据库登陆名
    private $db_pwd;        //数据库登陆密码
    private $db_name;       //数据库名
    private $db_charset;    //数据库字符编码
    private $db_pconn;      //长连接标识位
    private $debug;         //调试开启
    private $conn;          //数据库连接标识
    private $msg = "";      //数据库操纵信息

    //    private $sql = "";      //待执行的SQL语句

    public function __construct($db_host, $db_user, $db_pwd, $db_name, $db_chaeset = 'utf8', $db_pconn = false, $debug = false)
    {
        $this->db_host = $db_host;
        $this->db_user = $db_user;
        $this->db_pwd = $db_pwd;
        $this->db_name = $db_name;
        $this->db_charset = $db_chaeset;
        $this->db_pconn = $db_pconn;
        $this->result = '';
        $this->debug = $debug;
        $this->initConnect();
    }

    public function initConnect()
    {
        if ($this->db_pconn) {
            $this->conn = @mysql_pconnect($this->db_host, $this->db_user, $this->db_pwd);
        } else {
            $this->conn = @mysql_connect($this->db_host, $this->db_user, $this->db_pwd);
        }
        if ($this->conn) {
            $this->query("SET NAMES " . $this->db_charset);
        } else {
            $this->msg = "数据库连接出错，错误编号：" . mysql_errno() . "错误原因：" . mysql_error();
        }
        $this->selectDb($this->db_name);
    }

    public function selectDb($dbname)
    {
        if ($dbname == "") {
            $this->db_name = $dbname;
        }
        if (!@mysql_select_db($this->db_name, $this->conn)) {
            $this->msg = "数据库不可用";
        }
    }
    public function selectDbs($dbname)
    {
        if ($dbname == "") {
            $this->db_name = $dbname;
        }
        if (!@mysql_select_db($this->db_name, $this->conn)) {
         return    $this->msg = "数据库不可用";
        }
    }

    public function query($sql, $debug = false)
    {
        if (!$debug) {
            $this->result = @mysql_query($sql, $this->conn);
        } else {

        }
        if ($this->result == false) {
            $this->msg = "sql执行出错，错误编号：" . mysql_errno() . "错误原因：" . mysql_error();
        }
        //        var_dump($this->result);
    }

    public function select($tableName, $columnName = "*", $where = "")
    {
        $sql = "SELECT " . $columnName . " FROM " . $tableName;
        $sql .= $where ? " WHERE " . $where : null;
        $this->query($sql);
    }

    public function findAll($tableName)
    {
        $sql = "SELECT * FROM $tableName";
        $this->query($sql);
    }

    public function insert($tableName, $column = array())
    {
        $columnName = "";
        $columnValue = "";
        foreach ($column as $key => $value) {
            $columnName .= $key . ",";
            $columnValue .= "'" . $value . "',";
        }
        $columnName = substr($columnName, 0, strlen($columnName) - 1);
        $columnValue = substr($columnValue, 0, strlen($columnValue) - 1);
        $sql = "INSERT INTO $tableName($columnName) VALUES($columnValue)";
        $this->query($sql);
        if ($this->result) {
            $this->msg = "数据插入成功。新插入的id为：" . mysql_insert_id($this->conn);
        }
    }

    public function update($tableName, $column = array(), $where = "")
    {
        $updateValue = "";
        foreach ($column as $key => $value) {
            $updateValue .= $key . "='" . $value . "',";
        }
        $updateValue = substr($updateValue, 0, strlen($updateValue) - 1);
        $sql = "UPDATE $tableName SET $updateValue";
        $sql .= $where ? " WHERE $where" : null;
        $this->query($sql);
        if ($this->result) {
            $this->msg = "数据更新成功。受影响行数：" . mysql_affected_rows($this->conn);
        }
    }

    public function delete($tableName, $where = "")
    {
        $sql = "DELETE FROM $tableName";
        $sql .= $where ? " WHERE $where" : null;
        $this->query($sql);
        if ($this->result) {
            $this->msg = "数据删除成功。受影响行数：" . mysql_affected_rows($this->conn);
        }
    }

    public function fetchArray($result_type = MYSQL_BOTH)
    {
        $resultArray = array();
        $i = 0;
        while (@$result = mysql_fetch_array($this->result, $result_type)) {
            $resultArray[$i] = $result;
            $i++;
        }
        return $resultArray;
    }

    public function getcount($result_type = MYSQL_ASSOC)
    {
        $i = 0;
        while ($result = mysql_fetch_array($this->result, $result_type)) {
            $i++;
        }
        return $i;
    }

    //    public function fetchObject(){
    //        return mysql_fetch_object($this->result);
    //    }

    public function printMessage()
    {
        return $this->msg;
    }

    public function freeResult()
    {
        @mysql_free_result($this->result);
    }

    public function __destruct()
    {
        if (!empty($this->result)) {
            $this->freeResult();
        }
        @mysql_close($this->conn);
    }
}

?>