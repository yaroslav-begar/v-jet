<?php

namespace App\Models;

use PDO;

class Post extends \Core\AbstractModel
{
    protected static $table = 'post';

    public static function findOneById(int $id)
    {
        $db = static::getDB();
        $stmt = $db->query("
            SELECT author, text, date 
            FROM post
            WHERE id = {$id}
        ");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function findAll()
    {
        $db = static::getDB();
        $stmt = $db->query('
            SELECT p.id, p.author, LEFT(p.text, 100) as text, p.date,
                (SELECT COUNT(*) FROM comment WHERE post_id = p.id) AS comments_number
            FROM post as p
            ORDER BY p.date DESC
            LIMIT 5
        ');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
