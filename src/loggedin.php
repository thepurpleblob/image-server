<?php

namespace collection;

define('SESSION_LIFETIME', 7200);

class loggedin extends action {

    public function get() {
        $hash = $this->data->token;
        $token = \ORM::for_table('token')->where('token', $hash)->find_one();
        if (!$token) {
            return false;
        } else {
            $elapsed = time() - $token->timemodified;
            if ($elapsed > SESSION_LIFETIME) {
                $token->delete();
                return false;
            }
            $token->timemodified = time();
            $token->save();
            return true;
        }
    }
}
