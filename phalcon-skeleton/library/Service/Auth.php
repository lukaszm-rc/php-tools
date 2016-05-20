<?php

namespace App\Service;

use Phalcon\Db\RawValue;
use Phalcony\Validator\Exception;
use User\Model\User;

/**
 * Class Auth
 * @package App\Service
 */
class Auth extends \Phalcon\Mvc\User\Component
{
    /**
     * Class property for cache getting of User
     *
     * @var User|bool|null
     */
    private $identity;

    /**
     * Get identity
     *
     * @return bool|User
     */
    public function getIdentity()
    {
        if (!is_null($this->identity)) {
            return $this->identity;
        }

        /**
         * @var $session \Phalcon\Session\Adapter
         */
        $session = $this->getDI()->get('session');
        if (!$session->isStarted()) {
            $session->start();
        }

        if ($session->get('id')) {
            /**
             * @todo check user after getting
             */
            return $this->identity = User::findFirst($session->get('id'));
        }

        return $this->identity = false;
    }

    /**
     * Register new user
     *
     * @param array $values
     * @return User
     * @throws Exception
     */
    public function registerUser(array $values = [])
    {
        $user = new User();
        $user->dateCreated = new RawValue('NOW()');
        $user->dateModified = new RawValue('NOW()');

        $user->publish = true;
        $user->deleted = false;

        $user->group_id = 1;

        $result = $user->save($values);
        if ($result === false) {
            throw new Exception(implode(', ', $user->getMessages()));
        }

        return $user;
    }

    /**
     * @param User $user
     */
    public function authByUser(\User\Model\User $user)
    {
        /**
         * @var $session \Phalcon\Session\Adapter
         */
        $session = $this->getDI()->get('session');
        if (!$session->isStarted()) {
            $session->start();
        }

        $session->set('id', $user->id);
    }

    /**
     * @param null|User $identity
     */
    public function setIdentity(\User\Model\User $identity = null)
    {
        $this->identity = $identity;
    }
}
