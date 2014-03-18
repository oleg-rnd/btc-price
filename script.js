<script type="text/javascript">
// Проверка на число
function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
};
//var i = 0;
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
//i = i+1;
//$('.result').text(kotirovka+" "+i);
};	 
$(document).ready(function(){
var page_valute = "eur"; // начальная валюта на странице (usd, eur, rur, cny )
//var url_parser = "vp-huobi.php"; 
//  var url_parser = "vp-huobi-1.php";  альтернативный вариант, var url_parser = "vp-btce.php"; 
var url_parser = "vp-bitstamp.php"; // парсер для получения котировок (выбор биржи)
var name_class = "price"; // класс контейнера с ценой
var time_int = 2000; // значение интервала между запусками (мс)
var auto_run = false; // режим автозапуска (true или false)

$('.'+name_class).after(function(index){
    return '<span class="btc_price noactive"></span>';
});

var timer = 0;

if(auto_run) {
$(function(){
// Запуск конвертора в авто режиме
timer = setTimeout(function run() {
$.ajax({
  type: "POST",
  url: url_parser,
  data: "valute="+page_valute,
  success: function(kotirovka){
	if(isNumeric(kotirovka) && kotirovka != 0)
		vconvertor(kotirovka, name_class);
  }
});
    timer = setTimeout(run, time_int);
  }, 10);
});
};

$('#start_convert').click(function(){
// Запуск конвертора по таймеру после клика
timer = setTimeout(function run() {
$.ajax({
  type: "POST",
  url: url_parser,
  data: "valute="+page_valute,
  success: function(kotirovka){
	if(isNumeric(kotirovka) && kotirovka != 0)
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
