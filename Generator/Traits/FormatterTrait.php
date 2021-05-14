<?php

namespace Apiato\Core\Generator\Traits;

trait FormatterTrait
{
    /**
     * @param string $string
     */
    protected function trimString($string): string
    {
        return trim($string);
    }

    /**
     * @param string $word
     */
    public function capitalize($word): string
    {
        return ucfirst($word);
    }
}
