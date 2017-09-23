<?php defined('HOM_NAME') || exit('Access Denied'); 
?><!doctype html>
<head>
<base href="<?php echo(isset($C['hostname']) ? $C['hostname'] : ''); ?>" ></base>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<link href="http://wdt.my.com/tpl/admin/default/css/global.css" type="text/css" rel="stylesheet" />
<script src="http://wdt.my.com/tpl/admin/default/js/jquery.js" type="text/javascript"></script>
<script src="http://wdt.my.com/tpl/admin/default/js/global.js" type="text/javascript"></script>
<script type="text/javascript">
	var pKey = "<?php echo(isset($_pkey) ? $_pkey : ''); ?>", urlKey = "<?php echo(isset($_ukey) ? $_ukey : ''); ?>", place = "<?php echo(isset($_place) ? $_place : ''); ?>";
</script>
<title><?php echo(isset($_title) ? $_title : ''); ?></title>
</head>

<body>

<div class="m">
	<div class="p">
		<form id="article_set" action="http://<?php echo(isset($C['hostname']) ? $C['hostname'] : ''); ?>/index.php?u=article-<?php echo(isset($_GET['action']) ? $_GET['action'] : ''); ?>-ajax-1" method="post">
		<input name="id" type="hidden" value="<?php echo(isset($data['id']) ? $data['id'] : ''); ?>" />
		<input name="color" type="hidden" value="<?php echo(isset($data['color']) ? $data['color'] : ''); ?>" />
		<input name="seo_title" type="hidden" value="<?php echo(isset($data['seo_title']) ? $data['seo_title'] : ''); ?>" />
		<div class="cc contadd">
			<table><tr valign="top">
				<td><table class="tb tl">
					<tr>
						<th class="th"><div style="width:65px"><b>*</b>分类</div></th>
						<td class="col">
							<?php echo(isset($cidhtml) ? $cidhtml : ''); ?>
						</td>
					</tr>
					<tr>
						<th class="th"><b>*</b>标题</th>
						<td class="col"><input name="title" type="text" value="<?php echo(isset($data['title']) ? $data['title'] : ''); ?>" class="inp w98" /></td>
					</tr>
					<tr>
						<th class="th"><b>*</b>内容</th>
						<td class="col">
							<textarea id="content" class="inp w98" name="content" style="height:80px"><?php echo(isset($data['content']) ? $data['content'] : ''); ?></textarea>
							<div class="addition">
								<label title="远程抓取图片比较消耗服务器资源，影响发布速度">
									<input name="isremote" type="checkbox" value="1"> 远程图片本地化
								</label>
							</div>
						</td>
					</tr>
					<tr>
						<th class="th">别名</th>
						<td class="col"><input name="alias" type="text" value="<?php echo(isset($data['alias']) ? $data['alias'] : ''); ?>" class="inp w1" /></td>
					</tr>
					<tr>
						<th class="th">标签</th>
						<td class="col"><input name="tags" type="text" value="<?php echo(isset($data['tags']) ? $data['tags'] : ''); ?>" class="inp w1" /></td>
					</tr>
					<tr>
						<th class="th">摘要</th>
						<td class="col">
							<textarea name="intro" class="inp w98" style="height:60px" titile="如果不填，程序自动生成摘要" maxlength="255"><?php echo(isset($data['intro']) ? $data['intro'] : ''); ?></textarea>
						</td>
					</tr>
					<tr>
						<th class="th"></th>
						<td class="col"><input type="submit" value="&#20445;&#23384;" class="but1" style="margin:0" /></td>
					</tr>
				</table></td>
				<td width="398"><table class="tb">
					<tr>
						<th class="th"><div style="width:90px">缩略图</div></th>
						<td class="col">
							<input id="gf_pic_val" name="pic" type="hidden" value="<?php echo(isset($data['pic']) ? $data['pic'] : ''); ?>" />
							<div class="picimg"><img id="gf_pic_img" src="<?php echo(isset($data['pic_src']) ? $data['pic_src'] : ''); ?>" /></div>
							<div class="picbut"><a id="gf_pic_but" class="but3" href="javascript:;">上传缩略图</a></div>
						</td>
					</tr>
					<tr>
						<th class="th">属性</th>
						<td class="col">
							<label><input class="mr3" name="flag[]" type="checkbox" value="1"<?php if (in_array(1,$data['flags'])) { ?> checked<?php } ?>>推荐</label>
							<label><input class="mr3" name="flag[]" type="checkbox" value="2"<?php if (in_array(2,$data['flags'])) { ?> checked<?php } ?>>热点</label>
							<label><input class="mr3" name="flag[]" type="checkbox" value="3"<?php if (in_array(3,$data['flags'])) { ?> checked<?php } ?>>头条</label>
							<label><input class="mr3" name="flag[]" type="checkbox" value="4"<?php if (in_array(4,$data['flags'])) { ?> checked<?php } ?>>精选</label>
							<label><input class="mr3" name="flag[]" type="checkbox" value="5"<?php if (in_array(5,$data['flags'])) { ?> checked<?php } ?>>幻灯</label>
						</td>
					</tr>
					<tr>
						<th class="th">作者</th>
						<td class="col"><input name="author" type="text" value="<?php echo(isset($data['author']) ? $data['author'] : ''); ?>" class="inp w1" /></td>
					</tr>
					<tr>
						<th class="th">来源</th>
						<td class="col"><input name="source" type="text" value="<?php echo(isset($data['source']) ? $data['source'] : ''); ?>" class="inp w1" /></td>
					</tr>
					<?php if ($_GET['action'] == 'edit') { ?>
					<tr>
						<th class="th">发布时间</th>
						<td class="col"><input name="dateline" type="text" value="<?php echo(isset($data['dateline']) ? $data['dateline'] : ''); ?>" class="inp w1" /></td>
					</tr>
					<?php } ?>
					<tr>
						<th class="th">浏览次数</th>
						<td class="col"><input name="views" type="text" value="<?php echo(isset($data['views']) ? $data['views'] : ''); ?>" class="inp w1" /></td>
					</tr>
					<tr>
						<th class="th">禁止评论</th>
						<td class="col"><input name="iscomment" type="checkbox" value="1"<?php if (!empty($data['iscomment'])) { ?> checked="checked"<?php } ?>></td>
					</tr>
					<tr>
						<th class="th">SEO关键字</th>
						<td class="col"><input name="seo_keywords" type="text" value="<?php echo(isset($data['seo_keywords']) ? $data['seo_keywords'] : ''); ?>" class="inp w1" /></td>
					</tr>
					<tr>
						<th class="th">SEO描述</th>
						<td class="col"><input name="seo_description" type="text" value="<?php echo(isset($data['seo_description']) ? $data['seo_description'] : ''); ?>" class="inp w1" /></td>
					</tr>
				</table></td>
			</tr></table>
		</div>
		</form>
	</div>
