
    <script type="text/javascript">
        let myTable,myRowTable=null;
        $(document).ready(function(){
            myTable = $('#datatable1').DataTable({
                'paging': true, // Table pagination
                'ordering': true, // Column ordering
                "order": [[ 0, 'asc'],[ 1, 'asc'],[ 2, 'asc']],
                'info': true, // Bottom left status text
                //fixedHeader: true,
                responsive: true,
                aLengthMenu: [
                    [25, 50, 100, 200, -1],
                    [25, 50, 100, 200, "All"]
                ],
                iDisplayLength: -1
            });
        });


        /**
         * שמירה
         * save new data or update data exists
         */
        $('#btn_save').on( 'click', function () {

            let id_line = $("#id_line").val();

            if((id_line=='0' && myRowTable!=null) || (id_line!='0' && myRowTable==null)){
                notify('תקלה - נא לדווח לאיש מחשוב');
                return;
            }

            //alert(id_line);
            if(id_line=='0'){
                //insert
                let url= '{{route('mainDonate.storeajax')}}';
                let resultAjax = SendToAjax(url,'POST');
                //console.log(resultAjax);
                if(resultAjax==undefined){
                    notify('حدث خطأ','error');
                    return false;
                }
                notify(resultAjax.msg ,resultAjax.cls);
                if(resultAjax.status===false){
                    return;
                }
                myTable.row.add($(resultAjax['rowHtml'])[0]).draw();
            }else{
                //return;
                //update
                //notify('update');
                let url= '{{route('mainDonate.updateajax')}}';
                url +="/"+id_line;
                let resultAjax = SendToAjax(url,'PUT');
                console.log(resultAjax);
                //return;
                if(resultAjax==undefined){
                    notify('حدث خطأ','error');
                    return false;
                }
                notify(resultAjax.msg ,resultAjax.cls);
                if(resultAjax.status===false){
                    return;
                }


                let numberRow = myTable.row(myRowTable)[0][0];
                let row = myTable.row(numberRow);
                let newData = resultAjax.rowHtmlArr;
                for (let i = 0; i < newData.length; i++) {
                    myTable.cell(row, i).data(newData[i]);
                }
                myTable.draw();
                //let thisRow = row.node();
                //$(thisRow).find('td').eq(1).css("background-color",newData['color_code']);


            }

            InitPage();
        });

        $(document).on('click', 'a.edit_row', function (e) {
            e.preventDefault();
            InitPage();
            let idline = $(this).data('idline');

            var nRow = $(this).parents('tr')[0];
            var aData = myTable.row(nRow).data();

            let url='{{route('mainDonate.editajax')}}';
            url +="/"+idline;

            let resultAjax = SendToAjax(url,'GET');

            if(resultAjax.status===false){
                notify(resultAjax.msg ,resultAjax.cls);
                return;
            }
            let row = resultAjax.row;
            $("#id_line").val('0');


            $("#datedont").val(row.datedont);
            $("#enterp").val(row.id_enter + "*" + row.id_proj);
            $("#id_city").val(row.id_city);
            $("#id_typedont").val(row.id_typedont);
            $("#amount").val(row.amount);
            $("#description").val(row.description);
            $("#namedont").val(row.namedont);

            myRowTable=nRow;
            $("#id_line").val(idline);

            $("#addline").collapse('show');
            $('html, body').animate({
                scrollTop: $("#addline").offset().top
            }, 800);

        });

        $(document).on('click', 'a.delete_row', function (e) {
            e.preventDefault();
            InitPage();
            var r = confirm("يرجى الموافقه على الحذف");
            if(r===false){
                return false;
            }


            var nRow = $(this).parents('tr')[0];
            var aData = myTable.row(nRow).data();
            let idline = $(this).data('idline');
            $("#id_line").val(idline);
            let url= '{{route('mainDonate.deleteajax')}}';
            url +="/"+idline;
            let resultAjax = SendToAjax(url,'DELETE');
            //console.log(resultAjax);
            if(resultAjax==undefined){
                notify('حدث خطأ','error');
                return false;
            }
            notify(resultAjax.msg ,resultAjax.cls);
            if(resultAjax.status===false){
                return;
            }
            myTable.row( nRow) .remove().draw();
            InitPage();
        });

        $(document).on('click', '#showbydate', function (e) {
            var fdate= $("#fromdate").val();
            var tdate= $("#todate").val();
            var showTitleTwo= $("#showTitleTwo").val();
            let url='{{ route('mainDonate.show') }}';

            if(fdate=="" || tdate==""){
                notify("תאריך לא תקין" ,"error");
                return false;
            }
            //url += "/" + fdate + "/" + tdate;
            //alert(url);
            window.location = url;
        });


        function InitPage(){
            myRowTable=null;
            $("#id_line").val('0');

            $("#datedont").val('');
            $("#enterp").val('0');
            $("#id_city").val('0');
            $("#id_typedont").val('0');
            $("#amount").val('');
            $("#description").val('');
            $("#namedont").val('');

        }


        function selectAll(){
            $("input[name='selectbox[]']").prop("checked", true);
        }

        function unSelectAll(){
            $("input[name='selectbox[]']").prop("checked", false);
        }

        function divlineditels(){
            var selectbox = $("input[name='selectbox[]']:checked");
            //alert(selectbox.length)
            let firsdata = $(selectbox [0]).data('titletwo');
            let idline = $(selectbox [0]).val();
            if(firsdata==0){
                Swal.fire('נא לבחור שורות מהטבלה', '', 'info')
                return false;
            }
            for (let i=0;i<selectbox.length;i++){
                if($(selectbox [i]).data('titletwo')!=firsdata){
                    Swal.fire('נא לבחור שורות מאותו סוג תנועה');
                    return false;
                }
            }
            dataObj = {};
            dataObj['idline']= $("#statos").val();

            //let url= ' route('linebanks.showrowdetils',$bank['id_bank']) ';
            url +="/"+idline;
            //alert(url);
            let resultAjax = SendToAjax(url,'GET','-1');
            if(resultAjax==undefined){
                notify('حدث خطأ','error');
                return false;
            }

            Swal.fire({
                title: '<strong>חלוקת שורה</strong>',
                //icon: 'info',
                html: resultAjax['html'],
                width: 1000,
                showDenyButton: true,
                //showCancelButton: true,
                confirmButtonText: 'حفظ',
                denyButtonText: `الغاء`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    var dataObj = {};
                    document.querySelectorAll('.inptrowdetl').forEach(function(el){
                        dataObj[el.id]= el.value;
                    });
                    dataObj['scome']='1';
                    //console.log(dataObj);
                    var counterSave=0;
                    for (let i=0;i<selectbox.length;i++) {
                        var idline_aj = $(selectbox[i]).val();
                        //let url= ' route('linedetail.storemultirowajax',$bank['id_bank'])  ';
                        url +="/"+idline_aj;
                        let resultAjax = SendToAjax(url,'POST',null,dataObj);
                        //console.log(resultAjax);
                        if(resultAjax!=undefined && resultAjax['status']!=undefined && resultAjax['status']==true){
                            counterSave++;
                            $(selectbox[i]).prop("checked", false);
                        }
                    }

                    Swal.fire(" נמשרו " + counterSave + " שורות מתוך " + selectbox.length + " שורות "  )
                } else if (result.isDenied) {
                    Swal.fire('שינוי לא בוצע', '', 'info')
                }
            })


        }
    </script>


