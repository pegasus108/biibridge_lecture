<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

abstract class HondanaModelBase extends Model
{
    use HasFactory;

    const CREATED_AT = 'c_stamp';
    const UPDATED_AT = 'u_stamp';
    protected $primaryKey;

    public function __construct(array $attributes = [])
    {
        $this->primaryKey =  $this->table . '_no';
        parent::__construct($attributes);
    }
}
