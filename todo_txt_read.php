<?php
$str = "";
$array = [];

$file = fopen('data/todo.txt', 'r');
flock($file, LOCK_EX);

if ($file) {
  while ($line = fgets($file)) {
    $str .= "<tr><td>{$line}</td></tr>";
    $lineArray = explode(" ", $line);
    // 各要素の取得と空白の処理
    $name = isset($lineArray[0]) ? $lineArray[0] : "";
    $support = isset($lineArray[1]) ? $lineArray[1] : "";
    $date = isset($lineArray[2]) ? $lineArray[2] : "";
    $place = isset($lineArray[3]) ? $lineArray[3] : "";
    $word = isset($lineArray[4]) ? $lineArray[4] : "";
    $weather = isset($lineArray[5]) ? $lineArray[5] : "";
    $video = isset($lineArray[6]) ? str_replace("\n", "", $lineArray[6]) : "";

    $array[] = [
      "name" => $name,
      "support" => $support,
      "date" => $date,
      "place" => $place,
      "weather" => $weather,
      "video" => $video,
      "word" => $word,
    ];
  }
}
flock($file, LOCK_UN);
fclose($file);

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>いい日旅立ち</title>
  <style>
    body {
      background-image: url("./Img/kikiki.jpg");
      opacity: 0;
      /* Initially hidden */
      transition: opacity 1s;
      /* Transition effect */
    }


    table {
      width: 100%;
      border-collapse: collapse;
    }

    th,
    td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: center;
    }


    tr {
      background-color: white;
    }

    tr:hover {
      background-color: #ddd;
    }

    th {
      padding-top: 12px;
      padding-bottom: 12px;
      text-align: center;
      background-color: #4CAF50;
      color: white;
    }

    tr {
      border-bottom: 1px solid #ddd;
      /* 各行の区切り線 */
    }

    *,
    *:before,
    *:after {
      -webkit-box-sizing: inherit;
      box-sizing: inherit;
    }

    /* html {
      -webkit-box-sizing: border-box;
      box-sizing: border-box;
      font-size: 62.5%;
    } */

    .btn,
    a.btn,
    button.btn {
      margin-left: 10px;
      font-size: 15px;
      font-weight: 700;
      line-height: 0.2;
      position: relative;
      display: inline-block;
      padding: 1rem 1rem;
      cursor: pointer;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
      -webkit-transition: all 0.3s;
      transition: all 0.3s;
      text-align: center;
      vertical-align: middle;
      text-decoration: none;
      letter-spacing: 0.1em;
      color: #212529;
      border-radius: 0.5rem;
    }

    .btn:hover {
      scale: 1.1;
    }

    label {
      font-size: 20px;
    }

    a.btn-flat {
      overflow: hidden;
      margin-left: 200px;
      padding: 1.5rem 6rem;

      color: #fff;
      border-radius: 0;
      background: #000;
    }

    a.btn-flat span {
      position: relative;
    }

    a.btn-flat:before {
      position: absolute;
      top: 0;
      left: 0;

      width: 150%;
      height: 500%;

      content: "";
      -webkit-transition: all 0.5s ease-in-out;
      transition: all 0.5s ease-in-out;
      -webkit-transform: translateX(-98%) translateY(-25%) rotate(45deg);
      transform: translateX(-98%) translateY(-25%) rotate(45deg);

      background: #00b7ee;
    }

    a.btn-flat:hover:before {
      -webkit-transform: translateX(-9%) translateY(-25%) rotate(45deg);
      transform: translateX(-9%) translateY(-25%) rotate(45deg);
    }
  </style>
</head>

