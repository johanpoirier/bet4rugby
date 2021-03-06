<?php

class Palmares
{
  var $db;
  var $config;

  public function __construct(&$db, &$config)
  {
    $this->db = $db;
    $this->config = $config;
  }

  /**
   * @param string $domain
   * @return array
   */
  public function list_finished_competitions_by_domain($domain)
  {
    $params = ['domain' => $domain];

    // Main Query
    $req = 'SELECT c.*, count(p.id) as userCount';
    $req .= ' FROM ' . $this->config['db_common_prefix'] . 'competitions as c';
    $req .= ' LEFT JOIN ' . $this->config['db_common_prefix'] . 'palmares as p';
    $req .= ' ON (c.id = p.competitionId)';
    $req .= ' WHERE domain = :domain';
    $req .= ' AND c.endDate < NOW()';
    $req .= ' GROUP BY p.competitionId';
    $req .= ' HAVING userCount > 1';
    $req .= ' ORDER BY startDate DESC';

    // Execute Query
    $competitionCount = 0;

    return $this->db->selectArray($req, $params, $competitionCount);
  }

  /**
   * @param int $competitionId
   * @param string $domain
   * @return mixed
   */
  public function get_competition_by_id_and_domain($competitionId, $domain)
  {
    $params = ['competitionId' => $competitionId, 'domain' => $domain];

    // Main Query
    $req = 'SELECT *';
    $req .= ' FROM ' . $this->config['db_common_prefix'] . 'competitions';
    $req .= ' WHERE id = :competitionId AND domain = :domain';
    $req .= ' LIMIT 1';


    // Execute Query
    $competitionCount = 0;

    return $this->db->selectLine($req, $params, $competitionCount);
  }

  /**
   * @param $domain
   * @return mixed
   */
  public function get_last_competition_for_domain($domain)
  {
    $params = ['domain' => $domain];

    // Main Query
    $req = 'SELECT c.*, count(p.id) as userCount';
    $req .= ' FROM ' . $this->config['db_common_prefix'] . 'competitions as c';
    $req .= ' LEFT JOIN ' . $this->config['db_common_prefix'] . 'palmares as p';
    $req .= ' ON (c.id = p.competitionId)';
    $req .= ' WHERE domain = :domain';
    $req .= ' GROUP BY p.competitionId';
    $req .= ' HAVING userCount > 1';
    $req .= ' ORDER BY startDate DESC';
    $req .= ' LIMIT 1';

    // Execute Query
    $competitionCount = 0;

    return $this->db->selectLine($req, $params, $competitionCount);
  }

  /**
   * @param int $competitionId
   * @return array
   */
  public function list_users_by_competition($competitionId)
  {
    $params = ['competitionId' => $competitionId];

    // Main Query
    $req = 'SELECT id, userName as name, userPoints as points, userScores as nbscores, userResults as nbresults, userDiff as diff';
    $req .= ' FROM ' . $this->config['db_common_prefix'] . 'palmares';
    $req .= ' WHERE competitionId = :competitionId';
    $req .= ' ORDER BY userPoints DESC';

    // Execute Query
    $userCount = 0;

    return $this->db->selectArray($req, $params, $userCount);
  }

  /**
   * @param $competitionId
   * @return array
   */
  public function get_competition_ranking($competitionId)
  {
    $users = $this->list_users_by_competition($competitionId);
    usort($users, 'compare_users');

    $ranks = [];
    usort($users, 'compare_users');
    $rank = 1;
    $last_rank = 0;


    if (count($users) > 0) {
      $last_user = $users[0];
      foreach ($users as $ID => $user) {
        $ranks[$user['id']] = $user;
        if (compare_users($user, $last_user) !== 0) {
          $rank = $last_rank + 1;
        }

        $ranks[$user['id']]['rank'] = $rank;

        $last_rank++;
        $last_user = $user;
      }
    }

    return $ranks;
  }
}
