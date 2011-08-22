<div class="maincontent">
    <div id="headline"><h1>Envoyer des invitations</h1></div>
</div>
<?
$current_user = $engine->getCurrentUser();
$userTeam = ($user) ? $engine->getUserTeam($current_user['userTeamID']) : false;
$users = $engine->getAllUsers();
?>
<div class="maincontent">
    <div class="ppp">
        <center><span style="color:red;"><b><? echo $message; ?></b></span></center>
        <h2>Inviter des inscrits à rejoindre votre groupe</h2>
        <form method="post" name="IN" action="/?op=invite_friends">
            <br>
            <input type="hidden" name="type" id="type" value="IN" />
            <input type="hidden" name="userTeamID" id="userTeamID" value="<? echo $current_user['userTeamID']; ?>" />
            <div class="formfield"><b>Choisisser un ou plusieurs inscrits à rejoindre votre groupe '<? echo $userTeam['name']; ?>'</b></div><br />
            <? for ($i = 0; $i < 5; $i++) { ?>
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

        <h2>Inviter des amis</h2>
        <form method="post" name="OUT"  action="/?op=invite_friends">
            <br>
            <input type="hidden" name="type" id="type" value="OUT" />
            <input type="hidden" name="userTeamID" id="userTeamID" value="<? echo $current_user['userTeamID']; ?>" />
            <div class="formfield"><b>Entrez un ou plusieurs emails de vos amis pour les inviter à pronostiquer avec vous !</b></div><br />
            <? for ($i = 0; $i < 5; $i++) { ?>
                <input type="text" size="30" id="email_<? echo $i; ?>" name="email_<? echo $i; ?>" /> qui sera inscrit au groupe '<? echo $userTeam['name']; ?>'
                <br />
                <br />
            <? } ?>
            <br /><br />
            <center><input class="image" type="image" src="/include/theme/<? echo $config['template']; ?>/images/submit.gif" value="Valider"></center>
        </form>

        <h2>Historique de vos invitations à des groupes</h2>
        <?
        $invits = $engine->getInvitationsBySender($current_user['userID']);
        if (count($invits) > 0) {
            foreach ($invits as $invitation) {
                if ($invitation['status'] == 2 || $invitation['status'] == -2) {
                    $user_invited = $engine->getUserByEmail($invitation['email']);
                    if($user_invited) {
                        $name = $user_invited['name'];
                    }
                    else {
                        continue;
                    }
                } else {
                    $name = $invitation['email'];
                }
                
                $status = ($invitation['status'] < 0) ? "<font color='orange'><b>Utilisée</b></font>" : (($invitation['expired'] == 1) ? "<font color='red'><b>Expirée</b></font>" : "<font color='green'><b>Inutilisée</b></font>");
                echo $name . " (" . $invitation['user_team_name'] . ") : " . $status . "<br/>";
            }
        }
        ?>
    </div>
</div>