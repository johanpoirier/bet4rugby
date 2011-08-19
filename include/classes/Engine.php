<?
include(BASE_PATH . 'include/misc/config.inc.php');
include(BASE_PATH . 'lang/' . $config['lang'] . '.inc.php');
include(BASE_PATH . 'include/misc/define.inc.php');
include(BASE_PATH . 'include/misc/functions.inc.php');
include(BASE_PATH . 'include/misc/db.php');

class Engine {

    var $db;
    var $debug;
    var $config;
    var $template;
    var $start_time;

    /*     * *************** */
    /*  CONTRUCTOR	  */
    /*     * *************** */

    function Engine($admin=false, $debug=false) {
        global $config;

        $time = time();
        $this->start_time = get_moment();
        $this->db = new DB();
        $this->db->set_debug($debug);
        $this->debug = $debug;
        $this->config = $config;
        $this->step = 0;
        $this->theme_location = '/include/theme/' . $config['template'] . "/";

        $this->admin = (isset($_SESSION['status']) && $_SESSION['status'] == 1) ? true : false;
    }

    function register($login, $pass) {
        // Main Query
        $req = "SELECT *";
        $req .= " FROM " . $this->config['db_prefix'] . "users ";
        $req .= " WHERE login = '" . $login . "'";
        $req .= " AND password = '" . $pass . "'";
        $req .= " AND status >= 0";

        $user = $this->db->select_line($req, $nb_user);

        if ($nb_user == 1) {
            $_SESSION['username'] = $user['name'];
            $_SESSION['nom_joueur'] = $user['name'];
            $_SESSION['login'] = $user['login'];
            $_SESSION['userID'] = $user['userID'];
            $_SESSION['status'] = $user['status'];
            if ($user['status'] == 1)
                $this->admin = true;
            return true;
        }
        else
            return false;
    }

    /*     * *************** */
    /*     GETTERS	  */
    /*     * *************** */

    function getSettingDate($name) {
        // Main Query
        $req = "SELECT date";
        $req .= " FROM " . $this->config['db_prefix'] . "settings s";
        $req .= " WHERE s.name = '" . $name . "'";

        $myDate = $this->db->select_one($req);
        if ($this->debug)
            echo $myDate;

        return explode_datetime($myDate);
    }

    function getSettingValue($name) {
        // Main Query
        $req = "SELECT value";
        $req .= " FROM " . $this->config['db_prefix'] . "settings s";
        $req .= " WHERE s.name = '" . $name . "'";

        $myValue = $this->db->select_one($req);
        if ($this->debug)
            echo $myValue;

        return $myValue;
    }

    function getNbPlayers() {
        // Main Query
        $req = "SELECT count(DISTINCT u.userID)";
        $req .= " FROM " . $this->config['db_prefix'] . "users u";
        $req .= " WHERE u.status >= 0";

        $nb_users = $this->db->select_one($req);

        return $nb_users;
    }

    function getNbActivePlayers() {
        // Main Query
        $req = "SELECT count(DISTINCT u.userID)";
        $req .= " FROM " . $this->config['db_prefix'] . "users u";
        $req .= " RIGHT JOIN " . $this->config['db_prefix'] . "pronos AS p ON(p.userID = u.userID)";
        $req .= " WHERE u.status >= 0";

        $nb_users = $this->db->select_one($req);

        return $nb_users;
    }

    function getNbMatchsPlayedByPhase($phaseID) {
        // Main Query
        $req = "SELECT count(DISTINCT m.matchID)";
        $req .= " FROM " . $this->config['db_prefix'] . "matchs m";
        $req .= " WHERE m.phaseID = " . $phaseID;
        $req .= " AND m.scoreA IS NOT NULL AND scoreB IS NOT NULL";

        $nb_matchs = $this->db->select_one($req);

        return $nb_matchs;
    }

    function getNbMatchsPlayed() {
        // Main Query
        $req = "SELECT count(DISTINCT m.matchID)";
        $req .= " FROM " . $this->config['db_prefix'] . "matchs m";
        $req .= " WHERE m.scoreA IS NOT NULL AND scoreB IS NOT NULL";

        $nb_matchs = $this->db->select_one($req);

        return $nb_matchs;
    }

    function getMonths() {
        global $lang;

        // Main Query
        $req = "SELECT date";
        $req .= " FROM " . $this->config['db_prefix'] . "settings s";
        $req .= " WHERE s.name = 'DATE_DEBUT' OR s.name = 'DATE_FIN'";
        $req .= " ORDER BY s.date";

        $months = array();
        $dates = $this->db->select_array($req, $nb_res);
        $i = 0;
        foreach ($dates as $dateBdd) {
            $date = explode_datetime($dateBdd['date']);
            $months[$i] = array($date['month'], $lang['months'][$date['month'] - 1]);
            $i++;
        }

        return $months;
    }

    function getMatch($matchID) {
        // Main Query
        $req = "SELECT *";
        $req .= " FROM " . $this->config['db_prefix'] . "matchs m";
        $req .= " WHERE matchID = $matchID";

        $match = $this->db->select_line($req, $nb_teams);

        return $match;
    }

    function getNextMatchs() {
        // Main Query
        $req = "SELECT *, tA.teamID as teamAid, tB.teamID as teamBid, tA.name as teamAname, tB.name as teamBname, DATE_FORMAT(date, 'le %e/%m à %Hh') as date_str, TIME_TO_SEC(TIMEDIFF(date, NOW())) as delay_sec";
        $req .= " FROM " . $this->config['db_prefix'] . "matchs m ";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "teams tA ON (m.teamA = tA.teamID)";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "teams tB ON (m.teamB = tB.teamID)";
        $req .= " WHERE date = (";
        $req .= " SELECT MIN(date) ";
        $req .= " FROM " . $this->config['db_prefix'] . "matchs m ";
        $req .= " WHERE date > NOW()";
        $req .= ")";

        $matchs = $this->db->select_array($req, $nb_matchs);

        if ($this->debug)
            array_show($matchs);

        return $matchs;
    }

    function getMatchByTeamsAndPhase($teamAid, $teamBid, $phase) {
        // Main Query
        $req = "SELECT *";
        $req .= " FROM " . $this->config['db_prefix'] . "matchs m";
        $req .= " WHERE teamA = $teamAid AND teamB = $teamBid AND phaseID = $phase";

        $match = $this->db->select_line($req, $nb_teams);

        return $match;
    }

    function getPoolsByPhase($phaseID=false) {
        // Main Query
        $req = "SELECT *";
        $req .= " FROM " . $this->config['db_prefix'] . "pools p";
        if ($phaseID)
            $req .= " WHERE phaseID = " . $phaseID;
        $req .= " ORDER BY name ASC";

        $pools = $this->db->select_array($req, $nb_pools);
        if ($this->debug)
            array_show($pools);

        return $pools;
    }

    function getPhase($id) {
        // Main Query
        $req = "SELECT *";
        $req .= " FROM " . $this->config['db_prefix'] . "phases p";
        $req .= " WHERE p.phaseID = " . $id;

        $phase = $this->db->select_line($req, $nb_lines);

        return $phase;
    }

    function getPhasesPlayed() {
        // Main Query
        $req = "SELECT p.phaseID, p.name, count(m.matchID) as nbMatchs";
        $req .= " FROM " . $this->config['db_prefix'] . "phases p";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "matchs m ON(m.phaseID = p.phaseID)";
        $req .= " GROUP BY (p.phaseID) HAVING nbMatchs > 0";

        $phases = $this->db->select_array($req, $nb_phases);
        if ($this->debug)
            array_show($phases);

        return $phases;
    }

    function getPhaseByPhaseRoot($id) {
        // Main Query
        $req = "SELECT *, count(m.matchID) as nbMatchs";
        $req .= " FROM " . $this->config['db_prefix'] . "phases p";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "matchs m ON(m.phaseID = p.phaseID)";
        $req .= " WHERE p.phasePrecedente = " . $id;
        $req .= " GROUP BY (p.phaseID) HAVING nbMatchs > 0";

        $phases = $this->db->select_array($req, $nb_phases);
        if ($this->debug)
            array_show($phases);

        return $phases;
    }

    function getFinalPhasesPlayed() {
        // Main Query
        $req = "SELECT p.phaseID, p.name, count(m.matchID) as nbMatchs";
        $req .= " FROM " . $this->config['db_prefix'] . "phases p";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "matchs m ON(m.phaseID = p.phaseID)";
        $req .= " WHERE p.phasePrecedente IS NOT NULL";
        $req .= " GROUP BY (p.phaseID) HAVING nbMatchs > 0";
        $req .= " ORDER BY p.phaseID DESC";

        $phases = $this->db->select_array($req, $nb_phases);
        if ($this->debug)
            array_show($phases);

        return $phases;
    }

    function getFinalPhases() {
        // Main Query
        $req = "SELECT DISTINCT phasePrecedente";
        $req .= " FROM " . $this->config['db_prefix'] . "phases p";
        $idsPre = $this->db->select_col($req, $nb_cols);

        $finalPhases = array();
        $phases = $this->getPhases();
        foreach ($phases as $phase) {
            if (!in_array($phase['phaseID'], $idsPre)) {
                $req = "SELECT *";
                $req .= " FROM " . $this->config['db_prefix'] . "phases p";
                $req .= " WHERE p.phaseID = " . $phase['phaseID'];
                $phase = $this->db->select_array($req, $nb_phases);
                foreach ($phase as $maPhase) {
                    $finalPhases[] = $maPhase;
                }
            }
        }

        return $finalPhases;
    }

