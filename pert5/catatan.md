-copy dari pertemuan berapapun kalau ini dari pertemuan 4 copy db, nginx, php, .env.,catatan.md,docker-compose.yml kecuali src 
- build, docker exec, composer create-project --prefer-dist raugadh/fila-starter .
, 


--------------------------------------------------------------
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('api_token')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
--------------------------------------------------



<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
------------------------------------------------------
ke models client

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // ← Ini bener
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Client extends Model
{
    use HasFactory; // ← Ini juga harus HasFactory, bukan HacFactory

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($client) {
            if (empty($client->api_token)) {
                $client->api_token = Str::random(10);
            }
        });
    }

    protected $table = 'clients';
    protected $fillable = [
        'name',
        'api_token',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}

pruct.php

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected  $fillable = [
        'name',
        'price',
        'client_id',
    ];

    public function client(){
        return $this->belongTo(Client::class);
    }
}

HTTP/CONTRELLER/API, di PRODUCT.API.CONTROLLER NYA

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function index(Request $request){
        $client = $request->get('authenticated_client');
        return response()->json($client->product()->get());
    }
}
-----------------------------------------------------------------
middle ware

<?php

namespace App\Http\Middleware;

use App\Models\Client;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        $client = Client::where('api_token', $token)->first();
        if(!$client){
             return response()->json([
                'massage' => 'Unathorized'
             ], 401);
             
        }
        $request->merge(['authenticated_client' => $client]);
        return $next($request);
    }
}

