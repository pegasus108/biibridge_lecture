<?php

namespace App\Repositories;

use App\Models\Contact;
use Illuminate\Support\Facades\DB;

class InfoRepository
{
    public function getInfoList($limit, $use_paginate = false, $contact_status = null)
    {
        $contact_list_query = Contact::select('contact_no', 'email', 'name', 'title','value')
            ->orderBy('public_date', 'desc');
        }
        if ($use_paginate) {
            return $contact_list_query->paginate($limit);
        } else {
            return $contact_list_query->limit($limit)->get();
        }
    }

    public function getInfoDetail($contact_no)
    {
        return Contact::select('contact_no', 'email', 'name', 'title','value')
            ->where('contact_no', $contact_no)
            ->first();
    }

}
