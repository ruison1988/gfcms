var more_start = 15;
clearInterval(int);

$("head").append('<style type="text/css">.star_holder{width:110px;height:22px;margin:3px 0;overflow:hidden;background:url(http://www.twcms.com/app/img/star.png)}.star_rating{width:100px;height:22px;background:url(http://www.twcms.com/app/img/star.png) 0 -22px}</style>');

$("#gfcms_app").html('<div class="thm"><div class="to"><p class="tu" style="background: transparent url(&quot;http://www.twcms.com/app/theme/default_black.jpg&quot;) repeat scroll 0% 0%; display: block;"></p><p class="full" style="display: none;"><b>版本号：1.0.0</b><b>更新于：2014.01.23</b>由小乐设计的效果图，空城编写代码。兼容IE6/IE7/IE8/IE9/IE10 Chrome Firefox Opera Safari Ipad</p></div><h1>默认主题(黑色)(default_black)</h1><p class="author">作者: <a target="_blank" href="http://www.twcms.com">小乐、空城</a></p><div class="star_holder" title="共评价 1 次"><div class="star_rating"></div></div><p class="gsm" theme="default_black"><a class="but2" onclick="install_theme(\'default_black\')" href="javascript:;">在线安装</a></p></div><div class="thm"><div class="to"><p class="tu" style="background: transparent url(&quot;http://www.twcms.com/app/theme/default_black2.jpg&quot;) repeat scroll 0% 0%; display: block;"></p><p class="full" style="display: none;"><b>版本号：1.0.0</b><b>更新于：2014.01.23</b>由小乐设计的效果图，空城编写代码。兼容IE6/IE7/IE8/IE9/IE10 Chrome Firefox Opera Safari Ipad</p></div><h1>默认主题(黑色2)(default_black2)</h1><p class="author">作者: <a target="_blank" href="http://www.twcms.com">小乐、空城</a></p><div class="star_holder" title="共评价 1 次"><div class="star_rating"></div></div><p class="gsm" theme="default_black2"><a class="but2" onclick="install_theme(\'default_black2\')" href="javascript:;">在线安装</a></p></div><div class="thm"><div class="to"><p class="tu" style="background:url(http://www.twcms.com/app/theme/default_green.jpg)"></p><p class="full"><b>版本号：1.0.0</b><b>更新于：2014.01.23</b>由小乐设计的效果图，空城编写代码。兼容IE6/IE7/IE8/IE9/IE10 Chrome Firefox Opera Safari Ipad</p></div><h1>默认主题(绿色)(default_green)</h1><p class="author">作者: <a target="_blank" href="http://www.twcms.com">小乐、空城</a></p><div class="star_holder" title="共评价 1 次"><div class="star_rating"></div></div><p class="gsm" theme="default_green"><a class="but2" onclick="install_theme(\'default_green\')" href="javascript:;">在线安装</a></p></div><div class="thm"><div class="to"><p class="tu" style="background:url(http://www.twcms.com/app/theme/default_green2.jpg)"></p><p class="full"><b>版本号：1.0.0</b><b>更新于：2014.01.23</b>由小乐设计的效果图，空城编写代码。兼容IE6/IE7/IE8/IE9/IE10 Chrome Firefox Opera Safari Ipad</p></div><h1>默认主题(绿色2)(default_green2)</h1><p class="author">作者: <a target="_blank" href="http://www.twcms.com">小乐、空城</a></p><div class="star_holder" title="共评价 1 次"><div class="star_rating"></div></div><p class="gsm" theme="default_green2"><a class="but2" onclick="install_theme(\'default_green2\')" href="javascript:;">在线安装</a></p></div><div class="thm"><div class="to"><p class="tu" style="background:url(http://www.twcms.com/app/theme/default_orange.jpg)"></p><p class="full"><b>版本号：1.0.0</b><b>更新于：2014.01.23</b>由小乐设计的效果图，空城编写代码。兼容IE6/IE7/IE8/IE9/IE10 Chrome Firefox Opera Safari Ipad</p></div><h1>默认主题(橙色)(default_orange)</h1><p class="author">作者: <a target="_blank" href="http://www.twcms.com">小乐、空城</a></p><div class="star_holder" title="共评价 1 次"><div class="star_rating"></div></div><p class="gsm" theme="default_orange"><a class="but2" onclick="install_theme(\'default_orange\')" href="javascript:;">在线安装</a></p></div><div class="thm"><div class="to"><p class="tu" style="background: transparent url(&quot;http://www.twcms.com/app/theme/default_red.jpg&quot;) repeat scroll 0% 0%; display: block;"></p><p class="full" style="display: none;"><b>版本号：1.0.0</b><b>更新于：2014.01.23</b>由小乐设计的效果图，空城编写代码。兼容IE6/IE7/IE8/IE9/IE10 Chrome Firefox Opera Safari Ipad</p></div><h1>默认主题(红色)(default_red)</h1><p class="author">作者: <a target="_blank" href="http://www.twcms.com">小乐、空城</a></p><div class="star_holder" title="共评价 1 次"><div class="star_rating"></div></div><p class="gsm" theme="default_red">已安装</p></div>').css("padding-top", 0);
load();

// 已安装的主题，提示已经安装 (这段代码性能有点低)
$(".cc:eq(0) .gsm").each(function(){
	$(".cc:eq(1) .gsm[theme='"+ $(this).attr("theme") +"']").html("已安装");
});