
    <script type="text/javascript">
        $(document).ready(function(){

           // let rowNode = myTable.row.add( [ '1', 32, 'Edinburgh' ,'xxxx'] ).draw().node();
            $('#myform').submit(function() {

                //return false;

            });

        });

        $(document).on('change', '.allradio', function (e) {

            $("#enterp").val($(this).data('enterp'));

        });



    </script>


