<?php
$dsn = 'mysql:dbname=データベース名;host=localhost';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//テーブル作成

// $sql = 'DROP TABLE gototable';
// $stmt = $pdo->query($sql);

$sql = "CREATE TABLE IF NOT EXISTS gototable"
    . " ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "date DATETIME,"
    . "pword TEXT"
    . ");";
$stmt = $pdo->query($sql);

if (!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pword"]) && empty($_POST['editNum'])) { //フォーム内が空でない場合に以下を実行する

    //新規投稿
    if (!empty($_POST['submit'])) {
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $date = date("Y/m/d H:i:s");
        $pword = $_POST["pword"];

        $sql = "INSERT INTO gototable (name, comment, date, pword) VALUES (:name, :comment, :date, :pword)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':pword', $pword, PDO::PARAM_STR);
        $stmt->execute();
    }
} elseif (!empty($_POST['submit'])) { // 編集フォームに投稿番号が入っており、かつ送信ボタンが押されたとき

    //編集
    $id = intval($_POST["editNum"]); //整数の入力データの受け取りを変数に代入
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $epass = ($_POST["pword"]);
    $sql = 'UPDATE gototable SET name=:name,comment=:comment WHERE id=:id and pword=:pword';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':pword', $epass, PDO::PARAM_STR);
    $stmt->execute();
}

//削除
if (!empty($_POST["delSubmit"])) { //削除のボタンが押されたとき
    $id = intval($_POST["delete"]); //
    $dpass = ($_POST["dpass"]);
    $sql = 'DELETE FROM gototable WHERE id=:id AND pword=:pword';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':pword', $dpass, PDO::PARAM_STR);
    $stmt->execute();


    //編集
}
// $enum = "";

//編集番号選択
if (!empty($_POST["ediSubmit"])) { //編集フォームの送信の有無で処理を分岐
    $id = intval($_POST["edit"]); //整数の入力データの受け取りを変数に代入

    $sql = 'SELECT * FROM gototable where id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id", $id, PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll();
    foreach ($results as $row) {
        $enum = $row['id'];
        $ename = $row['name'];
        $ecomment = $row['comment'];
        $epass = $row['pword'];
    }
}
?>

<form action="" method="post">
    <input type="text" name="name" placeholder="名前" value="<?php if (isset($ename)) {
                                                                echo $ename;
                                                            } ?>"><br>
    <input type="text" name="comment" placeholder="コメント" value="<?php if (isset($ecomment)) {
                                                                    echo $ecomment;
                                                                } ?>"><br>
    <input type="hidden" name="editNum" value="<?php if (isset($enum)) {
                                                    echo $enum;
                                                } ?>">
    <input type="text" name="pword" placeholder="パスワード" value="<?php if (isset($epass)) {
                                                                    echo $epass;
                                                                } ?>">
    <input type="submit" name="submit" value="送信">
    <br><br>
    <input type="number" name="delete" placeholder="削除対象番号"><br>
    <input type="text" name="dpass" placeholder="パスワード">
    <input type="submit" name="delSubmit" value="削除">
    <br><br>
    <input type="number" name="edit" placeholder="編集対象番号"><br>
    <input type="text" name="epass" placeholder="パスワード">
    <input type="submit" name="ediSubmit" value="編集">
</form>



<?php
$sql = 'SELECT * FROM gototable';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();

// $sql = 'SHOW CREATE TABLE gototable';
// $result = $pdo->query($sql);
// foreach ($result as $row) {
//     echo $row[1];
// }
// echo "<hr>";

foreach ($results as $row) {
    //$rowの中にはテーブルのカラム名が入る
    echo $row['id'] . ',';
    echo $row['name'] . ',';
    echo $row['comment'] . ',';
    echo $row['date'] . ',';
    echo $row['pword'] . '<br>';
    echo "<hr>";
}
?>