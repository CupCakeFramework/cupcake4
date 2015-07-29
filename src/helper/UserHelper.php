<?php

use Doctrine\ORM\EntityManager;

class UserHelper {

    const STATUS_LOGADO = 0;
    const STATUS_SENHA_INVALIDA = 1;
    const STATUS_USUARIO_INATIVO = 2;
    const sessionUserAlias = 'usuario_logado';

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

    function __construct(EntityManager $em) {
        $this->em = $em;
        $this->setStatus(self::STATUS_SENHA_INVALIDA);
    }

    public function usuarioEstaLogado() {
        return !empty($_SESSION[self::sessionUserAlias]['id']);
    }

    function getStatus() {
        return $this->status;
    }

    /**
     * Retorna a instancia do Usuário Logado
     * @return Usuario
     */
    public function getUsuarioLogado() {
        if (!$this->usuarioLogado instanceof Usuario) {
            $this->reloadUsuarioLogado();
        }
        return $this->usuarioLogado;
    }

    public function reloadUsuarioLogado() {
        $this->usuarioLogado = $this->em->getRepository('Usuario')->find($_SESSION[self::sessionUserAlias]['id']);
    }

    public function logar($email, $senha) {
        $usuario = $this->em->getRepository('Usuario')->findOneBy(array('email' => $email));
        if (!$usuario instanceof Usuario) {
            $this->setStatus(self::STATUS_SENHA_INVALIDA);
            return false;
        }
        if ($usuario->getAtivo() != Usuario::ATIVO_TRUE) {
            $this->setStatus(self::STATUS_USUARIO_INATIVO);
            return false;
        }
        if (self::password_verify($senha, $usuario->getSenha())) {
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

    public function salvarUsuarioNaSessao(Usuario $empresa) {
        $_SESSION[self::sessionUserAlias]['id'] = $empresa->getId();
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

}
