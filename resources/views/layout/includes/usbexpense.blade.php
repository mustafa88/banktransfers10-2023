@php
    $colortd= "bg-green";
    if(isset($rowData['id_proj'])){
        switch ($rowData['id_proj']){
                case "2":
                    $colortd= "bg-info";
                    break;
                    case "3":
                        $colortd= "bg-warning";
                        break;
                        case "12":
                            $colortd= "bg-inverse  ";
                            break;
            }
    }


@endphp

<tr>
    <td>{{$rowData['dateexpense']}}</td>
    <td><a class="{{$colortd}}">{{$rowData['projects']['name']}}</a></td>
    {{--<td>{{$rowData['asmctaexpense']}}</td>--}}
    <td>
        @if(is_null($rowData['id_expense']))
            {{$rowData['id_expenseother']}}
        @else
            {{$rowData['expense']['name']}}
        @endif
    </td>
    <td>{{$rowData['amount']}}</td>
    <td>{{$rowData['titletwo']['ttwo_text']}}</td>
    {{--<td>{{$rowData['numinvoice']}}</td>
    <td>{{$rowData['dateinvoice']}}</td>--}}
    <td>{{$rowData['note']}}</td>

    <td>
    <div class="btn-group mb-1">
        <button class="btn dropdown-toggle btn-primary" type="button" data-toggle="dropdown"
                aria-expanded="false">בחר
        </button>
        <div class="dropdown-menu dropmenu" role="menu" x-placement="bottom-start">
            <a class="dropdown-item edit_row" href="javascript:void(0)" data-idline="{{$rowData['uuid_usb']}}"><i
                    class="far fa-edit"></i> تعديل</a>
            <a class="dropdown-item delete_row" href="javascript:void(0)" data-idline="{{$rowData['uuid_usb']}}"><i
                    class="far fa-trash-alt"></i> حذف</a>
        </div>
    </div>
    </td>
</tr>

