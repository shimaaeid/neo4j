<?php

namespace App\Repository;

use App\Models\Student;
use Laudis\Neo4j\Authentication\Authenticate;
use Laudis\Neo4j\ClientBuilder;
use Laudis\Neo4j\Contracts\TransactionInterface;

class StudentRepoClassNeo4j implements StudentRepoNeo4jInterface
{

    public function save($name, $subject, $date)
    {


        $client = ClientBuilder::create()
        ->withDriver('bolt', 'bolt+s://neo4j:students@localhost') // creates a bolt driver
        ->withDriver('https', 'https://test.com', Authenticate::basic('neo4j', 'students')) // creates an http driver
        ->withDriver('neo4j', 'neo4j://neo4j.test.com?students=my-database', Authenticate::oidc('token')) // creates an auto routed driver with an OpenID Connect token
        ->withDefaultDriver('bolt')
        ->build();

    //    dd($client);

    $result = $client->writeTransaction(static function (TransactionInterface $tsx) {
        $result = $tsx->run('MERGE (x {y: "z"}:X) return x');
        return $result->first()->get('x')['y'];
    });

    echo $result; // echos 'z'

        // $formData = Student::makeNode();
        // $formData->setProperty('name', $name)
        //     ->setProperty('subject', $subject)
        //     ->setProperty('date', $date)
        //     ->save();

    }

}
