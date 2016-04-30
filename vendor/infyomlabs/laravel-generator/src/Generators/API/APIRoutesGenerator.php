<?php

namespace InfyOm\Generator\Generators\API;

use Illuminate\Support\Str;
use InfyOm\Generator\Common\CommandData;
use InfyOm\Generator\Generators\BaseGenerator;
use InfyOm\Generator\Utils\TemplateUtil;

class APIRoutesGenerator extends BaseGenerator
{
    /** @var CommandData */
    private $commandData;

    /** @var string */
    private $path;

    /** @var string */
    private $routeContents;

    /** @var string */
    private $routesTemplate;

    public function __construct(CommandData $commandData)
    {
        $this->commandData = $commandData;
        $this->path = $commandData->config->pathApiRoutes;

        $this->routeContents = file_get_contents($this->path);

        $routesTemplate = TemplateUtil::getTemplate('api.routes.routes', 'laravel-generator');

        $this->routesTemplate = TemplateUtil::fillTemplate($this->commandData->dynamicVars, $routesTemplate);
    }

    public function generate()
    {
        $this->routeContents .= "\n\n".$this->routesTemplate;

        file_put_contents($this->path, $this->routeContents);

        $this->commandData->commandComment("\n".$this->commandData->config->mCamelPlural.' api routes added.');
    }

    public function rollback()
    {
        if (Str::contains($this->routeContents, $this->routesTemplate)) {
            $this->commandData->commandComment('api routes deleted');
        }
    }
}
