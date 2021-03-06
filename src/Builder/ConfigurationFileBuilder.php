<?php

namespace CodePrimer\Builder;

use CodePrimer\Model\BusinessBundle;
use CodePrimer\Renderer\TemplateRenderer;
use CodePrimer\Template\Template;
use Doctrine\Inflector\InflectorFactory;

class ConfigurationFileBuilder implements ArtifactBuilder
{
    const CODEPRIMER = 'codeprimer';
    const GITHUB = 'github';
    const PHP_CS_FIXER = 'php cs fixer';
    const GITIGNORE = 'gitignore';

    public function build(BusinessBundle $businessBundle, Template $template, TemplateRenderer $renderer): array
    {
        $artifact = $template->getArtifact();
        $filename = $artifact->getVariant();
        $variant = strtolower($artifact->getVariant());
        if (self::PHP_CS_FIXER == $variant) {
            $filename = '.php_cs';
        } elseif (self::GITIGNORE == $variant) {
            $filename = '.gitignore';
        }

        $type = strtolower($artifact->getType());
        if (self::CODEPRIMER == $type) {
            $filename = 'codeprimer/'.$filename;
        } elseif (self::GITHUB == $type) {
            $filename = '.github/workflows/'.$filename;
        }

        $project = [];

        $inflector = InflectorFactory::create()->build();
        $project['name'] = $inflector->classify($businessBundle->getName());

        $context = [
            'project' => $project,
            'package' => $businessBundle,
            'bundle' => $businessBundle,
        ];

        $file = $renderer->renderToFile($filename, $businessBundle, $template, $context);

        return [$file];
    }
}
