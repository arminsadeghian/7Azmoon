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
        $response = $this->call('POST', 'api/v1/users', []);
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
        $response = $this->call('PUT', 'api/v1/users', []);
        $this->assertEquals(422, $response->status());
    }

    /** @test */
    public function itShouldUpdateUserPassword()
    {
        $response = $this->call('PUT', '/api/v1/users/change-password', [
            'id' => 3,
            'password' => '123456789',
            'password_repeat' => '123456789',
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
    public function itMustThrowExceptionIfWeDontSendParameterToUpdatePassword()
    {
        $response = $this->call('PUT', '/api/v1/users/change-password', []);
        $this->assertEquals(422, $response->status());
    }

    /** @test */
    public function itShouldDeleteUser()
    {
        $response = $this->call('DELETE', '/api/v1/users', [
            'id' => 380,
        ]);

        $this->assertEquals(200, $response->status());

        $this->seeJsonStructure([
            'success',
            'message',
            'data' => [],
        ]);
    }

    /** @test */
    public function itMustThrowExceptionIfWeDontSendParameterToDeleteUser()
    {
        $response = $this->call('DELETE', '/api/v1/users', []);
        $this->assertEquals(422, $response->status());
    }

    /** @test */
    public function itShouldGetUsers()
    {
        $page = 1;
        $pageSize = 3;

        $response = $this->call('GET', '/api/v1/users', [
            'page' => $page,
            'page_size' => $pageSize
        ]);

        $data = json_decode($response->getContent(), true);

        $this->assertEquals($pageSize, count($data['data']));
        $this->assertEquals(200, $response->status());
        $this->seeJsonStructure([
            'success',
            'message',
            'data',
        ]);
    }

}
