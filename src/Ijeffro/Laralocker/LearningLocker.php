<?php

namespace Ijeffro\Laralocker;

use Ijeffro\Laralocker\LearningLocker\Roles\RoleHandler;
use Ijeffro\Laralocker\LearningLocker\Users\UserHandler;
use Ijeffro\Laralocker\LearningLocker\Stores\StoreHandler;
use Ijeffro\Laralocker\LearningLocker\Queries\QueryHandler;
use Ijeffro\Laralocker\LearningLocker\Clients\ClientHandler;
use Ijeffro\Laralocker\LearningLocker\Exports\ExportHandler;
use Ijeffro\Laralocker\LearningLocker\Personas\PersonaHandler;
use Ijeffro\Laralocker\LearningLocker\Journeys\JourneyHandler;
use Ijeffro\Laralocker\LearningLocker\Journeys\ProgressHandler;
use Ijeffro\Laralocker\LearningLocker\Personas\AttributeHandler;
use Ijeffro\Laralocker\LearningLocker\Downloads\DownloadHandler;
use Ijeffro\Laralocker\LearningLocker\Personas\IdentifierHandler;
use Ijeffro\Laralocker\LearningLocker\Statements\StatementHandler;
use Ijeffro\Laralocker\LearningLocker\Dashboards\DashboardHandler;
use Ijeffro\Laralocker\LearningLocker\Statements\ForwardingHandler;
use Ijeffro\Laralocker\LearningLocker\Organisations\OrganisationHandler;
use Ijeffro\Laralocker\LearningLocker\Visualisations\VisualisationHandler;

class LearningLocker {

  /**
   * Learning Locker Conection Details
   *
   * @param string|null $id
   * @param string|null $key
   * @param string|null $secret
   * @param string|null $url
   *
   * @return void
   */
  public function __construct(string $key = null, string $secret = null, string $url = null)
  {
    $this->key = $key ? (string) $key : null;
    $this->secret = $secret ? (string) $secret : null;
    $this->url = $url ? (string) $url : null;
  }

  /**
   * Learning Locker API: Connection
   *
   * @param string $key
   * @param string $secret
   * @param string $url
   *
   * @return LearningLocker
   */
  public function connect(string $key, string $secret, string $url)
  {
    $connection = new LearningLocker($key, $secret, $url);
    if ($connection) return $connection;

    return self::connect($key, $secret, $url);
  }

  /**
   * Learning Locker Clients
   *
   * @param string|null $id
   * @return ClientHandler
   */
  public function clients(string $id = null)
  {
    $clients = new ClientHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($clients) return $clients;

    return self::clients($id ? $id : null);
  }

  /**
   * Learning Locker Client
   *
   * @param string|null $id
   * @return ClientHandler
   */
  public function client(string $id = null)
  {
    $client = new ClientHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($client) return $client;

    return self::client($id ? $id : null);
  }

  /**
   * Learning Locker Users
   *
   * @param string|null $id
   * @return UserHandler
   */
  public function users(string $id = null)
  {
    $users = new UserHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($users) return $users;

    return self::users($id ? $id : null);
  }

  /**
   * Learning Locker User
   *
   * @param string|null $id
   * @return UserHandler
   */
  public function user(string $id = null)
  {
    $user = new UserHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($user) return $user;

    return self::user($id ? $id : null);
  }

  /**
   * Learning Locker Roles
   *
   * @param string|null $id
   * @return RoleHandler
   */
  public function roles(string $id = null)
  {
    $roles = new RoleHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($roles) return $roles;

    return self::roles($id ? $id : null);
  }

  /**
   * Learning Locker Role
   *
   * @param string|null $id
   * @return RoleHandler
   */
  public function role(string $id = null)
  {
    $role = new RoleHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($role) return $role;

    return self::role($id ? $id : null);
  }

  /**
   * Learning Locker Stores - (LRS)
   *
   * @param string|null $id
   * @return StoreHandler
   */
  public function stores(string $id = null)
  {
    $stores = new StoreHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($stores) return $stores;

    return self::stores($id ? $id : null);
  }

  /**
   * Learning Locker Store - (LRS)
   *
   * @param string|null $id
   * @return StoreHandler
   */
  public function store(string $id = null)
  {
    $store = new StoreHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($store) return $store;

    return self::store($id ? $id : null);
  }

  /**
   * Learning Locker Queries
   *
   * @param string|null $id
   * @return QueryHandler
   */
  public function queries(string $id = null)
  {
    $queries = new QueryHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($queries) return $queries;

    return self::queries($id ? $id : null);
  }

  /**
   * Learning Locker Query
   *
   * @param string|null $id
   * @return QueryHandler
   */
  public function query(string $id = null)
  {
    $query = new QueryHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($query) return $query;

    return self::query($id ? $id : null);
  }

  /**
   * Learning Locker Exports
   *
   * @param string|null $id
   * @return ExportHandler
   */
  public function exports(string $id = null)
  {
    $exports = new ExportHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($exports) return $exports;

    return self::exports($id ? $id : null);
  }

  /**
   * Learning Locker Export
   *
   * @param string|null $id
   * @return ExportHandler
   */
  public function export(string $id = null)
  {
    $export = new ExportHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($export) return $export;

    return self::export($id ? $id : null);
  }

