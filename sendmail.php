<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';

$mail = new PHPMailer(true);
$mail->CharSet = 'UTF-8';
$mail->setLanguage('ru', 'phpmailer/language/');
$mail->IsHTML(true);

//От кого письмо
$mail->setFrom('rodion-web@yandex.ru', 'Данные с формы');
//Кому отправить
$mail->addAddress('rodion-web@yandex.ru', 'Родион Рамазанов');
//Тема письма
$mail->Subject = 'Привет! Это данные с формы.';

//Рука
$order = "0";
if ($_POST['r1'] == "online") {
    $order = "Заказ онлайн";
} else {
    if ($_POST['r1'] == "offline") {
        $order = "Заказ офлайн";
    } else {
        if ($_POST['r1'] == "teleport") {
            $order = "Заказать телепорт";
        }
    }
}


//Тело письма
$body = '<h1>Встречайте супер письмо!</h1>';

if (trim(!empty($_POST['email']))) {
    $body .= '<p><strong>E-mail:</strong> ' . $_POST['email'] . '</p>';
}
if (trim(!empty($_POST['r1']))) {
    $body .= '<p><strong>Тип заказа:</strong> ' . $order . '</p>';
}
if (trim(!empty($_POST['phoneYou']))) {
    $body .= '<p><strong>Phone:</strong> ' . $_POST['phoneYou'] . '</p>';
}

if (trim(!empty($_POST['textareaText']))) {
    $body .= '<p><strong>Сообщение:</strong> ' . $_POST['textareaText'] . '</p>';
}
if (trim(!empty($_POST['range']))) {
    $body .= '<p><strong>Цифра:</strong> ' . $_POST['range'] . '</p>';
}

if (trim(!empty($_POST['checkbox']))) {
    foreach ($_POST["checkbox"] as $checkbox) {
        $body .= '<p><strong>Что нужно:</strong> ' .  $checkbox . '</p>';
    }
}


//Прикрепить файл
if (!empty($_FILES['image']['tmp_name'])) {
    //путь загрузки файла
    $filePath = __DIR__ . "/files/" . $_FILES['image']['name'];
    //грузим файл
    if (copy($_FILES['image']['tmp_name'], $filePath)) {
        $fileAttach = $filePath;
        $body .= '<p><strong>Фото в приложении</strong>';
        $mail->addAttachment($fileAttach);
    }
}

$mail->Body = $body;

//Отправляем
if (!$mail->send()) {
    $message = 'Ошибка';
} else {
    $message2 = "привет! твое письмо доставленно";
    $emailP = htmlspecialchars($_POST["email"]);
    $mail2 = new PHPMailer(true);
    $mail2->CharSet = 'UTF-8';
    $mail2->setLanguage('ru', 'phpmailer/language/');
    $mail2->isHTML();
    $mail2->setFrom('rodion-web@yandex.ru', 'Данные с формы');
    $mail2->AddAddress($emailP);
    $mail2->Subject = 'Привет! Это данные с формы.';
    $mail2->Body = $message2;
    $mail2->Send();

    $message = 'Данные отправлены!';
}

$response = ['message' => $message];
header('Content-type: application/json');
echo json_encode($response);



$refferer = getenv('HTTP_REFERER');
$date = date("d.m.y"); // число.месяц.год  
$time = date("H:i"); // часы:минуты:секунды 


$f = fopen("leads.xls", "a+");
fwrite($f, " <tr>");
fwrite($f, " <td>$emailP</td> <td></td>  <td>$date / $time</td>");
fwrite($f, " <td>$refferer</td>");
fwrite($f, " </tr>");
fwrite($f, "\n ");
fclose($f);

$mail->ClearAddresses();
$mail2->ClearAddresses();
