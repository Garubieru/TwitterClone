<?php

namespace backend\Models;

use MF\Model\Model;

class Tweet extends Model
{
  private $id;
  private $user_id;
  private $tweet;
  private $time;

  public function __get($attr)
  {
    return $this->$attr;
  }

  public function __set($attr, $val)
  {
    $this->$attr = $val;
  }

  public function insertTweet()
  {
    $query = "
    INSERT INTO tweets(user_id, tweet) VALUES(:user_id, :tweet);";

    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':user_id', $this->__get('user_id'));
    $stmt->bindValue(':tweet', $this->__get('tweet'));

    $stmt->execute();

    return $this;
  }

  public function recoverTweets()
  {
    $query = "
    SELECT 
      u.name, 
      u.username, 
      t.tweet, 
      DATE_FORMAT(t.time, '%d/%m/%Y %H:%i') as time
    FROM 
      users as u 
    RIGHT JOIN 
      tweets as t 
    ON 
      (u.id = t.user_id) 
    WHERE
      t.user_id 
      IN 
        (SELECT 
          uf.follow_id 
        FROM 
          user_follows as uf 
        WHERE 
          uf.user_id = :user_id)
      OR
        t.user_id = :user_id
    ORDER BY 
      time DESC";

    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':user_id', $this->__get('user_id'));
    $stmt->execute();

    $tweets = $stmt->fetchAll(\PDO::FETCH_OBJ);

    return $tweets;
  }
}
