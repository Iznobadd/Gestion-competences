<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220608114418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mission_user (mission_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_A4D17A46BE6CAE90 (mission_id), INDEX IDX_A4D17A46A76ED395 (user_id), PRIMARY KEY(mission_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mission_user ADD CONSTRAINT FK_A4D17A46BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mission_user ADD CONSTRAINT FK_A4D17A46A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649BE6CAE90');
        $this->addSql('DROP INDEX IDX_8D93D649BE6CAE90 ON user');
        $this->addSql('ALTER TABLE user DROP mission_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE mission_user');
        $this->addSql('ALTER TABLE user ADD mission_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649BE6CAE90 ON user (mission_id)');
    }
}
