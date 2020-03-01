<?php

$dbName = 'testing';
$dbHost = 'localhost';
$dbUser = 'phpmyadmin';
$dbPass = 'some_pass';


echo '<p style="width: 600px;">Необходимо написать запрос, по которому будут выданы все строки с именем
пользователя, названием сообщества и и названием разрешения, которое у
него в этом сообществе. Имя пользователя должно содержать букву T в любом
регистре или название разрешения должно содержать подстроку articles .
Название сообщества должно содержать не менее 15 символов.</p>'; 

try {
    $dbh = new PDO("mysql:host=$dbHost; dbname=$dbName", $dbUser, $dbPass);

    $query = 'SELECT users.name as user_name, communities.name as community_name,
            permissions.name as permission_name, CHAR_LENGTH(communities.name) as community_length
        FROM `community_members`
        LEFT JOIN `community_member_permissions` ON (community_members.user_id = community_member_permissions.member_id)
        LEFT JOIN `users` ON (community_members.user_id = users.id)
        LEFT JOIN `communities` ON (community_members.community_id = communities.id)
        LEFT JOIN `permissions` ON (community_member_permissions.permission_id = permissions.id)
        WHERE users.name LIKE "%T%" OR permissions.name LIKE "%articles%"
        HAVING community_length >= 15';

    $result = $dbh->prepare($query);
    $result->execute();
    $rowCount = $result->rowCount();
    if($result->errorInfo()[2]) echo $result->errorInfo()[2];

    if ($rowCount === 0) die('Empty request');
    echo "<h3>Результатов по запросу: $rowCount </h3>";
    while ($row = $result->fetch()) 
    {
        echo "User: <strong>{$row['user_name']}</strong></br>
            Community: <strong>{$row['community_name']}</strong></br>
            Permission: <strong>{$row['permission_name']}</strong></br><hr>";
    }

    $dbh = null;

} catch (PDOException $e) {
    die($e->getMessage() . "<br/>");
}
