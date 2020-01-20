<?php

namespace collection;

class authenticate extends action {

    public function get() {
        //var_dump($this->data); die;
        $username = $this->data->username;
        $password = $this->data->password;
        $user = \ORM::for_table('srps_users')->where('username', $username)->find_one();
        if (!$user) {
            return false;
        }
        $hash = $user->password;
        if (!password_verify($password, $hash)) {
            return false;
        }
        if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
            $user->password = password_hash($password, PASSWORD_DEFAULT);
            $user->save();
        }
      
        $userfields = $user->as_array();
        unset($userfields['password']);
        return json_encode($userfields);
    }
}
