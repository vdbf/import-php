import-php
========================
[![Build Status](https://scrutinizer-ci.com/g/vdbf/import-php/badges/build.png?b=master)](https://scrutinizer-ci.com/g/vdbf/import-php/build-status/master) [![Code Coverage](https://scrutinizer-ci.com/g/vdbf/import-php/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/vdbf/import-php/?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/vdbf/import-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/vdbf/import-php/?branch=master)

Simple importer written in PHP

## Example

```php
<?php

/**
 * Excel sheet example:
 * | A | B | C |
 * | 1 | 2 | 3 |
 * | 4 | 5 | 6 |
 */

$path = 'path/to/file.xlsx';
$importer = new Vdbf\Import\Excel\SingleSheetImporter(new Vdbf\Import\Excel\Reader());

//importer closure is called for every data-row
$importer->import($path, function ($row, $header) {
  print_r($header); //['A', 'B', 'C']
  print_r($row); //[1, 2, 3]
});

//importer dump method dumps all rows to a 2D associative array
$dump = $importer->dump($path);
print_r($dump) //[['A' => 1, 'B' => 2, 'C' => 3], ['A' => 4, 'B' => 5, 'C' => 6]]
```

## Configuration

The importer can be constructed with an array of options as a second argument.

```php
<?php

$options = [
  'read_header' => false, //skips reading the first row as a header row, defaults to true
  'sheet_index' => 1      //imports the second sheet, defaults to 0
];

$importer = new Vdbf\Import\Excel\SingleSheetImporter(new Vdbf\Import\Excel\Reader(), $options);

...
```



