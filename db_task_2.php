<?php

$dbName = 'testing';
$dbHost = 'localhost';
$dbUser = 'phpmyadmin';
$dbPass = 'some_pass';


echo '<p style="width: 600px;">Необходимо написать запрос, по которому будут выданы идентификаторы
сообществ, названия сообществ, названия разрешений и количество
пользователей, имеющих соответствующее разрешение внутри сообщества.
Вывести записи с количеством разрешений не менее 5. Результаты должны
быть отсортированы по убыванию идентификаторов сообщества и возрастанию
количества разрешений. Количество возвращаемых результатов должно быть
не более 100.</p>'; 

try {
    $dbh = new PDO("mysql:host=$dbHost; dbname=$dbName", $dbUser, $dbPass, 
        [PDO::MYSQL_ATTR_INIT_COMMAND => "SET sql_mode = 'TRADITIONAL'"]);

    $query = 'SELECT communities.name, communities.id, community_members.community_id, permissions.name AS permission_name, 
            community_member_permissions.permission_id, COUNT(community_members.id) as count_users
        FROM `communities`
        LEFT JOIN `community_members` ON community_members.community_id = communities.id
        LEFT JOIN `community_member_permissions` ON community_members.user_id = community_member_permissions.member_id
        LEFT JOIN `permissions` ON community_member_permissions.permission_id = permissions.id
        WHERE community_member_permissions.permission_id != 0 
        GROUP BY communities.id, community_member_permissions.permission_id
        HAVING count_users >= 5
        ORDER BY community_id DESC, count_users ASC
        LIMIT 100';

    $result = $dbh->prepare($query);
    $result->execute();
    $rowCount = $result->rowCount();
    if($result->errorInfo()[2]) echo $result->errorInfo()[2];

    if ($rowCount === 0) die('Empty request');
    echo "<h3>Результатов по запросу: $rowCount </h3>";
    while ($row = $result->fetch()) 
    {
        echo "Community id: <strong>{$row['community_id']}</strong></br>
            Community name: <strong>{$row['name']}</strong></br>
            Permission: <strong>{$row['permission_name']}</strong></br>
            Users: <strong>{$row['count_users']}</strong></br><hr>";
    }

    $dbh = null;

} catch (PDOException $e) {
    die($e->getMessage() . "<br/>");
}
