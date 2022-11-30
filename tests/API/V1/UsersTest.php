<?php

namespace Tests\API\V1;

use Tests\TestCase;

class UsersTest extends TestCase
{
    /** @test */
    public function shouldCreateNewUser()
    {
        $newUser = [
            'full_name' => 'Armin Sadeghian',
            'email' => 'armin@gmail.com',
            'mobile' => '09038884841',
            'password' => '123456',
        ];

        $response = $this->call('POST', 'api/v1/users', $newUser);

        $this->assertEquals(201, $response->status());

        $this->seeJsonStructure([
            'success',
            'message',
            'data' => [
                'full_name',
                'email',
                'mobile',
                'password',
            ],
        ]);
    }

    /** @test */
    public function itMustThrowExceptionIfWeDontSendParameter()
    {
        $newUser = [];
        $response = $this->call('POST', 'api/v1/users', $newUser);
        $this->assertEquals(422, $response->status());
    }

    /** @test */
    public function itShouldUpdateTheInformationOfUser()
    {
        $response = $this->call('PUT', 'api/v1/users', [
            'id' => 3,
            'full_name' => 'Armin Sadeghian-Updated',
            'email' => 'arminUpdated@gmail.com',
            'mobile' => '09038884841Updated',
        ]);

        $this->assertEquals(200, $response->status());

        $this->seeJsonStructure([
            'success',
            'message',
            'data' => [
                'full_name',
                'email',
                'mobile',
            ],
        ]);
    }

    /** @test */
    public function itMustThrowExceptionIfWeDontSendParameterToUpdateInfo()
    {
        $newUser = [];
        $response = $this->call('PUT', 'api/v1/users', $newUser);
        $this->assertEquals(422, $response->status());
    }

}
