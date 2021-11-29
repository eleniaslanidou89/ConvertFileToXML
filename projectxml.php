<?php
$input = "P|Carl Gustaf|Bernadotte
T|0768-101801|08-101801
A|Drottningholms slott|Stockholm|10001
T|0768-101801|08-101801
A|Drottningholms slott|Stockholm|10001
F|Victoria|1977
A|Haga Slott|Stockholm|10002
F|Carl Philip|1979
T|0768-101801|08-101801
P|Barack|Obama
A|1600 Pennsylvania Avenue|Washington, D.C";

$prearr = explode("\n", $input);
$arr = preg_replace("/\r|\n/", '', $prearr);
$i = 0;
foreach ($arr as $v) {
    $arr[$i] = explode('|', $v);
    $i++;
}

//shows array in console
print_r($arr);
?>

<?php
function generateXML($data)
{
    $name = 'output';

    //create the XML document
    $xmlDoc = new DOMDocument();

    $tabPeople = $xmlDoc->appendChild($xmlDoc->createElement('people'));

    $prevkey = '';
    $usedP = false;
    $usedT = false;
    $usedA = false;
    $usedF = false;
    $PorF = '';

    foreach ($data as $value) {
        if ($value[0][0] == 'P') {
            $PorF = 'P';
            $usedP = true;
            $usedT = false;
            $usedA = false;
            $usedF = false;
            if (!empty($value)) {
                $tabperson = $tabPeople->appendChild(
                    $xmlDoc->createElement('person')
                );
                foreach ($value as $key => $val) {
                    if ($key == 1) {
                        $tabperson->appendChild(
                            $xmlDoc->createElement('firstname', $val)
                        );
                    }
                    if ($key == 2) {
                        $tabperson->appendChild(
                            $xmlDoc->createElement('lastname', $val)
                        );
                    }
                }
            }
        }
        if (
            $value[0][0] == 'T' &&
            $usedT == false &&
            ($PorF == 'P' || $PorF == 'F')
        ) {
            $prevkey = $value[0][0];
            $usedT = true;
            if ($PorF == 'P') {
                $tabphone = $tabperson->appendChild(
                    $xmlDoc->createElement('phone')
                );
            }
            if ($PorF == 'F') {
                $tabphone = $tabfamily->appendChild(
                    $xmlDoc->createElement('phone')
                );
            }
            foreach ($value as $key => $val) {
                if ($key == 1) {
                    $tabphone->appendChild(
                        $xmlDoc->createElement('mobile', $val)
                    );
                }
                if ($key == 2) {
                    $tabphone->appendChild(
                        $xmlDoc->createElement('landlinenumber', $val)
                    );
                }
            }
        }
        if (
            $value[0][0] == 'A' &&
            $usedA == false &&
            ($PorF == 'P' || $PorF == 'F')
        ) {
            $prevkey = $value[0][0];
            $usedA = true;
            if ($PorF == 'P') {
                $tabaddress = $tabperson->appendChild(
                    $xmlDoc->createElement('address')
                );
            }
            if ($PorF == 'F') {
                $tabaddress = $tabfamily->appendChild(
                    $xmlDoc->createElement('address')
                );
            }
            foreach ($value as $key => $val) {
                if ($key == 1) {
                    $tabaddress->appendChild(
                        $xmlDoc->createElement('gata', $val)
                    );
                }
                if ($key == 2) {
                    $tabaddress->appendChild(
                        $xmlDoc->createElement('stad', $val)
                    );
                }
                if ($key == 3) {
                    $tabaddress->appendChild(
                        $xmlDoc->createElement('postnummer', $val)
                    );
                }
            }
        }
        if ($value[0][0] == 'F' && $PorF == 'P') {
            $prevkey = $value[0][0];
            $PorF = 'F';
            $usedF = true;
            $usedT = false;
            $usedA = false;
            $tabfamily = $tabperson->appendChild(
                $xmlDoc->createElement('family')
            );
            foreach ($value as $key => $val) {
                if ($key == 1) {
                    $tabfamily->appendChild(
                        $xmlDoc->createElement('name', $val)
                    );
                }
                if ($key == 2) {
                    $tabfamily->appendChild(
                        $xmlDoc->createElement('born', $val)
                    );
                }
            }
        }
    }

    //format the output
    $xmlDoc->formatOutput = true;

    //save the XML file
    $file_name = str_replace(' ', '_', $name) . '.xml';
    $xmlDoc->save($file_name);

    //returns the name of the xml file
    return $file_name;
}

generateXML($arr);
?>

