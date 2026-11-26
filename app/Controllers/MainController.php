<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\I18n;
use App\Core\Request;
use App\Core\View;

class MainController
{
    private View $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    public function index(Request $request, array $params): void
    {
        $this->view->renderWithLayout('pages/home', [
            'pageTitle' => I18n::t('site_name'),
            'features' => [
                I18n::t('feature_portfolio'),
                I18n::t('feature_cards'),
                I18n::t('feature_tags'),
                I18n::t('feature_search'),
            ],
        ]);
    }
}