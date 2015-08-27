<?php

/**
 *
 * @author Ricardo Fiorani
 */
interface UserInterface {

    public static function getFindFieldName();
    
    public function getId();
    
    public function isLoggable();
}
