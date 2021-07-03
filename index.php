<?php
require_once __DIR__ . '/functions.php';

// データベースに接続
$dbh = connectDb();

/* 顧客情報の習得
---------------------------------------------*/
$sql = <<<EOM
SELECT
    *
FROM
    customers
ORDER BY
    company
EOM;

$stmt = $dbh->prepare($sql);

$stmt->execute();

$cts = $stmt->fetchAll(PDO::FETCH_ASSOC);?>

<!DOCTYPE html>
<html lang="ja">

<!-- _head.phpの読み込み -->
<?php include_once __DIR__ . '/_head.html' ?>

<body>
    <div class="wrapper">
        <h1 class="title"><a href="index.php">顧客管理アプリ</a></h1>
        <div class="customer-area">
            <h2 class="sub-title">顧客リスト</h2>
            <table class="customer-list">
                <thead>
                    <tr>
                        <th class="customer-company">会社名</th>
                        <th class="customer-name">氏名</th>
                        <th class="customer-email">メールアドレス</th>
                        <th class="edit-link-area"></th>
                        <th class="delete-link-area"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cts as $ct) : ?>
                        <tr>
                            <td><?= h($ct['company']) ?></td><!-- 会社名を出力 -->
                            <td><?= h($ct['name']) ?></td><!-- 氏名を出力 -->
                            <td><?= h($ct['email']) ?></td><!-- メールアドレスを出力 -->
                            <td><a href="edit.php?id=<?= h($ct['id']) ?>" class="btn edit-btn">編集</a></td>
                            <td><a href="delete.php?id=<?= h($ct['id']) ?>" class="btn delete-btn">削除</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <a href="new.php" class="btn new-btn">新規登録</a>

        </div>
    </div>
</body>

</html>