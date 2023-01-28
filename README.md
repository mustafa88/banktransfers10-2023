<p align="center">
Bank managment APP
</p>

<p align="center">

# setup after cloning project
1. create mysql schema/database "bank"
2. import bank.sql dump file into schema
3. configure env file:
   a. copy .env.example to .env
   b. change mysql section in .env: username password and DB
   c. cd htdocs\banktransfer
   d. run composer global require laravel/installer
   e. composer install
   f. weekly(composer update)
   g. php artisan key:generate
   k. php artisan serve

</p>

# Controllers
## הגדרות מערכת
In routes under "managetable" we have all routes to  'הגדרות מערכת

1.1 'managetable/title' = //כותרת ראשית ותת כותרת ראשית
    in showTable route
    goes to 
    'pageTitle' => "כותרת ראשית",
    'subTitle' => 'ניהול שדות כותרת ראשית',

# controller is TitleOneController.php
# tables: $table = 'title_one'; with titleTwo table
# model: use App\Models\Bank\Title_one;
# view: managetable.titletable
תת כותרת
TitleTwoController.php
# model: use App\Models\Bank\Title_two;

# Form requests 
in folder App\Http\Requests\Bank, the main use is to make validations before running the request.
It can also be used for authorization of users.

Flow:
1.  <form method="post" action="{{route('table.title.store')}}"> => TitleOneRequset => TitleOneController.class::store
    the connection between TitleOneRequset and controller TitleOneController managed by laravel when in method declaration of the 
    controller the type of request is used. Here we set " public function store('TitleOneRequset' $requset)"
