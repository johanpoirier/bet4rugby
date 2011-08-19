<?
session_start();

header("Content-Type: text/html; charset=utf-8");
define('WEB_PATH', "/");
define('BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . "/");
define('URL_PATH', "/");

require('include/classes/Engine.php');

$debug = false;
$engine = new Engine(false, $debug);

define('LOGIN', (isset($_GET['op']) && ($_GET['op']) == "login"));
define('REGISTER', (isset($_GET['op']) && ($_GET['op']) == "register"));
define('FORGOT_IDS', (isset($_GET['op']) && ($_GET['op']) == "forgot_ids"));
define('AUTHENTIFICATION_NEEDED', (!isset($_SESSION['userID']) && !LOGIN && !REGISTER && !FORGOT_IDS));
define('PHASE_ID_ACTIVE', $engine->getPhaseIDActive());

$op = "";
$pageToInclude = "";
global $message;

if (FORGOT_IDS) {
    if (isset($_POST['email'])) {
        $res = $engine->sendIDs($_POST['email']);
        redirect("/?message=" . $res);
    } else {
        $pageToInclude = "pages/forgot_ids.php";
    }
}

else if (REGISTER) {
    if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['login'])) {
        if ($_POST['password1'] != $_POST['password2']) {
            redirect("/?op=register&message=" . PASSWORD_MISMATCH);
        }
        $status = $engine->addUser($_POST['login'], $_POST['password1'], $_POST['name'], $_POST['firstname'], $_POST['email'], -1, 0);

        if ($status < 0) {
            redirect("/?op=register&c=" . $_POST['code'] . "&message=" . $status);
        } else {
            redirect("/?message=" . REGISTER_OK);
        }
    }
    if (isset($_GET['message']) && $_GET['message']) {
        $message = $engine->lang['messages'][$_GET['message']];
    }
    $pageToInclude = "pages/register.php";
}

else if (AUTHENTIFICATION_NEEDED) {
    if (isset($_GET['message']) && $_GET['message']) {
        $message = $engine->lang['messages'][$_GET['message']];
    }
    $pageToInclude = "pages/login.php";
}

