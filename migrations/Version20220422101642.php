<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220422101642 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD county VARCHAR(255) DEFAULT NULL, ADD street VARCHAR(255) DEFAULT NULL, ADD street_number VARCHAR(10) DEFAULT NULL, ADD bloc VARCHAR(10) DEFAULT NULL, ADD scara VARCHAR(10) DEFAULT NULL, ADD etaj VARCHAR(5) DEFAULT NULL, ADD apart VARCHAR(5) DEFAULT NULL, ADD cam VARCHAR(5) DEFAULT NULL, ADD sector VARCHAR(1) DEFAULT NULL, ADD comuna VARCHAR(255) DEFAULT NULL, ADD sat VARCHAR(255) DEFAULT NULL, ADD other VARCHAR(255) DEFAULT NULL, ADD address_full VARCHAR(255) DEFAULT NULL, DROP address, DROP state');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD address VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD state VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP county, DROP street, DROP street_number, DROP bloc, DROP scara, DROP etaj, DROP apart, DROP cam, DROP sector, DROP comuna, DROP sat, DROP other, DROP address_full');
    }
}
