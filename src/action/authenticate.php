<?php

namespace collection\action;

class authenticate extends action {

    /**
     * Set new token for this $user
     * Delete any existing ones (as 'new' login)
     */
    protected function token($user) {
        \ORM::for_table('token')->where('user', $user->id)->delete_many();
        $token = \ORM::for_table('token')->create();
        $token->user = $user->id;
        $token->token = sha1($user->email . time() . rand(0, 999999));
        $token->timemodified = time();
        $token->save();

        return $token->token;
    }

    public function post() {
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
        $userfields['token'] = $this->token($user);
        unset($userfields['password']);
        return json_encode($userfields);
    }
}
