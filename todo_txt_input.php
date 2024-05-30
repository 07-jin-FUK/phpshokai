<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>今日の一日</title>
  <style>
    body {
      background-image: url("./Img/haikei.jpg");
      background-size: cover;
      display: flex;
      justify-content: center;
      align-items: center;

    }

    form {
      width: 50%;
      /* display: flex; */
      flex-direction: column;
      justify-content: center;
      align-items: center;
      margin-top: 10%;

      background-color: white;
      opacity: 0.9;
    }

    /* 新しく追加 */
    div {

      display: flex;
      justify-content: center;
      align-items: center;
      margin-bottom: 10px;
    }

    div label {
      flex-basis: 200px;
      /* ラベルの幅を固定 */
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
      /* margin-left: 200px; */
      padding: 1.3rem 2rem;

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

    .error {
      border: 2px solid red;
    }

    /* 

    .cp_iptxt {
      position: relative;

      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    .cp_iptxt input[type='text'] {
      font: 15px/24px sans-serif;
      box-sizing: border-box;
      width: 30%;
      padding: 0.3em;
      padding-left: 10px;
      letter-spacing: 1px;
      border: 0;

    }

    .cp_iptxt input[type='text']:focus {
      outline: none;
    }

    .cp_iptxt input[type='text']:focus::after {
      outline: none;
    }

    .cp_iptxt i {
      position: absolute;
      top: 0;
      left: 0;
      padding: 9px 5px;
      transition: 0.3s;
      color: #aaaaaa;
    }

    .cp_iptxt::after {
      display: block;
      width: 30%;
      height: 4px;
      margin-top: -1px;
      content: '';
      border-width: 0 1px 1px 1px;
      border-style: solid;
      border-color: #da3c41;
    } */
  </style>
</head>

<body>
  <audio id="audio" src="./Img/Someday.mp3" preload="auto" loop></audio>
  <form enctype="multipart/form-data" id="diaryForm" action="todo_txt_create.php" method="POST">
    <fieldset>
      <legend>今日の一日を記念に残しましょう</legend>
      <div class="cp_iptxt">
        <input type="text" name="name" id="nameInput" placeholder="お名前">
        <i class="fa fa-user fa-lg fa-fw" aria-hidden="true"></i>
      </div>
      <div class="cp_iptxt">
        <input type="text" name="support" id="supportInput" placeholder="だれといったの？">
      </div>
      <div>
        いつ？ <input type="date" name="date" id="dateInput">
      </div>
      <div>
        天気は？ <select name="weather" id="weatherInput">
          <option value="晴れ">晴れ</option>
          <option value="雨">雨</option>
          <option value="くもり">くもり</option>
          <option value="雪">雪</option>
        </select>
      </div>
      <div class="cp_iptxt">
        <input type="text" name="place" id="placeInput" placeholder="どこに行ったの？">
      </div>
      <div class="cp_iptxt">
        <input type="text" name="word" id="wordInput" placeholder="ひとこと日記">
      </div>
      <div>
        <input type="file" name="video" id="videoInput">
      </div>
      <div>
        <button class=" btn btn-flat">思い出を追加！</button>
      </div>
      <div>
        <a href="todo_txt_read.php" class=" btn btn-flat"><span>過去の日記を見る！</span></a>
      </div>
    </fieldset>
  </form>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', (event) => {
      const music = document.getElementById('audio');
      const nameInput = document.getElementById('nameInput');
      const form = document.getElementById('diaryForm');
      const requiredFields = [
        nameInput,
        document.getElementById('supportInput'),
        document.getElementById('dateInput'),
        document.getElementById('weatherInput'),
        document.getElementById('placeInput'),
        document.getElementById('wordInput'),
        document.getElementById('videoInput')
      ];

      // 音量を小さく設定（0.0から1.0の範囲で設定）
      music.volume = 0.1;

      // ページロード時に音楽を再生
      music.play();

      // 名前の入力フィールドがクリックされたときに音楽を再生
      nameInput.addEventListener('click', () => {
        music.play();
      });
      form.addEventListener('submit', (event) => {
        let valid = true;

        // すべてのフィールドのエラーハイライトをリセット
        requiredFields.forEach(field => {
          field.classList.remove('error');
        });

        // 入力がないフィールドをチェック
        requiredFields.forEach(field => {
          if (!field.value) {
            valid = false;
            field.classList.add('error');

            console.log(field.value);
            // $(".error").css('border', '');

          }
        });

        if (!valid) {
          event.preventDefault(); // フォームの送信を中止
          alert("すべてのフィールドを入力してください。");
        }
      });
    });
  </script>
</body>

</html>

</body>

</html>