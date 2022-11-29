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

}
