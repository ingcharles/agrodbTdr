<?php

session_start();

if (empty($_SESSION['count'])) {
   $_SESSION['count'] = 1;
} else {
   $_SESSION['count']++;
}
?>

<p>
Hola visitante, ha visto esta p�gina <?php echo $_SESSION['count']; ?> veces.
</p>

<p>
Para continuar, <a href="session.php?<?php echo htmlspecialchars(SID); ?>">haga clic
aqu�</a>.
</p>