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
		<form id="product_set" action="http://<?php echo(isset($C['hostname']) ? $C['hostname'] : ''); ?>/index.php?u=product-<?php echo(isset($_GET['action']) ? $_GET['action'] : ''); ?>-ajax-1" method="post">
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
						<th class="th"><b>*</b>图集</th>
						<td class="col">
							<div id="gf_dropbox" class="cf">
								<?php if(isset($data['images']) && is_array($data['images'])) { foreach($data['images'] as $k=>&$v) { ?>
								<div class="d_li">
									<div class="d_img"><img src="../<?php echo(isset($v['thumb']) ? $v['thumb'] : ''); ?>" height="120" width="120" style="margin-top: -10px;"></div>
									<div class="d_txt">
										<input name="images[<?php echo(isset($k) ? $k : ''); ?>][big]" value="<?php echo(isset($v['big']) ? $v['big'] : ''); ?>" type="hidden">
										<input name="images[<?php echo(isset($k) ? $k : ''); ?>][thumb]" value="<?php echo(isset($v['thumb']) ? $v['thumb'] : ''); ?>" type="hidden">
										<input name="images[<?php echo(isset($k) ? $k : ''); ?>][aid]" value="<?php echo(isset($v['aid']) ? $v['aid'] : ''); ?>" type="hidden">
										<textarea name="images[<?php echo(isset($k) ? $k : ''); ?>][content]" placeholder="请输入描述"><?php echo htmlspecialchars($v['content']); ?></textarea>
									</div>
									<div class="d_del" aid="<?php echo(isset($v['aid']) ? $v['aid'] : ''); ?>">删除</div>
								</div>
								<?php }} ?>
								<div id="gf_imgup" class="d_li2">
									<div><a id="gf_img_but" class="but3" href="javascript:;">上传图片</a></div>
									<div id="gf_img_tips"></div>
								</div>
							</div>
						</td>
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
	var data = $("#product_set").serialize();
	gfAjax.post("index.php?u=product-auto_save-ajax-1", data, function(html) { });
};

// 编辑时保存图集
window.editSaveImg = function() {
	if(action != "edit") return;
	var data = $("#product_set").serialize();
	gfAjax.post("index.php?u=product-save_images-ajax-1", data, function(html) { });
};

// 传统上传缩略图
document.getElementById("gf_pic_upfile").onchange = function() {
	document.getElementById("gf_pic_upform").submit();
};

