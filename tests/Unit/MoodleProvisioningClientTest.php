<?php

namespace Tests\Unit;

use App\Models\WebPlatform;
use App\Services\MoodleProvisioningClient;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Tests\TestCase;

class MoodleProvisioningClientTest extends TestCase
{
    private function platform(): WebPlatform
    {
        return new WebPlatform(['url' => 'https://moodle.test/', 'token' => 'secret-token']);
    }

    public function testItListsCoursesThroughTheConnector(): void
    {
        Http::fake(['*' => Http::response(['courses' => [[
            'id' => 12, 'fullname' => 'Curso base', 'shortname' => '001/0001',
        ]]])]);

        $courses = app(MoodleProvisioningClient::class)->courses($this->platform());

        $this->assertSame(12, $courses[0]['id']);
        Http::assertSent(fn (Request $request) =>
            $request['wsfunction'] === 'local_zonaavz_list_courses' && $request['wstoken'] === 'secret-token'
        );
    }

    public function testItSendsProvisioningPayloadEncoded(): void
    {
        Http::fake(['*' => Http::response(['courseid' => 99, 'shortname' => '001/0002', 'created' => true])]);

        $result = app(MoodleProvisioningClient::class)->provision($this->platform(), [
            'zonaavz_course_id' => 4,
            'required_users' => [['username' => 'inspector.cfa', 'role' => 'inspectortotal']],
        ]);

        $this->assertSame(99, $result['courseid']);
        Http::assertSent(function (Request $request) {
            $payload = json_decode(base64_decode(substr($request['payload'], 7)), true);
            return $request['wsfunction'] === 'local_zonaavz_provision_course'
                && $payload['zonaavz_course_id'] === 4
                && $payload['required_users'][0]['username'] === 'inspector.cfa'
                && $payload['required_users'][0]['role'] === 'inspectortotal';
        });
    }

    public function testItValidatesRequiredUsersInMoodle(): void
    {
        Http::fake(fn (Request $request) => Http::response([
            'users' => [[
                'id' => $request['criteria'][0]['value'] === 'dinamizador.avz' ? 10 : 11,
                'username' => $request['criteria'][0]['value'],
            ]],
        ]));

        app(MoodleProvisioningClient::class)->assertUsersExist(
            $this->platform(),
            ['dinamizador.avz', 'inspector.cfa']
        );

        Http::assertSent(fn (Request $request) =>
            $request['wsfunction'] === 'core_user_get_users'
            && $request['criteria'][0]['key'] === 'username'
            && $request['criteria'][0]['value'] === 'dinamizador.avz'
        );
        Http::assertSentCount(2);
    }

    public function testItRejectsMissingRequiredUsers(): void
    {
        Http::fake(fn (Request $request) => Http::response([
            'users' => $request['criteria'][0]['value'] === 'dinamizador.avz'
                ? [['id' => 10, 'username' => 'dinamizador.avz']]
                : [],
        ]));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('inspector.cfa');

        app(MoodleProvisioningClient::class)->assertUsersExist(
            $this->platform(),
            ['dinamizador.avz', 'inspector.cfa']
        );
    }

    public function testItFallsBackToTheStandardCourseFunctionWithAnOldConnector(): void
    {
        Http::fakeSequence()
            ->push(['exception' => 'dml_missing_record_exception', 'message' => 'No se puede encontrar registro de datos en la tabla external_functions de la base de datos.'])
            ->push([[
                'id' => 20, 'fullname' => 'Curso antiguo', 'shortname' => '020/0001',
                'idnumber' => '', 'categoryid' => 3, 'startdate' => 1, 'enddate' => 2, 'visible' => 1,
            ]]);

        $courses = app(MoodleProvisioningClient::class)->courses($this->platform());

        $this->assertSame(20, $courses[0]['id']);
        Http::assertSentCount(2);
    }

    public function testItRaisesMoodleErrors(): void
    {
        Http::fake(['*' => Http::response(['exception' => 'moodle_exception', 'message' => 'Sin permisos'])]);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Sin permisos');
        app(MoodleProvisioningClient::class)->siteInfo($this->platform());
    }

    public function testItRejectsAutomaticCreationWhenTheTokenDoesNotExposeProvisioning(): void
    {
        Http::fake(['*' => Http::response(['functions' => [
            ['name' => 'core_course_get_courses', 'version' => '3.9'],
        ]])]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('local_zonaavz_provision_course');

        app(MoodleProvisioningClient::class)->assertProvisioningAvailable($this->platform());
    }
}
