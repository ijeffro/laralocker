<?php
namespace LerningLocker\Statements;

use TinCan\Agent, TinCan\Verb, TinCan\Activity, TinCan\Statement, TinCan\LanguageMap, TinCan\Context, TinCan\Result;
use Carbon\Carbon;

interface StatementInterface {
  function getAuthCredentials();
  function send( Agent $agent, $verb, $object, Carbon $timestamp=null, Context $context, Result $result );
  function makeStatement( Agent $agent, Verb $verb, $object, Carbon $timestamp, Context $context, Result $result );

  function getVerb($id, $display);

  function getVersion();
  function setVersion($version);
}