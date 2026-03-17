<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $primaryKey = 'id_account';
    protected $table = 'bankaccounts';
    protected $connection = 'mysql2';
    public $timestamps = false;
    
    protected $fillable = [
        'cashier_id',
        'institution',
        'account_number',
        'account_name',
        'transit_number',
        'institution_number',
        'specimen',
        'specimen_file_name',
        'description',
    ];
}