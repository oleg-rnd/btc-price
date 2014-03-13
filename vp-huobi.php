<?php
// Парсер курса валют ЦБ
function currency_cbr_get_rates($char_code) {
$url = "http://www.cbr.ru/scripts/XML_daily.asp"; // URL, XML документ, всегда содержит актуальные данные
$curs = array(); // массив с данными
if(!$xml=simplexml_load_file($url)) die('Ошибка загрузки XML'); // загружаем полученный документ в дерево XML
foreach($xml->Valute as $v){ // перебор всех значений
	if($v->CharCode == $char_code){
		$curs[(string)$v->CharCode]=(float)str_replace(",", ".", (string)$v->Value); // запись значений в массив
		$curs["Nominal"]=(int)str_replace(",", ".", (string)$v->Nominal);
	};
};
return $curs;
};

function vparser($valute)
{
$error = "";
// Получаем котировку CNY-Bitcoin
$url = "http://market.huobi.com/staticmarket/detail.html";
//$url = "test1.txt";
$page = file_get_contents($url);
if(empty($page))
	$error = $error."<li>Нет доступа к странице или неверный адрес: ".$url."</li>";
else
{
/* --- Обработка ответа API (юань) */
	$page = str_replace("view_detail(", "", $page);
	$page = str_replace(")", "", $page);
	$buffer = json_decode(trim($page), true);
	//$regexp = "/view_detail\(\{\"sells\"\:\[\{\"price\"\:[\"]*(.*)[,\"]/Us";
	//$regexp = "/view_detail\(\{\"sells\"\:\[\{\"price\"\:(.*),/Us";
	//preg_match_all($regexp, $page, $buffer);
/* --- Запись результатов парсинга в массив */
	// удаляем кавычки, заменяем запятую на точку, обрезаем пробелы
	//$result["cny_btc_sale"] = trim(str_replace(",", ".", str_replace("\"", "", $buffer[1][0])));
	$result["cny_btc_sale"] = trim(str_replace(",", ".", $buffer["sells"][0]["price"]));
};
$buffer = "";
// Перевод юаней в рубли и затем в доллары
$currency_cbr = currency_cbr_get_rates("CNY");
$result["rur_btc_sale"] = $result["cny_btc_sale"]*$currency_cbr["CNY"]/$currency_cbr["Nominal"];

if($valute != "rur") {
/* --- Котировки валют СБР */
	$url = "http://www.sberbank.ru/moscow/ru/quotes/currencies/";
	$page = file_get_contents($url);
	if(empty($page))
		$error = $error."<li>Нет доступа к странице или неверный адрес: ".$url."</li>";
	else
	{
/* --- Обработка контента со страницы */
		//$page = iconv("UTF-8", "WINDOWS-1251//IGNORE", $page);
		$regexp = "/<table class=\\\"table3_eggs4\\\"(?:.*)>(.*)<\/table>/Us";
		preg_match_all($regexp, $page, $buffer1);

		$regexp = "/<td style=\\\"vertical-align:middle;font-size:16px(?:.*)>(.*)<\/td>/Us";
		preg_match_all($regexp, $buffer1[1][0], $buffer);

/* --- Запись результатов парсинга в массив */
		$result["usd_sale_sb"] = trim(str_replace(",", ".", $buffer[1][0]));
		$result["usd_buy_sb"] = trim(str_replace(",", ".", $buffer[1][1]));
		$result["eur_sale_sb"] = trim(str_replace(",", ".", $buffer[1][2]));
		$result["eur_buy_sb"] = trim(str_replace(",", ".", $buffer[1][3]));
	};
$buffer = "";	
}; 
if(empty($error))
	return $result;
else
	return $error;
};
?>

<?php
//$_POST["valute"] = "usd";
$result = vparser($_POST["valute"]);
/*  Конвертер валют  */
	switch ($_POST["valute"]) {
	case "rur":
		$kotirovka = $result["rur_btc_sale"];
		break;
	case "usd":
		$kotirovka = $result["rur_btc_sale"]/$result["usd_sale_sb"];
		break;
	case "eur":
		$kotirovka = $result["rur_btc_sale"]/$result["eur_sale_sb"];
		break;
	case "cny":
		$kotirovka = $result["cny_btc_sale"];
		break;
	default:
		$kotirovka = 1;
		$error = $error."<li>Конвертер не работает с валютой ".$valute."</li>";	
	};
echo $kotirovka;
?>
