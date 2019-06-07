<?php

namespace Ijeffro\Laralocker;

use Ijeffro\Laralocker\LearningLocker\Connection;
use Ijeffro\Laralocker\LearningLocker\Roles\RoleHandler;
use Ijeffro\Laralocker\LearningLocker\Users\UserHandler;
use Ijeffro\Laralocker\LearningLocker\Stores\StoreHandler;
use Ijeffro\Laralocker\LearningLocker\Queries\QueryHandler;
use Ijeffro\Laralocker\LearningLocker\Client\ClientHandler;
use Ijeffro\Laralocker\LearningLocker\Exports\ExportHandler;
use Ijeffro\Laralocker\LearningLocker\Personas\PersonaHandler;
use Ijeffro\Laralocker\LearningLocker\Journeys\JourneyHandler;
use Ijeffro\Laralocker\LearningLocker\Downloads\DownloadHandler;
use Ijeffro\Laralocker\LearningLocker\Statements\StatementHandler;
use Ijeffro\Laralocker\LearningLocker\Dashboards\DashboardHandler;
use Ijeffro\Laralocker\LearningLocker\Organisation\OrganisationHandler;
use Ijeffro\Laralocker\LearningLocker\Visualisations\VisualisationHandler;
use Ijeffro\Laralocker\LearningLocker\JourneyProgress\JourneyProgressHandler;
use Ijeffro\Laralocker\LearningLocker\StatementForwarding\StatementForwardingHandler;

class LearningLocker {

    public $api;
    public $connection;
    public $aggregation;

    public $user;
    public $users;

    public $role;
    public $roles;

    public $store;
    public $stores;

    public $query;
    public $queries;

    public $export;
    public $exports;

    public $client;
    public $clients;

    public $persona;
    public $personas;

    public $journey;
    public $journeys;

    public $download;
    public $downloads;

    public $dashboard;
    public $dashboards;

    public $statement;
    public $statements;

    public $organisation;
    public $organisations;

    public $visualisation;
    public $visualisations;

    /**
     * Learning Locker API: Clients
     *
     * @return ClientHandler
     */
    public function aggregation()
    {
        if ($this->aggregation) return $this->aggregation;

        $this->aggregation = new AggregationHandler;
        return $this->aggregation();
    }

    /**
     * Learning Locker API: Clients
     *
     * @return ClientHandler
     */
    public function clients()
    {
        if ($this->clients) return $this->clients;

        $this->clients = new ClientHandler;
        return $this->clients();
    }

    /**
     * Learning Locker API: Client
     *
     * @param $id
     * @return ClientHandler
     */
    public function client($id = null)
    {
        if ($this->client) return $this->client;
        $this->client = new ClientHandler($id ? $id : null);
        return $this->client($id ? $id : null);
    }

    /**
     * Learning Locker API: Users
     *
     * @return UserHandler
     */
    public function users()
    {
        if ($this->users) return $this->users;

        $this->users = new UserHandler;
        return $this->users();
    }

    /**
     * Learning Locker API: User
     *
     * @param $id
     * @return UserHandler
     */
    public function user($id = null)
    {
        if ($this->user) return $this->user;

        $this->user = new UserHandler($id ? $id : null);
        return $this->user($id ? $id : null);
    }

    /**
     * Learning Locker API: Roles
     *
     * @return RoleHandler
     */
    public function roles()
    {
        if ($this->roles) return $this->roles;

        $this->roles = new RoleHandler;
        return $this->roles();
    }

    /**
     * Learning Locker API: Role
     *
     * @param $id
     * @return RoleHandler
     */
    public function role($id = null)
    {
        if ($this->role) return $this->role;

        $this->role = new RoleHandler($id ? $id : null);
        return $this->role($id ? $id : null);
    }

    /**
     * Learning Locker API: Stores (LRS)
     *
     * @return StoreHandler
     */
    public function stores()
    {
        if ($this->stores) return $this->stores;

        $this->stores = new StoreHandler;
        return $this->stores();
    }

    /**
     * Learning Locker API: Store (LRS)
     *
     * @param $id
     * @return StoreHandler
     */
    public function store($id = null)
    {
        if ($this->store) return $this->store;

        $this->store = new StoreHandler($id ? $id : null);
        return $this->store($id ? $id : null);
    }

    /**
     * Learning Locker API: Query
     *
     * @return QueryHandler
     */
    public function queries()
    {
        if ($this->queries) return $this->queries;

        $this->queries = new QueryHandler;
        return $this->queries();
    }

    /**
     * Learning Locker API: Query
     *
     * @return QueryHandler
     */
    public function query($id = null)
    {
        if ($this->query) return $this->query;

        $this->query = new QueryHandler($id ? $id : null);
        return $this->query($id ? $id : null);
    }

    /**
     * Learning Locker API: Exports
     *
     * @return ExportHandler
     */
    public function exports()
    {
        if ($this->exports) return $this->exports;

        $this->exports = new ExportHandler;
        return $this->exports();
    }

    /**
     * Learning Locker API: Exports
     *
     * @return ExportHandler
     */
    public function export($id = null)
    {
        if ($this->export) return $this->export;

        $this->export = new ExportHandler($id ? $id : null);
        return $this->export($id ? $id : null);
    }

    /**
     * Learning Locker API: Personas
     *
     * @return PersonaHandler
     */
    public function personas()
    {
        if ($this->personas) return $this->personas;

        $this->personas = new PersonaHandler;
        return $this->personas();
    }

