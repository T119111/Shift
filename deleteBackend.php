<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css"
rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1"
crossorigin="anonymous">
<title>消去完了</title>
</head>
<body class="bg-light">
<h2 class="text-center text-info my-4">結果</h2>

<?php
$dsn = "mysql:host=localhost;dbname=webdesign";
$username = "root";
$password = "1234";

//消去確認のチェックがなかった場合
if(empty($_POST["deleteshiftid"])){
	echo "消去する日程を選択してください。<br>";
	echo "<br><button type='button' onclick='history.back()'>戻る</button>";
	exit;
}

//消去確認のチェックがなかった場合
if(empty($_POST["kakunin"])){
	echo "消去確認のチェックをしてください。<br>";
	echo "<br><button type='button' onclick='history.back()'>戻る</button>";
	exit;
}

//取得
$deleteshiftid = $_POST["deleteshiftid"];

//DBに接続
try{
	$dbcon = new PDO($dsn, $username, $password);
}
catch(PDOException $e){
	die("DSNを使ったデータベースの接続に失敗しました".$e->getMessage() );
}

for( $i = 0; $i < count($deleteshiftid); $i++ ){

	$sqlstring = "
	DELETE
	FROM shift
	WHERE shiftid = '$deleteshiftid[$i]'
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
}

echo "<br>シフトを消去しました。<br>";
echo "<a href='deleteFrontend.php'>日程消去のトップに戻る</a><br>";
echo "<a href='menu.html'>シフト管理のトップに戻る</a>";

echo "</div>";

//検索結果とDBオブジェクトを開放する
$recset = null;
$dbcon = null;

?>

</body>
</html>