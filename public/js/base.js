$(function(){

	$(document).on('change', ".js_img", function (e){
		let cls = $(this).parent().next().attr('class');
		let tcls = '.' + cls;
		let reader = new FileReader();
		reader.onload = function(e){
			$(tcls).attr('src', e.target.result);
		}
		reader.readAsDataURL(e.target.files[0]);
		$(this).parent().css('display', 'none');
		let ncls = cls.slice(0, -1);
		let num = String(Number(cls.slice(-1)) + 1);
		tag = "<span class='dlt'>削除</span>";
		$(this).parent().parent().append(tag);
		$(this).parent().parent().addClass('img_size');
		tag = "<div class='imgs'><label class='label_img'>画像を追加";
		tag += "<input class='upimg js_img' type='file' name='image[]' accept='image/*'></label>";
		tag += "<img class='"+ (ncls + num) + "'></div>";
		$(this).parent().parent().after(tag);
	});

	$(document).on('click', ".dlt", function(){
		$(this).parent().remove();
	});

	$(".js_open").on('click', function(){
		let url = $(this).attr('href');
		event.preventDefault();
		$(".js_modal_open").show();
		$(".modal_ok").on('click', function(){
			if(document.URL.match(/index/)){
				window.location.href = url;
			}
			if(document.URL.match(/edit/) || document.URL.match(/userdetail/)
				|| document.URL.match(/attendance/) || document.URL.match(/deleteaccount/)){
				document.update.submit();
			}
		});
		$(".js_modal_close").on('click', function () {
			$(".js_modal_open").hide();
		});
	});
});