    function getPhases() {
        // Main Query
        $req = "SELECT *";
        $req .= " FROM " . $this->config['db_prefix'] . "phases p";
        $req .= " ORDER BY phaseID ASC";

        $phases = $this->db->select_array($req, $nb_teams);
        if ($this->debug)
            array_show($phases);

        return $phases;
    }

    function getUsers() {
        // Main Query
        $req = "SELECT u.userID, u.name, u.login, u.points, u.nbresults, u.nbscores, u.diff, u.last_rank, u.userTeamID, t.name AS team, count(p.userID) AS nbpronos";
        $req .= " FROM " . $this->config['db_prefix'] . "users u";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "pronos AS p ON(p.userID = u.userID)";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "user_teams AS t ON(t.userTeamID = u.userTeamID)";
        $req .= " WHERE (p.scoreA IS NOT null) AND (p.scoreB IS NOT null) AND u.status >= 0";
        $req .= " GROUP BY p.userID";
        $req .= " ORDER BY u.name ASC";

        $users = $this->db->select_array($req, $nb_teams);
        if ($this->debug)
            array_show($users);

        return $users;
    }

    function getUsersByUserTeam($userTeamID, $all_users=false) {
        // Main Query
        $req = "SELECT DISTINCT(u.userID), u.name, u.login, u.points, u.nbresults, u.nbscores, u.diff, u.last_rank, t.name AS team";
        if (!$all_users)
            $req .= ", COUNT(p.userID) AS nbpronos";
        $req .= " FROM " . $this->config['db_prefix'] . "users u";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "pronos AS p ON(p.userID = u.userID)";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "user_teams AS t ON(t.userTeamID = u.userTeamID)";
        $req .= " WHERE u.userTeamID = " . $userTeamID;
        if (!$all_users) {
            $req .= " AND (p.scoreA IS NOT null) AND (p.scoreB IS NOT null) AND u.status >= 0";
            $req .= " GROUP BY p.userID";
        }
        $req .= " ORDER BY u.name ASC";

        $users = $this->db->select_array($req, $nb_teams);
        if ($this->debug)
            array_show($users);

        return $users;
    }

    function getNbUsersByUserTeam($userTeamID) {
        // Main Query
        $req = "SELECT COUNT(u.userID)";
        $req .= " FROM " . $this->config['db_prefix'] . "users u";
        $req .= " WHERE u.userTeamID = " . $userTeamID . " AND u.status >= 0";

        $nb_users = $this->db->select_one($req);
        if ($this->debug)
            echo $nb_users;

        return $nb_users;
    }

    function getUser($id) {
        // Main Query
        $req = "SELECT u.userID, u.name, u.login, u.points, u.nbresults, u.nbscores, u.diff, u.last_rank, u.status, u.userTeamID, t.name AS team";
        $req .= " FROM " . $this->config['db_prefix'] . "users u";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "user_teams AS t ON(t.userTeamID = u.userTeamID)";
        $req .= " WHERE u.userId = " . $id;

        $user = $this->db->select_line($req, $nb_teams);

        return $user;
    }

    function getUserByEMail($email) {
        // Main Query
        $req = "SELECT u.userID, u.name, u.login, u.points, u.nbresults, u.nbscores, u.diff, u.last_rank, u.status, u.userTeamID, t.name AS team";
        $req .= " FROM " . $this->config['db_prefix'] . "users u";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "user_teams AS t ON(t.userTeamID = u.userTeamID)";
        $req .= " WHERE u.email = '" . $email ."'";

        $user = $this->db->select_line($req, $nb_teams);

        return $user;
    }

    function getUserTeams() {
        // Main Query
        $req = "SELECT *";
        $req .= " FROM " . $this->config['db_prefix'] . "user_teams AS ut";
        $req .= " ORDER BY ut.name ASC";

        $userTeams = $this->db->select_array($req, $nb_teams);
        if ($this->debug)
            array_show($userTeams);

        return $userTeams;
    }

    function getNextPronosByUser($userID) {
        // Main Query
        $req = "SELECT DISTINCT m.matchID, t1.name as teamAname, t2.name as teamBname, m.scoreA as scoreMatchA, m.scoreB as scoreMatchB, DATE_FORMAT(date, 'le %e/%m à %Hh') as date_str, TIME_TO_SEC(TIMEDIFF(m.date, NOW())) as delay_sec";
        $req .= " FROM " . $this->config['db_prefix'] . "matchs m";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "pronos p ON(p.matchID = m.matchID)";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "teams AS t1 ON(m.teamA = t1.teamID)";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "teams AS t2 ON(m.teamB = t2.teamID)";
        $req .= " WHERE m.matchID NOT IN (SELECT p.matchID FROM " . $this->config['db_prefix'] . "pronos p WHERE p.userID = " . $userID . " AND p.scoreA IS NOT NULL AND p.scoreB IS NOT NULL) AND m.date > NOW()";
        $req .= " ORDER BY m.date ASC";

        $pronos = $this->db->select_array($req, $nb_pronos);

        if ($this->debug)
            array_show($pronos);

        return $pronos;
    }

    function getMatchsByPool($poolID) {
        // Main Query
        $req = "SELECT m.matchID, DATE_FORMAT(m.date,'le %e/%m à %Hh%i') as dateStr, t1.teamID AS teamAid, t1.name AS teamAname, t2.teamID AS teamBid, t2.name AS teamBname, p.name as teamPool";
        $req .= ", m.scoreA as scoreMatchA, m.scoreB as scoreMatchB, m.pnyA as pnyMatchA, m.pnyB as pnyMatchB, m.bonusA, m.bonusB";
        $req .= " FROM " . $this->config['db_prefix'] . "matchs m";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "teams AS t1 ON(m.teamA = t1.teamID)";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "teams AS t2 ON(m.teamB = t2.teamID)";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "pools AS p ON(t1.poolID = p.poolID)";
        $req .= " WHERE t1.poolID = " . $poolID . " and t2.poolID = " . $poolID;
        $req .= " ORDER BY m.date ASC";

        $matchs = $this->db->select_array($req, $nb_teams);
        if ($this->debug)
            array_show($matchs);

        return $matchs;
    }

    function getMatchsByTeamAndPhase($teamID, $phaseID=1) {
        // Main Query
        $req = "SELECT m.matchID, DATE_FORMAT(m.date,'le %e/%m à %Hh%i') as dateStr, t1.teamID AS teamAid, t1.name AS teamAname, t2.teamID AS teamBid, t2.name AS teamBname";
        $req .= ", m.scoreA as scoreMatchA, m.scoreB as scoreMatchB, m.pnyA as pnyMatchA, m.pnyB as pnyMatchB, m.bonusA, m.bonusB";
        $req .= " FROM " . $this->config['db_prefix'] . "matchs m";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "teams AS t1 ON(m.teamA = t1.teamID)";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "teams AS t2 ON(m.teamB = t2.teamID)";
        $req .= " WHERE (t1.teamID = " . $teamID . " OR t2.teamID = " . $teamID . ") AND m.phaseID = " . $phaseID;

        $matchs = $this->db->select_array($req, $nb_teams);
        if ($this->debug)
            array_show($matchs);

        return $matchs;
    }

    function getMatchsByPhase($phase) {
        // Main Query
        $req = "SELECT m.matchID, DATE_FORMAT(m.date,'le %e/%m à %Hh%i') as dateStr, t1.teamID AS teamAid, t1.name AS teamAname, t2.teamID AS teamBid, t2.name AS teamBname";
        $req .= ", m.scoreA as scoreMatchA, m.scoreB as scoreMatchB, m.pnyA as pnyMatchA, m.pnyB as pnyMatchB, m.bonusA, m.bonusB";
        $req .= " FROM " . $this->config['db_prefix'] . "matchs m";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "teams AS t1 ON(m.teamA = t1.teamID)";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "teams AS t2 ON(m.teamB = t2.teamID)";
        $req .= " WHERE phaseID=" . $phase;
        $req .= " ORDER BY m.date ASC";

        $matchs = $this->db->select_array($req, $nb_teams);
        if ($this->debug)
            array_show($matchs);

        return $matchs;
    }

    function getMatchs() {
        // Main Query
        $req = "SELECT m.matchID, DATE_FORMAT(m.date,'le %e/%m à %Hh%i') as dateStr, tA.teamID as teamAid, tB.teamID as teamBid, tA.name as teamAname, tB.name as teamBname, p.name as teamPool, m.phaseID";
        $req .= ", m.scoreA as scoreMatchA, m.pnyA as pnyMatchA, m.bonusA, m.scoreB as scoreMatchB, m.pnyB as pnyMatchB, m.bonusB";
        $req .= " FROM " . $this->config['db_prefix'] . "matchs m ";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "teams tA ON (m.teamA = tA.teamID)";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "teams tB ON (m.teamB = tB.teamID)";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "pools AS p ON(tA.poolID = p.poolID)";
        $req .= " ORDER BY m.date, teamPool";

        $matchsBdd = $this->db->select_array($req, $nb_teams);
        foreach ($matchsBdd as $match) {
            $colorA = "transparent";
            $colorB = "transparent";
            if ($match['scoreMatchA'] > $match['scoreMatchB'])
                $colorA = "#99FF99";
            else if ($match['scoreMatchA'] < $match['scoreMatchB'])
                $colorB = "#99FF99";
            else {
                if ($match['pnyMatchA'] > $match['pnyMatchB'])
                    $colorA = "#99FF99";
                else if ($match['pnyMatchA'] < $match['pnyMatchB'])
                    $colorB = "#99FF99";
            }
            $match['COLOR_A'] = $colorA;
            $match['COLOR_B'] = $colorB;
            $matchs[] = $match;
        }
        if ($this->debug)
            array_show($matchs);

        return $matchs;
    }

