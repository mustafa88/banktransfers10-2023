<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bank\TitleOneRequset;
use Illuminate\Http\Request;
use App\Models\Bank\Title_one;
class TitleOneController extends Controller
{
    //

    public function test(){
        $titleone =  Title_one::with(['titleTwo'])->get();
        return $titleone;
    }

    public function showTable(){
        $titleone = Title_one::with(['titleTwo'])->get()->toArray();
        return view('managetable.titletable', compact('titleone'))
            ->with(
                [
                    'pageTitle' => "כותרת ראשית",
                    'subTitle' => 'ניהול שדות כותרת ראשית',
                ]
            );
    }

    public function store(TitleOneRequset $requset){
        $arrDate = [
            'tone_text' => $requset->tone_text,
            'tone_notactive' => 0,
        ];
        Title_one::create($arrDate);
        return redirect()->back()->with("success", "تم الحفظ بنجاح");
    }
}
