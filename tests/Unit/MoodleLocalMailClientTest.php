<?php

namespace Tests\Unit;

use App\Services\MoodleLocalMailClient;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Tests\TestCase;

class MoodleLocalMailClientTest extends TestCase
{
    public function testItSendsTheMessageAsAFormAndReturnsTheMoodleMessageId(): void
    {
        config([
            'moodle_mail.enabled' => true,
            'moodle_mail.function' => 'local_zonaavz_send_mail',
        ]);

        Http::fake([
            'https://moodle.test/webservice/rest/server.php' => Http::response([
                'messageid' => 321,
                'duplicate' => false,
            ]),
        ]);

        $result = app(MoodleLocalMailClient::class)->send('https://moodle.test/', 'secret-token', [
            'idempotencykey' => str_repeat('a', 64),
            'courseshortname' => '0007/689',
            'senderusername' => 'teacher1',
            'recipientusername' => 'student1',
            'subject' => 'Bienvenida',
            'bodytext' => 'Contenido',
            'bodyhtml' => '<p>Contenido</p>',
        ]);

        $this->assertSame(321, $result['message_id']);
        $this->assertFalse($result['duplicate']);

        Http::assertSent(function (Request $request) {
            return $request->url() === 'https://moodle.test/webservice/rest/server.php'
                && $request->hasHeader('Accept', 'application/json')
                && $request['wstoken'] === 'secret-token'
                && $request['wsfunction'] === 'local_zonaavz_send_mail'
                && $request['moodlewsrestformat'] === 'json'
                && $request['senderusername'] === 'teacher1'
                && $request['recipientusername'] === 'student1';
        });
    }

    public function testItAcceptsAnIdempotentDuplicateResponse(): void
    {
        config(['moodle_mail.enabled' => true]);

        Http::fake([
            '*' => Http::response([
                'messageid' => 321,
                'duplicate' => true,
            ]),
        ]);

        $result = app(MoodleLocalMailClient::class)->send('https://moodle.test', 'token', []);

        $this->assertSame(321, $result['message_id']);
        $this->assertTrue($result['duplicate']);
    }

    public function testItRaisesTheErrorReturnedByMoodle(): void
    {
        config(['moodle_mail.enabled' => true]);

        Http::fake([
            '*' => Http::response([
                'exception' => 'moodle_exception',
                'errorcode' => 'invalidrecord',
                'message' => 'El alumno no está matriculado.',
            ]),
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('El alumno no está matriculado.');

        app(MoodleLocalMailClient::class)->send('https://moodle.test', 'token', []);
    }

    public function testItIncludesTheMoodleErrorWhenTheHttpStatusIsNotSuccessful(): void
    {
        config(['moodle_mail.enabled' => true]);

        Http::fake([
            '*' => Http::response([
                'exception' => 'invalid_parameter_exception',
                'errorcode' => 'invalidparameter',
                'message' => 'El destinatario no puede recibir mensajes en este curso.',
            ], 406),
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Moodle ha respondido con HTTP 406: El destinatario no puede recibir mensajes en este curso. [invalidparameter]'
        );

        app(MoodleLocalMailClient::class)->send('https://moodle.test', 'token', []);
    }

    public function testItIncludesAPlainTextHttpErrorBody(): void
    {
        config(['moodle_mail.enabled' => true]);
        Http::fake(['*' => Http::response('Request rejected by Moodle', 406)]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Moodle ha respondido con HTTP 406: Request rejected by Moodle');

        app(MoodleLocalMailClient::class)->send('https://moodle.test', 'token', []);
    }

    public function testItRejectsAResponseWithoutAMessageId(): void
    {
        config(['moodle_mail.enabled' => true]);
        Http::fake(['*' => Http::response(['duplicate' => false])]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Moodle no ha confirmado la creación del mensaje.');

        app(MoodleLocalMailClient::class)->send('https://moodle.test', 'token', []);
    }
}
