<?php

namespace App\Models;

use App\Models\Items\ItemSize;
use App\Models\Items\ItemImage;
use App\Models\Items\ItemContract;
use App\Models\Items\ItemEquipment;
use App\Models\Items\ItemMeasurement;
use App\QueryBuilders\ItemQueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Item extends Model
{
    protected $primaryKey = 'Items_Numbers';
    protected $table = 'items';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'Categories',
        'Names',
        'fr_Name',
        'unit_of_measurement',
        'Types',
        'Prices',
        'has_serial_number',
        'nb_place',
        'Available',
        'online_availability',
        'Status',
        'Overview',
        'fr_overview',
        'Descriptions',
        'fr_description',
        'Pictures',
        'subscription_frequency',
        'related_link',
        'item_doc',
        'initiale_prices',
        'contract',
        'item_date',
        'expiration_date',
    ];
    protected $casts = [
        'item_date' => 'datetime',
        'expiration_date' => 'datetime',
    ];
    protected $hidden = [
        'Pictures',
        'item_doc'
    ];

    public function getTNameAttribute() : ?string
    {
        return app()->getLocale() == 'fr' ? $this->fr_Name : $this->Names;
    }
    
    public function getTOverviewAttribute() : ?string
    {
        return app()->getLocale() == 'fr' ? $this->fr_overview : $this->Overview;
    }
    public function getTDescriptionAttribute() : ?string
    {
        return app()->getLocale() == 'fr' ? $this->fr_description : $this->Descriptions;
    }
    
    public function getFormatedPriceAttribute() : string
    {
        return number_format($this->Prices, 2, '.', ' ');
    }
    
    public function getFormatedInitialPriceAttribute() : string
    {
        return number_format($this->initiale_prices, 2, '.', ' ');
    }
    
    public function getFormatedTypeAttribute() : string
    {
        if(app()->getLocale() == 'fr')
            return $this->Types == 'Products' ? 'Produit' : ($this->Types == 'Services' ? 'Service' : 'Autre');
        else
            return $this->Types == 'Products' ? 'Product' : ($this->Types == 'Services' ? 'Service' : 'Other');
    }
    
    public function getTSubscriptionFrequencyAttribute() : ?string
    {
        return match($this->subscription_frequency){
            'week' => __('Par semaine'),
            'month' => __('Par mois'),
            'year' => __('Par an'),
            'daily' => __('Par jour'),
            'minutely' => __('Par minute'),
            'hourly' => __('Par heure'),
            default => $this->subscription_frequency
        };
    }
    
    public function getTStatusAttribute() : ?string
    {
        return match($this->Status){
            'New' => __('Nouvel Arrivage'),
            'Coming Soon' => __('A venir bientôt'),
            'Best Sales' => __('Meilleure Vente')
        };
    }
    
    public function getTAvailableAttribute() : ?string
    {
        return match($this->Available){
            'Yes' => __('Oui'),
            'No' => __('Non'),
        };
    }
    
    public function getPictureAttribute() : string
    {
        return $this->Pictures ? 'data:image/jpeg;base64,'.base64_encode($this->Pictures) : '';
    }

    public function category() : BelongsTo
    {
        return $this->belongsTo(ItemCategory::class, 'Categories', 'Category_id');
    }

    public function measure() : BelongsTo
    {
        return $this->belongsTo(ItemMeasurement::class, 'unit_of_measurement', 'id_measurement');
    }

    public function images() : HasMany
    {
        return $this->hasMany(ItemImage::class, 'Id_item', 'Items_Numbers');
    }

    public function sizes() : HasMany
    {
        return $this->hasMany(ItemSize::class, 'item_id', 'Items_Numbers');
    }

    public function contracts() : HasMany
    {
        return $this->hasMany(ItemContract::class, 'item_id', 'Items_Numbers');
    }
    
    public function reservations() : HasMany
    {
        return $this->hasMany(Reservation::class, 'customers', 'Customers_Numbers');
    }

    public function equipments(): BelongsToMany
    {
        return $this->belongsToMany(ItemEquipment::class, 'items_equipments', 'item_id', 'equipment_id', 'Items_Numbers', 'equipment_id');
    }

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'ingredient_item', 'item_id', 'ingredient_id', 'Items_Numbers', 'id_ingredients')
                    ->withPivot(['needed_quantity']);
    }

    public function scontracts() : BelongsToMany
    {
        return $this->belongsToMany(CustomerContract::class, 'customers_contract_item_group_sale', 'item_number', 'contract_id', 'Items_Numbers', 'id_contract');
    }

    public function servicestep(): BelongsToMany
    {
        return $this->belongsToMany(ServiceStep::class, 'workspace_services_steps_position',  'service_id', 'service_step_id', 'Items_Numbers', 'step_id')
                    ->withPivot(['position_nb']);
    }

    public function newEloquentBuilder($query)
    {
        return new ItemQueryBuilder($query);
    }

    protected static function booted(): void
    {
        static::creating(function (Item $item) {
            $item->item_date = date('Y-m-d H:i:s');
        });
    }

}