<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\User;

class UserTest extends TestCase
{
   use DatabaseMigrations;
   use DatabaseTransactions;

   public function testGetAllUsers()
   {
       $users = factory(User::class, 5)->create();
       foreach( $users as $user )
       {
           $data[] = [
               'email' =>(string) $user->email,
               'name' => (string) $user->name,
               'created_at' => (string) $user->created_at->toDateTimeString(),
               'updated_at' => (string) $user->updated_at->toDateTimeString()
           ];
       }
       $this->json('GET', '/user/all', [],  [
       ])
           ->seeJsonEquals([
               'status' => 1,
               'data' => $data
           ]);
   }

   public function testUserEmailExist()
   {
       $users = factory(User::class, 1)->create();
       foreach( $users as $user )
       {
           $data[] = [
               'email' =>(string) $user->email,
               'name' => (string) $user->name,
               'created_at' => (string) $user->created_at->toDateTimeString(),
               'updated_at' => (string) $user->updated_at->toDateTimeString()
           ];
       }
       $this->json('GET', '/user/emailExist', [
           'email' => $data[0]['email']
       ],[])
           ->seeJson([
               'status'=>1
           ]);
   }

   public function testUserEmailNotExist()
   {
       $users = factory(User::class, 1)->create();
       foreach( $users as $user )
       {
           $data[] = [
               'email' =>(string) $user->email,
               'name' => (string) $user->name,
               'created_at' => (string) $user->created_at->toDateTimeString(),
               'updated_at' => (string) $user->updated_at->toDateTimeString()
           ];
       }
       $this->json('GET', '/user/emailExist', [
           'email' => 'test@mail.com'
       ],[])
           ->seeJson([
               'status'=>0
           ]);
   }

   public function testUserInvalidEmail()
   {
       $users = factory(User::class, 1)->create();
       foreach( $users as $user )
       {
           $data[] = [
               'email' =>(string) $user->email,
               'name' => (string) $user->name,
               'created_at' => (string) $user->created_at->toDateTimeString(),
               'updated_at' => (string) $user->updated_at->toDateTimeString()
           ];
       }
       $this->json('GET', '/user/emailExist', [
           'email' => 'test'
       ],[])
           ->seeJson([
               'status'=>0
           ]);
   }

   public function testUserEmailRequired()
   {
       $users = factory(User::class, 1)->create();
       foreach( $users as $user )
       {
           $data[] = [
               'email' =>(string) $user->email,
               'name' => (string) $user->name,
               'created_at' => (string) $user->created_at->toDateTimeString(),
               'updated_at' => (string) $user->updated_at->toDateTimeString()
           ];
       }
       $this->json('GET', '/user/emailExist', [
           'email' => ''
       ],[])
           ->seeJson([
               'status'=>0
           ]);
   }
}
