<?
$pools = $engine->getPoolsByPhase();
$phases = $engine->getPhases();
$teams = $engine->getTeamsByPool(1);
$mois = $engine->getMonths();
$dateCourante = $engine->getSettingDate("DATE_DEBUT");
?><script type="text/javascript">
    <!--
    var fillTeamsA = function(response) {
        document.getElementById('teamsDivA').innerHTML = response;
    }

    var fillTeamsB = function(response) {
        document.getElementById('teamsDivB').innerHTML = response;
    }

    function loadTeams(side) {
        var selectPool = document.getElementById('pool');

        if(side == 'A') {
            $.ajax({
                type: "POST",
                url: "/include/classes/ajax.php",
                data: "op=getTeamsByPool&side=" + side + "&pool=" + selectPool.options[selectPool.selectedIndex].value,
                success: fillTeamsA
            });
        }
        else {
            $.ajax({
                type: "POST",
                url: "/include/classes/ajax.php",
                data: "op=getTeamsByPool&side=" + side + "&pool=" + selectPool.options[selectPool.selectedIndex].value,
                success: fillTeamsB
            });
        }
    }

    function loadTeams2(phase, side) {
        if(side == 'A') {
            $.ajax({
                type: "POST",
                url: "/include/classes/ajax.php",
                data: "op=getTeamsByPhase&side=" + side + "&phase=" + phase,
                success: fillTeamsA
            });
        }
        else {
            $.ajax({
                type: "POST",
                url: "/include/classes/ajax.php",
                data: "op=getTeamsByPhase&side=" + side + "&phase=" + phase,
                success: fillTeamsB
            });
        }
    }

    function changePhase(phase) {
        if(phase > 1) document.getElementById('tdPools').style.visibility = 'hidden';
        else document.getElementById('tdPools').style.visibility = 'visible';
        loadTeams2(phase, 'A');
        loadTeams2(phase, 'B');
    }
    //-->
</script>
<div class="maincontent">
    <div id="headline"><h1>Matchs</h1></div>
    <div class="tag_cloud">
        <? foreach ($pools as $pool) { ?>
            <span style="font-size: 150%">Groupe <? echo $pool['name']; ?></span>
            <?
            $matchs = $engine->getMatchsByPool($pool['poolID']);
            foreach ($matchs as $match) {
                ?>
                <br /><? echo $match['dateStr']; ?> : <? echo $match['teamAname']; ?> - <? echo $match['teamBname']; ?>
            <? } ?>
            <br />
            <br />
        <? } ?>
        <?
        foreach ($phases as $phase) {
            if ($phase['phaseID'] > 1) {
                ?>
                <span style="font-size: 150%"><? echo $phase['name']; ?></span>
                <?
                $matchs = $engine->getMatchsByPhase($phase['phaseID']);
                foreach ($matchs as $match) {
                    ?>
                    <br /><? echo $match['dateStr']; ?> : <? echo $match['teamAname']; ?> - <? echo $match['teamBname']; ?>
                <? } ?>
                <br />
                <br />
                <?
            }
        }
        ?>
    </div>
    <br />

    <form name="add_team" action="?op=add_match" method="post">
        <div class="tag_cloud">
            <table width="100%">
                <tr>
                    <td colspan="2" width="100%">Date :</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <select name="day" id="day">
                            <? for ($jour = 1; $jour <= 31; $jour++) { ?>
                                <option value="<? echo $jour; ?>"><? echo $jour; ?></option>
                            <? } ?>
                        </select>
                        <select name="month" id="month">
                            <? foreach ($mois as $moi) { ?>
                                <option value="<? echo $moi[0]; ?>"><? echo $moi[1]; ?></option>
                            <? } ?>
                        </select> <? echo $dateCourante['year']; ?> <input type="text" size="2" name="hour" value="<? echo $dateCourante['hour']; ?>" id="hour" />h
                        <input type="text" size="2" name="minutes" id="minutes" value="<? echo $dateCourante['minute']; ?>" />
                    </td>
                </tr>
                <tr>
                    <td width="50%">Groupe :</td><td width="50%">Phase :</td>
                </tr>
                <tr>
                    <td id="tdPools" style="visibility:visible;">
                        <select name="pool" id="pool" onchange="loadTeams('A'); loadTeams('B');">
                            <? foreach ($pools as $pool) { ?>
                                <option value="<? echo $pool['poolID']; ?>"><? echo $pool['name']; ?></option>
                            <? } ?>
                        </select>
                    </td>
                    <td>
                        <select name="phase" id="phase" onchange="changePhase(this.options[selectedIndex].value)">
                            <? foreach ($phases as $phase) { ?>
                                <option value="<? echo $phase['phaseID']; ?>"><? echo $phase['name']; ?></option>
                            <? } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Equipe A :</td>
                    <td>Equipe B :</td>
                </tr>
                <tr>
                    <td id="teamsDivA">
                        <select name="teamA" id="teamA">
                            <? foreach ($teams as $team) { ?>
                                <option value="<? echo $team['teamID']; ?>"><? echo $team['name']; ?></option>
                            <? } ?>
                        </select>
                    </td>
                    <td id="teamsDivB">
                        <select name="teamB" id="teamB">
                            <? foreach ($teams as $team) { ?>
                                <option value="<? echo $team['teamID']; ?>"><? echo $team['name']; ?></option>
                            <? } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:center;"><input type="submit" name="add_match" value="Ajouter" /></td>
                </tr>
            </table>
        </div>
    </form>
</div>
