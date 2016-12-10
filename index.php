<head>
<style>
body {
	margin:0px;
}
div.sfbg, div.sfbgg {
    height: 39px;
}
.sfbgg {
    background-color: #f1f1f1;
    border-bottom: 1px solid #666;
    border-color: #e5e5e5;
	padding: 10px;
}
.sfibbbc {
    width: 638px;
	background-color: #fff;
    height: 38px;
    vertical-align: top;
    border: 1px solid #d9d9d9;
	margin-left: 115px;
}
.sfsbc {
    display: inline-block;
    float: right;
    vertical-align: top;
    width: 40px;
    margin-right: -1px;
}
.gsst_b {
    display: inline-block;
    float: right;
    vertical-align: top;
    width: 65px;
    margin-right: 40px;
	margin-top: -38px;
	background: url('featurebtn.png');
	height: 38px;
}
.lsb {
    background: transparent;
	background: url('srchbtn.png');
    border: 0;
    font-size: 0;
    height: 100%;
    outline: 0;
    width: 100%;
	z-index: 9999;
}
.lsb:active {
    background: transparent;
	background: url('srchbtn2.png');
    border: 0;
    font-size: 0;
    height: 100%;
    outline: 0;
    width: 100%;
	z-index: 9999;
}
.gsfi, .lst {
    font: 16px arial,sans-serif;
    line-height: 26px !important;
    height: 100%;
	width: calc(100% - 105px);
	padding: 10px;
	border: none;
}
.sbsb_a {
	width: 638px;
	background-color: #fff;
    vertical-align: top;
    border: 1px solid #d9d9d9;
	border-top: none;
	margin-left: 115px;
	box-shadow: 0 2px 4px #ccc;
    -webkit-box-shadow: 0 2px 4px #ccc;
}
.sbsb_c {
	font: 16px arial,sans-serif;
    line-height: 22px;
    overflow: hidden;
    padding: 0 10px;
}
.sbsb_c:hover {
	background: #efefef;
}
.logo {
	height: 50px;
	width: 110px;
	float: left;
	margin-top: -10px;
	margin-left: -10px;
	background: url('logo.png');
}
input:focus, select:focus,textarea:focus, button:focus {
    outline: none;
}
</style>
<script src="jquery-3.1.1.min.js"></script>
</head>
<script>
	var select_id = -1;
	var item_count = 0;
	var input;
	function LoadSuggetion() {		
		var suggest_form = $('#sbsb_a');
		var key = event.keyCode;
		if(key != 38 && key != 40) {
			select_id = -1;
			input = $('#search_form').val();
			if(input != '') {
				suggest_form.show();		
				$.post( "search.php",
					{ 'key': input },
					function( return_val ) {
				  $( "#sbsb_a" ).html( return_val );
				});
				
			} else {
				suggest_form.hide();
			}
		} else {
			item_count = $('.sbsb_c').length;
			if(key == 38) {
				if(select_id == -1) {
					select_id = item_count - 1;
				} else {
					select_id--;
				}
				$('.sbsb_c').css('background-color', '#fff');
				if(select_id == -1){
					$('#search_form').val(input);
				} else {
					$('#sugg_'+select_id).css('background-color', '#efefef');
					$('#search_form').val($('#sugg_'+select_id).text());
				}
			} else if(key == 40) {
				if(select_id == item_count - 1) {
					select_id = -1;
				} else {
					select_id++;
				}
				$('.sbsb_c').css('background-color', '#fff');
				if(select_id == -1){
					$('#search_form').val(input);
				} else {
					$('#sugg_'+select_id).css('background-color', '#efefef');
					$('#search_form').val($('#sugg_'+select_id).text());
				}
			}
		}
	}
</script>
<div class="sfbgg">
	<div class="logo"></div>
	<div class="sfibbbc">
		<input type="text" class="gsfi" id="search_form" onkeyup="LoadSuggetion(event)">
		<div class="sfsbc">
			<button class="lsb" value="Tìm kiếm" aria-label="Tìm với Google" name="btnG" type="submit"> 
			</button>
		</div>
		<div class="gsst_b"></div>
	</div>
	<div class="sbsb_a" id="sbsb_a" style="display: none">
	</div>
</div>
