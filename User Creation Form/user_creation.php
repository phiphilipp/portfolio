<form method="POST">
  <table>
    <tr>
      <td><label for="user">Benutzername:</label></td>
      <td><input type="text" name="user"></td>
    </tr>
    <tr>
      <td><label for="password1">Passwort:</label></td>
      <td><input type="password" name="password1"></td>
    </tr>
    <tr>
      <td><label for="password1">Wiederholung Passwort:</label></td>
      <td><input type="password" name="password2"></td>
    </tr>
    <tr>
      <td style="text-align:right" colspan="2"><input type="submit" name="btn_register" value="Registrieren"></td>
    </tr>
  </table>
</form>

<?php 
/*  Der Table "login" in der "userdata" SQL-Datenbank hat ein 
    Auto Increment auf einer ID (login_id),
    sodass jeder neue Benutzer automatisch eine eigene ID erhält.
    Der Benutzername in der Datenbank ist unique. */

mysqli_report(MYSQLI_REPORT_OFF);

if(isset($_POST["btn_register"])){
  $hostname = "localhost";
  $username = "root";
  $password = "";
  $database = "userdata";

  $connection = @mysqli_connect($hostname, $username, $password, $database);

  /*  Fehlerbehandlung und Abbruch des Scripts im Falle eines
      Fehlers beim Verbinden zur Datenbank.
      Ausgabe in die() zum Entwickeln und Testen. */ 
  if(mysqli_connect_errno()) {
    die("Fehler (" . mysqli_connect_errno() . '): ' . mysqli_connect_error());
  }
  // Schutz vor SQL Injection
  $user = mysqli_real_escape_string($connection, $_POST["user"]);
  $password1 = $_POST["password1"];
  $password2 = $_POST["password2"];

  /*  Prüfung ob ein Wert in $user vorhanden ist und 
      ob die eingegebenen Passwörter übereinstimmen. */
  if ($user && $password1 === $password2){
    /* Blowfish Verschlüsselung, optional auch PASSWORD_DEFAULT aber 
    dann auf die SQL Feldgröße für die Zukunft denken */
    $password1 = password_hash($password1, PASSWORD_BCRYPT);
    // Senden des Querys an die Datenbank mit dem verschlüsselten Passwort und SQL Injection-Schutz
    $query = "INSERT INTO login (benutzer,passwort) VALUES ('$user', '$password1')";
    mysqli_query($connection, $query);
    // Ausgabe des Errors, wenn einer vorhanden, zu Testzwecken
    if (mysqli_errno($connection) == 1062){
      echo "Der Benutzername ist bereits vergeben";
    }
    else{
      echo "Das Benutzerkonto wurde erfolgreich angelegt.";
    }
    echo mysqli_error($connection);
  }
  // Fehlerausgabe zu Testzwecken, wenn die eingegebenen Passwörter nicht übereinstimmen
  elseif ($password1 != $password2){
    echo "Die Passwörter stimmen nicht überein";
  }
  else{
    echo "Bitte überprüfen Sie Ihre Eingabe.";
  }
  mysqli_close($connection);
}
?>