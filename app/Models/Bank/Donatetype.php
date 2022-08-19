<?php

namespace App\Models\Bank;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bank\Donateworth;
class Donatetype extends Model
{
    use HasFactory;
    protected  $table = 'Donatetype';
    protected $fillable = ['id','name'];
    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = true;


    public function donateworth(){
        return $this->hasMany(Donateworth::class,'id_typedont','id');
    }
}
