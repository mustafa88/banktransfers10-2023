<?php

namespace App\Http\Controllers\Usb;

use App\Http\Controllers\Controller;
use App\Http\Requests\Usb\UsbExpenseRequest;
use App\Http\Requests\Usb\UsbIncomeRequest;
use App\Models\bank\City;
use App\Models\bank\Enterprise;
use App\Models\bank\Expense;
use App\Models\bank\Income;
use App\Models\bank\Projects;
use App\Models\Bank\Title_two;
use App\Models\Usb\Usbexpense;
use App\Traits\HelpersTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsbExpenseController extends Controller
{
    use HelpersTrait;

    public function index(Request $request,$id_entrep,$id_proj,$id_city)
    {
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

        $a_title = Enterprise::find($id_entrep)->name . " => ";
        $a_title .= Projects::find($id_proj)->name . " => ";
        $a_title .= City::find($id_city)->city_name;

        $expense = Expense::whereHas('projects', function($q) use ($id_proj){
            $q->where('projects.id', $id_proj)->where('inactive','0');
        })->get();

        $title_two = Title_two::Where('ttwo_one_id',1)->get();

        $usbexpense = Usbexpense::with(['enterprise','projects','city','expense','titletwo'])
        ->where('dateexpense', '>=', $showLineFromDate)
        ->where('dateexpense', '<=', $showLineToDate)
        ->where('id_enter',$id_entrep)
        ->where('id_proj',$id_proj)
        ->where('id_city',$id_city)
        ->get();

        //return $usbexpense;
        $param_url = ['id_entrep'=>$id_entrep,'id_proj'=>$id_proj,'id_city'=>$id_city];

        $dataTables='v1';
        return view('usb.expense' ,
            //compact('enterprise','city','donatetype','donateworth')
            compact('usbexpense','expense','title_two','param_url','dataTables')
        )
            ->with(
                [
                    'pageTitle' => "سجل المصروفات {$a_title}",
                    'subTitle' => 'سجل المصروفات للمشروع',
                ]
            );
    }

    public function index_entrep(Request $request,$id_entrep,$id_city)
    {
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

        $a_title = Enterprise::find($id_entrep)->name . " => ";
        $a_title .= City::find($id_city)->city_name;

        $projects = Projects::whereHas('city', function ($q) use ($id_city) {
            $q->where('city.city_id', $id_city);
        })->whereHas('enterprise', function ($q) use ($id_entrep) {
            $q->where('enterprise.id', $id_entrep);
        })
            ->get();

        $title_two = Title_two::Where('ttwo_one_id',1)->get();

        $usbexpense = Usbexpense::with(['enterprise','projects','city','expense','titletwo'])

            ->where('dateexpense', '>=', $showLineFromDate)
            ->where('dateexpense', '<=', $showLineToDate)
            ->where('id_enter',$id_entrep)
            //->where('id_proj',$id_proj)
            ->where('id_city',$id_city)
            ->get();

        //return $usbexpense;
        $param_url = ['id_entrep'=>$id_entrep,'id_city'=>$id_city];

        $dataTables='v1';
        return view('usb.expenseentrep' ,
            //compact('enterprise','city','donatetype','donateworth')
            compact('usbexpense','projects','title_two','param_url','dataTables')
        )
            ->with(
                [
                    'pageTitle' => "سجل المصروفات {$a_title}",
                    'subTitle' => 'سجل المصروفات للمشروع',
                ]
            );
    }


    public function storeAjax(UsbExpenseRequest $request, $id_entrep, $id_proj, $id_city){

        try {
            \DB::beginTransaction();

            if($request->id_line != 0){
                $resultArr['status'] = false;
                $resultArr['cls'] = 'error';
                $resultArr['msg'] = 'תקלה בשמירה';
                return $resultArr;
            }


            if($request->id_expense=='999999' and $request->id_expenseother==null){
                $resultArr['status'] = false;
                $resultArr['cls'] = 'error';
                $resultArr['msg'] = 'يجب كتابة مورد';
                return $resultArr;
            }

            $arrDate = [
                'id_enter' =>$id_entrep,
                'id_proj' => $id_proj,
                'id_city' => $id_city,
                'dateexpense' => $request->dateexpense,
                'asmctaexpense' => $request->asmctaexpense,
                //'id_expense' => $request->id_expense,
                'id_expenseother' => $request->id_expenseother,
                'amount' => $request->amount,
                'id_titletwo' => $request->id_titletwo,
                'dateinvoice' => $request->dateinvoice,
                'numinvoice' => $request->numinvoice,
                'note' => $request->note,
            ];

            if($request->id_expense!='999999'){
                $arrDate['id_expense'] = $request->id_expense;
            }

            $rowinsert = Usbexpense::create($arrDate);

            $rowUsbexpense = Usbexpense::with(['enterprise','projects','city','expense','titletwo'])
                ->find($rowinsert->uuid_usb );;

            $rowHtml =view('layout.includes.usbexpense',['rowData' => $rowUsbexpense])->render();
            $rowHtml = trim(preg_replace("/\s+/", ' ', $rowHtml));

            $resultArr['status'] = true;
            $resultArr['cls'] = 'success';
            $resultArr['msg'] = 'تم الحفظ بنجاح';
            $resultArr['row'] = $rowUsbexpense;

            $resultArr['rowHtml'] = $rowHtml;

            \DB::commit();
            return $resultArr;
        }catch(\Exception $exp) {
            \DB::rollBack();

            $resultArr['status'] = false;
            $resultArr['cls'] = 'error';
            $resultArr['msg'] = 'حصل خطا اثناء الحفظ';
            $resultArr['errormsg'] = $exp->getMessage();
            return $resultArr;
        }
    }

    public function updateAjax(UsbExpenseRequest $request, $id_entrep, $id_proj, $id_city, $uuid_usbexpense){
        try {
            \DB::beginTransaction();

            if($uuid_usbexpense == 0){
                $resultArr['status'] = false;
                $resultArr['cls'] = 'error';
                $resultArr['msg'] = 'תקלה בשמירה';
                return $resultArr;
            }



            $rowUsbexpense = Usbexpense::where('id_enter',$id_entrep)
                //->where('id_proj',$id_proj)
                ->where('id_city',$id_city)
                ->find($uuid_usbexpense);

            if(!$rowUsbexpense){
                $resultArr['status'] = false;
                $resultArr['cls'] = 'error';
                $resultArr['msg'] = 'שורה לא קיימת';
                return $resultArr;
            }


            if($request->id_expense=='999999' and $request->id_expenseother==null){
                $resultArr['status'] = false;
                $resultArr['cls'] = 'error';
                $resultArr['msg'] = 'يجب كتابة مورد';
                return $resultArr;
            }



            $rowUsbexpense->id_proj = $id_proj;
            $rowUsbexpense->dateexpense =  $request->dateexpense;
            $rowUsbexpense->asmctaexpense =  $request->asmctaexpense;
            $rowUsbexpense->amount =  $request->amount;
            $rowUsbexpense->id_titletwo =  $request->id_titletwo;
            $rowUsbexpense->dateinvoice =  $request->dateinvoice;
            $rowUsbexpense->numinvoice =  $request->numinvoice;
            $rowUsbexpense->note =  $request->note;

            if($request->id_expense!='999999'){
                $rowUsbexpense->id_expense = $request->id_expense;
                $rowUsbexpense->id_expenseother =  null;
            }else{
                $rowUsbexpense->id_expense = null;
                $rowUsbexpense->id_expenseother =  $request->id_expenseother;
            }

            $rowUsbexpense->save();



            $rowUsbexpense = Usbexpense::with(['enterprise','projects','city','expense','titletwo'])
                ->find($uuid_usbexpense );

            $rowHtml =view('layout.includes.usbexpense',['rowData' => $rowUsbexpense])->render();


            $resultArr['status'] = true;
            $resultArr['cls'] = 'success';
            $resultArr['msg'] = 'تم الحفظ بنجاح';
            $resultArr['row'] = $rowUsbexpense;
            $resultArr['rowHtml'] = $rowHtml;
            $resultArr['rowHtmlArr'] = $this->rowHtmlToArray($rowHtml);

            \DB::commit();

            return $resultArr;
        }catch(\Exception $exp) {
            \DB::rollBack();
            $resultArr['status'] = false;
            $resultArr['cls'] = 'error';
            $resultArr['msg'] = 'حصل خطا اثناء الحفظ';
            $resultArr['errormsg'] = $exp->getMessage();
            return $resultArr;
        }
    }

    public function editAjax(UsbExpenseRequest $request,$id_entrep,$id_proj,$id_city,$uuid_usbexpense){
        try {
            \DB::beginTransaction();

            $rowUsbexpense = Usbexpense::where('id_enter',$id_entrep)
                //->where('id_proj',$id_proj)
                ->where('id_city',$id_city)
                ->find($uuid_usbexpense);

            if(!$rowUsbexpense){
                $resultArr['status'] = false;
                $resultArr['cls'] = 'error';
                $resultArr['msg'] = 'שורה לא קיימת';
                return response()->json($resultArr);
            }

            $resultArr['status'] = true;
            $resultArr['cls'] = 'info';
            $resultArr['msg'] = 'עריכת שורה';
            $resultArr['row'] = $rowUsbexpense;

            \DB::commit();
            return $resultArr;
        }catch(\Exception $exp) {
            \DB::rollBack();
            $resultArr['status'] = false;
            $resultArr['cls'] = 'error';
            $resultArr['msg'] = 'حصل خطا اثناء الحفظ';
            $resultArr['errormsg'] = $exp->getMessage();
            return $resultArr;
        }
    }

    public function deleteAjax($id_entrep,$id_proj,$id_city,$uuid_usbexpense){
        try {
            \DB::beginTransaction();
            $rowUsbexpense = Usbexpense::where('id_enter',$id_entrep)
                //->where('id_proj',$id_proj)
                ->where('id_city',$id_city)
                ->find($uuid_usbexpense);

            if(!$rowUsbexpense){
                $resultArr['status'] = false;
                $resultArr['cls'] = 'error';
                $resultArr['msg'] = 'שורה לא קיימת';
                return response()->json($resultArr);
            }
            $rowUsbexpense->delete();
            $resultArr['status'] = true;
            $resultArr['cls'] = 'success';
            $resultArr['msg'] = 'تم الحذف بنجاح';

            \DB::commit();
            return $resultArr;
        }catch(\Exception $exp) {
            \DB::rollBack();
            $resultArr['status'] = false;
            $resultArr['cls'] = 'error';
            $resultArr['msg'] = 'حصل خطا اثناء الحفظ';
            $resultArr['errormsg'] = $exp->getMessage();
            return $resultArr;
        }


    }

}
