<?php
namespace LerningLocker\Statements;

use TinCan\Verb;
use TinCan\Agent;
use Carbon\Carbon;
use TinCan\Result;
use TinCan\Context;
use TinCan\Activity;
use TinCan\Statement;
use TinCan\LanguageMap;

interface StatementInterface {
  function getAuthCredentials();
  function send( Agent $agent, $verb, $object, Carbon $timestamp=null, Context $context, Result $result );
  function makeStatement( Agent $agent, Verb $verb, $object, Carbon $timestamp, Context $context, Result $result );

  function getVerb($id, $display);

  function getVersion();
  function setVersion($version);
}
