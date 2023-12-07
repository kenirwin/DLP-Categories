<?php
$dataFile = "dlp-data.csv";
$GLOBALS['listIsOpen'] = false;
$GLOBALS['subListIsOpen'] = false;

function LineIsBlank($line) {
    $blank = true;
    $keys = array_keys($line);
    for ($i = 0; $i < count($keys); $i++) {
        if ($line[$keys[$i]] != null) {
            $blank = false;
        }
    }
    return $blank;
}
function AddLink($item) {
    $slug = strtolower(str_replace(' ', '+', $item));
    $link = '<a href="' . $slug . '">' . $item . '</a>';
    return $link;
}
function Formatter($line) {
    if (LineIsBlank($line)) {
        return ''; // ignore blank lines
    }
    // PageTitle
    // Category
    // DontLinkCat
    // Subcategory
    // SubCatIsLink
    // Item
    // SubItem

    // dumb workaround:
    $keys = array_keys($line);  
    for ($i = 0; $i < count($keys); $i++) {
        if (preg_match('/PageTitle/', $keys[$i]) && $line[$keys[$i]] != null) {
            print '<h1>' . $line[$keys[$i]] . '</h1>'.PHP_EOL;
        }
        
    }
    // end dumb workaround

    $prepend = '';
    if ($GLOBALS['subListIsOpen'] && ($line['SubItem'] == null)) {
        $GLOBALS['subListIsOpen'] = false;
        $prepend .= '    </ul>'.PHP_EOL;
    }
    if ($GLOBALS['listIsOpen'] && ($line['Item'] == null) && $line['SubItem'] == null) {
        $GLOBALS['listIsOpen'] = false;
        $prepend .= '</ul>'.PHP_EOL;
    }

    if (($line['Category']) != null) {
        $category = $line['Category'];
        if (! $line['DontLinkCat'] == 1) {
            $category = AddLink($category);
        }
        return $prepend . '<h2>' . $category . '</h2>';
    }
    if (($line['Item']) != null) {
        $item = $line['Item'];
        $item = AddLink($item);
        if ($GLOBALS['listIsOpen'] == false) {
            $GLOBALS['listIsOpen'] = true;
            return $prepend.'<ul>'.PHP_EOL.'  <li>' . $item . '</li>';
        }
        return $prepend.'  <li>' . $item . '</li>';
    }
    if (($line['SubItem']) != null) { 
        $subItem = $line['SubItem'];
        $subItem = AddLink($subItem);
        if ($GLOBALS['subListIsOpen'] == false) {
            $GLOBALS['subListIsOpen'] = true;
            return '    <ul>'.PHP_EOL.'      <li>' . $subItem . '</li>';
        }
        return '      <li>' . $subItem . '</li>';
    }
}

/* Map Rows and Loop Through Them */
$rows   = array_map('str_getcsv', file($dataFile));
$header = array_shift($rows);
$csv    = array();
foreach($rows as $row) {
    $csv[] = array_combine($header, $row);
}

foreach ($csv as $line) {
    // print('line: ');
    print(Formatter($line).PHP_EOL);
    // var_dump($line);
}
if ($GLOBALS['subListIsOpen']) {
    print '    </ul>'.PHP_EOL;
}   
if ($GLOBALS['listIsOpen']) {
    print '</ul>'.PHP_EOL;
}
// var_dump($csv);
