<?php

namespace App\Controllers\News\Admin;

use App\lib\Controller;
use App\lib\Renderer;
use App\Model\News\News;

class NewsController extends Controller
{
    public function __construct()
    {
        parent::__construct('News');
    }

    public function executeList()
    {
        $listeNews = $this->manager->getList();
        $nombreNews = $this->manager->count();

        $renderer = new Renderer(
            'list.twig',
            '../src/Controllers/News/Admin/Views',
            ['nombreNews' => $nombreNews, 'listeNews' => $listeNews, 'title' => 'Gestion des articles']
        );
        $renderer->render();
    }

    public function executeAddNews()
    {
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $titre = $_POST['titre'];
            $content = $_POST['contenu'];
            $auteur = $_SESSION['login'];

            $news = new News([
                'titre' => $titre,
                'auteur' => $auteur,
                'contenu' => $content,
            ]);

            $this->manager->save($news);

            $this->redirect('/admin/listNews');
        } else {
            $renderer = new Renderer(
                'insert.twig',
                '../src/Controllers/News/Admin/Views',
                ['title' => 'Ajouter un article']
            );
            $renderer->render();
        }
    }

    public function executeUpdateNews($id)
    {
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $titre = $_POST['titre'];
            $content = $_POST['contenu'];
            $auteur = $_SESSION['login'];

            $news = new News([
                'id' => $id,
                'titre' => $titre,
                'auteur' => $auteur,
                'contenu' => $content,
            ]);

            $this->manager->save($news);

            $this->redirect('/admin/listNews');
        } else {
            $news = $this->manager->getUnique($id);

            $renderer = new Renderer(
                'update.twig',
                '../src/Controllers/News/Admin/Views',
                ['title' => 'Modifier un article', 'news' => $news]
            );
            $renderer->render();
        }
    }

    public function executeDelete($id)
    {
        $this->manager->delete($id);
        $this->redirect('/admin/listNews');
    }
}
