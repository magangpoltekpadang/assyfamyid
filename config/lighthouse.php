<?php declare(strict_types=1);

return [

    'route' => [
        /*
         * The URI the endpoint responds to, e.g. mydomain.com/graphql.
         */
        'uri' => '/graphql',

        /*
         * Lighthouse creates a named route for convenient URL generation and redirects.
         */
        'name' => 'graphql',

        /*
         * Beware that middleware defined here runs before the GraphQL execution phase,
         * make sure to return spec-compliant responses in case an error is thrown.
         */
        'middleware' => [
            // Ensures the request is not vulnerable to cross-site request forgery.
            // Nuwave\Lighthouse\Http\Middleware\EnsureXHR::class,

            // Always set the `Accept: application/json` header.
            Nuwave\Lighthouse\Http\Middleware\AcceptJson::class,

            // Logs in a user if they are authenticated. In contrast to Laravel's 'auth'
            // middleware, this delegates auth and permission checks to the field level.
            Nuwave\Lighthouse\Http\Middleware\AttemptAuthentication::class,

            // Logs every incoming GraphQL query.
            // Nuwave\Lighthouse\Http\Middleware\LogGraphQLQueries::class,
        ],

        /*
         * The `prefix`, `domain` and `where` configuration options are optional.
         */
        // 'prefix' => '',
        // 'domain' => '',
        // 'where' => [],
    ],

    'guards' => null,

    /*
    |--------------------------------------------------------------------------
    | Schema Path
    |--------------------------------------------------------------------------
    |
    | Path to your .graphql schema file.
    | Additional schema files may be imported from within that file.
    |
    */

    'schema_path' => base_path('graphql/schema.graphql'),

    'schema_cache' => [
        /*
         * Setting to true enables schema caching.
         */
        'enable' => env('LIGHTHOUSE_SCHEMA_CACHE_ENABLE', env('APP_ENV') !== 'local'),

        /*
         * File path to store the lighthouse schema.
         */
        'path' => env('LIGHTHOUSE_SCHEMA_CACHE_PATH', base_path('bootstrap/cache/lighthouse-schema.php')),
    ],

    'cache_directive_tags' => false,

    'query_cache' => [
        /*
         * Setting to true enables query caching.
         */
        'enable' => env('LIGHTHOUSE_QUERY_CACHE_ENABLE', true),

        /*
         * Allows using a specific cache store, uses the app's default if set to null.
         */
        'store' => env('LIGHTHOUSE_QUERY_CACHE_STORE', null),

        /*
         * Duration in seconds the query should remain cached, null means forever.
         */
        'ttl' => env('LIGHTHOUSE_QUERY_CACHE_TTL', 24 * 60 * 60),
    ],

    'validation_cache' => [
        /*
         * Setting to true enables validation caching.
         */
        'enable' => env('LIGHTHOUSE_VALIDATION_CACHE_ENABLE', false),

        /*
         * Allows using a specific cache store, uses the app's default if set to null.
         */
        'store' => env('LIGHTHOUSE_VALIDATION_CACHE_STORE', null),

        /*
         * Duration in seconds the validation result should remain cached, null means forever.
         */
        'ttl' => env('LIGHTHOUSE_VALIDATION_CACHE_TTL', 24 * 60 * 60),
    ],

    'parse_source_location' => true,


    'namespaces' => [
        'models' => ['App', 'App\\Models'],
        'queries' => 'App\\GraphQL\\Queries',
        'mutations' => 'App\\GraphQL\\Mutations',
        'subscriptions' => 'App\\GraphQL\\Subscriptions',
        'types' => 'App\\GraphQL\\Types',
        'interfaces' => 'App\\GraphQL\\Interfaces',
        'unions' => 'App\\GraphQL\\Unions',
        //'scalars' => 'App\\GraphQL\\Scalars\\DateTime',
        'directives' => 'App\\GraphQL\\Directives',
        'validators' => 'App\\GraphQL\\Validators',
        'scalars' => ['Nuwave\\Lighthouse\\Scalars'],
        // 'scalars' => [
        //     'DateTime' => App\GraphQL\Scalars\DateTime::class,
        // ],
        

    ],

    'security' => [
        'max_query_complexity' => GraphQL\Validator\Rules\QueryComplexity::DISABLED,
        'max_query_depth' => GraphQL\Validator\Rules\QueryDepth::DISABLED,
        'disable_introspection' => (bool) env('LIGHTHOUSE_SECURITY_DISABLE_INTROSPECTION', false)
            ? GraphQL\Validator\Rules\DisableIntrospection::ENABLED
            : GraphQL\Validator\Rules\DisableIntrospection::DISABLED,
    ],


    'pagination' => [

        'default_count' => null,

        'max_count' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Debug
    |--------------------------------------------------------------------------
    |
    | Control the debug level as described in https://webonyx.github.io/graphql-php/error-handling/
    | Debugging is only applied if the global Laravel debug config is set to true.
    |
    | When you set this value through an environment variable, use the following reference table:
    |  0 => INCLUDE_NONE
    |  1 => INCLUDE_DEBUG_MESSAGE
    |  2 => INCLUDE_TRACE
    |  3 => INCLUDE_TRACE | INCLUDE_DEBUG_MESSAGE
    |  4 => RETHROW_INTERNAL_EXCEPTIONS
    |  5 => RETHROW_INTERNAL_EXCEPTIONS | INCLUDE_DEBUG_MESSAGE
    |  6 => RETHROW_INTERNAL_EXCEPTIONS | INCLUDE_TRACE
    |  7 => RETHROW_INTERNAL_EXCEPTIONS | INCLUDE_TRACE | INCLUDE_DEBUG_MESSAGE
    |  8 => RETHROW_UNSAFE_EXCEPTIONS
    |  9 => RETHROW_UNSAFE_EXCEPTIONS | INCLUDE_DEBUG_MESSAGE
    | 10 => RETHROW_UNSAFE_EXCEPTIONS | INCLUDE_TRACE
    | 11 => RETHROW_UNSAFE_EXCEPTIONS | INCLUDE_TRACE | INCLUDE_DEBUG_MESSAGE
    | 12 => RETHROW_UNSAFE_EXCEPTIONS | RETHROW_INTERNAL_EXCEPTIONS
    | 13 => RETHROW_UNSAFE_EXCEPTIONS | RETHROW_INTERNAL_EXCEPTIONS | INCLUDE_DEBUG_MESSAGE
    | 14 => RETHROW_UNSAFE_EXCEPTIONS | RETHROW_INTERNAL_EXCEPTIONS | INCLUDE_TRACE
    | 15 => RETHROW_UNSAFE_EXCEPTIONS | RETHROW_INTERNAL_EXCEPTIONS | INCLUDE_TRACE | INCLUDE_DEBUG_MESSAGE
    |
    */

    'debug' => env('LIGHTHOUSE_DEBUG', GraphQL\Error\DebugFlag::INCLUDE_DEBUG_MESSAGE | GraphQL\Error\DebugFlag::INCLUDE_TRACE),

    'error_handlers' => [
        Nuwave\Lighthouse\Execution\AuthenticationErrorHandler::class,
        Nuwave\Lighthouse\Execution\AuthorizationErrorHandler::class,
        Nuwave\Lighthouse\Execution\ValidationErrorHandler::class,
        Nuwave\Lighthouse\Execution\ReportingErrorHandler::class,
    ],

    'field_middleware' => [
        Nuwave\Lighthouse\Schema\Directives\TrimDirective::class,
        Nuwave\Lighthouse\Schema\Directives\ConvertEmptyStringsToNullDirective::class,
        Nuwave\Lighthouse\Schema\Directives\SanitizeDirective::class,
        Nuwave\Lighthouse\Validation\ValidateDirective::class,
        Nuwave\Lighthouse\Schema\Directives\TransformArgsDirective::class,
        Nuwave\Lighthouse\Schema\Directives\SpreadDirective::class,
        Nuwave\Lighthouse\Schema\Directives\RenameArgsDirective::class,
        Nuwave\Lighthouse\Schema\Directives\DropArgsDirective::class,
    ],

    'global_id_field' => 'id',

    'persisted_queries' => true,

    'transactional_mutations' => true,

    'force_fill' => true,

    'batchload_relations' => true,

    'shortcut_foreign_key_selection' => false,

    'subscriptions' => [

        'queue_broadcasts' => env('LIGHTHOUSE_QUEUE_BROADCASTS', true),

        'broadcasts_queue_name' => env('LIGHTHOUSE_BROADCASTS_QUEUE_NAME', null),

        'storage' => env('LIGHTHOUSE_SUBSCRIPTION_STORAGE', 'redis'),

        'storage_ttl' => env('LIGHTHOUSE_SUBSCRIPTION_STORAGE_TTL', null),

        'encrypted_channels' => env('LIGHTHOUSE_SUBSCRIPTION_ENCRYPTED', false),

        'broadcaster' => env('LIGHTHOUSE_BROADCASTER', 'pusher'),

        'broadcasters' => [
            'log' => [
                'driver' => 'log',
            ],
            'echo' => [
                'driver' => 'echo',
                'connection' => env('LIGHTHOUSE_SUBSCRIPTION_REDIS_CONNECTION', 'default'),
                'routes' => Nuwave\Lighthouse\Subscriptions\SubscriptionRouter::class . '@echoRoutes',
            ],
            'pusher' => [
                'driver' => 'pusher',
                'connection' => 'pusher',
                'routes' => Nuwave\Lighthouse\Subscriptions\SubscriptionRouter::class . '@pusher',
            ],
            'reverb' => [
                'driver' => 'pusher',
                'connection' => 'reverb',
                'routes' => Nuwave\Lighthouse\Subscriptions\SubscriptionRouter::class . '@reverb',
            ],
        ],

        'exclude_empty' => env('LIGHTHOUSE_SUBSCRIPTION_EXCLUDE_EMPTY', true),
    ],

    'defer' => [
        /*
         * Maximum number of nested fields that can be deferred in one query.
         * Once reached, remaining fields will be resolved synchronously.
         * 0 means unlimited.
         */
        'max_nested_fields' => 0,

        /*
         * Maximum execution time for deferred queries in milliseconds.
         * Once reached, remaining fields will be resolved synchronously.
         * 0 means unlimited.
         */
        'max_execution_ms' => 0,
    ],

    'federation' => [
        /*
         * Location of resolver classes when resolving the `_entities` field.
         */
        'entities_resolver_namespace' => 'App\\GraphQL\\Entities',
    ],

    'tracing' => [
        /*
         * Driver used for tracing.
         *
         * Accepts the fully qualified class name of a class that implements Nuwave\Lighthouse\Tracing\Tracing.
         * Lighthouse provides:
         * - Nuwave\Lighthouse\Tracing\ApolloTracing\ApolloTracing::class
         * - Nuwave\Lighthouse\Tracing\FederatedTracing\FederatedTracing::class
         *
         * In Lighthouse v7 the default will be changed to 'Nuwave\Lighthouse\Tracing\FederatedTracing\FederatedTracing::class'.
         */
        'driver' => Nuwave\Lighthouse\Tracing\ApolloTracing\ApolloTracing::class,
    ],

    'scalars' => [
    'DateTime' => \Nuwave\Lighthouse\Schema\Types\Scalars\DateTime::class,
    'Date' => \Nuwave\Lighthouse\Schema\Types\Scalars\Date::class,
    //'Time' => \Nuwave\Lighthouse\Schema\Types\Scalars\Time::class,
],


];
