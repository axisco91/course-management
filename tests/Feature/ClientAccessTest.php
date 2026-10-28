<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class ClientAccessTest extends TestCase
{
    protected function setUp(): void
    {
        putenv('CLIENT_ACCESS_DOMAIN=example.test');
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:', 'client_access.domain' => 'example.test', 'client_access.excluded_ids' => [1]]);
        DB::purge('sqlite');
        Schema::create('main_companies', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('url'); $table->boolean('active');
        });
        DB::table('main_companies')->insert([
            ['id'=>1,'name'=>'Centro global','url'=>'https://global.example.test','active'=>1],
            ['id'=>2,'name'=>'Centro Alfa','url'=>'alfa.example.test','active'=>1],
            ['id'=>3,'name'=>'Centro Inactivo','url'=>'https://beta.example.test','active'=>0],
            ['id'=>4,'name'=>'Centro externo','url'=>'https://evil.test','active'=>1],
            ['id'=>5,'name'=>'Centro reservado','url'=>'https://accesoclientes.example.test','active'=>1],
        ]);

    }
    public function test_only_public_active_tenants_and_safe_login_are_exposed(): void
    {
        $this->getJson('https://accesoclientes.example.test/public/companies/search?q=Centro')->assertOk()->assertExactJson(['data'=>[['id'=>2,'name'=>'Centro Alfa','subdomain'=>'alfa','login_url'=>'https://alfa.example.test/login']]]);
        foreach ([1,3,4,5,999] as $id) $this->getJson('https://accesoclientes.example.test/public/companies/'.$id)->assertNotFound();
        $this->getJson('https://accesoclientes.example.test/public/companies/2')->assertOk();
        $this->getJson('https://api.example.test/public/companies/2')->assertOk();
        DB::table('main_companies')->where('id',2)->update(['active'=>0]);
        $this->getJson('https://accesoclientes.example.test/public/companies/2')->assertNotFound();
    }
    public function test_validation_literal_search_and_host_isolation(): void
    {
        $this->getJson('https://accesoclientes.example.test/public/companies/search?q=ab')->assertStatus(422);
        $this->getJson('https://accesoclientes.example.test/public/companies/search?q=%25%25%25')->assertExactJson(['data'=>[]]);
        $this->getJson('https://accesoclientes.example.test/api/main-companies')->assertNotFound();
        $this->getJson('https://example.test/public/companies/2')->assertNotFound();
        $this->getJson('https://other.example.test/public/companies/2')->assertNotFound();
    }
    public function test_rate_limit(): void
    {
        for ($i=0;$i<30;$i++) $this->getJson('https://accesoclientes.example.test/public/companies/2')->assertOk();
        $this->getJson('https://accesoclientes.example.test/public/companies/2')->assertStatus(429);
    }
    public function test_reserved_host_cannot_be_assigned(): void
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        app(\App\Services\MainCompanyService::class)->update(new \App\Models\MainCompany(), ['url'=>'https://accesoclientes.example.test']);
    }
}
