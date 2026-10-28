<?php
namespace Tests\Feature;
use Tests\TestCase;
class ClientAccessCorsTest extends TestCase
{
    public function test_zona_api_preflight(): void
    {
        config(['client_access.domain' => 'academypro.app']);
        $this->withHeaders(['Origin'=>'https://zona.academypro.app','Access-Control-Request-Method'=>'GET','Access-Control-Request-Headers'=>'authorization,content-type'])
            ->options('https://api.academypro.app/api/users/basic-user')
            ->assertStatus(204)->assertHeader('Access-Control-Allow-Origin','https://zona.academypro.app')->assertHeader('Access-Control-Allow-Credentials','true');
    }
    public function test_untrusted_origin_is_not_allowed(): void
    {
        $this->withHeaders(['Origin'=>'https://evil.example','Access-Control-Request-Method'=>'GET'])
            ->options('https://api.academypro.app/api/users/basic-user')->assertHeaderMissing('Access-Control-Allow-Origin');
    }
}
