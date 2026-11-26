<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\View;
use App\Models\Repositories\CommentRepository;
use App\Models\Repositories\GameRepository;
use App\Models\Repositories\LikeRepository;
use App\Services\UploadService;

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

    public function index(Request $request, array $params): void
    {
        $query = trim((string) $request->get('q'));
        $tagId = $request->get('tag');
        $tagId = $tagId !== null && $tagId !== '' ? (int) $tagId : null;

        $games = $this->gameRepo->search($query, $tagId);
        $tags = $this->gameRepo->findAllTags();

        $this->view->renderWithLayout('pages/games', [
            'pageTitle' => 'Все игры — GameFolio',
            'games' => $games,
            'tags' => $tags,
            'currentQuery' => $query,
            'currentTagId' => $tagId,
        ]);
    }

    public function show(Request $request, array $params): void
    {
        $id = (int) $params['id'];
        $game = $this->gameRepo->findWithDetails($id);

        if (!$game) {
            $this->view->renderWithLayout('pages/errors/404', [
                'pageTitle' => '404 — Игра не найдена',
            ]);
            return;
        }

        $comments = $this->commentRepo->findByGame($id);
        $likeCount = $this->likeRepo->countByGame($id);
        $isLiked = false;
        $userId = Session::userId();
        if ($userId !== null) {
            $isLiked = $this->likeRepo->isLikedByUser($id, $userId);
        }

        $gameTags = $this->gameRepo->findTagsByGame($id);

        $this->view->renderWithLayout('pages/game_details', [
            'game' => $game,
            'gameTags' => $gameTags,
            'comments' => $comments,
            'likeCount' => $likeCount,
            'isLiked' => $isLiked,
            'success' => Session::flash('success'),
            'error' => Session::flash('error'),
            'pageTitle' => $game['title'] . ' — GameFolio'
        ]);
    }

    public function createForm(Request $request, array $params): void
    {
        $tags = $this->gameRepo->findAllTags();
        $this->view->renderWithLayout('pages/game_create', [
            'pageTitle' => 'Добавить игру — GameFolio',
            'error' => Session::flash('error'),
            'tags' => $tags,
        ]);
    }

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

        $tagIds = $request->post('tags', []);
        if (is_array($tagIds)) {
            $this->gameRepo->syncGameTags($gameId, $tagIds);
        }

        Session::flash('success', 'Игра успешно добавлена');
        Response::redirect('/games/' . $gameId);
    }

    public function editForm(Request $request, array $params): void
    {
        $gameId = (int) $params['id'];
        $userId = Session::userId();

        if (!$this->gameRepo->isOwner($gameId, $userId)) {
            Session::flash('error', 'Вы не можете редактировать эту игру');
            Response::redirect('/games/' . $gameId);
            return;
        }

        $game = $this->gameRepo->findWithDetails($gameId);
        $tags = $this->gameRepo->findAllTags();

        $this->view->renderWithLayout('pages/game_edit', [
            'pageTitle' => 'Редактировать игру — GameFolio',
            'game' => $game,
            'tags' => $tags,
            'error' => Session::flash('error'),
            'success' => Session::flash('success'),
        ]);
    }

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

        $toDelete = $request->post('delete_media', []);
        if (is_array($toDelete) && !empty($toDelete)) {
            $ids = array_map('intval', $toDelete);
            $urls = $this->gameRepo->deleteMedia($ids, $gameId);
            foreach ($urls as $url) {
                $this->uploadService->deleteFile($url);
            }
        }

        if (!empty($_FILES['media']['name'][0])) {
            $paths = $this->uploadService->uploadMultipleFiles($_FILES['media'], 'games');
            $order = $this->gameRepo->getNextMediaOrder($gameId);
            foreach ($paths as $path) {
                $this->gameRepo->addMedia($gameId, $path, 'image', $order++, false);
            }
        }

        $tagIds = $request->post('tags', []);
        if (is_array($tagIds)) {
            $this->gameRepo->syncGameTags($gameId, $tagIds);
        }

        Session::flash('success', 'Игра успешно обновлена');
        Response::redirect('/games/' . $gameId);
    }

    public function delete(Request $request, array $params): void
    {
        $gameId = (int) $params['id'];
        $userId = Session::userId();

        if (!$this->gameRepo->isOwner($gameId, $userId)) {
            Session::flash('error', 'Вы не можете удалить эту игру');
            Response::redirect('/games/' . $gameId);
            return;
        }

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