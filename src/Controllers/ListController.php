<?php namespace RobinCms\Core\Controllers;

use RobinCms\Core\Controller;

class ListController extends Controller
{

    public function showList($listKey)
    {
        $list = $this->lists->getList($listKey);

        if (!$list) {
            $this->session->setFlash('error', 'There is no list having the key "' . $listKey . '"');
            return $this->redirect($this->router->route('robin.dashboard'));
        }
        $data = [
            'list'         => $list,
            'current_menu' => 'list_' . $listKey,
            'list_key'     => $listKey
        ];

        return $this->view->render('@robin/list-items.tpl', $data);
    }


    public function showListItemEdit($listKey, $itemKey)
    {
        $list = $this->lists->getList($listKey);

        if (!$list) {
            $this->session->setFlash('error', 'There is no list having the key "' . $listKey . '"');
            return $this->redirect($this->router->route('robin.dashboard'));
        }

        // Check if we just changed locale and the current item doesn't have
        // a translated counter part.
        $isNew = !$this->lists->hasItem($listKey, $itemKey)
            && $this->session->getFlash('locale_changed') === true;

        $data = [
            'list'         => $list,
            'item_key'     => $itemKey,
            'is_new'       => $isNew,
            'current_menu' => 'list_' . $listKey,
        ];

        return $this->view->render('@robin/list-item-edit.tpl', $data);
    }


    public function addListItem($listKey)
    {
        $list = $this->lists->getList($listKey);

        if (!$list) {
            $this->session->setFlash('error', 'There is no list having the key "' . $listKey . '"');
            return $this->redirect($this->router->route('robin.dashboard'));
        }

        $index = uniqid("idx_");
        while($this->lists->hasItem($listKey, $index)) {
            $index = uniqid("idx_");
        }

        return $this->redirect($this->router->route('robin.list-item.edit', [$listKey, $index]));
    }


    public function updateListItem($listKey, $itemKey)
    {
        $csrf_token = arr_val($_POST, 'csrf_token');
        $response   = $this->jsonResponse();

        if (!$this->csrf->validateToken($csrf_token, 'edit-list-item' . $itemKey)) {
            // Invalid CSRF-token. Let's stop this madness. Return an error!
            return $response->setErrors(['Seems like the page have timed out.']);
        }

        if ($itemKey != arr_val($_POST, 'item_key')) {
            return $response->setErrors(['The request seems invalid. For security reasons, we won\'t update the item']);
        }

        
        $locale  = arr_val($_POST, 'locale');
        $listKey = arr_val($_POST, 'list.key');
        $fields  = arr_val($_POST, 'data');

        $errors = [];

        if (!$this->locales->exists($locale)) {
            $errors[] = "Invalid locale"; 
        }

        if (!$this->lists->hasList($listKey)) {
            $errors[] = "Invalid list";
        }

        if (!is_array($fields) || empty($fields)) {
            $errors[] = "Got not content to update";
        }

        if (!$errors) {
            if (!$this->lists->updateListItem($listKey, $itemKey, $fields)) {
                $errors[] = "Something went wrong. Content not updated";
            }
        }

        if ($errors) {
            $response->setErrors($errors);
        } else {
            $response->setMessage('List item updated!');
        }

        return $response;
    }

    public function deleteListItem($listKey, $itemKey)
    {
        $csrf_token = arr_val($_POST, 'csrf_token');
        $response   = $this->jsonResponse();

        if (!$this->csrf->validateToken($csrf_token, 'edit-list-item' . $itemKey)) {
            // Invalid CSRF-token. Let's stop this madness. Return an error!
            return $response->setErrors(['Seems like the page have timed out.']);
        }

        if ($itemKey != arr_val($_POST, 'item_key') || $listKey != arr_val($_POST, 'list.key')) {
            return $response->setErrors(['The request seems invalid. For security reasons, we won\'t update the item']);
        }
        
        $locale  = arr_val($_POST, 'locale');

        $errors = [];

        if (!$this->locales->exists($locale)) {
            $errors[] = "Invalid locale"; 
        }

        if (!$this->lists->hasList($listKey)) {
            $errors[] = "Invalid list";
        }

        if (!$errors) {
            if (!$this->lists->deleteListItem($listKey, $itemKey)) {
                $errors[] = "Something went wrong. Item not deleted.";
            }
        }

        if ($errors) {
            $response->setErrors($errors);
        } else {
            $this->session->setFlash('success', 'List item deleted');
            $response->setMessage('List item deleted!');
        }

        return $response;
    }


    public function updateItemsOrder($listKey)
    {
        $order      = arr_val($_POST, 'order', []);
        $csrf_token = arr_val($_POST, 'csrf_token');
        $response   = $this->jsonResponse();
        $locale     = arr_val($_POST, 'locale');

        if (!$this->csrf->validateToken($csrf_token, 'update-list-items-order' . $listKey)) {
            // Invalid CSRF-token. Let's stop this madness. Return an error!
            return $response->setErrors(['Seems like the page have timed out.']);
        }

        $errors = [];

        if (!$this->locales->exists($locale)) {
            $errors[] = "Invalid locale"; 
        }

        if (!$this->lists->hasList($listKey)) {
            $errors[] = "Invalid list";
        }

        if (!$errors) {
            if (!$this->lists->updateListItemsOrder($listKey, $order)) {
                $errors[] = "Something went wrong while saving the new list order.";
            }
        }

        if ($errors) {
            $response->setErrors($errors);
        } else {
            $response->setMessage('Items order updated!');
        }

        return $response;
    }
}
