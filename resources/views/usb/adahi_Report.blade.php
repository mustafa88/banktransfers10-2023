@extends('layout.mainangle')

@section('page-head')
    <style>

    </style>
@endsection
@section('page-content')

    <div class="card card-default">
        <div class="card-header">ملخص مشروع الاضاحي</div>
        <div class="card-body">
            <div class="row">
                @if($errors->any())
                    {!! implode('', $errors->all('<div>:message</div>')) !!}
                @endif
                @if (Session::has('success'))
                    <div class="row">
                        <div class="alert alert-success" role="alert"><strong>{{ Session::get('success') }}</strong>
                        </div>
                    </div>
                @endif
            </div>
            <form  >
                <div class="form-row align-items-center">
                    <div class="col-auto">
                        <label for="fromDate">מתאריך</label>
                        <input type="date" name="fromDate" id="fromDate" value="{{session()->get('showLineFromDate')}}"
                               class="form-control mb-2">
                    </div>

                    <div class="col-auto">
                        <label for="toDate">עד תאריך</label>
                        <input type="date" name="toDate" id="toDate" value="{{session()->get('showLineToDate')}}"
                               class="form-control mb-2">
                    </div>

                    <div class="col-auto">
                        <button class="btn btn-primary mb-2" type="button" id="showData">عرض</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <div class="card card-default">
        <div class="card-header">ملخص مشروع الاضاحي</div>
        <div class="card-body collapse show">

            <div class="row">
                <div class="col-6">
                    <div class="card card-default">
                        <div class="card-header">تلخيص مجموع مشروع الاضاحي </div>
                        <div class="card-body">
                            <div class="table-responsive table-bordered">
                                <table class="table">
                                    <thead>
                                    <tr>
                                            <th>البلد</th>
                                            <th>مجموع - خاروف</th>
                                            <th>مجموع - اسباع</th>
                                            <th>مجموع - اسباع</th>
                                            <th>مبلغ خروف</th>
                                            <th>مبلغ عجل</th>
                                            <th>مبلغ مصاريف الذبح</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($totalReportArr as $k=>$item)
                                            <tr>
                                                <td>{{$item['city_name']}}</td>
                                                <td>{{number_format($item['countsheep'],0)}}</td>
                                                <td>{{number_format($item['countcowseven'],0)}}</td>
                                                <td>{{number_format($item['countcow'],0)}}</td>
                                                <td>{{number_format($item['sumsheepprice'],0)}}</td>
                                                <td>{{number_format($item['sumcow'],0)}}</td>
                                                <td>{{number_format($item['sumtotalall'],0)}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-6">
                    <div class="card card-default">
                        <div class="card-header">تلخيص مجموع مشروع الاضاحي </div>
                        <div class="card-body">
                            <div class="table-responsive table-bordered">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>البلد</th>
                                        <th>طريقة الدغع</th>
                                        <th>المبلغ</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($methodBuy as $k=>$item)
                                        <tr>
                                            <td>{{$item['city_name']}}</td>
                                            <td>{{$item['ttwo_text']}}</td>
                                            <td>{{number_format($item['sumtotalall'],0)}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                        <div class="col-6">
                            <div class="card card-default">
                                <div class="card-header">منتظرين التحلل</div>
                                <div class="card-body">
                                    <div class="table-responsive table-bordered">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>البلد</th>
                                                <th>الاسم</th>
                                                <th>هاتف</th>

                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($ReportThll as $k=>$item)
                                                <tr>
                                                    <td>{{$item['city']['city_name']}}</td>
                                                    <td>{{$item['nameclient']}}</td>
                                                    <td>{{$item['phone']}}</td>

                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
            </div>


            <div class="row">
                <div class="col-6">
                    <div class="card card-default">
                        <div class="card-header">جزء من الاضحية</div>
                        <div class="card-body">
                            <div class="table-responsive table-bordered">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>البلد</th>
                                        <th>الاسم</th>
                                        <th>هاتف</th>

                                        <th>تبرع ب</th>
                                        <th>وصف الجزء</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($ReportPartAdhi as $k=>$item)
                                        <tr>
                                            <td>{{$item['city']['city_name']}}</td>
                                            <td>{{$item['nameclient']}}</td>
                                            <td>{{$item['phone']}}</td>

                                            <td>
                                                @if($item['sheep']>0){{$item['sheep']}} - خروف<br>@endif
                                                @if($item['cowseven']>0){{$item['cowseven']}} - سبع عجل<br>@endif
                                                @if($item['cow']>0){{$item['cow']}} - عجل<br>@endif
                                            </td>
                                            <td>{{$item['partdesc']}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


@endsection


@section('page-script')
    {{--  load file js from folder public --}}

    @include( "scripts.usb.adahi_Report" )
    showData
@endsection
