<?php
/**
 *
 *
 * @author NaiXiaoXin<wangsiqi@goyoo.com>
 * @copyright 2003-2016 Goyoo Inc.
 */

if (!defined('IN_DISCUZ') || !defined('IN_APPBYME'))
{
    exit('Access Denied');
}

class TopicController extends ApiController
{


    public function actionList()
    {
        $where = '';
        if (isset($this->data['search']) && $this->data['search'])
        {
            $where = ' AND `ti_title` LIKE \'%' . $this->data['search'] . '%\'';
        }
        $start = ($this->data['page'] - 1) * $this->data['pageSize'];
        $data = $this->db->queryAll(
            'SELECT `ti_id`,`ti_title` FROM %t WHERE `ti_starttime`<%d AND `ti_endtime`>%d %i ORDER BY `ti_id` DESC LIMIT %d,%d',
            array(
                'appbyme_topic_items',
                time(),
                time(),
                $where,
                $start,
                $this->data['pageSize']
            )
        );
        $this->setData(array('list' => $data));
    }


    public function actionConfig()
    {
        if ($_POST)
        {

            $this->data['bindFid'] && AppbymeConfig::saveCvalue('topic_bind_fid', $this->data['bindFid']);
            $this->data['verify'] && AppbymeConfig::saveCvalue('topic_bind_verify', $this->data['verify']);
            $this->data['titleHide'] && AppbymeConfig::saveCvalue('topic_settitle_hide', $this->data['titleHide']);
        } else
        {
            $this->setOneData('bindFid', intval(AppbymeConfig::getCvalueData('topic_bind_fid',0)));
            $this->setOneData('verify', intval(AppbymeConfig::getCvalueData('topic_bind_verify',0)));
            $this->setOneData('titleHide', intval(AppbymeConfig::getCvalueData('topic_settitle_hide',0)));
        }
    }

    public function actionTopiclist()
    {
        $search = $this->data['search'];
        if (strlen($search) != 0) {
            $where = ' WHERE (`ti_authorname` LIKE \'%' . WebUtils::t($search) . '%\' OR `ti_title` LIKE \'%' . WebUtils::t($search) . '%\')';
        }
        $page = $this->data['page'];
        $pageSize = $this->data['pageSize'];
        $str = ($page - 1) * $pageSize;
        $sql = 'SELECT * FROM %t %i ORDER BY `ti_id` DESC LIMIT %d,%d';
        $data['items'] = $this->db->queryAll($sql, array('appbyme_topic_items', $where, $str, $pageSize));
        $data['count'] = $this->db->queryFirst('SELECT COUNT(0) FROM %t %i', array('appbyme_topic_items', $where));
        $data['total_pages'] = ceil($data['count'] / $pageSize);//计算共几页
        $this->setData($data);
    }

    public function actionTopic()
    {
        if($_POST){
            $data['ti_title'] = $this->data['title'];
            $data['ti_starttime'] = strtotime($this->data['starttime']);
            $data['ti_endtime'] = strtotime($this->data['endtime']);
            $data['ti_content'] = $this->data['content'];
            if($_FILES){
                $upload = $this->_uploadAttach(0);
                $data['ti_cover'] = $upload['urlName'];
                $data['ti_remote'] = $upload['remote'];
            }
            $data['ti_fid'] = $this->data['fid'];
            if($this->data['id']){
                $id = $where['ti_id'] = intval($this->data['id']);
                $this->db->update('appbyme_topic_items',$data,$where);
            }else{
                $data['ti_authorid'] =1;
                $data['ti_authorname'] = UserUtils::getUserName(1);
                $id = $this->db->insert('appbyme_topic_items',$data,true);
            }

        }else{
            $id = intval($this->data['id']);
            if(empty($id)){
                $this->error('ID不存在');
            }
        }
        $data = $this->db->queryRow('SELECT * FROM %t WHERE ti_id=%d', array('appbyme_topic_items', $id));
        $this->setData($data);
    }

    public function actionDeleteTopic()
    {
        $id = intval($this->data['id']);
        if(empty($id)){
            $this->error('ID不存在');
        }
        $data = $this->db->delete('appbyme_topic_items', array('ti_id'=>$id));
        if(!$data){
            $this->error('删除失败!');
        }
    }

    /**
     * @param $uid
     * @param $allowValue
     * @return array
     * @throws CException
     */
    private function _uploadAttach($allowValue) {
        global $_G;
        $extid = 0;
        $type = 'forum';
        $forcename = '';
        $fileExtension = FileUtils::getFileExtension($_FILES['uploadFile']['name'][$allowValue], 'jpg');
        Yii::import('application.components.discuz.source.class.discuz.discuz_upload', true);
        $upload = new Mobcent_upload;
        $attach['extension'] = $fileExtension;
        $attach['attachdir'] = $upload->get_target_dir($type, $extid);
        $filename = $upload->get_target_filename($type, $extid, $forcename).'.'.$attach['extension'];
        $attach['attachment'] = $attach['attachdir'].$filename;
        $attach['target'] = getglobal('setting/attachdir').'./'.$type.'/'.$attach['attachment'];
        $res = array();
        $uploaded = Qiniuup::uptoqiniu(file_get_contents($_FILES['uploadFile']['tmp_name'][$allowValue]),$filename,true,false);
        if(!$uploaded){
            $savePath = getglobal('setting/attachdir').'./'.$type.'/'.$attach['attachdir'];

            if (!is_dir($savePath)) {
                mkdir($savePath, 0777, true);
            }
            $saveName = $savePath.$filename;
            $uploaded = move_uploaded_file($_FILES['uploadFile']['tmp_name'][$allowValue], $saveName);
            $islocalup = $uploaded ? true : false;
        }else{
            $res['urlName'] = $uploaded;
            $res['remote'] = 1;
        }
        if ($islocalup) {
            // 添加水印
            Yii::import('application.components.discuz.source.class.class_image', true);
            $image = new Mobcent_Image;
            if ($image->param['watermarkstatus']['forum'] > 0) {
                $image->makeWatermark($attach['target'], '', 'forum');
            }
            ImageUtils::getThumbImageEx($path_url, 10, false, false, true);
            $res['urlName'] = $attach['attachment'];
            $res['remote'] = 0;
        }
        if($uploaded){
            //七牛上传删掉临时文件
            if(file_exists($_FILES['uploadFile']['tmp_name'][$allowValue])){
                @unlink($_FILES['uploadFile']['tmp_name'][$allowValue]);
            }
        }
        return $res;
    }
    
    protected function setOneData($key,$value){
        return $this->setData(array($key=>$value));
    }

}