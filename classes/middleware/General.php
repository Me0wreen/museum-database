<?php

namespace classes\middleware;

class General
{
    private function verifySession(): void {
        if (isset($_SESSION["admin"])) {
            if ($_SERVER['REMOTE_ADDR'] != $_SESSION["admin"]["ip"]) {
                unset($_SESSION["admin"]);
            }

            if ($_SERVER['HTTP_USER_AGENT'] != $_SESSION["admin"]["agent"]) {
                unset($_SESSION["admin"]);
            }

            if (time() > ($_SESSION["admin"]["last_access"] + 3600)) {
                unset($_SESSION["admin"]);
            }
            else {
                $_SESSION["admin"]["last_access"] = time();
            }
        }
    }

    public function authenticateUser(): void {
        //Check for session hijacking
        $this->verifySession();
        //Check if session is still available
        if (!isLoggedIn()) {
            redirect("/");
        }
    }
}