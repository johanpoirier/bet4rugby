<div class="maincontent">
    <div id="headline">
        <h1>Mon compte</h1>
    </div>
</div>
<?
$user = $engine->getCurrentUser();
$userTeam = ($user) ? $engine->getUserTeam($user['userTeamID']) : false;
?>
<div class="maincontent">
    <div class="ppp">
        <center><span style="color: red;"><b><? echo $message; ?></b></span></center>
        <ul>
            <li>
                <h2><a href="/?op=change_account">Modifier mon compte</a></h2>
            </li>
            <? if (!$userTeam) { ?>
                <li>
                    <h2><a href="/?op=create_group">Créer un groupe</a></h2>
                </li>
                <li>
                    <h2><a href="/?op=join_group">Rejoindre un groupe</a></h2>
                </li>
            <? } else { ?>
                <li>
                    <h2><a href="/?op=leave_group&user_team_id=<? echo $userTeam['userTeamID']; ?>" onclick="return(confirm('Êtes-vous sûr de vouloir quitter votre groupe \'<? echo $userTeam['name']; ?>\' ?'));">
                            Quitter mon groupe "<? echo $userTeam['name']; ?>"
                        </a></h2>
                </li>
            <? }
                
               if($userTeam) {
            ?>
                <li>
                    <h2><a href="/?op=invite_friends">Inviter des amis</a></h2>
                </li>
            <? } ?>
        </ul>			
    </div>
</div>