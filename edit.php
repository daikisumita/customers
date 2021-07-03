<?php
require_once __DIR__ . '/functions.php';
$company = '';
$name = '';
$email = '';
$errors = [];

$id = filter_input(INPUT_GET, 'id');

// データベースに接続
$dbh = connectDb();

$sql = <<<EOM
SELECT
    *
FROM
    customers
WHERE
    id = :id
EOM;


$stmt = $dbh->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$ct = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // フォームに入力されたデータの受け取り
    $company = filter_input(INPUT_POST, 'company');
    $name = filter_input(INPUT_POST, 'name');
    $email = filter_input(INPUT_POST, 'email');

    // 必須チェック
    if ($company == '') {
        $errors[] = '会社名を入力してください';
    }
    if ($name == '') {
        $errors[] = '氏名を入力してください';
    }
    if ($email == '') {
        $errors[] = 'メールアドレスを入力してください';
    }

    if ($ct['company'] == $company && 
        $ct['name'] == $name && 
        $ct['email'] == $email) {
        $errors [] = '変更内容がありません';

    }

    // エラーがなかったら更新
    if (empty($errors)) {
        $sql = <<<EOM
        UPDATE
            customers
        SET
            company = :company,
            name = :name,
            email = :email
        WHERE
            id = :id
        EOM;

        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':company', $company, PDO::PARAM_STR);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        header('Location: index.php');
        exit;
    }
    // エラーがあった場合は、入力した内容で上書き
    $ct['company'] = $company;
    $ct['name'] = $name;
    $ct['email'] = $email;
}
?>

<!DOCTYPE html>
<html lang="ja">

<!-- _head.phpの読み込み -->
<?php include_once __DIR__ . '/_head.html' ?>
<body>
    <div class="wrapper">
        <h1 class="title"><a href="index.php">顧客管理アプリ</a></h1>
        <div class="form-area">
            <h2 class="sub-title">編集</h2>

            <?php if ($errors) : ?>
                <ul class="errors">
                    <?php foreach ($errors as $error) : ?>
                        <li><?= h($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <form action="" method="post">
                <label for="company">会社名</label>
                <input type="text" id="company" name="company" value="<?= h($ct['company']) ?>">
                <label for="name">氏名</label>
                <input type="text" id="name" name="name" value="<?= h($ct['name']) ?>">
                <label for="email">メールアドレス</label>
                <input type="email" id="email" name="email" value="<?= h($ct['email']) ?>">
                <input type="submit" class="btn submit-btn" value="更新">
            </form>
            <a href="index.php?id=<?= h($ct['id']) ?>" class="btn return-btn">戻る</a>
        </div>
    </div>
</body>

</html>