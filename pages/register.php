<div class="maincontent">
    <div class="headline">
        <div class="headline-title">
            <h1>S'inscrire</h1>
        </div>
    </div>

    <div class="ppp">
        <center><span style="color:red;"><b><?php echo $message; ?></b></span></center>

        <form method="post" action="/?op=register">
            <input type="hidden" name="redirect" value=""/>
            <input type="hidden" name="code" value="<?php if (isset($_GET['c'])) {
                echo $_GET['c'];
            } ?>"/>
            <br/>

            <div class="formfield"><b>Votre nom de famille</b></div>
            <input type="text" name="name" id="name" value="" class="textinput" size="40" maxlength="60"
                   placeholder="Nom" autofocus required/><br/><br/>

            <div class="formfield"><b>Votre prénom</b></div>
            <input type="text" name="firstname" id="firstname" value="" class="textinput" size="40" maxlength="40"
                   placeholder="Prénom" required/><br/><br/>

            <div class="formfield"><b>Votre adresse de courriel</b></div>
            <input type="email" name="email" id="email" value="" class="textinput" size="40" maxlength="100"
                   placeholder="Adresse de courriel" required/><br/><br/>

            <div class="formfield"><b>Votre login</b> <i>(utilisé pour vous connecter au site)</i></div>
            <input type="text" name="login" id="login" value="" class="textinput" size="40" maxlength="100"
                   placeholder="Login" required/><br/><br/>

            <div class="formfield"><b>Votre mot de passe</b></div>
            <input type="password" name="password1" id="password1" class="textinput" size="30" maxlength="20"
                   placeholder="Mot de passe" required/><br/><br/>

            <div class="formfield"><b>Votre mot de passe à nouveau</b></div>
            <input type="password" name="password2" id="password1" class="textinput" size="30" maxlength="20"
                   placeholder="Répétez votre mot de passe" required/><br/><br/>

            <input class="imageinput" type="image"
                   src="/include/theme/<?php echo $config['template']; ?>/images/register3.gif" value="S'inscrire"/>
        </form>
    </div>
</div>
