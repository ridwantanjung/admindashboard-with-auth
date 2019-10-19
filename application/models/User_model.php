<?php
defined('BASEPATH') or exit('No direct script access allowed');
class User_model extends CI_Model
{
    public function updateUser($name, $email)
    {
        $this->db->set('name', $name);
        $this->db->where('email', $email);
        $this->db->update('user');
    }

    public function updatePassword($password_hash, $session_email)
    {
        $this->db->set('password', $password_hash);
        $this->db->where('email', $session_email);
        $this->db->update('user');
    }
}
