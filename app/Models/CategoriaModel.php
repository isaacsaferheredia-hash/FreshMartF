<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoriaModel
{
    public function products(): HasMany{
        return $this->hasMany("/Models/ProductModel", "products_id");
    }

}
