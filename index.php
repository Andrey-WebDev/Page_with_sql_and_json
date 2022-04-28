<!doctype html>
<html lang="ru">
<head>
  <title>Тестовое задание</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="../src/hystmodal.css">
    <link rel="stylesheet" href="./demos.css">
  <style>
      #okno {
        width: 300px;
        height: 50px;
        text-align: center;
        padding: 15px;
        border: 3px solid #0000cc;
        border-radius: 10px;
        color: #0000cc;
        display: none;
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        margin: auto;
      }
      #okno:target {display: block;}
      .close {
        display: inline-block;
        border: 1px solid #0000cc;
        color: #0000cc;
        padding: 0 12px;
        margin: 10px;
        text-decoration: none;
        background: #f2f2f2;
        font-size: 14pt;
        cursor:pointer;
      }
      .close:hover {background: #e6e6ff;}
    </style>
</head>
<body>
  <?php
    $host = 'localhost'; 
    $user = 'root';    
    $pass = ''; 
    $db_name = 'lab1';   // Имя базы данных
    $link = mysqli_connect($host, $user, $pass, $db_name); // Соединяемся с базой

    if (!$link) {
      echo 'Не могу соединиться с БД. Код ошибки: ' . mysqli_connect_errno() . ', ошибка: ' . mysqli_connect_error();
      exit;
    }
    if (isset($_POST["Name"])) {
      if (isset($_GET['red_id'])) {
          $sql = mysqli_query($link, "UPDATE `students` SET `Name` = '{$_POST['Name']}',`Course` = '{$_POST['Course']}' WHERE `ID`={$_GET['red_id']}");
      } else {
          $sql = mysqli_query($link, "INSERT INTO `students` (`Name`, `Course`) VALUES ('{$_POST['Name']}', '{$_POST['Course']}')");
      }
    }

    if (isset($_GET['del_id'])) { 
      $sql = mysqli_query($link, "DELETE FROM `students` WHERE `ID` = {$_GET['del_id']}");
    }
    if (isset($_GET['red_id'])) {
      $sql = mysqli_query($link, "SELECT `ID`, `Name`, `Course` FROM `students` WHERE `ID`={$_GET['red_id']}");
      $student = mysqli_fetch_array($sql);
    }
  ?>
  <h1>Список студентов</h1>
  <table class="table_col">
    <colgroup>
      <col style="background:#C7DAF0;">
  </colgroup>
    <tr>
      <th>ID</th>
      <th>ФИО студента</th>
      <th>Курс</th>
      <th>Редактирование</th>
    </tr>
    <?php
      $sql = mysqli_query($link, 'SELECT `ID`, `Name`, `Course` FROM `students`');
      while ($result = mysqli_fetch_array($sql)) {
        echo '<tr>' .
             "<td>{$result['ID']}</td>" .
             "<td>{$result['Name']}</td>" .
             "<td>{$result['Course']}</td>" .
             "<td><a href='?del_id={$result['ID']}'>Удалить</a></td>" .
             '</tr>';
      }
    ?>
  </table>
  
  <div align="center">
  <button class="send" data-hystmodal="#modalForms" ><a href="?add=new">Добавить нового студента</a></button>
  </div>
 <div class="hystmodal hystmodal--simple" id="modalForms" aria-hidden="true">
        <div class="hystmodal__wrap">
            <div class="hystmodal__window hystmodal__window--form" role="dialog" aria-modal="true">
                <button class="hystmodal__close" data-hystclose>Закрыть</button>
                <div class="hystmodal__styled">
                    <form action="#" method="POST">
                    <table width="100%">
                      <tr>
                        <th align="right">ФИО студента:</th>
                        <td><input type="text" name="Name" size="35" value="<?= isset($_GET['red_id']) ? $student['Name'] : ''; ?>"></td>
                      </tr>
                      <tr>
                        <th align="right">Курс:</th>
                        <td><input type="text" name="Course" size="3" value="<?= isset($_GET['red_id']) ? $student['Course'] : ''; ?>"> </td>
                        </tr>
                        <tr>
                        <td colspan="2" align="center"><input type="submit" class="send" value="Добавить"></td>
                      </tr>
                    </table>
                  </form>
                </div>
            </div>
        </div>
    </div>
      <h3>JSON</h3>
      <div class="text_json">Здесь вы можете посмотреть данные по студентам из таблицы в JSON формате</div>
      <div class="json_button">
      <button class="send" data-hystmodal="#JSON_format" ><a href="?add=new">Вывести данные</a></button>
      </div>
      <div class="hystmodal hystmodal--simple" id="JSON_format" aria-hidden="true">
              <div class="hystmodal__wrap">
                  <div class="hystmodal__window hystmodal__window--form" role="dialog" aria-modal="true">
                      <button class="hystmodal__close" data-hystclose>Закрыть</button>
                      <div class="hystmodal__styled">
                          <form action="#" method="POST">
                          <?php
                          $data = array(); // в этот массив запишем то, что выберем из базы
                          $sql = mysqli_query($link, 'SELECT `ID`, `Name`, `Course` FROM `students`'); 
                          while($row = mysqli_fetch_assoc($sql)){ 
                          $data[] = $row; 
                          }
                          echo "Данные в JSON формате:" . "<br>" . "<br>" . json_encode($data);
                          ?>
                        </form>
                      </div>
                  </div>
              </div>
          </div>

<script src="../dist/hystmodal.min.js"></script>
    <script>
        const myModal = new HystModal({
            // for dynamic init() of modals
            // linkAttributeName: false,
            catchFocus: true,
            closeOnEsc: true,
            backscroll: true,
            beforeOpen: function(modal){
                console.log('Message before opening the modal');
                console.log(modal); //modal window object
            },
            afterClose: function(modal){
                console.log('Message after modal has closed');
                console.log(modal); //modal window object

                //If Youtube video inside Modal, close it on modal closing
                let videoframe = modal.openedWindow.querySelector('iframe');
                if(videoframe){
                    videoframe.contentWindow.postMessage('{"event":"command","func":"stopVideo","args":""}', '*');
                }
            },
        });
    </script>
</body>
</html>