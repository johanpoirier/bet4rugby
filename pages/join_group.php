<div class="maincontent">
    <div id="headline">
        <h1>Rejoindre un groupe</h1>
    </div>
</div>
<?
$code = false;
$invitation = false;
if (isset($_GET['c'])) {
    $code = $_GET['c'];
    $invitation = $engine->isInvitedByCode($code);
    if($invitation) {
        $autoUserTeamID = $invitation['userTeamID'];
    }
}
$userTeams = $engine->getUserTeams();
?>
<div class="maincontent">
    <div class="ppp">
        <center><span style="color:red;"><b><? echo $message; ?></b></span></center>
        <form method="post" id="join_group_form"  name="join_group_form" action="/?op=join_group">
            <input type="hidden" name="code" id="code" <? if($code) { echo 'value="'. $code . '"'; } ?>/>
            <br/>
            <div class="formfield"><b>Sélectionner le groupe que vous souhaitez rejoindre</b></div>
            <select name="group" id="group">
                <? foreach ($userTeams as $userTeam) { ?>
                    <option name="<? echo $userTeam['userTeamID']; ?>" value="<? echo $userTeam['userTeamID']; ?>"><? echo $userTeam['name']; ?> (<? echo $userTeam['ownerName']; ?>)</option>
                <? } ?>
            </select>
            <br /><br />
            <div class="formfield"><b>Veuillez entrer le mot de passe du groupe :</b></div>
            <input type="password" name="password" size="12" id="password"/>
            <br /><br /><br />
            <input class="image" type="image" src="/include/theme/<? echo $config['template']; ?>/images/submit.gif" value="create it" />
        </form>
    </div>
</div>
<?
$currentUser = $engine->getCurrentUser();
if($invitation && ($invitation['email'] == $currentUser['email'])) {
    $userTeam = $engine->getUserTeam($invitation['userTeamID']);
?>
<script type="text/javascript">
    if(confirm("Souhaitez-vous rejoindre le groupe '<? echo $userTeam['name']; ?>' ?")) {
        $("form#join_group_form").submit();
    }
</script>
<?    
}
?>