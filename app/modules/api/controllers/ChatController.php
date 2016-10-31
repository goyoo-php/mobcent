<?php
/**
 * 聊天室和console通信接口
 * Created by PhpStorm.
 * User: tantan
 * Date: 16/6/15
 * Time: 14:20
 */
class ChatController extends ApiController
{
    /**
     * 保存融云信息
     * @param $appKey
     * @param $appSecret
     */
    public function actionSave()
    {
        $forumKey = $_GET['forumKey'];
        $data     = $this->db->queryScalar('SELECT `cvalue` FROM %t WHERE `ckey`=%s',array('appbyme_config','RONGYUN_KEY'));
        $data     = unserialize($data);
        $data[$forumKey]['appSecret'] = $this->data['appSecret'];
        $data[$forumKey]['appKey']    = $this->data['appKey'];
        $data                         = serialize($data);
        $this->db->insert('appbyme_config',array(
                'ckey' => 'RONGYUN_KEY',
                'cvalue' => $data
            ),
            false,
            true
        );
    }

    /**
     * 获得粉丝列表
     * @param $uid 用户id
     */
    public function actionUserList()
    {
        $outdata = array();
        $tmpdata = $this->db->queryAll('SELECT `uid` FROM %t WHERE `followuid`=%d LIMIT %d,%d',array(
            'home_follow',
            intval($this->data['uid']),
            intval(($this->data['page']-1)*$this->data['pageSize']),
            intval($this->data['pageSize'])
        ));
        foreach($tmpdata as $v)
        {
            $outdata[] = $v['uid'];
        }
        $this->setData(array('userlist' => $outdata));
    }
    protected function setRules()
    {
        return array(
            'save' => array(
                'appKey' => 'string',
                'appSecret' => 'string'
            ),
            'userlist' => array(
                'uid' => 'int'
            )
        );
    }
}