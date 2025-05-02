<?php

// 入力値を取得し、htmlspecialcharsでエスケープ
$productId = isset($_GET["productId"]) ? htmlspecialchars($_GET["productId"]) : '';

//データベースに接続
$dsn = 'mysql:dbname=test;host=db';
$user = 'mysql';
$password = 'mysql';

try {
    $dbh = new PDO($dsn, $user, $password);
    $dbh->exec("SET NAMES utf8mb4");

    //商品IDが含まれない場合
    if (empty($productId)) {
        echo '<pre>';
        echo "商品IDが指定されていません。";
        echo '</pre>';
        exit;
    }

    //クエリを実行し、商品データを所得
    $query = 'SELECT * FROM products WHERE product_id = ' . $productId;
    $stmt = $dbh->query($query);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    //所得対象の商品データが存在しない場合
    if (!$row) {
        echo '<pre>';
        echo "指定された商品は存在しません。";
        echo '</pre>';
        exit;
    }
} catch (PDOException $e) {
    echo "データベースの接続に失敗しました: " . $e->getMessage();
    exit();
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>商品詳細</title>
        <link rel="stylesheet" href="stylesheet.css">
    </head>
    <body>
        <div class="container">
            <h1 class="title"><?php echo $row["product_name"] ?></h1>
            <form action="index.php" method="get">
                <div class="form">
                    <input type="submit" value="戻る">
                </div>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>商品ID</th>
                        <th>商品コード</th>
                        <th>商品名</th>
                        <th>色</th>
                        <th>単価</th>
                        <th>作成日時</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $row["product_id"] ?></td>
                        <td><?php echo $row["product_code"] ?></td>
                        <td><?php echo $row["product_name"] ?></td>
                        <td><?php echo $row["color"] ?></td>
                        <td><?php echo $row["price"] ?></td>
                        <td><?php echo $row["created_at"] ?></td>
                    </tr>
                </tbody>
            </table>
        </div>     
    </body>
</html>
