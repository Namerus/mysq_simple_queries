<?php

$dbName = 'testing';
$dbHost = 'localhost';
$dbUser = 'phpmyadmin';
$dbPass = 'some_pass';


echo '<p style="width: 600px;">Необходимо написать запрос, по которому будут выданы строки с именем
пользователя, названием сообщества, датой присоединения к сообществу,
упорядоченные по убыванию даты присоединения к сообществу. Выбираемые
сообщества должны быть созданы не ранее, чем 2013-01-01 00:00:00.</p>'; 

try {
    $dbh = new PDO("mysql:host=$dbHost; dbname=$dbName", $dbUser, $dbPass);

    $query = 'SELECT community_members.joined_at, users.name, communities.name as community_name
        FROM `community_members` 
        LEFT JOIN `users` ON community_members.user_id = users.id
        LEFT JOIN `communities` ON community_members.community_id = communities.id
        WHERE community_members.joined_at >= "2013-01-01 00:00:00" 
        ORDER BY joined_at DESC';
    $result = $dbh->prepare($query);
    $result->execute();
    $rowCount = $result->rowCount();

    if ($rowCount === 0) die('Empty request');
    echo "<h3>Результатов по запросу: $rowCount </h3>";
    while ($row = $result->fetch())
    {
        echo $row['name'] . '</br>' . $row['joined_at'] . '</br>' . $row['community_name'] . '<hr>';
    }

    $dbh = null;

} catch (PDOException $e) {
    die($e->getMessage() . "<br/>");
}