    function getTeam($teamID) {
        // Main Query
        $req = "SELECT *";
        $req .= " FROM " . $this->config['db_prefix'] . "teams t";
        $req .= " WHERE t.teamID = " . $teamID;

        $team = $this->db->select_line($req, $null);
        if ($this->debug)
            array_show($team);

        return $team;
    }

    function getTeamsByPool($poolID) {
        // Main Query
        $req = "SELECT *";
        $req .= " FROM " . $this->config['db_prefix'] . "teams t";
        $req .= " WHERE t.poolID = " . $poolID;
        $req .= " ORDER BY teamID ASC";

        $teams = $this->db->select_array($req, $nb_teams);
        if ($this->debug)
            array_show($teams);

        return $teams;
    }

    function getPointsRules() {
        // Main Query
        $req = "SELECT phaseID, nbPointsRes, nbPointsScoreNiv1, nbPointsScoreNiv2, nbPointsEcartNiv1, nbPointsEcartNiv2";
        $req .= " FROM " . $this->config['db_prefix'] . "phases";
        $req .= " ORDER BY phaseID ASC";

        $phases = $this->db->select_array($req, $nb_teams);
        $rules = array();
        foreach ($phases as $phase) {
            $rules[$phase['phaseID']] = array($phase['nbPointsRes'], $phase['nbPointsScoreNiv1'], $phase['nbPointsScoreNiv2'], $phase['nbPointsEcartNiv1'], $phase['nbPointsEcartNiv2']);
        }
        if ($this->debug)
            array_show($rules);

        return $rules;
    }

    function getQualifiedTeamsByPhase($phase, $type='Match', $userID=0) {
        $teamsQualified = array();
        if ($phase['phasePrecedente'] != NULL) {
            $phasePrecedente = $this->getPhase($phase['phasePrecedente']);
            $pools = $this->getPoolsByPhase($phasePrecedente['phaseID']);
            if (sizeof($pools) > 0) {
                foreach ($pools as $pool) {
                    if ($type == 'Match')
                        $matchs = $this->getMatchsByPool($pool['poolID']);
                    elseif ($type == 'Prono')
                        $matchs = $this->getPronosByUserAndPool($userID, $pool['poolID'], $phasePrecedente['phaseID']);
                    else
                        $matchs = array();
                    $teams = $this->getTeamsByPool($pool['poolID']);
                    $array_teams = $this->getRanking($teams, $matchs, 'score' . $type);
                    for ($i = 0; $i < $phasePrecedente['nb_qualifies']; $i++) {
                        $teamsQualified[] = $array_teams[$i];
                    }
                }
            } else {
                $indic = $phase['nb_qualifies'];
                if ($indic > 0)
                    $indic = 1;
                if ($type == 'Match')
                    $matchs = $this->getMatchsByPhase($phasePrecedente['phaseID']);
                elseif ($type == 'Prono')
                    $matchs = $this->getPronosByUserAndPhase($userID, $phasePrecedente['phaseID']);
                else
                    $matchs = array();
                foreach ($matchs as $match) {
                    $idTeam = 0;
                    if (($indic * $match['score' . $type . 'A']) > ($indic * $match['score' . $type . 'B']))
                        $idTeam = $match['teamAid'];
                    elseif (($indic * $match['score' . $type . 'A']) < ($indic * $match['score' . $type . 'B']))
                        $idTeam = $match['teamBid'];
                    else {
                        if (($indic * $match['pny' . $type . 'A']) > ($indic * $match['pny' . $type . 'B']))
                            $idTeam = $match['teamAid'];
                        else
                            $idTeam = $match['teamBid'];
                    }
                    $teamsQualified[] = $this->getTeam($idTeam);
                }
            }
        }
        else {
            $teamsQualified = $this->getTeamsByPhase($phaseID);
        }
        return $teamsQualified;
    }

    function getTeamsByPhase($phaseID) {
        $req = "SELECT DISTINCT t.teamID, t.name";
        $req .= " FROM " . $this->config['db_prefix'] . "matchs m";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "teams AS t ON(m.teamA = t.teamID)";
        $req .= " WHERE m.phaseID = " . $phaseID;

        $teams = $this->db->select_array($req, $nb_teams);
        if ($this->debug)
            array_show($teams);

        return $teams;
    }

    function getPronosByUserAndPool($userID, $poolID=false, $phaseID=1, $mode=0) {
        // Main Query
        $req = "SELECT m.matchID, DATE_FORMAT(m.date,'le %e/%m à %Hh%i') as dateStr, t1.teamID AS teamAid, t1.name AS teamAname, m.scoreA as scoreMatchA, m.pnyA as pnyMatchA, m.scoreB as scoreMatchB, m.pnyB as pnyMatchB, b.scoreA as scorePronoA, b.pnyA as pnyPronoA, t2.teamID AS teamBid, t2.name AS teamBname, b.scoreB as scorePronoB, b.pnyB as pnyPronoB, p.name as teamPool, m.phaseID";
        $req .= " FROM " . $this->config['db_prefix'] . "matchs m";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "pronos AS b ON((b.matchID = m.matchID) AND (b.userID = " . $userID . "))";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "teams AS t1 ON(m.teamA = t1.teamID)";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "teams AS t2 ON(m.teamB = t2.teamID)";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "pools AS p ON(t1.poolID = p.poolID)";
        $req .= " WHERE m.phaseID=" . $phaseID;
        if ($poolID)
            $req .= " AND t1.poolID = " . $poolID . " AND t2.poolID = " . $poolID;
        if ($mode == 1)
            $req .= " AND m.date < NOW()";
        $req .= " ORDER BY m.date, teamPool";

        // Phase
        $phase = $this->getPhase($phaseID);
        $phasePoints = $this->getPointsRules();
        $pronos = array();
        $pronosBdd = $this->db->select_array($req, $nb_pronos);
        foreach ($pronosBdd as $prono) {
            // colors
            $colorA = "transparent";
            $colorB = "transparent";
            if ($prono['scorePronoA'] > $prono['scorePronoB'])
                $colorA = "#99FF99";
            else if ($prono['scorePronoA'] < $prono['scorePronoB'])
                $colorB = "#99FF99";
            else {
                if ($prono['pnyPronoA'] > $prono['pnyPronoB'])
                    $colorA = "#99FF99";
                else if ($prono['pnyPronoA'] < $prono['pnyPronoB'])
                    $colorB = "#99FF99";
            }
            $prono['COLOR_A'] = $colorA;
            $prono['COLOR_B'] = $colorB;

            //
            $resProno = $this->computeNbPtsProno($phase, $prono['scoreMatchA'], $prono['scoreMatchB'], $prono['pnyMatchA'], $prono['pnyMatchB'], $prono['scorePronoA'], $prono['scorePronoB'], $prono['pnyPronoA'], $prono['pnyPronoB']);
            if ($resProno['points'] >= ($phase['nbPointsRes'] + ($phase['nbPointsScoreNiv2'] * 2) + $phase['nbPointsEcartNiv1'])) {
                $color = "green";
                $points = "+" . $resProno['points'] . "pts";
            } elseif ($resProno['points'] >= $phase['nbPointsRes']) {
                $color = "black";
                $points = "+" . $resProno['points'] . "pts";
            } else {
                $color = "red";
                $points = $resProno['points'] . "pt";
            }
            $diff = "(" . $resProno['diff'] . ")";

            if (($prono['scoreMatchA'] != NULL) && ($prono['scoreMatchB'] != NULL)) {
                $prono['POINTS'] = $points;
                $prono['COLOR'] = $color;
                $prono['DIFF'] = $diff;
            }

            if ($phase['aller_retour'] == 1) {
                // match aller
                $matchAller = $this->getMatchByTeamsAndPhase($prono['teamBid'], $prono['teamAid'], $prono['phaseID']);
                if ($matchAller != NULL) {
                    $prono['SCORE_ALLER_A'] = $matchAller['scoreA'];
                    $prono['SCORE_ALLER_B'] = $matchAller['scoreB'];
                    if (($prono['scorePronoA'] != $matchAller['scoreA']) || ($prono['scorePronoB'] != $matchAller['scoreB'])) {
                        $prono['pnyPronoA'] = NULL;
                        $prono['pnyPronoB'] = NULL;
                    }
                }
                if (strlen($prono['SCORE_ALLER_A']) == 0)
                    $prono['SCORE_ALLER_A'] = "-1";
                if (strlen($prono['SCORE_ALLER_B']) == 0)
                    $prono['SCORE_ALLER_B'] = "-1";
            }

            // limite de temps
            if (($prono['teamAid'] == NULL) || ($prono['teamBid'] == NULL))
                $disabled = ' disabled="disabled"';
            else {
                if ((($this->getTimeBeforeMatch($prono['matchID']) < 900) || ($mode == 1)) && ($mode != 2))
                    $disabled = ' disabled="disabled"';
                else
                    $disabled = "";
            }
            $prono['DISABLED'] = $disabled;

            $pronos[] = $prono;
        }
        if ($this->debug)
            array_show($pronos);

        return $pronos;
    }

