<?php 
namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController 
{
    public function login() 
    { 
        return view('auth/login'); 
    }

    public function register() 
    { 
        return view('auth/register'); 
    }

    public function storeRegister() 
    {
        // --- 1. VALIDATION ---
        $rules =[
            'name'     => 'required|min_length[3]',
            'email'    => 'required|valid_email|is_unique[users.email]', // Make sure email is unique
            'password' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            // If validation fails, redirect back to the form with errors
            return redirect()->to('/register')->withInput()->with('errors', $this->validator->getErrors());
        }

        // --- 2. SAVE TO DATABASE (If validation passes) ---
        $userModel = new UserModel();

        $data =[
            'name'     => $this->request->getVar('name'),
            'email'    => $this->request->getVar('email'),
            'password' => $this->request->getVar('password'), // No manual hash here, model does it
            'role'     => 'user' // Added the missing role
        ];

        // We check if the save was successful
        if ($userModel->save($data)) {
            return redirect()->to('/')->with('msg', 'Registration Successful! Please login.');
        } else {
            return redirect()->to('/register')->with('msg_error', 'An error occurred during registration.');
        }
    }

    public function loginAuth() 
    {
        $session = session();
        $userModel = new UserModel();
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        
        $data = $userModel->where('email', $email)->first();
        
        if($data){
            if(password_verify($password, $data['password'])){
                $ses_data = ['id'=>$data['id'], 'name'=>$data['name'], 'email'=>$data['email'], 'isLoggedIn'=>TRUE];
                $session->set($ses_data);

                // --- RECORD LOGIN LOG ---
                $activityModel = new \App\Models\UserActivityModel();
                $activityModel->insert([
                    'user_id' => $data['id'],
                    'action' => 'login',
                    'timestamp' => date('Y-m-d H:i:s'),
                    'ip_address' => $this->request->getIPAddress()
                ]);

                return redirect()->to('/dashboard');
            }
        }
        
        // Redirect back with an error message AND the old input
        return redirect()->back()->withInput()->with('err', 'Invalid Email or Password');
    }

    public function logout() 
    {
        // --- RECORD LOGOUT LOG ---
        if (session()->get('isLoggedIn')) {
            $activityModel = new \App\Models\UserActivityModel();
            $activityModel->insert([
                'user_id' => session()->get('id'),
                'action' => 'logout',
                'timestamp' => date('Y-m-d H:i:s'),
                'ip_address' => $this->request->getIPAddress()
            ]);
        }

        session()->destroy();
        return redirect()->to('/');
    }
}