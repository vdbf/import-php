<?php namespace Vdbf\Import\Excel;

use Vdbf\Import\Importer as ImporterContract;
use Vdbf\Import\Reader as ReaderContract;

class SingleSheetImporter implements ImporterContract
{

    /**
     * @var Reader
     */
    protected $reader;

    /**
     * @var int
     */
    protected $sheetIndex;

    /**
     * Importer constructor.
     * @param $reader
     * @param int $sheetIndex
     */
    public function __construct(ReaderContract $reader, $sheetIndex = 0)
    {
        $this->reader = $reader;
        $this->sheetIndex = $sheetIndex;
    }

    /**
     * Do a memory efficient (iterator) import of each row
     *
     * @param $path
     * @param null $importer
     */
    public function import($path, $importer = null)
    {
        $this->reader->load($path);

        $sheet = $this->reader->sheet($this->sheetIndex);
        $rows = $this->reader->rows($sheet);

        $header = $this->cells($rows->current()->getCellIterator());

        $rows->next();

        while ($rows->valid()) {
            $row = $this->cells($rows->current()->getCellIterator());
            call_user_func_array($importer, [$row, $header]);
            $rows->next();
        }
    }

    /**
     * Do a simple dump to an associative array
     *
     * @param $path
     * @return array
     */
    public function dump($path)
    {
        $out = [];

        $this->import($path, function ($row, $header) use (&$out) {
            $out[] = array_combine($header, $row);
        });

        return $out;
    }

    /**
     * Iterate cells and return the calculated value in an array
     *
     * @param $cells
     * @return array
     */
    protected function cells($cells, $calculated = true)
    {
        $out = [];
        $getter = 'get' . ($calculated ? 'Calculated' : '') . 'Value';

        while ($cells->valid()) {
            $out[] = $cells->current()->{$getter}();
            $cells->next();
        }

        return $out;
    }
}