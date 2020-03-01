<?php

function highlight_nicknames(string $text) 
{
    $result = preg_replace('/(^|\s)@(\D\w+)\s/i', ' <b>${2}</b> ', $text);
    return $result;
}


$example_1 = '@storm87 сообщил нам вчера о результатах';
$example_2 = 'Я живу в одном доме с @300spartans';
$example_3 = 'Правильный ник: @usernick | неправильный ник: @usernick;';

$text = 'Some text with some @nickname ';

echo highlight_nicknames("$example_1 </br> $example_2 </br> $example_3");

