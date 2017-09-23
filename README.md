## 功夫网站内容管理系统(GFCMS)，基于PHP+MySQLi的技术架构。

#### GFCMS2.0定位于高安全、高性能、高扩展、高SEO、高傻瓜化。

```txt
GFCMS2.0目录结构
	|--control					控制器
	|--model					模型
	|--config					配置
	|--framework				框架
	|--log						日志
	|--runtime					运行
		|--home_control			前台控制器编译缓存
		|--home_model			前台模型编译缓存
		|--home_view			前台视图编译缓存
		|--home_view_diy		前台DIY视图编译缓存
		|--admin_control		后台控制器编译缓存
		|--admin_model			后台模型编译缓存
		|--admin_view			后台视图编译缓存
	|--view						视图目录
		|--install				安装目录
		|--tpl					模板目录
			|--plugin				插件
			|--admin					后台项目
				|--block				模块
				|--control				控制器
				|--view					视图
			|--home						前台项目
				|--block				模块
				|--control				控制器
				|--view					视图
		|--web					静态页面
		|--upload					上传文件
```


**GFCMS2.0简易模板引擎(共8个标签)**

标签|注释
---|---
{inc:header.htm}					|包含模板
{hook:header_before.htm}			|模板钩子(方便插件修改模板)
{php}{/php}						|模板支持PHP代码 (不支持<??><?php?>的写法)
{block:}{/block}					|模板模块
{loop:}{/loop}					|数组遍历
{if:} {else} {eleseif:} {/if}	|逻辑判断
{$变量}							|显示变量
{@$k+1}							|显示逻辑变量 (用于运算时的输出，一般用的很少)
