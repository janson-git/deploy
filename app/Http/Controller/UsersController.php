<?php

namespace App\Http\Controller;

class UsersController extends AbstractController
{
    public function index()
    {
        $this->setTitle($this->app->getAuth()->getUserName());
        $this->setSubTitle('@' . $this->app->getAuth()->getUserLogin());

        return $this->view->render('users/index.blade.php', [
            'sshKeyUploaded' => file_exists('ssh_keys/'. $this->app->getAuth()->getUserLogin()),
        ]);
    }
    
    public function addkey()
    {
        $this->setTitle(__('set_ssh_key'));
        
        $text = __('ssh_key_page_description');
        
        if ($this->request->isPost()) {
            $key = $this->p('key');
            $key = str_replace("\r\n", "\n", trim($key)) . "\n";
            $filename = 'ssh_keys/'. $this->app->getAuth()->getUserLogin();

            if ($key && file_put_contents($filename, $key) !== false) {
                chmod($filename, 0600);
                $text = __('ssh_key_saved_successfully');
            } else {
                $text = __('set_ssh_save_failed');
            }
        }
        
        return $this->view->render('users/addkey.blade.php', [
            'msg' => $text,
        ]);
    }

    public function committerInfo()
    {
        $this->setTitle(__('set_committer'));

        if ($this->request->isPost()) {
            $name = $this->p('name');
            $email = $this->p('email');

            $pattern = "#[^a-zA-Z0-9@\s]+#";
            $name = preg_filter($pattern, '', $name);
            $email = preg_filter($pattern, '', $email);

            $user = $this->app->getAuth()->getUser();
            $user->setCommitAuthorName($name);
            $user->setCommitAuthorEmail($email);

            $user->save();

            return $this->response->withRedirect('/user');
        }

        return $this->view->render('users/setCommitterForm.blade.php');
    }
}
