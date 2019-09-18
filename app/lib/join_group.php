<div class="maincontent">
    <div class="headline">
        <div class="headline-title">
            <h1>Rejoindre un groupe</h1>
        </div>
    </div>
<?php
$code = false;
$invitation = false;
if (isset($_GET['c'])) {
    $code = $_GET['c'];
    $invitation = $engine->isInvitedByCode($code);
    if ($invitation) {
        $autoUserTeamID = $invitation['userTeamID'];
    }
}
$userTeams = $engine->getUserTeams();

$currentUser = $engine->getCurrentUser();
$invitedUserTeam = false;
if ($invitation && ($invitation['email'] == $currentUser['email'])) {
    $invitedUserTeam = $engine->getUserTeam($invitation['userTeamID']);
}
?>
    <div class="ppp">
        <center><span style="color:red;"><b><?php echo $message; ?></b></span></center>
        <form method="post" id="join_group_form" name="join_group_form" action="/?op=join_group">
            <input type="hidden" name="code" id="code" <?php if ($code) {
                echo 'value="' . $code . '"';
            } ?>/>
            <br/>

            <div class="formfield"><b>Sélectionner le groupe que vous souhaitez rejoindre</b></div>
            <select name="group" id="group">
                <?php foreach ($userTeams as $userTeam) {
                    $selected = $userTeam['userTeamID'] === $invitedUserTeam['userTeamID'];
                ?>
                    <option name="<?= $userTeam['userTeamID'] ?>" value="<?= $userTeam['userTeamID'] ?>" <?= ($selected ? 'selected' : '') ?>>
                        <?= $userTeam['name'] ?> (<?= $userTeam['ownerName'] ?>)
                    </option>
                <?php } ?>
            </select>
            <br/><br/>

            <div class="formfield"><b>Veuillez entrer le mot de passe du groupe :</b></div>
            <input type="password" name="password" size="12" id="password"/>
            <br/><br/><br/>
            <input class="image" type="image" src="/template/<?php echo $config['template']; ?>/images/submit.gif"
                   value="create it"/>
        </form>
    </div>
</div>
<?php
if ($invitedUserTeam !== false) {
    ?>
    <script type="text/javascript">
        if (confirm("Souhaitez-vous rejoindre le groupe '<?= $invitedUserTeam['name'] ?>' ?")) {
            $("form#join_group_form").submit();
        }
    </script>
    <?php
}
?>