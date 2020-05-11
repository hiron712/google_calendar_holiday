<?php

require_once("config.php");

// 取得したAPIキー
$api_key = API_KEY;

// カレンダーID
$calendar_id = urlencode('japanese__ja@holiday.calendar.google.com');  // Googleの提供する日本の祝日カレンダー

// データの開始日/
//$start = date('2020-01-01\T00:00:00\Z');
$start = date('Y-m-d\T00:00:00\Z');


// データの終了日
//$end = date('2020-12-31\T00:00:00\Z');
$end = mktime(0, 0, 0, date("m"),   date("d"),   date("Y")+1);
$end = date('Y-m-d\T00:00:00\Z',$end);

$url = "https://www.googleapis.com/calendar/v3/calendars/" . $calendar_id . "/events?";
$query = [
	'key' => $api_key,
	'timeMin' => $start,
	'timeMax' => $end,
	'maxResults' => 50,
	'orderBy' => 'startTime',
	'singleEvents' => 'true'
];


$results = [];
if ($data = file_get_contents($url. http_build_query($query), true)) {
	$data = json_decode($data);
	// $data->itemには日本の祝日カレンダーの"予定"が入ってきます
	foreach ($data->items as $row) {
		// [予定の日付 => 予定のタイトル]
		$results[$row->start->date] = $row->summary;
	}
}
?>
	<!doctype html>
	<html lang="ja">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport"
		      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>Document</title>
		<style>
			body{
				font-size: 12px;
			}
			.code{
				padding: 10px;
				background-color: #f4f4f4;
				border: 1px solid #eeeeee;
			}
      table{
        border-collapse: collapse;
      }
      th,td{
        padding: 5px;
      }
		</style>
	</head>
	<body>

	<h3>URL</h3>
	<p class="code">
		<?php
		echo $url. http_build_query($query);
		?>
	</p>

	<h3>JSON</h3>
	<p class="code">
		<?php
		echo json_encode($results);
		?>
	</p>

  <h3>HTML</h3>
  <div class="code">
    <table>
			<?php
			foreach ($results as $kay => $item):
				?>
        <tr>
          <th><?php echo $kay;?></th>
          <td><?php echo $item;?></td>
        </tr>
			<?php
			endforeach;
			?>
    </table>
  </div>

	</body>
	</html>



