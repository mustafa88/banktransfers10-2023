<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Models\Bank\Donatetype;
use Illuminate\Http\Request;
use App\Http\Requests\Bank\DonatetypeRequest;

class DonateTypeController extends Controller
{
    //

    public function donateType()
    {
        //מסך ראשי סוג תרומה

        $donatetype = Donatetype::get();
        $dataTables='v1';
        return view('managetable.donatetype' , compact('dataTables','donatetype'))
            ->with(
                [
                    'pageTitle' => "انواع التبرعات بقيمة",
                    'subTitle' => 'ادارة انواع التبرعات بقيمة',
                ]
            );
    }


    public function store(DonatetypeRequest $request){
        $arrDate = [
            'name' => $request->name,
            'price' => $request->price,
        ];
        Donatetype::create($arrDate);
        return redirect()->back()->with("success", "تم الحفظ بنجاح");
    }

    public function updatePriceAjax(DonatetypeRequest $request,$id_donatetype){

        if($id_donatetype == 0){
            $resultArr['status'] = false;
            $resultArr['cls'] = 'error';
            $resultArr['msg'] = 'תקלה בשמירה';
            return response()->json($resultArr);
        }

        $donateType = Donatetype::find($id_donatetype);

        if(!$donateType){
            $resultArr['status'] = false;
            $resultArr['cls'] = 'error';
            $resultArr['msg'] = 'תקלה בשמירה';
            return response()->json($resultArr);
        }

        $donateType->price = $request->price;

        $donateType->save();

        $resultArr['status'] = true;
        $resultArr['cls'] = 'success';
        $resultArr['msg'] = 'تم الحفظ بنجاح';
        return response()->json($resultArr);

    }
}
