<?php

session_start();

if (empty($_SESSION['count'])) {
   $_SESSION['count'] = 1;
} else {
   $_SESSION['count']++;
}
?>

<p>
Hola visitante, ha visto esta página <?php echo $_SESSION['count']; ?> veces.
</p>

<p>
Para continuar, <a href="session.php?<?php echo htmlspecialchars(SID); ?>">haga clic
aquí</a>.
</p>