    function getPronosByUserTeamAndPhase($userID, $teamID, $phaseID=1) {
        // Main Query
        $req = "SELECT DISTINCT m.matchID, DATE_FORMAT(m.date,'le %e/%m à %Hh%i') as dateStr, t1.teamID AS teamAid, t1.name AS teamAname, t2.teamID AS teamBid, t2.name AS teamBname";
        $req .= ", p.scoreA as scorePronoA, p.scoreB as scorePronoB, p.pnyA as pnyPronoA, p.pnyB as pnyPronoB";
        $req .= " FROM " . $this->config['db_prefix'] . "pronos p";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "matchs AS m ON(m.matchID = p.matchID)";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "teams AS t1 ON(m.teamA = t1.teamID)";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "teams AS t2 ON(m.teamB = t2.teamID)";
        $req .= " WHERE p.userID = " . $userID . " AND (t1.teamID = " . $teamID . " OR t2.teamID = " . $teamID . ") AND m.phaseID = " . $phaseID;

        $pronos = $this->db->select_array($req, $nb_pronos);
        if ($this->debug)
            array_show($pronos);

        return $pronos;
    }

    function getPronosByUserAndPhase($userID, $phaseID=1) {
        // Main Query
        $req = "SELECT DISTINCT m.matchID, DATE_FORMAT(m.date,'le %e/%m à %Hh%i') as dateStr, t1.teamID AS teamAid, t1.name AS teamAname, t2.teamID AS teamBid, t2.name AS teamBname";
        $req .= ", m.scoreA as scoreMatchA, m.scoreB as scoreMatchB, m.pnyA as pnyMatchA, m.pnyB as pnyMatchB";
        $req .= ", p.scoreA as scorePronoA, p.scoreB as scorePronoB, p.pnyA as pnyPronoA, p.pnyB as pnyPronoB";
        $req .= " FROM " . $this->config['db_prefix'] . "pronos p";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "matchs AS m ON(m.matchID = p.matchID)";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "teams AS t1 ON(m.teamA = t1.teamID)";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "teams AS t2 ON(m.teamB = t2.teamID)";
        $req .= " WHERE p.userID = " . $userID . " AND m.phaseID = " . $phaseID;

        $pronos = $this->db->select_array($req, $nb_pronos);
        if ($this->debug)
            array_show($pronos);

        return $pronos;
    }

    function getResultsByPhase($phase=1, $poolID=false) {
        // Main Query
        $req = "SELECT m.matchID, DATE_FORMAT(m.date,'le %e/%m à %Hh%i') as dateStr, t1.teamID AS teamAid, t1.name AS teamAname, m.scoreA as scoreMatchA, m.pnyA as pnyMatchA, m.scoreB as scoreMatchB, m.pnyB as pnyMatchB, t2.teamID AS teamBid, t2.name AS teamBname, p.name as teamPool";
        $req .= " FROM " . $this->config['db_prefix'] . "matchs m";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "teams AS t1 ON(m.teamA = t1.teamID)";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "teams AS t2 ON(m.teamB = t2.teamID)";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "pools AS p ON(t1.poolID = p.poolID)";
        $req .= " WHERE m.phaseID=" . $phase;
        if ($poolID)
            $req .= " AND t1.poolID = " . $poolID . " AND t2.poolID = " . $poolID;
        $req .= " ORDER BY m.date, teamPool";

        $results = array();
        $resultsBdd = $this->db->select_array($req, $nb_teams);
        foreach ($resultsBdd as $result) {
            // colors
            $colorA = "transparent";
            $colorB = "transparent";
            if ($result['scoreMatchA'] > $result['scoreMatchB'])
                $colorA = "#99FF99";
            else if ($result['scoreMatchA'] < $result['scoreMatchB'])
                $colorB = "#99FF99";
            else {
                if ($result['pnyMatchA'] > $result['pnyMatchB'])
                    $colorA = "#99FF99";
                elseif ($result['pnyMatchA'] < $result['pnyMatchB'])
                    $colorB = "#99FF99";
            }
            $result['COLOR_A'] = $colorA;
            $result['COLOR_B'] = $colorB;

            $results[] = $result;
        }
        if ($this->debug)
            array_show($results);

        return $results;
    }

    function getOddsByMatch($matchID) {
        $req = "SELECT *";
        $req .= " FROM " . $this->config['db_prefix'] . "pronos b";
        $req .= " WHERE matchID = " . $matchID;
        $req .= " AND scoreA IS NOT NULL AND scoreB IS NOT NULL";

        $pronos = $this->db->select_array($req, $nb_bets);

        $odds = array();
        $odds['A_AVG'] = 0;
        $odds['B_AVG'] = 0;
        $odds['A_WINS'] = 0;
        $odds['B_WINS'] = 0;
        $odds['NUL'] = 0;

        foreach ($pronos as $prono) {
            if ($prono['scoreA'] > $prono['scoreB'])
                $odds['A_WINS']++;
            if ($prono['scoreB'] > $prono['scoreA'])
                $odds['B_WINS']++;
            if ($prono['scoreA'] == $prono['scoreB'])
                $odds['NUL']++;
            $odds['A_AVG'] += $prono['scoreA'];
            $odds['B_AVG'] += $prono['scoreB'];
        }
        if ($nb_bets > 0)
            $odds['A_AVG'] = round($odds['A_AVG'] / $nb_bets, 2);
        if ($nb_bets > 0)
            $odds['B_AVG'] = round($odds['B_AVG'] / $nb_bets, 2);
        $odds['A_WINS'] = round(($nb_bets + 1) / ($odds['A_WINS'] + 1), 2);
        $odds['B_WINS'] = round(($nb_bets + 1) / ($odds['B_WINS'] + 1), 2);
        $odds['NUL'] = round(($nb_bets + 1) / ($odds['NUL'] + 1), 2);

        if ($this->debug)
            array_show($odds);

        return $odds;
    }

    function getPronosByMatch($matchID) {
        // Main Query
        $req = "SELECT b.userID, m.matchID";
        $req .= ", m.scoreA as scoreMatchA, m.pnyA as pnyMatchA, m.scoreB as scoreMatchB, m.pnyB as pnyMatchB";
        $req .= ", b.scoreA as scorePronoA, b.pnyA as pnyPronoA, b.scoreB as scorePronoB, b.pnyB as pnyPronoB";
        $req .= ", tA.teamID as teamAid, tB.teamID as teamBid, tA.name as teamAname, tB.name as teamBname";
        $req .= ", p.name as teamPool, DATE_FORMAT(date,'le %e/%m à %Hh%i') as dateStr";
        $req .= " FROM " . $this->config['db_prefix'] . "matchs m ";
        $req .= " RIGHT JOIN " . $this->config['db_prefix'] . "pronos b ON (m.matchID = b.matchID)";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "teams tA ON (m.teamA = tA.teamID)";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "teams tB ON (m.teamB = tB.teamID)";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "pools p ON (tA.poolID= p.poolID)";
        $req .= " WHERE m.matchID = " . $matchID;
        $req .= " ORDER BY m.date, teamAname";

        $pronosBdd = $this->db->select_array($req, $nb_teams);
        $pronos = array();
        foreach ($pronosBdd as $prono) {
            // colors
            $colorA = "transparent";
            $colorB = "transparent";
            if ($prono['scorePronoA'] > $prono['scorePronoB'])
                $colorA = "#99FF99";
            else if ($prono['scorePronoA'] < $prono['scorePronoB'])
                $colorB = "#99FF99";
            $prono['COLOR_A'] = $colorA;
            $prono['COLOR_B'] = $colorB;

            // limite de temps
            if (($prono['teamAid'] == NULL) || ($prono['teamBid'] == NULL))
                $disabled = ' disabled="disabled"';
            else {
                if ($this->getTimeBeforeMatch($prono['matchID']) < 900)
                    $disabled = ' disabled="disabled"';
                else
                    $disabled = "";
            }
            $prono['DISABLED'] = $disabled;

            $pronos[] = $prono;
        }
        if ($this->debug)
            array_show($pronos);

        return $pronos;
    }

    function getUsersRank() {
        $users = $this->getUsers();
        $ranks = array();
        usort($users, "compare_users");
        $i = 1;
        $j = 0;
        $last_user = $users[0];
        foreach ($users as $ID => $user) {
            if ($user['nbpronos'] == 0) {
                $ranks[$user['userID']] = 'NULL';
                continue;
            }
            if (compare_users($user, $last_user) != 0)
                $i = $j + 1;
            $ranks[$user['userID']] = $i;
            $j++;
            $last_user = $user;
        }

        return $ranks;
    }

    function getLastGenerate() {
        // Main Query
        $req = "SELECT date";
        $req .= " FROM " . $this->config['db_prefix'] . "settings";
        $req .= " WHERE name = 'LAST_GENERATE'";

        $last_generate = $this->db->select_one($req, null);

        if ($this->debug)
            echo $last_generate;

        return $last_generate;
    }

