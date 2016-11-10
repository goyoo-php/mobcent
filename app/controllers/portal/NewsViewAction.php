<?php

/**
 * 文章详情接口
 *
 * @author NaiXiaoXin
 * @author 谢建平 <jianping_xie@aliyun.com>
 * @copyright 2012-2014 Appbyme
 */
if (!defined('IN_DISCUZ') || !defined('IN_APPBYME')) {
    exit('Access Denied');
}

// Mobcent::setErrors();
class NewsViewAction extends MobcentAction {

    public function run($json) {
        $res = $this->initWebApiArray();

//        $json = '{"aid": 1, "page": 1}';
        $json = rawurldecode($json);
        $json = WebUtils::jsonDecode($json);

        !isset($json['aid']) && $json['aid'] = 0;
        !isset($json['page']) && $json['page'] = 1;
        !isset($json['bbcode']) && $json['bbcode'] = 0;

        $res = $this->_getResult($res, (int) $json['aid'], (int) $json['page'], intval($json['bbcode']));
        echo WebUtils::outputWebApi($res, '', false);
    }

    private function _getResult($res, $aid, $page, $bbcode) {
        require_once DISCUZ_ROOT . './source/function/function_home.php';
        require_once DISCUZ_ROOT . './source/function/function_portal.php';
        loadcache('portalcategory');
        global $_G;

        // 在DISCUZ_ROOT/source/module/portal/portal_view.php基础上二次开发
        if (empty($aid)) {
            return $this->makeErrorInfo($res, lang('message', 'view_no_article_id'));
        }

        $article = C::t('portal_article_title')->fetch($aid);
        require_once libfile('function/portalcp');
        $categoryperm = getallowcategory($_G['uid']);

        if (empty($article) || ($article['status'] > 0 && $article['uid'] != $_G['uid'] && !$_G['group']['allowmanagearticle'] && empty($categoryperm[$article['catid']]['allowmanage']) && $_G['adminid'] != 1 && $_GET['modarticlekey'] != modauthkey($article['aid']))) {
            return $this->makeErrorInfo($res, lang('message', 'view_article_no_exist'));
        }

        // if(!empty($_G['setting']['antitheft']['allow']) && empty($_G['setting']['antitheft']['disable']['article']) && empty($_G['cache']['portalcategory'][$article['catid']]['noantitheft'])) {
        //     helper_antitheft::check($aid, 'aid');
        // }

        $res['body']['newsInfo'] = $this->_getNewsInfo($article, $page, $bbcode);

        return $res;
    }

    private function _getNewsInfo($article, $page, $bbcode) {
        global $_G;

        $newsInfo = array();

        $aid = $article['aid'];
        $articleUrl = $this->_fetchArticleUrl($aid);

        // 门户静态化,暂时去掉这个跳转,因为有些用户不支持wap版的门户页面
        /*
          if(!empty($_G['setting']['makehtml']['flag']) && $article['htmlmade'] && !isset($_G['makehtml']) && empty($_GET['diy']) && empty($article['url'])) {
          // dheader('location:'. fetch_article_url($article));
          $newsInfo['redirectUrl'] = $articleUrl;
          return $newsInfo;
          }
         */

        $article_count = C::t('portal_article_count')->fetch($aid);
        if ($article_count)
            $article = array_merge($article_count, $article);

        if ($article_count) {
            C::t('portal_article_count')->increase($aid, array('viewnum' => 1));
            unset($article_count);
        } else {
            C::t('portal_article_count')->insert(array(
                'aid' => $aid,
                'catid' => $article['catid'],
                'viewnum' => 1));
        }

        if ($article['url']) {
            // if(!isset($_G['makehtml'])) {
            //     dheader("location:{$article['url']}");
            // }
            // exit();
            $newsInfo['redirectUrl'] = $article['url'];
            return $newsInfo;
        }

        $cat = category_remake($article['catid']);

        $article['pic'] = pic_get($article['pic'], '', $article['thumb'], $article['remote'], 1, 1);

        if ($page < 1)
            $page = 1;

        $org = array();
        if ($article['idtype'] == 'blogid') {
            $org = C::t('home_blog')->fetch($article['id']);
            if (empty($org)) {
                C::t('portal_article_title')->update($aid, array('id' => 0, 'idtype' => ''));
                // dheader('location: '.  fetch_article_url($article));
                // exit();
                $newsInfo['redirectUrl'] = $articleUrl;
                return $newsInfo;
            }
        }

        $article['allowcomment'] = !empty($cat['allowcomment']) && !empty($article['allowcomment']) ? 1 : 0;

        $article['timestamp'] = $article['dateline'];
        $article['dateline'] = dgmdate($article['dateline']);

        $newsInfo['redirectUrl'] = '';
        $newsInfo['catName'] = WebUtils::t('文章详情');
        $newsInfo['title'] = WebUtils::emptyHtml($article['title']);
        $newsInfo['dateline'] = $article['dateline'];
        $newsInfo['author'] = $article['username'];
        $newsInfo['viewNum'] = (int) $article['viewnum'];
        $newsInfo['commentNum'] = (int) $article['commentnum'];
        $newsInfo['allowComment'] = $article['allowcomment'] ? 1 : 0;
        $newsInfo['summary'] = $article['summary'];
        $newsInfo['pageCount'] = (int) $article['contents'];
        $newsInfo['from'] = $article['from'];
        $newsInfo['is_favor'] = PortalUtils::isFavoriteArticle($_G['uid'],$article['aid']);
        $newsInfo['articleUrl'] = $articleUrl;
        if($bbcode) {
            $newsInfo['html-content'] = PortalUtils::getNewsContentHtml($article, $page );
        }

        $user = $this->getUserInfo($newsInfo['uid']);
        $newsInfo = array_merge($newsInfo,$user);
        $newsInfo['content'] = $this->_transContent(PortalUtils::getNewsContent($article, $page));

        return $newsInfo;
    }

    private function getUserInfo($uid)
    {
        $newsInfo['avatar'] = UserUtils::getUserAvatar($uid);
        $newsInfo['gender'] = UserUtils::getUserGender($uid);
        $newsInfo['uid']    = $uid;
        return $newsInfo;
    }

    private function _transContent($content) {
        $newContent = array();
        foreach ($content as $value) {
            if ($value['type'] == 'text') {
                $value['content'] = preg_replace('/\[mobcent_url=(.+?)\](.+?)\[\/mobcent_url\]/', ' \\2 ', $value['content']);
                $value['content'] = preg_replace('/\[mobcent_url=.+?\]/is', '', $value['content']);
                $value['content'] = preg_replace('/\[\/mobcent_url\]/is', '', $value['content']);
            }
            //增加视频解析 Author:NXX Date:20151027
            if ($value['type'] == 'video') {
                $viedo = ForumUtils::transVideoUrl($value['content']);
                $value['content'] = $viedo;
                $temp = ForumUtils::parseVideoUrl($viedo);
                $value['extParams']['videoType'] = $temp['type'];
                $value['extParams']['videoId'] = $temp['vid'];
            }
            $value['content'] != '' && $newContent[] = $value;
        }
        return $newContent;
    }

    private function _fetchArticleUrl($aid) {
        return WebUtils::getHttpFileName('portal.php?mod=view&aid=' . $aid);
    }

}