//====拖拽图片上传====
window.isDrop = !!window.FileReader;
var kpUpload = {
	init : function() {
		if(!window.TW_UP) window.TW_UP = true;

		if(isDrop) {
			$("#gf_img_tips").html("支持拖拽上传");

			//ajax上传图集
			$("#gf_img_upfile").change(function() {
				$("#gf_img_upfile").hide();
				kpUpload.ajaxUpload(this.files);
			});

			//加载 HTML5 拖拽上传图集
			kpUpload.ondrag();
		}else{
			$("#gf_imgup div").css("padding-top", "10px");
			$("#gf_img_tips").html("您的浏览器太老，不支持拖拽上传，建议使用IE10以上版本或chrome");

			//传统上传图集
			document.getElementById("gf_img_upfile").onchange = function() {
				document.getElementById("gf_img_upform").submit();
			};
		}
	},

	//绑定拖拽事件  提示: ondragover(当元素被拖动至有效拖放目标上方时运行脚本) ondrop(当拖动元素时运行脚本)是必须事件，否则拖拽不正常
	ondrag : function() {
		var box = document.getElementById("gf_dropbox");
		var handleDragover = function(e) {
			$(box).css("border-color", "#808080");
			e.stopPropagation();
			e.preventDefault();
		}
		var handlePrevent = function(e) {
			e.stopPropagation();
			e.preventDefault();
		}
		var handleDragleave = function(e) { //当元素离开有效拖放目标时运行脚本
			$(box).css("border-color", "#ccc");
			e.preventDefault();
		}
		document.ondragleave = handleDragleave;
		document.ondragover = handleDragover;
		document.ondrop = handlePrevent;
		if(window.parent) {
			parent.document.ondragover = handleDragover;
			parent.document.ondrop = handlePrevent;
		}
		box.ondragover = handleDragover;
		box.ondrop = function(e){
			$(box).css("border-color", "#ccc");
			$("#gf_img_upfile").hide();
			e.stopPropagation();
			e.preventDefault();
			kpUpload.ajaxUpload(e.dataTransfer.files);
		};
	},

	// 缩放图片尺寸
	scaleImg : function (img, maxW, maxH) {
		var width = 0, height = 0, percent, ow = img.width, oh = img.height;
		if(ow > maxW || oh > maxH) {
			if(ow >= oh) {
				var width = ow - maxW;
				if(width >= 0) {
					percent = (width / ow).toFixed(2);
					var h = oh - oh * percent;
					img.height = h;
					img.width = maxW;
					img.style.marginTop = (maxH-h)/2+"px";
				}
			}else{
				if(height = oh - maxH) {
					percent = (height / oh).toFixed(2);
					img.width = ow - ow * percent;
					img.height = maxH;
				}
			}
		}
	},

	//增加图片显示
	boxAdd : function(file, big, thumb, aid) {
		var img = document.createElement("img");
		var div = $('<div class="d_img"></div>').append(img);
		var inps = '<input name="images['+aid+'][big]" value="'+big+'" type="hidden" />';
			inps += '<input name="images['+aid+'][thumb]" value="'+thumb+'" type="hidden" />';
			inps += '<input name="images['+aid+'][aid]" value="'+aid+'" type="hidden" />'; // 用来删除时使用
			inps += '<textarea name="images['+aid+'][content]" placeholder="请输入描述"></textarea>';
		var d_li = $('<div class="d_li"><div class="d_txt">'+inps+'</div><div class="d_del" aid="'+aid+'">删除</div></div>').prepend(div);
		$("#gf_imgup").before(d_li);

		$("#gf_dropbox").kpdragsort(); // 重新绑定拖拽事件
		setdelImg(); // 重新绑定删除事件

		autoSave(); // 发布时会自动保存上传的图集
		editSaveImg(); // 在编辑时，保存一下

		if(typeof file == "object") {
			var reader = new FileReader();
			reader.readAsDataURL(file);
			reader.onload = function(e) {
				img.src = e.target.result;
				img.onload = function() {
					kpUpload.scaleImg(this, 120, 100);
				}
			};
		}else if(typeof file == "string") {
			img.src = file;
			img.onload = function() {
				kpUpload.scaleImg(this, 120, 100);
			}
		}
	},

	// 判断是否图片
	isImg : function(type) {
		switch (type) {
			case 'image/jpg':
			case 'image/jpeg':
			case 'image/gif':
			case 'image/png':
				return true;
			default:
				return false;
		}
	},

	//按顺序ajax上传
	ajaxUpload : function(files) {
		var upload = function(i) {
			if(typeof files[i] == 'object') {
				var f = files[i];

				if(!kpUpload.isImg(f.type)) {
					upload(i+1);
					return;
				}

				var xhr = new XMLHttpRequest();
				xhr.open("post", "index.php?u=attach-upload_image&type=img&ajax=1<?php echo(isset($edit_cid_id) ? $edit_cid_id : ''); ?>", true);
				xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");

				//模拟数据
				var fd = new FormData();
				fd.append("upfile", f);
				xhr.send(fd);

				xhr.addEventListener('load', function (e) {
					var data = toJson(e.target.response);

					if(data.state == "SUCCESS") {
						kpUpload.boxAdd("../"+data.thumb, data.path, data.thumb, data.aid);
						setTimeout(function(){ upload(i+1); }, 50);

						if(i == files.length - 1) {
							setImgBut(); // 图集上传按钮
						}
					}else{
						setImgBut();
					}
				});
			}
		}
		upload(0);
	}
};

