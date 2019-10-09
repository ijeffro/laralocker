<?php
namespace LearningLocker\StatementForwarding;

use Curatr\Object\Completion\Types;
use Curatr\ObjectCompletionCriterion;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;

class Query {

  const COMMENT = "AUTOGENERATED BY - DO NOT MANUALLY AMEND";

  /**
   * Generate the Statement Forward Query
   * based on the Object Completion Criteria
   */
  public static function statementForward($org_id) {
    $criteria = ObjectCompletionCriterion::where('org_id', $org_id)->whereHas(\Object::class, function($q){
      $q->where("completion_type", Types::XAPI);
    })->with('object')->get();

    if (sizeof($criteria) === 0 ){
      // if there are no criteria
      return false;
    } else {
      //if there is criteria
      $queries = [];

      foreach ($criteria as $criterion) {
        $course = $criterion->object->course;
        if (empty($course) || ($course && !empty($course->deleted_at))) {
          continue;
        }
        $criteriaQuery = [];
        $criteriaQuery['$comment'] = 'id:'  . $criterion->id . ', object_id:' . $criterion->object_id;
        $activity_id = $criterion->use_curatr_activity ? $criterion->object->getXapiUrl(true, true) : $criterion->activity_id;
        $criteriaQuery['statement.object.id'] = $activity_id;
        if (!empty($criterion->verb)) $criteriaQuery['statement.verb.id'] = $criterion->verb;
        if (!empty($criterion->completion)) $criteriaQuery['statement.result.completion'] = true;
        if (!empty($criterion->success)) $criteriaQuery['statement.result.success'] = true;
        if (is_numeric($criterion->raw)) $criteriaQuery['statement.result.score.raw'] = ['$gte' => floatval($criterion->raw)];
        if (is_numeric($criterion->scaled)) $criteriaQuery['statement.result.score.scaled'] = ['$gte' => floatval($criterion->scaled)];

        if (sizeof($criteriaQuery) > 0) {
          $queries[] = $criteriaQuery;
        }
      }
      if (sizeof($queries) === 0 ){
        // if there are no quries
        return false;
      }

      $query = [
        '$comment' => self::COMMENT,
        '$or'=>$queries
      ];
    }

    return json_encode($query);
  }

  /**
   * Get the Learning Locker Statement Forward by ID.
   *
   * @param   $id
   * @return  $response
   */
  protected function get() {
    try {
      $url = $this->endpoint . $this->api . $this->v2 . $this->query . '/';
      $request = $this->getClient()->get($url, [
        'auth' => $this->getAuth(),
        'headers' => [
          'content-type' => 'application/json'
        ],
      ]);

      if($request->getStatusCode() === 404) {
        throw new ClientException('There was a issue connecting to Learning Locker.');
      }
      $response = $request->json();
    } catch (ClientException $e) {
      return $e;
    }

    return $response;
  }

}
