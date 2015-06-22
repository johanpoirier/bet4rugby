<?php
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
        <?php foreach ($pools as $pool) { ?>
            <span style="font-size: 150%">Groupe <?php echo $pool['name']; ?></span>
            <?php
            $matchs = $engine->getMatchsByPool($pool['poolID']);
            foreach ($matchs as $match) {
                ?>
                <br /><?php echo $match['dateStr']; ?> : <?php echo $match['teamAname']; ?> - <?php echo $match['teamBname']; ?>
            <?php } ?>
            <br />
            <br />
        <?php } ?>
        <?php
        foreach ($phases as $phase) {
            if ($phase['phaseID'] > 1) {
                ?>
                <span style="font-size: 150%"><?php echo $phase['name']; ?></span>
                <?php
                $matchs = $engine->getMatchsByPhase($phase['phaseID']);
                foreach ($matchs as $match) {
                    ?>
                    <br /><?php echo $match['dateStr']; ?> : <?php echo $match['teamAname']; ?> - <?php echo $match['teamBname']; ?>
                <?php } ?>
                <br />
                <br />
                <?php
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
                            <?php for ($jour = 1; $jour <= 31; $jour++) { ?>
                                <option value="<?php echo $jour; ?>"><?php echo $jour; ?></option>
                            <?php } ?>
                        </select>
                        <select name="month" id="month">
                            <?php foreach ($mois as $moi) { ?>
                                <option value="<?php echo $moi[0]; ?>"><?php echo $moi[1]; ?></option>
                            <?php } ?>
                        </select> <?php echo $dateCourante['year']; ?> <input type="number" min="0" max="23" size="2" name="hour" value="<?php echo $dateCourante['hour']; ?>" id="hour" />h
                        <input type="number" min="0" max="59" size="2" name="minutes" id="minutes" value="<?php echo $dateCourante['minute']; ?>" />
                    </td>
                </tr>
                <tr>
                    <td width="50%">Groupe :</td><td width="50%">Phase :</td>
                </tr>
                <tr>
                    <td id="tdPools" style="visibility:visible;">
                        <select name="pool" id="pool" onchange="loadTeams('A'); loadTeams('B');">
                            <?php foreach ($pools as $pool) { ?>
                                <option value="<?php echo $pool['poolID']; ?>"><?php echo $pool['name']; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td>
                        <select name="phase" id="phase" onchange="changePhase(this.options[selectedIndex].value)">
                            <?php foreach ($phases as $phase) { ?>
                                <option value="<?php echo $phase['phaseID']; ?>"><?php echo $phase['name']; ?></option>
                            <?php } ?>
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
                            <?php foreach ($teams as $team) { ?>
                                <option value="<?php echo $team['teamID']; ?>"><?php echo $team['name']; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td id="teamsDivB">
                        <select name="teamB" id="teamB">
                            <?php foreach ($teams as $team) { ?>
                                <option value="<?php echo $team['teamID']; ?>"><?php echo $team['name']; ?></option>
                            <?php } ?>
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
