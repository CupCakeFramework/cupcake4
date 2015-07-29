<?php

/**
 * @author Ricardo Fiorani
 */
class CupDateFormatter {

    public function format(DateTime $date) {
        return $date->format('d/m/Y ') . $this->getDiaDaSemana($date);
    }

    public function getDiaDaSemana(DateTime $date) {
        $diaDaSemana = $date->format('w'); // Representação numérica dos dias da semana.
        $listaSemana = array(0 => "Domingo", 1 => "Segunda-feira", 2 => "Terça-feira", 3 => "Quarta-feira", 4 => "Quinta-feira", 5 => "Sexta-feira", 6 => "Sábado");
        return $listaSemana[$diaDaSemana];
    }

}
