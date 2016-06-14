<?php
if($_POST)
{
 $to_Email = "salim2110@gmail.com"; //Podaj tu email docelowy
 $subject = 'Ah!! My email from Somebody out there...'; //Tutaj temat wiadomo�ci - mo�esz te� wykorzysta� pole formularza i pobra� t� warto�� od klienta
  
  
 //Sprawdzamy czy jest to rz�danie Ajax, je�li nie..
 if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
  
 //Ko�czymy skrypt wysy�aj�c dane JSON
 $output = json_encode(
 array(
 'type'=>'error', 
 'text' => 'Rz�danie musi przej�� przez AJAX'
 ));
  
 die($output);
 } 
  
 //Sprawdzamy czy wszystkie pola zosta�y wys�ane. ko�czymy skrypt je�li nie (tutaj dodawaj wi�cej p�l, kt�re s� wymagane)
 if(!isset($_POST["userName"]) || !isset($_POST["userEmail"]) || !isset($_POST["userPhone"]) || !isset($_POST["userMessage"]))
 {
 $output = json_encode(array('type'=>'error', 'text' => 'POLA S� PUSTE!'));
 die($output);
 }
 
 //Pobieramy dane z formularza
 $user_Name = filter_var($_POST["userName"], FILTER_SANITIZE_STRING);
 $user_Email = filter_var($_POST["userEmail"], FILTER_SANITIZE_EMAIL);
 $user_Phone = filter_var($_POST["userPhone"], FILTER_SANITIZE_STRING);
 $user_Message = filter_var($_POST["userMessage"], FILTER_SANITIZE_STRING);
  
 //Dodatkowa validacja PHP (tylko dla p�l wymaganych)
 if(strlen($user_Name)<4) // Wywala b��d je�li imi� ma mniej ni� 4 znaki
 {
 $output = json_encode(array('type'=>'error', 'text' => 'Imi� jest za kr�tkie!'));
 die($output);
 }
 if(!filter_var($user_Email, FILTER_VALIDATE_EMAIL)) //sprawdzamy email
 {
 $output = json_encode(array('type'=>'error', 'text' => 'Prosz� poda� poprawny email!'));
 die($output);
 }
 if(!is_numeric($user_Phone)) //sprawdzamy czy telefon jest numeryczny
 {
 $output = json_encode(array('type'=>'error', 'text' => 'Tylko liczby s� dozwolone'));
 die($output);
 }
 if(strlen($user_Message)<5) //Sprawdzamy czy wiadomo�� ma wi�cej ni� 5 znak�w
 {
 $output = json_encode(array('type'=>'error', 'text' => 'Wiadomo�� za kr�tka! Wpisz co� jeszcze.'));
 die($output);
 }
  
 //Nag��wki do Maila
 $headers = 'From: '.$user_Email.'' . "\r\n" .
 'Reply-To: '.$user_Email.'' . "\r\n" .
 'X-Mailer: PHP/' . phpversion();
  
 $sentMail = @mail($to_Email, $subject, $user_Message .' -'.$user_Name, $headers);
  
 if(!$sentMail)
 {
 $output = json_encode(array('type'=>'error', 'text' => 'Nie mo�na wys�a� wiadomo�ci. Sprawd� konfiguracj� PHP Mail'));
 die($output);
 }else{
 $output = json_encode(array('type'=>'message', 'text' => 'Witaj '.$user_Name .' Dzi�kuj� za wiadomo��!'));
 die($output);
 }
}
?>