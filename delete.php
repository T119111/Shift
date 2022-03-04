<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css"
rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1"
crossorigin="anonymous">
<title>日程消去</title>
<?php

?>

</head>
<body class="bg-light">
<script type="text/javascript">
var today = new Date();
</script>

<?php
$dsn = "mysql:host=localhost;dbname=webdesign";
$username = "root";
$password = "1234";

// 変数の初期化
$name = $_POST["name"];
//DBに接続
try{
	$dbcon = new PDO($dsn, $username, $password);
}
catch(PDOException $e){
	die("DSNを使ったデータベースの接続に失敗しました".$e->getMessage() );
}

//テーブルのすべてレコードを検索するSQL文
$sqlstring = "
select employeeid
from employee
WHERE fullname = '$name'
;
";

//SQL文の実行
//テーブルのすべてのレコードが $recset に格納される
if( ! $recset = $dbcon -> query( $sqlstring ) ){
	//SQLのqueryが正しく実行できなかったとき，SQL文のチェック
	echo "sqlstring = $sqlstring <br>";
	echo "SQL実行時のエラーメッセージ:";
	print_r( $dbcon->errorInfo() );
	die("テーブルのSQLの実行でエラーが発生しました");
}

//データの受け取り
while( $rowdata = $recset -> fetch(PDO::FETCH_ASSOC) ){
	$empid = $rowdata["employeeid"];
}

//検索結果を開放する
$recset = null;

echo "<div class='container w-75'>";
echo "<h2 class='text-center text-info my-4'>消去する日程を選択してください</h2><br>";
echo "$name さん<br>";

//テーブルのすべてレコードを検索するSQL文
$sqlstring2 = "
select *
from shift
WHERE employeeid = '$empid'
ORDER BY shiftday ASC, strtime ASC
;
";

//SQL文の実行
//テーブルのすべてのレコードが $recset に格納される
if( ! $recset = $dbcon -> query( $sqlstring2 ) ){
	//SQLのqueryが正しく実行できなかったとき，SQL文のチェック
	echo "sqlstring = $sqlstring2 <br>";
	echo "SQL実行時のエラーメッセージ:";
	print_r( $dbcon->errorInfo() );
	die("テーブルのSQLの実行でエラーが発生しました");
}

echo "<form method='post' action='deleteBackend.php'>";

$beforday = "non";
//データの受け取り
while( $rowdata = $recset -> fetch(PDO::FETCH_ASSOC) ){
	$shiftid = $rowdata["shiftid"];
	$shiftday = $rowdata["shiftday"];
	$strtime = substr($rowdata["strtime"], -9);
	$endtime = substr($rowdata["endtime"], -9);

	if($shiftday != $beforday){
		echo "<br>$shiftday<br>";
	}

	echo " <input type='checkbox' name='deleteshiftid[]' value='$shiftid'>";
	echo "$strtime ～ $endtime<br>";

	$beforday = $shiftday;
}

echo "<br><input type='checkbox' name='kakunin' value='-1'>消去確認(チェックしてください)";

echo "<br><input type='submit' value='消去'>";
echo " <input type='reset' value='リセット'><br><br>";

echo "<a href='deleteFrontend.php'>日程消去のトップに戻る</a><br>";
echo "<a href='menu.html'>シフト管理のトップに戻る</a>";

echo "</form>";
echo "</div>";

//検索結果とDBオブジェクトを開放する
$recset = null;
$dbcon = null;

?>
</body>
</html>