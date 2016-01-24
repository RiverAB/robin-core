<?php namespace RobinCms\Core\Controllers;

use RobinCms\Core\Controller;

class FilesController extends Controller
{
    public function __construct()
    {
        $this->view->addGlobal('current_menu', 'files');
    }

    public function showFiles()
    {
        $data = [
            'files'         => $this->files->getFiles(),
            'show_previews' => arr_val($_GET, 'previews'),
        ];

        return $this->view->render('@robin/files.tpl', $data);
    }

    public function getFiles()
    {
        $response = $this->jsonResponse();
        return $response->setData($this->files->getFiles());
    }

    public function showFileBrowser()
    {
        $response = $this->jsonResponse();

        $data = [
            'files'  => $this->files->getFiles(),
            'status' => arr_val($_GET, 'status', null),
            'id'     => arr_val($_GET, 'id', null),
        ];

        return $response->setData(
            $this->view->render('@robin/partials/file-browser.tpl', $data)
        );
    }

    public function upload()
    {
        $response = $this->jsonResponse();

        if (array_key_exists('uploadfile', $_FILES) && is_array($_FILES['uploadfile'])) {
            $error = $this->files->uploadFile($_FILES['uploadfile']);
        }

        if ($error) {
            $response->setError($error);
        }

        return $response;
    }

    public function delete()
    {
        $response = $this->jsonResponse();

        $csrf_token = arr_val($_POST, 'csrf_token');
        $file       = arr_val($_POST, 'file');

        if (!$this->csrf->validateToken($csrf_token, 'delete-file')) {
            // Invalid CSRF-token. Let's stop this madness. Return an error!
            return $response->setErrors(['Seems like the page have timed out.']);
        }

        $error = $this->files->deleteFile($file);

        if ($error) {
            $response->setError($error);
        }

        return $response;
    }

}
