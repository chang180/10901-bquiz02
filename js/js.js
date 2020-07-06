// JavaScript Document
function lo(th, url) {
	$.ajax(url, { cache: false, success: function (x) { $(th).html(x) } })
}
function good(id, type, user) {
	// $.post("back.php?do=good&type="+type,{"id":id,"user":user},function() //舊版本jquery寫法，要改一改
	// {

	$.post("api/good.php", { id, type, user }, function () {

		if (type == "1") {
			$("#vie" + id).text($("#vie" + id).text() * 1 + 1)
			$("#good" + id).text("收回讚").attr("onclick", "good('" + id + "','2','" + user + "')")
		}
		else {
			$("#vie" + id).text($("#vie" + id).text() * 1 - 1)
			$("#good" + id).text("讚").attr("onclick", "good('" + id + "','1','" + user + "')")
		}
	})
	// })
}