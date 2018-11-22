<?php
@session_start();
if (!isset($_SESSION['administrator'])) {
    echo "<a href='../loginpage.php'>Please Login First!</a>";
    exit(1);
}
require_once("../include/db_info.inc.php");

const transferProblemId = 2756;

function removeAcDir($dirPath) {
    if (!file_exists($dirPath) || !is_dir($dirPath)) {
        return;
    }

    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }

    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            removeAcDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}

function mockProblemInfo($from, $to) {
    $sql = 'select * from problem where problem_id = ' . $from;
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    mysql_free_result($result);

    $title=mysql_real_escape_string($row['title']);
    $time_limit=mysql_real_escape_string($row['time_limit']);
    $memory_limit=mysql_real_escape_string($row['memory_limit']);
    $description=mysql_real_escape_string($row['description']);

    $input=mysql_real_escape_string($row['input']);
    $output=mysql_real_escape_string($row['output']);
    $sample_input=mysql_real_escape_string($row['sample_input']);
    $sample_output=mysql_real_escape_string($row['sample_output']);

    $hint=mysql_real_escape_string($row['hint']);
    $source=mysql_real_escape_string($row['source']);

    $update = "update problem set " .
        "title = '$title'" .
        ", time_limit = " . $time_limit .
        ", memory_limit = " . $memory_limit .
        ", description = '$description'" .
        ", input = '$input'" .
        ", output = '$output'" .
        ", sample_input = '$sample_input'"  .
        ", sample_output = '$sample_output'" .
        ", hint = '$hint'" .
        ", source = '$source'" .
        ", in_date = '${row['in_date']}'" .
        ", defunct = '${row['defunct']}'" .
        ", spj = '${row['spj']}'" .
        ", author = '${row['author']}'" .
        " where problem_id = " .  $to;

    return mysql_query($update);
}

function mockProblemWithRetry($moveFrom, $moveTo) {
    $retryTime = 3;
    while ($retryTime-- > 0) {
        if (mockProblemInfo($moveFrom, $moveTo)) {
            break;
        }
        usleep(1000);
    }
}

function moveProblem($moveFrom, $moveTo) {
    mockProblemWithRetry($moveFrom, transferProblemId);
    usleep(200);
    mockProblemWithRetry($moveTo, $moveFrom);
    usleep(200);
    mockProblemWithRetry(transferProblemId, $moveTo);
}

function moveData($moveFrom, $moveTo, $path) {

    $tempNameDir = $path . "transferDir";
    $moveFromDir = $path . $moveFrom;
    $moveToDir = $path . $moveTo;

    // 删除掉之前的ac代码
    removeAcDir($moveFromDir . '/ac');
    removeAcDir($moveToDir . '/ac');

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

$flag = moveData($moveFrom, $moveTo, $OJ_DATA . '/');
if (!$flag) {
    echo "transfer test data failed";
    exit(1);
}

moveProblem($moveFrom, $moveTo);

moveSolution($moveFrom, $moveTo);

echo "success";
