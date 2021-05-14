<?php

namespace Apiato\Core\Traits;

use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\PersonalAccessClient;

trait TestCaseTrait
{
    public function migrateDatabase(): void
    {
        Artisan::call('migrate');
    }

    /**
     * Equivalent to passport:install but enough to run the tests.
     */
    public function setupPassportOAuth2(): void
    {
        $client = (new ClientRepository())->createPersonalAccessClient(
            null,
            'Testing Personal Access Client',
            'http://localhost'
        );

        $accessClient            = new PersonalAccessClient();
        $accessClient->client_id = $client->id;
        $accessClient->save();
    }
}
