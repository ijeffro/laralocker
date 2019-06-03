<?php

namespace Ijeffro\Laralocker;

use Config;
use TinCan\Verb;
use TinCan\Agent;
use Carbon\Carbon;
use Guzzle\Exception\RequestException;
use Ijeffro\Laralocker\xAPI\xAPIHandler;
use Ijeffro\Laralocker\Constants\xAPIConstants;
use Ijeffro\Laralocker\Statements\StatementGenerator;

class xAPI extends xAPIHandler
{
    public $actor;
    public $what;
    public $send;
    public $did;


    /**
     * Learning Locker: xAPI Statement Actor
     *
     * @param $actor['name']
     * @param $actor['email']
     * @param $ref = Account Actor
     * @param $homepage = Account Homepage
     *
     */
    public function actor($actor = [], $ref = null, $homepage = null)
    {
        if (!$actor) return error('Please supply an actor');

        $this->agent = new Agent;
        $actor_type = Config::get('laralocker.learning_locker.xapi_statements.actor_type');

        if ($actor_type === xAPIConstants::MBOX) {

            if ($actor['name'] && $actor['email']) {
                $authority = StatementGenerator::createAuthority($actor['name'], $actor['email']);
                $actor = StatementGenerator::createMboxActor($actor['name'], $actor['email']);
                // dd($authority);
            } else {
                return error('Please supply the actor\'s name and email in the form of an array.');
            }


            // $this->agent->setAuthority($authority);
            $this->agent->setName($actor['name']);
            $this->agent->setMbox($actor['mbox']);
        }

        // if ($actor_type === xAPIConstants::ACCOUNT && $actor) {
        //     $homepage = Config::get('laralocker.xapi_statements.actor_hompage');

        //     if($homepage && $ref) {
        //         $$actor = Statemen

        // dd($this->actor);tGenerator::createAccountActor($actor, $homepage, $ref);
        //     }
        // }

        // $this->actor = [];
        $this->actor = $this->agent;

        return $this;
    }

    public function did($did)
    {
        $verb = new Verb;
        $did = StatementGenerator::getVerb($did);

        $verb->setId($did);
        $this->verb = $verb;

        return $this;
    }

    public function what($what)
    {
        $obejct = StatementGenerator::createObject($what);
        $this->object = $obejct;
        return $this;
    }

    public function make()
    {
        $this->timestamp = Carbon::now();

        $this->statement = $this->makeStatement(
            $this->actor,
            $this->verb,
            $this->object,
            $this->timestamp
            // $context,
            // $result
        );

        $this->make = [];
        $this->make = $this->statement;
        return $this->make;
    }


    /**
     * Store the Learning Locker Statement Forward.
     *
     * @param   $data
     * @return  $response
     *
     */
    public function store()
    {
       $this->send = $this->send(
            $this->actor,
            $this->verb,
            $this->object,
            $this->timestamp
            // $context=null,
            // $result=null
        );

        // $this->send = [];
        $this->send =$this->send;
        return $this->send;

    }


    public function statement()
    {
        // return {
        //     "statement": {
        //         "authority": {
        //             "objectType": "Agent",
        //             "name": "New Client",
        //             "mbox": "mailto:hello@learninglocker.net"
        //         },
        //         "stored": "2019-05-23T10:34:59.140Z",
        //         "context": {
        //             "registration": "bd72a9fd-58d6-4c3c-b97b-04696b481a17",
        //             "contextActivities": {
        //                 "grouping": [
        //                     {
        //                         "objectType": "Activity",
        //                         "id": "https://phil.curatr3.com/courses/linear",
        //                         "definition": {
        //                             "type": "http://adlnet.gov/expapi/activities/course"
        //                         }
        //                     },
        //                     {
        //                         "objectType": "Activity",
        //                         "id": "https://phil.curatr3.com",
        //                         "definition": {
        //                             "type": "http://curatr3.com/define/type/organisation",
        //                             "name": {
        //                             "en-GB": "HT2"
        //                             }
        //                         }
        //                     }
        //                 ]
        //             }
        //         },
        //         "platform": "Curatr"
        //         },
        //         "actor": {
        //             "objectType": "Agent",
        //             "name": "James Mullaney",
        //             "mbox": "mailto:phil.graham@ht2labs.com"
        //         },
        //         "timestamp": "2019-05-23T10:34:57+00:00",
        //         "version": "1.0.0",
        //         "id": "357bb526-8d7a-48a9-8ab2-c9cd28a772e3",
        //         "verb": {
        //             "id": "http://activitystrea.ms/schema/1.0/access",
        //             "display": {
        //             "en-GB": "accessed"
        //             }
        //         },
        //         "object": {
        //             "objectType": "Activity",
        //             "id": "http://phil.curatr3.com/courses/linear/learn",
        //             "definition": {
        //             "type": "http://activitystrea.ms/schema/1.0/page",
        //             "name": {
        //                 "en-GB": "Linear - Learn"
        //             }
        //         }
        //     }
        // }

        // // End of statement
    }


}
