<?php

namespace App\Http\Controller;

class GitController extends AbstractController
{
    public function index()
    {
        $this->app->view()->setHeader(__('deploy'));
        
        return $this->view->render('git/index.blade.php', [
            'list' => $this->app->directory()->allData(),
        ]);
    }

    public function update()
    {
        $dir = $this->app->getRequest()->getParam('dir');

        return $this->app->json([
            'data' => $this->app->directory()->update($dir)
        ]);
    }

    public function checkout()
    {
        $dir = $this->app->getRequest()->getParam('dir');
        $branch = $this->app->getRequest()->getParam('branch', '');

        return $this->app->json([
            'data' => $this->app->directory()->checkout($dir, $branch)
        ]);
    }

    public function reset()
    {
        $dir = $this->app->getRequest()->getParam('dir');

        $this->app->json(array(
            'data' => $this->app->directory()
                ->fix($dir, $this->app->getRequest()->getParam('doClean', false))
        ));
    }

    public function showAddRepositoryForm()
    {
        $this->setTitle(__('deploy'));
        $this->setSubTitle(__('add_repository'));

        $this->view->render('git/addRepositoryForm.blade.php');
    }

    public function addRepository()
    {
        // SSH link like: git@github.com:janson-git/deploy.git
        // HTTPS url like: https://github.com/janson-git/deploy.git

        $repoPath = $this->p('repository_path');
        $repoPath = preg_replace('#[^a-zA-Z0-9:@./\-]#', '', $repoPath);

        $repoNameFull = mb_substr($repoPath, strrpos($repoPath, '/') + 1);
        $dirName = str_replace('.git', '', $repoNameFull);

        try {
            $output = $this->app->directory()->cloneRepository($repoPath, $dirName);
        } catch (\Exception $e) {
            $output = $e->getMessage();
            return $this->app->json(['data' => $output], 500);
        }

        return $this->app->json(['data' => $output]);
    }
}
