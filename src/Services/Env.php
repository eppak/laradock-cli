<?php namespace Eppak\Services;


use Eppak\Contracts\Runnable;
use Eppak\Exceptions\FileNotFoundException;
use Eppak\Exceptions\PathNotFoundException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


/**
 * (c) Alessandro Cappellozza <alessandro.cappellozza@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */
class Env implements Runnable
{
    private $error;
    /**
     * @var Configuration
     */
    private $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function configure($path): bool
    {
        $env = "{$path}/{$this->configuration->folder()}/.env";
        $envExample = "{$path}/{$this->configuration->folder()}/env-example";

        try {

            if (!File::exists($path)) {
                throw new PathNotFoundException($path);
            }

            if (!File::exists($envExample)) {
                throw new FileNotFoundException('env-example');
            }

            File::move($envExample, $env);

            $content = $this->replace(File::get($env), [
                "PHP_VERSION" => $this->configuration->php(),
                "COMPOSE_PROJECT_NAME" => $this->configuration->name(),
                "MYSQL_VERSION" => $this->configuration->mysql()
            ]);

            File::put($env, $content);

            return true;

        } catch (\Exception $e) {
            $this->error = $e->getMessage();

            return false;
        }
    }

    public function error(): ?string
    {
        return "ERROR: {$this->error}";
    }

    private function replace(string $content, array $values): string
    {
        $result = [];
        $lines = explode("\n", $content);

        foreach ($lines as $line) {
            $result[] = $this->line($line, $values);
        }

        return join("\n", $result);
    }

    private function line(string $line, array $values): string
    {
        foreach ($values as $key => $value) {
            if (Str::startsWith($line, "{$key}=")) {
                return "{$key}={$value}";
            }
        }

        return $line;
    }
}
