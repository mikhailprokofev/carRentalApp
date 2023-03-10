<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Component\AutoDoc\Enum\RuleFieldTypeEnum;
use Illuminate\Console\Command;

final class AutoDoc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'doc:auto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto Documentation for Swagger';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        \Artisan::call('route:list --json -vvv');
        $allRoutes = json_decode(\Artisan::output(), true);

        $needleRoutes = array_values(
            array_filter(
                $allRoutes,
                function ($current) {
                    return in_array('api', $current['middleware']);
                },
            ),
        );

        $paths = [];

        foreach ($needleRoutes as $curRout) {
            $subject = explode('@', $curRout['action']);
            $controller = $subject[0];
            $method = $subject[1] ?? '__invoke';
            $url = '/' . preg_replace('/\\{.+\\}/', '', $curRout['uri']);
            $httpMethod = strtolower(explode('|', $curRout['method'])[0]);

            $controllerFullName = explode('\\', $controller);
            $tag = array_pop($controllerFullName);

            $reflection = new \ReflectionMethod($controller, $method);
            $params = $reflection->getParameters();
            $attributes = $reflection->getAttributes();

            $curRoutParams = [];

            foreach ($params as $param) {
                $type = (string)$param->getType();
                if (str_contains($type, 'ImportStatusDoneEvent')) {
                    $curRoutParams[] = (object) [
                        'name' => 'id',
                        'in' => 'path',
                        'description' => 'ImportStatusDoneEvent idetifier',
                        'required' => true,
                        'schema' => (object) [
                            'type' => 'string',
                            'format' => 'uuid',
                        ],
                    ];
                    $url .= '{id}';
                }

                if (str_contains($type, 'Request')) {
                    $request = (string) $type;
                    $rules = (new $request())->rules();

                    foreach ($rules as $rule => $rule_desc) {
                        if (is_array($rule_desc)) {
                            $rule_desc = $rule_desc[0];
                        }
                        $curRoutParams[] = (object) [
                            'name' => $rule,
                            'in' => 'query',
                            'description' => "$rule fuild",
                            'required' => str_contains($rule_desc, 'required'),
                            'schema' => (object) [
                                'type' => 'string',
                                'format' => RuleFieldTypeEnum::isEqual($rule_desc),
                            ],
                        ];
                    }
                }
            }

            $summary = 'Set your summary';
            $description = 'Set your description';
            foreach ($attributes as $attribute) {
                $summary = $attribute->getArguments()['summary'] ?? $summary;
                $description = $attribute->getArguments()['description'] ?? $description;
            }

            $paths[$url][$httpMethod] = [
                'tags' => [$tag],
                'summary' => $summary,
                'description' => $description,
                'operationId' => $curRout['name'],
                'responses' => (object) [
                    '200' => (object) [
                        'description' => 'Successful work of server api',
                    ],
                ],
            ];

            if (! empty($curRoutParams)) {
                $paths[$url][$httpMethod]['parameters'] = $curRoutParams;
            }
        }

        $this->setAutoDoc($paths);

        return Command::SUCCESS;
    }

    public function setAutoDoc(array $paths): void
    {
        $json = json_decode(file_get_contents(base_path() . '/storage/api-docs/autodoc.json'), true);
        $json['paths'] = $paths;
        $file = fopen(base_path() . '/storage/api-docs/api-docs.json', 'w') or exit('Unable to open file!');
        fwrite($file, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        fclose($file);
    }
}
