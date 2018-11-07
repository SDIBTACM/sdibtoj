<?php
@session_start();
if (!isset($_SESSION['administrator'])) {
    echo "<a href='../loginpage.php'>Please Login First!</a>";
    exit(1);
}
require_once("../include/db_info.inc.php");

const transferProblemId = 2756;

function mockProblemInfo($from, $to) {
    $sql = 'select * from problem where problem_id = ' . $from;
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    mysql_free_result($result);

    $update = "update problem set " .
        "title = '${row['title']}'" .
        ", time_limit = " . $row['time_limit'] .
        ", memory_limit = " . $row['memory_limit'] .
        ", description = '${row['description']}'" .
        ", input = '${row['input']}'" .
        ", output = '${row['output']}'" .
        ", sample_input = '${row['sample_input']}'"  .
        ", sample_output = '${row['sample_output']}'" .
        ", hint = '${row['hint']}'" .
        ", source = '${row['source']}'" .
        ", in_date = '${row['in_date']}'" .
        ", defunct = '${row['defunct']}'" .
        ", spj = '${row['spj']}'" .
        ", author = '${row['author']}'" .
        " where problem_id = " .  $to;

    mysql_query($update);
}

function moveProblem($moveFrom, $moveTo) {
    mockProblemInfo($moveFrom, transferProblemId);
    usleep(200);
    mockProblemInfo($moveTo, $moveFrom);
    usleep(200);
    mockProblemInfo(transferProblemId, $moveTo);
}

function moveData($moveFrom, $moveTo, $path) {

    $tempNameDir = $path . "transferDir";
    $moveFromDir = $path . $moveFrom;
    $moveToDir = $path . $moveTo;

    $ok_1 = @rename($moveFromDir, $tempNameDir);
    $ok_2 = @rename($moveToDir, $moveFromDir);
    $ok_3 = @rename($tempNameDir, $moveToDir);

    return $ok_1 && $ok_2 && $ok_3;
}

function moveSolution($moveFrom, $moveTo) {
    // 暂时不处理了
}

$moveFrom = intval($_GET['moveFrom']);
$moveTo = intval($_GET['moveTo']);

if ($moveFrom <= 0 || $moveTo <= 0 || $moveFrom == transferProblemId || $moveTo == transferProblemId) {
    echo "wrong problem id";
    exit(1);
}

$flag = true;//moveData($moveFrom, $moveTo, $OJ_DATA . '/');
if (!$flag) {
    echo "transfer test data failed";
    exit(1);
}

moveProblem($moveFrom, $moveTo);

moveSolution($moveFrom, $moveTo);

echo "success";
