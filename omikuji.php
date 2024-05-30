<?php

$randomNumber = rand(0,4);

// var_dump($randomNumber);
// exit();
// if($randomNumber === 0){
//     $result ="大吉";
// }else if($randomNumber === 1){
//     $result ="吉";
// }else if($randomNumber === 2){
//     $result ="中吉";
// }else if($randomNumber === 3){
//     $result ="小吉";
// }else {
//     $result ="凶";
// }
$result=["大吉","吉","中吉","小吉","凶"][$randomNumber];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .result{
            color:red;
        }
    </style>
</head>
<body>
    <h1>おみくじの結果は<span class=result><?= $result ?></span>です。</h1>
    
</body>
</html>
