<?php

namespace LaravelProjectAnalyzer;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use ReflectionMethod;
use FilesystemIterator;

class ProjectAnalyzer {
    private $rootPath;
    private $analysisReport = [];

    public function __construct(string $projectPath) {
        $this->rootPath = realpath($projectPath);
        if (!$this->rootPath) {
            throw new \Exception("Invalid project path provided");
        }
    }

    public function analyze(): array {
        $this->analysisReport = [
            'project_structure' => $this->analyzeProjectStructure(),
            'routes' => $this->analyzeRoutes(),
            'models' => $this->analyzeModels(),
            'controllers' => $this->analyzeControllers(),
            'migrations' => $this->analyzeMigrations(),
            'views' => $this->analyzeViews(),
            'services' => $this->analyzeServices(),
            'middleware' => $this->analyzeMiddleware(),
            'configuration' => $this->analyzeConfigurationFiles()
        ];

        return $this->analysisReport;
    }

    private function analyzeProjectStructure(): array {
        $structure = [
            'app_directories' => [],
            'total_files' => 0,
            'total_directories' => 0
        ];

        $iterator = new RecursiveDirectoryIterator($this->rootPath, FilesystemIterator::SKIP_DOTS);
        $recursiveIterator = new RecursiveIteratorIterator($iterator);

        foreach ($recursiveIterator as $file) {
            $relativePath = str_replace($this->rootPath . DIRECTORY_SEPARATOR, '', $file->getPath());
            $pathParts = explode(DIRECTORY_SEPARATOR, $relativePath);

            if (!isset($structure['app_directories'][$pathParts[0]])) {
                $structure['app_directories'][$pathParts[0]] = 0;
            }
            $structure['app_directories'][$pathParts[0]]++;
            $structure['total_files']++;
        }

        $structure['total_directories'] = count(glob($this->rootPath . '/*', GLOB_ONLYDIR));

        return $structure;
    }

    private function analyzeRoutes(): array {
        $routesPath = $this->rootPath . '/routes';
        $routes = [];

        $routeFiles = [
            'web.php' => 'Web Routes',
            'api.php' => 'API Routes',
            'console.php' => 'Console Routes',
            'channels.php' => 'Broadcast Channels'
        ];

        foreach ($routeFiles as $filename => $type) {
            $fullPath = $routesPath . '/' . $filename;
            if (file_exists($fullPath)) {
                $content = file_get_contents($fullPath);
                preg_match_all('/Route::(\w+)\([\'"]([^\'"]*)[\'"]\s*,\s*[\[{]?([^}]*)/m', $content, $matches, PREG_SET_ORDER);

                $fileRoutes = [];
                foreach ($matches as $match) {
                    $fileRoutes[] = [
                        'method' => $match[1],
                        'path' => $match[2],
                        'handler' => isset($match[3]) ? trim($match[3]) : null
                    ];
                }

                $routes[$type] = $fileRoutes;
            }
        }

        return $routes;
    }

    private function analyzeModels(): array {
        $modelsPath = $this->rootPath . '/app/Models';
        $models = [];

        if (is_dir($modelsPath)) {
            $modelFiles = glob($modelsPath . '/*.php');
            foreach ($modelFiles as $modelFile) {
                $modelName = basename($modelFile, '.php');
                $models[$modelName] = $this->parseModelFile($modelFile);
            }
        }

        return $models;
    }

    private function parseModelFile($modelFile): array {
        $content = file_get_contents($modelFile);
        
        // Extract relationships
        preg_match_all('/public function\s+(\w+)\(\)\s*{[^}]*->(hasOne|hasMany|belongsTo|belongsToMany|morphOne|morphMany|morphTo)\(/m', $content, $relationshipMatches);
        
        $relationships = [];
        foreach ($relationshipMatches[1] as $index => $methodName) {
            $relationships[] = [
                'name' => $methodName,
                'type' => $relationshipMatches[2][$index]
            ];
        }

        // Extract fillable fields
        preg_match('/protected \$fillable\s*=\s*\[(.*?)\]/s', $content, $fillableMatches);
        $fillableFields = [];
        if (!empty($fillableMatches[1])) {
            $fillableFields = array_map('trim', explode(',', str_replace(['"', "'"], '', $fillableMatches[1])));
            $fillableFields = array_filter($fillableFields);
        }

        // Extract casts
        preg_match('/protected \$casts\s*=\s*\[(.*?)\]/s', $content, $castsMatches);
        $casts = [];
        if (!empty($castsMatches[1])) {
            $castsLines = explode(',', $castsMatches[1]);
            foreach ($castsLines as $line) {
                if (strpos($line, '=>') !== false) {
                    $parts = explode('=>', $line);
                    $key = trim(str_replace(['"', "'"], '', $parts[0]));
                    $value = trim(str_replace(['"', "'"], '', $parts[1]));
                    $casts[$key] = $value;
                }
            }
        }

        return [
            'relationships' => $relationships,
            'fillable_fields' => $fillableFields,
            'casts' => $casts
        ];
    }

