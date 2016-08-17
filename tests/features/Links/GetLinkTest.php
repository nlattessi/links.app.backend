<?php

use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseMigrations;

class GetLinkTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->app->instance('middleware.disable', true);
    }

    public function test_get_link()
    {
        $link = $this->linkFactory();

        $this
            ->get("/links/{$link->id}", ['Accept' => 'application/json'])
            ->seeStatusCode(Response::HTTP_OK);

        $body = json_decode($this->response->getContent(), true);
        $this->assertArrayHasKey('data', $body);

        $data = $body['data'];
        $this->assertEquals($link->id, $data['id']);
        $this->assertEquals($link->uuid, $data['uuid']);
        $this->assertEquals($link->title, $data['title']);
        $this->assertEquals($link->url, $data['url']);
        $this->assertEquals($link->description, $data['description']);
        $this->assertEquals($link->category->name, $data['category']);
        $this->assertEquals($link->created_at->toDateTimeString(), $data['created_at']);
        $this->assertEquals($link->updated_at->toDateTimeString(), $data['updated_at']);
    }

    public function test_should_fail_if_link_id_not_exist()
    {
        $this
            ->get('links/999', ['Accept' => 'application/json'])
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
        $this->get('/links/invalid-route', ['Accept' => 'application/json']);

        $this
            ->assertNotRegExp(
                '/Link not found/',
                $this->response->getContent(),
                'LinksController@show route matching when it should not.'
            );
    }
}