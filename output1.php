<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css"
rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1"
crossorigin="anonymous">
<title>シフト確認</title>
</head>
<body>

<?php
//Data Source Nameの指定
//  dbname は， webdesignに変更
//
$dsn = "mysql:host=localhost;dbname=webdesign";
$username = "root";
$password = "1234";

//DBに接続
try{
	$dbcon = new PDO($dsn, $username, $password);
}
catch(PDOException $e){
	die("DSNを使ったデータベースの接続に失敗しました".$e->getMessage() );
}

//学籍テーブルのすべてレコードを検索するSQL文
$sqlstring = "
select *
from employee
;
";

//SQL文の実行
//gakusekiテーブルのすべてのレコードが $recset に格納される
if( ! $recset = $dbcon -> query( $sqlstring ) ){
	//SQLのqueryが正しく実行できなかったとき，SQL文のチェック
	echo "sqlstring = $sqlstring <br>";
	echo "SQL実行時のエラーメッセージ:";
	print_r( $dbcon->errorInfo() );
	die("gausekiテーブルのSQLの実行でエラーが発生しました");
}

echo "<h1 class='text-center text-info my-4'>シフトを表示する名前を選択して下さい</h1>";

echo "<div class='container w-75'>";

echo "<form action='output2.php' method='POST'>";
//名前をselectボックスで選択する
echo "従業員名  <select name='employeeid' class='form-control'>";
//employeeテーブルに格納されている学生をすべて表示する
while( $rowdata = $recset -> fetch(PDO::FETCH_ASSOC) ){
	//カンマ区切りの文字列を，フォーム変数として受け渡す
	echo "<option value='{$rowdata["employeeid"]}'>";
	echo "{$rowdata["fullname"]}";
	echo "</option>";
}
echo "</select><br>";

echo "<input type='submit' value='開始'>";
echo "<input type='reset' value='リセット'>";

echo "</form>";
echo "</div>";

?>

</body>
</html>