    private function analyzeControllers(): array {
        $controllersPath = $this->rootPath . '/app/Http/Controllers';
        $controllers = [];

        if (is_dir($controllersPath)) {
            $controllerFiles = glob($controllersPath . '/*.php');
            foreach ($controllerFiles as $controllerFile) {
                $controllerName = basename($controllerFile, '.php');
                $content = file_get_contents($controllerFile);

                // Extract methods
                preg_match_all('/public function\s+(\w+)\s*\(([^)]*)\)/', $content, $methodMatches);

                $methods = [];
                foreach ($methodMatches[1] as $index => $methodName) {
                    $parameters = array_map('trim', explode(',', $methodMatches[2][$index]));
                    $methods[] = [
                        'name' => $methodName,
                        'parameters' => array_filter($parameters)
                    ];
                }

                $controllers[$controllerName] = [
                    'methods' => $methods
                ];
            }
        }

        return $controllers;
    }

    private function analyzeMigrations(): array {
        $migrationsPath = $this->rootPath . '/database/migrations';
        $migrations = [];

        if (is_dir($migrationsPath)) {
            $migrationFiles = glob($migrationsPath . '/*.php');
            foreach ($migrationFiles as $migrationFile) {
                $content = file_get_contents($migrationFile);
                preg_match_all('/Schema::create\([\'"](\w+)[\'"]\s*,\s*function\s*\(\$table\)\s*{(.*?)}\);/s', $content, $matches, PREG_SET_ORDER);

                foreach ($matches as $match) {
                    $tableName = $match[1];
                    preg_match_all('/\$table->(\w+)\([\'"]?(\w+)[\'"]?\)/', $match[2], $columns, PREG_SET_ORDER);

                    $tableColumns = array_map(function($column) {
                        return [
                            'name' => $column[2],
                            'type' => $column[1]
                        ];
                    }, $columns);

                    $migrations[] = [
                        'table' => $tableName,
                        'columns' => $tableColumns
                    ];
                }
            }
        }

        return $migrations;
    }

    private function analyzeViews(): array {
        $viewsPath = $this->rootPath . '/resources/views';
        $views = [];

        if (is_dir($viewsPath)) {
            $viewFiles = glob($viewsPath . '/**/*.blade.php', GLOB_BRACE);
            foreach ($viewFiles as $viewFile) {
                $relativePath = str_replace($viewsPath . '/', '', $viewFile);
                $content = file_get_contents($viewFile);

                $views[$relativePath] = [
                    'blade_directives' => $this->extractBladeDirectives($content),
                    'variables_used' => $this->extractViewVariables($content)
                ];
            }
        }

        return $views;
    }

    private function extractBladeDirectives($content): array {
        preg_match_all('/@(\w+)/', $content, $matches);
        return array_unique($matches[1]);
    }

    private function extractViewVariables($content): array {
        preg_match_all('/\$(\w+)/', $content, $matches);
        return array_unique($matches[1]);
    }

    private function analyzeServices(): array {
        $servicesPath = $this->rootPath . '/app/Services';
        $services = [];

        if (is_dir($servicesPath)) {
            $serviceFiles = glob($servicesPath . '/*.php');
            foreach ($serviceFiles as $serviceFile) {
                $serviceName = basename($serviceFile, '.php');
                $content = file_get_contents($serviceFile);

                // Extract methods
                preg_match_all('/public function\s+(\w+)\s*\(([^)]*)\)/', $content, $methodMatches);

                $methods = [];
                foreach ($methodMatches[1] as $index => $methodName) {
                    $parameters = array_map('trim', explode(',', $methodMatches[2][$index]));
                    $methods[] = [
                        'name' => $methodName,
                        'parameters' => array_filter($parameters)
                    ];
                }

                $services[$serviceName] = [
                    'methods' => $methods
                ];
            }
        }

        return $services;
    }

