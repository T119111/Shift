<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css"
rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1"
crossorigin="anonymous">
<title>指定した期間の日程入力</title>
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
$err_msg = "";
$result = "";
$flag = true;
$check = 0;
$j = 0;

$name = $_POST["name"];
//DBに接続
try{
	$dbcon = new PDO($dsn, $username, $password);
}
catch(PDOException $e){
	die("DSNを使ったデータベースの接続に失敗しました".$e->getMessage() );
}

//学籍テーブルのすべてレコードを検索するSQL文
$sqlstring = "
select employeeid
from employee
WHERE fullname = '$name'
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

//データの受け取り
while( $rowdata = $recset -> fetch(PDO::FETCH_ASSOC) ){
	$empid[] = $rowdata["employeeid"];
}


// 入力チェック
//入力開始日
if(empty($_POST["start"])){
	echo "日程入力開始日を選択して下さい。<br>";
	$check++;
}else{
	$start = $_POST["start"];
}

//入力終了日
if(empty($_POST["end"])){
	echo "日程入力終了日を選択して下さい。<br>";
	$check++;
}else{
	$end = $_POST["end"];
}

if(empty($_POST["start"]) || empty($_POST["end"])){
	$flag = false;
}

//開始日より終了日が前の日であった場合
if($flag && $end < $start){
	echo "正しい期間を選択して下さい。<br>";
	$check++;
}

//入力に不備があった場合
if($check > 0){
	echo "<br><button type='button' onclick='history.back()'>戻る</button>";
	exit;
}

echo "<div class='container w-75'>";
echo "<h4 class='text-center text-info my-4'><font color='black'><strong>$start</strong></font>
     から <font color='black'><strong>$end</strong></font> までの日程を入力して下さい</h4><br>";
echo "$name さん<br><br>";

echo "<form method='post' action='BackEnd.php'>";

for ( $i = date ( "Y-m-d", strtotime ("$start") ); $i < date ( "Y-m-d", strtotime ("$end + 1day") ); $i = date ( "Y-m-d", strtotime ("$i + 1day") ) ) {
	$j ++;
	// 曜日フラグ初期化
	$week_flg = "";
	// w:数値で示す曜日（0：日 ～ 6：土）
	$week_now = date ( "w", strtotime($i) );
	if       ( $week_now == 0 ) {
		$week_flg = "(日)";
	}elseif ( $week_now == 1 ) {
		$week_flg = "(月)";
	}elseif ( $week_now == 2 ) {
		$week_flg = "(火)";
	}elseif ( $week_now == 3 ) {
		$week_flg = "(水)";
	}elseif ( $week_now == 4 ) {
		$week_flg = "(木)";
	}elseif ( $week_now == 5 ) {
		$week_flg = "(金)";
	}elseif ( $week_now == 6 ) {
		$week_flg = "(土)";
	}
	$result= $i . $week_flg;

	//日程を表示
	echo "$result ： <br>　";
	//日程を入力
	echo "<input type='radio' name='date[$j]' value='0' checked='checked' >入れない　";
	echo "<input type='radio' name='date[$j]' value='1'>09:00 ～ 12:00　";
	echo "<input type='radio' name='date[$j]' value='2'>10:00 ～ 13:00　";
	echo "<input type='radio' name='date[$j]' value='3'>11:00 ～ 14:00　";
	echo "<input type='radio' name='date[$j]' value='4'>12:00 ～ 15:00　";

	echo "<br><br>";
}

echo "<input type='hidden' name='name' value='$name'>";
echo "<input type='hidden' name='nempid' value='$empid[0]'>";
echo "<input type='hidden' name='start' value='$start'>";
echo "<input type='hidden' name='end' value='$end'>";

echo "<br><input type='submit' value='入力'>";
echo " <input type='reset' value='リセット'>";

echo "</form>";
echo "</div>";

?>
</body>
</html>