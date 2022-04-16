<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220416061224 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE descriptor (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', content LONGTEXT NOT NULL, language VARCHAR(3) NOT NULL, creation_date_time DATETIME NOT NULL, update_date_time DATETIME NOT NULL, type VARCHAR(20) NOT NULL, name LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE email (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name LONGTEXT NOT NULL, type VARCHAR(20) NOT NULL, creation_date_time DATETIME NOT NULL, update_date_time DATETIME NOT NULL, enable TINYINT(1) DEFAULT \'1\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE email_content_join (descriptor_ref CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', parent_descriptor CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_B010D341790F4212 (descriptor_ref), INDEX IDX_B010D34144D1F252 (parent_descriptor), PRIMARY KEY(descriptor_ref, parent_descriptor)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE email_subject_join (descriptor_ref CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', parent_descriptor CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_EFB8BA5F790F4212 (descriptor_ref), INDEX IDX_EFB8BA5F44D1F252 (parent_descriptor), PRIMARY KEY(descriptor_ref, parent_descriptor)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jwt (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', user CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', type VARCHAR(20) NOT NULL, creation_date_time DATETIME NOT NULL, expiration_date_time DATETIME NOT NULL, used TINYINT(1) NOT NULL, INDEX IDX_8D17CDF08D93D649 (user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE privacy (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name LONGTEXT NOT NULL, type VARCHAR(20) NOT NULL, language VARCHAR(3) NOT NULL, creation_date_time DATETIME NOT NULL, update_date_time DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE privacy_flag (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', user_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, mandatory TINYINT(1) NOT NULL, required_yes_to_booking TINYINT(1) NOT NULL, required_yes_to_send_email TINYINT(1) NOT NULL, checked TINYINT(1) DEFAULT NULL, atl_id INT NOT NULL, consent_date_time DATETIME DEFAULT NULL, INDEX IDX_975BFF84A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE settings (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', category VARCHAR(20) DEFAULT NULL, value LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', name VARCHAR(200) NOT NULL, description VARCHAR(60) DEFAULT NULL, responsive VARCHAR(255) DEFAULT NULL, controls LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', weight SMALLINT UNSIGNED NOT NULL, type VARCHAR(15) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', api_token VARCHAR(255) DEFAULT NULL, enabled TINYINT(1) NOT NULL, active TINYINT(1) NOT NULL, email VARCHAR(180) NOT NULL, username VARCHAR(50) DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, first_name VARCHAR(50) DEFAULT NULL, last_name VARCHAR(50) DEFAULT NULL, vat_code VARCHAR(20) DEFAULT NULL, tax_code VARCHAR(20) DEFAULT NULL, unique_code VARCHAR(60) DEFAULT NULL, business_name VARCHAR(255) DEFAULT NULL, pec VARCHAR(180) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, province VARCHAR(255) DEFAULT NULL, state VARCHAR(255) DEFAULT NULL, zip_code VARCHAR(10) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, mobile_phone VARCHAR(20) DEFAULT NULL, birth_place VARCHAR(255) DEFAULT NULL, birth_date DATETIME DEFAULT NULL, sex VARCHAR(1) DEFAULT NULL, client_code VARCHAR(255) DEFAULT NULL, public_administration VARCHAR(255) DEFAULT NULL, cig VARCHAR(255) DEFAULT NULL, cup VARCHAR(255) DEFAULT NULL, is_agency TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D6497BA2F5EB (api_token), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649A689F3FA (client_code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE email_content_join ADD CONSTRAINT FK_B010D341790F4212 FOREIGN KEY (descriptor_ref) REFERENCES email (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE email_content_join ADD CONSTRAINT FK_B010D34144D1F252 FOREIGN KEY (parent_descriptor) REFERENCES descriptor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE email_subject_join ADD CONSTRAINT FK_EFB8BA5F790F4212 FOREIGN KEY (descriptor_ref) REFERENCES email (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE email_subject_join ADD CONSTRAINT FK_EFB8BA5F44D1F252 FOREIGN KEY (parent_descriptor) REFERENCES descriptor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jwt ADD CONSTRAINT FK_8D17CDF08D93D649 FOREIGN KEY (user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE privacy_flag ADD CONSTRAINT FK_975BFF84A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE email_content_join DROP FOREIGN KEY FK_B010D34144D1F252');
        $this->addSql('ALTER TABLE email_subject_join DROP FOREIGN KEY FK_EFB8BA5F44D1F252');
        $this->addSql('ALTER TABLE email_content_join DROP FOREIGN KEY FK_B010D341790F4212');
        $this->addSql('ALTER TABLE email_subject_join DROP FOREIGN KEY FK_EFB8BA5F790F4212');
        $this->addSql('ALTER TABLE jwt DROP FOREIGN KEY FK_8D17CDF08D93D649');
        $this->addSql('ALTER TABLE privacy_flag DROP FOREIGN KEY FK_975BFF84A76ED395');
        $this->addSql('DROP TABLE descriptor');
        $this->addSql('DROP TABLE email');
        $this->addSql('DROP TABLE email_content_join');
        $this->addSql('DROP TABLE email_subject_join');
        $this->addSql('DROP TABLE jwt');
        $this->addSql('DROP TABLE privacy');
        $this->addSql('DROP TABLE privacy_flag');
        $this->addSql('DROP TABLE settings');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
