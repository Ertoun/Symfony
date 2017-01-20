<?php
// src/Perso/PlatformBundle/Antispam/PersoAntispam.php

namespace Perso\PlatformBundle\Antispam;

class PersoAntispam
{
    /**
     * Check if text is spam
     *
     * @param  string $text
     * @return  bool
     */

    function __construct(\Swift_mailer $mailer, $locale, $minLength)
    {
        $this->mailer       = $mailer;
        $this->locale       = $locale;
        $this->minLength    = (int) $minLength;
    }

    public function isSpam($text)
    {
        return strlen($text) < $this->minLength;
    }
}