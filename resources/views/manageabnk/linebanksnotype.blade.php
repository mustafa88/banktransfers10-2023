@extends('layout.mainangle')


@section('page-head')
    <!-- Datatables-->
    <link rel="stylesheet" href="{{ asset('angle/vendor/datatables.net-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('angle/vendor/datatables.net-keytable-bs/css/keyTable.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('angle/vendor/datatables.net-responsive-bs/css/responsive.bootstrap.css') }}">
    <style>
        .dropmenu{
            position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 34px, 0px);
        }
    </style>
@endsection

@section('page-content')

    @if($errors->any())
        {!! implode('', $errors->all('<div>:message</div>')) !!}
    @endif
    <div>
        <h3>معطيات حساب بنك {{$bank['enterprise']['name']}} @if(!empty($bank['projects']['name'])) مشروع {{$bank['projects']['name']}} @endif</h3>
    </div>


        <form method="post" action="{{route('linebanks.storeselecttitle',$bank['id_bank'])}}" id="formselct">
        @csrf

    <div class="card card-default">
        <div class="card-header">עדכון גורף לכל השורות המסומנות</div>
        <div class="card-body">

            @if (Session::has('successupdateselect'))
                <div class="row">
                    <div class="alert alert-success" role="alert"><strong>{{ Session::get('successupdateselect') }}</strong></div>
                </div>
            @endif

                <div class="form-row align-items-center">
                    <div class="col-auto">
                        <label for="id_titletwo">עדכון גורף ל - סוג תנועה</label>
                        <select class="form-control custom-select custom-select-sm " name="idselect_titletwo" id="idselect_titletwo"  >
                            <option value="0">בחר</option>
                            @foreach($title as $item)
                                <optgroup label="{{$item['tone_text']}}">
                                    @if(isset($item['title_two']))
                                        @foreach ($item['title_two'] as $item2 )
                                            <option value="{{$item2['ttwo_id']}}"> {{$item2['ttwo_text']}}</option>
                                        @endforeach
                                    @endif
                                </optgroup>
                            @endforeach

                        </select>
                    </div>
                    <div class="col-auto">
                        <input type="submit" name="btn_savetitle" id="btn_savetitle" value="حفظ" class="btn btn-primary mb-2">
                    </div>

                </div>

        </div>
    </div>

    <div>

        <!-- DATATABLE DEMO 1-->
        <div class="card card-default">
            <div class="card-header">
                <div class="card-title">שורות בנק</div>

                <div class="form-row align-items-center">
                    <div class="col-auto">
                        <label  for="fromdate">מתאריך</label>
                        <input type="date"  name="fromdate" id="fromdate" value="{{session()->get('showLineBankFromDate')}}" class="form-control" >
                    </div>
                    <div class="col-auto">
                        <label for="todate" >עד תאריך</label>
                        <input type="date"  name="todate" id="todate" value="{{session()->get('showLineBankToDate')}}" class="form-control" >
                    </div>
                    <div class="col-auto">
                        <label for="showTitleTwo">סוג תנועה</label>
                        <select class="form-control custom-select custom-select-sm  " name="showTitleTwo" id="showTitleTwo"  >
                            <option value="0">הכל</option>
                            @foreach($title as $item)
                                <optgroup label="{{$item['tone_text']}}">
                                    @if(isset($item['title_two']))
                                        @foreach ($item['title_two'] as $item2 )
                                            <option value="{{$item2['ttwo_id']}}"
                                           @if(session()->get('showLineBankTitleTwo')==$item2['ttwo_id']) selected @endif> {{$item2['ttwo_text']}}</option>
                                        @endforeach
                                    @endif
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <button class="mb-2 btn btn-success" type="button" id="showbydate">عرض</button>
                    </div>
                </div>

                <div class="text-sm">
                    <button class="mb-1 btn btn-outline-primary" type="button" onclick="selectAll()">סמן הכל</button>
                    <button class="mb-1 btn btn-outline-warning" type="button" onclick="unSelectAll()">בטל סימון</button>
                </div>

                <div class="row row-flush">
                    <div class="col-12 col-md-1">
                        <div class="table-success text-center">שורה תקינה</div>
                    </div>
                    <div class="col-12 col-md-1">
                        <div class="table-warning text-center">שורה לא תקינה</div>
                    </div>
                    <div class="col-12 col-md-1">
                        <div class="table-danger text-center">שורה כפולה</div>
                    </div>
                </div>




            </div>
            <div class="card-body">
                <table class="table table-striped my-4 w-100 hover" id="datatable1">
                    <thead>
                    <tr>
                        <th>תאריך תנועה</th>
                        <th>תיאור</th>
                        <th>אסמכתא</th>
                        <th>חובה</th>
                        <th>זכות</th>
                        <th>סוג תנועה</th>
                        <th>פעולה</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($banksline as $item)
                        @include('layout.includes.linedetail_displayrowl_notype',['rowBanksLine' => $item])
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    </form>
@endsection



@section('page-script')

    <!-- Datatables-->
    <script src="{{ asset('angle/vendor/datatables.net/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('angle/vendor/datatables.net-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('angle/vendor/datatables.net-buttons/js/dataTables.buttons.js') }}"></script>
    <script src="{{ asset('angle/vendor/datatables.net-buttons-bs/js/buttons.bootstrap.js') }}"></script>
    <script src="{{ asset('angle/vendor/datatables.net-buttons/js/buttons.colVis.js') }}"></script>
    <script src="{{ asset('angle/vendor/datatables.net-buttons/js/buttons.flash.js') }}"></script>
    <script src="{{ asset('angle/vendor/datatables.net-buttons/js/buttons.html5.js') }}"></script>
    <script src="{{ asset('angle/vendor/datatables.net-buttons/js/buttons.print.js') }}"></script>
    <script src="{{ asset('angle/vendor/datatables.net-keytable/js/dataTables.keyTable.js') }}"></script>
    <script src="{{ asset('angle/vendor/datatables.net-responsive/js/dataTables.responsive.js') }}"></script>
    <script src="{{ asset('angle/vendor/datatables.net-responsive-bs/js/responsive.bootstrap.js') }}"></script>
    <script src="{{ asset('angle/vendor/jszip/dist/jszip.js') }}"></script>
    <script src="{{ asset('angle/vendor/pdfmake/build/pdfmake.js') }}"></script>
    <script src="{{ asset('angle/vendor/pdfmake/build/vfs_fonts.js') }}"></script>
    <!-- FILESTYLE-->
    <script src="{{ asset('angle/vendor/bootstrap-filestyle/src/bootstrap-filestyle.js') }}"></script><!-- TAGS INPUT-->
    <script src="{{ asset('angle/vendor/sweetalert2/dist/sweetalert2.all.min.js') }}"></script><!-- SWEET ALERT-->

    @include( "scripts.managebank.linebanks" )

    @include('layout.includes.linedetailedit')

    @stack('linedetailedit-script')

@endsection





