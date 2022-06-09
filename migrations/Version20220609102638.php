<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220609102638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE card_skill (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, skill_id INT NOT NULL, love TINYINT(1) NOT NULL, stars INT DEFAULT NULL, INDEX IDX_660915B1A76ED395 (user_id), INDEX IDX_660915B15585C142 (skill_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE card_skill ADD CONSTRAINT FK_660915B1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE card_skill ADD CONSTRAINT FK_660915B15585C142 FOREIGN KEY (skill_id) REFERENCES skill (id)');
        // $this->addSql('ALTER TABLE skill DROP rate');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE card_skill');
        $this->addSql('ALTER TABLE skill ADD rate INT DEFAULT NULL');
    }
}
