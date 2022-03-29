
<?php

// Подключаем клиент Google таблиц
require_once __DIR__ . '/vendor/autoload.php';

// Наш ключ доступа к сервисному аккаунту
$googleAccountKeyFilePath = __DIR__ . '/credentials.json';
putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $googleAccountKeyFilePath);

// Создаем новый клиент
$client = new Google_Client();
// Устанавливаем полномочия
$client->useApplicationDefaultCredentials();

// Добавляем область доступа к чтению, редактированию, созданию и удалению таблиц
$client->addScope('https://www.googleapis.com/auth/spreadsheets');

$service = new Google_Service_Sheets($client);

// ID таблицы
$spreadsheetId = '1GvdeP8-AdMjxk5u4mD22Sw6-eVm4J-o507muAoTtj8M';


$response = $service->spreadsheets_values->get($spreadsheetId, "A1:D");
$sheet_value = $response->getValues();

if (!empty($_POST["email"]) and !empty($_POST["category"]) and !empty($_POST["headline"]) and !empty($_POST["text"])) {

	$valueRange= new Google_Service_Sheets_ValueRange();
	$i = count((is_countable($sheet_value)?$sheet_value:[]))+1;
	$range = "A$i:D";
	$valueRange->setValues([[$_POST["category"], $_POST["email"], $_POST["headline"], $_POST["text"]]]);
	$conf = ["valueInputOption" => "RAW"];

	$service->spreadsheets_values->update($spreadsheetId, $range, $valueRange, $conf);
	$sheet_value[] = [$_POST["category"], $_POST["email"], $_POST["headline"], $_POST["text"]];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Lab_4</title>
</head>

<body>
<form action="l2.php" method="POST">
	<label>Тема (заголовок):
	<input type="text" name="Заголовок"><br>
	</label>
	<br> 
	<label>Категории:
	<select name="Категории">
		<option>Фрукты</option>
		<option>Овощи</option>
		<option>Орехи</option>
	</select>
	</label>
	<br>
	<br>
	<label>Текст объявления:
	<textarea rows="10" cols="15" name="Текст объявления"></textarea><br>
	</label>
	<br>
	<label>E-mail: 
	<input type="text" name="Е-mail" /><br>
	<br>
	<input type="submit" value="Готово">
</form>
</body>
</html>