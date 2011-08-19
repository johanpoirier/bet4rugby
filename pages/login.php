<div class="maincontent">
  <div id="headline"><h1>Connexion</h1></div>
	<div class="ppp">
		<h2>Connexion</h2>
		<form method="post" action="/?op=register">
			<input type="hidden" name="login" value="1" />
			<input type="hidden" name="redirect" value="" />
			<br />
			<div class="formfield"><b>Nom d'utilisateur</b></div>
			<input type="text" name="login" value="" class="textinput" maxlength="100" /><br /><br />
			<div class="formfield"><b>Mot de passe</b></div>
			<input type="password" name="pass" class="textinput" maxlength="20" /><br /><br />
			<input class="imageinput" type="image" src="/include/theme/<? echo $engine->config['template']; ?>/images/login.gif" value="logga in" />
		</form>
	</div>
</div>
