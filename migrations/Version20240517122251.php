<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240517122251 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game_match (id INT AUTO_INCREMENT NOT NULL, season_id INT DEFAULT NULL, sport_id INT DEFAULT NULL, schedule DATETIME NOT NULL, location VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, details VARCHAR(255) DEFAULT NULL, INDEX IDX_4868BC8A4EC001D1 (season_id), INDEX IDX_4868BC8AAC78BCF8 (sport_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, team_id INT DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, number INT NOT NULL, is_player TINYINT(1) NOT NULL, is_teacher TINYINT(1) NOT NULL, INDEX IDX_34DCD176296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE season (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE season_team (season_id INT NOT NULL, team_id INT NOT NULL, INDEX IDX_42A93A814EC001D1 (season_id), INDEX IDX_42A93A81296CD8AE (team_id), PRIMARY KEY(season_id, team_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sport (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, duration INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, sport_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, school VARCHAR(255) NOT NULL, INDEX IDX_C4E0A61FAC78BCF8 (sport_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team_match_game (id INT AUTO_INCREMENT NOT NULL, game_match_id INT DEFAULT NULL, team_id INT DEFAULT NULL, order_number INT NOT NULL, points INT NOT NULL, score INT NOT NULL, INDEX IDX_7C9DF23B81FA53F0 (game_match_id), INDEX IDX_7C9DF23B296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, is_developer TINYINT(1) NOT NULL, is_admin TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game_match ADD CONSTRAINT FK_4868BC8A4EC001D1 FOREIGN KEY (season_id) REFERENCES season (id)');
        $this->addSql('ALTER TABLE game_match ADD CONSTRAINT FK_4868BC8AAC78BCF8 FOREIGN KEY (sport_id) REFERENCES sport (id)');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE season_team ADD CONSTRAINT FK_42A93A814EC001D1 FOREIGN KEY (season_id) REFERENCES season (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE season_team ADD CONSTRAINT FK_42A93A81296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61FAC78BCF8 FOREIGN KEY (sport_id) REFERENCES sport (id)');
        $this->addSql('ALTER TABLE team_match_game ADD CONSTRAINT FK_7C9DF23B81FA53F0 FOREIGN KEY (game_match_id) REFERENCES game_match (id)');
        $this->addSql('ALTER TABLE team_match_game ADD CONSTRAINT FK_7C9DF23B296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_match DROP FOREIGN KEY FK_4868BC8A4EC001D1');
        $this->addSql('ALTER TABLE game_match DROP FOREIGN KEY FK_4868BC8AAC78BCF8');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176296CD8AE');
        $this->addSql('ALTER TABLE season_team DROP FOREIGN KEY FK_42A93A814EC001D1');
        $this->addSql('ALTER TABLE season_team DROP FOREIGN KEY FK_42A93A81296CD8AE');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61FAC78BCF8');
        $this->addSql('ALTER TABLE team_match_game DROP FOREIGN KEY FK_7C9DF23B81FA53F0');
        $this->addSql('ALTER TABLE team_match_game DROP FOREIGN KEY FK_7C9DF23B296CD8AE');
        $this->addSql('DROP TABLE game_match');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE season');
        $this->addSql('DROP TABLE season_team');
        $this->addSql('DROP TABLE sport');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE team_match_game');
        $this->addSql('DROP TABLE user');
    }
}
