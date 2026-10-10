<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Users extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();

        return view('users', [
            'users' => $userModel
                ->orderBy('id', 'DESC')
                ->findAll(),
        ]);
    }

    public function new()
    {
        helper('form');

        return view('user_form');
    }

    public function create()
    {
        helper('form');

        $rules = [
            'username' => [
                'label' => 'Username',
                'rules' =>
                    'required|min_length[3]|max_length[50]|' .
                    'regex_match[/^[a-zA-Z0-9._]+$/]|' .
                    'is_unique[users.username]',
            ],
            'full_name' => [
                'label' => 'Full Name',
                'rules' =>
                    'required|min_length[3]|max_length[100]',
            ],
            'password' => [
                'label' => 'Password',
                'rules' =>
                    'required|min_length[8]|max_length[255]',
            ],
            'password_confirm' => [
                'label' => 'Confirm Password',
                'rules' => 'required|matches[password]',
            ],
        ];

        $avatar = $this->request->getFile('avatar');

        if (
            $avatar &&
            $avatar->getError() !== UPLOAD_ERR_NO_FILE
        ) {
            $rules['avatar'] = [
                'label' => 'Avatar',
                'rules' =>
                    'uploaded[avatar]|is_image[avatar]|' .
                    'mime_in[avatar,image/jpg,image/jpeg,image/png]|' .
                    'max_size[avatar,2048]',
            ];
        }

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'errors',
                    $this->validator->getErrors()
                );
        }

        $avatarName = null;

        if (
            $avatar &&
            $avatar->getError() !== UPLOAD_ERR_NO_FILE &&
            $avatar->isValid() &&
            !$avatar->hasMoved()
        ) {
            $uploadPath = FCPATH . 'uploads/avatars';

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $avatarName = $avatar->getRandomName();

            service('image')
                ->withFile($avatar->getTempName())
                ->fit(300, 300, 'center')
                ->save(
                    $uploadPath .
                    DIRECTORY_SEPARATOR .
                    $avatarName
                );
        }

        $userModel = new UserModel();

        $userModel->insert([
            'username' =>
                strtolower(
                    trim($this->request->getPost('username'))
                ),
            'full_name' =>
                trim($this->request->getPost('full_name')),
            'password' =>
                password_hash(
                    $this->request->getPost('password'),
                    PASSWORD_DEFAULT
                ),
            'avatar' => $avatarName,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()
            ->to('/users')
            ->with(
                'success',
                'User added successfully.'
            );
    }

    public function edit($id)
    {
        helper('form');

        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            throw PageNotFoundException::forPageNotFound(
                'User record not found.'
            );
        }

        return view('user_edit', [
            'user' => $user,
        ]);
    }

    public function update($id)
    {
        helper('form');

        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            throw PageNotFoundException::forPageNotFound(
                'User record not found.'
            );
        }

        $rules = [
            'username' => [
                'label' => 'Username',
                'rules' =>
                    'required|min_length[3]|max_length[50]|' .
                    'regex_match[/^[a-zA-Z0-9._]+$/]|' .
                    "is_unique[users.username,id,{$id}]",
            ],
            'full_name' => [
                'label' => 'Full Name',
                'rules' =>
                    'required|min_length[3]|max_length[100]',
            ],
        ];

        $avatar = $this->request->getFile('avatar');

        if (
            $avatar &&
            $avatar->getError() !== UPLOAD_ERR_NO_FILE
        ) {
            $rules['avatar'] = [
                'label' => 'Avatar',
                'rules' =>
                    'uploaded[avatar]|is_image[avatar]|' .
                    'mime_in[avatar,image/jpg,image/jpeg,image/png]|' .
                    'max_size[avatar,2048]',
            ];
        }

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'errors',
                    $this->validator->getErrors()
                );
        }

        $updateData = [
            'username' =>
                strtolower(
                    trim($this->request->getPost('username'))
                ),
            'full_name' =>
                trim($this->request->getPost('full_name')),
        ];

        if (
            $avatar &&
            $avatar->getError() !== UPLOAD_ERR_NO_FILE &&
            $avatar->isValid() &&
            !$avatar->hasMoved()
        ) {
            $uploadPath = FCPATH . 'uploads/avatars';

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $newName = $avatar->getRandomName();

            service('image')
                ->withFile($avatar->getTempName())
                ->fit(300, 300, 'center')
                ->save(
                    $uploadPath .
                    DIRECTORY_SEPARATOR .
                    $newName
                );

            if (!empty($user['avatar'])) {
                $oldAvatarPath =
                    $uploadPath .
                    DIRECTORY_SEPARATOR .
                    $user['avatar'];

                if (is_file($oldAvatarPath)) {
                    unlink($oldAvatarPath);
                }
            }

            $updateData['avatar'] = $newName;
        }

        $userModel->update($id, $updateData);

        return redirect()
            ->to('/users')
            ->with(
                'success',
                'User updated successfully.'
            );
    }

    public function delete($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            throw PageNotFoundException::forPageNotFound(
                'User record not found.'
            );
        }

        if ((int) session()->get('user_id') === (int) $id) {
            return redirect()
                ->to('/users')
                ->with(
                    'error',
                    'You cannot delete your own account while logged in.'
                );
        }

        $userModel->delete($id);

        if (!empty($user['avatar'])) {
            $avatarPath =
                FCPATH .
                'uploads/avatars' .
                DIRECTORY_SEPARATOR .
                $user['avatar'];

            if (is_file($avatarPath)) {
                unlink($avatarPath);
            }
        }

        return redirect()
            ->to('/users')
            ->with(
                'success',
                'User deleted successfully.'
            );
    }
}