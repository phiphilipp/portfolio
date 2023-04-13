<?php
// Erzeugen einer Session
session_start();

// Erzeugen des Arrays mit den Buchstaben für das Captcha
$captcha = "";
$zufallsBuchstabe_array = range("A", "Z");

// Mischen des Arrays sowie Auswahl von 5 Buchstaben
shuffle($zufallsBuchstabe_array);
for ($i = 0; $i < 5; $i++){
  $captcha .= $zufallsBuchstabe_array[$i];
}

// Erstellen der Bild-Palette
$im = imagecreate(200,50);
imagecolorallocate($im, 220, 220, 220);

// Erstellen und Mischen des Arrays mit Schriftarten für das Captcha
$ttf_array = ["Gabriola.ttf", "cour.ttf", "verdana.ttf","tahomabd.ttf", "Candaral.ttf"];
shuffle($ttf_array);

for($i=0, $x=10; $i <5; $i++, $x+=40){
  // Erzeugen von zufälligen Werten für Größe, Winkel und Farbe der einzelnen Buchstaben
  $size = mt_rand(12,34);
  $angle = mt_rand(-30, 30);
  $color = imagecolorallocate($im,mt_rand(0,200),mt_rand(0,200),mt_rand(0,200));
  imagettftext($im, $size, $angle, $x,35, $color,$ttf_array[$i], $zufallsBuchstabe_array[$i]);

  // Erzeugen von Linien, um Bilderkennung zu erschweren
  $yLineStart = mt_rand(0,50);
  $yLineEnd = mt_rand(0,50);
  imageline($im, 0, $yLineStart, 200, $yLineEnd, $color);

  // Erzeugen von Kreisen, um Bilderkennung zu erschweren
  $circMidX = mt_rand(0,200);
  $circMidY = mt_rand(0,50);
  $circWidth = mt_rand(0,300);
  $circHeight= mt_rand(0,300);
  imagearc($im, $circMidX, $circMidY, $circWidth, $circHeight, 0,0,$color);
}
// Speichern des Bildes als JPEG
imagejpeg($im, "captcha.jpg");

?>
<!-- Einbinden des erzeugten Captchas -->
<img src="captcha.jpg">
<form action="captcha.php" method="post">
  <input type="text" name="input">
  <input type="submit" name="senden" value="Prüfen">
</form>

<?php
// Einfache Prüfung ob der eingebenene Wert dem angezeigten Captcha-Bild-Wert entspricht  
if (isset($_POST["senden"])){
  if(strtoupper($_POST["input"])== $_SESSION["captcha"]){
    echo "Richtig";
  }
  else{
    echo "Nicht richtig";
  }
}
// Speichern des Captcha-Wertes am Ende des Scripts in der Session-Variable
$_SESSION["captcha"] = $captcha;
?>