    private function analyzeMiddleware(): array {
        $middlewarePath = $this->rootPath . '/app/Http/Middleware';
        $middleware = [];

        if (is_dir($middlewarePath)) {
            $middlewareFiles = glob($middlewarePath . '/*.php');
            foreach ($middlewareFiles as $middlewareFile) {
                $middlewareName = basename($middlewareFile, '.php');
                $content = file_get_contents($middlewareFile);

                // Extract methods
                preg_match_all('/public function\s+(\w+)\s*\(([^)]*)\)/', $content, $methodMatches);

                $methods = [];
                foreach ($methodMatches[1] as $index => $methodName) {
                    $parameters = array_map('trim', explode(',', $methodMatches[2][$index]));
                    $methods[] = [
                        'name' => $methodName,
                        'parameters' => array_filter($parameters)
                    ];
                }

                $middleware[$middlewareName] = [
                    'methods' => $methods
                ];
            }
        }

        return $middleware;
    }

    private function analyzeConfigurationFiles(): array {
        $configPath = $this->rootPath . '/config';
        $configs = [];

        if (is_dir($configPath)) {
            $configFiles = glob($configPath . '/*.php');
            foreach ($configFiles as $configFile) {
                $configName = basename($configFile, '.php');
                
                // Safely parse config file content
                $content = file_get_contents($configFile);
                
                // Extract configuration keys and their values
                preg_match_all("/['\"](.*?)['\"]\s*=>\s*([^,\n]+)/", $content, $matches, PREG_SET_ORDER);
                
                $configDetails = [];
                foreach ($matches as $match) {
                    $key = $match[1];
                    $value = trim($match[2]);
                    
                    // Basic value parsing (simplified)
                    if (in_array(strtolower($value), ['true', 'false', 'null'])) {
                        $value = json_decode(strtolower($value));
                    } elseif (is_numeric($value)) {
                        $value = is_float($value) ? floatval($value) : intval($value);
                    } elseif (preg_match("/^['\"](.*)['\"]/", $value, $stringMatch)) {
                        $value = $stringMatch[1];
                    }
                    
                    $configDetails[$key] = $value;
                }
                
                $configs[$configName] = $configDetails;
            }
        }

        return $configs;
    }

    public function generateReport(): void {
        $report = $this->analyze();
        
        $reportFile = $this->rootPath . '/project_analysis_report.json';
        file_put_contents($reportFile, json_encode($report, JSON_PRETTY_PRINT));
        
        echo "Project analysis complete. Report saved to: $reportFile\n";
    }

    public function printReport(): void {
        $report = $this->analyze();
        
        echo "=== Laravel Project Analysis Report ===\n\n";
        
        echo "1. Project Structure:\n";
        echo "   Total Files: " . $report['project_structure']['total_files'] . "\n";
        echo "   Total Directories: " . $report['project_structure']['total_directories'] . "\n";
        echo "   Directories Breakdown:\n";
        foreach ($report['project_structure']['app_directories'] as $dir => $count) {
            echo "   - $dir: $count files\n";
        }
        
        echo "\n2. Routes Overview:\n";
        foreach ($report['routes'] as $type => $routes) {
            echo "   $type:\n";
            foreach ($routes as $route) {
                echo "   - {$route['method']}: {$route['path']}\n";
            }
        }
        
        echo "\n3. Models Analysis:\n";
        foreach ($report['models'] as $modelName => $modelInfo) {
            echo "   Model: $modelName\n";
            echo "   Relationships:\n";
            foreach ($modelInfo['relationships'] as $relationship) {
                echo "   - {$relationship['name']} ({$relationship['type']})\n";
            }
            echo "   Fillable Fields: " . implode(', ', $modelInfo['fillable_fields']) . "\n";
        }
        
        echo "\n4. Controllers Overview:\n";
        foreach ($report['controllers'] as $controllerName => $controllerInfo) {
            echo "   Controller: $controllerName\n";
            echo "   Methods:\n";
            foreach ($controllerInfo['methods'] as $method) {
                echo "   - {$method['name']}(" . implode(', ', $method['parameters']) . ")\n";
            }
        }
        
        // Additional sections can be added as needed
    }
}

// Usage Example
if (php_sapi_name() === 'cli') {
    $projectPath = getcwd(); // Current directory
    
    $analyzer = new ProjectAnalyzer($projectPath);
    
    // Choose between these methods
    // $analyzer->generateReport(); // Generates a JSON report
    $analyzer->printReport(); // Prints a detailed console report
}