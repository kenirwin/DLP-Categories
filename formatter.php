<?php
// $dataFile = "dlp-data.csv";

// foreach file in csv directory
//   get filename
// help me write this
$dir = "./csv/";
$files = scandir($dir);
foreach ($files as $file) {
    if (preg_match('/.csv/', $file)) {
        $dataFile = $dir . $file;
        $GLOBALS['listIsOpen'] = false;
        $GLOBALS['subListIsOpen'] = false;
        $GLOBALS['level3ListIsOpen'] = false;

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
    }
print '<hr>'.PHP_EOL;
}




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
    if ($GLOBALS['LinkTextOverride'] != null) {
        $slug = strtolower(str_replace(' ', '+', $GLOBALS['LinkTextOverride']));
    } 
    else {
        $slug = strtolower(str_replace(' ', '+', $item));
    }
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
    // SubCategory
    // SubCatIsLink
    // Item
    // SubItem
    // ItemLevel3  
    // LinkTextOverride  
    $GLOBALS['LinkTextOverride'] = $line['LinkTextOverride'];
    // dumb workaround:
    // I don't know why $line['PageTitle'] isn't working
    $keys = array_keys($line);  
    for ($i = 0; $i < count($keys); $i++) {
        if (preg_match('/PageTitle/', $keys[$i]) && $line[$keys[$i]] != null) {
            print '<h1>' . $line[$keys[$i]] . '</h1>'.PHP_EOL;
        }
        
    }
    // end dumb workaround

    $prepend = '';
    if ($GLOBALS['level3ListIsOpen'] && ($line['ItemLevel3'] == null)) {
        $GLOBALS['level3ListIsOpen'] = false;
        $prepend .= '          </ul>'.PHP_EOL;
    }
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
    if (($line['SubCategory']) != null) {
        $SubCategory = $line['SubCategory'];
        if (! $line['SubCatNotLink'] == 1) {
            $SubCategory = AddLink($SubCategory);
        }
        return $prepend . '<h3>' . $SubCategory . '</h3>';
    }
    if (($line['Item']) != null) {
        $item = $line['Item'];
        $item = AddLink($item);
        if ($GLOBALS['listIsOpen'] == false) {
            $GLOBALS['listIsOpen'] = true;
            return $prepend.'<ul class="item">'.PHP_EOL.'  <li>' . $item . '</li>';
        }
        return $prepend.'  <li>' . $item . '</li>';
    }
    if (($line['SubItem']) != null) { 
        $subItem = $line['SubItem'];
        $subItem = AddLink($subItem);
        if ($GLOBALS['subListIsOpen'] == false) {
            $GLOBALS['subListIsOpen'] = true;
            return $prepend.'    <ul class="subitem">'.PHP_EOL.'      <li>' . $subItem . '</li>';
        }
        return $prepend.'      <li>' . $subItem . '</li>';
    }
    if (($line['ItemLevel3']) != null) { 
        $itemLevel3 = $line['ItemLevel3'];
        $itemLevel3 = AddLink($itemLevel3);
        if ($GLOBALS['level3ListIsOpen'] == false) {
            $GLOBALS['level3ListIsOpen'] = true;
            return $prepend.'          <ul class="level-3">'.PHP_EOL.'            <li>' . $itemLevel3 . '</li>';
        }
        return $prepend.'            <li>' . $itemLevel3 . '</li>';
    }
}


// var_dump($csv);
