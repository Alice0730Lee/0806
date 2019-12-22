<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190828031510 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user CHANGE content content varchar(200) not null, CHANGE name name varchar(40) not null, CHANGE date date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        // $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, content varchar(200) not null, name varchar(40) not null, date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reply (rid INT AUTO_INCREMENT NOT NULL, r_content varchar(200) not null, r_name varchar(40) not null, uid INT NOT NULL, r_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY(rid)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE reply CHANGE r_content r_content VARCHAR(200) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE r_name r_name VARCHAR(40) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE r_date r_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE content content VARCHAR(200) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE name name VARCHAR(40) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE date date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        // $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE reply');
    }
}
