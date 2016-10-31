<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta>
        <meta http-equiv="Cache-control" content="no-cache"></meta>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"></meta>
        <meta name="format-detection" content="telephone=no"></meta>
        <?php
        global $_G;
        header("Content-type: text/html; charset=utf-8");
        ?>
        <title><?php echo WebUtils::lp('forum_topicRate_rate_title'); ?> </title>
        <script type="text/javascript" src="<?php echo $this->dzRootUrl; ?>/mobcent/app/web/js/appbyme/appbyme.js"></script>
        <script type="text/javascript">
            connectAppbymeJavascriptBridge(function (bridge) {
                var json = {};
                AppbymeJavascriptBridge.customButton(JSON.stringify(json));
            })
            var errorMsg = '<?php echo WebUtils::u($errorMsg); ?>';
            if (errorMsg != '') {
                alert(errorMsg);
            }

        </script>
    </head>

    <body class="bg">
        <style>	* { word-wrap: break-word; }
            ul,ol,li,span,p,form,h1,h2,h3,4,h5,h6,dl,dt,dd { margin: 0; padding: 0; border: 0; z-index:inherit; }
            img,a img { border:0; margin:0; padding:0; }
            ul,ol,li { list-style:none; }
            * { margin:0; padding:0; }
            html,body { height:100%; font:12px/1.6  Microsoft YaHei, Helvetica, sans-serif; color:#4C4C4C; }
            input,select,textarea,button { font:14px/1.5  Microsoft YaHei, Helvetica, sans-serif; }
            body, ul, ol, li, dl, dd, p, h1, h2, h3, h4, h5, h6, form, fieldset, .pr, .pc { margin: 0; padding: 0; }
            table { empty-cells: show; border-collapse: collapse; }
            caption, th { text-align: left; font-weight: 400; }
            ul li, .xl li { list-style: none; }
            h1, h2, h3, h4, h5, h6 { font-size: 1em; }
            em, cite, i { font-style: normal; }
            a img { border: none; }
            label { cursor: pointer; }
            .bg { background:#eeeeee; }
            .rq { color: red; }

            a:link,a:visited,a:hover { color:#4C4C4C; text-decoration:none; }
            .blue { color: #0086CE; }
            a.blue:link, a.blue:visited, a.blue:hover { color:#0086CE; text-decoration:none; }
            .grey { color:#9C9C9C; }
            a.grey:link, a.grey:visited, a.grey:hover { color:#9C9C9C; text-decoration:none; }
            .orange { color:#F60; }
            a.orange:link,a.orange:visited,a.orange:hover{color:#F60;text-decoration:none }

            .z { float: left; } .y { float: right; }
            .cl:after { content: "."; display: block; height: 0; clear: both; visibility: hidden; } .cl { zoom: 1; }
            .overflow{overflow:hidden;}
            .none { display:none; }
            .vm { vertical-align: middle; }
            .vm * { vertical-align: middle; }
            .hm { text-align: center; }
            .mbm{ width:100%; background:#fff;}
            .mbm  .trtop{ background:#f0f4f7;padding:3px;}
            .mbm td,.mbm th{ text-align:center; padding:5px; }
            .mbm td{ border-bottom:#d9d9d9 1px solid;}
            .px{ border:#d9d9d9 1px solid; border-radius:3px;}

            .tpclg{ background:#fff; margin-top:20px;}
            .tpclg textarea{ width:100%; border:0;}
            .tpclg span{ padding:0 10px; line-height:30px;}
            .tongzhi{ margin-top:20px; background:#fff; padding:10px; }
            .button{ margin:20px;}
            .button2{ background:#ee5e39; border-radius:3px; width:100%; border:0; color:#fff; padding:10px;}
            /*            #sendreasonpm{ margin-top:5px;}*/
            .weui_switch {
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
                position: relative;
                width: 52px;
                height: 32px;
                border: 1px solid #DFDFDF;
                outline: 0;
                border-radius: 16px;
                box-sizing: border-box;
                background: #DFDFDF;
            }
            .weui_switch:before {
                content: " ";
                position: absolute;
                top: 0;
                left: 0;
                width: 50px;
                height: 30px;
                border-radius: 15px;
                background-color: #FDFDFD;
                -webkit-transition: -webkit-transform .3s;
                transition: transform .3s;
            }
            .weui_switch:after {
                content: " ";
                position: absolute;
                top: 0;
                left: 0;
                width: 30px;
                height: 30px;
                border-radius: 15px;
                background-color: #FFFFFF;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);
                -webkit-transition: -webkit-transform .3s;
                transition: transform .3s;
            }
            .weui_switch:checked {
                border-color: #04BE02;
                background-color: #04BE02;
            }
            .weui_switch:checked:before {
                -webkit-transform: scale(0);
                -ms-transform: scale(0);
                transform: scale(0);
            }
            .weui_switch:checked:after {
                -webkit-transform: translateX(20px);
                -ms-transform: translateX(20px);
                transform: translateX(20px);
            }

            /* 按钮*/
            .weui_btn_primary {
                background-color: #04BE02;
            }
            .weui_btn_primary:not(.weui_btn_disabled):visited {
                color: #FFFFFF;
            }
            .weui_btn_primary:not(.weui_btn_disabled):active {
                color: rgba(255, 255, 255, 0.4);
                background-color: #039702;
            }
            .weui_btn {
                position: relative;
                display: block;
                margin-left: auto;
                margin-right: auto;
                padding-left: 14px;
                padding-right: 14px;
                box-sizing: border-box;
                font-size: 18px;
                text-align: center;
                text-decoration: none;
                color: #FFFFFF;
                line-height: 2.33333333;
                border-radius: 5px;
                -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
                overflow: hidden;
            }
            .weui_btn:after {
                content: " ";
                width: 200%;
                height: 200%;
                position: absolute;
                top: 0;
                left: 0;
                border: 1px solid rgba(0, 0, 0, 0.2);
                -webkit-transform: scale(0.5);
                -ms-transform: scale(0.5);
                transform: scale(0.5);
                -webkit-transform-origin: 0 0;
                -ms-transform-origin: 0 0;
                transform-origin: 0 0;
                box-sizing: border-box;
                border-radius: 10px;
            }
            button.weui_btn, input.weui_btn {
                width: 100%;
                border-width: 0;
                outline: 0;
                -webkit-appearance: none;
            }
        </style>


        <form id="rateform" method="post" autocomplete="off"  action="<?php echo $formUrl; ?>">
            <div class="c">
                <table cellspacing="0" cellpadding="0" class="dt mbm">
                    <tbody><tr class="trtop">
                            <th width="25%"><?php echo WebUtils::lp('forum_topicRate_title'); ?></th>
                            <th width="">&nbsp;</th>
                            <th width="25%"><?php echo WebUtils::lp('forum_topicRate_grade_area'); ?></th>
                            <th width="25%"><?php echo WebUtils::lp('forum_topicRate_now_surplus'); ?></th>
                        </tr>
                        <?php foreach ($ratelist as $id => $options): ?>
                            <tr>
                                <td> <?php echo WebUtils::u($_G['setting']['extcredits'][$id]['title']); ?>:</td>
                                <td>
                                    <input type="text"  name="score<?php echo $id ?>" id="score1" class="px z" value="" >

                                </td>
                                <td><?php echo $_G['group']['raterange'][$id]['min']; ?> ~ <?php echo $_G['group']['raterange'][$id]['max']; ?></td>
                                <td><?php echo $maxratetoday[$id] ?></td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody></table>

                <div class="tpclg">
                    <span><?php echo WebUtils::lp('forum_topicRate_reason'); ?></span>

                    <textarea name="reason" >

                    </textarea>


                </div>
            </div>

            <div class="tongzhi"  style="
                 font-size: 16px;
                 " > 
                <?php echo WebUtils::lp('forum_topicRate_notification_author'); ?>
                <label for="sendreasonpm"><input class="weui_switch" type="checkbox" name="sendreasonpm" id="sendreasonpm" style=" float:right ;margin-top: -5px;" /></label>
            </div>
            <dd class="button">   
<!--                <input name="modsubmit" type="submit" value="确定" class="formdialog button2">-->
                <input name="modsubmit" type="submit" value="<?php echo WebUtils::lp('forum_topicRate_ok'); ?>" class="weui_btn weui_btn_primary"style="
                       border-radius: 3px;
                       width: 100%;
                       border: 0;
                       color: #fff;
                       /* padding: 10px; */
                       "></dd>
        </form>
        <br><br><br>
                    </body>
                    </html>