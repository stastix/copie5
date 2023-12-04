<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231204175746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE demande (id INT AUTO_INCREMENT NOT NULL, use_name_id INT DEFAULT NULL, destination VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, duration INT NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_2694D7A58F0517EF (use_name_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande_user (demande_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_EA36A49C80E95E18 (demande_id), INDEX IDX_EA36A49CA76ED395 (user_id), PRIMARY KEY(demande_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, use_name_id INT DEFAULT NULL, text_reclamation VARCHAR(255) DEFAULT NULL, cible_reclamation VARCHAR(255) DEFAULT NULL, date_reclamation DATE NOT NULL, etat_reclamation VARCHAR(255) DEFAULT NULL, INDEX IDX_CE6064048F0517EF (use_name_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A58F0517EF FOREIGN KEY (use_name_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE demande_user ADD CONSTRAINT FK_EA36A49C80E95E18 FOREIGN KEY (demande_id) REFERENCES demande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demande_user ADD CONSTRAINT FK_EA36A49CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064048F0517EF FOREIGN KEY (use_name_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user CHANGE Role Role ENUM(\'ADMIN\', \'GUIDE\', \'CLIENT\')');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A58F0517EF');
        $this->addSql('ALTER TABLE demande_user DROP FOREIGN KEY FK_EA36A49C80E95E18');
        $this->addSql('ALTER TABLE demande_user DROP FOREIGN KEY FK_EA36A49CA76ED395');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064048F0517EF');
        $this->addSql('DROP TABLE demande');
        $this->addSql('DROP TABLE demande_user');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('ALTER TABLE user CHANGE Role Role VARCHAR(255) DEFAULT NULL');
    }
}
