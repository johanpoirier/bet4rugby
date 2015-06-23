<div class="maincontent">
    <div class="headline"><h1>Création de groupe</h1></div>
</div>

<div class="maincontent">
    <div class="ppp">
        <h2>Creer un groupe</h2>
        <center><span style="color:red;"><b><?php echo $message; ?></b></span></center>
        <form method="post" action="/?op=create_group">
            <br>
            <div class="formfield"><b>Saisissez votre nom de groupe</b></div>
            <input type="text" name="group_name" value="" class="textinput" maxlength="100" /><br><br>
            <div class="formfield"><b>Saisissez un code d'accès pour contrôler l'adhésion au groupe</b></div>
            <input type="password" name="password1" value="" class="textinput" maxlength="100" /><br><br>
            <div class="formfield"><b>Saisissez à nouveau votre code d'accès du groupe</b></div>
            <input type="password" name="password2" value="" class="textinput" maxlength="100" /><br><br>
            <input class="image" type="image" src="/include/theme/<?php echo $config['template']; ?>/images/submit.gif" value="create it" />
        </form>
    </div>
</div>