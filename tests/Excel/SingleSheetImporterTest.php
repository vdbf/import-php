<?php

class SingleSheetImporterTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \Vdbf\Import\Excel\SingleSheetImporter
     */
    protected $importer;

    /**
     * @var string
     */
    protected $path;

    public function setUp()
    {
        $this->importer = new Vdbf\Import\Excel\SingleSheetImporter(new \Vdbf\Import\Excel\Reader());
        $this->path = dirname(__DIR__) . '/fixtures/map.xlsx';
    }

    public function testHeader()
    {
        $dumped = $this->importer->dump($this->path);

        $item = array_pop($dumped);

        $this->assertEquals(['A', 'B', 'C', 'D'], array_keys($item));
    }

    public function testRows()
    {
        $dumped = $this->importer->dump($this->path);

        //3 rows
        $this->assertCount(3, $dumped);

        //4 columns
        $this->assertCount(4, array_pop($dumped));

        //A3:D3
        $this->assertEquals(['A' => 2.0, 'B' => 5.0, 'C' => 8.0, 'D' => 80.0], array_pop($dumped));
    }

    public function testClosure()
    {
        $i = 0;

        $this->importer->import($this->path, function ($row, $header) use (&$i) { $i++; });

        //assume called three times
        $this->assertEquals(3, $i);
    }

    public function testReadHeader()
    {
        $this->importer = new Vdbf\Import\Excel\SingleSheetImporter(new Vdbf\Import\Excel\Reader(), ['read_header' => false]);

        $dumped = $this->importer->dump($this->path);

        $this->assertCount(4, $dumped);
    }

}