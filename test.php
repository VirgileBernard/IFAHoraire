<?php
require 'db.php';
$stmt = $pdo->query('SELECT COUNT(*) FROM utilisateur');
echo 'Nombre d’utilisateurs: ' . $stmt->fetchColumn();
