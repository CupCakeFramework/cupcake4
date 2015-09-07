<?php
namespace Cupcake\Mailer;

use Cupcake\Renderer\CupRendererInterface;
use PHPMailer;

class MailerManager
{

    private $template = 'email_template';
    private $to;
    private $subject;
    private $message;

    /**
     * @var bool|false
     */
    private $dumpEmailOnScreen;

    /**
     * @var CupRendererInterface
     */
    private $renderer;

    /**
     * @var array
     */
    private $config;

    /**
     * @var bool
     */
    private $bypassEmail;

    /**
     * @param array $config
     * @param CupRendererInterface $renderer
     * @param bool|false $dumpMailOnScreen
     * @param bool|false $bypassEmail
     */
    public function __construct(
        array $config,
        CupRendererInterface $renderer,
        $dumpMailOnScreen = false,
        $bypassEmail = false
    ) {
        $this->renderer = $renderer;
        $this->config = $config;
        $this->dumpEmailOnScreen = $dumpMailOnScreen;
        $this->bypassEmail = $bypassEmail;
    }

    public function enviaEmail($dados, $to, $subject = 'Contato através do site', $viewEmail = 'email/contato')
    {
        if($this->bypassEmail){
            return true;
        }
        $this->to = $to;
        $this->subject = $subject;
        $this->message = $this->getRenderedEmail($viewEmail, ['dados' => $dados, 'subject' => $this->subject]);

        return $this->send();
    }

    private function getRenderedEmail($view, $dados)
    {
        $this->getRenderer()->setTemplate($this->template);

        return $this->getRenderer()->renderizar($view, $dados, true);
    }

    private function send()
    {
        $mail = $this->getMailer();
        $mail->addAddress($this->to);
        $mail->addReplyTo($this->to);
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);

        $mail->Subject = $this->subject;
        $mail->Body = $this->message;

        if (true == $this->dumpEmailOnScreen) {
            header('Content-Type: text / html;charset = utf-8');
            die($mail->Body);
        }

        return $mail->send();
    }

    /**
     * @return PHPMailer
     */
    public function getMailer()
    {
        $mail = new PHPMailer(true);
        if ($this->config['isSMTP']) {
            $mail->SMTPDebug = $this->config['SMTPDebug'];                               // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = $this->config['Host'];  // Specify main and backup SMTP servers
            $mail->SMTPAuth = $this->config['SMTPAuth'];                               // Enable SMTP authentication
            $mail->Username = $this->config['Username'];                 // SMTP username
            $mail->Password = $this->config['Password'];                           // SMTP password
            if (isset($this->config['SMTPSecure'])) {
                $mail->SMTPSecure = $this->config['SMTPSecure'];                            // Enable TLS encryption, `ssl` also accepted
            }
            $mail->Port = $this->config['Port'];                                    // TCP port to connect to
            $mail->From = $this->config['From'];
            $mail->FromName = $this->config['FromName'];
        } else {
            $mail->From = $this->config['From'];
            $mail->FromName = $this->config['FromName'];
            $mail->isMail();
        }

        return $mail;
    }

    /**
     * @return CupRendererInterface
     */
    private function getRenderer()
    {
        return $this->renderer;
    }

}
