<?php

namespace Tests\API\V1;

use App\Repositories\Contracts\UserRepositoryInterface;
use Tests\TestCase;

class UsersTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:refresh');
    }

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
        $user = $this->createUser()[0];
        $response = $this->call('PUT', 'api/v1/users', [
            'id' => $user->getId(),
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
        $user = $this->createUser()[0];
        $response = $this->call('PUT', '/api/v1/users/change-password', [
            'id' => $user->getId(),
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
        $user = $this->createUser()[0];
        $response = $this->call('DELETE', '/api/v1/users', [
            'id' => $user->getId(),
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
        $this->createUser(10);

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

    private function createUser(int $count = 1): array
    {
        $users = [];

        $userRepository = $this->app->make(UserRepositoryInterface::class);

        $userData = [
            'full_name' => '7azmoon',
            'email' => '7azmoon@gmail.com',
            'mobile' => '09111111111',
        ];

        foreach (range(0, $count) as $item) {
            $users[] = $userRepository->create($userData);
        }

        return $users;
    }

}
