<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231202132650 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, auteur VARCHAR(255) NOT NULL, contenu VARCHAR(255) NOT NULL, note VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cartefidelite (user_id INT DEFAULT NULL, IdCarte INT AUTO_INCREMENT NOT NULL, PtsFidelite INT NOT NULL, DateDebut DATE NOT NULL, DateFin DATE NOT NULL, EtatCarte VARCHAR(9) NOT NULL, NiveauCarte VARCHAR(9) NOT NULL, UNIQUE INDEX UNIQ_CAC25E04A76ED395 (user_id), PRIMARY KEY(IdCarte)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, eventssaif_id INT DEFAULT NULL, user_name VARCHAR(30) NOT NULL, context VARCHAR(255) NOT NULL, id_event INT NOT NULL, INDEX IDX_5F9E962ABBA5B640 (eventssaif_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE eventssaif (id INT AUTO_INCREMENT NOT NULL, destinationsaif VARCHAR(20) DEFAULT NULL, titlesaif VARCHAR(20) DEFAULT NULL, descriptionsaif VARCHAR(20) DEFAULT NULL, durationsaif VARCHAR(20) DEFAULT NULL, imagesaif VARCHAR(20) DEFAULT NULL, prixsaif INT DEFAULT NULL, typesaif VARCHAR(30) DEFAULT NULL, rating DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offrespecialevenment (IdOffreSpecialEvenment INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date_depart DATE NOT NULL, prix DOUBLE PRECISION NOT NULL, categorie VARCHAR(255) NOT NULL, guide_id VARCHAR(255) NOT NULL, destination VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, niveau VARCHAR(9) NOT NULL, PRIMARY KEY(IdOffreSpecialEvenment)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, payment_id VARCHAR(255) NOT NULL, payer_id VARCHAR(255) NOT NULL, payer_email VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, currency VARCHAR(255) NOT NULL, parchased_at DATETIME NOT NULL, payment_statut VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ratingsaif (id INT AUTO_INCREMENT NOT NULL, event_r_id INT DEFAULT NULL, id_user INT DEFAULT NULL, value_raiting INT DEFAULT NULL, INDEX IDX_558741F966B9E782 (event_r_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, event_id INT NOT NULL, nb_adults INT DEFAULT NULL, nb_kids INT DEFAULT NULL, prix_r INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, Role ENUM(\'ADMIN\', \'GUIDE\', \'CLIENT\'), cartefidelite_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, motDePasse VARCHAR(255) NOT NULL, numeroTelephone VARCHAR(255) NOT NULL, dateNaissance VARCHAR(255) NOT NULL, genre VARCHAR(255) NOT NULL, is_verified TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cartefidelite ADD CONSTRAINT FK_CAC25E04A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962ABBA5B640 FOREIGN KEY (eventssaif_id) REFERENCES eventssaif (id)');
        $this->addSql('ALTER TABLE ratingsaif ADD CONSTRAINT FK_558741F966B9E782 FOREIGN KEY (event_r_id) REFERENCES eventssaif (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cartefidelite DROP FOREIGN KEY FK_CAC25E04A76ED395');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962ABBA5B640');
        $this->addSql('ALTER TABLE ratingsaif DROP FOREIGN KEY FK_558741F966B9E782');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE cartefidelite');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE eventssaif');
        $this->addSql('DROP TABLE offrespecialevenment');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE ratingsaif');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