//拖拽插件
$.fn.kpdragsort = function(options) {
	var container = this;

	$(container).children(".d_li").off("mousedown").mousedown(function(e) {
		if(e.which != 1 || $(e.target).is("input, textarea, a") || window.kp_only) return; // 排除非左击和其他元素
		e.preventDefault(); // 阻止选中文本

		var x = e.pageX;
		var y = e.pageY;
		var _this = $(this); // 点击选中块
		var w = _this.width();
		var h = _this.height();
		var w2 = w/2;
		var h2 = h/2;
		var p = _this.position();
		var left = p.left;
		var top = p.top;
		var sTop = $(".p:first").scrollTop();
		window.kp_only = true;

		// 添加虚线框
		_this.before('<div id="kp_widget_holder"></div>');
		var wid = $("#kp_widget_holder");
		wid.css({"border":"1px dashed #ccc", "float":"left", "width":"120px", "height":"120px", "margin":"5px 0 0 5px"});

		// 保持原来的宽高
		_this.css({"width":w, "height":h, "position":"absolute", opacity: 0.8, "z-index": 999, "left":left, "top":top});

		// 绑定mousemove事件
		$(document).mousemove(function(e) {
			e.preventDefault();

			// 移动选中块
			var l = left + e.pageX - x;
			var t = top + ($(".p:first").scrollTop() - sTop) + e.pageY - y;
			_this.css({"left":l, "top":t});

			// 选中块的中心坐标
			var ml = l+w2;
			var mt = t+h2;

			// 遍历所有块的坐标
			$(container).children(".d_li").not(_this).not(wid).each(function(i) {
				var obj = $(this);
				var p = obj.position();
				var a1 = p.left;
				var a2 = p.left + obj.width();
				var a3 = p.top;
				var a4 = p.top + obj.height();

				// 移动虚线框
				if(a1 < ml && ml < a2 && a3 < mt && mt < a4) {
					if(!obj.next("#kp_widget_holder").length) {
						wid.insertAfter(this);
					}else{
						wid.insertBefore(this);
					}
					return;
				}
			});
		});

		// 绑定mouseup事件
		var _mouseup = function() {
			$(document).off('mouseup').off('mousemove');

			// 拖拽回位，并删除虚线框
			var p = wid.position();
			_this.animate({"left":p.left, "top":p.top}, 100, function() {
				_this.removeAttr("style");
				wid.replaceWith(_this);

				setImgBut(); // 重新定位上传按钮
				autoSave(); // 在发表时，保存一下
				editSaveImg(); // 在编辑时，保存一下

				window.kp_only = null;
				if(parent) parent.document.onmouseup = null;
			});
		};
		$(document).mouseup(_mouseup);
		if(parent) parent.document.onmouseup = _mouseup;
	});
}

// 设置显示缩略图 (iframe使用)
function setDisplayPic(path, thumb) {
	$("#gf_pic_val").val(thumb);
	$("#gf_pic_img").attr("src", "../"+thumb);
	autoSave();
}

// 图集中插入新图 (iframe使用)
function setDisplayImg(path, thumb, aid) {
	$("#gf_img_upfile").hide();
	kpUpload.boxAdd("../"+thumb, path, thumb, aid);
	setImgBut(); // 重新定位上传按钮
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

// 定位图集上传按钮
function setImgBut() {
	if(!$("#gf_img_but").length) return;
	var isIE = !!window.ActiveXObject;
	var obj = $("#gf_img_but");
	var p = obj.position();
	var top = isIE  ? p.top-10 : p.top;
	$("#gf_img_upfile").hover(function(){
		obj.addClass("but_on");
	}, function(){
		obj.removeClass("but_on");
	}).show().css({"position":"absolute", "top":p.top, "left":p.left, "width":obj.outerWidth(true), "height":obj.outerHeight(true)});
}

// 删除单张图集的图片
function setdelImg() {
	$(".d_del").off("mousedown").mousedown(function(e) {
		e.stopPropagation(); // 清除事件冒泡，以免影响拖拽插件
	}).off("click").click(function() {
		var _me = $(this);
		var aid = _me.attr("aid");
		gfAjax.post("index.php?u=product-del_attach-ajax-1", {"aid":aid}, function(html) {
			// var data = toJson(html);

			// 历史遗留问题，以前是没有aid的，会导致删除失败
			// if(data.err == 0) {
				_me.parent().remove();
			// }

			setImgBut(); // 重新定位上传按钮
			autoSave(); // 在发表时，保存一下
			editSaveImg(); // 在编辑时，保存一下
		});
	});
}

// 定位按钮和加载图集上传
setPicBut();
setImgBut();
kpUpload.init();
$("#gf_dropbox").kpdragsort();
setdelImg();

// 触发自动保存
$("#product_set input, #product_set textarea, #product_set select").change(autoSave);

// 提交表单
gfAjax.submit("#product_set", function(data){
	gfAjax.alert(data);
	if(window.gfData.err==0) {
		setTimeout(function(){
			var i = P("#box_tab ul li[urlKey='product-index']").index();
			if(i != -1) parent.ifrRefresh(i);
			parent.oneTab("product-index");

			var j = P("#box_tab ul li[urlKey='"+urlKey+"']").index();
			parent.rmTab(j);
		}, 1200);
	}
});
</script>




</body>
</html>
