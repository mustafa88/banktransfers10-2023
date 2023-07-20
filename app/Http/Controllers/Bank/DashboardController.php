<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Models\bank\Banks;
use App\Models\bank\Banksline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function main($year=null){

        if($year==null){
            $year = date('Y');
        }
        $banks = Banks::with('enterprise','projects')->get();
        //return $banks;
        foreach ($banks as $indexbank =>$item){

            $arrProg[0] = ['חודש',1,2,3,4,5,6,7,8,9,10,11,12];
            $arrProg[1] = ['מס שורות','-','-','-','-','-','-','-','-','-','-','-','-'];
            $arrProg[2] = ['שורות לא תקינות','-','-','-','-','-','-','-','-','-','-','-','-'];


            $id_bank = $item['id_bank'];

            $countAllRow = DB::table('banksline')
                ->select(
                    \DB::raw("(DATE_FORMAT(datemovement, '%m')) as month_year")
                    ,DB::raw("count(*)  as countLine")
                )
                ->where('id_bank','=' ,$id_bank)
                ->whereYear('datemovement','=' ,$year)
                ->groupBy(\DB::raw("(DATE_FORMAT(datemovement, '%m'))"))
                ->orderBy(\DB::raw("(DATE_FORMAT(datemovement, '%m'))"))
                ->get();
            $countAllRow = json_decode($countAllRow, true);

            $countNoFixRow = DB::table('banksline')
                ->select(
                    \DB::raw("(DATE_FORMAT(datemovement, '%m')) as month_year")
                    ,DB::raw("count(*)  as countLine")
                )
                ->where('id_bank','=' ,$id_bank)
                ->whereYear('datemovement','=' ,$year)
                ->whereYear('done','!=' ,1)
                ->groupBy(\DB::raw("(DATE_FORMAT(datemovement, '%m'))"))
                ->orderBy(\DB::raw("(DATE_FORMAT(datemovement, '%m'))"))
                ->get();
            $countNoFixRow = json_decode($countNoFixRow, true);


            foreach ($countAllRow as $index => $item) {
                $arrProg[1][(int)$item['month_year']] = $item['countLine'];
            }

            foreach ($countNoFixRow as $index => $item) {
                $arrProg[2][(int)$item['month_year']] = $item['countLine'];
            }
            if($id_bank==2){
                //ddd($arrProg);
                //return $index;
            }
            //return $index;
            $banks[$indexbank]['arrProg'] = $arrProg;
            unset($arrProg);
        }

        //return $banks;

        $arrYear = array();
        for ($i=2021 ; $i<=date('Y') ;$i++){
            $arrYear[] = $i;
        }
        return view('reports.dashboard', compact('arrYear','year','banks'))
        ->with(
        [
        'pageTitle' => "ملخص التقدم السنوي",
        'subTitle' => 'ملخص التقدم السنوي',
        ]
        );
    }
}
