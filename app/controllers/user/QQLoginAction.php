<?php
/**
 * 
 * @date 2015-9-10 17:16:47
 * @author NaiXiaoXin
 * @author  谢建平 <jianping_xie@aliyun.com>  
 * @copyright 2012-2015 Appbyme
 */

if (!defined('IN_DISCUZ') || !defined('IN_APPBYME')) {
    exit('Access Denied');
}

// Mobcent::setErrors();
class QQLoginAction extends CAction {
    
    public function run() {
        $connect = C::t('common_setting')->fetch('connect', true);
        $oauth2 = $connect['oauth2'];
        if ($oauth2=='1') {
            $this->_run_x31();
        } else {
            $this->_run();
        }
    }

    private function _run() {
        $path = Yii::getPathOfAlias('application.components.discuz.qqconnect');
        require_once($path . '/connect_login_x25.php');
    }

    private function _run_x31() {
        $path = Yii::getPathOfAlias('application.components.discuz.qqconnect');
        require_once($path . '/connect_login_x31.php');
    }
}