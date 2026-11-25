<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\View;
use App\Models\Repositories\UserRepository;
use App\Services\UploadService;

class ProfileEditController
{
    private View $view;
    private UserRepository $userRepo;
    private UploadService $uploadService;

    public function __construct(View $view)
    {
        $this->view = $view;
        $this->userRepo = new UserRepository();
        $this->uploadService = new UploadService();
    }

    // Форма редактирования профиля (GET /profile/edit)
    public function editForm(Request $request, array $params): void
    {
        $userId = Session::userId();
        $user = $this->userRepo->findById($userId);

        $this->view->render('pages/profile_edit', [
            'user' => $user,
            'error' => Session::flash('error'),
            'success' => Session::flash('success'),
            'title' => 'Редактирование профиля — GameFolio'
        ]);
    }

    // Обработка редактирования профиля (POST /profile/edit)
    public function update(Request $request, array $params): void
    {
        $userId = Session::userId();
        $user = $this->userRepo->findById($userId);

        $nickname = trim((string) $request->post('nickname'));
        $bio = trim((string) $request->post('bio'));

        // Валидация
        if (strlen($nickname) < 2 || strlen($nickname) > 100) {
            Session::flash('error', 'Никнейм должен быть от 2 до 100 символов');
            Response::redirect('/profile/edit');
            return;
        }

        $updateData = [
            'nickname' => $nickname,
            'bio' => $bio ?: null,
        ];

        // Загрузка аватарки
        if (!empty($_FILES['avatar']['name'])) {
            $avatarPath = $this->uploadService->uploadFile($_FILES['avatar'], 'avatars');
            if ($avatarPath) {
                // Удаляем старый аватар
                if ($user->avatar_url) {
                    $this->uploadService->deleteFile($user->avatar_url);
                }
                $updateData['avatar_url'] = $avatarPath;
            } else {
                Session::flash('error', 'Ошибка загрузки аватарки. Проверьте формат (JPG, PNG, GIF, WebP) и размер (до 5 MB)');
                Response::redirect('/profile/edit');
                return;
            }
        }

        // Загрузка баннера
        if (!empty($_FILES['banner']['name'])) {
            $bannerPath = $this->uploadService->uploadFile($_FILES['banner'], 'banners');
            if ($bannerPath) {
                // Удаляем старый баннер
                if ($user->banner_url) {
                    $this->uploadService->deleteFile($user->banner_url);
                }
                $updateData['banner_url'] = $bannerPath;
            } else {
                Session::flash('error', 'Ошибка загрузки баннера. Проверьте формат (JPG, PNG, GIF, WebP) и размер (до 5 MB)');
                Response::redirect('/profile/edit');
                return;
            }
        }

        $this->userRepo->updateProfile($userId, $updateData);

        // Обновляем никнейм в сессии
        Session::set('user_nickname', $nickname);

        Session::flash('success', 'Профиль успешно обновлён');
        Response::redirect('/profile/edit');
    }
}