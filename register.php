<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Register</title>
  </head>
  <body>
  <form action="regi.php" method="post">
      <input type="text" name="iserv" placeholder="ISERV-Mail-Adresse" required><br>
      <select name="role" required>
        <option value="1">Lehry</option>
        <option value="0">Schüly</option>
        <option value="2">Sozialarbeity</option>
      </select><br>
      <p> Bitte beachte, dass eine falsche Angabe der Rolle in keinerlei Vorteil resultiert, da alle Rollen die selben Rechte haben. </p>
      <button type="submit" name="submit">Register</button>
    </form>
  </body>
</html>
