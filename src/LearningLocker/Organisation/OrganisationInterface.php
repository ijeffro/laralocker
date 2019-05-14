<?php
namespace HT2\XAPI;

use TinCan\Agent, TinCan\Verb, TinCan\Activity, TinCan\Statement, TinCan\LanguageMap, TinCan\Context, TinCan\Result;
use Carbon\Carbon;

interface XAPIInterface {
  function getAuthCredentials();
  function get( Agent $agent, $verb, $object, Carbon $timestamp=null, Context $context, Result $result );
  function makeStatement( Agent $agent, Verb $verb, $object, Carbon $timestamp, Context $context, Result $result );
}