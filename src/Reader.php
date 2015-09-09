<?php namespace Vdbf\Import;

interface Reader
{

    /**
     * @param $path
     * @return void
     */
    public function load($path);

}