<?php

// 入力値を取得し、htmlspecialcharsでエスケープ
$productName = isset($_GET["productName"]) ? htmlspecialchars($_GET["productName"]) : '';
$price = isset($_GET["price"]) ? htmlspecialchars($_GET["price"]) : '';
$limit = isset($_GET["limit"]) ? htmlspecialchars($_GET["limit"]) : '10';

// データベースに接続
$dsn = 'mysql:dbname=test;host=db';
$user = 'mysql';
$password = 'mysql';
try {
    $dbh = new PDO($dsn, $user, $password);
    $dbh->exec("SET NAMES utf8mb4"); 
    $errors = [];

    // デフォルトのクエリ
    $query = 'SELECT * FROM products WHERE 1=1';
    $params = [];

    // 商品名検索
    if (!empty($productName)) {
        if (mb_strlen($productName) > 255) {
            $errors["productName"] = "商品名は255文字以内で入力してください。";
            $productName = "";
        } else {
            $productName = str_replace(['%', '_'], ['\%', '\_'], $productName);
            $query .= " AND product_name LIKE :productName";
            $params[':productName'] = "%{$productName}%";
        }
    }

    //価格検索
    if (!empty($_GET["price"])) {
        $price = $_GET["price"];
        if ((!ctype_digit($price) || (int)$price >= 10000000000)) {
            $errors["price"] = "価格は9999999999以下の整数で入力してください。";
            $price  = "";
        } else {
            $query .= " AND price >= :price";
            $params[':price'] = (int)$price;
        }
    }

    //並び順
    $query .= " ORDER BY created_at DESC, product_id ASC";

    //表示件数
    if (!in_array($limit, ['10', '20', '30'])) {
        $limit = '10'; // デフォルト値
    }
    $query .= " LIMIT " . $limit;

    //クエリ実行
    $stmt = $dbh->prepare($query);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "データベースの接続に失敗しました: " . $e->getMessage();
    exit();
}


?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>商品検索</title>
        <link rel="stylesheet" href="stylesheet.css">
    </head>
    <body>
        <div class="container">
            <h1 class="title">商品検索</h1>
            <?php if (!empty($errors)): ?>
                <div class="error-messages">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <form action="index.php" method="get">
                <div class="form">
                    <p>商品名</p>
                    <input type="text" name="productName" value="<?php echo $productName ?>" >
                </div>
                <div class="form">
                    <p>価格</p>
                    <input type="number" name="price" value = "<?php echo $price ?>">
                </div>
                <div class="form">
                    <p>表示件数</p>
                    <select name="limit" value="<?php echo $limit ?>" >
                        <option value="10" <?php if ($limit === "10") {
    echo "selected";
} ?>>10</option>
                        <option value="20" <?php if ($limit === "20") {
    echo "selected";
} ?>>20</option>
                        <option value="30" <?php if ($limit === "30") {
    echo "selected";
} ?>>30</option>
                    </select>
                </div>
                <div class="search">
                    <input type="submit" value="検索">
                </div>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>商品名</th>
                        <th>色</th>
                        <th>商品単価</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product["product_name"]); ?></td>
                            <td><?php echo htmlspecialchars($product["color"]); ?></td>
                            <td><?php echo htmlspecialchars($product["price"]); ?>円</td>
                            <td>
                                <form action="detail.php" method="get">
                                    <input type="hidden" name="productId" value="<?php echo htmlspecialchars($product['product_id']); ?>">
                                    <input type="submit" value="詳細">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>     
    </body>
</html>
