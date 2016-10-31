<?php
/**
 * 微信快速登录接口
 * Created by PhpStorm.
 * User: onmylifejie
 * Date: 2016/6/21
 * Time: 20:11
 **/
if (!defined('IN_DISCUZ') || !defined('IN_APPBYME')) {
    exit('Access Denied');
}
class WxLoginAction extends MobcentAction {

    private $_wxLogin = 'wxLogin_config';

    public function run($json){
        $res = WebUtils::initWebApiArray_oldVersion();

        $config = AppbymeConfig::getCvalue($this->_wxLogin);
        if(isset($config['wxLogin']) && $config['wxLogin'] == 0) {
            $res = WebUtils::makeErrorInfo_oldVersion($res,'微信登录未开启');
            WebUtils::outputWebApi($res);
        }
        $json = WebUtils::jsonDecode($json);
        //解析json的时候会有一些解析失败，因为特殊字符。
        //跳转链接和登录
        if(!$this->_ckSign($json)){
            $res = WebUtils::makeErrorInfo_oldVersion($res,'参数错误');
            WebUtils::outputWebApi($res);
        }
        $res = $this->wxlogin($json, $res);

        WebUtils::outputWebApi($res);
    }

    public function wxlogin($json, $res) {
        //查appbyme_connection表中的openid
        if(!isset($json['unionid'])) {
            $res = WebUtils::makeErrorInfo_oldVersion($res,'传递微信登录信息有误');
            WebUtils::outputWebApi($res);
        }
        $wxtUserInfo = AppbymeConnection::getMobcentWxinfoByUnionId($json['unionid']);
        if (!$wxtUserInfo) { //判断此微信是否已经注册过
            //微信允许重名，DZ不允许重名。所以判断数据库中是否有该名字
            $json['nickname'] = WebUtils::removeEmoji($json['nickname']);
            $json = WebUtils::tarr($json);
            //空格用户打回
            if(trim($json['nickname']) == null) {
                return $this->makeErrorInfo($res, '用户名为空,不允许登录');
            }
            $username = UserUtils::UserFilter($json['nickname']);
            $password = '1q2@Ws'.time();
            $Reg = '1';
            $email = rawurldecode($json['email']);
            $regInfo = UserUtils::register($username, $password, $email, 'general', 1, 1);
            if ($regInfo['errcode']) {
                return $this->makeErrorInfo($res, $regInfo['message']);
            }
            $uid = $regInfo['info']['uid'];
            //更新用户名字
            //微信注册写入注册表
            if ($Reg == '1') {
                $data = array('uid' => $uid, 'openid' => $json['openid'], 'status' => 1, 'type' => 1, 'param' => $json['unionid']);
                AppbymeConnection::insertMobcentWx($data);
                $res['avatar'] = AppbymeConnection::syncAvatar($uid, $json['headimgurl']);
            }
        }else {
            $uid = $wxtUserInfo['uid'];
        }
        $userInfo = AppbymeUserAccess::registerProcess($uid, time());
        $res['token'] = strval($userInfo['token']);
        $res['secret'] = strval($userInfo['secret']);
        $user = UserUtils::getUserInfomation($uid);
        $res = array_merge($res,$user);
        return $res;
    }
    private function _ckSign($json){
        $serkey = DbUtils::createDbUtils(true)->queryScalar('SELECT `cvalue` FROM %t WHERE `ckey`=%s',array('appbyme_config','ForumKey_SecretKey'));
        $serkey = unserialize($serkey);
        if(isset($serkey[$json['forumKey']])){
            $sk = $serkey[$json['forumKey']];
            $hash = $json['sign'];
            unset($json['sign']);
            ksort($json);
            $tmpstring = '';
            foreach($json as $k => $v){
                $tmpstring .= $k.':'.$v.'&';
            }
            $tmpstring .= $sk;
            if($hash == md5($tmpstring)){
                return true;
            }else{
                return false;
            }
        }
    }
}