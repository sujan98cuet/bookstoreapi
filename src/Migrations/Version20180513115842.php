<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180513115842 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE Labels');
        $this->addSql('ALTER TABLE Books DROP added_on, DROP isbn, DROP title, CHANGE id id INT AUTO_INCREMENT NOT NULL, ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Labels (id INT NOT NULL, name VARCHAR(255) NOT NULL COLLATE latin1_swedish_ci) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE books MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE books DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE books ADD added_on DATE NOT NULL, ADD isbn VARCHAR(250) NOT NULL COLLATE latin1_swedish_ci, ADD title VARCHAR(250) NOT NULL COLLATE latin1_swedish_ci, CHANGE id id INT NOT NULL');
    }
}