    function getHttpTags($start=0) {
        $tags = $this->getTags($start, 10);
        $nb_tags = $this->getNbTags();
        $page = ceil(($start + 1) / 10);
        $max = ceil($nb_tags / 10);
        echo $page . "|" . $max . "|";
        foreach ($tags as $tag) {
            if ($tag['userID'] == $_SESSION['userID'] || $this->admin)
                $del_img = 1;
            else
                $del_img = 0;
            echo $tag['tagID'] . ";" . $tag['date_str'] . ";" . $tag['name'] . ";" . $del_img . ";" . utf8_decode($tag['tag']) . "|";
        }
    }

    function getTags($start=false, $limit=false, $userTeamId=false) {
        // Main Query
        $req = "SELECT *, DATE_FORMAT(date,'%d/%m %kh%i') as date_str";
        $req .= " FROM " . $this->config['db_prefix'] . "tags t ";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "users u ON (u.userID = t.userID)";
        if ($userTeamId)
            $req .= " WHERE t.userTeamID = " . $userTeamId;
        else
            $req .= " WHERE t.userTeamID = -1";
        $req .= " ORDER BY date DESC";
        if ($limit != false)
            $req .= " LIMIT " . $start . "," . $limit . "";

        $tags = $this->db->select_array($req, $nb_teams);

        if ($this->debug)
            array_show($tags);

        return $tags;
    }

    function getNbTags() {
        // Main Query
        $req = "SELECT count(*)";
        $req .= " FROM " . $this->config['db_prefix'] . "tags t ";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "users u ON (u.userID = t.userID)";

        $nb_tags = $this->db->select_one($req);

        if ($this->debug)
            echo($nb_tags);

        return $nb_tags;
    }

    function getTag($tagID) {
        // Main Query
        $req = "SELECT *, DATE_FORMAT(date,'%d/%m %kh%i') as date_str";
        $req .= " FROM " . $this->config['db_prefix'] . "tags t ";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "users u ON (u.userID = t.userID)";
        $req .= " WHERE tagID = " . $tagID;

        $tag = $this->db->select_line($req, $null);

        if ($this->debug)
            array_show($tag);

        return $tag;
    }

    function getRanking($teams, $pronos, $libScore, $userID=false) {
        $nbPtsVictoire = $this->getSettingValue("NB_POINTS_VICTOIRE");
        $nbPtsNul = $this->getSettingValue("NB_POINTS_NUL");

        $array_teams = array();
        foreach ($teams as $team) {
            $team['points'] = 0;
            $team['diff'] = 0;
            if ($libScore == 'scoreMatch')
                $team['matchs'] = $this->getMatchsByTeamAndPhase($team['teamID'], 1);
            else
                $team['matchs'] = $this->getPronosByUserTeamAndPhase($_SESSION['userID'], $team['teamID'], 1);
            $array_teams[$team['teamID']] = $team;
        }

        foreach ($pronos as $prono) {
            if (!isset($array_teams[$prono['teamAid']]['gf']))
                $array_teams[$prono['teamAid']]['gf'] = 0;
            $array_teams[$prono['teamAid']]['gf'] += $prono[$libScore . 'A'];
            if (!isset($array_teams[$prono['teamBid']]['gf']))
                $array_teams[$prono['teamBid']]['gf'] = 0;
            $array_teams[$prono['teamBid']]['gf'] += $prono[$libScore . 'B'];

            if ($prono[$libScore . 'A'] > $prono[$libScore . 'B']) {
                $array_teams[$prono['teamAid']]['points'] += $nbPtsVictoire;
                $array_teams[$prono['teamAid']]['diff'] += ( $prono[$libScore . 'A'] - $prono[$libScore . 'B']);
                $array_teams[$prono['teamBid']]['diff'] -= ( $prono[$libScore . 'A'] - $prono[$libScore . 'B']);
            }
            if ($prono[$libScore . 'A'] < $prono[$libScore . 'B']) {
                $array_teams[$prono['teamBid']]['points'] += $nbPtsVictoire;
                $array_teams[$prono['teamAid']]['diff'] -= ( $prono[$libScore . 'B'] - $prono[$libScore . 'A']);
                $array_teams[$prono['teamBid']]['diff'] += ( $prono[$libScore . 'B'] - $prono[$libScore . 'A']);
            }
            if ($prono[$libScore . 'A'] == $prono[$libScore . 'B'] && ($prono[$libScore . 'A'] != "")) {
                $array_teams[$prono['teamAid']]['points'] += $nbPtsNul;
                $array_teams[$prono['teamBid']]['points'] += $nbPtsNul;
            }

            if ($libScore == 'scoreMatch') {
                $array_teams[$prono['teamAid']]['points'] += $prono['bonusA'];
                $array_teams[$prono['teamBid']]['points'] += $prono['bonusB'];
            } else {
                // Bonus def
                $diffMatch = $prono[$libScore . 'A'] - $prono[$libScore . 'B'];
                if (($diffMatch > 0) && ($diffMatch <= 7))
                    $array_teams[$prono['teamBid']]['points'] += 1;
                elseif (($diffMatch < 0) && ($diffMatch >= -7))
                    $array_teams[$prono['teamAid']]['points'] += 1;

                // Bonus off
                if ($prono[$libScore . 'A'] > 30)
                    $array_teams[$prono['teamAid']]['points'] += 1;
                if ($prono[$libScore . 'B'] > 30)
                    $array_teams[$prono['teamBid']]['points'] += 1;
                /* if($prono['pnyPronoA'] == 1)
                  $array_teams[$prono['teamAid']]['points'] += 1;
                  if($prono['pnyPronoB'] == 1)
                  $array_teams[$prono['teamBid']]['points'] += 1; */
            }
        }

        if ($libScore == 'scoreMatch') {
            usort($array_teams, "compare_teams_1to1");
        } else {
            usort($array_teams, "compare_pronoteams_1to1");
        }

        // Coloration des qualifies
        for ($i = 0; $i < 2; $i++) {
            $array_teams[$i]['style'] = "background-color:#D1DEFF;";
        }

        return $array_teams;
    }

    function getNbTotalMatchs() {
        // Main Query
        $req = "SELECT count(matchID)";
        $req .= " FROM " . $this->config['db_prefix'] . "matchs m";

        $nb_matchs = $this->db->select_one($req);

        if ($this->debug)
            echo($nb_matchs);

        return $nb_matchs;
    }

    function getNbPronosByUser($userID) {
        // Main Query
        $req = "SELECT count(matchID)";
        $req .= " FROM " . $this->config['db_prefix'] . "pronos p";
        $req .= " WHERE p.userID = " . $userID;

        $nb_pronos = $this->db->select_one($req);

        if ($this->debug)
            echo($nb_pronos);

        return $nb_pronos;
    }

    function getNbPlayedPronosByUser($userID) {
        // Main Query
        $req = "SELECT count(p.matchID)";
        $req .= " FROM " . $this->config['db_prefix'] . "pronos p";
        $req .= " LEFT JOIN " . $this->config['db_prefix'] . "matchs m ON(p.matchID = m.matchID)";
        $req .= " WHERE p.userID = " . $userID;
        $req .= " AND (m.scoreA IS NOT NULL) AND (m.scoreB IS NOT NULL)";
        $req .= " AND (p.scoreA IS NOT NULL) AND (p.scoreB IS NOT NULL)";


        $nb_pronos = $this->db->select_one($req);

        if ($this->debug)
            echo($nb_pronos);

        return $nb_pronos;
    }

    /*     * *************** */
    /*     SETTERS	  */
    /*     * *************** */

    function addMatch($phase, $pool, $day, $month, $hour, $minutes, $teamA, $teamB) {
        $date = "2011-" . $month . "-" . $day . " " . $hour . ":" . $minutes . ":00";
        if ($matchID = $this->isMatchExists($teamA, $teamB, $phase))
            return $this->db->exec_query("UPDATE " . $this->config['db_prefix'] . "matchs SET date = '" . addslashes($date) . "' WHERE matchID = " . $matchID . "");
        else
            return $this->db->insert("INSERT INTO " . $this->config['db_prefix'] . "matchs (date, teamA, teamB, phaseID) VALUES ('" . addslashes($date) . "','" . addslashes($teamA) . "','" . addslashes($teamB) . "', " . addslashes($phase) . ")");
    }

    function addUser($name, $login, $pass, $userTeamId, $isAdmin) {
        if (false)
            return $this->db->insert("INSERT INTO " . $this->config['db_prefix'] . "users (name, login, password, userTeamID, status) VALUES ('" . addslashes($name) . "','" . addslashes($login) . "','" . addslashes($pass) . "', " . $userTeamId . ", " . $isAdmin . ")");
        else
            return $this->db->insert("INSERT INTO " . $this->config['db_prefix'] . "users (name, login, password, userTeamID, status) VALUES ('" . addslashes($name) . "','" . addslashes($login) . "','" . addslashes($pass) . "', " . $userTeamId . ", " . $isAdmin . ")");
    }

