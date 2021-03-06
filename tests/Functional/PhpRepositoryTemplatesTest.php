<?php

namespace CodePrimer\Tests\Functional;

use CodePrimer\Adapter\RelationalDatabaseAdapter;
use CodePrimer\Template\Artifact;

/**
 * Class PhpRepositoryTemplatesTest.
 *
 * @group functional
 */
class PhpRepositoryTemplatesTest extends TemplateTestCase
{
    const DOCTRINE_ORM_EXPECTED_DIR = self::EXPECTED_DIR.'/code/php/repository/DoctrineOrmRepository/';

    /**
     * @throws \Exception
     */
    public function testDoctrineOrmRepositoryTemplate()
    {
        $this->initEntities();

        self::assertCount(6, $this->businessBundle->getBusinessModels());

        $artifact = new Artifact(Artifact::CODE, 'Repository', 'php', 'doctrineOrm');

        // Extract the template to use for this artifact
        $template = $this->templateRegistry->getTemplateForArtifact($artifact);
        self::assertNotNull($template);

        // Extract the builder to use for this artifact
        $builder = $this->factory->createBuilder($artifact);
        self::assertNotNull($builder);

        // Prepare the entities for Doctrine ORM
        $adapter = new RelationalDatabaseAdapter();
        $adapter->generateRelationalFields($this->businessBundle);

        // Build the artifacts
        $builder->build($this->businessBundle, $template, $this->renderer);

        // Make sure the right files have been generated
        $this->assertGeneratedFile('gen-src/Repository/UserRepository.php', self::DOCTRINE_ORM_EXPECTED_DIR);
        $this->assertGeneratedFile('gen-src/Repository/UserStatsRepository.php', self::DOCTRINE_ORM_EXPECTED_DIR);
        $this->assertGeneratedFile('gen-src/Repository/MetadataRepository.php', self::DOCTRINE_ORM_EXPECTED_DIR);
        $this->assertGeneratedFile('gen-src/Repository/PostRepository.php', self::DOCTRINE_ORM_EXPECTED_DIR);
        $this->assertGeneratedFile('gen-src/Repository/TopicRepository.php', self::DOCTRINE_ORM_EXPECTED_DIR);
        $this->assertGeneratedFile('gen-src/Repository/SubscriptionRepository.php', self::DOCTRINE_ORM_EXPECTED_DIR);
    }
}
