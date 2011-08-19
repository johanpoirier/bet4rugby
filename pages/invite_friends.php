<div class="maincontent">
    <div id="headline"><h1>Envoyer des invitations</h1></div>
</div>
<?
$user = $engine->getCurrentUser();
$users = $engine->getUsers();
?>
<div class="maincontent">
    <div class="ppp">
        <center><span style="color:red;"><b><? echo $message; ?></b></span></center>
        <h2>Inviter des inscrits à rejoindre vos groupes</h2>
        <form method="post" name="IN" action="/?op=invite_friends">
            <br>
            <input type="hidden" name="type" id="type" value="IN" />
            <input type="hidden" name="userTeamID" id="userTeamID" value="<? echo $user['userTeamID']; ?>" />
            <div class="formfield"><b>Choisisser un ou plusieurs inscrits à rejoindre vos groupes</b></div><br />
            <? for($i=0; $i<5; $i++) { ?>
            Inviter
            <select name="userID_<? echo $i; ?>" id="userID_<? echo $i; ?>">
                <option name="0" value="0"> - </option>
                <? foreach ($users as $user) { ?>
                <option value="<? echo $user['userID']; ?>"><? echo $user['name']; ?></option>
                <? } ?>
            </select>
            <br />
            <br />
            <? } ?>
            <br /><br />
            <center><input class="image" type="image" src="/include/theme/<? echo $config['template']; ?>/images/submit.gif" value="Valider"></center>
        </form>
        <!-- END is_group -->				
        <h2>Inviter des amis</h2>
        <form method="post" name="OUT"  action="/?op=invite_friends">
            <br>
            <input type="hidden" name="type" id="type" value="OUT">
            <div class="formfield"><b>Entrez un ou plusieurs emails de vos amis pour les inviter à pronostiquer avec vous !</b></div><br />
            <!-- BEGIN invit_out -->
            <input type="text" id="email{invit_out.ID}" name="email{invit_out.ID}" /> qui sera inscrit à <select name="groupID{invit_out.ID}" id="groupID{invit_out.ID}">
                <option name="0" value="0">Aucun groupe</option>
                <!-- BEGIN groups -->
                <option name="{invit_out.groups.ID}" value="{invit_out.groups.ID}">{invit_out.groups.NAME}</option>
                <!-- END groups -->
            </select><br /><br />
            <!-- END invit_out -->
            <br /><br />
            <center><input class="image" type="image" src="/include/theme/<? echo $config['template']; ?>/images/submit.gif" value="Valider"></center>
        </form>

    </div>
</div>