else {
    if (isset($_REQUEST['op'])) {
        $op = $_REQUEST['op'];
    }
    switch ($op) {
        case "login":
            if ($engine->login($_POST['login'], $_POST['pass'])) {
                $pageToInclude = "pages/ranking.php";
            } else {
                $pageToInclude = "pages/login.php";
            }
            break;

        case "logout":
            session_destroy();
            redirect("/");
            break;

        case "my_profile":
            if (isset($_GET['message']) && $_GET['message']) {
                $message = $engine->lang['messages'][$_GET['message']];
            }
            $pageToInclude = "pages/my_profile.php";
            break;

        case "update_profile":
            $message = "";
            $pwd = "";
            if((strlen($_POST['pwd1']) > 0)) {
                if($_POST['pwd1'] == $_POST['pwd2'])
                    $pwd = $_POST['pwd1'];
                else {
                    redirect("/?op=my_profile&message=" . PASSWORD_MISMATCH);
                }
            }
            if(!$engine->updateProfile($_SESSION['userID'], $_POST['name'], $_POST['email'], $_POST['pwd1'])) {
                redirect("/?op=my_profile&message=" . UNKNOWN_ERROR);
            }
            redirect("/?op=my_profile&message=" . CHANGE_ACCOUNT_OK);
            break;

        case "view_ranking":
            $pageToInclude = "pages/ranking.php";
            break;

        case "view_ranking_teams":
            $pageToInclude = "pages/ranking_teams.php";
            break;

        case "view_ranking_users_in_team":
            $pageToInclude = "pages/ranking_users_in_team.php";
            break;

        case "update_ranking":
            $engine->updateRanking();
            $engine->updateUserTeamRanking();
            $pageToInclude = "pages/ranking.php";
            break;

        case "edit_matchs":
            $pageToInclude = "pages/admin/edit_matchs.php";
            break;

        case "edit_results":
            $pageToInclude = "pages/admin/edit_results.php";
            break;

        case "view_results":
            $pageToInclude = "pages/view_results.php";
            break;

        case "add_match":
            $engine->addMatch($_POST['phase'], $_POST['pool'], $_POST['day'], $_POST['month'], $_POST['hour'], $_POST['minutes'], $_POST['teamA'], $_POST['teamB']);
            $pageToInclude = "pages/admin/edit_matchs.php";
            break;

        case "edit_pronos":
            $pageToInclude = "pages/edit_pronos.php";
            break;

        case "edit_pf":
            $phase = $engine->getPhase(PHASE_ID_ACTIVE);
            if ($phase['phasePrecedente'] == NULL) {
                redirect("/?op=edit_pronos");
            }
            $pageToInclude = "pages/edit_pf.php";
            break;

        case "save_pronos":
            $userId = $_REQUEST['userId'];
            foreach ($_POST as $input => $score) {
                $ipt = strtok($input, "_");
                if ($ipt == "iptScoreTeam") {
                    $team = strtok("_");
                    $matchID = strtok("_");
                    if (!$engine->isDatePassed($matchID)) {
                        $engine->saveProno($userId, $matchID, $team, $score);
                    } else {
                        $user = $engine->getUser($userId);
                        if (($_SESSION['status'] == 1) && ($userId != $_SESSION['userID'])) {
                            $engine->saveProno($userId, $matchID, $team, $score);
                        }
                    }
                }
            }
            $pageToInclude = "pages/edit_pronos.php";
            break;

        case "save_pf":
            $userId = $_REQUEST['userId'];
            foreach ($_POST as $input => $score) {
                $ipt = strtok($input, "_");
                if ($ipt == "iptScoreTeam") {
                    $team = strtok("_");
                    $matchID = strtok("_");
                    if (isset($_POST["iptPny_" . $matchID])) {
                        $pny = $_POST["iptPny_" . $matchID];
                        $engine->saveProno($userId, $matchID, $team, $score, $pny);
                    } else {
                        $engine->saveProno($userId, $matchID, $team, $score);
                    }
                }
            }
            $pageToInclude = "pages/edit_pf.php";
            break;

        case "save_results":
            foreach ($_POST as $input => $score) {
                $ipt = strtok($input, "_");
                if ($ipt == "iptScoreTeam") {
                    $team = strtok("_");
                    $matchID = strtok("_");
                    $bonus = $_POST["sltBonus_" . $team . "_" . $matchID];
                    $pny = '';
                    if (isset($_POST["iptPny_" . $matchID]))
                        $pny = $_POST["iptPny_" . $matchID];
                    $engine->saveResult($matchID, $team, $score, $bonus, $pny);
                }
            }
            $pageToInclude = "pages/admin/edit_results.php";
            break;

        case "rules":
            $pageToInclude = "pages/rules.php";
            break;

        case "edit_users":
            $pageToInclude = "pages/admin/users.php";
            break;

        case "add_user":
            $name = $_POST['name'];
            $login = $_POST['login'];
            $pass = $_POST['pass'];
            $userTeamId = $_POST['sltUserTeam'];
            $isAdmin = 0;
            if (isset($_POST['admin']))
                $isAdmin = $_POST['admin'];
            $engine->addUser($name, $login, $pass, $userTeamId, $isAdmin);

            $pageToInclude = "pages/admin/users.php";
            break;

        default:
            if (AUTHENTIFICATION_NEEDED) {
                $pageToInclude = "pages/login.php";
            }
            else {
                $pageToInclude = "pages/ranking.php";
            }
            break;
    }
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">
    <head>
        <title><? echo $engine->config['title']; ?></title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <link type="text/css" rel="stylesheet" href="include/theme/<? echo $engine->config['template']; ?>/pc.css" />
        <script type="text/javascript" src="/js/jquery.js"> </script>
        <script type="text/javascript" src="/js/main.js"> </script>
    </head>
    <body>
        <div id="main">
            <? include_once("include/theme/" . $engine->config['template'] . "/header.php"); ?>
            <? if (!$engine->admin) { ?>
                <div id="nav_area">
                    <img src="include/theme/<? echo $engine->config['template']; ?>/images/user_bar.png" usemap="#testbar5" border="0" alt="Menu" />
                    <map name="testbar5" id="testbar5">
                        <area shape="rect" coords="12,4,184,30" href="/?op=view_ranking" alt="Classement" />
                        <area shape="rect" coords="184,4,376,30" href="/?op=edit_pronos" alt="Mes pronostics" />
                        <area shape="rect" coords="376,4,568,30" href="/?op=edit_pf" alt="Ma phase finale" />
                        <area shape="rect" coords="500,4,742,30" href="/?op=view_results" alt="Résultats" />
                    </map>
                </div>
                <?
            } else {
                ?>
                <div id="nav_area">
                    <img src="include/theme/<? echo $engine->config['template']; ?>/images/admin_bar.png" usemap="#testbar5" border="0" alt="Menu" />
                    <map name="testbar5" id="testbar5">
                        <area shape="rect" coords="12,4,115,30" href="/?op=view_ranking" alt="Classement" />
                        <area shape="rect" coords="117,4,220,17" href="/?op=edit_pronos" alt="Mes pronostics" />
                        <area shape="rect" coords="117,17,220,30" href="/?op=edit_pf" alt="Ma phase finale" />
                        <area shape="rect" coords="222,4,325,30" href="/?op=view_results" alt="Résultats"  />
                        <area shape="rect" coords="327,4,430,30" href="/?op=edit_users" alt="" />
                        <area shape="rect" coords="432,4,535,30" href="/?op=edit_results" alt="" />
                        <area shape="rect" coords="537,4,640,30" href="/?op=edit_matchs" alt="" />
                        <area shape="rect" coords="642,4,744,30" href="/?op=edit_teams" alt="" />
                    </map>
                </div>
            <? } ?>
            <div id="mainarea">
                <? if (strlen($pageToInclude) > 0)
                    include_once($pageToInclude); ?>
            </div>
            <?
            if (isset($_SESSION["userID"])) {
                include_once("include/theme/" . $engine->config['template'] . "/footer_private.php");
            } else {
                include_once("include/theme/" . $engine->config['template'] . "/footer_public.php");
            }
            ?>
        </div>
    </body>
</html>
