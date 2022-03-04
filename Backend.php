<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css"
rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1"
crossorigin="anonymous">
<title>入力完了</title>

<?php
//日付と時刻を文字列で返す関数
function shiftTime( $date, $time){
	$tmp[1] = $date;
	$tmp[2] = $date;

	$stmp = array();

	if($time == 1){
		$stmp[1] = " 09:00:00";
		$stmp[2] = " 12:00:00";
	}else if($time == 2){
		$stmp[1] = " 10:00:00";
		$stmp[2] = " 13:00:00";
	}else if($time == 3){
		$stmp[1] = " 11:00:00";
		$stmp[2] = " 14:00:00";
	}else if($time == 4){
		$stmp[1] = " 12:00:00";
		$stmp[2] = " 15:00:00";
	}

	$shiftday[1] = $tmp[1].$stmp[1];
	$shiftday[2] = $tmp[2].$stmp[2];

	return $shiftday;
}
?>

</head>
<body class="bg-light">
<h2 class="text-center text-info my-4">入力結果</h2>

<?php
$dsn = "mysql:host=localhost;dbname=webdesign";
$username = "root";
$password = "1234";

//配列の宣言
$dateArray = array();
$ymdArray = array();

//取得
$name = $_POST["name"];
$empid = $_POST["nempid"];
$start = $_POST["start"];
$end = $_POST["end"];

//日ごといつ出れるかを配列で取得
foreach ($_POST["date"] as $key => $value){
	$dateArray[$key] = $value;
}

//DBに接続
try{
	$dbcon = new PDO($dsn, $username, $password);
}
catch(PDOException $e){
	die("DSNを使ったデータベースの接続に失敗しました".$e->getMessage() );
}

$j = 0;
echo "<div class='container w-75'>";

//値をデータベースに格納
for ( $i = date ( "Y-m-d", strtotime ("$start") ); $i < date ( "Y-m-d", strtotime ("$end + 1day") ); $i = date ( "Y-m-d", strtotime ("$i + 1day") ) ) {
	$j++;
	//入れないのとき
	if( $dateArray[$j] == 0){

	}
	//入れるのとき
	else{
		$dtime = shiftTime($i, $dateArray[$j]);

		//SQL文
		$sqlstring = "
		INSERT INTO shift (shiftday,employeeid,strtime,endtime)
		VALUES ( '$i', $empid, '$dtime[1]', '$dtime[2]')
		;
		";

		//SQL文の実行
		//gakusekiテーブルのすべてのレコードが $recset に格納される
		if( ! $recset = $dbcon -> query( $sqlstring ) ){
			//SQLのqueryが正しく実行できなかったとき，SQL文のチェック
			echo "sqlstring = $sqlstring <br>";
			echo "SQL実行時のエラーメッセージ:";
			print_r( $dbcon->errorInfo() );
			die("テーブルのSQLの実行でエラーが発生しました");
		}else{
			echo "シフトを $dtime[1] ～ $dtime[2] で登録しました。 <br>";
		}
	}
}

echo "シフトを登録しました。<br>";
echo "<a href='FrontEnd.php'>日程入力のトップに戻る</a><br>";
echo "<a href='menu.html'>シフト管理のトップに戻る</a>";
echo "</div>";

?>

</body>
</html>