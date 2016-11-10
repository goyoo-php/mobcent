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

    //加入聊天室
    public function actionInRoom()
    {
        //错误码  110 00000参数错误  110 00001还用户已进入聊天室  110 00002进入聊天室失败
        if(!($this->data['uid'] && $this->data['cid'])) {
            $this->error('11000000');
        }
        $inroom = $this->db->queryScalar('SELECT COUNT(*) FROM %t WHERE `cid`=%d AND `uid` =%d',array('appbyme_chatuser', $this->data['cid'], $this->data['uid']));
        if(intval($inroom)) {
            $this->error('11000001');
        }
        $re = $this->db->insert('appbyme_chatuser',array(
            'uid' => intval($this->data['uid']),
            'uname' => strval($this->data['uname']),
            'uavatar' => strval($this->data['avatar']),
            'cid' => intval($this->data['cid'])
        ));
        if(!$re) {
            $this->error('11000002');
        }
    }
    //退出聊天室
    public function actionOutRoom()
    {
        if(!($this->data['uid'] && $this->data['cid'])) {
            $this->error('11000000');
        }
        $this->db->delete('appbyme_chatuser',array(
            'uid' => $this->data['uid'],
            'cid' => $this->data['cid']
        ));
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