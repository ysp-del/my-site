<?php
class TryClass{
    public function getValue()
    {
        return '';
    }
}
class TryTest1 extends PHPUnit_Framework_TestCase
{


    public function testValue1()
    {
        $try = new TryClass();
        $this->assertEquals('frt',$try->getValue());

    }

    public function testvalue2()
    {
        $try = new TryClass();
        $this->assertEquals('ft',$try->getValue());
        $where = array();
        $map = array();
        $map['gname'] = trim(I("gname"));
        if ($map['gname']){
            $where['ig.name'] = array('like','%'.$map['gname'].'%');
        }
        $map['tel'] = trim(I("tel"));
        if ($map['tel']){
            $where['ml.tel'] = array('like','%'.$map['tel'].'%');
        }
        $map['member_id'] = I("member_id");
        if($map['member_id'])
        {
            $where['ml.member_id'] = $map['member_id'];
        }
        $serchar = I("serchar");
        if($serchar=='1'){
            $lastdate = date("Y-m-d",strtotime("last month"));
            $nextdate = date("Y-m-d");
            $map['sdate'] = I("sdate", $lastdate);
            $map['edate'] = I("edate", $nextdate);
        }else{
            $map['sdate'] = I("sdate", '');
            $map['edate'] = I("edate", '');
        }
        $map['datetime'] = I("datetime", '');
        if($map['datetime']!='1') {
            if ($map['sdate'] && $map['edate']) {
                $where['_string'] = "FROM_UNIXTIME(ir.addtime,'%Y%m%d') between " . str_replace('-', '', $map['sdate']) . " AND " . str_replace('-', '',
                        $map['edate']);
            } else {
                if ($map['sdate']) {
                    $where["FROM_UNIXTIME(ir.addtime,'%Y%m%d')"] = array('gt', str_replace('-', '', $map['sdate']));
                }
                if ($map['edate']) {
                    $where["FROM_UNIXTIME(ir.addtime,'%Y%m%d')"] = array('lt', str_replace('-', '', $map['edate']));
                }
            }
        }
        $field = "ir.*,ml.tel,ml.name,ml.member_id,ig.name gname,jl.jms_title,al.js_name,al.js_name";
        $table = "{$this->_prefix}integral_record AS ir";
        $join = "LEFT JOIN {$this->_prefix}integral_details AS ig ON ir.integral_id=ig.integral_id";
        $join .= " LEFT JOIN {$this->_prefix}app_member_list AS al ON ir.js_id=al.js_id";
        $join .= " LEFT JOIN {$this->_prefix}join_list AS jl ON ir.jms_id=jl.jms_id";
        $join .= " LEFT JOIN {$this->_prefix}member_list AS ml ON ir.member_id=ml.id";
        $mdoel = M()->table($table)->join($join);
        $listData = $this->lists($mdoel,$where, 'ir.id desc', $field);
        if($listData)
        {
            foreach($listData as $k => $v)
            {
                if($v['success_time'])
                {
                    $listData[$k]['success_time'] = date('Y-m-d H:i:s',$v['success_time']);
                }
            }
        }
        $this->assign('map', $map);
        $this->assign('integral_record', $listData);
        $this->display();
    }


}

