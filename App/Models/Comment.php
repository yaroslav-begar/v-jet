<?php

namespace App\Models;

use PDO;

class Comment extends \Core\AbstractModel
{
    protected static $table = 'comment';

    public static function findAllByPostId(int $id)
    {
        $db = static::getDB();
        $stmt = $db->query("
            SELECT c.author as author, c.text as text, c.date as date
            FROM comment as c
            JOIN post as p ON p.id = c.post_id
            WHERE p.id = {$id}
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
