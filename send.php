<?php
error_reporting(E_ALL ^ E_NOTICE);
ini_set('date.timezone', 'Asia/Shanghai');
header("Content-type:text/Json;charset=utf-8");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods:GET,POST");
require_once 'Classes/PHPExcel.php';
require_once 'Classes/PHPExcel/IOFactory.php';
require_once 'Classes/PHPExcel/Reader/Excel5.php';
require_once 'Classes/PHPExcel/Reader/IReader.php';
require_once 'Classes/PHPExcel/Reader/IReader.php';

ini_set("display_errors","On");
error_reporting(E_ALL);
//set_time_limit(300);

$mysql="mysql";
$host="localhost";
$name="root";
$pass="root";
$table1="user";

$conn=mysqli_connect($host,$name,$pass);
$res=mysqli_select_db($conn,$table1); //选择数据库

//var_dump($res);
mysqli_query($conn,"SET NAMES utf8");//解决中文乱码问题

//require_once $filename='/alidata/www/default/smdc/excel/1.xlsx';

//Excel5  xls文件 Excel2007 xlsx 文件
//PHP Fatal error:  Uncaught excepti on 'PHPExcel_Reader_Exception' with message 'The filename /alidata/www/default/smdc/excel/1.xls is not recognised as an OLE file' in
$objReader = PHPExcel_IOFactory::createReader('Excel2007');//use excel2007 for 2007 format

$objReader1 = new PHPExcel_Reader_Excel5();///yjdata/www/www/nwx.bilalipay.com/web/excel/
$objPHPExcel = $objReader->load('/yjdata/www/www/nwx.bilalipay.com/web/excel/aa.xlsx'); //$filename可以是上传的文件，或者是指定的文件

$sheet = $objPHPExcel->getSheet(0);
$highestRow = $sheet->getHighestRow(); // 取得总行数
$highestColumn = $sheet->getHighestColumn(); // 取得总列数
$k = 0;

//循环读取excel文件,读取一条,插入一条  芙蓉楼批量导入数据库
for($j=2;$j<=$highestRow;$j++)
{
   // $a = $objPHPExcel->getActiveSheet()->getCell("A".$j)->getValue();//获取A列的值
    $b = $objPHPExcel->getActiveSheet()->getCell("B".$j)->getValue();//获取B列的值
    $c = $objPHPExcel->getActiveSheet()->getCell("C".$j)->getValue();//获取B列的值
   //获取B列的值
    $d = $objPHPExcel->getActiveSheet()->getCell("D".$j)->getValue();
    //获取B列的值
    $e = $objPHPExcel->getActiveSheet()->getCell("E".$j)->getValue()? $objPHPExcel->getActiveSheet()->getCell("E".$j)->getValue():0;
    //获取B列的值
    $f = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue() ?$objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue():0;
    //获取B列的值
    $g = $objPHPExcel->getActiveSheet()->getCell("G".$j)->getValue();
    //获取B列的值
    $h = $objPHPExcel->getActiveSheet()->getCell("H".$j)->getValue();
    $i = $objPHPExcel->getActiveSheet()->getCell("I".$j)->getValue();
    $ja =$objPHPExcel->getActiveSheet()->getCell("J".$j)->getValue()?$objPHPExcel->getActiveSheet()->getCell("J".$j)->getValue():0;
    $k = $objPHPExcel->getActiveSheet()->getCell("K".$j)->getValue() ?$objPHPExcel->getActiveSheet()->getCell("K".$j)->getValue():0;
    $l = $objPHPExcel->getActiveSheet()->getCell("L".$j)->getValue();
    $m = $objPHPExcel->getActiveSheet()->getCell("M".$j)->getValue();
    $n = $objPHPExcel->getActiveSheet()->getCell("N".$j)->getValue();
    $o = $objPHPExcel->getActiveSheet()->getCell("O".$j)->getValue();
    $p = $objPHPExcel->getActiveSheet()->getCell("P".$j)->getValue()?$objPHPExcel->getActiveSheet()->getCell("P".$j)->getValue():0;$q = $objPHPExcel->getActiveSheet()->getCell("Q".$j)->getValue();
    $r = $objPHPExcel->getActiveSheet()->getCell("R".$j)->getValue();
    $s = $objPHPExcel->getActiveSheet()->getCell("S".$j)->getValue();

   $sql = "INSERT INTO tbl_old_user VALUES('','92','0','0','$e','$f','0','0','0','$ja','$k','0','0','0','0','$p','0','0','0')";
   // mysqli_query($conn,$sql);
}