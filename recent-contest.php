<?php
 require_once("oj-header.php");
        require_once("./include/db_info.inc.php");
        echo "<title>Recent Contests from Naikai-contest-spider</title>";
?>

<div align="center">
<?php
    $json = @file_get_contents('http://algcontest.rainng.com/contests.json');

$rows = json_decode($json, true);

$odd=true;
?>
<h2></h2>
<table width=80% align=center>
<thead class=toprow>
        <tr>
                <th class="column-1">OJ</th><th class="column-2">Name</th><th class="column-3">Start Time</th><th class="column-4">Week</th><th class="column-5">Access</th>
        </tr>
</thead>
<tbody class="row-hover">
<? foreach ($rows as $row) : 
   $odd=!$odd;
?>
  <tr class="<?php echo $odd?"oddrow":"evenrow"  ?>">
                <td class="column-1"><?=$row['oj']?></td><td class="column-2"><a id="name_<?=$row['id']?>" href="<?=$row['link']?>" target="_blank"><?=$row['name']?></a></td><td class="column-3"><?=$row['start_time']?></td><td class="column-4"><?=$row['week']?></td><td class="column-5"><?=$row['access']?></td>
        </tr>
<? endforeach; ?>
</tbody>
</table>
</div>
<div align=center>DataSource:http://algcontest.rainng.com/contests.json  Spider Author:<a href="https://github.com/Azure99/AlgContestInfo" >Azure99</a></div>
<?php require('oj-footer.php');?>

