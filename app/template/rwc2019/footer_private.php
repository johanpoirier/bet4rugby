<?php
    include_once(BASE_PATH . 'lib/config.inc.php');
?>
<footer>
    <p><a href="index.php">Accueil</a> | <a href="/?op=account">Mon compte</a> | <a href="/?op=rules">Règlement</a> | <a href="<?= $config['github'] ?>/issues/new" target="_blank">Soumettre un bug</a> | <a href="mailto:<?= $config['email'] ?>">E-mail</a> | <a href="/?op=logout">Déconnexion</a></p>
    <p>©2019 JoPs</p>
</footer>
