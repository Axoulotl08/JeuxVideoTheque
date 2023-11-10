<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230511184343 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, state_id INT NOT NULL, console_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, start_date DATE DEFAULT NULL, finish_date DATE DEFAULT NULL, box_game SMALLINT NOT NULL, trophy_pourcentage INT DEFAULT NULL, game_time INT DEFAULT NULL, sale_date DATE NOT NULL, update_date DATE DEFAULT NULL, creation_date DATE NOT NULL, INDEX IDX_232B318C5D83CC1 (state_id), INDEX IDX_232B318C72F9DD9F (console_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C5D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C72F9DD9F FOREIGN KEY (console_id) REFERENCES console (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C5D83CC1');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C72F9DD9F');
        $this->addSql('DROP TABLE game');
    }
}
