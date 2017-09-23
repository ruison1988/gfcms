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
	<div class="head">
		<dl>
			<input id="add" type="button" value="发布" class="but1" />
			<dt style="display:inline;float:left;margin-right:8px"><?php echo(isset($cidhtml) ? $cidhtml : ''); ?></dt>
			<dt style="display:inline;float:right">
				<form class="search" method="post" action="http://<?php echo(isset($C['hostname']) ? $C['hostname'] : ''); ?>/index.php?u=article-index">
				<input type="submit" value="搜索文章" class="but1" style="float:right;margin:0 8px 0 5px">
				<input type="search" name="keyword" value="<?php echo(isset($keyword) ? $keyword : ''); ?>" style="float:right;width:150px;padding:1px">
				</form>
			</dt>
		</dl>
	</div>

	<div class="p c2">
		<div class="cc">
			<?php if (empty($cms_article_arr)) { ?><div class="sky warning bnote"><i></i><b>暂无内容</b></div><?php }else{ ?>
			<table class="tb2">
				<tr class="tit">
					<th class="th" width="25"><input type="checkbox" id="check_all"></th>
					<th class="th">标题</th>
					<th class="th" width="110">作者</th>
					<th class="th" width="100">评论数</th>
					<th class="th" width="150">发布时间</th>
					<th class="th" width="150">操作</th>
				</tr>

				<?php if(isset($cms_article_arr) && is_array($cms_article_arr)) { foreach($cms_article_arr as &$v) { ?>
				<tr class="li">
					<td class="col"><input type="checkbox" name="chk_row" _id="<?php echo(isset($v['id']) ? $v['id'] : ''); ?>" _cid="<?php echo(isset($v['cid']) ? $v['cid'] : ''); ?>"></td>
					<td class="col"><?php echo(isset($v['title']) ? $v['title'] : ''); echo(isset($v['flagstr']) ? $v['flagstr'] : ''); ?></td>
					<td class="col"><?php echo(isset($v['author']) ? $v['author'] : ''); ?></td>
					<td class="col"><?php echo(isset($v['comments']) ? $v['comments'] : ''); ?></td>
					<td class="col"><?php  echo date('Y-m-d H:i:s', $v['dateline']); ?></td>
					<td class="col">
						<a class="but3" href="javascript:edit(<?php echo(isset($v['id']) ? $v['id'] : ''); ?>, <?php echo(isset($v['cid']) ? $v['cid'] : ''); ?>);">编辑</a>
						<a class="but3" href="<?php echo(isset($v['url']) ? $v['url'] : ''); ?>" target="_blank">查看</a>
						<a class="but3 del" href="javascript:del(<?php echo(isset($v['id']) ? $v['id'] : ''); ?>, <?php echo(isset($v['cid']) ? $v['cid'] : ''); ?>);">删除</a>
					</td>
				</tr>
				<?php }} ?>
			</table>

			<div class="page cf"><span>共 <font color="red"><?php echo(isset($total) ? $total : ''); ?></font> 篇</span><?php echo(isset($pages) ? $pages : ''); ?></div>
			<?php } ?>
		</div>
	</div>
</div>
<script type="text/javascript">
$("#add").click(function(){
	parent.oneTab("article-add");
});

$("#cid").change(function(){
	location.href = "index.php?u=article-index-cid-"+ $(this).val();
});

//全选
$("#check_all").click(function(){
	var bool = $(this)[0].checked;
	var len = $("input[name='chk_row']").length;
	for(var i=0; i<len; i++) {
		$("input[name='chk_row']").eq(i)[0].checked = bool;
	}
	bool ? buttonDis() : $("#batch_del").remove();
});

//是否显示删除按钮
$("input[name='chk_row']").click(function(){
	var len = $("input[name='chk_row']:checked").length;
	len ? buttonDis() : $("#batch_del").remove();
});

//显示删除按钮
function buttonDis() {
	if(!$("#batch_del").length) $(".head>dl").append('<input id="batch_del" onclick="batch_del()" type="button" value="批量删除" class="but1" />');
}

//编辑
function edit(id, cid) {
	parent.oneTab("article-edit-cid-"+cid+"-id-"+id);
}

//删除
function del(id, cid) {
	gfAjax.confirm("删除不可恢复，确定删除？", function(){
		gfAjax.postd("index.php?u=article-del-ajax-1", {"id":id, "cid":cid}, function(data){
			gfAjax.alert(data);
			if(window.gfData.err==0) setTimeout(function(){ window.location.reload(); }, 1000);
		});
	});
}

//批量删除
function batch_del() {
	gfAjax.confirm("删除不可恢复，确定删除？", function(){
		var id_arr = {};
		var len = $("input[name='chk_row']").length;
		for(var i=0; i<len; i++) {
			var obj = $("input[name='chk_row']").eq(i);
			if(obj[0].checked) {
				id_arr[i] = [obj.attr("_id"), obj.attr("_cid")];
			}
		}
		gfAjax.postd("index.php?u=article-batch_del-ajax-1", {"id_arr":id_arr}, function(data){
			gfAjax.alert(data);
			if(window.gfData.err==0) setTimeout(function(){ window.location.reload(); }, 1000);
		});
	});
}
</script>
</body>
</html>
