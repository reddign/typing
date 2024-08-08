<?php

enum RankType: string {
    case WPM = 'wpm';
    case Accuracy = 'accuracy';
}
/**
 * Get a user's global and personal rankings using the provided score for that RankType.
 * @param SQLConnection $connection asdf
 * @param int $userId asdf
 * @param RankType $type the type of ranking to check against
 * @param string $score the string representation of the score that user is checking against
 * @return array a map containing the 'personal' and 'global' keys and their corresponding values
 */
function get_rankings(SQLConnection $connection, int $userId, RankType $type, string $score): array {
    global $connection;
    $personal_scores = $connection->query("SELECT DISTINCT $type->value FROM scores WHERE userid = $userId ORDER BY $type->value DESC");
    $global_scores = $connection->query("SELECT DISTINCT $type->value FROM scores ORDER BY $type->value DESC");
    $p_rank = -1;
    $g_rank = -1;
    for ($i = 0; $i < sizeof($personal_scores); $i++) {
        if ($personal_scores[$i][$type->value] == $score) {
            $p_rank = $i + 1;
            break;
        }
    }
    for ($i = 0; $i < sizeof($global_scores); $i++) {
        if ($personal_scores[$i][$type->value] == $score) {
            $g_rank = $i + 1;
            break;
        }
    }

    return ['personal' => $p_rank, 'global' => $g_rank];
}


function get_leaderboard(SQLConnection $connection, RankType $type, int $maxEntries) {
    $query = $connection->query("SELECT IFNULL(u.username,'deleted user') AS 'username',max(s.$type->value) AS $type->value FROM scores s LEFT JOIN users u ON (u.id=s.userid) GROUP BY s.userid ORDER BY $type->value DESC LIMIT $maxEntries");
    return $query;
}

?>