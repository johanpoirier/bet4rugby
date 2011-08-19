<div class="maincontent">
    <div id="headline">
        <h1>Mon Compte</h1>
    </div>
</div>
<?
if (isset($_SESSION["userID"])) {
    $user = $engine->getUser($_SESSION["userID"]);
}
?>
<form id="formProfile" method="POST" action="/?op=update_profile">
    <div class="maincontent">
        <p>
        <br />
        <u>Login</u> : <? echo $user['login']; ?>
        <br />
        <br />
        <u>Nom</u> : <input type="text" name="name" value="<? echo $user['name']; ?>" size="30" />
        <br />
        <br />
        <u>Equipe</u> : <? echo $user['team']; ?>
        <br />
        <br />
        <u>E-Mail</u> : <input type="text" name="email" value="<? echo $user['email']; ?>" size="40" />
        <br />
        <br />
        <u>Nouveau mot de passe</u> : 
        <br />
        <br />
        <input type="password" name="pwd1" /> (à confirmer pour changer : <input type="password" name="pwd2" />)
        </p>
        <br />
        <p align="center">
            <font color="#ff0000"><? echo $message; ?></font>
            <br />
            <br />
            <input type="image" src="/include/theme/<? echo $config['template']; ?>/images/submit.gif" name="submit" alt="Valider" />
        </p>
    </div>
</form>