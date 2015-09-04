<?php namespace Vdbf\Import\Excel;

use Vdbf\Import\Reader as ReaderContract;

class Reader implements ReaderContract
{

    /**
     * @var \PHPExcel
     */
    protected $object;

    /**
     * @param $path
     * @return void
     * @throws \PHPExcel_Reader_Exception
     */
    public function load($path)
    {
        //determine correct reader
        $reader = \PHPExcel_IOFactory::createReaderForFile($path);

        //load the file
        $this->object = $reader->load($path);
    }

    /**
     * Retrieve a sheet by index
     *
     * @param $index
     * @return \PHPExcel_Worksheet
     * @throws \PHPExcel_Exception
     */
    public function sheet($index)
    {
        return $this->object->getSheet($index);
    }

    /**
     * Retrieve row iterator
     *
     * @param \PHPExcel_Worksheet $sheet
     * @return \PHPExcel_Worksheet_RowIterator
     */
    public function rows(\PHPExcel_Worksheet $sheet)
    {
        $rows = $sheet->getRowIterator()->resetStart();

        return $rows;
    }

}