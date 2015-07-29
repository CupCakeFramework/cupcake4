<?php

/**
 * Messenger Simples para ser usado com o CupCake 3
 *
 * @author Ricardo Fiorani
 */
class CupMessenger {

    private $sessionId;

    function __construct($sessionId = 'messenger-default') {
        $this->sessionId = $sessionId;
    }

    public function adicionarMensagemErro($mensagem) {
        $this->adicionarMensagem($mensagem, 2);
    }

    public function adicionarMensagemSucesso($mensagem) {
        $this->adicionarMensagem($mensagem, 1);
    }

    public function adicionarMensagem($mensagem, $tipo = 0) {
        /*
         * Tipos
         * 0 = Neutro
         * 1 = Sucesso
         * 2 = Erro
         */
        switch ($tipo) {
            case 0:
            default:
                $classeErro = 'info';
                break;
            case 1:
                $classeErro = 'success';
                break;
            case 2:
                $classeErro = 'danger';
                break;
        }
        if (empty($_SESSION[$this->sessionId])) {
            $_SESSION[$this->sessionId] = array();
        }

        array_push($_SESSION[$this->sessionId], array('mensagem' => $mensagem, 'classe' => $classeErro));
    }

    public function listarMensagens() {
        $mensagens = $_SESSION[$this->sessionId];
        $this->removerMensagens();
        return $mensagens;
    }

    public function existeMensagens() {
        return !empty($_SESSION[$this->sessionId]);
    }

    public function contarMensagens() {
        return count($_SESSION[$this->sessionId]);
    }

    public function removerMensagens() {
        unset($_SESSION[$this->sessionId]);
    }

}
