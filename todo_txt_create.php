<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // フォームからデータを取得
    $name = $_POST['name'];
    $support = $_POST['support'];
    $date = $_POST['date'];
    $place = $_POST['place'];
    $weather = $_POST['weather'];
    $word = $_POST['word'];

    // 動画ファイルをアップロード
    $videoFileName = 'upload';
    // var_dump($_FILES);
    // exit();

    if (isset($_FILES['video']) && $_FILES['video']['error'] == 0) {
        $uploadDir = 'uploads/';

        // オリジナルのファイル名を取得し、安全なファイル名に変換
        $originalFileName = basename($_FILES['video']['name']);
        $safeFileName = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $originalFileName);

        // ユニークなファイル名を生成
        $uniqueFileName = time() . '_' . $safeFileName;
        $uploadFile = $uploadDir . $uniqueFileName;

        // アップロード先ディレクトリが存在しない場合は作成
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // ファイルを移動
        if (move_uploaded_file($_FILES['video']['tmp_name'], $uploadFile)) {
            echo "ファイルは正しくアップロードされました。\n";
            $videoFileName = $uniqueFileName;
        } else {
            echo "ファイルのアップロードに失敗しました。\n";
        }
    } else {
        echo "動画のアップロードにエラーがあります。エラーメッセージ: " . $_FILES['video']['error'] . "\n";
        print_r($_FILES['video']);
    }

    // データをフォーマット
    $line = "{$name} {$support} {$date} {$place} {$word} {$weather} {$videoFileName}\n";

    // ファイルに書き込み
    $file = fopen('data/todo.txt', 'a');
    flock($file, LOCK_EX);
    fwrite($file, $line);
    flock($file, LOCK_UN);
    fclose($file);

    // 入力ページにリダイレクト
    header("Location: todo_txt_input.php");
    exit;
}
