<?php
/**
 * Created by PhpStorm.
 * User: Herval
 * Date: 15/04/2020
 * Time: 18:53
 */

namespace Tests\Traits;


use Exception;
use Illuminate\Foundation\Testing\TestResponse;

trait TestSaves
{
    protected function assertStore(array $sendData, array $testDatabaseData, array $testJsonData = null): TestResponse
    {
        /** @var TestResponse $response */
        $response = $this->json('POST', $this->routeStore(), $sendData);

        if ($response->status() !== 201) {
            throw new Exception("Response status must nbe 201, give {$response->status()}: \n {$response->content()}");
        }

        $this->assertInDatabase($response, $testDatabaseData);
        $this->assertJsonResponseContent($response, $testDatabaseData, $testJsonData);

        return $response;
    }

    private function assertInDatabase(TestResponse $response, array $testDatabaseData)
    {
        $model = $this->model();
        $table = (new $model)->getTable();
        $this->assertDatabaseHas($table, $testDatabaseData + ['id' => $response->json('id')]);
    }

    private function assertJsonResponseContent(TestResponse $response, array $testDatabaseData, array $testJsonData = null)
    {
        $testResponse = $testJsonData ?? $testDatabaseData;
        $response = assertJsonFragment($testResponse + ['id' => $response->json('id')]);
    }

    protected function assertUpdate(array $sendData, array $testDatabaseData, array $testJsonData = null): TestResponse
    {
        /** @var TestResponse $response */
        $response = $this->json('PUT', $this->routeUpdate(), $sendData);

        if ($response->status() !== 200) {
            throw new Exception("Response status must nbe 200, give {$response->status()}: \n {$response->content()}");
        }

        $this->assertInDatabase($response, $testDatabaseData);
        $this->assertJsonResponseContent($response, $testDatabaseData, $testJsonData);

        return $response;
    }
}
