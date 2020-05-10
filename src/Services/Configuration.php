<?php namespace Eppak\Services;

use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Yaml\Yaml;
use TypeError;

/**
 * (c) Alessandro Cappellozza <alessandro.cappellozza@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */
class Configuration
{
    private $configuration = [];

    public function __construct()
    {
        if (!env('DISABLE_CONFIG') || env('DISABLE_CONFIG') == null) {
            $filename = $this->from();

            $this->load($filename);
        }
    }

    public function folder(): string
    {
        if (!array_key_exists('folder', $this->configuration)) {
            return "laradock";
        }

        return $this->configuration['laradock'];
    }

    public function php(): string
    {
        if (!array_key_exists('php', $this->configuration)) {
            return "7.4";
        }

        return $this->configuration['php'];
    }

    public function mysql(): string
    {
        if (!array_key_exists('mysql', $this->configuration)) {
            return "5.7";
        }

        return $this->configuration['mysql'];
    }

    public function name(): string
    {
        if (!array_key_exists('name', $this->configuration)) {
            return "laradock-cli";
        }

        return $this->configuration['name'];
    }

    public function from(): ?string
    {
        $cwd = getcwd();
        $home = userHome(APP_PATH);

        $path = "{$cwd}/" . APP_CONFIG;
        if (File::exists($path)) {
            Log::info("Config file is {$path}");

            return $path;
        }

        $path = "{$home}/" . APP_CONFIG;
        if (File::exists($path)) {
            Log::info("Config file is {$path}");

            return $path;
        }

        Log::info("Config file not present");
        return null;
    }

    public function load($filename)
    {
        try {
            if (!File::exists($filename)) {
                return;
            }

            $this->configuration = Yaml::parseFile($filename);

            if ($this->configuration == null) {
                $this->configuration = [];
            }

        } catch (TypeError $e) {
            Log::error("Configuration parse error: {$e->getMessage()}");
            $this->configuration = [];

        } catch (Exception $e) {
            Log::error("Configuration parse error: {$e->getMessage()}");
            $this->configuration = [];
        }
    }
}
