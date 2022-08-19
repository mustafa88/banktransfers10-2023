<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bank\DonateworthRequest;
use App\Models\bank\Banksline;
use App\Models\bank\City;
use App\Models\Bank\Donatetype;
use App\Models\Bank\Donateworth;
use App\Models\bank\Enterprise;
use App\Traits\HelpersTrait;
use Illuminate\Http\Request;

class DonateworthController extends Controller
{
    //
    use HelpersTrait;
    public function mainDonate(Request $request)
    {
        //הצגת כל העמותות
        //$enterprise = Enterprise::with(['project'])->get()->toArray();

        if($request->fromDate!=null and $request->toDate!=null){
            $request->session()->put('showLineFromDate',$request->fromDate);
            $request->session()->put('showLineToDate',$request->toDate);
        }

        if(!$request->session()->has('showLineFromDate')){
            $request->session()->put('showLineFromDate',date('Y-01-01'));
        }

        if(!$request->session()->has('showLineToDate')){
            $request->session()->put('showLineToDate',date('Y-12-31'));
        }

        $showLineFromDate = $request->session()->get('showLineFromDate');
        $showLineToDate = $request->session()->get('showLineToDate');

        $city = City::get();
        $donatetype = Donatetype::get();
        $enterprise = Enterprise::with(['project'])->get();

        $donateworth = Donateworth::with(['enterprise','projects','city','donatetype'])
            ->where('datedont', '>=', $showLineFromDate)
            ->where('datedont', '<=', $showLineToDate)
        ->get();
        //return $donateworth;
        return view('manageabnk.donate' , compact('enterprise','city','donatetype','donateworth'))
            ->with(
                [
                    'pageTitle' => "تبرعات بقيمة",
                    'subTitle' => 'تسجيل تبرعات بقيمة للمؤسسات',
                ]
            );
    }


    public function storeAjax(DonateworthRequest $requset)
    {
        if($requset->id_line != 0){
            $resultArr['status'] = false;
            $resultArr['cls'] = 'error';
            $resultArr['msg'] = 'תקלה בשמירה';
            return response()->json($resultArr);
        }
        $enterp = explode('*',$requset->enterp);

        $id_enter = $enterp[0];
        $id_proj = $enterp[1];

        $arrDate = [
            'datedont' => $requset->datedont,
            'id_enter' =>$id_enter,
            'id_proj' => $id_proj,
            'id_city' => $requset->id_city,
            'id_typedont' => $requset->id_typedont,
            'amount' => $requset->amount,
            'description' => $requset->description,
            'namedont' => $requset->namedont,
        ];

        $rowinsert = Donateworth::create($arrDate);

        $rowDonate= Donateworth::with(['enterprise','projects','city','donatetype'])->find($rowinsert->id_donate);


        $rowHtml = view('layout.includes.linedonate',compact('rowDonate'))->render();
        //$rowHtml = trim(preg_replace('/\s\s+/', ' ', $rowHtml));
        $rowHtml = trim(preg_replace("/\s+/", ' ', $rowHtml));

        $resultArr['status'] = true;
        $resultArr['cls'] = 'success';
        $resultArr['msg'] = 'تم الحفظ بنجاح';
        $resultArr['row'] = $rowDonate;

        $resultArr['rowHtml'] = $rowHtml;
        return response()->json($resultArr);
    }


    public function editAjax(DonateworthRequest $requset ,$id_donate){
        $rowDonate= Donateworth::find($id_donate);
        if(!$rowDonate){
            $resultArr['status'] = false;
            $resultArr['cls'] = 'error';
            $resultArr['msg'] = 'שורה לא קיימת';
            return response()->json($resultArr);
        }
        $resultArr['status'] = true;
        $resultArr['cls'] = 'info';
        $resultArr['msg'] = 'עריכת שורה';
        $resultArr['row'] = $rowDonate;
        return response()->json($resultArr);
    }

    public function updateAjax(DonateworthRequest $requset ,$id_donate){

        if($id_donate == 0){
            $resultArr['status'] = false;
            $resultArr['cls'] = 'error';
            $resultArr['msg'] = 'תקלה בשמירה';
            return response()->json($resultArr);
        }

        $rowDonate= Donateworth::find($id_donate);
        if(!$rowDonate){
            $resultArr['status'] = false;
            $resultArr['cls'] = 'error';
            $resultArr['msg'] = 'שורה לא קיימת';
            return response()->json($resultArr);
        }

        $enterp = explode('*',$requset->enterp);

        $id_enter = $enterp[0];
        $id_proj = $enterp[1];

        $rowDonate->datedont = $requset->datedont;
        $rowDonate->id_enter = $id_enter;
        $rowDonate->id_proj = $id_proj;
        $rowDonate->id_city = $requset->id_city;
        $rowDonate->id_typedont = $requset->id_typedont;
        $rowDonate->amount = $requset->amount;
        $rowDonate->description = $requset->description;
        $rowDonate->namedont = $requset->namedont;

        $rowDonate->save();

        $rowDonate= Donateworth::with(['enterprise','projects','city','donatetype'])->find($id_donate);

        $rowHtml = view('layout.includes.linedonate',compact('rowDonate'))->render();

        $resultArr['status'] = true;
        $resultArr['cls'] = 'success';
        $resultArr['msg'] = 'تم الحفظ بنجاح';
        $resultArr['row'] = $rowDonate;
        $resultArr['rowHtml'] = $rowHtml;
        $resultArr['rowHtmlArr'] = $this->rowHtmlToArray($rowHtml);
        return response()->json($resultArr);
    }


    public function deleteAjax($id_donate)
    {
        $rowDonate= Donateworth::find($id_donate);
        if(!$rowDonate){
            $resultArr['status'] = false;
            $resultArr['cls'] = 'error';
            $resultArr['msg'] = 'שורה לא קיימת';
            return response()->json($resultArr);
        }

        $rowDonate->delete();

        $resultArr['status'] = true;
        $resultArr['cls'] = 'success';
        $resultArr['msg'] = 'تم الحذف بنجاح';
        return response()->json($resultArr);

    }

}
