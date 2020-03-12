<?php

namespace collection\action;

define('SESSION_LIFETIME', 7200);

class loggedin extends action {

    public function post() {
        $hash = $this->data->token;
error_log('GOT HERE - TOKEN = ' . token);
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
