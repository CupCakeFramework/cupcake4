<?php

namespace Cupcake\Messenger;

/**
 * Messenger Simples para ser usado com o CupCake 3
 *
 * @author Ricardo Fiorani
 */
class FlashMessenger
{

    const TYPE_NEUTRAL = 0;
    const TYPE_SUCCESS = 1;
    const TYPE_ERROR = 2;


    private $sessionId;

    /**
     * @param string $sessionId
     */
    function __construct($sessionId = 'messenger-default')
    {
        $this->sessionId = $sessionId;
        $this->startSession();
    }


    /**
     * @param $mensagem
     */
    public function adicionarMensagemErro($mensagem)
    {
        $this->adicionarMensagem($mensagem, 2);
    }

    /**
     * @param $mensagem
     */
    public function adicionarMensagemSucesso($mensagem)
    {
        $this->adicionarMensagem($mensagem, 1);
    }

    /**
     * @param $mensagem
     * @param int $tipo
     */
    public function adicionarMensagem($mensagem, $tipo = self::TYPE_NEUTRAL)
    {
        switch ($tipo) {
            case self::TYPE_NEUTRAL :
            default:
                $classeErro = 'info';
                break;
            case self::TYPE_SUCCESS :
                $classeErro = 'success';
                break;
            case self::TYPE_ERROR :
                $classeErro = 'danger';
                break;
        }
        if (empty($_SESSION[$this->sessionId])) {
            $_SESSION[$this->sessionId] = array();
        }

        array_push($_SESSION[$this->sessionId], array('mensagem' => $mensagem, 'classe' => $classeErro));
    }

    /**
     * @return array
     */
    public function listarMensagens()
    {
        $mensagens = $_SESSION[$this->sessionId];
        $this->removerMensagens();

        return $mensagens;
    }

    /**
     * @return bool
     */
    public function existeMensagens()
    {
        return !empty($_SESSION[$this->sessionId]);
    }

    /**
     * @return int
     */
    public function contarMensagens()
    {
        return count($_SESSION[$this->sessionId]);
    }

    /**
     * Remove all messages from the session
     */
    public function removerMensagens()
    {
        unset($_SESSION[$this->sessionId]);
    }

    public function startSession()
    {
        if (session_id() == '') {
            session_start();
        }
    }

}
