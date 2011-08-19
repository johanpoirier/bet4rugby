<?
session_start();
define('WEB_PATH', "/");
define('BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . "/");
define('URL_PATH', "/");

require('../../../include/classes/Engine.php');
$engine = new Engine(false, false);

$op = $_REQUEST['op'];
switch ($op) {
    case "getTeamsByPool":
        $poolID = $_REQUEST['pool'];
        $side = $_REQUEST['side'];
        $teams = $engine->getTeamsByPool($poolID);
        ?>
        <select name="team<? echo $side; ?>" id="team<? echo $side; ?>">
            <? foreach ($teams as $team) { ?>
                <option value="<? echo $team['teamID']; ?>"><? echo $team['name']; ?></option>
            <? } ?>
        </select>
        <?
        break;

    case "getTeamsByPhase":
        $phase = $engine->getPhase($_REQUEST['phase']);
        $side = $_REQUEST['side'];
        $teams = $engine->getQualifiedTeamsByPhase($phase);
        ?>
        <select name="team<? echo $side; ?>" id="team<? echo $side; ?>">
            <? foreach ($teams as $team) { ?>
                <option value="<? echo $team['teamID']; ?>"><? echo $team['name']; ?></option>
            <? } ?>
        </select>
        <?
        break;

    case "saveTag":
        $tag = $_REQUEST['tag'];
        $teamID = -1;
        if (isset($_REQUEST['userTeamID']))
            $teamID = $_REQUEST['userTeamID'];
        $teams = $engine->saveTag($tag, $teamID);
        echo $engine->loadTags($teamID);
        break;

    case "delTag":
        $tagID = $_REQUEST['tagID'];
        $teamID = -1;
        if (isset($_REQUEST['userTeamID']))
            $teamID = $_REQUEST['userTeamID'];
        $teams = $engine->delTag($tagID);
        echo $engine->loadTags($teamID);
        break;

    case "getTags":
        echo $engine->loadTags($_POST['userTeamID'], $_POST['start']);
        break;

    default:
        break;
}
?>