</div>

<iframe name="gf_upifr" style="display:none"></iframe>
<form id="gf_pic_upform" target="gf_upifr" method="post" enctype="multipart/form-data" action="http://<?php echo(isset($C['hostname']) ? $C['hostname'] : ''); ?>/index.php?u=attach-upload_image&type=pic<?php echo(isset($edit_cid_id) ? $edit_cid_id : ''); ?>">
	<input id="gf_pic_upfile" type="file" name="upfile" accept="image/jpg,image/jpeg,image/png,image/gif" />
</form>
<form id="gf_img_upform" target="gf_upifr" method="post" enctype="multipart/form-data" action="http://<?php echo(isset($C['hostname']) ? $C['hostname'] : ''); ?>/index.php?u=attach-upload_image&type=img<?php echo(isset($edit_cid_id) ? $edit_cid_id : ''); ?>">
	<input id="gf_img_upfile" type="file" name="upfile" accept="image/jpg,image/jpeg,image/png,image/gif" multiple="multiple" />
</form>

<script type="text/javascript">
// 编辑器API
window.editor_api = {
	// 内容
	content : {
		obj : $('#content'),
		get : function() {
			return this.obj.val();
		},
		set : function(s) {
			return this.obj.val(s);
		},
		focus : function() {
			return this.obj.focus();
		}
	}
}

// 自动保存
var action = "<?php echo(isset($_GET['action']) ? $_GET['action'] : ''); ?>";
window.autoSave = function() {
	if(action != "add") return;
	var data = $("#article_set").serialize();
	gfAjax.post("index.php?u=article-auto_save-ajax-1", data, function(html) { });
};

// 传统上传缩略图
document.getElementById("gf_pic_upfile").onchange = function() {
	document.getElementById("gf_pic_upform").submit();
};

// 设置显示缩略图 (iframe使用)
function setDisplayPic(path, thumb) {
	$("#gf_pic_val").val(thumb);
	$("#gf_pic_img").attr("src", "../"+thumb);
	autoSave();
}

// 定位缩略图上传按钮
function setPicBut() {
	var obj = $("#gf_pic_but"),
		p = obj.position(),
		w = obj.outerWidth(true),
		h = obj.outerHeight(true),
		r = $(document).width() - w - p.left;
	$("#gf_pic_upfile").hover(function(){
		obj.addClass("but_on");
	}, function(){
		obj.removeClass("but_on");
	}).show().css({"position":"absolute", "top":p.top, "right":r, "width":w, "height":h});
}

setPicBut();

// 触发自动保存
$("#article_set input, #article_set textarea, #article_set select").change(autoSave);

// 提交表单
gfAjax.submit("#article_set", function(data){
	gfAjax.alert(data);
	if(window.gfData.err==0) {
		setTimeout(function(){
			var i = P("#box_tab ul li[urlKey='article-index']").index();
			if(i != -1) parent.ifrRefresh(i);
			parent.oneTab("article-index");

			var j = P("#box_tab ul li[urlKey='"+urlKey+"']").index();
			parent.rmTab(j);
		}, 1200);
	}
});
</script>




</body>
</html>
