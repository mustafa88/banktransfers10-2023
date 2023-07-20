
    <script type="text/javascript">


        $(document).ready(function(){

            InitPage();
        });


        /**
         */
        $('#btn_save').on( 'click', function () {

        });

        $(document).on('click', 'a.delete_row', function (e) {

        });

        $(document).on('click', '#btn_cancel', function (e) {
            InitPage();
        });

        function urlParam(url ,id_line){
            url = url.replace('p1', _param_url['id_city'] );
            if(id_line!= undefined ){
                url += "/" + id_line;
            }
            return url;
        }




        function InitPage(customRest){


        }

    </script>


