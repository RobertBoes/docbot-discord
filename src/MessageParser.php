<?php
namespace Docbot;

class MessageParser {
    protected $docs = [
                "artisan",
                "authentication",
                "authorization",
                "billing",
                "blade",
                "broadcasting",
                "cache",
                "cashier-paddle",
                "collections",
                "configuration",
                "console-tests",
                "container",
                "contracts",
                "contributions",
                "controllers",
                "csrf",
                "database-testing",
                "database",
                "deployment",
                "documentation",
                "dusk",
                "eloquent-collections",
                "eloquent-mutators",
                "eloquent-relationships",
                "eloquent-resources",
                "eloquent-serialization",
                "eloquent",
                "encryption",
                "envoy",
                "errors",
                "events",
                "facades",
                "filesystem",
                "fortify",
                "frontend",
                "hashing",
                "helpers",
                "homestead",
                "horizon",
                "http-client",
                "http-tests",
                "installation",
                "license",
                "lifecycle",
                "localization",
                "logging",
                "mail",
                "middleware",
                "migrations",
                "mix",
                "mocking",
                "notifications",
                "octane",
                "packages",
                "pagination",
                "passport",
                "passwords",
                "pint",
                "providers",
                "queries",
                "queues",
                "rate-limiting",
                "readme",
                "redirects",
                "redis",
                "releases",
                "requests",
                "responses",
                "routing",
                "sail",
                "sanctum",
                "scheduling",
                "scout",
                "seeding",
                "session",
                "socialite",
                "starter-kits",
                "structure",
                "telescope",
                "testing",
                "upgrade",
                "urls",
                "valet",
                "validation",
                "verification",
                "views",
                "vite",
            ];

    public function __invoke($message)
    {
        // cheap hack to force `docs ` input
        $query = substr($message->content, 5);

        if(in_array($query, $this->docs)){

            return "<https://laravel.com/docs/$query>";
        }

        return false;
    }
}