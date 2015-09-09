<?php namespace Vdbf\Import;

interface Importer
{

    /**
     * @param $path
     * @return void
     */
    public function import($path);

    /**
     * @param $path
     * @return array
     */
    public function dump($path);

}