    /**
     * Learning Locker API: Persona
     *
     * @param $id
     * @return PersonaHandler
     */
    public function persona($id = null)
    {
        if ($this->persona) return $this->persona;

        $this->persona = new PersonaHandler($id ? $id : null);
        return $this->persona($id ? $id : null);
    }

    /**
     * Learning Locker API: Journeys
     *
     * @return JourneyHandler
     */
    public function journeys()
    {
        if ($this->journeys) return $this->journeys;

        $this->journeys = new JourneyHandler;
        return $this->journeys();
    }

    /**
     * Learning Locker API: Journey
     *
     * @param $id
     * @return JourneyHandler
     */
    public function journey($id = null)
    {
        if ($this->journey) return $this->journey;

        $this->journey = new JourneyHandler($id ? $id : null);
        return $this->journey($id ? $id : null);
    }

    /**
     * Learning Locker API: Journey
     *
     * @param $id
     * @return JourneyProgressHandler
     */
    public function journeyProgress($id = null)
    {
        if ($this->journey_progress) return $this->journey_progress;

        $this->journey_progress = new JourneyProgressHandler($id ? $id : null);
        return $this->journeyProgress($id ? $id : null);
    }

    /**
     * Learning Locker API: Dashboards API
     *
     * @return DashboardHandler
     */
    public function dashboards()
    {
        if ($this->dashboards) return $this->dashboards;

        $this->dashboards = new DashboardHandler;
        return $this->dashboards();
    }

    /**
     * Learning Locker API: Dashboards API
     *
     * @param $id
     * @return DashboardHandler
     */
    public function dashboard($id = null)
    {
        if ($this->dashboard) return $this->dashboard;

        $this->dashboard = new DashboardHandler($id ? $id : null);
        return $this->dashboard($id ? $id : null);
    }

    /**
     * Learning Locker API: Downloads
     *
     * @return DownloadsHandler
     */
    public function downloads()
    {
        if ($this->downloads) return $this->downloads;

        $this->downloads = new DownloadHandler;
        return $this->downloads();
    }

    /**
     * Learning Locker API: Downloads
     *
     * @return DownloadsHandler
     */
    public function download($id)
    {
        if ($this->download) return $this->download;

        $this->download = new DownloadHandler($id);
        return $this->download($id);
    }

    /**
     * Learning Locker API: Statements
     *
     * @return StatementHandler
     */
    public function statements()
    {
        if ($this->statements) return $this->statements;

        $this->statements = new StatementHandler;
        return $this->statements();
    }

    /**
     * Learning Locker API: Statement
     *
     * @param $id
     * @return StatementHandler
     */
    public function statement($id = null)
    {
        if ($this->statement) return $this->statement;

        $this->statement = new StatementHandler($id ?? $id);
        return $this->statement($id ?? $id);
    }

    /**
     * Learning Locker API: Organisation
     *
     * @param $id
     * @return OrganisationHandler
     */
    public function organisation($id)
    {
        if ($this->organisation) return $this->organisation;

        $this->organisation = new OrganisationHandler($id ?? $id);
        return $this->organisation();

    }

    /**
     * Learning Locker API: Visualisations API
     *
     * @return VisualisationHandler
     */
    public function visualisations()
    {
        if ($this->visualisations) return $this->visualisations;

        $this->visualisations = new VisualisationHandler;
        return $this->visualisations();
    }

    /**
     * Learning Locker API: Visualisations API
     *
     * @param $id
     * @return VisualisationHandler
     */
    public function visualisation($id = null)
    {
        if ($this->visualisation) return $this->visualisation;

        $this->visualisation = new VisualisationHandler($id ? $id : null);
        return $this->visualisation($id ? $id : null);
    }

    /**
     * Learning Locker API: Routes
     *
     */
    public function routes()
    {
        require __DIR__.'/../routes/laralocker.php';
    }

    /**
     * Learning Locker API: Model Access
     *
     * @param $model, $id = null
     */
    public function api($model, $id = null) {

        switch (strtolower($model)) {
            case "clients":
                $this->clients();
                break;
            case "client":
                $this->client($id);
                break;
            case "user" || "users":
                $this->users();
                break;
            case "stores" || "store" || "lrs":
                $this->stores();
                break;
            case "query" || "queries":
                $this->query();
                break;
            case "export" || "exports":
                $this->exports();
                break;
            case "export" || "exports":
                $this->personas();
                break;
            case "journey" || "journeys":
                $this->journeys();
                break;
            case "dashboard" || "dashboards":
                $this->dashboards();
                break;
            case "download" || "downloads":
                $this->downloads();
                break;
            case "statement" || "statements":
                $this->statements();
                break;
            case "organisation" || "organisations" || "org":
                $this->organisations();
                break;
            case "visualisation" || "visualisations" || "visualise":
                $this->visualisations();
                break;
            default:
                return error(
                    "Please supply an api model; user, organisation, store, etc. You can refer to the learning locker api documents for more details"
                );
        }
    }

    /**
     * Learning Locker API: Connect
     *
     * @return Connection
     */
    public function connect($key, $secret, $url)
    {
        if ($this->connection) return $this;

        $this->connection = new Connection($key, $secret, $url);
        return $this->connect($key, $secret, $url);
    }

}
