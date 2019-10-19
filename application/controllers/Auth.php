<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }
    public function index()
    {
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');
        if ($this->form_validation->run() == false) {

            $data['title'] = 'MyWeb - Login';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/login');
            $this->load->view('templates/auth_footer');
        } else {
            $this->_login();
        }
        //block if already have session but user wanna acces registration page
        if ($this->session->userdata('role_id') == 1) {
            redirect('admin');
        } else if ($this->session->userdata('role_id') == 2) {
            redirect('user');
        }
    }

    private function _login()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $user = $this->db->get_where('user', ['email' => $email])->row_array();
        // user exist
        if ($user) {
            //if user active
            if ($user['is_active'] == 1) {
                //check password
                if (password_verify($password, $user['password'])) {
                    $data = [
                        'email' => $user['email'],
                        'role_id' => $user['role_id']
                    ];
                    $this->session->set_userdata($data);
                    if ($user['role_id'] == 1) {
                        redirect('admin');
                    } else {
                        redirect('user');
                    }
                } else {
                    $this->session->set_flashdata('message_danger', 'Password incorrect!');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message_danger', 'Your account hasn\'t been activated yet! check your email to activate');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message_danger', 'Your email address is not registered! Please register <a href="auth/registration" class="alert-link">here</a>');
            redirect('auth');
        }

        if (!$this->session->userdata($data)) {
            $this->session->set_flashdata('message_success', 'you has been logged out, please login again');
            redirect('auth');
        }
    }

    public function registration()
    {
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
            'is_unique' => 'This email address is already registered!'
        ]);
        $this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[8]|matches[repeatpassword]', [
            'matches' => 'Password do not match!',
            'min_length' => 'Password to short! , required 8 character or more'
        ]);
        $this->form_validation->set_rules('repeatpassword', 'Password', 'required|trim|matches[password]');
        if ($this->form_validation->run() == false) {

            $data['title'] = 'MyWeb - Registration';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/registration');
            $this->load->view('templates/auth_footer');
        } else {
            $email = $this->input->post('email', true);
            $data = [
                'name' =>  htmlspecialchars($this->input->post('name', true)),
                'email' => htmlspecialchars($email),
                'image' => 'default.png',
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'role_id' => 2,
                'is_active' => 0,
                'date_created' => time()

            ];

            // SIAPKAN TOKEN
            $token = base64_encode(random_bytes(32));
            $user_token = [
                'email' => $email,
                'token' => $token,
                'date_created' => time()
            ];

            $this->db->insert('user', $data);
            $this->db->insert('user_token', $user_token);

            $this->_sendEmail($token, 'verify');

            $this->session->set_flashdata('message_success', 'Congratulation! your accoount has been created, Please check your email to activate. <br> <small style="color:red;"> link will expired in 4 hours </small>');
            redirect('auth');
        }
        //block if already have session but user wanna acces registration page
        if ($this->session->userdata('role_id') == 1) {
            redirect('admin');
        } else if ($this->session->userdata('role_id') == 2) {
            redirect('user');
        }
    }

    private function _sendEmail($token, $type)
    {
        $config = [
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_user' => 'tanjungridwan152@gmail.com',
            'smtp_pass' => '085711983036',
            'smtp_port' => 465,
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'newline' => "\r\n"
        ];

        $this->load->library('email', $config);
        $this->email->initialize($config);

        $this->email->from('tanjungridwan152@gmail.com', 'CI MyWeb');
        $this->email->to($this->input->post('email'));
        if ($type == 'verify') {

            $this->email->subject('Account Verification');
            $this->email->message('Click this link to verify your account : 
                <a href="' . base_url('auth/verify') . '?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Activate</a>');
        } elseif ($type == 'forgotpassword') {

            $this->email->subject('Reset Password');
            $this->email->message('Click this link to reset your password : 
                <a href="' . base_url('auth/resetpassword') . '?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Reset Password</a>');
        }

        if ($this->email->send()) {
            return true;
        } else {
            echo $this->email->print_debugger();
            die;
        }
    }
    // ============================ for _sendEmail $type ==============================
    public function verify()
    {
        $email =  $this->input->get('email');
        $token = $this->input->get('token');

        $user = $this->db->get_where('user', ['email' => $email])->row_array();

        if ($user) {

            $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();
            if ($user_token) {
                if (time() - $user_token['date_created'] < 14400) {

                    $this->db->set('is_active', 1);
                    $this->db->where('email', $email);
                    $this->db->update('user');

                    $this->db->delete('user_token', ['email' => $email]);

                    $this->session->set_flashdata('message_success', '<strong>Congratulation!</strong> <br> ' . $email . ' has been <strong>activated</strong>, please login');
                    redirect('auth');
                } else {

                    $this->db->delete('user', ['email' => $email]);
                    $this->db->delete('user_token', ['email' => $email]);

                    $this->session->set_flashdata('message_danger', '<strong>Failed</strong> to activate account, token expired.');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message_danger', '<strong>Failed</strong> to activate account, invalid token.');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message_danger', '<strong>Failed</strong> to activate account, no such registered email.');
            redirect('auth');
        }
    }

    public function resetpassword()
    {
        $email = $this->input->get('email');
        $token = $this->input->get('token');

        $user =  $this->db->get_where('user', ['email' => $email])->row_array();

        if ($user) {
            $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();
            if ($user_token) {
                if (time() - $user_token['date_created'] < 14400) {

                    $this->session->set_userdata('reset_email', $email);
                    $this->changePassword();

                    $this->db->delete('user_token', ['email' => $email]);
                } else {

                    $this->session->set_flashdata('message_danger', '<strong>Failed</strong> to reset password, token expired.');
                    redirect('auth');
                    $this->db->delete('user_token', ['email' => $email]);
                }
            } else {
                $this->session->set_flashdata('message_danger', '<strong>Failed</strong> to reset password, token expired/invalid.');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message_danger', '<strong>Failed</strong> to reset password, no such registered email.');
            redirect('auth');
        }
    }

    // ============================ END for _sendEmail $type ==============================

    public function logout()
    {
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('role_id');

        $this->session->set_flashdata('message_success', 'You have successfully logged out.');
        redirect('auth');
    }

    public function blocked()
    {
        $data['title'] = 'Access forbidden - 403';
        $this->load->view('templates/header', $data);
        $this->load->view('auth/blocked');
        $this->load->view('templates/footer');
    }

    public function forgotPassword()
    {
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        if ($this->form_validation->run() == false) {

            $data['title'] = 'MyWeb - Forgot Password';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/forgot-password');
            $this->load->view('templates/auth_footer');
        } else {
            $email = $this->input->post('email');
            if ($this->db->get_where('user', ['email' => $email, 'is_active' => 1])->row_array()) {

                $token = base64_encode(random_bytes(32));
                $user_token = [
                    'email' => $email,
                    'token' => $token,
                    'date_created' => time()
                ];
                $this->db->insert('user_token', $user_token);
                $this->_sendEmail($token, 'forgotpassword');

                $this->session->set_flashdata('message_success', 'Please check your email to reset password <br> <small style="color:red;"> link will expired in 4 hours </small>');
                redirect('auth/forgotpassword');
            } elseif ($this->db->get_where('user', ['email' => $email, 'is_active' => 0])->row_array()) {

                $this->session->set_flashdata('message_danger', 'email is not active yet!');
                redirect('auth/forgotpassword');
            } else {

                $this->session->set_flashdata('message_danger', 'Email not registered!');
                redirect('auth/forgotpassword');
            }
        }
    }

    public function changePassword()
    {
        if (!$this->session->userdata('reset_email')) {
            redirect('auth');
        }
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
        $this->form_validation->set_rules('repeatpassword', 'Repeat Password', 'trim|required|matches[password]');
        if ($this->form_validation->run() == false) {

            $data['title'] = 'MyWeb - Change Password';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/change-password');
            $this->load->view('templates/auth_footer');
        } else {
            $password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
            $email = $this->session->userdata('reset_email');

            $this->db->set('password', $password);
            $this->db->where('email', $email);
            $this->db->update('user');

            $this->session->unset_userdata('reset_email');
            $this->session->set_flashdata('message_success', '<strong>Congratulation !</strong><br> Your password has been changed, please login.');
            redirect('auth');
        }
    }
}
