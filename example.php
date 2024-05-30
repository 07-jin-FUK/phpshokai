<?php
//phpの横や上にコメントアウトすると動かない
// 変数の定義は$必須
// 行末には「;」必須
$number = 100;
$str = "はじめてのPHP";

// echo $number;

// 配列
// $array[0]などで取得可能
$array1 = ["JavaScript", "PHP", "Swift", "Haskell"];

// var_dump($array1);
// exit();


// キー名を指定することもできる（jsのオブジェクト的な感じ）
// $array2["サーバ"]などで取得可能
$array2 = [
  "フロント" => "JavaScript",
  "サーバ" => "PHP",
  "iOS" => "Swift",
  "関数型" => "Haskell"
];

