<div class="maincontent">
    <div class="headline"><h1>Connexion</h1></div>
    <div class="ppp">
        <h2>Connexion</h2>
        <font color="#ff0000"><?php echo $message; ?></font>
        <form method="post" action="/?op=login">
            <input type="hidden" name="login" value="1" />
            <input type="hidden" name="redirect" value="" />
            <input type="hidden" name="code" value="<?php if(isset($_GET['s'])) { echo $_GET['s']; } ?>" />
            <br />
            <div class="formfield"><b>Nom d'utilisateur</b></div>
            <input type="text" name="login" value="" class="textinput" maxlength="100" autofocus required /><br /><br />
            <div class="formfield"><b>Mot de passe</b></div>
            <input type="password" name="pass" class="textinput" maxlength="20" required /><br /><br />
            <input class="imageinput" type="image" src="/include/theme/<?php echo $engine->config['template']; ?>/images/login.gif" value="logga in" />
        </form>
    </div>
</div>
