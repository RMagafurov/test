<?php
session_start(); ?>
<form action="" method="POST">
    Для завершения и нового сеанса нажмите -
    <input name="dell_session" type="submit" value="Новый пользователь"><br><br><br><br><br>
    ЗАГАДАЙТЕ ДВУХЗНАЧНОЕ ЧИСЛО.<br/> Когда будете готовы, нажмите на кнопку -
    <input name="zag" type="submit" value="ЗАГАДАНО"><br/><br/>
    Введите загаданое число для сравнения : <br/><br/>
    <input name="my_number_input" type="text" value="" placeholder="мое число">
    <input name="my_number_send" type="submit" value="ОТПРАВИТЬ">
</form>

<?php
$esenses = new Extrasenses;
// кол-во экстрасенсов
$esenses ->ess = 3;

// нажатие кнопки "Загадано"
if($_POST['zag']){
    // вызов функции генерации кол-ва экстрасенсов
    $esenses -> es_rand($esenses ->ess + 1);
    // вызов функции истории
    $esenses -> viewHistory($esenses ->ess + 1);
}

// Нажатие кнопки "Отправить"
if($_POST['my_number_send']){
    // вызов функции проверки совпадения номера
    $esenses -> checkNum($esenses ->ess + 1, $_POST['my_number_input']);
    // вызов функции истории
    $esenses -> viewHistory($esenses ->ess + 1);
}
if($_POST['dell_session']){
    // Очистить массив $_SESSION полностью
    session_unset();
    // Удалить временное хранилище (файл сессии) на сервере
    session_destroy();
}



class Extrasenses
{
    public $ess = "";

    // функция генерации догадок экстрасенсов
    public function es_rand($n){
        for ($i = 1; $i != $n; $i++) {
            $_SESSION['esens'.$i][] = mt_rand(10, 99);
            // если в рейтинге экстрасенса пусто, выставляем ноль
            if(empty($_SESSION['esens'.$i.'_r'])){$_SESSION['esens'.$i.'_r'] = 0;}
        }
    }

    //функция проверки совпадения номера
    public function checkNum($n, $my_num){
        // добавляем отправленное число в массив истории чисел
        $_SESSION['my_num'][] = $my_num;
        for ($i = 1; $i != $n; $i++) {
            // проверяем совпадение по догадкам экстрасенсов, если совпало плюсуем рейтинг
            if(end($_SESSION['esens'.$i]) == $_POST['my_number_input'] ){
                $_SESSION['esens'.$i.'_r'] ++;
            } else {
                //если не совпало и переменная не пустая, минусуем рейтинг, если пустая выставляем ноль, что бы не уйти в минус
                if(!empty($_SESSION['esens'.$i.'_r'])){$_SESSION['esens'.$i.'_r']--;} else{$_SESSION['esens'.$i.'_r'] = 0;}
            }
        }

    }
    // Функция вывода истории
    public function viewHistory($n){
        echo "История загаданных чисел : ";
        if($_SESSION['my_num']){
            foreach ($_SESSION['my_num'] as $value){
                echo " ".$value;
            }
        }

        for ($i = 1; $i != $n; $i++){
            echo "<br/><br/>История догадок ".$i." экстрасенса : ";
            foreach ($_SESSION['esens'.$i] as $value){
                echo " ".$value;
            }
            echo "<br/> Рейтинг : ".$_SESSION['esens'.$i.'_r']."<br/>";
        }

    }
}
?>
