<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251006090456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rbac_permissions (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, tree_left INT NOT NULL, tree_right INT NOT NULL, INDEX IDX_F4AB496C727ACA70 (parent_id), INDEX IDX_F4AB496C77153098C5E48D5FCC85371 (code, tree_left, tree_right), UNIQUE INDEX UNIQ_F4AB496C77153098727ACA70 (code, parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rbac_roles (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, tree_left INT NOT NULL, tree_right INT NOT NULL, INDEX IDX_48780154727ACA70 (parent_id), INDEX IDX_4878015477153098C5E48D5FCC85371 (code, tree_left, tree_right), UNIQUE INDEX UNIQ_4878015477153098727ACA70 (code, parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role_permission (role_id INT NOT NULL, permission_id INT NOT NULL, INDEX IDX_6F7DF886D60322AC (role_id), INDEX IDX_6F7DF886FED90CCA (permission_id), PRIMARY KEY(role_id, permission_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rbac_permissions ADD CONSTRAINT FK_F4AB496C727ACA70 FOREIGN KEY (parent_id) REFERENCES rbac_permissions (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rbac_roles ADD CONSTRAINT FK_48780154727ACA70 FOREIGN KEY (parent_id) REFERENCES rbac_roles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_permission ADD CONSTRAINT FK_6F7DF886D60322AC FOREIGN KEY (role_id) REFERENCES rbac_roles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_permission ADD CONSTRAINT FK_6F7DF886FED90CCA FOREIGN KEY (permission_id) REFERENCES rbac_permissions (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rbac_permissions DROP FOREIGN KEY FK_F4AB496C727ACA70');
        $this->addSql('ALTER TABLE rbac_roles DROP FOREIGN KEY FK_48780154727ACA70');
        $this->addSql('ALTER TABLE role_permission DROP FOREIGN KEY FK_6F7DF886D60322AC');
        $this->addSql('ALTER TABLE role_permission DROP FOREIGN KEY FK_6F7DF886FED90CCA');
        $this->addSql('DROP TABLE rbac_permissions');
        $this->addSql('DROP TABLE rbac_roles');
        $this->addSql('DROP TABLE role_permission');
    }
}
