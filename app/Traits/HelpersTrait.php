<?php

namespace App\Traits;


trait HelpersTrait
{
    /**
     * @param $rowHtml
     * @return array
     * מקבל שורה בטבלה TR
     * מחזיר כל הערכים במערך
     */
    function rowHtmlToArray($rowHtml){
        $rowHtml = trim(preg_replace('/\s\s+/', ' ', $rowHtml));

        $rowHtml = str_replace(array('<tr>','</tr>'), "", $rowHtml);

        $rowHtmlArr = array();
        while($rowHtml!=""){
            $pos1 = strpos($rowHtml, '<td>');
            $pos2 = strpos($rowHtml, '</td>');
            if($pos1===false or $pos2===false){
                break;
            }
            $rowHtmlArr[] = substr($rowHtml,$pos1+4,$pos2-5);
            $rowHtml =  substr($rowHtml,$pos2+5);
        }
        return $rowHtmlArr;
    }
}
