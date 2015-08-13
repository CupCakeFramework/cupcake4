<?php

use Doctrine\ORM\EntityManager;

class UserHelper {

    const STATUS_LOGADO = 0;
    const STATUS_SENHA_INVALIDA = 1;
    const STATUS_USUARIO_INATIVO = 2;
    const sessionUserAlias = 'userHelperSession';

    /**
     *
     * @var EntityManager 
     */
    private $em;

    /**
     *
     * @var Usuario 
     */
    private $usuarioLogado;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $userEntityName;

    function __construct(EntityManager $em, $userEntityName) {
        $this->em = $em;
        $this->userEntityName = $userEntityName;
        $this->setStatus(self::STATUS_SENHA_INVALIDA);
        $this->startSession();
    }

    public function usuarioEstaLogado() {
        return false == empty($_SESSION[self::sessionUserAlias]['id']);
    }

    function getStatus() {
        return $this->status;
    }

    /**
     * Retorna a instancia do Usuário Logado
     * @return Usuario
     */
    public function getUsuarioLogado() {
        if (!$this->usuarioLogado instanceof UserInterface) {
            $this->reloadUsuarioLogado();
        }
        return $this->usuarioLogado;
    }

    public function reloadUsuarioLogado() {
        $this->usuarioLogado = $this->em->getRepository($this->getUserEntityName())->find($_SESSION[self::sessionUserAlias]['id']);
    }

    public function logar($searchedValue, $password) {
        $userEntityName = $this->getUserEntityName();
        $usuario = $this->em->getRepository($userEntityName)->findOneBy(array($userEntityName::getFindFieldName() => $searchedValue));
        if (false == $usuario instanceof UserInterface) {
            $this->setStatus(self::STATUS_SENHA_INVALIDA);
            return false;
        }
        if (false == $usuario->isLoggable()) {
            $this->setStatus(self::STATUS_USUARIO_INATIVO);
            return false;
        }
        if (true === self::password_verify($password, $usuario->getSenha())) {
            $this->salvarUsuarioNaSessao($usuario);
            $this->setStatus(self::STATUS_LOGADO);
            return true;
        }
        return false;
    }

    public function deslogar() {
        if ($this->usuarioEstaLogado()) {
            session_destroy();
        }
    }

    public function salvarUsuarioNaSessao($user) {
        if (false == $user instanceof $this->userEntityName) {
            throw new Exception('Invalid user class to save');
        }
        $_SESSION[self::sessionUserAlias]['id'] = $user->getId();
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public static function generateRandomPassword() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }

    public static function password_hash($password) {
        return crypt($password, '$2a$14$' . uniqid(mt_rand(), true) . '$');
    }

    public static function password_verify($password, $hash) {
        return crypt($password, $hash) === $hash;
    }

    function getUserEntityName() {
        return $this->userEntityName;
    }

    function startSession() {
        if (session_id() == '') {
            session_start();
        }
    }

}
