<?php
namespace app\index\controller;

use think\Controller;
// use think\Request;
use think\Db;

class Main extends Controller
{
    public function hydraulic()
    {
        // $refresh=$_REQUEST['refresh'];
        $refresh=input("post.refresh");
        if(empty($refresh)){$refresh=1;}

        // 获取数据库对象在进行操作
        $code = Db::table("table_deviceinfomation")->where('device_type','液压支架组')->value('device_code');

        if(empty($code)) 
        {
            echo"当前未添加液压支架设备";
            return;
        }//防止未添加设备导致网页崩溃

        $tempStr = explode("_",$code);
        // var_dump($tempStr);
        $support_num = $tempStr[1];//获取支架数量
        $this->assign('support_num',$support_num);

        $para = Db::table("table_signalconnect_support")->value("para");
        if(empty($para))
        {
            echo"液压支架未进行信号连接，请先做信号连接";
            return;
        }//防止未进行信号连接导致网页崩溃

        $com = Db::table("table_deviceinfomation")->where('device_type','液压支架组')->value('com');
        
        //读取数据库
        $supportdb = "table_signalconnect_support";
        $db = "table_device_realtimedata_".$com."_device".$code;

        //压力1 pressure1
        $pressure1data = Db::table($supportdb)->where('control',"压力1")->value('name_en');
        // var_dump($Pressure1data);
        $pressure1 = Db::table($db)->column($pressure1data);

        // 压力2 pressure2
        $pressure2data = Db::table($supportdb)->where('control',"压力2")->value('name_en');
        $pressure2 = Db::table($db)->column($pressure2data);

        // 煤机位置 Coal machine position
        $positiondata = Db::table($supportdb)->where('control',"煤机位置")->value('name_en');
        $position = Db::table($db)->value($positiondata);
        $getPositionByData = strval($position*86/$support_num)."vw";

        // 地址重复标志 address
        $addressdata = Db::table($supportdb)->where('control',"地址重复标志")->value('name_en');
        $addressArray = Db::table($db)->column($addressdata);

        for ($i=0;$i < count($addressArray);$i++) {
            if ($addressArray[$i] == '--'||$addressArray[$i] == '0') {
                $addressArray[$i] = '10';
            }
            elseif ($addressArray[$i] == '1') {
                $addressArray[$i] = '0';
            }
            $address[] = [0,$i,$addressArray[$i]-'0'];
            // var_dump($Address[$i]);
        }

        // 电磁阀故障 electromagnetic
        $electromagneticdata = Db::table($supportdb)->where('control',"电磁阀故障")->value('name_en');
        $electromagneticArray = Db::table($db)->column($electromagneticdata);

        for ($i=0;$i < count($electromagneticArray);$i++) {
            if ($electromagneticArray[$i] == '--'||$electromagneticArray[$i] == '0') {
                $electromagneticArray[$i] = '20';
            }
            elseif ($electromagneticArray[$i] == '1') {
                $electromagneticArray[$i] = '0';
            }
            $electromagnetic[] = [1,$i,$electromagneticArray[$i]-'0'];
        }

        // 传感器故障 sensor
        $sensordata = Db::table($supportdb)->where('control',"传感器故障")->value('name_en');
        $sensorArray = Db::table($db)->column($sensordata);

        for ($i=0;$i < count($sensorArray);$i++) {
            if ($sensorArray[$i] == '--'||$sensorArray[$i] == '0') {
                $sensorArray[$i] = '30';
            }
            elseif ($sensorArray[$i] == '1') {
                $sensorArray[$i] = '0';
            }
            $sensor[] = [2,$i,$sensorArray[$i]-'0'];
        }

        // 过流故障 overcurrent
        $overcurrentdata = Db::table($supportdb)->where('control',"过流故障")->value('name_en');
        $overcurrentArray = Db::table($db)->column($overcurrentdata);

        for ($i=0;$i < count($overcurrentArray);$i++) {
            if ($overcurrentArray[$i] == '--'||$overcurrentArray[$i] == '0') {
                $overcurrentArray[$i] = '40';
            }
            elseif ($overcurrentArray[$i] == '1') {
                $overcurrentArray[$i] = '0';
            }
            $overcurrent[] = [3,$i,$overcurrentArray[$i]-'0'];
        }

        // 过滤器故障 filter
        $filterdata = Db::table($supportdb)->where('control',"过滤器故障")->value('name_en');
        $filterArray = Db::table($db)->column($filterdata);

        for ($i=0;$i < count($filterArray);$i++) {
            if ($filterArray[$i] == '--'||$filterArray[$i] == '0') {
                $filterArray[$i] = '50';
            }
            elseif ($filterArray[$i] == '1') {
                $filterArray[$i] = '0';
            }
            $filter[] = [4,$i,$filterArray[$i]-'0'];
        }

        // 急停状态 emergencyStop
        $emergencyStopdata = Db::table($supportdb)->where('control',"急停状态")->value('name_en');
        $emergencyStopArray = Db::table($db)->column($emergencyStopdata);

        for ($i=0;$i < count($emergencyStopArray);$i++) {
            if ($emergencyStopArray[$i] == '--'||$emergencyStopArray[$i] == '0') {
                $emergencyStopArray[$i] = '60';
            }
            elseif ($emergencyStopArray[$i] == '1') {
                $emergencyStopArray[$i] = '0';
            }
            $emergencyStop[] = [5,$i,$emergencyStopArray[$i]-'0'];
        }

        // 锁闭状态 locked
        $lockeddata = Db::table($supportdb)->where('control',"锁闭状态")->value('name_en');
        $lockedArray = Db::table($db)->column($lockeddata);

        for ($i=0;$i < count($lockedArray);$i++) {
            if ($lockedArray[$i] == '--'||$lockedArray[$i] == '0') {
                $lockedArray[$i] = '70';
            }
            elseif ($lockedArray[$i] == '1') {
                $lockedArray[$i] = '0';
            }
            $locked[] = [6,$i,$lockedArray[$i]-'0'];
        }

        $refresh=input("post.refresh");
        if(empty($refresh)){
        $this->assign('pressure1',$pressure1);
        $this->assign('pressure2',$pressure2);
        $this->assign('position',$position);
        $this->assign('getPositionByData',$getPositionByData);
        $this->assign('address',$address);
        $this->assign('electromagnetic',$electromagnetic);
        $this->assign('sensor',$sensor);
        $this->assign('overcurrent',$overcurrent);
        $this->assign('filter',$filter);        
        $this->assign('emergencyStop',$emergencyStop);
        $this->assign('locked',$locked);
        }
        else{
            $all['pressure1']         = $pressure1;
            $all['pressure2']         = $pressure2;
            $all['position']          = $position;
            $all['getPositionByData'] = $getPositionByData;
            $all['address']           = $address;
            $all['electromagnetic']   = $electromagnetic;
            $all['sensor']            = $sensor;
            $all['overcurrent']       = $overcurrent;
            $all['filter']            = $filter;
            $all['emergencyStop']     = $emergencyStop;
            $all['locked']            = $locked;
            $all['refresh']           = $refresh;

            // $this->ajaxReturn($all);
            return json($all);
        }

        // 加载页面
        return $this->fetch();
    }

