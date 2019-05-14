<?php
namespace LaraLocker;

use Arrilot\Widgets\Facade as Widget;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use TCG\Voyager\Actions\DeleteAction;
use TCG\Voyager\Actions\EditAction;
use TCG\Voyager\Actions\RestoreAction;
use TCG\Voyager\Actions\ViewAction;
use TCG\Voyager\Events\AlertsCollection;
use TCG\Voyager\FormFields\After\HandlerInterface as AfterHandlerInterface;
use TCG\Voyager\FormFields\HandlerInterface;
use TCG\Voyager\Models\Category;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;
use TCG\Voyager\Models\Page;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Post;
use TCG\Voyager\Models\Role;
use TCG\Voyager\Models\Setting;
use TCG\Voyager\Models\Translation;
use TCG\Voyager\Models\User;
use TCG\Voyager\Traits\Translatable;

class LaraLocker
{
    protected $version;
    protected $filesystem;
    protected $actions = [
        DeleteAction::class,
        RestoreAction::class,
        EditAction::class,
        ViewAction::class,
    ];
    protected $models = [
        'Organisation'=> Oragnisation::class,
        'User'        => User::class,
    ];

    public function __construct()
    {
        $this->filesystem = app(Filesystem::class);
        $this->findVersion();
    }
    public function model($name)
    {
        return app($this->models[studly_case($name)]);
    }
    public function modelClass($name)
    {
        return $this->models[$name];
    }
    public function useModel($name, $object)
    {
        if (is_string($object)) {
            $object = app($object);
        }
        $class = get_class($object);
        if (isset($this->models[studly_case($name)]) && !$object instanceof $this->models[studly_case($name)]) {
            throw new \Exception("[{$class}] must be instance of [{$this->models[studly_case($name)]}].");
        }
        $this->models[studly_case($name)] = $class;
        return $this;
    }
    public function view($name, array $parameters = [])
    {
        foreach (Arr::get($this->viewLoadingEvents, $name, []) as $event) {
            $event($name, $parameters);
        }
        return view($name, $parameters);
    }
    public function onLoadingView($name, \Closure $closure)
    {
        if (!isset($this->viewLoadingEvents[$name])) {
            $this->viewLoadingEvents[$name] = [];
        }
        $this->viewLoadingEvents[$name][] = $closure;
    }

    public function routes()
    {
        require __DIR__.'/../routes/laralocker.php';
    }

    public function getVersion()
    {
        return $this->version;
    }
    public function addAlert(Alert $alert)
    {
        $this->alerts[] = $alert;
    }
    public function alerts()
    {
        if (!$this->alertsCollected) {
            event(new AlertsCollection($this->alerts));
            $this->alertsCollected = true;
        }
        return $this->alerts;
    }
    protected function findVersion()
    {
        if (!is_null($this->version)) {
            return;
        }
        if ($this->filesystem->exists(base_path('composer.lock'))) {
            // Get the composer.lock file
            $file = json_decode(
                $this->filesystem->get(base_path('composer.lock'))
            );
            // Loop through all the packages and get the version of voyager
            foreach ($file->packages as $package) {
                if ($package->name == 'tcg/voyager') {
                    $this->version = $package->version;
                    break;
                }
            }
        }
    }

}