    function saveProno($userID, $matchID, $team, $score, $pny=-1, $isAdmin=0) {
        if ($score == "")
            $score = 'NULL';
        if ($this->isPronoExists($userID, $matchID)) {
            $ret = $this->db->exec_query("UPDATE " . $this->config['db_prefix'] . "pronos SET score" . $team . " =" . addslashes($score) . " WHERE userID=" . $userID . " AND matchID=" . $matchID . "");
        } else {
            $ret = $this->db->insert("INSERT INTO " . $this->config['db_prefix'] . "pronos (userID,matchID,score" . $team . ") VALUES ('" . addslashes($userID) . "','" . addslashes($matchID) . "'," . addslashes($score) . ")");
        }
        $pnyValue = 'NULL';
        if ($pny >= 0) {
            $pnyValue = $pny;
        }
        $ret = $this->db->exec_query("UPDATE " . $this->config['db_prefix'] . "pronos SET pny" . $team . " =" . $pnyValue . " WHERE userID=" . $userID . " AND matchID=" . $matchID);

        return $ret;
    }

    function saveResult($matchID, $team, $score, $bonus, $pny='') {
        if ($score == "")
            $score = 'NULL';
        $req = "UPDATE " . $this->config['db_prefix'] . "matchs";
        $req .= " SET score" . $team . " =" . addslashes($score);
        $req .= ", bonus" . $team . " =" . addslashes($bonus);
        if ($pny == 'A')
            $req .= ", pnyA = 1, pnyB = 0";
        elseif ($pny == 'B')
            $req .= ", pnyA = 0, pnyB = 1";
        else
            $req .= ", pnyA = NULL, pnyB = NULL";
        $req .= " WHERE matchID=" . $matchID;

        $ret = $this->db->exec_query($req);

        return $ret;
    }

    function setLastGenerate() {
        // Main Query
        $req = "REPLACE";
        $req .= " INTO " . $this->config['db_prefix'] . "settings";
        $req .= " (name,date)";
        $req .= " VALUES ('LAST_GENERATE',NOW())";

        $this->db->exec_query($req);

        return;
    }

    function setNewPassword($userID) {
        $user = $this->getUser($userID);
        if(!$user) {
            return false;
        }
        $new_pass = newPassword(8);

        $req = 'UPDATE ' . $this->config['db_prefix'] . 'users';
        $req .= ' SET password = \'' . $new_pass . '\'';
        $req .= ' WHERE "userID" = ' . $userID . '';

        if($this->db->exec_query($req)) {
            return $new_pass;
        }
        else {
            return false;
        }
    }
    
    function saveTag($text, $userTeamID=false) {
        $userID = $_SESSION['userID'];
        if (!$userTeamID)
            $userTeamID = -1;
        
        prepare_numeric_data(array(&$userTeamID,&$userID));
        prepare_alphanumeric_data(array(&$text));
	$text = htmlspecialchars(trim($text));
                
        $tagID = $this->db->insert("INSERT INTO " . $this->config['db_prefix'] . "tags (userID, userTeamID, date, tag) VALUES (" . $userID . ", " . $userTeamID . ", NOW(), '" . $text . "')");

        return;
    }

    function delTag($tagID) {
        $tag = $this->getTag($tagID);
        if ($tag['userID'] != $_SESSION['userID'] && !$this->admin)
            return;

        $tagID = $this->db->exec_query("DELETE FROM " . $this->config['db_prefix'] . "tags WHERE tagID = " . $tagID . "");

        return;
    }

    /*     * *************** */
    /*     TESTERS	  */
    /*     * *************** */

    function isPronoExists($userID, $matchID) {
        // Main Query
        $req = "SELECT matchID";
        $req .= " FROM " . $this->config['db_prefix'] . "pronos ";
        $req .= " WHERE userID = " . $userID;
        $req .= " AND matchID = " . $matchID;

        return $this->db->select_one($req, null);
    }

    function isMatchExists($teamA, $teamB, $phaseID) {
        // Main Query
        $req = "SELECT matchID";
        $req .= " FROM " . $this->config['db_prefix'] . "matchs m ";
        $req .= " WHERE teamA = " . $teamA;
        $req .= " AND teamB = " . $teamB;
        $req .= " AND phaseID = " . $phaseID;

        return $this->db->select_one($req, null);
    }

    function isDatePassed($matchID) {
        // Main Query
        $req = "SELECT 1";
        $req .= " FROM " . $this->config['db_prefix'] . "matchs m ";
        $req .= " WHERE matchID = " . $matchID;
        $req .= " AND m.date < NOW()";

        return $this->db->select_one($req, null);
    }

    function isRankToUpdate() {
        $req = "SELECT 1";
        $req .= " FROM " . $this->config['db_prefix'] . "settings";
        $req .= " WHERE name = 'LAST_GENERATE'";
        $req .= " AND DATE_FORMAT(date, '%m%e') <> DATE_FORMAT(NOW(), '%m%e')";
        $isLastGenerate = $this->db->select_one($req, null);

        if ($isLastGenerate == 1) {
            $req = "SELECT count(matchID) as nbMatchs";
            $req .= " FROM " . $this->config['db_prefix'] . "matchs";
            $req .= " WHERE DATE_FORMAT(date, '%m%e') = DATE_FORMAT(NOW(), '%m%e')";
            $req .= " AND scoreA IS NULL AND scoreB IS NULL";
            $nbMacths = $this->db->select_one($req, null);
            return ($nbMacths == 0);
        }
        else
            return false;
    }

    /*     * *************** */
    /*     MISC		  */
    /*     * *************** */

    function resetNbMatchsPlayed() {
        // Main Query
        $req = "UPDATE " . $this->config['db_prefix'] . "settings";
        $req .= " SET value = 0";
        $req .= " WHERE name = 'NB_MATCHS_PLAYED'";

        $this->db->exec_query($req);

        return;
    }

    function loadTags($userTeamID=-1, $start=false) {
        $start = $start ? $start : 0;
        $tags = $this->getTags($start, 20, $userTeamID);
        
        $tag_content = "";
        foreach ($tags as $tag) {
            $tag_content .= "<div id=\"tag_".$tag['tagID']."\">";
            $tag_content .= "<img onclick=\"delTag(".$tag['tagID'].")\" src=\"/include/theme/".$this->config['template']."/images/del.png\" alt=\"Supprimer\" />";
            $tag_content .= "<u>".$tag['date']."<br /><b>".stripslashes($tag['name'])."</b></u><br />";
            $tag_content .= stripslashes($tag['tag'])."<br /><br /></div>";
        }
        echo $tag_content;
    }
   
    function loadRanking() {
        $users = $this->getUsers();
        usort($users, "compare_users");
        $nbMatchs = $this->getNbTotalMatchs();

        $i = 1;
        $j = 0;
        $k = 0;
        $last_user = $users[0];

        foreach ($users as $user) {
            if ($user['nbpronos'] == 0)
                continue;
            if (compare_users($user, $last_user) != 0)
                $i = $j + 1;

            $evol = $user['last_rank'] - $i;

            if ($evol == 0)
                $img = "egal.png";
            elseif ($evol > 5)
                $img = "arrow_up2.png";
            elseif ($evol > 0)
                $img = "arrow_up1.png";
            elseif ($evol < -5)
                $img = "arrow_down2.png";
            elseif ($evol < 0)
                $img = "arrow_down1.png";

            if ($evol > 0)
                $evol = "+" . $evol;

            $usersView[$k++] = array(
                'RANK' => $i,
                'LAST_RANK' => "<img src=\"" . $this->theme_location . "images/" . $img . "\" alt=\"\" /><br/><span style=\"text-align:center;font-size:70%;\">(" . $evol . ")</span>",
                'NB_BETS' => ($user['nbpronos'] != $nbMatchs) ? "(<span style=\"color:red;\">" . $user['nbpronos'] . "/" . $nbMatchs . "</span>)" : "",
                'ID' => $user['userID'],
                'NAME' => $user['name'],
                'VIEW_BETS' => "<a href=\"/?op=edit_pf&user=" . $user['userID'] . "\">",
                'POINTS' => $user['points'],
                'NBRESULTS' => $user['nbresults'],
                'NBSCORES' => $user['nbscores'],
                'DIFF' => $user['diff'],
                'TEAM' => $user['team']
            );
            $last_user = $user;
            $j++;
        }

        return $usersView;
    }

    function loadUserTeamRanking() {
        $userTeams = $this->getUserTeams();
        $userTeamsView = array();

        foreach ($userTeams as $userTeam) {
            $users = $this->getUsersByUserTeam($userTeam['userTeamID']);
            $userTeam['nbUsersActifs'] = sizeof($users);
            if ($userTeam['nbUsersActifs'] < 2)
                continue;
            $userTeam['nbUsersTotal'] = $this->getNbUsersByUserTeam($userTeam['userTeamID']);
            $userTeamsView[] = $userTeam;
        }

        $rank = 1;
        $last_team = $userTeams[0];
        usort($userTeamsView, "compare_user_teams");
        for ($i = 0; $i < sizeof($userTeamsView); $i++) {
            if (compare_user_teams($userTeamsView[$i], $last_team) != 0)
                $rank = $i + 1;
            $userTeamsView[$i]['rank'] = $rank;

            $evol = $userTeamsView[$i]['lastRank'] - $rank;
            if ($evol == 0)
                $img = "egal.png";
            elseif ($evol > 5)
                $img = "arrow_up2.png";
            elseif ($evol > 0)
                $img = "arrow_up1.png";
            elseif ($evol < -5)
                $img = "arrow_down2.png";
            elseif ($evol < 0)
                $img = "arrow_down1.png";
            if ($evol > 0)
                $evol = "+" . $evol;
            $userTeamsView[$i]['lastRank'] = "<img src=\"" . $this->theme_location . "images/" . $img . "\" alt=\"\" /><br/><span style=\"text-align:center;font-size:70%;\">(" . $evol . ")</span>";

            $last_team = $userTeamsView[$i];
        }
        return $userTeamsView;
    }

