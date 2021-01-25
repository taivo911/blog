<?php
session_start();

function message($name = '', $msg = '', $class = 'alert alert-success')
{
    if (!empty($name)) {
        if (!empty($msg)) {
            if (!empty($_SESSION[$name])) {
                unset($_SESSION[$name]); //kustutab session massiivi sisu
            }
            if (!empty($_SESSION[$name . '_class'])) {
                unset($_SESSION[$name . '_class']); //kustutab klassi sisu
            }
            $_SESSION[$name] = $msg;
            $_SESSION[$name . '_class'] = $class;

            // juhul kui on tyhi s6num ja samal ajal pole tyhi nime osa, siis
        } else if (empty($msg) and !empty($name)) {
            $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
            echo '<div class="' . $class . '" id="msg">' . $_SESSION[$name] . '</div>';
            // kustutan nime ja klassi
            unset($_SESSION[$name]);
            unset($_SESSION[$name . '_class']);
        }
    }
}