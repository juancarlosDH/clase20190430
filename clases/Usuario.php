<?php

class Usuario implements \JsonSerializable {
    private $email;
    private $password;
    private $avatar;

    public function __construct($email, $password, $avatar){
        $this->email = $email;
        $this->password = $password;
        $this->avatar = $avatar;
    }

    public function getEmail(){
        return $this->email;
    }
    public function getAvatar(){
        return $this->avatar;
    }

    //esto hace que los atributos privados se puedan usar en json_encode
    public function jsonSerialize()
    {
        $vars = get_object_vars($this);

        return $vars;
    }
}


 ?>
