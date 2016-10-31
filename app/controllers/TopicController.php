<?php
/**
 * topic控制器
 * @author tanguanghua <18725648509@163.com>
 **/
if (!defined('IN_DISCUZ') || !defined('IN_APPBYME')) {
    exit('Access Denied');
}

class TopicController extends MobcentController {

    public function actions() {
        return array(
            'topiclist' => 'application.controllers.topic.TopiclistAction',
            'caretpc' => 'application.controllers.topic.CaretpcAction',
            'topicdtl' => 'application.controllers.topic.TopicdtlAction',
            'mytopic' => 'application.controllers.topic.MytopicAction',
            'test' => 'application.controllers.topic.TestAction',
            'apply' => 'application.controllers.topic.ApplyAction',
            'subtopic' => 'application.controllers.topic.SubtopicAction',
        );
    }
    protected function mobcentAccessRules() {
        return array(
            'topiclist' => false,
            'caretpc' => true,
            'topicdtl' => false,
            'mytopic' => true,
            'apply' => false,
            'subtopic' => false,
            'test' => false
        );
    }
}