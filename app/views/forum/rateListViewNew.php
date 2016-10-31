<html xmlns="http://www.w3.org/1999/xhtml"><head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <meta http-equiv="Cache-control" content="no-cache">
                <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
                    <meta name="format-detection" content="telephone=no">
                         <?php
                            global $_G;
                            header("Content-type: text/html; charset=utf-8");
                            ?>
                            <title><?php echo WebUtils::lp('forum_topicRate_rate_all'); ?></title>

                            </head>
                            <script type="text/javascript" src="<?php echo $this->dzRootUrl; ?>/mobcent/app/web/js/appbyme/appbyme.js"></script>
                            <script type="text/javascript">
                                connectAppbymeJavascriptBridge(function (bridge) {
                                    var json = {};
                                    AppbymeJavascriptBridge.customButton(JSON.stringify(json));
                                })


                            </script>
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
                                    .mbm td,.mbm th{ padding:10px }
                                    .mbm td{ border-bottom:#d9d9d9 1px solid;}
                                    .px{ border:#d9d9d9 1px solid; border-radius:3px;}


                                </style>


                                <form id="rateform" method="post" autocomplete="off" action="">
                                    <div class="c">
                                        <table cellspacing="0" cellpadding="0" class="dt mbm">
                                            <tbody><tr class="trtop">
                                                    <th width="50%"><?php echo WebUtils::lp('forum_topicRate_rate_member'); ?></th>
                                                    <?php
                                                    $num = count($logcount);
                                                    $width = 0.4 / $num;
                                                    ?>
                                                    <?php foreach ($logcount as $s => $k) { ?>
                                                        <th width="<?php echo $width; ?>"><?php echo WebUtils::u($_G['setting']['extcredits'][$s]['title']) ?></th>
                                                    <?php } ?>
                                                </tr>
                                                <?php foreach ($loglist as $s => $k) { ?>
                                                    <tr>
                                                        <td><?php echo WebUtils::u($k['username']); ?></td>
                                                        <?php foreach ($logcount as $ss => $kk) { ?>
                                                            <td><?php echo WebUtils::u($k['score'][$ss]); ?></td>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </tr>

                                            </tbody></table>


                                    </div>



                                </form>
                                <br><br><br>
                                            </body></html>