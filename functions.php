<?php
require_once __DIR__ . '/config.php';

// 接続処理を行う関数
function connectDb()
{
    try {
        return new PDO(
            DSN,
            USER,
            PASSWORD,
            [PDO::ATTR_ERRMODE =>
            PDO::ERRMODE_EXCEPTION]
        );
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit;
    }
}

// エスケープ処理を行う関数
function h($str)
{
    // ENT_QUOTES: シングルクオートとダブルクオートを共に変換する。
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// タスク登録時のバリデーション
function insertValidate($title)
{
    // 初期化
    $errors = [];

    if ($title == '') {
        $errors[] = MSG_TITLE_REQUIRED;
    }

    return $errors;
}

// タスク登録
function insertTask($title)
{
    // データベースに接続
    $dbh = connectDb();

    // レコードを追加
    $sql = <<<EOM
    INSERT INTO
        customers
        (title)
    VALUES
        (:title)
    EOM;
    
    // プリペアドステートメントの準備
    $stmt = $dbh->prepare($sql);
    
    // パラメータのバインド
    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    
    // プリペアドステートメントの実行
    $stmt->execute();
}

function createErrMsg($errors)
{
    $err_msg = "<ul class=\"errors\">\n";

    foreach ($errors as $error) {
        $err_msg .= "<li>" . h($error) . "</li>\n";
    }

    $err_msg .= "</ul>\n";

    return $err_msg;
}