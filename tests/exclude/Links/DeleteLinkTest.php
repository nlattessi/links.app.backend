<?php

use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseMigrations;

class DeleteLinkTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->app->instance('middleware.disable', true);
    }

    public function test_delete_link()
    {
        $link = $this->linkFactory();

        $this
            ->seeInDatabase('links', [
                'id' => $link->id,
                'uuid' => $link->uuid,
                'title' => $link->title,
                'url' => $link->url,
                'category_id' => $link->category->id,
                'created_at' => $link->created_at,
                'updated_at' => $link->updated_at,
            ]);

        $this
            ->delete("/links/{$link->uuid}", [], ['Accept' => 'application/json'])
            ->seeStatusCode(Response::HTTP_NO_CONTENT)
            ->isEmpty();

        $this->notSeeInDatabase('links', ['id' => $link->id]);
    }

    public function test_should_fail_if_uuid_not_exist()
    {
        $this
            ->delete('links/25769c6c-d34d-4bfe-ba98-e0ee856f3e7a', [], ['Accept' => 'application/json'])
            ->seeStatusCode(Response::HTTP_NOT_FOUND)
            ->seeJson([
                'error' => [
                    'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                    'status' => Response::HTTP_NOT_FOUND,
                ],
            ]);
    }

    public function test_should_not_match_an_invalid_route()
    {
        $this->delete('/links/invalid-route', [], ['Accept' => 'application/json']);

        $this
            ->assertNotRegExp(
                '/Link not found/',
                $this->response->getContent(),
                'LinksController@update route matching when it should not.'
            );
    }
}
