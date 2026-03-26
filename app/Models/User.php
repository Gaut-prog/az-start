<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Action\Helpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    protected $primaryKey = 'id_credential';
    protected $table = 'customers_credential';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'userN',
        'passW',
        'customer_number',
        'status',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'passW',
        'remember_token',
    ];
    
    public function customer() : BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_number', 'Customers_Numbers');
    }

    public function getLinkedCompanyAttribute() : ?array
    {
        $company = Session::get('linked_company');
        if(!$company){
            $customer = $this->customer;
            if($customer->type == "webcompany")
                $company = $customer->attributesToArray();
            else{
                $company = (array) DB::table('customers')
                                ->join('company_employee_links', 'company_employee_links.company_number', '=', 'customers.Customers_Numbers')
                                ->where('company_employee_links.employee_number', $this->customer_number)
                                ->where('Customers_Numbers', $this->getCompanyAccountConnectedAttribute())
                                ->select('customers.*', 'company_employee_links.role')
                                ->first();
                if($company)
                    $company['type'] = $company['role'];
            }
            Session::put('linked_company', $company);
        }
        return $company;
    }
    
    public function getLinkedCompaniesAttribute() : ?array
    {
        $companies = Session::get('linked_companies', []);
        if(!$companies){
            $record = DB::table('company_employee_links')
                ->join('customers', 'customers.Customers_Numbers', '=', 'company_employee_links.company_number')
                ->where('company_employee_links.employee_number', $this->customer_number)
                ->where('customers.Status', 1)
                ->whereNotNull('customers.customer_db')
                ->select('customers.Customers_Numbers', 'customers.Names', 'customers.customer_db', 'customers.Picture', 'customers.Categories', 'company_employee_links.role')
                ->distinct()
                ->get();
                
                foreach($record as $company){
                    $company->customer_db = str_replace(" ", "", $company->customer_db);
                    $dbc = Helpers::customer_dbSet($company->customer_db);
                    //Vérifier s'il est encore actif dans chaque entreprise et récupérer cette dernière
                    if($dbc)
                    {
                        $isEmployee = DB::connection('mysql2')->table('employees')
                                ->where('az_id', $this->customer_number)
                                ->where('Status', 'Actif')
                                ->first('employees_Number');
                        if($isEmployee){
                            if($company->Picture)
                                $company->Picture = "data:image/png;base64,". base64_encode($company->Picture);

                            $companies[$company->Customers_Numbers] = $company;
                        }
                    }
                }
                Session::put('linked_companies', $companies);
        }
        return $companies;
    }
    
    public function getCompanyAccountConnectedAttribute() : ?int
    {
        return Cookie::get('company_account_connected');
    }
    
    public function setCompanyAccountConnectedAttribute(array|null $value) : void
    {
        if($value){
            $company = (array) DB::table('customers')->where('Customers_Numbers', $value['id'])->first();
            $company['type'] = $value['role'];
            Cookie::queue(cookie()->forever('company_account_connected', $value['id']));
            Session::put('linked_company', $company);
        }else{
            Cookie::queue(Cookie::forget('company_account_connected'));
            Session::forget(['linked_company']);
        }
    }
    
    public function getNameAttribute() : ?string
    {
        return $this->customer->name;
    }
    
    public function getCurrentCountryAttribute() : ?string
    {
        return Session::get('user_current_country');
    }
    
    //Modifier le pays courant de l'utilisateur
    public function setCurrentCountryAttribute(string $code) : void
    {
        Session::put('user_current_country', $code);
    }

    //Récupérer le rôle de l'utilisateur connecté dans l'entreprise actuelle
    public function getRoleAttribute() : string
    {
        $type = $this->linked_company['type'] ?? 'webbuyer';
        
        return match ($type) {
            'trainingadmin' => "Administrateur",
            'webcashier' => "Caissier",
            'webdeliver' => "Livreur",
            'webmanager' => "Gérant",
            'webtreasurer' => "Trésorier",
            'webhr' => "Directeur RH",
            'webaccountant' => "Comptable",
            'webcommercial' => "Commercial",
            'webmaster' => "Webmaster",
            'webwarehouseman' => "Magasinier",
            'trainingteacher' => "Enseignant",
            'webcompany' => "Entreprise",
            'webgroup' => "Groupe",
            default => "Personnel",
        };
    }
    
    //Token pour autorier l'utilisateur à acceder aux plateformes AZ sans se connecter
    public function getAccessTokenAttribute() : string
    {
        $access_token = Session::get('access_token');
        if(!$access_token){
            $company_id = $this->linked_company['Customers_Numbers'] ?? 'webbuyer';
            $access_token = sha1('token').'//'. $this->id_credential.'//'.md5($this->userN).sha1('minou') .'//'. $company_id .'//'.uniqid();
            Session::put('access_token', $access_token);
        }
        
        return $access_token;
    }

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            $user->status = 1;
        });
    }

    private function hasRememberMeCookie() : bool
    {
        $hasRememberMe = false;
        foreach(request()->cookies as $key => $value) {
            if(str_starts_with($key, 'remember_web_')) {
                $hasRememberMe = true;
                break;
            }
        }
        return $hasRememberMe;
    }

}