    public function alarm()
    {
        // $alarmUpdata=1;
        $alarmUpdata=input("post.alarmUpdata");
        // 获取数据库对象在进行操作
        $code = Db::table("table_deviceinfomation")->where('device_type','液压支架组')->value('device_code');
        $com = Db::table("table_deviceinfomation")->where('device_type','液压支架组')->value('com');
        //读取数据库
        $alarmdb = 'table_device_alarmdisplay_'.$com;
        $alarmdata = Db::table($alarmdb)->select();
        $numb = count($alarmdata);
        if (empty($alarmUpdata)) {
            $alarmUpdata = 1;
            $this->assign("numb",$numb);
            $this->assign('alarmdata',$alarmdata);
        }
        else{
            $data['numb'] = $numb;
            $data['alarmdata'] = $alarmdata;
            $data['alarmUpdata'] = $alarmUpdata;
            return json($data);
        }      

        return $this->fetch();
    }

    public function liquid()
    {
        $liquid = input("post.liquid");
        // 获取数据库对象在进行操作
        $code = Db::table("table_deviceinfomation")->where('device_type','乳化泵')->value('device_code');
        
        if (empty($code)) {
            echo"当前未添加乳化泵设备";
            return;
        }//防止未添加设备导致网页崩溃

        $para = Db::table("table_signalconnect_pump")->value('para');
        if(empty($para))
        {
            echo"乳化泵未进行信号连接，请先做信号连接";
            return;
        }//防止未进行信号连接导致网页崩溃

        $com = Db::table("table_deviceinfomation")->where('device_type','乳化泵')->value('com');

        //读取数据库
        $pumpdb = "table_signalconnect_pump";
        $db = "table_device_realtimedata_".$com."_device".$code;
        $pumphisdb = "table_system_historydata_".$com."_device".$code;
        // $dbtest = "iot_data";

        /* 液泵工作表数据 */
        //1#泵油压力 Oil pressure
        $oilpres1data = Db::table($pumpdb)->where('control',"1#泵油压力")->value('name_en');
        $oilpres1 = implode(Db::table($db)->column($oilpres1data));     
        //1#泵油温度 Oil temperature
        $oiltemp1data = Db::table($pumpdb)->where('control',"1#泵油温度")->value('name_en');
        $oiltemp1 = implode(Db::table($db)->column($oiltemp1data));      
        //1#泵蓄能器 Accumulator
        $accumulator1data = Db::table($pumpdb)->where('control',"1#泵蓄能器")->value('name_en');
        $accumulator1 = implode(Db::table($db)->column($accumulator1data));        
        //1#泵油位 Oil level
        $oillevel1data = Db::table($pumpdb)->where('control',"1#泵油位")->value('name_en');
        $oillevel1 = implode(Db::table($db)->column($oillevel1data));
        
        //2#泵油压力
        $oilpres2data = Db::table($pumpdb)->where('control',"2#泵油压力")->value('name_en');
        $oilpres2 = implode(Db::table($db)->column($oilpres2data));
        //2#泵油温度
        $oiltemp2data = Db::table($pumpdb)->where('control',"2#泵油温度")->value('name_en');
        $oiltemp2 = implode(Db::table($db)->column($oiltemp2data));
        //2#泵蓄能器
        $accumulator2data = Db::table($pumpdb)->where('control',"2#泵蓄能器")->value('name_en');
        $accumulator2 = implode(Db::table($db)->column($accumulator2data));
        //2#泵油位
        $oillevel2data = Db::table($pumpdb)->where('control',"2#泵油位")->value('name_en');
        $oillevel2 = implode(Db::table($db)->column($oillevel2data));

        //3#泵油压力
        $oilpres3data = Db::table($pumpdb)->where('control',"3#泵油压力")->value('name_en');
        $oilpres3 = implode(Db::table($db)->column($oilpres3data));       
        //3#泵油温度
        $oiltemp3data = Db::table($pumpdb)->where('control',"3#泵油温度")->value('name_en');
        $oiltemp3 = implode(Db::table($db)->column($oiltemp3data));
        //3#泵蓄能器
        $accumulator3data = Db::table($pumpdb)->where('control',"3#泵蓄能器")->value('name_en');
        $accumulator3 = implode(Db::table($db)->column($accumulator3data));
        //3#泵油位
        $oillevel3data = Db::table($pumpdb)->where('control',"3#泵油位")->value('name_en');
        $oillevel3 = implode(Db::table($db)->column($oillevel3data));

        //当前系统压力
        $temp_pres = Db::table($pumpdb)->where('control',"系统压力")->value('name_en');
        $pres = implode(Db::table($db)->column($temp_pres));
        if($pres == '--')
            $pres = 0;
        //当前液位
        $temp_level = Db::table($pumpdb)->where('control',"配液箱液位")->value('name_en');
        $level = implode(Db::table($db)->column($temp_level));
        if($level == '--')
            $level = 0;
        //当前浓度
        $temp_conc = Db::table($pumpdb)->where('control',"乳化液浓度")->value('name_en');
        $conc = implode(Db::table($db)->column($temp_conc));
        if($conc == '--')
            $conc = 0;


        /* 折线图数据 */
        //系统压力 System pressure
        $systempresdata = Db::table($pumpdb)->where('control',"系统压力")->value('name_en');
        $systempresArray = Db::table($pumphisdb)->field('datetime,'.$systempresdata)->order("id")->select();
        if (empty($systempresArray)) {
            $systempresArray = array([
                'datetime'=>'2020-01-01 00:00:00',
                $systempresdata=>'0',
            ]);
        }//防止历史数据表中没数据导致页面崩溃

        // for ($i=0;$i < count($systempresArray);$i++) {
        //     $systempres[] = [$systempresArray[$i]['datetime'],$systempresArray[$i][$systempresdata]];
        // }
        if (count($systempresArray)<=30) {
            $Time = $systempresArray[0]['datetime'];
            for ($i=0;$i < count($systempresArray);$i++) {
                if($systempresArray[$i][$systempresdata] == "--")
                {
                    $systempresArray[$i][$systempresdata] = 0;
                }
            $systempres[] = [$systempresArray[$i]['datetime'],$systempresArray[$i][$systempresdata]];
        }
        }
        else{
            $Time = $systempresArray[count($systempresArray)-30]['datetime'];
            for ($i=count($systempresArray)-30;$i < count($systempresArray);$i++) {
               if($systempresArray[$i][$systempresdata] == "--")
                {
                    $systempresArray[$i][$systempresdata] = 0;
                } 
            $systempres[] = [$systempresArray[$i]['datetime'],$systempresArray[$i][$systempresdata]];
        }
        }
        // var_dump($systempres);

        //配液箱液位 Liquid tank level
        $tankleveldata = Db::table($pumpdb)->where('control',"配液箱液位")->value('name_en');
        $tanklevelArray = Db::table($pumphisdb)->field('datetime,'.$tankleveldata)->order("id")->select();
        if (empty($tanklevelArray)) {
            $tanklevelArray = array([
                'datetime'=>'2020-01-01 00:00:00',
                $tankleveldata=>'0',
            ]);
        }//防止历史数据表中没数据导致页面崩溃

        if (count($tanklevelArray)<=30) {
            for ($i=0; $i < count($tanklevelArray); $i++) { 
                if($tanklevelArray[$i][$tankleveldata] == "--")
                {
                    $tanklevelArray[$i][$tankleveldata] = 0;
                }
                $tanklevel[] = [$tanklevelArray[$i]['datetime'],$tanklevelArray[$i][$tankleveldata]];
            }
        }
        else{
            for ($i=count($tanklevelArray)-30; $i < count($tanklevelArray); $i++) { 
                if($tanklevelArray[$i][$tankleveldata] == "--")
                {
                    $tanklevelArray[$i][$tankleveldata] = 0;
                }
                $tanklevel[] = [$tanklevelArray[$i]['datetime'],$tanklevelArray[$i][$tankleveldata]];
            }
        }

        //乳化液浓度 Emulsion concentration
        $emulsionconcentdata = Db::table($pumpdb)->where('control',"乳化液浓度")->value('name_en');
        $emulsionconcentArray = Db::table($pumphisdb)->field('datetime,'.$emulsionconcentdata)->order("id")->select();
        if (empty($emulsionconcentArray)) {
            $emulsionconcentArray = array([
                'datetime'=>'2020-01-01 00:00:00',
                $emulsionconcentdata=>'0',
            ]);
        }//防止历史数据表中没数据导致页面崩溃

        if (count($emulsionconcentArray)<=30) {
            for ($i=0; $i < count($emulsionconcentArray); $i++) { 
                if($emulsionconcentArray[$i][$emulsionconcentdata] == "--")
                {
                    $emulsionconcentArray[$i][$emulsionconcentdata] = 0;
                }
                $emulsionconcent[] = [$emulsionconcentArray[$i]['datetime'],$emulsionconcentArray[$i][$emulsionconcentdata]];
            }
        }
        else{
            for ($i=count($emulsionconcentArray)-30; $i < count($emulsionconcentArray); $i++) { 
                if($emulsionconcentArray[$i][$emulsionconcentdata] == "--")
                {
                    $emulsionconcentArray[$i][$emulsionconcentdata] = 0;
                }
                $emulsionconcent[] = [$emulsionconcentArray[$i]['datetime'],$emulsionconcentArray[$i][$emulsionconcentdata]];
            }
        }


        /* 工作状态面板数据 */
        //乳化液状态 Emulsion state
        $emulsionstatedata = Db::table($pumpdb)->where('control',"乳化液状态")->value('name_en');
        $emulsionstate = Db::table($db)->where('id',1)->value($emulsionstatedata);
        if ($emulsionstate == '1') {$emulsionStr = "液位过低";}
        elseif ($emulsionstate == '2') {$emulsionStr = "正在配液";}
        elseif ($emulsionstate == '3') {$emulsionStr = "液位正常";}
        elseif ($emulsionstate == '--') {$emulsionStr = "缺少数据";}
        else {$emulsionStr = "数据异常";}

        //1#泵状态 pump1 status
        $pump1statusdata = Db::table($pumpdb)->where('control',"1#泵状态")->value('name_en');
        $pump1status = Db::table($db)->where('id',1)->value($pump1statusdata);
        if ($pump1status == '--'||$pump1status == '0') {$pump1Str = "缺少数据";}
        elseif ($pump1status == '1') {$pump1Str = "变频运行";}
        elseif ($pump1status == '2') {$pump1Str = "工频运行";}
        elseif ($pump1status == '3') {$pump1Str = "吸空保护";}
        elseif ($pump1status == '4') {$pump1Str = "爆管保护";}
        elseif ($pump1status == '5') {$pump1Str = "油温保护";}
        elseif ($pump1status == '6') {$pump1Str = "油压保护";}
        elseif ($pump1status == '7') {$pump1Str = "油位保护";}
        else {$pump1Str = "数据异常";}

        //2#泵状态 pump2 status
        $pump2statusdata = Db::table($pumpdb)->where('control',"2#泵状态")->value('name_en');
        $pump2status = Db::table($db)->where('id',1)->value($pump2statusdata);
        if ($pump2status == '--'||$pump2status == '0') {$pump2Str = "缺少数据";}
        elseif ($pump2status == '1') {$pump2Str = "变频运行";}
        elseif ($pump2status == '2') {$pump2Str = "工频运行";}
        elseif ($pump2status == '3') {$pump2Str = "吸空保护";}
        elseif ($pump2status == '4') {$pump2Str = "爆管保护";}
        elseif ($pump2status == '5') {$pump2Str = "油温保护";}
        elseif ($pump2status == '6') {$pump2Str = "油压保护";}
        elseif ($pump2status == '7') {$pump2Str = "油位保护";}
        else {$pump2Str = "数据异常";}


        //3#泵状态 pump3 status
        $pump3statusdata = Db::table($pumpdb)->where('control',"3#泵状态")->value('name_en');
        $pump3status = Db::table($db)->where('id',1)->value($pump3statusdata);
        if ($pump3status == '--'||$pump3status == '0') {$pump3Str = "缺少数据";}
        elseif ($pump3status == '1') {$pump3Str = "变频运行";}
        elseif ($pump3status == '2') {$pump3Str = "工频运行";}
        elseif ($pump3status == '3') {$pump3Str = "吸空保护";}
        elseif ($pump3status == '4') {$pump3Str = "爆管保护";}
        elseif ($pump3status == '5') {$pump3Str = "油温保护";}
        elseif ($pump3status == '6') {$pump3Str = "油压保护";}
        elseif ($pump3status == '7') {$pump3Str = "油位保护";}
        else {$pump3Str = "数据异常";}
 

        if (empty($liquid)) {
            $liquid = 1;
            $this->assign('oilpres1',$oilpres1);
            $this->assign('oiltemp1',$oiltemp1);
            $this->assign('accumulator1',$accumulator1);
            $this->assign('oillevel1',$oillevel1);

            $this->assign('oilpres2',$oilpres2);
            $this->assign('oiltemp2',$oiltemp2);
            $this->assign('accumulator2',$accumulator2);
            $this->assign('oillevel2',$oillevel2);

            $this->assign('oilpres3',$oilpres3);
            $this->assign('oiltemp3',$oiltemp3);
            $this->assign('accumulator3',$accumulator3);
            $this->assign('oillevel3',$oillevel3);

            $this->assign('systempres',$systempres);
            $this->assign('Time',$Time);
            $this->assign('tanklevel',$tanklevel);
            $this->assign('emulsionconcent',$emulsionconcent);

            $this->assign('emulsionStr',$emulsionStr);
            $this->assign('pump1Str',$pump1Str);
            $this->assign('pump2Str',$pump2Str);
            $this->assign('pump3Str',$pump3Str);

            $this->assign('pres',$pres);
            $this->assign('level',$level);
            $this->assign('conc',$conc);
        }
        else{
            $data['oilpres1'] = $oilpres1;
            $data['oiltemp1'] = $oiltemp1;
            $data['accumulator1'] = $accumulator1;
            $data['oillevel1'] = $oillevel1;

            $data['oilpres2'] = $oilpres2;
            $data['oiltemp2'] = $oiltemp2;
            $data['accumulator2'] = $accumulator2;
            $data['oillevel2'] = $oillevel2;

            $data['oilpres3'] = $oilpres3;
            $data['oiltemp3'] = $oiltemp3;
            $data['accumulator3'] = $accumulator3;
            $data['oillevel3'] = $oillevel3;

            $data['systempres'] = $systempres;
            $data['Time'] = $Time;
            $data['tanklevel'] = $tanklevel;
            $data['emulsionconcent'] = $emulsionconcent;

            $data['emulsionStr'] = $emulsionStr;
            $data['pump1Str'] = $pump1Str;
            $data['pump2Str'] = $pump2Str;
            $data['pump3Str'] = $pump3Str;

            $data['pres'] = $pres;
            $data['level'] = $level;
            $data['conc'] = $conc;
            return json($data);
        }
        //加载页面
        return $this->fetch();
    }

