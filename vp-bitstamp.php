<?php
function vparser($valute)
{
$error = "";
/* --- Парсим котировки Биткоин */
		$url = "https://www.bitstamp.net/api/ticker/";
		$page = file_get_contents($url);
		if(empty($page))
			$error = $error."<li>Нет доступа к странице или неверный адрес: ".$url."</li>";
		else
		{
/* --- Обработка ответа API (доллар) */
			$buffer = json_decode(trim($page), true);
/* --- Запись результатов в массив */
			if($buffer === NULL || $buffer === FALSE)
				$error = $error."<li>Данные не соответствуют формату JSON ".$url."</li>";
			else
			{
				$result["usd_btc_sale"] = trim(str_replace(",", ".", $buffer['bid']));
				$result["usd_btc_buy"] = trim(str_replace(",", ".", $buffer['ask']));
			};
		};
		$buffer = "";
if($valute != "usd") {	
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
$result = vparser($_POST["valute"]);
/*  Конвертер валют  */
	switch ($_POST["valute"]) {
	case "rur":
		$kotirovka = $result["usd_btc_sale"]*$result["usd_buy_sb"];
		break;
	case "usd":
		$kotirovka = $result["usd_btc_sale"];
		break;
	case "eur":
		$kotirovka = $result["usd_btc_sale"]*($result["usd_sale_sb"]/$result["eur_buy_sb"]);
		break;
	default:
		$kotirovka = 1;
		$error = $error."<li>Конвертер не работает с валютой ".$valute."</li>";	
	};
echo $kotirovka;
?>
