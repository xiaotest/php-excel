<?php
require_once dirname(__DIR__).'/common/includes.php';
$logHandler= new CLogFileHandler(dirname(__DIR__)."/logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

$data=array(
    'name'=>'xixming',
    'age'=>'67'
);

//847566293460  42073
//116676472159  19804
//419607105860   6252
//911294401367    9000
$d=date('m');
if($d=='07' || $d=='17' ||$d=='27'){

}
echo  $d;
exit;
Log::DEBUG('查询'.json_encode($data));
echo Helpfunction::formatQueryParaMap($data);
//只限5月使用\n菜金每满100元可用1张\n每桌最多可叠加使用2张\n本劵不退、不兑换饮料、\n烟酒、茶位、土特产等不可用\n不与其他优惠同享\n优惠部分不开发票\n