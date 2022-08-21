<?php

namespace TBCD\Doctrine\HfsqlDriver;

use Variant;

class RecordSetConverter
{

    /**
     * @param \Variant $recordSet
     * @return array
     */
    public static function convert(Variant $recordSet): array
    {
        $results = [];

        while (!$recordSet->EOF) {

            $row = [];

            for ($x = 0; $x < $recordSet->Fields->Count; $x++) {

                $value = $recordSet->Fields[$x]->value;
                $field = $recordSet->Fields[$x]->name;

                if ($recordSet->Fields[$x]->type == 133) {
                    $date = (string)$recordSet->Fields[$x]->value;
                    switch ($date) {
                        case '':
                        case '30/11/1999':
                            $date = null;
                            break;
                        default:
                            $exploded = explode('/', $date);
                            $rev = array_reverse($exploded);
                            $date = implode('-', $rev);
                    }
                    $value = $date;
                }

                $row[$field] = $value;
            }
            $results[] = $row;
            $recordSet->MoveNext();
        }

        return $results;
    }

}