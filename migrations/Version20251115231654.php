<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251115231654 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE archive (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', matter_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', box_number VARCHAR(255) NOT NULL, room VARCHAR(255) NOT NULL, cabinet VARCHAR(255) NOT NULL, shelf VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, notes LONGTEXT DEFAULT NULL, archived_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_D5FC5D9CD614E59F (matter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE case_type (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', full_name VARCHAR(255) NOT NULL, identification_number VARCHAR(255) NOT NULL, client_type VARCHAR(255) NOT NULL, identification_type VARCHAR(255) NOT NULL, company_name VARCHAR(255) DEFAULT NULL, registration_number VARCHAR(255) DEFAULT NULL, authorized_representative_name VARCHAR(255) DEFAULT NULL, authorized_representative_phone VARCHAR(255) DEFAULT NULL, authorized_representative_email VARCHAR(255) DEFAULT NULL, primary_phone VARCHAR(255) NOT NULL, secondary_phone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, residential_address LONGTEXT DEFAULT NULL, postal_address LONGTEXT DEFAULT NULL, physical_address LONGTEXT DEFAULT NULL, notes LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE communication_log (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', client_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', matter_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', communication_type VARCHAR(255) NOT NULL, subject VARCHAR(255) NOT NULL, summary LONGTEXT NOT NULL, communication_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', duration_minutes INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_ED41616319EB6921 (client_id), INDEX IDX_ED416163D614E59F (matter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', client_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_D8698A7619EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE file_movement (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', matter_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', from_user_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', to_user_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', movement_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', from_location VARCHAR(255) NOT NULL, to_location VARCHAR(255) NOT NULL, purpose LONGTEXT DEFAULT NULL, notes LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_458D1A6AD614E59F (matter_id), INDEX IDX_458D1A6A2130303A (from_user_id), INDEX IDX_458D1A6A29F6EE60 (to_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matter (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', case_type_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', lead_lawyer_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', secretary_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', file_number VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, filing_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', closing_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', notes LONGTEXT NOT NULL, status_type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_B0DE9B6F6AEF7998 (case_type_id), INDEX IDX_B0DE9B6FD5BAFAA7 (lead_lawyer_id), INDEX IDX_B0DE9B6FA2A63DB2 (secretary_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matter_client (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', matter_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', client_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', client_role VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_1F9FB6F8D614E59F (matter_id), INDEX IDX_1F9FB6F819EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matter_lawyer (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', matter_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', lawyer_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', role_type VARCHAR(255) NOT NULL, unassigned_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_B934C6DAD614E59F (matter_id), INDEX IDX_B934C6DA4C19F89F (lawyer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matter_update (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', matter_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', update_type VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, event_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_40FE87D5D614E59F (matter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', matter_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', assigned_to_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, priority VARCHAR(255) NOT NULL, task_status_type VARCHAR(255) NOT NULL, due_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', completed_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_527EDB25D614E59F (matter_id), INDEX IDX_527EDB25F4BD7827 (assigned_to_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE archive ADD CONSTRAINT FK_D5FC5D9CD614E59F FOREIGN KEY (matter_id) REFERENCES matter (id)');
        $this->addSql('ALTER TABLE communication_log ADD CONSTRAINT FK_ED41616319EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE communication_log ADD CONSTRAINT FK_ED416163D614E59F FOREIGN KEY (matter_id) REFERENCES matter (id)');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A7619EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE file_movement ADD CONSTRAINT FK_458D1A6AD614E59F FOREIGN KEY (matter_id) REFERENCES matter (id)');
        $this->addSql('ALTER TABLE file_movement ADD CONSTRAINT FK_458D1A6A2130303A FOREIGN KEY (from_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE file_movement ADD CONSTRAINT FK_458D1A6A29F6EE60 FOREIGN KEY (to_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE matter ADD CONSTRAINT FK_B0DE9B6F6AEF7998 FOREIGN KEY (case_type_id) REFERENCES case_type (id)');
        $this->addSql('ALTER TABLE matter ADD CONSTRAINT FK_B0DE9B6FD5BAFAA7 FOREIGN KEY (lead_lawyer_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE matter ADD CONSTRAINT FK_B0DE9B6FA2A63DB2 FOREIGN KEY (secretary_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE matter_client ADD CONSTRAINT FK_1F9FB6F8D614E59F FOREIGN KEY (matter_id) REFERENCES matter (id)');
        $this->addSql('ALTER TABLE matter_client ADD CONSTRAINT FK_1F9FB6F819EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE matter_lawyer ADD CONSTRAINT FK_B934C6DAD614E59F FOREIGN KEY (matter_id) REFERENCES matter (id)');
        $this->addSql('ALTER TABLE matter_lawyer ADD CONSTRAINT FK_B934C6DA4C19F89F FOREIGN KEY (lawyer_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE matter_update ADD CONSTRAINT FK_40FE87D5D614E59F FOREIGN KEY (matter_id) REFERENCES matter (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25D614E59F FOREIGN KEY (matter_id) REFERENCES matter (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25F4BD7827 FOREIGN KEY (assigned_to_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE archive DROP FOREIGN KEY FK_D5FC5D9CD614E59F');
        $this->addSql('ALTER TABLE communication_log DROP FOREIGN KEY FK_ED41616319EB6921');
        $this->addSql('ALTER TABLE communication_log DROP FOREIGN KEY FK_ED416163D614E59F');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A7619EB6921');
        $this->addSql('ALTER TABLE file_movement DROP FOREIGN KEY FK_458D1A6AD614E59F');
        $this->addSql('ALTER TABLE file_movement DROP FOREIGN KEY FK_458D1A6A2130303A');
        $this->addSql('ALTER TABLE file_movement DROP FOREIGN KEY FK_458D1A6A29F6EE60');
        $this->addSql('ALTER TABLE matter DROP FOREIGN KEY FK_B0DE9B6F6AEF7998');
        $this->addSql('ALTER TABLE matter DROP FOREIGN KEY FK_B0DE9B6FD5BAFAA7');
        $this->addSql('ALTER TABLE matter DROP FOREIGN KEY FK_B0DE9B6FA2A63DB2');
        $this->addSql('ALTER TABLE matter_client DROP FOREIGN KEY FK_1F9FB6F8D614E59F');
        $this->addSql('ALTER TABLE matter_client DROP FOREIGN KEY FK_1F9FB6F819EB6921');
        $this->addSql('ALTER TABLE matter_lawyer DROP FOREIGN KEY FK_B934C6DAD614E59F');
        $this->addSql('ALTER TABLE matter_lawyer DROP FOREIGN KEY FK_B934C6DA4C19F89F');
        $this->addSql('ALTER TABLE matter_update DROP FOREIGN KEY FK_40FE87D5D614E59F');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25D614E59F');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25F4BD7827');
        $this->addSql('DROP TABLE archive');
        $this->addSql('DROP TABLE case_type');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE communication_log');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE file_movement');
        $this->addSql('DROP TABLE matter');
        $this->addSql('DROP TABLE matter_client');
        $this->addSql('DROP TABLE matter_lawyer');
        $this->addSql('DROP TABLE matter_update');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
