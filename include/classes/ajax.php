<?php
session_start();
define('WEB_PATH', "/");
define('BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . "/");
define('URL_PATH', "/");

require('Engine.php');
$engine = new Engine(false, false);

$op = $_REQUEST['op'];
switch ($op) {
    case "getTeamsByPool":
        $poolID = $_REQUEST['pool'];
        $side = $_REQUEST['side'];
        $teams = $engine->getTeamsByPool($poolID);
        ?>
        <select name="team<?php echo $side; ?>" id="team<?php echo $side; ?>">
            <?php foreach ($teams as $team) { ?>
                <option value="<?php echo $team['teamID']; ?>"><?php echo $team['name']; ?></option>
            <?php } ?>
        </select>
        <?php
        break;

    case "getTeamsByPhase":
        $phase = $engine->getPhase($_REQUEST['phase']);
        $side = $_REQUEST['side'];
        $teams = $engine->getQualifiedTeamsByPhase($phase);
        ?>
        <select name="team<?php echo $side; ?>" id="team<?php echo $side; ?>">
            <?php foreach ($teams as $team) { ?>
                <option value="<?php echo $team['teamID']; ?>"><?php echo $team['name']; ?></option>
            <?php } ?>
        </select>
        <?php
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

    case "getUser":
        $userID = $_REQUEST['id'];
        $user = $engine->getUser($userID);
        if (isset($user)) {
            echo $user['name'] . "|" . $user['login'] . "|" . $user['email'] . "|" . $user['status'] . "|" . $user['userTeamID'];
        }
        break;

    default:
        break;
}
