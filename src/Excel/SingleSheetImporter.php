<?php namespace Vdbf\Import\Excel;

use Vdbf\Import\Importer as ImporterContract;
use Vdbf\Import\Reader as ReaderContract;

/**
 * Class SingleSheetImporter
 *
 * Reads a single sheet, imports per row and supplies the importer with the row content and the header content
 * Options available:
 * - sheet_index, defaults to 0
 * - read_header, defaults to true, defines if the sheet has a header row
 *
 * @package Vdbf\Import\Excel
 */
class SingleSheetImporter implements ImporterContract
{

    /**
     * @var ReaderContract
     */
    protected $reader;

    /**
     * @var array
     */
    protected $options;

    /**
     * Importer constructor.
     * @param ReaderContract $reader
     * @param array $options
     */
    public function __construct(ReaderContract $reader, $options = [])
    {
        $this->reader = $reader;
        $this->options = array_merge([
            'sheet_index' => 0
        ], $options);
    }

    /**
     * Do a memory efficient (iterator) import of each row
     *
     * @param string $path
     * @param string|array|\Closure|null $importer
     */
    public function import($path, $importer = null)
    {
        $this->reader->load($path);

        $sheet = $this->reader->sheet($this->option('sheet_index'));
        $rows = $this->reader->rows($sheet);

        $header = $this->readHeader($rows);

        while ($rows->valid()) {
            $row = $this->cells($rows->current()->getCellIterator());
            call_user_func_array($importer, [$row, $header]);
            $rows->next();
        }
    }

    /**
     * Do a simple dump to an associative array
     *
     * @param string $path
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

    /**
     * Retrieve option with default value fallback
     *
     * @param string $key
     * @param mixed $defaultValue
     * @return mixed
     */
    protected function option($key, $defaultValue = null)
    {
        return isset($this->options[$key]) ? $this->options[$key] : $defaultValue;
    }

    /**
     * Read header and if configured for reading, proceed to next row
     *
     * @param $rows
     * @return array
     */
    protected function readHeader(&$rows)
    {
        $header = $this->cells($rows->current()->getCellIterator());

        if ($this->option('read_header', true)) {
            $rows->next();
        } else {
            $header = range(0, count($header) - 1);
        }

        return $header;
    }
}
