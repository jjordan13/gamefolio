<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\View;
use App\Models\Repositories\GameRepository;
use App\Services\UploadService;
use App\Models\Repositories\CommentRepository;
use App\Models\Repositories\LikeRepository;

class GameController
{
    private View $view;
    private GameRepository $gameRepo;
    private UploadService $uploadService;
    private CommentRepository $commentRepo;
    private LikeRepository $likeRepo;


    public function __construct(View $view)
    {
        $this->view = $view;
        $this->gameRepo = new GameRepository();
        $this->uploadService = new UploadService();
        $this->commentRepo = new CommentRepository();
        $this->likeRepo = new LikeRepository();
    }

    // Список всех игр (GET /games)
    public function index(Request $request, array $params): void
    {
        $games = $this->gameRepo->findAllWithAuthor();
        $this->view->render('pages/games', [
            'games' => $games,
            'title' => 'Все игры — GameFolio'
        ]);
    }

    // Страница одной игры (GET /games/{id})
    public function show(Request $request, array $params): void
    {
        $id = (int) $params['id'];
        $game = $this->gameRepo->findWithDetails($id);

        if (!$game) {
            echo "Игра не найдена. <a href='/games'>Назад к списку</a>";
            return;
        }

        $comments = $this->commentRepo->findByGame($id);
        $likeCount = $this->likeRepo->countByGame($id);
        $isLiked = false;
        $userId = Session::userId();
        if ($userId !== null) {
            $isLiked = $this->likeRepo->isLikedByUser($id, $userId);
        }

        $this->view->render('pages/game_details', [
            'game' => $game,
            'comments' => $comments,
            'likeCount' => $likeCount,
            'isLiked' => $isLiked,
            'success' => Session::flash('success'),
            'error' => Session::flash('error'),
            'title' => $game['title'] . ' — GameFolio'
        ]);
    }

    // Форма добавления игры (GET /games/create)
    public function createForm(Request $request, array $params): void
    {
        $this->view->render('pages/game_create', [
            'error' => Session::flash('error'),
            'title' => 'Добавить игру — GameFolio'
        ]);
    }

    // Обработка добавления игры (POST /games/create)
    public function create(Request $request, array $params): void
    {
        $userId = Session::userId();
        $title = trim((string) $request->post('title'));

        if ($title === '' || mb_strlen($title) > 255) {
            Session::flash('error', 'Название игры обязательно и не длиннее 255 символов');
            Response::redirect('/games/create');
            return;
        }

        $gameId = $this->gameRepo->create(
            $userId,
            $title,
            trim((string) $request->post('short_description')),
            trim((string) $request->post('full_description')),
            trim((string) $request->post('engine')),
            trim((string) $request->post('genre'))
        );

        if (!empty($_FILES['media']['name'][0])) {
            $paths = $this->uploadService->uploadMultipleFiles($_FILES['media'], 'games');
            foreach ($paths as $i => $path) {
                $this->gameRepo->addMedia($gameId, $path, 'image', $i, $i === 0);
            }
        }

        Session::flash('success', 'Игра успешно добавлена');
        Response::redirect('/games/' . $gameId);
    }


    // Форма редактирования игры (GET /games/{id}/edit)
    public function editForm(Request $request, array $params): void
    {
        $gameId = (int) $params['id'];
        $userId = Session::userId();

        // Проверяем, что пользователь является владельцем игры
        if (!$this->gameRepo->isOwner($gameId, $userId)) {
            Session::flash('error', 'Вы не можете редактировать эту игру');
            Response::redirect('/games/' . $gameId);
            return;
        }

        $game = $this->gameRepo->findWithDetails($gameId);

        $this->view->render('pages/game_edit', [
            'game' => $game,
            'error' => Session::flash('error'),
            'success' => Session::flash('success'),
            'title' => 'Редактировать игру — GameFolio'
        ]);
    }

