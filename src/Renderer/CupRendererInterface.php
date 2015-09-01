<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Fiorani
 * Date: 28/08/2015
 * Time: 12:20
 */

namespace Cupcake\Renderer;


interface CupRendererInterface
{
    /**
     * @param $template
     * @return mixed
     */
    public function setTemplate($template);

    /**
     * @param $nomeView
     * @param array $variaveis
     * @param bool|false $retornar
     * @return mixed
     */
    public function renderizar($nomeView, array $variaveis = [], $retornar = false);
}