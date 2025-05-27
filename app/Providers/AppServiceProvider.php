<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Nuwave\Lighthouse\Schema\TypeRegistry;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Language\AST\Node;
use Carbon\Carbon;
use Nuwave\Lighthouse\Scalars\DateTime;
use Nuwave\Lighthouse\Scalars\Date;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->runningInConsole()) {
            try {
                DB::connection()->getPdo();

                if (rand(1, 10) <= 2) { // 20% intermittent
                    echo "Koneksi intermittent (tidak stabil)";
                } else {
                    echo "Koneksi sukses";
                }
            } catch (\Exception $e) {
                echo "Koneksi gagal: " . $e->getMessage();
            }
        }

        $this->app->get(TypeRegistry::class)->register(
            new class extends ScalarType {
                public function __construct()
                {
                    parent::__construct([
                        'name' => 'DateTime',
                        'serialize' => [$this, 'serialize'],
                        'parseValue' => [$this, 'parseValue'],
                        'parseLiteral' => [$this, 'parseLiteral'],
                    ]);
                }

                public function serialize($value)
                {
                    return $value instanceof \DateTimeInterface
                        ? $value->format(\DateTime::ATOM)
                        : $value;
                }

                public function parseValue($value)
                {
                    return new \DateTime($value);
                }

                public function parseLiteral(Node $valueNode, array $variables = null)
                {
                    if ($valueNode instanceof \GraphQL\Language\AST\StringValueNode) {
                        return new \DateTime($valueNode->value);
                    }
                    return null;
                }
            }
        );

    }
}