    public function power()
    {
        return $this->fetch();
    }

    public function hydraulic_monitor()
    {
        $refresh=1;
        $refresh=input("post.refresh");
        // $refresh=$_REQUEST['refresh'];

        // 获取数据库对象在进行操作
        $code = Db::table("table_deviceinfomation")->where('device_type','液压支架组')->value('device_code');
        $tempStr = explode("_", $code);
        $support_num = $tempStr[1];

        $com = Db::table("table_deviceinfomation")->where('device_type','液压支架组')->value('com');
       
        //读取数据库
        $supportdb = "table_signalconnect_support";
        $db = "table_device_realtimedata_".$com."_device".$code;

        $para = Db::table($supportdb)->value("para");

        //压力1 pressure1
        $pressure1data = Db::table($supportdb)->where('control',"压力1")->value('name_en');
        $pressure1 = Db::table($db)->column($pressure1data);

        // 压力2 pressure2
        $pressure2data = Db::table($supportdb)->where('control',"压力2")->value('name_en');
        $pressure2 = Db::table($db)->column($pressure2data);

        // 煤机位置 Coal machine position
        $positiondata = Db::table($supportdb)->where('control',"煤机位置")->value('name_en');
        $position = Db::table($db)->value($positiondata);
        $getPositionByData = strval($position*86/$support_num)."vw";

        // 地址重复标志 address
        $addressdata = Db::table($supportdb)->where('control',"地址重复标志")->value('name_en');
        $addressArray = Db::table($db)->column($addressdata);

        for ($i=0;$i < count($addressArray);$i++) {
            if ($addressArray[$i] == '--'||$addressArray[$i] == '0') {
                $addressArray[$i] = '10';
            }
            elseif ($addressArray[$i] == '1') {
                $addressArray[$i] = '0';
            }
            $address[] = [0,$i,$addressArray[$i]-'0'];
            // var_dump($Address[$i]);
        }

        // 电磁阀故障 electromagnetic
        $electromagneticdata = Db::table($supportdb)->where('control',"电磁阀故障")->value('name_en');
        $electromagneticArray = Db::table($db)->column($electromagneticdata);

        for ($i=0;$i < count($electromagneticArray);$i++) {
            if ($electromagneticArray[$i] == '--'||$electromagneticArray[$i] == '0') {
                $electromagneticArray[$i] = '20';
            }
            elseif ($electromagneticArray[$i] == '1') {
                $electromagneticArray[$i] = '0';
            }
            $electromagnetic[] = [1,$i,$electromagneticArray[$i]-'0'];
        }

        // 传感器故障 sensor
        $sensordata = Db::table($supportdb)->where('control',"传感器故障")->value('name_en');
        $sensorArray = Db::table($db)->column($sensordata);

        for ($i=0;$i < count($sensorArray);$i++) {
            if ($sensorArray[$i] == '--'||$sensorArray[$i] == '0') {
                $sensorArray[$i] = '30';
            }
            elseif ($sensorArray[$i] == '1') {
                $sensorArray[$i] = '0';
            }
            $sensor[] = [2,$i,$sensorArray[$i]-'0'];
        }

        // 过流故障 overcurrent
        $overcurrentdata = Db::table($supportdb)->where('control',"过流故障")->value('name_en');
        $overcurrentArray = Db::table($db)->column($overcurrentdata);

        for ($i=0;$i < count($overcurrentArray);$i++) {
            if ($overcurrentArray[$i] == '--'||$overcurrentArray[$i] == '0') {
                $overcurrentArray[$i] = '40';
            }
            elseif ($overcurrentArray[$i] == '1') {
                $overcurrentArray[$i] = '0';
            }
            $overcurrent[] = [3,$i,$overcurrentArray[$i]-'0'];
        }

        // 过滤器故障 filter
        $filterdata = Db::table($supportdb)->where('control',"过滤器故障")->value('name_en');
        $filterArray = Db::table($db)->column($filterdata);

        for ($i=0;$i < count($filterArray);$i++) {
            if ($filterArray[$i] == '--'||$filterArray[$i] == '0') {
                $filterArray[$i] = '50';
            }
            elseif ($filterArray[$i] == '1') {
                $filterArray[$i] = '0';
            }
            $filter[] = [4,$i,$filterArray[$i]-'0'];
        }


        // 急停状态 emergencyStop
        $emergencyStopdata = Db::table($supportdb)->where('control',"急停状态")->value('name_en');
        $emergencyStopArray = Db::table($db)->column($emergencyStopdata);

        for ($i=0;$i < count($emergencyStopArray);$i++) {
            if ($emergencyStopArray[$i] == '--'||$emergencyStopArray[$i] == '0') {
                $emergencyStopArray[$i] = '60';
            }
            elseif ($emergencyStopArray[$i] == '1') {
                $emergencyStopArray[$i] = '0';
            }
            $emergencyStop[] = [5,$i,$emergencyStopArray[$i]-'0'];
        }


        // 锁闭状态 locked
        $lockeddata = Db::table($supportdb)->where('control',"锁闭状态")->value('name_en');
        $lockedArray = Db::table($db)->column($lockeddata);

        for ($i=0;$i < count($lockedArray);$i++) {
            if ($lockedArray[$i] == '--'||$lockedArray[$i] == '0') {
                $lockedArray[$i] = '70';
            }
            elseif ($lockedArray[$i] == '1') {
                $lockedArray[$i] = '0';
            }
            $locked[] = [6,$i,$lockedArray[$i]-'0'];
        }

        $all['pressure1']           =$pressure1;
        $all['pressure2']           =$pressure2;
        $all['position']            =$position;
        $all['getPositionByData']   =$getPositionByData;
        $all['address']             =$address;
        $all['electromagnetic']     =$electromagnetic;
        $all['sensor']              =$sensor;
        $all['overcurrent']         =$overcurrent;
        $all['filter']              =$filter;
        $all['emergencyStop']       =$emergencyStop;
        $all['locked']              =$locked;

        return json($all);
    }

    // public function alarm_monitor()
    // {
    //     $alarmText=1;
    //     $alarmText=input("post.alarmText");
    //     // 获取数据库对象在进行操作
    //     $code = Db::table("table_deviceinfomation")->where('device_type','液压支架组')->value('device_code');
    //     $com = Db::table("table_deviceinfomation")->where('device_type','液压支架组')->value('com');
    //     //读取数据库
    //     $alarmdb = 'table_device_alarmdisplay_'.$com;
    //     $alarmdata = Db::table($alarmdb)->select();
    //     // $numb = count($alarmdata);
    //     $data = $alarmdata;

    //     return json($data);
    // }

}
