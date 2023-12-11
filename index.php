<?php

require(__DIR__ . '/Controller/FireHistoryController.php');

$fireHistoryController = new FireHistoryController();
$selectedYear = isset($_GET['selectedYear']) ? $_GET['selectedYear'] : null;

?>

<!DOCTYPE html>
<html lang="en">
    <?php require('_head.php') ?>
<body>

<h1>&#x1F6B6; HikeGuard - Fire History</h1>

<form method="get" action="" id="filterForm">
    <label for="filterSelect">Filter by Year:</label>
    <select id="filterSelect" name="selectedYear">
        <option value="1500" <?php if ($selectedYear == '1500') echo 'selected'; ?>>1500 BP and later</option>
        <option value="1000" <?php if ($selectedYear == '1000') echo 'selected'; ?>>1000 BP and later</option>
        <option value="500" <?php if ($selectedYear == '500') echo 'selected'; ?>>500 BP and later</option>
    </select>
    <input type="submit" value="Submit">
</form>

<div id="studyContainer">
    <?php $fireHistoryController->processFormSubmission(); ?>
</div>

</body>
</html>
