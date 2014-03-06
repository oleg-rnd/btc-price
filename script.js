<script type="text/javascript">
function vconvertor(kotirovka, name_class) {
/* Конвертация валюты в биткоин и замена цен */
var regexp = /(\d+.)/ig;
var old_val = 0;
$('.'+name_class).each(function () {
	old_val = $(this).html();
	old_val = old_val.replace(",", ".");
	old_val = old_val.replace(" ", "");
	old_val = old_val.match(regexp);
	old_val = parseFloat(old_val);
	new_val = (old_val / kotirovka).toFixed(4);
	//new_val = old_val;
	$(this).removeClass("active").addClass("noactive");
	$(this).next($('.btc_price')).text(new_val+' BTC').removeClass("noactive").addClass("active");
});
};	 
$(document).ready(function(){
var page_valute = "usd"; // начальная валюта на странице (usd, eur, rur, cny)
var url_parser = "vp-btce.php"; // парсер для получения котировок (выбор биржи)
//var url_parser = "vp-bitstamp.php";
//var url_parser = "vp-huobi.php";
var name_class = "price"; // класс контейнера с ценой
var time_int = 20000; // значение интервала между запусками (мс)

$('.'+name_class).after(function(index){
    return '<span class="btc_price noactive"></span>';
});

var timer = 0;

$('#start_convert').click(function(){
// Запуск конвертора по таймеру после клика
timer = setTimeout(function run() {
$.ajax({
  type: "POST",
  url: url_parser,
  data: "valute="+page_valute,
  success: function(kotirovka){
	vconvertor(kotirovka, name_class);
    /*alert("Котировка получена: " + kotirovka);*/
  }
});
    timer = setTimeout(run, time_int);
  }, 10);
});

$('#restart_convert').click(function(){
	$('.'+name_class).removeClass("noactive").addClass("active");
	$('.btc_price').removeClass("active").addClass("noactive");
	clearTimeout(timer); // остановка запуска конвертора по таймеру
});

});
</script>