    function loadRankingInTeams($userTeamId) {
        $users = $this->getUsersByUserTeam($userTeamId);
        usort($users, "compare_users");
        $nbMatchs = $this->getNbTotalMatchs();

        $i = 1;
        $j = 0;
        $k = 0;
        $last_user = $users[0];

        foreach ($users as $user) {
            if ($user['nbpronos'] == 0)
                continue;
            if (compare_users($user, $last_user) != 0)
                $i = $j + 1;

            $usersView[$k++] = array(
                'RANK' => $i,
                'LAST_RANK' => "<img src=\"" . $this->theme_location . "images/" . $img . "\" alt=\"\" /><br/>",
                'NB_BETS' => ($user['nbpronos'] != $nbMatchs) ? "(<span style=\"color:red;\">" . $user['nbpronos'] . "/" . $nbMatchs . "</span>)" : "",
                'ID' => $user['userID'],
                'NAME' => $user['name'],
                'VIEW_BETS' => "<a href=\"/?op=edit_pronos&user=" . $user['userID'] . "\">",
                'POINTS' => $user['points'],
                'NBRESULTS' => $user['nbresults'],
                'NBSCORES' => $user['nbscores'],
                'DIFF' => $user['diff'],
                'TEAM' => $user['team']
            );
            $last_user = $user;
            $j++;
        }

        return $usersView;
    }

    function updateRanking() {
        $matchs = $this->getMatchs();
        $users = array();
        $ranks = $this->getUsersRank();

        // Points pr les matchs
        foreach ($matchs as $match) {
            $pronos = $this->getPronosByMatch($match['matchID']);
            $phase = $this->getPhase($match['phaseID']);

            foreach ($pronos as $prono) {
                if (!isset($users[$prono['userID']])) {
                    $users[$prono['userID']] = array();
                    $users[$prono['userID']]['userID'] = $prono['userID'];
                    $users[$prono['userID']]['points'] = 0;
                    $users[$prono['userID']]['nbscores'] = 0;
                    $users[$prono['userID']]['diff'] = 0;
                    $users[$prono['userID']]['nbresults'] = 0;
                    $users[$prono['userID']]['rank'] = 'NULL';
                    if ($ranks[$prono['userID']])
                        $users[$prono['userID']]['rank'] = $ranks[$prono['userID']];
                }

                if (($prono['scorePronoA'] != NULL) && ($prono['scorePronoB'] != NULL) && ($match['scoreMatchA'] != NULL) && ($match['scoreMatchB'] != NULL)) {
                    $resProno = $this->computeNbPtsProno($phase, $match['scoreMatchA'], $match['scoreMatchB'], $match['pnyMatchA'], $match['pnyMatchB'], $prono['scorePronoA'], $prono['scorePronoB'], $prono['pnyPronoA'], $prono['pnyPronoB']);
                    $users[$prono['userID']]['nbresults'] += $resProno['res'];
                    $users[$prono['userID']]['points'] += $resProno['points'];
                    $users[$prono['userID']]['nbscores'] += $resProno['score'];
                    $users[$prono['userID']]['diff'] += $resProno['diff'];
                }
            }
        }

        // Points pour les qualifies
        $phases = $this->getPhases();
        foreach ($users as $user) {
            foreach ($phases as $phase) {
                if (($phase['phasePrecedente'] != NULL) && ($phase['nb_qualifies'] > 0)) {
                    $phasePre = $this->getPhase($phase['phasePrecedente']);
                    if ($this->getNbMatchsPlayedByPhase($phasePre['phaseID']) == $phasePre['nb_matchs']) {
                        $teamsMatchQualified = $this->getQualifiedTeamsByPhase($phase);
                        $teamsPronoQualified = $this->getQualifiedTeamsByPhase($phase, 'Prono', $user['userID']);
                        foreach ($teamsMatchQualified as $teamM) {
                            foreach ($teamsPronoQualified as $teamP) {
                                if ($teamP['teamID'] == $teamM['teamID']) {
                                    $users[$user['userID']]['points'] += $phasePre['nbPointsQualifie'];
                                    break;
                                }
                            }
                        }
                    }
                }
            }

            // Dernieres Phases
            $phasesUltimes = $this->getFinalPhases();
            foreach ($phasesUltimes as $phase) {
                $phasePre = $phase;
                $phase['phasePrecedente'] = $phasePre['phaseID'];

                if ($this->getNbMatchsPlayedByPhase($phase['phaseID']) == $phase['nb_matchs']) {
                    $teamsMatchQualified = $this->getQualifiedTeamsByPhase($phase);
                    $teamsPronoQualified = $this->getQualifiedTeamsByPhase($phase, 'Prono', $user['userID']);
                    foreach ($teamsMatchQualified as $teamM) {
                        foreach ($teamsPronoQualified as $teamP) {
                            if ($teamP['teamID'] == $teamM['teamID']) {
                                $users[$user['userID']]['points'] += $phasePre['nbPointsQualifie'];
                                break;
                            }
                        }
                    }
                }
            }
        }

        // MaJ BDD
        $is_rank_to_update = $this->isRankToUpdate();
        foreach ($users as $ID => $user) {
            if ($is_rank_to_update) {
                $this->db->exec_query("UPDATE " . $this->config['db_prefix'] . "users SET points = " . $user['points'] . ", nbresults = " . $user['nbresults'] . ", nbscores = " . $user['nbscores'] . ", diff = " . $user['diff'] . ", last_rank = " . $user['rank'] . " WHERE userID=" . $ID . "");
            } else {
                $this->db->exec_query("UPDATE " . $this->config['db_prefix'] . "users SET points = " . $user['points'] . ", nbresults = " . $user['nbresults'] . ", nbscores = " . $user['nbscores'] . ", diff = " . $user['diff'] . " WHERE userID=" . $ID . "");
            }
        }
        //if($is_rank_to_update) $this->setLastGenerate();
        return;
    }

    function updateUserTeamRanking() {
        $userTeams = $this->getUserTeams();
        $userTeamsView = array();
        $nbMatchsPlayed = $this->getNbMatchsPlayed();

        foreach ($userTeams as $userTeam) {
            $userTeam['avgPoints'] = 0;
            $userTeam['maxPoints'] = 0;
            $userTeam['totalPoints'] = 0;

            $users = $this->getUsersByUserTeam($userTeam['userTeamID']);
            $nbUsersActifs = 0;
            foreach ($users as $user) {
                if (($this->getNbPlayedPronosByUser($user['userID']) / $nbMatchsPlayed) > 0.5) {
                    $userTeam['totalPoints'] += $user['points'];
                    if ($user['points'] > $userTeam['maxPoints']) {
                        $userTeam['maxPoints'] = $user['points'];
                    }
                    $nbUsersActifs++;
                }
            }
            if ($nbUsersActifs > 0) {
                $userTeam['avgPoints'] = round($userTeam['totalPoints'] / $nbUsersActifs);
            }
            $userTeamsView[] = $userTeam;
        }

        // MaJ BDD
        $is_rank_to_update = $this->isRankToUpdate();
        usort($userTeamsView, "compare_user_teams");
        for ($i = 0; $i < sizeof($userTeamsView); $i++) {
            $userTeam = $userTeamsView[$i];
            $userTeam['rank'] = ($i + 1);

            if ($is_rank_to_update) {
                $this->db->exec_query("UPDATE " . $this->config['db_prefix'] . "user_teams SET avgPoints = " . $userTeam['avgPoints'] . ", totalPoints = " . $userTeam['totalPoints'] . ", maxPoints = " . $userTeam['maxPoints'] . ", lastRank = " . $userTeam['rank'] . " WHERE userTeamID=" . $userTeam['userTeamID']);
            } else {
                $this->db->exec_query("UPDATE " . $this->config['db_prefix'] . "user_teams SET avgPoints = " . $userTeam['avgPoints'] . ", totalPoints = " . $userTeam['totalPoints'] . ", maxPoints = " . $userTeam['maxPoints'] . " WHERE userTeamID=" . $userTeam['userTeamID']);
            }
        }
        if ($is_rank_to_update)
            $this->setLastGenerate();
    }

    function getTimeBeforeMatch($matchID) {
        // Main Query
        $req = "SELECT TIME_TO_SEC(TIMEDIFF(date, NOW())) as delay_sec";
        $req .= " FROM " . $this->config['db_prefix'] . "matchs m ";
        $req .= " WHERE matchID=" . $matchID;
        $res = $this->db->select_one($req);

        return $res;
    }

