<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\Migrations\Version\Version;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200531124845 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql(
            'insert into users set email=:email, password=:password, name=:name, phone=:phone, created_at=CURRENT_TIMESTAMP, updated_at=CURRENT_TIMESTAMP',
            [
                'email' => 'test@email.com',
                'password' => password_hash('123456', PASSWORD_ARGON2ID),
                'name' => 'John Doe',
                'phone' => '+87453945345',
            ]
        );

        $this->addSql(
            'insert into users set email=:email, password=:password, name=:name, phone=:phone, created_at=CURRENT_TIMESTAMP, updated_at=CURRENT_TIMESTAMP',
            [
                'email' => 'test2@email.com',
                'password' => password_hash('123123', PASSWORD_ARGON2ID),
                'name' => 'Steve Jobs',
                'phone' => '+90753945345',
            ]
        );
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
