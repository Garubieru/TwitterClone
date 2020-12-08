<?php

namespace backend\Models;

use MF\Model\Model;

class User extends Model
{
  private $id;
  private $name;
  private $username;
  private $email;
  private $password;

  public function __get($attr)
  {
    return $this->$attr;
  }

  public function __set($attr, $val)
  {
    $this->$attr = $val;
  }

  // Register
  public function registerUser()
  {
    $query = "
      INSERT INTO 
        users(name, username, email, password)
        VALUES(:name, :username, :email, :password);";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':name', $this->__get('name'));
    $stmt->bindValue(':username', $this->__get('username'));
    $stmt->bindValue(':email', $this->__get('email'));
    $stmt->bindValue(':password', md5($this->__get('password')));

    $stmt->execute();

    return $this;
  }

  // Authenticate user
  public function authUser()
  {
    $query = "
    SELECT id, name, username, email FROM users 
    WHERE (email = :email OR username = :username) AND password = :password";
    $stmt = $this->db->prepare($query);

    $stmt->bindValue(':email', $this->__get('email'));
    $stmt->bindValue(':username', $this->__get('username'));
    $stmt->bindValue(':password', md5($this->__get('password')));

    $stmt->execute();
    $user = $stmt->fetch(\PDO::FETCH_OBJ);

    return $user;
  }

  public function recoverUser()
  {
    $query = "
    SELECT 
      u.id, 
      u.name, 
      u.username,
      (
        SELECT
          count(*)
        FROM
          user_follows as uf
        WHERE
          uf.user_id = :user_id and uf.follow_id = u.id
      ) as following
    FROM 
      users as u
    WHERE 
      u.username like :username AND id != :user_id";

    $stmt = $this->db->prepare($query);

    $stmt->bindValue(':username', '%' . $this->__get('username') . '%');
    $stmt->bindValue(':user_id', $this->__get('id'));
    $stmt->execute();

    $user = $stmt->fetchAll(\PDO::FETCH_OBJ);

    return $user;
  }

  public function recoverAllUsers()
  {
    $query = "
    SELECT 
      u.id, 
      u.name, 
      u.username,
      (
        SELECT
          count(*)
        FROM
          user_follows as uf
        WHERE
          uf.user_id = :user_id and uf.follow_id = u.id
      ) as following
    FROM 
      users as u
    WHERE 
      u.id != :user_id";

    $stmt = $this->db->prepare($query);

    $stmt->bindValue(':user_id', $this->__get('id'));
    $stmt->execute();

    $users = $stmt->fetchAll(\PDO::FETCH_OBJ);

    return $users;
  }

  public function followUser($follow_id)
  {
    $query = "
    INSERT INTO user_follows(user_id, follow_id) VALUES(
      :user_id,
      :follow_id
    )";

    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':user_id', $this->__get('id'));
    $stmt->bindValue(':follow_id', $follow_id);

    $stmt->execute();

    return true;
  }

  public function unfollowUser($follow_id)
  {
    $query = "
    DELETE FROM user_follows WHERE user_id = :user_id AND follow_id = :follow_id";

    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':user_id', $this->__get('id'));
    $stmt->bindValue(':follow_id', $follow_id);

    $stmt->execute();

    return true;
  }

  // Validate user
  public function validRegisters()
  {
    $name = $this->__get('name');
    $username = $this->__get('username');
    $email = $this->__get('email');
    $password = $this->__get('password');

    if (
      (strlen($name) < 3 || empty($name)) ||
      (strlen($username) < 3 || empty($username)) ||
      (strlen($email) < 3 || empty($email)) ||
      (strlen($password) < 3 || empty($password))
    ) {
      return False;
    } else {
      return True;
    }
  }

  // Recover user by email
  public function validEmail()
  {
    $query = "SELECT email FROM users WHERE email = :email";
    $stmt = $this->db->prepare($query);

    $stmt->bindValue(':email', $this->__get('email'));
    $stmt->execute();

    $registeredEmail = $stmt->fetch(\PDO::FETCH_OBJ);

    if ($registeredEmail) {
      return False;
    } else {
      return True;
    }
  }

  public function validUsername()
  {
    $query = "SELECT username FROM users WHERE username = :username";
    $stmt = $this->db->prepare($query);

    $stmt->bindValue(':username', $this->__get('username'));
    $stmt->execute();

    $registeredUsername = $stmt->fetch(\PDO::FETCH_OBJ);

    if ($registeredUsername) {
      return False;
    } else {
      return True;
    }
  }
}
