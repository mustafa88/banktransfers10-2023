@extends('layout.mainangle')

@section('page-head')
    <style>

    </style>
@endsection
@section('page-content')

    <div class="card card-default">
        <div class="card-header">ملخص المصروفات والمدخولات</div>
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
            <form>
                <div class="form-row align-items-center">
                    <div class="col-auto">
                        <label for="fdate">מתאריך</label>
                        <input type="date" name="fromDate" id="fromDate" value="{{session()->get('showLineFromDate')}}"
                               class="form-control mb-2">
                    </div>

                    <div class="col-auto">
                        <label for="tdate">עד תאריך</label>
                        <input type="date" name="toDate" id="toDate" value="{{session()->get('showLineToDate')}}"
                               class="form-control mb-2">
                    </div>

                    <div class="col-auto">
                        <button class="btn btn-primary mb-2" type="submit">Submit</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <div class="card" role="tabpanel">
        <!-- Nav tabs-->
        <ul class="nav nav-tabs nav-fill" role="tablist">
            @foreach($allCity as $city)
                <li class="nav-item" role="presentation"><a class="nav-link " href="#idcity{{$city['id_city']}}"
                                                            aria-controls="moneyin" role="tab" data-toggle="tab"
                                                            aria-selected="true">{{$city['city_name']}}</a></li>
            @endforeach
        </ul>
        <div class="tab-content p-0">
            @foreach($allCity as $city)
            <div class="tab-pane " id="idcity{{$city['id_city']}}" role="tabpanel">

                <div class="col-4">
                    <div class="card card-default">
                        <div class="card-header">مجموع المدخولات حسب طريقة التبرع</div>
                        <div class="card-body">
                            <div class="table-responsive table-bordered">
                                @include('layout.includes.displaytable',
                    ['tableBody' => $income_title_curr[$city['id_city']],
                     'tableKeyBody' => array('ttwo_text','amount','symbol'),
                     'tableHead' => array('طريقة التبرع','المبلغ','العملة'),
                     ])
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="card card-default">
                        <div class="card-header">مجموع المدخولات حسب نوع التبرع</div>
                        <div class="card-body">
                            <div class="table-responsive table-bordered">
                                @include('layout.includes.displaytable',
                    ['tableBody' => $income_typeincom_curr[$city['id_city']],
                     'tableKeyBody' => array('name','amount','symbol'),
                     'tableHead' => array('نوع التبرع','المبلغ','العملة'),
                     ])
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="card card-default">
                        <div class="card-header">مجموع المدخولات للمشاريع حسب نوع التبرع</div>
                        <div class="card-body">
                            <div class="table-responsive table-bordered">
                                @include('layout.includes.displaytable',
                    ['tableBody' => $income_proj_typeincom_curr[$city['id_city']],
                     'tableKeyBody' => array('projectname','name','amount','symbol'),
                     'tableHead' => array('نوع التبرع','المبلغ','العملة'),
                     ])
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="card card-default">
                        <div class="card-header">مجموع المدخولات ل طريقة التبرع + نوع التبرع</div>
                        <div class="card-body">
                            <div class="table-responsive table-bordered">
                                @include('layout.includes.displaytable',
                    ['tableBody' => $income_title_typeincom_curr[$city['id_city']],
                     'tableKeyBody' => array('ttwo_text' ,'name','amount','symbol'),
                     'tableHead' => array('طريقة التبرع','نوع التبرع','المبلغ','العملة'),
                     ])
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-4">
                    <div class="card card-default">
                        <div class="card-header text-danger">مجموع المصروفات  طريقة الدفع</div>
                        <div class="card-body">
                            <div class="table-responsive table-bordered">
                                @include('layout.includes.displaytable',
                    ['tableBody' => $expense_title[$city['id_city']],
                     'tableKeyBody' => array('ttwo_text','amount'),
                     'tableHead' => array('طريقة التبرع','المبلغ'),
                     ])
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="card card-default">
                        <div class="card-header text-danger">مجموع المصروفات المشروع +طريقة الدفع</div>
                        <div class="card-body">
                            <div class="table-responsive table-bordered">
                                @include('layout.includes.displaytable',
                    ['tableBody' => $expense_proj_title[$city['id_city']],
                     'tableKeyBody' => array('projectname' ,'ttwo_text','amount'),
                     'tableHead' => array('المشروع' ,'طريقة التبرع','المبلغ'),
                     ])
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            @endforeach
        </div>
    </div>

@endsection


@section('page-script')
    {{--  load file js from folder public --}}
@endsection

{{--
@foreach($row_titletwo as $item)
<div class="table-responsive table-bordered">
    <table class="table">
        <thead>
        <tr>
            <th colspan="3" class="bg-primary">{{$item['ttwo_text']}}</th>
        </tr>
<tr class="bg-primary-light">
    <th>نوع</th>
    <th>مبلغ بالعملة</th>
    <th>عدد المتبرعين</th>
</tr>
        </thead>
        <tbody>
        @foreach($arrProg[$item['id_titletwo']] as $key1=>$item1)

                <tr >
                    <td>{{$item1->nameincome}}</td>
                    <td>{{$item1->amount}}{{$item1->symbol}}</td>
                    <td>{{$item1->count}}</td>
                </tr>

        @endforeach
        </tbody>
    </table>
</div>
<div class="table-responsive table-bordered">
    <table class="table">
<thead>
<tr class="bg-warning">
    <th colspan="3">جدول تلخيص - {{$item['ttwo_text']}}</th>
</tr>

</thead>
        <tbody>
        @foreach($arrProgSum[$item['id_titletwo']] as $key1=>$item1)

            <tr>
                <td>مجموع {{$item1->symbol}}</td>
                <td>{{$item1->amount}}{{$item1->symbol}}</td>
                <td>{{$item1->count}} متبرعين</td>
            </tr>

        @endforeach
        </tbody>
    </table>
</div>
@endforeach


<div class="table-responsive table-bordered">
    <table class="table">
        <thead>
        <tr>
            <th colspan="3" class="bg-danger">مصروفات</th>
        </tr>
        <tr class="bg-primary-light">
            <th>طريقة الدفع</th>
            <th>المبلغ</th>
            <th>عدد الدفعات</th>
        </tr>
        </thead>
        <tbody>
        @foreach($arrProgExpense as $key1=>$item1)

            <tr >
                <td>{{$item1->ttwo_text}}</td>
                <td>{{$item1->amount}}</td>
                <td>{{$item1->count}}</td>
            </tr>

        @endforeach
        </tbody>
    </table>
</div>

--}}
