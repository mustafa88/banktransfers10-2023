<?php

namespace App\Models\Usb;

use App\Models\bank\City;
use App\Models\bank\Enterprise;
use App\Models\bank\Projects;
use App\Models\Bank\Title_two;
use App\Models\bank\Expense;
use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usbexpense extends Model
{
    use HasFactory ,HasUuid;
    protected  $table = 'Usbexpense';
    protected $fillable = ['uuid_usb','id_enter','id_proj','id_city',
        'dateexpense','asmctaexpense',
        'id_expense','id_expenseother',
        'amount','id_titletwo','dateinvoice','numinvoice','note','created_at','updated_at'];
    protected $hidden = [];
    protected $primaryKey = 'uuid_usb';
    //protected $keyType = 'string';
    //public $incrementing = false;
    public $timestamps = true;

    public function enterprise(){
        return $this->belongsTo(Enterprise::class,'id_enter','id');
    }

    public function projects(){
        return $this->belongsTo(Projects::class,'id_proj','id');
    }

    public function city(){
        return $this->belongsTo(City::class,'id_city','city_id');
    }

    public function expense(){
        return $this->belongsTo(Expense::class,'id_expense','id');
    }

    public function titletwo(){
        return $this->belongsTo(Title_two::class,'id_titletwo','ttwo_id');
    }
}