    // Обработка редактирования игры (POST /games/{id}/edit)
    public function update(Request $request, array $params): void
    {
        $gameId = (int) $params['id'];
        $userId = Session::userId();

        if (!$this->gameRepo->isOwner($gameId, $userId)) {
            Session::flash('error', 'Вы не можете редактировать эту игру');
            Response::redirect('/games/' . $gameId);
            return;
        }

        $title = trim((string) $request->post('title'));
        if ($title === '' || mb_strlen($title) > 255) {
            Session::flash('error', 'Название игры обязательно и не длиннее 255 символов');
            Response::redirect('/games/' . $gameId . '/edit');
            return;
        }

        $this->gameRepo->update($gameId, [
            'title' => $title,
            'short_description' => trim((string) $request->post('short_description')) ?: null,
            'full_description' => trim((string) $request->post('full_description')) ?: null,
            'engine' => trim((string) $request->post('engine')) ?: null,
            'genre' => trim((string) $request->post('genre')) ?: null,
        ]);

        // Удаление отмеченных медиа
        $toDelete = $request->post('delete_media', []);
        if (is_array($toDelete) && !empty($toDelete)) {
            $ids = array_map('intval', $toDelete);
            $urls = $this->gameRepo->deleteMedia($ids, $gameId);
            foreach ($urls as $url) {
                $this->uploadService->deleteFile($url);
            }
        }

        // Добавление новых медиа в конец галереи
        if (!empty($_FILES['media']['name'][0])) {
            $paths = $this->uploadService->uploadMultipleFiles($_FILES['media'], 'games');
            $order = $this->gameRepo->getNextMediaOrder($gameId);
            foreach ($paths as $path) {
                $this->gameRepo->addMedia($gameId, $path, 'image', $order++, false);
            }
        }

        Session::flash('success', 'Игра успешно обновлена');
        Response::redirect('/games/' . $gameId);
    }

    // Удаление игры (POST /games/{id}/delete)
    public function delete(Request $request, array $params): void
    {
        $gameId = (int) $params['id'];
        $userId = Session::userId();

        if (!$this->gameRepo->isOwner($gameId, $userId)) {
            Session::flash('error', 'Вы не можете удалить эту игру');
            Response::redirect('/games/' . $gameId);
            return;
        }

        // Сначала собираем пути файлов, потом удаляем запись (каскадом снесёт game_media)
        $urls = $this->gameRepo->findAllMediaUrls($gameId);
        $this->gameRepo->delete($gameId);

        foreach ($urls as $url) {
            $this->uploadService->deleteFile($url);
        }

        Session::flash('success', 'Игра успешно удалена');
        Response::redirect('/profiles/' . $userId);
    }

    public function addComment(Request $request, array $params): void
    {
        $gameId = (int) $params['id'];
        $userId = Session::userId();

        if ($userId === null) {
            Session::flash('error', 'Войдите, чтобы оставить комментарий');
            Response::redirect('/login');
            return;
        }

        $content = trim((string) $request->post('content'));
        if ($content === '') {
            Session::flash('error', 'Комментарий не может быть пустым');
            Response::redirect('/games/' . $gameId);
            return;
        }

        $this->commentRepo->create($gameId, $userId, $content);
        Response::redirect('/games/' . $gameId);
    }

    public function deleteComment(Request $request, array $params): void
    {
        $commentId = (int) $params['comment_id'];
        $userId = Session::userId();

        if ($userId === null) {
            Response::redirect('/');
            return;
        }

        $this->commentRepo->delete($commentId, $userId);
        // gameId берём из referer или просто редиректим на главную
        Response::redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }

    public function toggleLike(Request $request, array $params): void
    {
        $gameId = (int) $params['id'];
        $userId = Session::userId();

        if ($userId === null) {
            Response::redirect('/login');
            return;
        }

        $this->likeRepo->toggle($gameId, $userId);
        Response::redirect('/games/' . $gameId);
    }
}