  /**
   * Learning Locker Personas
   *
   * @param string|null $id
   * @return PersonaHandler
   */
  public function personas(string $id = null)
  {
    $personas = new PersonaHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($personas) return $personas;

    return self::personas($id ? $id : null);
  }

  /**
   * Learning Locker Persona
   *
   * @param string|null $id
   * @return PersonaHandler
   */
  public function persona(string $id = null)
  {
    $persona = new PersonaHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($persona) return $persona;

    return self::persona($id ? $id : null);
  }

  /**
   * Learning Locker Persona Attributes
   *
   * @param string|null $id
   * @return AttributeHandler
   */
  public function personaAttribute(string $id = null)
  {
    $persona_attribute = new AttributeHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($persona_attribute) return $persona_attribute;

    return self::persona($id ? $id : null);
  }

  /**
   * Learning Locker Persona Attributes
   *
   * @param string|null $id
   * @return AttributeHandler
   */
  public function personaAttributes(string $id = null)
  {
    $persona_attribute = new AttributeHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($persona_attribute) return $persona_attribute;

    return self::persona($id ? $id : null);
  }

  /**
   * Learning Locker Persona Attributes
   *
   * @param string|null $id
   * @return IdentifierHandler
   */
  public function personaIdentifier(string $id = null)
  {
    $persona_identifier = new IdentifierHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($persona_identifier) return $persona_identifier;

    return self::personaIdentifier($id ? $id : null);
  }

  /**
   * Learning Locker Journeys
   *
   * @param string|null $id
   * @return JourneyHandler
   */
  public function journeys(string $id = null)
  {
    $journeys = new JourneyHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($journeys) return $journeys;

    return self::journeys($id ? $id : null);
  }

  /**
   * Learning Locker Journey
   *
   * @param string|null $id
   * @return JourneyHandler
   */
  public function journey(string $id = null)
  {
    $journey = new JourneyHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($journey) return $journey;

    return self::journey($id ? $id : null);
  }

  /**
   * Learning Locker Journey Progress
   *
   * @param string|null $id
   * @return ProgressHandler
   */
  public function journeyProgress(string $id = null)
  {
    $journey_progress = new ProgressHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($journey_progress) return $journey_progress;

    return self::journeyProgress($id ? $id : null);
  }

  /**
   * Learning Locker Dashboards
   *
   * @param string|null $id
   * @return DashboardHandler
   */
  public function dashboards(string $id = null)
  {
    $dashboards = new DashboardHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($dashboards) return $dashboards;

    return self::dashboards($id ? $id : null);
  }

  /**
    * Learning Locker Dashboard
   *
   * @param string|null $id
   * @return DashboardHandler
   */
  public function dashboard(string $id = null)
  {
    $dashboard = new DashboardHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($dashboard) return $dashboard;

    return self::dashboard($id ? $id : null);
  }

  /**
   * Learning Locker Downloads
   *
   * @param string|null $id
   * @return DownloasHandler
   */
  public function downloads(string $id = null)
  {
    $downloads = new DownloadHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($downloads) return $downloads;

    return self::downloads($id ? $id : null);
  }

  /**
   * Learning Locker Download
   *
   * @param string|null $id
   * @return DownloadHandler
   */
  public function download(string $id = null)
  {
    $download = new DownloadHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($download) return $download;

    return self::download($id ? $id : null);
  }

  /**
   * Learning Locker Statements
   *
   * @param string|null $id
   * @return StatementHandler
   */
  public function statements(string $id = null)
  {
    $statements = new StatementHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($statements) return $statements;

    return self::statements($id ? $id : null);
  }

  /**
   * Learning Locker Statement
   *
   * @param string|null $id
   * @return StatementHandler
   */
  public function statement(string $id = null)
  {
    $statement = new StatementHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($statement) return $statement;

    return self::statement($id ? $id : null);
  }

  /**
   * Learning Locker Statement Forwarding
   *
   * @param string|null $id
   * @return StatementHandler
   */
  public function statementForwarding(string $id = null)
  {
    $statement_forwarding = new ForwardingHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($statement_forwarding) return $statement_forwarding;

    return self::statementForwarding($id ? $id : null);
  }

  /**
   * Learning Locker Organisation
   *
   * @param string|null $id
   * @return OrganisationHandler
   */
  public function organisations(string $id = null)
  {
    $organisation = new OrganisationHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($organisation) return $organisation;

    return self::organisation($id ? $id : null);
  }

  /**
   * Learning Locker Organisation
   *
   * @param string|null $id
   * @return OrganisationHandler
   */
  public function organisation(string $id = null)
  {
    $organisation = new OrganisationHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($organisation) return $organisation;

    return self::organisation($id ? $id : null);
  }

  /**
   * Learning Locker Visualisations
   *
   * @param string|null $id
   * @return VisualisationHandler
   */
  public function visualisations(string $id = null)
  {
    $visualisations = new VisualisationHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($visualisations) return $visualisations;

    return self::visualisations($id ? $id : null);
  }

  /**
   * Learning Locker Visualisation
   *
   * @param string|null $id
   * @return VisualisationHandler
   */
  public function visualisation(string $id = null)
  {
    $visualisation = new VisualisationHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($visualisation) return $visualisation;

    return self::visualisation($id ? $id : null);
  }

  /**
   * Learning Locker API: Routes
   *
   * @param string|null $type
   */
  public function routes(string $type = null)
  {
    require __DIR__.'/../routes/laralocker.php';
  }
}
