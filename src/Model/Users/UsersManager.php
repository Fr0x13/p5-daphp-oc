<?php

namespace App\Model\Users;

use App\lib\Manager;

abstract class UsersManager extends Manager
{
    /**
     * Méthode permettant d'enregistrer un commentaire.
     *
     * @param $comment Le commentaire à enregistrer
     */
    public function save(User $user)
    {
        if ($user->isValid()) {
            $this->add($user);
        } else {
            throw new \RuntimeException('L\'utilisateur doit être validé pour être enregistré');
        }
    }

    abstract public function getByEmail($id);

    abstract public function count();

    /**
     * Méthode permettant d'ajouter un utilisateur.
     *
     * @param $user User L'utilisateur à ajouter
     */
    abstract protected function add(User $user);
}