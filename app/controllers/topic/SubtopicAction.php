<?php
/**
 * 发布话题接口
 * @author tanguanghua <18725648509@163.com>
 **/
if (!defined('IN_DISCUZ') || !defined('IN_APPBYME')) {
    exit('Access Denied');
}

class SubtopicAction extends MobcentAction {
    public function run($title = '',$content = '',$endtime='',$aid = 0) {
        $db = DbUtils::getDzDbUtils(true);
        $res = WebUtils::initWebApiArray_oldVersion();
        $uid = $this->getController()->uid;
        $uinfo = getuserbyuid($uid);
        $verify = $db->queryRow('SELECT `cvalue` FROM %t WHERE `ckey`=%s LIMIT 1',array('appbyme_config','topic_bind_verify'));
        if(!empty($verify)){
            $ck = $db->queryRow('SELECT COUNT(0) FROM %t WHERE `uid`=%d AND `verify'.$verify['cvalue'].'`=1',array('common_member_verify',$uid));
        }
        /*
        增加不能发布话题原因返回 by onmylifejie
        */
        global $_G;
        $vefname = $_G['setting']['verify'][$verify['cvalue']]['title'];
        if(!isset($ck) || !$ck['COUNT(0)']){
            $res = WebUtils::makeErrorInfo_oldVersion($res,'mobcent_topic_verify',array('{verifyname}'=>WebUtils::t($vefname)));
            WebUtils::outputWebApi($res);
        }
        if(!$aid || !$title){
            $res = WebUtils::makeErrorInfo_oldVersion($res,'参数错误');
            WebUtils::outputWebApi($res);
        }
        $attach = $db->queryRow('SELECT * FROM %t WHERE `aid`='.$aid.' LIMIT 1',array('forum_attachment_unused'));//forum_attachment_unused
        $db->query('DELETE FROM %t WHERE `aid`='.$aid,array('forum_attachment_unused'));
        $data['ti_title'] = WebUtils::t($title);
        $data['ti_content'] = WebUtils::t($content);
        $data['ti_starttime'] = $_SERVER['REQUEST_TIME'];
        $data['ti_endtime'] = $endtime ? strtotime($endtime) : ($_SERVER['REQUEST_TIME']+DAY_SECONDS*3);
        $data['ti_authorid'] = $uid;
        $data['ti_authorname'] = $uinfo['username'];
        $data['ti_cover'] = $attach['attachment'];
        $data['ti_remote'] = $attach['remote'];
        $dbre = $db->insert('appbyme_topic_items', $data);
        if(!$dbre){
//            $res['rs'] = 0;
//            $res['head']['errInfo'] = WebUtils::t('调用没有成功,数据库操作失败');
            $res = WebUtils::makeErrorInfo_oldVersion($res,'调用没有成功,数据库操作失败');
        }
        WebUtils::outputWebApi($res);
    }
}
