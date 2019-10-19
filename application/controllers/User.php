<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model', 'user');
        is_logged_in();
    }

    public function index()
    {
        $data['title'] = 'My Profile';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('user/index', $data);
        $this->load->view('templates/footer');
    }

    public function edit()
    {

        $data['title'] = 'Edit Profile';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $this->form_validation->set_rules('name', 'Full Name', 'required|trim');

        if ($this->form_validation->run() == false) {

            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('user/edit', $data);
            $this->load->view('templates/footer');
        } else {
            $name = $this->input->post('name');
            $email = $this->input->post('email');

            //check if user upload image
            $upload_image = $_FILES['image']['name'];
            if ($upload_image) {
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size']     = '4096';
                $config['upload_path'] = './assets/img/profile';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {
                    $old_image = $data['user']['image'];
                    if ($old_image != 'default.png') {
                        unlink(FCPATH . 'assets/img/profile/' . $old_image);
                    }

                    $new_image = $this->upload->data('file_name');
                    $this->db->set('image', $new_image);
                } else {
                    $this->session->set_flashdata('message_danger',  $this->upload->display_errors());
                    redirect('user/edit');
                }
            }
            $this->user->updateUser($name, $email);
            $this->session->set_flashdata('message_success', 'Your profile has been <strong>updated</strong>');
            redirect('user/edit');
        }
    }

    public function changePassword()
    {
        $data['title'] = 'Change Password';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $this->form_validation->set_rules('old_password', 'Old Password', 'required|trim');
        $this->form_validation->set_rules('new_password', 'New Password', 'required|trim|min_length[8]');
        $this->form_validation->set_rules('repeatnew_password', 'Repeat New Password', 'required|trim|matches[new_password]');

        if ($this->form_validation->run() == false) {

            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('user/changepassword', $data);
            $this->load->view('templates/footer');
        } else {
            $oldpassword = $this->input->post('old_password');
            $new_password = $this->input->post('new_password');
            if (!password_verify($oldpassword, $data['user']['password'])) {
                $this->session->set_flashdata('message_danger', 'Your old password isn\'t <strong>correct !</strong>');
                redirect('user/changepassword');
            } else {
                if ($oldpassword == $new_password) {
                    $this->session->set_flashdata('message_danger', 'new password <strong>cannot be the same</strong> as old password!');
                    redirect('user/changepassword');
                } else {
                    //password ok
                    $session_email = $this->session->userdata('email');
                    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                    $this->user->updatePassword($password_hash, $session_email);
                    $this->session->set_flashdata('message_success', 'Your password has been <strong>changed</strong> !');
                    redirect('user/changepassword');
                }
            }
        }
    }
}