<body>
  <audio id="audio" src="./Img/あの日見た景色.mp3" preload="auto"></audio>
  <fieldset>
    <legend>本日の日記</legend>
    <div>
      <label for="searchDate">日付で探す：</label>
      <input type="date" id="searchDate">
      <button id="searchByDate" class=" btn btn-flat">日付で検索</button>
    </div>

    <div>
      <label for="searchName">どなたの思い出を見ますか？</label>
      <input type="text" id="searchName">
      <button id="searchByName" class=" btn btn-flat">思い出をのぞく</button>
      <a href="todo_txt_input.php" class=" btn btn-flat"><span>日記を更新する！</span></a>
    </div>
    <button id="sortByDate" class=" btn btn-flat">日付順に並べる</button>
    <button id="sortByPost" class=" btn btn-flat">投稿順に並べる</button>

    <table>
      <thead>
        <tr>
          <th>動画</th>
          <th>本日の日記</th>
          <th>天気</th>
          <th>ひとこと思い出</th>
        </tr>
      </thead>
      <tbody id="result"></tbody>
    </table>
  </fieldset>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      document.body.style.opacity = 1;
    });

    const info = <?= json_encode($array) ?>;
    let sortOrder = "post"; // 初期値は投稿順
    console.log(info);


    function renderTable(entries) {
      const resultElement = document.getElementById("result");
      resultElement.innerHTML = ""; // テーブルをクリア

      entries.forEach(entry => {
        const row = document.createElement("tr");

        // 動画を追加
        const videoCell = document.createElement("td");
        if (entry.video) {
          const videoElement = document.createElement("video");
          videoElement.width = 240;
          videoElement.height = 160;

          videoElement.controls = true;
          const sourceElement = document.createElement("source");
          sourceElement.src = "uploads/" + encodeURIComponent(entry.video);
          sourceElement.type = "video/mp4";
          videoElement.appendChild(sourceElement);
          videoCell.appendChild(videoElement);

          // デバッグ用メッセージ
          console.log("Video file path:", sourceElement.src);

        } else {
          videoCell.textContent = 'なし';
        }
        row.appendChild(videoCell);

        // テキストを追加
        const textCell = document.createElement("td");
        const date = new Date(entry.date);
        const formattedDate = date.toLocaleDateString("ja-JP", {
          year: "numeric",
          month: "long",
          day: "numeric"
        });
        textCell.innerHTML = `<span style="color: blue;font-size:25px">${entry.name}</span>は<span style="color: green;font-size:25px">${entry.support}</span>と<br><span style="color: red;font-size:25px">${formattedDate}</span>に<span style="color: purple;font-size:25px">${entry.place}</span>に行ったよ！`;
        textCell.style.lineHeight = "2"; // 行間を大きくする
        row.appendChild(textCell);

        // スタンプを表示
        const stampCell = document.createElement("td");
        if (entry.weather === "晴れ") {
          const stampImage = document.createElement("img");
          stampImage.src = "./Img/晴れた.png"; // 晴れのスタンプ画像のパスを指定
          stampImage.alt = "晴れ";
          stampImage.style.width = "100px"; // スタンプ画像の幅を指定
          stampImage.style.height = "100px"; // スタンプ画像の高さを指定
          stampCell.appendChild(stampImage);
        } else if (entry.weather === "くもり") {
          const stampImage = document.createElement("img");
          stampImage.src = "./Img/曇り.png"; // 曇りのスタンプ画像のパスを指定
          stampImage.alt = "くもり";
          stampImage.style.width = "100px"; // スタンプ画像の幅を指定
          stampImage.style.height = "100px"; // スタンプ画像の高さを指定
          stampCell.appendChild(stampImage);
        } else if (entry.weather === "雨") {
          const stampImage = document.createElement("img");
          stampImage.src = "./Img/雨.png"; // 雨のスタンプ画像のパスを指定
          stampImage.alt = "雨";
          stampImage.style.width = "100px"; // スタンプ画像の幅を指定
          stampImage.style.height = "100px"; // スタンプ画像の高さを指定
          stampCell.appendChild(stampImage);
        } else {
          const stampImage = document.createElement("img");
          stampImage.src = "./Img/雪.png"; // 雨のスタンプ画像のパスを指定
          stampImage.alt = "雪";
          stampImage.style.width = "100px"; // スタンプ画像の幅を指定
          stampImage.style.height = "100px"; // スタンプ画像の高さを指定
          stampCell.appendChild(stampImage);
        }
        row.appendChild(stampCell);

        const textCell2 = document.createElement("td");
        textCell2.innerHTML = `<span style="color: blue;font-size:20px">${entry.word}</span>`;
        row.appendChild(textCell2);

        resultElement.appendChild(row);
      });
    }

    // 日付で検索する関数
    function searchByDate() {
      const searchValue = document.getElementById("searchDate").value;
      const filteredEntries = info.filter(entry => entry.date === searchValue);
      renderTable(filteredEntries);
    }

    // 名前で検索する関数
    function searchByName() {
      const searchValue = document.getElementById("searchName").value.trim();
      const filteredEntries = info.filter(entry => entry.name.includes(searchValue));
      renderTable(filteredEntries);
    }

    // 日付順にソートする関数
    function sortByDate() {
      info.sort((a, b) => new Date(a.date) - new Date(b.date));
      sortOrder = "date";
      renderTable(info);
    }

    // 初期表示
    renderTable(info);

    // 日付検索ボタンにクリックイベントを設定
    document.getElementById("searchByDate").addEventListener("click", searchByDate);

    // 名前検索ボタンにクリックイベントを設定
    document.getElementById("searchByName").addEventListener("click", searchByName);

    // 日付順ボタンにクリックイベントを設定
    document.getElementById("sortByDate").addEventListener("click", sortByDate);

    // 投稿順ボタンにクリックイベントを設定
    document.getElementById("sortByPost").addEventListener("click", function() {
      // ページをリロードして投稿順に戻す
      location.reload();
    });

    document.addEventListener('DOMContentLoaded', (event) => {
      const music = document.getElementById('audio');

      // 音量を小さく設定（0.0から1.0の範囲で設定）
      music.volume = 0.1;

      // ページロード時に音楽を再生
      music.play();
    });
  </script>
</body>

</html>