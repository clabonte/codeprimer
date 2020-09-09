<?php
/*
 * This file has been generated by CodePrimer.io
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CodePrimer\Tests\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Class Version00000000000001
 * This is the initial migration used to create the MySQL-compatible database for the application
 * @package CodePrimer\Tests\Migration
 */
final class Version00000000000001 extends AbstractMigration
{
    const SQL_MIGRATIONS_DIR = __DIR__ . '/../../migrations/';

    public function getDescription() : string
    {
        return 'Initial migration used to configure the MySQL-compatible database created by CodePrimer';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $migration = file_get_contents(self::SQL_MIGRATIONS_DIR . 'CreateDatabase.sql');
        $sections = explode('-- TRIGGERS START', $migration);

        // Execute regular statements
        $statements = explode(';', $sections[0]);
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement)) {
                $this->addSql($statement);
            }
        }

        // Execute triggers statements
        $statements = explode('//', $sections[1]);
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement) && (strpos($statement, 'DELIMITER') !== 0)) {
                $this->addSql($statement);
            }
        }
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $statements = explode(';', file_get_contents(self::SQL_MIGRATIONS_DIR . 'RevertDatabase.sql'));
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement)) {
                $this->addSql($statement);
            }
        }
    }

}