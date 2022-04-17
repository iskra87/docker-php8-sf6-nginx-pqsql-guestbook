<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220415042511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
CREATE TABLE conference (
    id INT NOT NULL,
     city VARCHAR(255) NOT NULL, 
     year VARCHAR(255) NOT NULL, 
     is_international BOOLEAN NOT NULL,
      PRIMARY KEY(id)
                        )
SQL
        );
        $this->addSql(<<<SQL
CREATE TABLE comment (
    id INT NOT NULL, 
    conference_id INT NOT NULL, 
    author VARCHAR(255) NOT NULL, 
    text TEXT NOT NULL, 
    email VARCHAR(255) NOT NULL, 
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
    photo_filename VARCHAR(255) DEFAULT NULL, 
    PRIMARY KEY(id)
                     )
SQL
        );
        $this->addSql('CREATE SEQUENCE comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE conference_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE INDEX IDX_9474526C604B8382 ON comment (conference_id)');
        $this->addSql('COMMENT ON COLUMN comment.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C604B8382 FOREIGN KEY (conference_id) REFERENCES conference (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C604B8382');
        $this->addSql('DROP SEQUENCE comment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE conference_id_seq CASCADE');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE conference');
    }
}
