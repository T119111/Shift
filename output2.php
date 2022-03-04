<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css"
rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1"
crossorigin="anonymous">
<title>シフト確認</title>

</head>
<body class="bg-light">
<h2 class="text-center text-info my-4">出力結果</h2>

<?php
$dsn = "mysql:host=localhost;dbname=webdesign";
$username = "root";
$password = "1234";

$employeeid = $_POST["employeeid"];
if($employeeid == 1){
	$employeename = '山田一郎';
}else if($employeeid == 2){
	$employeename = '海田二郎';
}else if($employeeid == 3){
	$employeename = '空田三郎';
}
//DBに接続
try{
	$dbcon = new PDO($dsn, $username, $password);
}
catch(PDOException $e){
	die("DSNを使ったデータベースの接続に失敗しました".$e->getMessage() );
}

$sqlstring = "
select *
from shift
WHERE employeeid = '$employeeid'
ORDER BY shiftday
;
";

if( ! $recset = $dbcon -> query( $sqlstring ) ){
	//SQLのqueryが正しく実行できなかったとき，SQL文のチェック
	echo "sqlstring = $sqlstring <br>";
	echo "SQL実行時のエラーメッセージ:";
	print_r( $dbcon->errorInfo() );
}

echo "<div class='container w-75'>";

echo "$employeename さんの予定<br>";

echo "<table class = 'table'>";
echo "<tr><th>シフト日</th><th>開始時刻</th><th>終了時刻</th></tr>";

while( $rowdata = $recset -> fetch(PDO::FETCH_ASSOC) ){
	echo "<tr>";
	echo "<td>{$rowdata["shiftday"]}</td>";
	echo "<td>{$rowdata["strtime"]}</td>";
	echo "<td>{$rowdata["endtime"]}</td>";
	echo "</tr>";
}
echo "</table>";
echo "<a href='output1.php'>日程確認のトップに戻る</a><br>";
echo "<a href='menu.html'>シフト管理のトップに戻る</a>";
echo "</div>";

$dbcon=NULL;
$recset=NULL;
?>

</body>
</html>