import-php
========================
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



