<?php

/**
 * 获取模块配置接口
 *
 * @author 谢建平 <jianping_xie@aliyun.com>
 * @author NaiXiaoXin
 * @copyright 2012-2015 Appbyme
 */
if (!defined('IN_DISCUZ') || !defined('IN_APPBYME')) {
    exit('Access Denied');
}

class ModuleConfigAction extends MobcentAction {

    public function run($moduleId, $configId = 0) {
        $res = $this->initWebApiArray();
        $id = (int)$configId;
        $res['body'] = $this->_getModuleconfig($moduleId, $id);
        $res['head']['errInfo'] = '';
        WebUtils::outputWebApi($res);
    }

    private function _getModuleconfig($moduleId, $id,$version=1) {
        $module = array('padding' => '');
        $uidiyModle = new AppbymeUiDiy($version,$id);
        $uidiyModle->getUiDiyVersion();
        $temp = $uidiyModle->getModule(false);
        foreach ($temp as $tmpModule) {
            if ($tmpModule['id'] == $moduleId) {
                $module = AppUtils::filterModule($tmpModule);
                break;
            }
        }
        return array(
            'module' => WebUtils::tarr($module),
        );
    }

}