    function computeNbPtsProno($phase, $scoreMatchA, $scoreMatchB, $scorePnyA, $scorePnyB, $scorePronoA, $scorePronoB, $pronoPnyA, $pronoPnyB) {
        $nbPoints = 0;
        $resJuste = 0;
        $diff = 0;

        $winnerMatch = 'x';
        $winnerProno = 'y';
        $resMatch = 'x';
        $resProno = 'y';

        $limite1 = 20;
        $ecart1a = 1;
        $ecart1b = 4;
        $limite2 = 40;
        $ecart2a = 3;
        $ecart2b = 8;
        $limite3 = 60;
        $ecart3a = 5;
        $ecart3b = 12;
        $ecart4a = 7;
        $ecart4b = 20;

        // Real winner
        if (($scoreMatchA != NULL) && ($scoreMatchB != NULL)) {
            if ($scoreMatchA > $scoreMatchB) {
                $winnerMatch = 'A';
                $resMatch = 'A';
            } elseif ($scoreMatchA < $scoreMatchB) {
                $winnerMatch = 'B';
                $resMatch = 'B';
            } else {
                $resMatch = 'N';
                if (($scorePnyA == NULL) || ($scorePnyB == NULL)) {
                    $winnerMatch = 'N';
                } elseif ($scorePnyA > $scorePnyB) {
                    $winnerMatch = 'A';
                } else {
                    $winnerMatch = 'B';
                }
            }
        }

        // Prono winner
        if (($scorePronoA != NULL) && ($scorePronoB != NULL)) {
            if ($scorePronoA > $scorePronoB) {
                $winnerProno = 'A';
                $resProno = 'A';
            } elseif ($scorePronoA < $scorePronoB) {
                $winnerProno = 'B';
                $resProno = 'B';
            } else {
                $resProno = 'N';
                if (($pronoPnyA == NULL) || ($pronoPnyB == NULL)) {
                    $winnerProno = 'N';
                } elseif ($pronoPnyA > $pronoPnyB) {
                    $winnerProno = 'A';
                } else {
                    $winnerProno = 'B';
                }
            }
        }

        // Computing points
        if ($resProno == $resMatch) {
            // Result
            $nbPoints += $phase['nbPointsRes'];
            $resJuste = 1;

            // Score A
            if ($scoreMatchA <= $limite1) {
                if (abs($scoreMatchA - $scorePronoA) < $ecart1a)
                    $nbPoints += $phase['nbPointsScoreNiv1'];
                elseif (abs($scoreMatchA - $scorePronoA) <= $ecart1b)
                    $nbPoints += $phase['nbPointsScoreNiv2'];
            }
            elseif ($scoreMatchA < $limite2) {
                if (abs($scoreMatchA - $scorePronoA) < $ecart2a)
                    $nbPoints += $phase['nbPointsScoreNiv1'];
                elseif (abs($scoreMatchA - $scorePronoA) <= $ecart2b)
                    $nbPoints += $phase['nbPointsScoreNiv2'];
            }
            elseif ($scoreMatchA < $limite3) {
                if (abs($scoreMatchA - $scorePronoA) < $ecart3a)
                    $nbPoints += $phase['nbPointsScoreNiv1'];
                elseif (abs($scoreMatchA - $scorePronoA) <= $ecart3b)
                    $nbPoints += $phase['nbPointsScoreNiv2'];
            }
            elseif ($scoreMatchA > $limite3) {
                if (abs($scoreMatchA - $scorePronoA) < $ecart4a)
                    $nbPoints += $phase['nbPointsScoreNiv1'];
                elseif (abs($scoreMatchA - $scorePronoA) <= $ecart4b)
                    $nbPoints += $phase['nbPointsScoreNiv2'];
            }

            // Score B
            if ($scoreMatchB <= $limite1) {
                if (abs($scoreMatchB - $scorePronoB) < $ecart1a)
                    $nbPoints += $phase['nbPointsScoreNiv1'];
                elseif (abs($scoreMatchB - $scorePronoB) <= $ecart1b)
                    $nbPoints += $phase['nbPointsScoreNiv2'];
            }
            elseif ($scoreMatchB < $limite2) {
                if (abs($scoreMatchB - $scorePronoB) < $ecart2a)
                    $nbPoints += $phase['nbPointsScoreNiv1'];
                elseif (abs($scoreMatchB - $scorePronoB) <= $ecart2b)
                    $nbPoints += $phase['nbPointsScoreNiv2'];
            }
            elseif ($scoreMatchB < $limite3) {
                if (abs($scoreMatchB - $scorePronoB) < $ecart3a)
                    $nbPoints += $phase['nbPointsScoreNiv1'];
                elseif (abs($scoreMatchB - $scorePronoB) <= $ecart3b)
                    $nbPoints += $phase['nbPointsScoreNiv2'];
            }
            elseif ($scoreMatchB > $limite3) {
                if (abs($scoreMatchB - $scorePronoB) < $ecart4a)
                    $nbPoints += $phase['nbPointsScoreNiv1'];
                elseif (abs($scoreMatchB - $scorePronoB) <= $ecart4b)
                    $nbPoints += $phase['nbPointsScoreNiv2'];
            }

            // Gap
            $matchGap = abs($scoreMatchA - $scoreMatchB);
            $pronoGap = abs($scorePronoA - $scorePronoB);
            if ($matchGap <= $limite1) {
                if (abs($matchGap - $pronoGap) < $ecart1a)
                    $nbPoints += $phase['nbPointsEcartNiv1'];
                elseif (abs($matchGap - $pronoGap) <= $ecart1b)
                    $nbPoints += $phase['nbPointsEcartNiv2'];
            }
            elseif ($matchGap <= $limite2) {
                if (abs($matchGap - $pronoGap) < $ecart2a)
                    $nbPoints += $phase['nbPointsEcartNiv1'];
                elseif (abs($matchGap - $pronoGap) <= $ecart2b)
                    $nbPoints += $phase['nbPointsEcartNiv2'];
            }
            elseif ($matchGap <= $limite3) {
                if (abs($matchGap - $pronoGap) < $ecart3a)
                    $nbPoints += $phase['nbPointsEcartNiv1'];
                elseif (abs($matchGap - $pronoGap) <= $ecart3b)
                    $nbPoints += $phase['nbPointsEcartNiv2'];
            }
            elseif ($matchGap > $limite3) {
                if (abs($matchGap - $pronoGap) < $ecart4a)
                    $nbPoints += $phase['nbPointsEcartNiv1'];
                elseif (abs($matchGap - $pronoGap) <= $ecart4b)
                    $nbPoints += $phase['nbPointsEcartNiv2'];
            }
        }

        $diff -= abs($scoreMatchA - $scorePronoA) + abs($scoreMatchB - $scorePronoB);
        if (($nbPoints - $phase['nbPointsRes']) >= (($phase['nbPointsScoreNiv2'] * 2) + $phase['nbPointsEcartNiv1']))
            $score = 1;
        $retour = array("points" => $nbPoints, "res" => $resJuste, "score" => $score, "diff" => $diff);

        if ($this->debug)
            array_show($retour);

        return $retour;
    }

    function getPhaseIDActive() {
        $phaseIDactive = -1;
        $phaseIDjoue = -1;
        $phases = $this->getPhases();
        foreach ($phases as $phase) {
            $req = "SELECT count(*) FROM " . $this->config['db_prefix'] . "matchs";
            $req .= " WHERE phaseID = " . $phase['phaseID'];
            $nb_matchs = $this->db->select_one($req);
            $req = "SELECT count(*) FROM " . $this->config['db_prefix'] . "matchs";
            $req .= " WHERE phaseID = " . $phase['phaseID'] . " AND scoreA IS NULL";
            $nb_matchs_non_joues = $this->db->select_one($req);
            if ($nb_matchs_non_joues > 0)
                $phaseIDactive = $phase['phaseID'];
            if (($nb_matchs > 0) && ($nb_matchs_non_joues == 0))
                $phaseIDjoue = $phase['phaseID'];
        }
        if ($phaseIDactive == -1) {
            $phaseIDactive = $phaseIDjoue;
        }
        return $phaseIDactive;
        ;
    }

    function sendIDs($email) {
        if($email) {
            $user = $this->getUserByEmail($email);
            if($user) {
                if($newPassword = $this->setNewPassword($user['userID'])) {
                    $res = utf8_mail($user['email'], $this->config['title']." - Rappel de vos identifiants", "Bonjour,\n\nVotre login est : " . $user['login'] . "\nVotre nouveau mot de passe est : " . $newPassword . "\n\nCordialement,\nL'équipe " . $this->config['support_team'] . "\n", $this->config['title'], $this->config['support_email'], $this->config['email_simulation']);
                } else {
                    $res = false;
                }

                if(!$res) {
                    utf8_mail($this->config['email'], $this->config['title']." - Problème envoi à '" . $email . "'", "L'utilisateur avec l'email '" . $email . "' a tenté de récupérer ses identifiants.\n", $this->config['title'], $this->config['support_email'], $this->config['email_simulation']);
                    return FORGOT_IDS_KO;
                } else {
                    return FORGOT_IDS_OK;
                }
            } else {
                utf8_mail($this->config['email'], $this->config['title']." - Email '" . $email . "' inconnu", "L'utilisateur avec l'email '" . $email . "' a tenté de récupérer ses identifiants.\n", $this->config['title'], $this->config['support_email'], $this->config['email_simulation']);
                return EMAIL_UNKNOWN;
            }
        } else {
            return INCORRECT_EMAIL;
        }
    }
}

?>
